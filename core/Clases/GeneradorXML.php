<?php
//require_once('clases/Conexion.php');
//require_once('clases/apifacturacion.php');
//require_once('clases/cantidad_en_letras.php');
//require_once('clases/xml.php');
//require_once("clases/envio.php");
namespace Core\Clases;

use Core\Clases\Conexion;
use Core\Clases\apifacturacion;
use Core\Clases\Cletras;
use Core\Clases\envio;
use DOMDocument;
use NumberFormatter;

class GeneradorXML
{
    function __construct()
    {
    }
    function CrearXMLFatura($nidauto, $ctipovta)
    {
        $cletras = "";
        $comprobante = [];
        $detalle = [];
        $cont = 0;
        $importeletras = new Cletras();
        $oenvio = new envio();
        $st = $oenvio->consultardcto($nidauto, $ctipovta);
        foreach ($st as $row) {
            $tipodoc =      '6'; //RUC: 6, DNI: 1
            $rucempresa       =      $row['rucempresa'];
            $tdoc = $row['tdoc'];
            $empresa =      $row['empresa'];
            $ptop =      $row['ptop'];
            $pais =      $row['pais'];
            $departamento      =      $row['ciudad'];
            $provincia         =      $row['ciudad'];
            $distrito          =      $row['distrito'];
            $ubigeo           =    $row['ubigeo'];
            $usuario_secundario =    $row['gene_usol'];
            $clave_usuario_secundario  =    $row['gene_csol'];
            $nruc               =     $row['nruc'];
            $ctipodoccliente = $row['tipodoccliente'];
            $ndni               =   $row['ndni'];
            $razo              =      $row['razo'];
            $direccion         =      $row['direccion'];
            $tdoc          =          $row['tdoc']; //FA: 01, BV:03, NC: 07, ND: 08
            $serie            =          $row['serie']; //DOnde la primera letra o digito debe ser F: FACTURAS, B: Boletas, los 3 digitos o caracteres siguientes son asignados por el negocio.
            $numero       =          $row['numero'];
            $dfecha     =         $row['dfecha']; //YYYY-MM-DD
            $mone            =          $row['mone']; //Soles: PEN, Dolares: USD
            $formapago    =    $row['form'];
            $valor  =          $row['valor'];
            $exoneradas    =      0;
            $inafectas =          0;
            $tigv               =          $row['igv'];
            $impo           =          $row['impo'];
            $cletras = $importeletras->ValorenLetras($row['impo'], $row['moneda'] === 'S' ? 'SOLES' : 'DOLARES');
            $certificado = $row['gene_cert'];
            $clavecertificado = $row['clavecertificado'];
            $usuario_secundario = trim($row['gene_usol']);
            $clave_usuario_secundario = trim($row['gene_csol']);
            $valor_unitario = round($row['prec'] / $row['vigv'], 6); //Sin IGV
            $precio_unitario = round($row['prec'], 2); //Con IGV
            $tipo_precio = '01';
            //$igv=round(($row['prec']/$row['vigv'])*($row['vigv']-1),2);
            $porcentaje_igv = ($row['vigv'] - 1) * 100;
            $valor_total = round($row['cant'] * ($row['prec'] / $row['vigv']), 2);
            $importe_total = round($row['cant'] * $row['prec'], 2);
            $codigo_afectacion = 1000;
            $igv = $importe_total - $valor_total;
            $codigoestab = Trim($row['codigoestab'] === "") ? '0000' : $row['codigoestab'];
            $detalle[$cont] = array(
                'coda'                      =>        $row['coda'],
                'descri'                 =>           $row['descri'],
                'cant'                    =>          round($row['cant'], 2),
                'valor_unitario'              =>      $valor_unitario, //Sin IGV
                'precio_unitario'             =>      $precio_unitario, //Con IGV
                'tipo_precio'                 =>      $tipo_precio,
                'igv'                         =>      $igv,
                'porcentaje_igv'              =>      $porcentaje_igv,
                'valor_total'                 =>      $valor_total,
                'importe_total'               =>      $importe_total,
                'unid1'                      =>       $row['unid1'], //UInidad de medida
                'codigo_afectacion_alt'       =>      $row['tigv'], //Gravadado : 10, EXonerado: 20, Inafecto: 30
                'codigo_afectacion'           =>      $codigo_afectacion,
                'nombre_afectacion'           =>      'IGV',
                'tipo_afectacion'             =>      'VAT'
            );
            $cont++;
        }
        if ($formapago == 'C') {
            $stf = $oenvio->ObtenerCuotasCredito($nidauto);
            $cuotas = array();
            $i = 0;
            $nro = 0;
            foreach ($stf as $row) {
                $nro++;
                $ccuota = "Cuota" . substr("000" . trim(strval($nro)), -3);
                $cuotas[$i] = array(
                    'nrocuota' => $ccuota,
                    'ndoc' => $row['ndoc'],
                    'impo' => $row['impo'],
                    'fevto' => $row['fevto']
                );
                $i++;
            }
        }
        $xml = new GeneradorXML();
        $doc = new DOMDocument(); //esta clase me permiete crear documentos o archivos XML
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        //crear el texto XML de la factura electronica
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
                <ext:UBLExtension>
                    <ext:ExtensionContent />
                </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
            <cbc:ID>' . $serie . '-' . $numero . '</cbc:ID>
            <cbc:IssueDate>' . $dfecha . '</cbc:IssueDate>
            <cbc:IssueTime>00:00:00</cbc:IssueTime>
            <cbc:DueDate>' . $dfecha . '</cbc:DueDate>
            <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT" listID="0101" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $tdoc . '</cbc:InvoiceTypeCode>
            <cbc:Note languageLocaleID="1000"><![CDATA[' . $cletras . ']]></cbc:Note>
            <cbc:DocumentCurrencyCode>' . $mone . '</cbc:DocumentCurrencyCode>
            <cac:Signature>
                <cbc:ID>' . $rucempresa . '</cbc:ID>
                <cbc:Note><![CDATA[' . $empresa . ']]></cbc:Note>
                <cac:SignatoryParty>
                    <cac:PartyIdentification>
                    <cbc:ID>' . $rucempresa . '</cbc:ID>
                    </cac:PartyIdentification>
                    <cac:PartyName>
                    <cbc:Name><![CDATA[' . $empresa . ']]></cbc:Name>
                    </cac:PartyName>
                </cac:SignatoryParty>
                <cac:DigitalSignatureAttachment>
                    <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                    </cac:ExternalReference>
                </cac:DigitalSignatureAttachment>
            </cac:Signature>
            <cac:AccountingSupplierParty>
                <cac:Party>
                    <cac:PartyIdentification>
                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $rucempresa . '</cbc:ID>
                    </cac:PartyIdentification>
                    <cac:PartyName>
                    <cbc:Name><![CDATA[' . Trim($empresa) . ']]></cbc:Name>
                    </cac:PartyName>
                    <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . Trim($empresa) . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cbc:ID>' . Trim($ubigeo) . '</cbc:ID>
                        <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">' . $codigoestab . '</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                        <cbc:CityName>' . trim($provincia) . '</cbc:CityName>
                        <cbc:CountrySubentity>' . trim($provincia) . '</cbc:CountrySubentity>
                        <cbc:District>' . trim($distrito) . '</cbc:District>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA[' . trim($ptop) . ']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>' . $pais . '</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingSupplierParty>
            <cac:AccountingCustomerParty>
                <cac:Party>
                    <cac:PartyIdentification>';
        if ($tdoc === '01') {
            $xml .= '
                        <cbc:ID schemeID="' . $ctipodoccliente . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $nruc . '</cbc:ID>';
        } else {
            if (strlen(Trim($ndni)) === 8 and $ndni != '00000000') {
                $xml .= '
                            <cbc:ID schemeID="' . $ctipodoccliente . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $ndni . '</cbc:ID>';
            } else {
                $xml .= '
                            <cbc:ID schemeID="0" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . '00000000' . '</cbc:ID>';
            }
        }
        $xml .= '
                    </cac:PartyIdentification>
                    <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . Trim($razo) . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA[' . Trim($direccion) . ']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>' . $pais . '</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingCustomerParty>';
        if ($formapago == 'C') {
            $xml .= '<cac:PaymentTerms>
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                <cbc:Amount currencyID="' . $mone . '">' . $impo . '</cbc:Amount>
                </cac:PaymentTerms>' . PHP_EOL;;
            foreach ($cuotas as $cuota) {
                $xml .= '<cac:PaymentTerms>
                    <cbc:ID>FormaPago</cbc:ID>
                    <cbc:PaymentMeansID>' . $cuota['nrocuota'] . '</cbc:PaymentMeansID>
                    <cbc:Amount currencyID="' . $mone . '">' . $cuota['impo'] . '</cbc:Amount>
                    <cbc:PaymentDueDate>' .  $cuota['fevto'] . '</cbc:PaymentDueDate>
                    </cac:PaymentTerms>' . PHP_EOL;
            }
        } else {
            $xml .= '<cac:PaymentTerms>
                <cbc:ID>FormaPago</cbc:ID>
                <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                </cac:PaymentTerms>' . PHP_EOL;
        }
        $xml .= '     <cac:TaxTotal>
                <cbc:TaxAmount currencyID="' . $mone . '">' . $tigv . '</cbc:TaxAmount>';
        if ($valor > 0) {
            $xml .= '<cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $mone . '">' . $valor . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $mone . '">' . $tigv . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                        <cac:TaxScheme>
                            <cbc:ID>1000</cbc:ID>
                            <cbc:Name>IGV</cbc:Name>
                            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                        </cac:TaxCategory>
                    </cac:TaxSubtotal>' . PHP_EOL;;
        }

        if ($exoneradas > 0) {
            $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $mone . '">' . $exoneradas . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $mone . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                            <cbc:Name>EXO</cbc:Name>
                            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                    </cac:TaxSubtotal>' . PHP_EOL;;
        }
        if ($inafectas > 0) {
            $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $mone . '">' . $inafectas . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $mone . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                            <cbc:Name>INA</cbc:Name>
                            <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                    </cac:TaxSubtotal>' . PHP_EOL;;
        }
        $total_antes_de_impuestos = $valor + $exoneradas + $inafectas;
        $xml .= '  </cac:TaxTotal>
            <cac:LegalMonetaryTotal>
                <cbc:LineExtensionAmount currencyID="' . $mone . '">' . $total_antes_de_impuestos . '</cbc:LineExtensionAmount>
                <cbc:TaxInclusiveAmount currencyID="' . $mone . '">' . $impo . '</cbc:TaxInclusiveAmount>
                <cbc:PayableAmount currencyID="' . $mone . '">' . $impo . '</cbc:PayableAmount>
            </cac:LegalMonetaryTotal>';
        $nombre_afectacion = 'IGV';
        $tipo_afectacion = 'VAT';
        $i = 0;
        foreach ($detalle as $v) {
            $i++;
            $xml .= '<cac:InvoiceLine>
                    <cbc:ID>' . $i . '</cbc:ID>
                    <cbc:InvoicedQuantity unitCode="' . $v['unid1'] . '">' . $v['cant'] . '</cbc:InvoicedQuantity>
                    <cbc:LineExtensionAmount currencyID="' . $mone . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
                    <cac:PricingReference>
                        <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $mone . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                        </cac:AlternativeConditionPrice>
                    </cac:PricingReference>
                    <cac:TaxTotal>
                        <cbc:TaxAmount currencyID="' . $mone . '">' . $v['igv'] . '</cbc:TaxAmount>
                        <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $mone . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $mone . '">' . $v['igv'] . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                            <cbc:TaxExemptionReasonCode>' . $v['codigo_afectacion_alt'] . '</cbc:TaxExemptionReasonCode>
                            <cac:TaxScheme>
                                <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                                <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                                <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                        </cac:TaxSubtotal>
                    </cac:TaxTotal>
                    <cac:Item>
                        <cbc:Description><![CDATA[' . $v['descri'] . ']]></cbc:Description>
                        <cac:SellersItemIdentification>
                        <cbc:ID>' . $v['coda'] . '</cbc:ID>
                        </cac:SellersItemIdentification>
                    </cac:Item>
                    <cac:Price>
                        <cbc:PriceAmount currencyID="' . $mone . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
                    </cac:Price>
                    </cac:InvoiceLine>';
        }
        $xml .= "
        </Invoice>";
        $nombrexml = $rucempresa . '-' . $tdoc . '-' . $serie . '-' . $numero;
        $ruta = 'xml/' . $rucempresa . '/' . $nombrexml;
        $doc->loadXML($xml);
        $doc->save($ruta . '.xml');
        $emisor = [];
        $emisor = array(
            'ruc'                         =>      $rucempresa,
            'nombrexml'                   =>      $nombrexml,
            'certificado'                 =>      $certificado,
            'clavecertificado'            =>      $clavecertificado,
            'usuario_secundario'           =>     $usuario_secundario,
            'clave_usuario_secundario'    =>      $clave_usuario_secundario
        );
        $objapi = new apifacturacion();
        $objapi->enviarcomprobanteElectronico($emisor, $nombrexml, $nidauto);
    }
    function CrearXMLNotaCredito($nidauto, $data)
    {

        $oenvio = new envio();
        $cont = 0;
        $item = 0;
        $ctdoc = "07";
        $st = $oenvio->consultarNotacredito($nidauto);
        $importeletras = new Cletras();
        foreach ($st as $row) {
            $item++;
            $ctipodoc = '06';
            $serie = $row['serie'];
            $numero = $row['numero'];
            $dfecha = $row['dfecha'];
            //  $this->importeletras->ValorenLetras();
            $cletras = $importeletras->ValorenLetras($row['impo'], $row['moneda'] === 'S' ? 'SOLES' : 'DOLARES');
            //$cletras = $this->importeletras->ValorEnLetras($row['impo'],$row['moneda']==='S' ? 'SOLES' : 'DOLARES');
            $cmoneda = $row['mone'];
            $serieref = substr($row['refe'], 0, 4);
            $numerorefe = substr($row['refe'], -8);
            $motivo = $row['motivo'];
            if (
                $row['detallenota'] == "01" or $row['detallenota'] == "02" or  $row['detallenota'] == "03" or  $row['detallenota'] == "04" or  $row['detallenota'] = "05"
                or  $row['detallenota'] == "06" or  $row['detallenota'] == "07"  or  $row['detallenota'] == "08" or $row['detallenota'] == "09"
                or  $row['detallenota'] == "10" or  $row['detallenota'] == "11"  or  $row['detallenota'] == "12" or  $row['detallenota'] = "13"
            ) {
                $cdetallenota = $row['deta'];
            } else {
                $cdetallenota = '01';
            }
            $tref = $row['tref'];
            $ctipodocliente = $row['tipodoc'];
            $dctocliente = ($row['tipodoc'] == '6'  ? $row['nruc'] : $row['ndni']);
            $rucempresa = $row['rucempresa'];
            $empresa = $row['empresa'];
            $ptop =      $row['ptop'];
            $pais =      $row['pais'];
            $departamento      =      $row['ciudad'];
            $provincia         =      $row['ciudad'];
            $distrito          =      $row['distrito'];
            $ubigeo           =    $row['ubigeo'];
            $usuario_secundario =    $row['gene_usol'];
            $clave_usuario_secundario  =    $row['gene_csol'];
            $codigoestab = $row['codigoestab'];
            $razo              =      $row['razo'];
            $direccion         =      $row['direccion'];

            $valor  =          round($row['valor'], 2);
            $exoneradas    =      0;
            $inafectas =          0;
            $tigv               =          $row['igv'];
            $impo           =          $row['impo'];
            $valor_unitario = round($row['prec'] / $row['vigv'], 6); //Sin IGV
            $precio_unitario = round($row['prec'], 2); //Con IGV
            $tipo_precio = '01';
            //$igv=round(($row['prec']/$row['vigv'])*($row['vigv']-1),2);
            $porcentaje_igv = ($row['vigv'] - 1) * 100;
            $valor_total = round($row['cant'] * ($row['prec'] / $row['vigv']), 2);
            $importe_total = round($row['cant'] * $row['prec'], 2);
            $codigo_afectacion = 1000;
            $igv = $importe_total - $valor_total;
            $certificado = $row['gene_cert'];
            $clavecertificado = $row['clavecertificado'];
            $usuario_secundario = trim($row['gene_usol']);
            $clave_usuario_secundario = trim($row['gene_csol']);
            if ($cdetallenota === '13') {
                $formapago    =   'C';
            } else {
                $formapago    =   'E';
            }
            $detalle[$cont] = array(
                'coda'                      =>        $row['coda'],
                'descri'                 =>           $row['descri'],
                'cant'                    =>          round($row['cant'], 2),
                'valor_unitario'              =>      $valor_unitario, //Sin IGV
                'precio_unitario'             =>      $precio_unitario, //Con IGV
                'tipo_precio'                 =>      $tipo_precio,
                'igv'                         =>      $igv,
                'porcentaje_igv'              =>      $porcentaje_igv,
                'valor_total'                 =>      $valor_total,
                'importe_total'               =>      $importe_total,
                'unid1'                      =>       $row['unid1'], //UInidad de medida
                'codigo_afectacion_alt'       =>      $row['tigv'], //Gravadado : 10, EXonerado: 20, Inafecto: 30
                'codigo_afectacion'           =>      $codigo_afectacion,
                'nombre_afectacion'           =>      'IGV',
                'tipo_afectacion'             =>      'VAT'
            );
            $cont++;
        }
        if ($formapago == 'C') {
            $stf = $oenvio->ObtenerCuotasCredito($nidauto);
            $cuotas = array();
            $i = 0;
            $nro = 0;
            foreach ($stf as $row) {
                $nro++;
                $ccuota = "Cuota" . substr("000" . trim(strval($nro)), -3);
                $cuotas[$i] = array(
                    'nrocuota' => $ccuota,
                    'ndoc' => $row['ndoc'],
                    'impo' => round($row['impo'], 2),
                    'fevto' => $row['fevto']
                );
                $i++;
            }
        }
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
                <ext:UBLExtension>
                    <ext:ExtensionContent />
                </ext:UBLExtension>
            </ext:UBLExtensions>
            <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
            <cbc:CustomizationID>2.0</cbc:CustomizationID>
            <cbc:ID>' . $serie . '-' . $numero . '</cbc:ID>
            <cbc:IssueDate>' . $dfecha . '</cbc:IssueDate>
            <cbc:IssueTime>00:00:01</cbc:IssueTime>
            <cbc:Note languageLocaleID="1000"><![CDATA[' . $cletras . ']]></cbc:Note>
            <cbc:DocumentCurrencyCode listAgencyName="United Nations Economic Commission for Europe" listID="ISO 4217 Alpha" listName="Currency">' . $cmoneda . '</cbc:DocumentCurrencyCode>
            <cbc:LineCountNumeric>' . $item . '</cbc:LineCountNumeric>
            <cac:DiscrepancyResponse>
                <cbc:ReferenceID>' . $serieref . '-' . $numerorefe . '</cbc:ReferenceID>
                <cbc:ResponseCode listName="Tipo de nota de credito" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo09">' . $motivo . '</cbc:ResponseCode>
                <cbc:Description>' . $cdetallenota . '</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                    <cbc:ID>' . $serieref . '-' . $numerorefe . '</cbc:ID>
                    <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $tref . '</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>
            <cac:Signature>
                <cbc:ID>' . $rucempresa . '</cbc:ID>
                <cbc:Note><![CDATA[' . $empresa . ']]></cbc:Note>
                <cac:SignatoryParty>
                    <cac:PartyIdentification>
                    <cbc:ID>' . $rucempresa . '</cbc:ID>
                    </cac:PartyIdentification>
                    <cac:PartyName>
                    <cbc:Name><![CDATA[' . $empresa . ']]></cbc:Name>
                    </cac:PartyName>
                </cac:SignatoryParty>
                <cac:DigitalSignatureAttachment>
                    <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                    </cac:ExternalReference>
                </cac:DigitalSignatureAttachment>
            </cac:Signature>
            <cac:AccountingSupplierParty>
                <cac:Party>
                    <cac:PartyIdentification>
                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $rucempresa . '</cbc:ID>
                    </cac:PartyIdentification>
                    <cac:PartyName>
                    <cbc:Name><![CDATA[' . $empresa . ']]></cbc:Name>
                    </cac:PartyName>
                    <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $empresa . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cbc:ID>' . trim($ubigeo) . '</cbc:ID>
                        <cbc:AddressTypeCode>' . $codigoestab . '</cbc:AddressTypeCode>
                        <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                        <cbc:CityName>' . trim($provincia) . '</cbc:CityName>
                        <cbc:CountrySubentity>' . trim($departamento) . '</cbc:CountrySubentity>
                        <cbc:District>' . trim($distrito) . '</cbc:District>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA[' . $ptop . ']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>' . $pais . '</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingSupplierParty>
            <cac:AccountingCustomerParty>
                <cac:Party>
                    <cac:PartyIdentification>';
        if ($ctipodocliente == '06') {
            $xml .= '<cbc:ID schemeAgencyName="PE:SUNAT" schemeID="6" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $dctocliente . '</cbc:ID>';
        } else {
            $xml .= '<cbc:ID schemeAgencyName="PE:SUNAT" schemeID="1" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $dctocliente . '</cbc:ID>';
        }
        $xml .= '</cac:PartyIdentification>
                    <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . trim($razo) . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                        <cac:AddressLine>
                            <cbc:Line><![CDATA[' . $direccion . ']]></cbc:Line>
                        </cac:AddressLine>
                        <cac:Country>
                            <cbc:IdentificationCode>' . $pais . '</cbc:IdentificationCode>
                        </cac:Country>
                    </cac:RegistrationAddress>
                    </cac:PartyLegalEntity>
                </cac:Party>
            </cac:AccountingCustomerParty>';
        if ($formapago == 'C') {
            $xml .= '<cac:PaymentTerms>
                   <cbc:ID>FormaPago</cbc:ID>
                   <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                   <cbc:Amount currencyID="' . $cmoneda . '">' . $impo . '</cbc:Amount>
                   </cac:PaymentTerms>';
            foreach ($cuotas as $cuota) {
                $xml . '<cac:PaymentTerms>
                       <cbc:ID>FormaPago</cbc:ID>
                       <cbc:PaymentMeansID>' . $cuota['nrocuota'] . '</cbc:PaymentMeansID>
                       <cbc:Amount currencyID="' . $cmoneda . '">' . $cuota['impo'] . '</cbc:Amount>
                       <cbc:PaymentDueDate>"' . $cmoneda . '">' . $cuota['fevto'] . '</cbc:PaymentDueDate>
                       </cac:PaymentTerms>';
            }
        }
        $xml .= '<cac:TaxTotal>
                <cbc:TaxAmount currencyID="' . $cmoneda . '">' . $tigv . '</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $cmoneda . '">' . $valor . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $cmoneda . '">' . $tigv . '</cbc:TaxAmount>
                    <cac:TaxCategory>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>';
        if ($exoneradas > 0) {
            $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $cmoneda . '">' . $exoneradas . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $cmoneda . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                            <cbc:Name>EXO</cbc:Name>
                            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                    </cac:TaxSubtotal>';
        }
        if ($inafectas > 0) {
            $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $cmoneda . '">' . $inafectas . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $cmoneda . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                        <cac:TaxScheme>
                            <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                            <cbc:Name>INA</cbc:Name>
                            <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                    </cac:TaxSubtotal>';
        }
        $xml .= '</cac:TaxTotal>
            <cac:LegalMonetaryTotal>
                <cbc:PayableAmount currencyID="' . $cmoneda . '">' . $impo . '</cbc:PayableAmount>
            </cac:LegalMonetaryTotal>';
        $i = 1;
        foreach ($detalle as $k => $v) {
            $xml .= '<cac:CreditNoteLine>
                    <cbc:ID>' . $i . '</cbc:ID>
                    <cbc:CreditedQuantity unitCode="' . $v['unid1'] . '">' . $v['cant'] . '</cbc:CreditedQuantity>
                    <cbc:LineExtensionAmount currencyID="' . $cmoneda . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
                    <cac:PricingReference>
                    <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $cmoneda . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                    </cac:AlternativeConditionPrice>
                    </cac:PricingReference>
                    <cac:TaxTotal>
                    <cbc:TaxAmount currencyID="' . $cmoneda . '">' . $v['igv'] . '</cbc:TaxAmount>
                    <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $cmoneda . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $cmoneda . '">' . $v['igv'] . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                            <cbc:TaxExemptionReasonCode>' . $v['codigo_afectacion_alt'] . '</cbc:TaxExemptionReasonCode>
                            <cac:TaxScheme>
                                <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                                <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                                <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                    </cac:TaxSubtotal>
                    </cac:TaxTotal>
                    <cac:Item>
                    <cbc:Description><![CDATA[' . $v['descri'] . ']]></cbc:Description>
                    <cac:SellersItemIdentification>
                        <cbc:ID>' . $v['coda'] . '</cbc:ID>
                    </cac:SellersItemIdentification>
                    </cac:Item>
                    <cac:Price>
                    <cbc:PriceAmount currencyID="' . $cmoneda . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
                    </cac:Price>
                </cac:CreditNoteLine>';
            $i++;
        }
        $xml .= '</CreditNote>';
        $nombrexml = $rucempresa . '-' . $ctdoc . '-' . $serie . '-' . $numero;
        $ruta = 'xml/' . $rucempresa . '/' . $nombrexml;
        $doc->loadXML($xml);
        $doc->save($ruta . '.xml');
        $emisor = [];
        $emisor = array(
            'ruc'                         =>      $rucempresa,
            'nombrexml'                   =>      $nombrexml,
            'certificado'                 =>      $certificado,
            'clavecertificado'            =>      $clavecertificado,
            'usuario_secundario'           =>     $usuario_secundario,
            'clave_usuario_secundario'    =>      $clave_usuario_secundario
        );
        $objapi = new apifacturacion();
        $objapi->enviarcomprobanteElectronico($emisor, $nombrexml, $nidauto);
        // $doc->loadXML($xml); //Cargo la variable XML
        // $doc->save($nombrexml . '.XML'); //Creo y guardo el XML
    }
    function CrearXMLNotaDebito($nidauto, $data)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID>2.0</cbc:CustomizationID>
           <cbc:ID>' . $comprobante['serie'] . '-' . $comprobante['correlativo'] . '</cbc:ID>
           <cbc:IssueDate>' . $comprobante['fecha_emision'] . '</cbc:IssueDate>
           <cbc:IssueTime>00:00:03</cbc:IssueTime>
           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante['total_texto'] . ']]></cbc:Note>
           <cbc:DocumentCurrencyCode>' . $comprobante['moneda'] . '</cbc:DocumentCurrencyCode>
           <cac:DiscrepancyResponse>
              <cbc:ReferenceID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ReferenceID>
              <cbc:ResponseCode>' . $comprobante['codmotivo'] . '</cbc:ResponseCode>
              <cbc:Description>' . $comprobante['descripcion'] . '</cbc:Description>
           </cac:DiscrepancyResponse>
           <cac:BillingReference>
              <cac:InvoiceDocumentReference>
                 <cbc:ID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ID>
                 <cbc:DocumentTypeCode>' . $comprobante['tipodoc_ref'] . '</cbc:DocumentTypeCode>
              </cac:InvoiceDocumentReference>
           </cac:BillingReference>
           <cac:Signature>
              <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $emisor['tipodoc'] . '">' . $emisor['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID>' . $emisor['ubigeo'] . '</cbc:ID>
                       <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                       <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                       <cbc:CityName>' . $emisor['provincia'] . '</cbc:CityName>
                       <cbc:CountrySubentity>' . $emisor['departamento'] . '</cbc:CountrySubentity>
                       <cbc:District>' . $emisor['distrito'] . '</cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $emisor['direccion'] . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode>' . $emisor['pais'] . '</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingSupplierParty>
              <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $cliente['tipodoc'] . '">' . $cliente['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente['razon_social'] . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $cliente['direccion'] . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode>' . $cliente['pais'] . '</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>
           <cac:TaxTotal>
              <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
              <cac:TaxSubtotal>
                 <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgravadas'] . '</cbc:TaxableAmount>
                 <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
                 <cac:TaxCategory>
                    <cac:TaxScheme>
                       <cbc:ID>1000</cbc:ID>
                       <cbc:Name>IGV</cbc:Name>
                       <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                 </cac:TaxCategory>
              </cac:TaxSubtotal>
           </cac:TaxTotal>
           <cac:RequestedMonetaryTotal>
              <cbc:PayableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:PayableAmount>
           </cac:RequestedMonetaryTotal>';

        foreach ($detalle as $k => $v) {
            $xml .= '<cac:DebitNoteLine>
                 <cbc:ID>' . $v['item'] . '</cbc:ID>
                 <cbc:DebitedQuantity unitCode="' . $v['unidad1'] . '">' . $v['cantidad'] . '</cbc:DebitedQuantity>
                 <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
                 <cac:PricingReference>
                    <cac:AlternativeConditionPrice>
                       <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                       <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                    </cac:AlternativeConditionPrice>
                 </cac:PricingReference>
                 <cac:TaxTotal>
                    <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                    <cac:TaxSubtotal>
                       <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                       <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                       <cac:TaxCategory>
                          <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
                          <cac:TaxScheme>
                             <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                             <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                             <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                          </cac:TaxScheme>
                       </cac:TaxCategory>
                    </cac:TaxSubtotal>
                 </cac:TaxTotal>
                 <cac:Item>
                    <cbc:Description><![CDATA[' . $v['descripcion'] . ']]></cbc:Description>
                    <cac:SellersItemIdentification>
                       <cbc:ID>' . $v['codigo'] . '</cbc:ID>
                    </cac:SellersItemIdentification>
                 </cac:Item>
                 <cac:Price>
                    <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
                 </cac:Price>
              </cac:DebitNoteLine>';
        }
        $xml .= '</DebitNote>';
        $doc->loadXML($xml);
        $doc->save($nombrexml . '.XML');
    }
    function CrearXMLResumenBoletas($nombrexml, $emisor, $detalle)
    {

        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
           <SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.1</cbc:CustomizationID>
          <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
          <cbc:ReferenceDate>' . $cabecera['fecha_emision'] . '</cbc:ReferenceDate>
          <cbc:IssueDate>' . $cabecera['fecha_envio'] . '</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>' . $emisor['ruc'] . '</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>' . $emisor['tipodoc'] . '</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';
        foreach ($detalle as $k => $v) {
            $xml .= '<sac:SummaryDocumentsLine>
                 <cbc:LineID>' . $v['item'] . '</cbc:LineID>
                 <cbc:DocumentTypeCode>' . $v['tipodoc'] . '</cbc:DocumentTypeCode>
                 <cbc:ID>' . $v['serie'] . '-' . $v['correlativo'] . '</cbc:ID>
                 <cac:Status>
                    <cbc:ConditionCode>' . $v['condicion'] . '</cbc:ConditionCode>
                 </cac:Status>                
                 <sac:TotalAmount currencyID="' . $v['moneda'] . '">' . $v['importe_total'] . '</sac:TotalAmount><sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['valor_total'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>' . $v['tipo_total'] . '</cbc:InstructionID>
                       </sac:BillingPayment><cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>';
            if ($v['codigo_afectacion'] != '1000') {
                $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                                 <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                                 <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
            }
            $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>1000</cbc:ID>
                                 <cbc:Name>IGV</cbc:Name>
                                 <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';

            $xml .= '</cac:TaxTotal>
             </sac:SummaryDocumentsLine>';
        }
        $xml .= '</SummaryDocuments>';
        $doc->loadXML($xml);
        $doc->save($nombrexml . '.xml');
    }
    function CrearXMLBajaDocumentos($nombrexml, $emisor, $detalle)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <ext:UBLExtensions>
          <ext:UBLExtension>
              <ext:ExtensionContent />
          </ext:UBLExtension>
      </ext:UBLExtensions>
      <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
      <cbc:CustomizationID>1.0</cbc:CustomizationID>
      <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
      <cbc:ReferenceDate>' . $cabecera['fecha_emision'] . '</cbc:ReferenceDate>
      <cbc:IssueDate>' . $cabecera['fecha_envio'] . '</cbc:IssueDate>
      <cac:Signature>
          <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
          <cac:SignatoryParty>
              <cac:PartyIdentification>
                  <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
              </cac:PartyIdentification>
              <cac:PartyName>
                  <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
              </cac:PartyName>
          </cac:SignatoryParty>
          <cac:DigitalSignatureAttachment>
              <cac:ExternalReference>
                  <cbc:URI>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:URI>
              </cac:ExternalReference>
          </cac:DigitalSignatureAttachment>
      </cac:Signature>
      <cac:AccountingSupplierParty>
          <cbc:CustomerAssignedAccountID>' . $emisor['ruc'] . '</cbc:CustomerAssignedAccountID>
          <cbc:AdditionalAccountID>' . $emisor['tipodoc'] . '</cbc:AdditionalAccountID>
          <cac:Party>
              <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
              </cac:PartyLegalEntity>
          </cac:Party>
      </cac:AccountingSupplierParty>';
        foreach ($detalle as $k => $v) {
            $xml .= '<sac:VoidedDocumentsLine>
             <cbc:LineID>' . $v['item'] . '</cbc:LineID>
             <cbc:DocumentTypeCode>' . $v['tipodoc'] . '</cbc:DocumentTypeCode>
             <sac:DocumentSerialID>' . $v['serie'] . '</sac:DocumentSerialID>
             <sac:DocumentNumberID>' . $v['correlativo'] . '</sac:DocumentNumberID>
             <sac:VoidReasonDescription><![CDATA[' . $v['motivo'] . ']]></sac:VoidReasonDescription>
         </sac:VoidedDocumentsLine>';
        }
        $xml .= '</VoidedDocuments>';
        $doc->loadXML($xml);
        $doc->save($nombrexml . '.xml');
    }
    function CrearXMLResumenDocumentos($emisor, $cabecera, $detalle, $nombrexml)
    {
        $doc = new DOMDocument();
        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->encoding = 'utf-8';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
           <SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.1</cbc:CustomizationID>
          <cbc:ID>' . $emisor['correlativo'] . '</cbc:ID>
          <cbc:ReferenceDate>' . $emisor['fechaemision'] . '</cbc:ReferenceDate>
          <cbc:IssueDate>' . $emisor['fechaenvio'] . '</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>' . $emisor['correlativo'] . '</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>' . $emisor['rucempresa'] . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA[' . $emisor['empresa'] . ']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>' . $emisor['correlativo'] . '</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>' . $emisor['rucempresa'] . '</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>' . $emisor['tipodoc'] . '</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $emisor['empresa'] . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';
        foreach ($detalle as $k => $v) {
            $xml .= '<sac:SummaryDocumentsLine>
                 <cbc:LineID>' . $v['item'] . '</cbc:LineID>
                 <cbc:DocumentTypeCode>' . $v['tdoc'] . '</cbc:DocumentTypeCode>
                 <cbc:ID>' . $v['serie'] . '-' . $v['numero'] . '</cbc:ID>
                 <cac:AccountingCustomerParty>
                <cbc:CustomerAssignedAccountID>' . trim($v['dni']) . '</cbc:CustomerAssignedAccountID>
                <cbc:AdditionalAccountID>' . $v['tipodoccliente'] . '</cbc:AdditionalAccountID>
                </cac:AccountingCustomerParty>
                 <cac:Status>
                    <cbc:ConditionCode>' . $v['condicion'] . '</cbc:ConditionCode>
                 </cac:Status>                
                 <sac:TotalAmount currencyID="' . $v['moneda'] . '">' . $v['importe_total'] . '</sac:TotalAmount><sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['valor_total'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>' . $v['tipo_total'] . '</cbc:InstructionID>
                       </sac:BillingPayment><cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>';
            if ($v['codigo_afectacion'] != '1000') {
                $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                                 <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                                 <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
            }
            $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>1000</cbc:ID>
                                 <cbc:Name>IGV</cbc:Name>
                                 <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
            $xml .= '</cac:TaxTotal>
             </sac:SummaryDocumentsLine>';
        }
        $xml .= '</SummaryDocuments>';
        $doc->loadXML($xml);
        $doc->save($nombrexml . '.xml');
    }
}
