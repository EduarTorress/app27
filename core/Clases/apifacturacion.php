<?php

namespace Core\Clases;
//use Core\Clases\signa  Clases\signature;
use Core\Clases\Signature;
use Core\Clases\conexion;
use Core\Clases\Envio;
use Core\Foundation\Application;
use DOMDocument;
use Exception;
use ZipArchive;

class apifacturacion
{
    public string $entidad;
    public function enviarcomprobanteElectronico(
        $emisor,
        $nombrexml,
        $nidauto,
        $rutacertificado = "certificados/",
        $rutaxml = "xml/",
        $rutacdr = "cdr/"
    ) {
        $obj = new Signature();
        $flgfirma = 0;
        $rutaxml = 'xml/' . $emisor['ruc'] . '/' . $nombrexml . '.xml';
        $nombrearchivoxml = $nombrexml . '.xml';
        $rutafirma = $rutacertificado . $emisor['ruc'] . '/' . $emisor['certificado'];
        $passfirma = trim($emisor['clavecertificado']);

        $obj->signature_xml($flgfirma, $rutaxml, $rutafirma, $passfirma);

        //echo "</br> Firmado";

        $zip = new ZipArchive();
        $rutazip = 'xml/' . $emisor['ruc'] . '/' . $nombrexml . '.zip';
        if ($zip->open($rutazip, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($rutaxml, $nombrexml . '.xml');
            $zip->close();
        }

        //echo "</br> Comprimido";
        $nombre_archivo_zip = $nombrexml . '.ZIP';
        $contenidozip = base64_encode(file_get_contents($rutazip));

        //echo "contenidp zip".$contenidozip;
        //https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl
        $app = Application::getInstance();
        $ws = $app->urlenvio;



        $xml_envio = '<soapenv:Envelope xmlns:ser="http://service.sunat.gob.pe" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <soapenv:Header>
            <wsse:Security>
                <wsse:UsernameToken>
                    <wsse:Username>' . $emisor['ruc'] . $emisor['usuario_secundario'] . '</wsse:Username>
                    <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                </wsse:UsernameToken>
            </wsse:Security>
        </soapenv:Header>
        <soapenv:Body>
            <ser:sendBill>
                <fileName>' . $nombre_archivo_zip . '</fileName>
                <contentFile>' . $contenidozip . '</contentFile>
            </ser:sendBill>
        </soapenv:Body>
        </soapenv:Envelope>';

        //  echo "</br>".htmlspecialchars($xml_envio);
        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: " . strlen($xml_envio)
        );

        $ch = curl_init(); //iniciar la llamada
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //
        curl_setopt($ch, CURLOPT_URL, $ws);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //para ejecutar los procesos de forma local en windows
        //enlace de descarga del cacert.pem: https://curl.haxx.se/docs/caextract.html
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo funciona en local, si en cambio estas en el servidor web con ssl comentar esta línea

        //ENVIO WS - SUNAT - FIN

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //echo "</br> HTTP Codigo" . $httpcode;

        //echo "</br> Respuesta".$response;

        if ($httpcode == 200) {
            $doc = new DOMDocument();
            $doc->loadXML($response);
            if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)) {
                $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                $cdr = base64_decode($cdr);
                file_put_contents($rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombre_archivo_zip, $cdr);
                $zip = new ZipArchive();
                if ($zip->open($rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombre_archivo_zip) === TRUE) {
                    $nombrecdr = $rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombrexml . '.xml';
                    $zip->extractTo($rutacdr . $emisor['ruc'] . '/', 'R-' . $nombrexml . '.xml');
                    $zip->close();
                }
                //   echo "</br> CDR Descomprimido   " . $nombrecdr;
                $car = $rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombrexml . '.xml';
                if (file_exists($car)) {
                    $xml = simplexml_load_file($car);
                  //  if (isset($xml->cbc)) {
                        $cbc = $xml->children('cbc', TRUE);
                        $note = $cbc->Note;
                        $cac = $xml->children('cac', TRUE);
                        $response = $cac->DocumentResponse->Response;
                        $status = $response->Status;
                        $descri = $response->children('cbc', TRUE)->Description;
                        $estado = $response->children('cbc', TRUE)->ResponseCode;
                        echo  "</br>" . $estado . ' ' . $descri;
                        $crpta = $estado . ' ' . $descri;
                        if ($estado === '0' or $estado == '0') {
                            $cxml = file_get_contents($rutaxml);
                            $cdr = file_get_contents($nombrecdr);
                            $oenvio = new envio();
                            $oenvio->xml = $cxml;
                            $oenvio->cdr = $cdr;
                            $oenvio->idauto = $nidauto;
                            $oenvio->crpta = $crpta;
                            $oenvio->nombrexml = $nombrearchivoxml;
                            $oenvio->ActualizaenvioCpe('T');
                        }
                  // else {
                  //      echo "</br>" .  ' Error al leer XML';
                  //  }
                }else{
                    echo "</br>" .  ' No se Pudo Obtener el XML de Respuesta de los servidores';
                }
            } else {
                if (isset($doc->getElementsByTagName('faultstrimg')->item(0)->nodeValue)) {
                    $codigoerror = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                    $mensajeerror = $doc->getElementsByTagName('faultsring')->item(0)->nodeValue;
                    echo "</br>" . $codigoerror . ' ' . $mensajeerror;
                } else {
                    echo "</br> " . "Respuesta" . $response;
                }
            }
        } else {
            echo curl_error($ch);
            echo "</br>" . " Error al Conectar 500";
        }

        curl_close($ch);
    }
    public function ConsultarTicket(
        $emisor,
        $ticket,
        $nombrexml,
        $detalle,
        $rutaxml = "xml/",
        $rutacdr = "cdr/"
    ) {
        //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
        //$ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService";

        $app = Application::getInstance();
        $ws = $app->urlenvio;
        //$nombre	= $emisor["ruc"]."-".$cabecera["tipodoc"]."-".$cabecera["serie"]."-".$cabecera["correlativo"];
        $nombre_xml    = $nombrexml . '.xml';
        $ctipoarchivo = substr(pathinfo($nombre_xml, PATHINFO_FILENAME), 13, 2);
        $xml_envio = '<soapenv:Envelope xmlns:ser="http://service.sunat.gob.pe" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <soapenv:Header>
            <wsse:Security>
                <wsse:UsernameToken>
                    <wsse:Username>' . $emisor['rucempresa'] . $emisor['usuario_secundario'] . '</wsse:Username>
                    <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                </wsse:UsernameToken>
            </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatus>
                    <ticket>' . $ticket . '</ticket>
                </ser:getStatus>
            </soapenv:Body>
        </soapenv:Envelope>';

        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: " . strlen($xml_envio)
        );

        try {
            $respuesta = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $ws);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // echo "codigo:" . $httpcode;
            // $cmensaje1 = $httpcode;
            if ($httpcode == 200) {
                $doc = new DOMDocument();
                $doc->loadXML($response);

                if (isset($doc->getElementsByTagName('content')->item(0)->nodeValue)) {
                    $cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);
                    $zip = new ZipArchive;
                    $nombrezip = $nombrexml . '.ZIP';


                    file_put_contents($rutacdr . $emisor['rucempresa'] . '/' . 'R-' . $nombrezip, $cdr);

                    if ($zip->open($rutacdr . $emisor['rucempresa'] . '/' . 'R-' . $nombrezip) === TRUE) {
                        $zip->extractTo($rutacdr . $emisor['rucempresa'] . '/', 'R-' . $nombre_xml);
                        $zip->close();
                    }
                    $car = $_ENV['DIR_ROOT'] . \DIRECTORY_SEPARATOR . "public" . \DIRECTORY_SEPARATOR . 'cdr' . \DIRECTORY_SEPARATOR . $emisor['rucempresa'] . \DIRECTORY_SEPARATOR . 'R-' . $nombre_xml;
                    //echo "<br>".$_ENV['DIR_ROOT'].\DIRECTORY_SEPARATOR."public".\DIRECTORY_SEPARATOR.$car;
                    //$car=$rutacdr
                    $xml = simplexml_load_file($car);
                    // if (isset($xml->cbc)){
                    $cbc = $xml->children('cbc', TRUE);
                    $note = $cbc->Note;
                    $cac = $xml->children('cac', TRUE);
                    $response = $cac->DocumentResponse->Response;
                    $status = $response->Status;
                    $descri = $response->children('cbc', TRUE)->Description;
                    $estado = $response->children('cbc', TRUE)->ResponseCode;
                    $crpta = $estado . ' ' . $descri;
                    $cdr = file_get_contents($car);
                    //   echo  "</br>  por  aqui!!!" .$estado .' '. $descri;   
                    if ($estado == '0') {
                        $respuestaticket = [
                            "estado" => '0',
                            "mensaje" => $estado . ' ' . $descri,
                            "cdr" => $cdr
                        ];
                    } else {
                        $respuestaticket = [
                            "estado" => '4',
                            "mensaje" => $estado . ' ' . $descri,
                            "cdr" => ""
                        ];
                    }

                    // $crpta = $estado . ' ' . $descri;
                    // $cmensaje1 = $estado . ' ' . $descri;
                    // echo "<br>" . $cmensaje1;
                    // // $cxml=file_get_contents($rutaxml);
                    // $cdr = file_get_contents($car);
                    // $oenvio = new envio();
                    // $oenvio->crpta = $crpta;
                    // $oenvio->cdr = $cdr;
                    // $oenvio->GrabaCDRTicket(trim($ticket), $detalle);
                    // }else{
                    //   echo '<br>'. 'No se puede cargar el Archivo';
                    // $cmensaje1='Al Parecer ya esta enviado';
                    // }

                } else {
                    $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                    $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                    // echo "error " . $codigo . ": " . $mensaje;
                    $cmensaje1 = "error " . $codigo . ": " . $mensaje;
                    $respuestaticket = [
                        "estado" => '1',
                        "mensaje" => "error " . $codigo . ": " . $mensaje,
                        "cdr" => ""
                    ];
                }
            } else {
                // echo curl_error($ch);
                // echo "Problema de conexión";
                //  $cmensaje1 = "Problema de conexión " . curl_error($ch);
                $respuestaticket = [
                    "estado" => '2',
                    "mensaje" => "Error de respuesta " . strval($httpcode) . ' ' . curl_error($ch),
                    "cdr" => ""
                ];
            }
            curl_close($ch);
        } catch (Exception $e) {
            $respuestaticket = [
                "estado" => '3',
                "mensaje" => "Error " . $e->getMessage(),
                "cdr" => ""
            ];
        }
        return $respuestaticket;
    }
    function consultarComprobante($emisor, $rutacdr = "cdr/")
    {
        try {
            //$ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";



            //https://e-factura.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl
            //https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService
            $app = Application::getInstance();
            $ws = $app->urlconsulta;

            $xml_post_string = '<soapenv:Envelope xmlns:ser="http://service.sunat.gob.pe"
                xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <soapenv:Header>
                <wsse:Security>
                <wsse:UsernameToken>
                <wsse:Username>' . trim($emisor['ruc']) . trim($emisor['usuario_secundario']) . '</wsse:Username>
                <wsse:Password>' . trim($emisor['clave_usuario_secundario']) . '</wsse:Password>
                </wsse:UsernameToken>
                </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                <ser:getStatusCdr>
                <rucComprobante>' . $emisor['ruc'] . '</rucComprobante>
                <tipoComprobante>' . $emisor['tdoc'] . '</tipoComprobante>
                <serieComprobante>' . $emisor['serie'] . '</serieComprobante>
                <numeroComprobante>' . $emisor['numero'] . '</numeroComprobante>
                </ser:getStatusCdr>
                </soapenv:Body>
                </soapenv:Envelope>';
            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-length: " . strlen($xml_post_string),
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $ws);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            //  echo '</br>'.$emisor['serie'].$emisor['numero'];
            $nombre_archivo_zip = $emisor['archivo'] . '.zip';




            if ($httpcode == 200) {
                $doc = new DOMDocument();

                $doc->loadXML($response);
                if (isset($doc->getElementsByTagName('content')->item(0)->nodeValue)) {
                    $cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
                    $cdr = base64_decode($cdr);
                    file_put_contents($rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombre_archivo_zip, $cdr);
                    $zip = new ZipArchive();
                    if ($zip->open($rutacdr . $emisor['ruc'] . '/' . 'R-' . $nombre_archivo_zip) === TRUE) {
                        $nombrecdr = $rutacdr . $emisor['ruc'] . '/' . 'R-' . $emisor['archivo'] . '.xml';
                        $zip->extractTo($rutacdr . $emisor['ruc'] . '/', 'R-' . $emisor['archivo'] . '.xml');
                        $zip->close();
                    }
                    //   echo "</br> CDR Descomprimido   ";
                    $car = $rutacdr . $emisor['ruc'] . '/' . 'R-' . $emisor['archivo'] . '.xml';
                    $xml = simplexml_load_file($car);
                    $cbc = $xml->children('cbc', TRUE);
                    $note = $cbc->Note;
                    $cac = $xml->children('cac', TRUE);
                    $response = $cac->DocumentResponse->Response;
                    $status = $response->Status;
                    $descri = $response->children('cbc', TRUE)->Description;
                    $estado = $response->children('cbc', TRUE)->ResponseCode;

                    $crpta = $estado . ' ' . $descri;
                    echo  "</br>" . $estado . ' ' . $descri;
                    if ($estado == '0') {
                        $cdr = file_get_contents($nombrecdr);
                        $oenvio = new envio();
                        $oenvio->empresa = $emisor['empresa'];
                        $oenvio->cdr = $cdr;
                        $oenvio->idauto = $emisor['idauto'];
                        $oenvio->crpta = $crpta;
                        //  echo  "</br>" . $estado . ' ' . $descri;
                        $oenvio->ActualizaenvioCpe('C');
                    }
                } else {
                    if (isset($doc->getElementsByTagName('faultstrimg')->item(0)->nodeValue)) {
                        $codigoerror = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                        $mensajeerror = $doc->getElementsByTagName('faultsring')->item(0)->nodeValue;
                        echo "</br>" . $codigoerror . ' ' . $mensajeerror;
                    } else {
                        echo "</br> " . "Respuesta" . $response;
                        echo "</br>" . $emisor['empresa'] . ' ' . $emisor['serie'] . ' ' . $emisor['numero'];
                    }
                }
            }
        } catch (Exception $e) {
            echo "SUNAT ESTA FUERA SERVICIO: " . $e->getMessage();
        }
    }
    public function EnviarResumenComprobantes($emisor, $nombrexml, $resumen, $detalle, $rutacertificado = "certificados/", $ruta_archivo_xml = "xml/")
    {
        //FIRMAR XML DIGITALMENTE - INICIO

        $objFirma = new Signature();

        $flg_firma = 0; //Posicion donde se firma en el XML
        //$ruta_xml_firmar = $ruta . '.XML';
        $rutaxml = 'xml/' . $emisor['rucempresa'] . '/' . $nombrexml . '.xml';
        $rutafirma = $rutacertificado . $emisor['rucempresa'] . '/' . $emisor['certificado'];
        $passfirma = trim($emisor['clavecertificado']);
        $objFirma->signature_xml($flg_firma, $rutaxml, $rutafirma, $passfirma);
        $nombrearchivoxml = $nombrexml . '.xml';

        //echo '</br>PASO 02: XML FIRMADO DIGITALMENTE CON EXITO.';


        $zip = new ZipArchive();
        $rutazip = 'xml/' . $emisor['rucempresa'] . '/' . $nombrexml . '.zip';
        if ($zip->open($rutazip, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($rutaxml, $nombrexml . '.xml');
            $zip->close();
        }

        //echo "</br> Comprimido";
        $nombre_archivo_zip = $nombrexml . '.zip';


        //echo '</br> PASO 03: XML COMPRIMIDO EN FORMATO .ZIP CON EXITO.Ok';
        //COMPRIMIR XML EN FORMATO .ZIP - FIN

        //CODIFICAR .ZIP EN BASE 64 - INICIO
        //  $nombre_archivo_zip = $nombre . '.ZIP';
        $contenido_del_zip = base64_encode(file_get_contents($rutazip));

        //echo '</br> ' . $contenido_del_zip;
        //echo '</br> PASO 04: ARCHIVO ZIP CODIFICADO EN BASE 64 CORRECTAMENTE ';

        //CODIFICAR .ZIP EN BASE 64 - FIN

        //ENVIO WS - SUNAT - INICIO

        // $ws = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
        $app = Application::getInstance();

        $ws = $app->urlenvio;

        $xml_envio = '<soapenv:Envelope xmlns:ser="http://service.sunat.gob.pe" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <soapenv:Header>
            <wsse:Security>
                <wsse:UsernameToken>
                    <wsse:Username>' . $emisor['rucempresa'] . $emisor['usuario_secundario'] . '</wsse:Username>
                    <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                </wsse:UsernameToken>
            </wsse:Security>
        </soapenv:Header>
        <soapenv:Body>
            <ser:sendSummary>
                <fileName>' . $nombre_archivo_zip . '</fileName>
                <contentFile>' . $contenido_del_zip . '</contentFile>
            </ser:sendSummary>
        </soapenv:Body>
    </soapenv:Envelope>';
        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: " . strlen($xml_envio)
        );
        try {
            $respuesta = array();
            $ch = curl_init(); //iniciar la llamada
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //
            curl_setopt($ch, CURLOPT_URL, $ws);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem: https://curl.haxx.se/docs/caextract.html
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo funciona en local, si en cambio estas en el servidor web con ssl comentar esta línea

            $response = curl_exec($ch); //ejecuto y obtengo rpta
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            //echo '</br> PASO 05: CONSUMO DE WS - SUNAT. CODIGO ESTADO: ' . $httpcode . ' RESPUESTA: ' . $response;

            $estadoofe = '0'; //0: No se enviado a SUNAT; 1: Exito, 2: Error, 3: Problema de conexión
            $ticket = '0';

            if ($httpcode == 200) { //Exito tuve rpta
                $doc = new DOMDocument();
                $doc->loadXML($response);

                if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) {
                    // echo '</br> PASO 06: OBTUVE EL NRO DE TICKET';
                    // echo '</br>'.$emisor['rucempresa'];
                    $ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
                    $respuesta = [
                        "estado" => "0",
                        "nombrexml" => $nombrearchivoxml,
                        "xml" => file_get_contents($rutaxml),
                        "ticket" => $ticket,
                        "mensaje" => "Ticket " . $ticket
                    ];
                    //  return $respuesta;
                    // $oenvio = new envio();
                    // $oenvio->nombrexml = $nombrearchivoxml;
                    // $oenvio->xml = file_get_contents($rutaxml);
                    // $oenvio->RegistraResumenEnvioBoletas($resumen, $ticket);
                    // $this->ConsultarTicket($emisor, $ticket, $nombrexml, $detalle);
                } else {
                    // $estadoofe = '2';
                    if (isset($doc->getElementsByTagName('faulcode')->item(0)->nodeValue)) {
                        $codigo = $doc->getElementsByTagName('faulcode')->item(0)->nodeValue;
                        $mensaje = $doc->getElementsByTagName('faulstring')->item(0)->nodeValue;
                        // echo '</br> Error: ' . $codigo . ' Mensaje: ' . $mensaje;
                        $respuesta = array(
                            "estado" => "2",
                            "nombrexml" => "",
                            "xml" => "",
                            "ticket" => "",
                            "mensaje" => $codigo . ' Mensaje: ' . $mensaje
                        );
                    } else //error problema de conexion
                    {
                        // $estadoofe = '3';
                        // echo curl_error($ch);
                        // echo '</br>' . $response;
                        // echo '</br> PROBLEMA DE CONEXION';
                        $respuesta = [
                            "estado" => "3",
                            "nombrexml" => "",
                            "xml" => "",
                            "ticket" => "",
                            "mensaje" => ' Problemas de Conexión: ' . $response
                        ];
                    }
                }
            }
            curl_close($ch);
        } catch (Exception $e) {
            $respuesta = [
                "estado" => "4",
                "nombrexml" => "",
                "xml" => "",
                "ticket" => "",
                "mensaje" => ' Error: ' . $e->getMessage()
            ];
        }
        return $respuesta;
    }
}
