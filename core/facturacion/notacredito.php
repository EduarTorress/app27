<?php


//Creacion de arrays - INICIO
$emisor = array(
    'tipodoc'           =>      '6', //RUC: 6, DNI: 1
    'ruc'               =>      '20480529244',
    'razon_social'      =>      'EMPRESA EMISORA SA',
    'nombre_comercial'  =>      'EMISORA FE SA',
    'direccion'         =>      'CALLE REAL 123',
    'pais'              =>      'PE',
    'departamento'      =>      'LIMA',
    'provincia'         =>      'LIMA',
    'distrito'          =>      'LIMA',
    'ubigeo'            =>      '010101',
    'usuario_secundario'        =>     'MODDATOS',
    'clave_usuario_secundario'  =>      'MODDATOS'
);

$cliente = array(
    'tipodoc'           =>      '6',
    'ruc'               =>      '10123456789',
    'razon_social'      =>      'PEPITO PEREZ',
    'direccion'         =>      'PUERTO PALMERAS 658',
    'pais'              =>      'PE'
);

$comprobante = array(
    'tipodoc'           =>          '07',//FA: 01, BV:03, NC: 07, ND: 08
    'serie'             =>          'FN01', //DOnde la primera letra o digito debe ser F: FACTURAS, B: Boletas, los 3 digitos o caracteres siguientes son asignados por el negocio.
    'correlativo'       =>          '12345',
    'fecha_emision'     =>          '2021-05-12', //YYYY-MM-DD
    'moneda'            =>          'PEN', //Soles: PEN, Dolares: USD
    'total_opgravadas'  =>          0,
    'total_opexoneradas'    =>      0,
    'total_opinafectas' =>          0,
    'igv'               =>          0,
    'total'             =>          0,
    'total_texto'       =>          '',
    'serie_ref'         =>          'F001',
    'correlativo_ref'   =>          "12545",
    'tipodoc_ref'       =>          '01',
    'codmotivo'         =>          '01', //Catalogo anexo No 9
    'descripcion'       =>          'Anulación de la operación'

);

$detalle = array(
    array(
        'item'                        =>      1,
        'codigo'                      =>      'COD001',
        'descripcion'                 =>      'ACEITE',
        'cantidad'                    =>      1,
        'valor_unitario'              =>      50, //Sin IGV
        'precio_unitario'             =>      59, //Con IGV
        'tipo_precio'                 =>      '01',
        'igv'                         =>      9,
        'porcentaje_igv'              =>      18,
        'valor_total'                 =>      50,
        'importe_total'               =>      59,
        'unidad'                      =>      'NIU', //UInidad de medida
        'codigo_afectacion_alt'       =>      '10', //Gravadado : 10, EXonerado: 20, Inafecto: 30
        'codigo_afectacion'           =>      1000,
        'nombre_afectacion'           =>      'IGV',
        'tipo_afectacion'             =>      'VAT'
    ),
    array(
        'item'                        =>      2,
        'codigo'                      =>      'COD002',
        'descripcion'                 =>      'LIBRO COQUITO',
        'cantidad'                    =>      2,
        'valor_unitario'              =>      50, //Sin IGV
        'precio_unitario'             =>      50, //Con IGV
        'tipo_precio'                 =>      '01',
        'igv'                         =>      0,
        'porcentaje_igv'              =>      18,
        'valor_total'                 =>      100,
        'importe_total'               =>      100,
        'unidad'                      =>      'NIU', //UInidad de medida
        'codigo_afectacion_alt'       =>      '20', //Gravadado : 10, EXonerado: 20, Inafecto: 30
        'codigo_afectacion'           =>      9997,
        'nombre_afectacion'           =>      'EXO',
        'tipo_afectacion'             =>      'VAT'
    ),
    array(
        'item'                        =>      3,
        'codigo'                      =>      'COD003',
        'descripcion'                 =>      'TOMATE',
        'cantidad'                    =>      2,
        'valor_unitario'              =>      50, //Sin IGV
        'precio_unitario'             =>      50, //Con IGV
        'tipo_precio'                 =>      '01',
        'igv'                         =>      0,
        'porcentaje_igv'              =>      18,
        'valor_total'                 =>      100,
        'importe_total'               =>      100,
        'unidad'                      =>      'NIU', //UInidad de medida
        'codigo_afectacion_alt'       =>      '30', //Gravadado : 10, EXonerado: 20, Inafecto: 30
        'codigo_afectacion'           =>      9998,
        'nombre_afectacion'           =>      'INA',
        'tipo_afectacion'             =>      'FRE'
    ),
);

//Creacion de arrays - FIN

//Inicializar los totals de la factura - INICIO
$op_gravadas = 0;
$op_inafectas = 0;
$op_exoneradas = 0;
$igv = 0;
$total = 0;
//Inicializar los totals de la factura - FIN

//Recorrer detalle y calcular totales - INICIO

foreach ($detalle as $k => $v) {
    if($v['codigo_afectacion_alt'] == '10') //Operaciones Gravadas
    {
        $op_gravadas = $op_gravadas + $v['valor_total'];
    }
    if($v['codigo_afectacion_alt'] == '20') //Operaciones Exoneradas
    {
        $op_exoneradas = $op_exoneradas + $v['valor_total'];
    }
    if($v['codigo_afectacion_alt'] == '30') //Operaciones Inafectas
    {
        $op_inafectas = $op_inafectas + $v['valor_total'];
    }

    $igv = $igv + $v['igv'];
    $total = $total + $v['importe_total'];
}
//Recorrer detalle y calcular totales - FIN


//Asignar totales al array comprobante
$comprobante['total_opgravadas'] = $op_gravadas;
$comprobante['total_opexoneradas']  = $op_exoneradas;
$comprobante['total_opinafectas']   = $op_inafectas;
$comprobante['igv'] = $igv;
$comprobante['total']   = $total;

require_once('cantidad_en_letras.php');
$comprobante['total_texto'] = CantidadEnLetra($total);

//CREACION XML - INICIO
require_once('xml.php');
$xml = new GeneradorXML();

//RUC DEL EMISOR - TIPO DE COMPROBANTE - SERIE COMPROBANTE - CORRELATIVO
$nombrexml = $emisor['ruc'] . '-' . $comprobante['tipodoc'] . '-' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
$ruta = 'xml/' . $nombrexml;

$xml->CrearXMLNotaCredito($ruta, $emisor, $cliente, $comprobante, $detalle);

echo '</br> XML CREADO CON EXITO. PASO 01';

require_once('signature.php');

$obj=new Signature();
$flgfirma=0;
$rutaxml=$ruta.'.xml';
$rutafirma='CertificadoPFX20.pfx';
$passfirma='Mateo6373';
$obj->signature_xml($flgfirma,$rutaxml,$rutafirma,$passfirma);

echo "</br> Firmado";
//CREACION XML - FIN
//clave certificado: Mateo6373

$zip=new ZipArchive();
$rutazip=$ruta.'.zip';
if($zip->open($rutazip,ZipArchive::CREATE)===TRUE){
    $zip->addFile($rutaxml,$nombrexml.'.xml');
    $zip->close();
}

echo "</br> Comprimido";
$nombre_archivo_zip = $nombrexml . '.ZIP';
$contenidozip=base64_encode(file_get_contents($rutazip));

//echo "contenidp zip".$contenidozip;
//https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl

$ws="https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";

$xml_envio ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
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
                    <contentFile>' . $contenidozip. '</contentFile>
                </ser:sendBill>
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

$ch = curl_init(); //iniciar la llamada
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 1); //
curl_setopt($ch,CURLOPT_URL, $ws);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch,CURLOPT_TIMEOUT, 30);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $xml_envio);
curl_setopt($ch,CURLOPT_HTTPHEADER, $header);    

//para ejecutar los procesos de forma local en windows
//enlace de descarga del cacert.pem: https://curl.haxx.se/docs/caextract.html
curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem"); //solo funciona en local, si en cambio estas en el servidor web con ssl comentar esta línea

//ENVIO WS - SUNAT - FIN

$response=curl_exec($ch);
$httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);

echo "</br> Envio y Respuesta".$httpcode;

echo "</br> Envio y Respuesta".$response;

if ($httpcode==200){
    $doc=new DOMDocument();
    $doc->loadXML($response);
    if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)){
        $cdr=$doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
        $cdr=base64_decode($cdr);
     

        //file_put_contents('cdr/'.'R-'.$nombre_archivo_zip,$cdr);
        file_put_contents('cdr/' . 'R-' . $nombre_archivo_zip, $cdr );

        $zip=new ZipArchive();
        $rutazip=$ruta.'.zip';
        
        if($zip->open('cdr/' . 'R-' . $nombre_archivo_zip) === TRUE)
        {
            
            //$zip->extractTo('cdr/' . 'R-' . $nombrexml . '.XML');
            $zip->extractTo('cdr/', 'R-' . $nombrexml . '.xml');
            $zip->close();
        }
        
        echo "</br> CDR Desscomprimido";
		
		
		
		
		

    }else{
        $codigoerror=$doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
        $mensajeerror=$doc->getElementsByTagName('faultsring')->item(0)->nodeValue;
        echo "</br>".$codigoerror.' '.$mensajeerror;
    }
}else{
   echo curl_error($ch);
   echo "<\br> Error al Conectar";
}

curl_close($ch);
?>