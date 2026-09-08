
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
    'tipodoc'           =>      '1',
    'ruc'               =>      '12345678',
    'razon_social'      =>      'PEPITO PEREZ',
    'direccion'         =>      'PUERTO PALMERAS 658',
    'pais'              =>      'PE'
);

$comprobante = array(
    'tipodoc'           =>          '03',//FA: 01, BV:03, NC: 07, ND: 08
    'serie'             =>          'B001', //DOnde la primera letra o digito debe ser F: FACTURAS, B: Boletas, los 3 digitos o caracteres siguientes son asignados por el negocio.
    'correlativo'       =>          '12345',
    'fecha_emision'     =>          '2021-05-12', //YYYY-MM-DD
    'moneda'            =>          'PEN', //Soles: PEN, Dolares: USD
    'total_opgravadas'  =>          0,
    'total_opexoneradas'    =>      0,
    'total_opinafectas' =>          0,
    'igv'               =>          0,
    'total'             =>          0,
    'total_texto'       =>          ''
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
$ruta = 'xml/' . $emisor['ruc']. '/' .$nombrexml;

$xml->CrearXMLFatura($ruta, $emisor, $cliente, $comprobante, $detalle);

echo '</br> XML CREADO CON EXITO. PASO 01';

require_once('apifacturacion.php');
$objapi = new apifacturacion();
$objapi->enviarcomprobanteElectronico($emisor,$nombrexml);

?>