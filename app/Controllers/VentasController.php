<?php

namespace App\Controllers;

use App\Models\Categoria;
use App\Models\Compra;
use App\Models\CtasporCobrar;
use App\Models\GuiaRemitente;
use App\Models\GuiaTransportista;
use App\Models\Pedido;
use App\Models\Ventas;
use App\Models\Usuario;
use Core\Routing\Controller;
use Valitron\Validator;
use App\Services\CarritoService;
use App\Services\CarritoServiceCanje;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Clases\Cletras;
use Exception;

class VentasController extends Controller
{
    function indexovtas()
    {
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        if ($idventa > 0) {
            $titulo = 'Editar-Servicios';
        } else {
            $titulo = 'Servicios';
        }
        $datosclientev = array();
        $datosclientev = array(
            'idcliev' => \session()->get('idcliev', 0),
            'razov' => \session()->get('razov', ''),
            'ruccliev' => \session()->get('ruccliev', 0),
            'dnicliev' => \session()->get('dnicliev', 0),
            'direcliev' => \session()->get('direcliev', ''),
            'clienteretencion' => \session()->get('clienteretencion', 'N'),
            'tdocv' => \session()->get('tdocv', 0),
            'ndoc' => \session()->get('ndoc', 0),
            'cndocv' => $serie,
            'numv' => $num,
            'ndo2v' => \session()->get('ndo2v', ''),
            'almv' => \session()->get('almv', ''),
            'fechv' => \session()->get('fechv', ''),
            'monev' => \session()->get('monev', ''),
            'formv' => \session()->get('formv', ''),
            'fechvv' => \session()->get('fechvv', ''),
            'idvenv' => \session()->get('idvenv', ''),
        );
        $gene_detra = session()->get('gene_gene_detr', '');
        return view('ventas/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa, 'detalle' => [], 'gene_detra' => $gene_detra]);
    }
    function regvtasp()
    {
        $titulo = "Registro de ventas";
        return view('ventas/informes/indexlistarple', ["titulo" => $titulo]);
    }
    function regvtasple(Request $request)
    {
        // $ventas = new Ventas();
        // $listado = $ventas->registroventasple($request->get('mes'), $request->get('ano'));
        // return view('ventas/informes/listarple', ['listado' => $listado]);
        $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
        if ($multiempresa == 'N') {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc']
            );
        } else {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc'],
                'codt' => $_SESSION['idalmacen']
            );
        }
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $this->obtenerlistadople($datapost)], 200);
    }
    function obtenerlistadople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listarple.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // var_dump($response);
        $data = json_decode($response, true);
        return $data['result'];
    }
    function obtenerlistadonotascreditople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarnotascreditople.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data['result'];
    }
    function oventasresumidas()
    {
        $ctitulo = 'Ventas x Servicio';
        return view('ventas/re_vtas', [
            "titulo" => $ctitulo
        ]);
    }
    function mostraroventasresumidas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $empresasel = $request->get("empresa");
        if (empty($empresasel)) {
            $nidt = 0;
        } else {
            $nidt = intval($empresasel);
        }
        $ventas = new Ventas();
        $listado = $ventas->mostraroventas($dfi, $dff, $nidt);
        return view('ventas/re_listavtasr', [
            "listado" => $listado
        ]);
    }
    function registrarovta(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }

        $ovtas = new Ventas();

        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $_SESSION['idalmacen'],
            "fechv" => $request->get("fechv"),
            "txtdireccion" => $request->get("txtdireccion"),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "razov" => $request->get('razov'),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "cliente" => $request->get("txtcliente"),
            "ruccliente" => $request->get("txtruccliente"),
            "dnicliente" => $request->get("dnicliente"),
            "nidus" => session()->get('usuario_id'),
            "ndias" => $request->get("ndias"),
            "txtdetraccion" => $request->get("txtdetraccion"),
            "nitem" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT)
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $registro = $ovtas->grabaroVentaGeneral($cabecera, $detalle);

        if ($registro['estado'] == '1') {

            $this->limpiarSesionOvta();
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $detalle;
            $_SESSION['ndoc'] = $registro['ndoc'];

            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $registro['ndoc']], 200);
        } else {
            return response()->json(['message' => 'Error al registrar venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function modificarovta(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $venta = new Ventas();
        $deta =  "";
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "ndoc" => $request->get("ndoc"),
            "ndo2v" => $request->get("ndo2v"),
            'razov' => $request->get('razov'),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "almv" => $_SESSION['idalmacen'],
            "fechv" => $request->get("fechv"),
            "fechvv" => $request->get("fechvv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "txtdetraccion" => $request->get("txtdetraccion"),
            "nidus" => session()->get('usuario_id'),
            "nidautov" => $request->get("idautov"),
            "detav" => $deta,
            "nitemsv" => 0
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $venta->actualizarOVenta($cabecera, $detalle);
        if ($rpta['estado'] == '1') {
            $this->limpiarSesionOvta();
            // $cvista = \retornavista('ventas', 'detalle');
            return response()->json(['message' => 'Se modificó correctamente'], 200);
        } else {
            return response()->json(['message' => 'Error al modificar Venta', 'error' => $rpta['mensaje']], 422);
        }
    }
    function buscarOVentaPorID($idauto)
    {
        $venta = new Ventas();
        $nroventa = "";
        $idautov = $idauto;
        $this->limpiarSesionOvta();
        // $carritov = session()->get('carritov', []);
        $lista = $venta->mostrsroventas($idauto);
        session()->set('idventa', $idautov);
        $datosclientev = array();
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $razo = str_replace('"', ' ', $item['razo']);
                $datosclientev = array(
                    'idauto' => $item['idauto'],
                    'almv' => $item['alma'],
                    'fechv' => $item['fech'],
                    'fvto' => $item['fvto'],
                    'ndoc' => $item['ndoc'],
                    'formv' => $item['form'],
                    'tdocv' => $item['tdoc'],
                    'dolarv' => $item['dolar'],
                    'tipov' => $item['tipo'],
                    'monev' => $item['mone'],
                    'razov' => $razo,
                    'direcliev' => $item['dire'],
                    'idcliev' => $item['idclie'],
                    'ruccliev' => $item['nruc'],
                    'clienteretencion' => $item['clie_rete'],
                    'dnicliev' => $item['ndni'],
                    'ndo2v' => $item['ndo2'],
                    'idvenv' => $item['codv'],
                    'detallev' => (isset($item['detalle'])) ? $item['detalle'] : '',
                    'rcom_detr' => $item['rcom_detr'],
                    'subtotalv' => $item['valor'],
                    'igvv' => $item['igv'],
                    'impov' => $item['impo']
                );
                $nroventa = $item['ndoc'];
            }
        }
        $detalleg = $venta->mostrardetalloventas($idauto);
        foreach ($detalleg as $i) {
            $detalle[] = array(
                'nreg' => $i["nreg"],
                'descri' => $i["descri"],
                'cant' => floatval($i['cant']),
                'precio' => floatval($i['prec']),
                'unidad' => $i['unidad'],
                'subt' => floatval($i['cant']) * floatval($i['prec']),
                'activo' => 'A'
            );
        }
        // session()->set('carritov', $carritov);
        session()->set('datosclientev', $datosclientev);

        $datosclientev = session()->get('datosclientev', []);

        $titulo = 'Actualizar Venta' . ' ' . $nroventa;
        session()->set('nroventa', $nroventa);

        $serie = substr($nroventa, 0, 4);
        $num = substr($nroventa, 4);

        session()->set('idventa', $idauto);

        $cvista = \retornavista('ventas', 'index');

        \session()->set('idcliev', $datosclientev['idcliev']);
        \session()->set('razov',  $datosclientev['razov']);
        \session()->set('ruccliev',  $datosclientev['ruccliev']);
        \session()->set('tdocv',  $datosclientev['tdocv']);
        \session()->set('clienteretencion',  $datosclientev['clienteretencion']);
        \session()->set('ndoc',  $datosclientev['ndoc']);
        \session()->set('cndocv',  $serie);
        \session()->set('numv', $num);
        \session()->set('direcliev',  $datosclientev['direcliev']);
        \session()->set('ndo2v',  $datosclientev['ndo2v']);
        \session()->set('almv',  $datosclientev['almv']);
        \session()->set('formv',  $datosclientev['formv']);
        \session()->set('monev',  $datosclientev['monev']);
        \session()->set('fechv',  $datosclientev['fechv']);
        \session()->set('idvenv',  $datosclientev['idvenv']);
        $gene_detra = session()->get('gene_gene_detr', '');
        return view($cvista, ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'idventa' => $idautov, 'serie' => $serie, 'num' => $num, 'detalle' => $detalle, 'gene_detra' => $gene_detra]);
    }
    function limpiarSesionOvta()
    {
        session()->remove('carritov');
        session()->remove('idventa');
        session()->remove('direcliev');
        session()->remove('idcliev');
        session()->remove('razov');
        session()->remove('ruccliev');
        session()->remove('dnicliev');
        session()->remove('tdocv');
        session()->remove('cndocv');
        session()->remove('numv');
        session()->remove('ndo2v');
        session()->remove('almv');
        session()->remove('formv');
        session()->remove('monev');
        session()->remove('fechv');
        session()->remove('fechvv');
        session()->remove('idvenv');
        session()->remove('optigv');
    }
    function grabarSesion(Request $request)
    {
        \session()->set('idcliev', $request->get('idcliev'));
        \session()->set('razov', $request->get('razov'));
        \session()->set('ruccliev', $request->get('ruccliev'));
        \session()->set('dnicliev', $request->get('dnicliev'));
        \session()->set('direcliev', $request->get('direcliev'));
        \session()->set('tdocv', $request->get('tdocv'));
        \session()->set('ndoc', $request->get('ndoc'));
        \session()->set('numv', $request->get('numv'));
        \session()->set('ndo2v', $request->get('ndo2v'));
        \session()->set('almv', $request->get('almv'));
        \session()->set('fechv', $request->get('fechv'));
        \session()->set('monev', $request->get('monev'));
        \session()->set('formv', $request->get('formv'));
        \session()->set('fechvv', $request->get('fechvv'));
        \session()->set('optigv', $request->get('optigv'));
        \session()->set('idvenv', $request->get('idvenv'));
        \session()->set('clienteretencion', $request->get('clienteretencion'));
        \session()->set('txtreferencia', $request->get('txtreferencia'));
    }
    function limpiarvta()
    {
        $this->limpiarSesionVtad();
        session()->set('moneda', 'S');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function index()
    {
        $this->limpiarSesionVtad();
        $titulo = "Ventas";
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        $datosclientev = array();
        $datosclientev = array(
            'idcliev' => \session()->get('idcliev', 0),
            'razov' => \session()->get('razov', ''),
            'dnicliev' => \session()->get('dnicliev', ''),
            'ruccliev' => \session()->get('ruccliev', 0),
            'tdocv' => \session()->get('tdocv', 03),
            'clienteretencion' => \session()->get('clienteretencion', 'N'),
            'cndocv' => $serie,
            'numv' => $num,
            'ndo2v' => \session()->get('ndo2v', ''),
            'almv' => \session()->get('almv', $_SESSION['idalmacen']),
            'fechv' => \session()->get('fechv', ''),
            'monev' => \session()->get('monev', ''),
            'formv' => \session()->get('formv', ''),
            'fechvv' => \session()->get('fechvv', ''),
            'idvenv' => \session()->get('idvenv', ''),
            'optigv' => \session()->get('optigv', 'I'),
            'txtreferencia' => \session()->get('txtreferencia', '')
        );
        session()->set("vista", "R");
        return view('ventasd/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa]);
    }
    function listarDetalle()
    {
        $idventa = \session()->get('idventa', 0);
        if ($idventa > 0) {
            $btn = 'Modificar';
        } else {
            $btn = 'Grabar';
        }
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        // $txtreferencia = \session()->get('txtreferencia', '');
        return view('ventasd/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siestaventas($idart)) {
            return true;
        } else {
            return false;
        }
    }
    function agregaritem(Request $request)
    {
        $idart = $request->get('txtcodigo');
        if ($this->verificarsiyaesta($idart)) {
            $data = [
                'message' => 'Producto ya agregado',
                'rpta' => 'N'
            ];
            return response()->json($data, 422);
        }
        $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatorio');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser Numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser Númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        $validarstock = (!empty($_SESSION['config']['validarstock']) ? $_SESSION['config']['validarstock'] : 'N');
        if ($validarstock == 'S') {
            $validar->rule("max", "txtcantidad", $stock)->message("Stock no disponible");
        }
        $validar->rule("min", "txtprecio", $preciomin)->message("Precio no permitido");
        $validar->labels([
            'precio' => 'txtprecio',
            'cantidad' => 'txtcantidad'
        ]);
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $producto = array();

        $producto = array(
            'coda' => $request->get("txtcodigo"),
            'descri' => $request->get("txtdescripcion"),
            'unidad' => $request->get('txtunidad'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get("txtprecio"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'tipoproducto' => $request->get('tipoproducto'),
            'costo' => $request->get('costo'),
            'caant' => 0
        );

        CarritoService::agregarItemVenta($producto, $request->get('cmbmoneda'));
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);

        $carritov = session()->get('carritov', []);

        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, [
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items,
            'carrito' => session()->get("carrito", [])
        ]);
    }
    function soloItem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'descri' => ($request->get('txtdescri')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval($request->get('txtprecio') <= 0.00  ? 1 : $request->get('txtprecio'))
        );
        CarritoService::editarProductoVenta($producto, $request->get('cmbmoneda'));
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function editardescuentoxproducto(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'descuento' => (floatval($request->get('descuento')) <= 0.00 ? 0 : $request->get('descuento'))
        );
        $preciofinal = CarritoService::editardescuentoxproducto($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto,
            'preciofinal' => $preciofinal
        ], 200);
    }

    function verificarvalorescarrito(Request $request)
    {
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        try {
            foreach ($detalle as $d) {
                $producto = array(
                    'id' => $d['id'],
                    'cant' => $d['cant'],
                    'precio' => $d['precio']
                );
                CarritoService::verificarvalorescarrito($producto);
            }
            return response()->json([
                'estado' => '1',
                'mensaje' => 'Se actualizo correctamente'
            ], 200);
        } catch (Exception $exep) {
            return response()->json([
                'estado' => '0',
                'mensaje' => 'No actualizo correctamente'
            ], 422);
        }
    }
    function limpiarSesionVtad()
    {
        session()->remove('carritov');
        session()->remove('cliente');
        session()->remove('idventa');
        session()->remove('idcliev');
        session()->remove('razov');
        session()->remove('txtcreditocliente');
        session()->remove('ruccliev');
        session()->remove('tdocv');
        session()->remove('cndocv');
        session()->remove('numv');
        session()->remove('ndo2v');
        session()->remove('almv');
        session()->remove('formv');
        session()->remove('monev');
        session()->remove('fechv');
        session()->remove('fechvv');
        session()->remove('idvenv');
        session()->remove('txtreferencia');
        session()->set("mensajesunat", "");
    }
    function registrar(Request $request)
    {
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        if (!validarrucregistro($request->get("txtruccliente"), $request->get("tdocv"))) {
            return response()->json(['errors' => 'No se puede hacer una venta a la misma empresa'], 422);
        }
        if (!validardiasatrasocpe($request->get("fechv"))) {
            return response()->json(['errors' => 'No se puede emitir una venta con más de dos días de atraso'], 422);
        }
        if (!validardiasadelantocpe($request->get("fechv"))) {
            return response()->json(['errors' => 'No se puede emitir una venta con un día de adelanto'], 422);
        }
        $validarcreditoxcliente = (empty($_SESSION['config']['validarcreditoxcliente']) ? 'N' : $_SESSION['config']['validarcreditoxcliente']);
        if ($validarcreditoxcliente == 'S') {
            if ($request->get('formv') == 'C') {
                if (floatval(CarritoService::totalVenta()) > floatval($request->get('txtcreditocliente'))) {
                    return response()->json(['errors' => ['El limite máximo de crédito para ese cliente es: ' . $request->get('txtcreditocliente')]], 422);
                }
                $ctas = new CtasporCobrar();
                $lista = $ctas->vencimientosporcliente($request->get("idcliev"), '2025-01-01', date('Y-m-d'));
                $importedeuda = array_column($lista['lista']['items'], 'importe');
                $totaldeuda = array_sum($importedeuda);
                $totalcreditoconventa = floatval($totaldeuda) + floatval(CarritoService::totalVenta());
                if (floatval($request->get('txtcreditocliente') < $totalcreditoconventa)) {
                    return response()->json(['errors' => ['Excede del limite de crédito establecido a ese cliente:  ' . $request->get('txtcreditocliente')]], 422);
                }
            }
        }
        // if (!empty($request->get("creditosporcuotas"))) {
        //     $creditosporcuotas = json_decode($request->get("creditosporcuotas"));
        //     $creditosporcuotas = json_decode(json_encode($creditosporcuotas), true);
        // } else {
        //     $creditosporcuotas = [];
        // }
        // $carritov = session()->get('carritov', []);
        // foreach ($carritov as $c) {
        //     if ($c['activo'] == 'A') {
        //         if (floatval($c['precio']) == 0 || floatval($c['cantidad'] == 0)) {
        //             return response()->json(['errors' => 'No se puede registrar una venta con un precio o cantidad con valor 0'], 422);
        //         }
        //     }
        // }
        // $descuentogeneral = 0;
        // $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        // if ($ventascondescuento == 'S') {
        //     $descuentogeneral = $request->get('descuentogeneral');
        // }
        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get("razov"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            'txtidautovtaanticipo' => $request->get('txtidautovtaanticipo'),
            'txttotalanticipo' => $request->get('txttotalanticipo'),
            "nitem" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT),
            'optigv' => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia"),
            "txtpago" => $request->get("txtpago"),
            "txtefectivo" => $request->get("txtefectivo"),
            // "creditosporcuotas" => $creditosporcuotas,
            // "descuentogeneral" => $descuentogeneral,
            "usuario" => $_SESSION['usuario']
        );
        $registro = $venta->grabarVentaGeneral($cabecera);
        if ($registro['estado'] == 1) {
            $carritodetalle = [];
            $carritov = session()->get('carritov', []);
            foreach ($carritov as $c) {
                if ($c['activo'] == 'A') {
                    array_push($carritodetalle, $c);
                }
            }
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] =  $carritodetalle;
            $_SESSION['ndoc'] = $registro['ndoc'];
            $this->limpiarSesionVtad();
            // $carritov = session()->get('carritov', []);
            $rpta = array('mensaje' => "Se Genero la venta ", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta, 200);
        } else {
            return response()->json(['message' => 'Error al registrar Venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function modificar(Request $request)
    {
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        if (!empty($request->get("creditosporcuotas"))) {
            $creditosporcuotas = json_decode($request->get("creditosporcuotas"));
            $creditosporcuotas = json_decode(json_encode($creditosporcuotas), true);
        } else {
            $creditosporcuotas = [];
        }
        $descuentogeneral = 0;
        $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        if ($ventascondescuento == 'S') {
            $descuentogeneral = $request->get('descuentogeneral');
        }
        $validarcreditoxcliente = (empty($_SESSION['config']['validarcreditoxcliente']) ? 'N' : $_SESSION['config']['validarcreditoxcliente']);
        if ($validarcreditoxcliente == 'S') {
            if ($request->get('formv') == 'C') {
                if (floatval(CarritoService::totalVenta()) > floatval($request->get('txtcreditocliente'))) {
                    return response()->json(['errors' => ['El limite máximo de crédito para ese cliente es: ' . $request->get('txtcreditocliente')]], 422);
                }
                $ctas = new CtasporCobrar();
                $lista = $ctas->vencimientosporcliente($request->get("idcliev"), '2025-01-01', date('Y-m-d'));
                $importedeuda = array_column($lista['lista']['items'], 'importe');
                $totaldeuda = array_sum($importedeuda);
                $totalcreditoconventa = floatval($totaldeuda) + floatval(CarritoService::totalVenta());
                if (floatval($request->get('txtcreditocliente') < $totalcreditoconventa)) {
                    return response()->json(['errors' => ['Excede del limite de crédito establecido a ese cliente:  ' . $request->get('txtcreditocliente')]], 422);
                }
            }
        }
        $numeroDocumento = $_SESSION['nroventa'];
        $venta = new Ventas();
        $deta =  "";
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "razov" => $request->get('razov'),
            "tdocv" => $request->get("tdocv"),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "cndocv" => $numeroDocumento,
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "fechvv" => $request->get("fechvv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nidautov" => $request->get("idautov"),
            'optigv' => $request->get('optigv'),
            "detav" => $deta,
            "nitemsv" => str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT),
            "txtreferencia" => $request->get("txtreferencia"),
            'txtidautovtaanticipo' => $request->get('txtidautovtaanticipo'),
            "descuentogeneral" => $descuentogeneral,
            "creditosporcuotas" => $creditosporcuotas,
            "txtpago" => $request->get("txtpago"),
            "txtefectivo" => $request->get("txtefectivo")
        );
        $rpta = $venta->actualizarVenta($cabecera);
        if ($rpta['estado'] == '1') {
            $this->limpiarSesionVtad();
            session()->set('carritov', []);
            $carritov = session()->get('carritov', []);
            $total = number_format(CarritoService::totalVenta(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('ventasd', 'detalle');
            return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'numeroDocumento' => $numeroDocumento]);
        } else {
            return response()->json(['message' => 'Error al modificar la venta' . $rpta['mensaje']], 422);
        }
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemVenta($pos);
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function buscarVentaPorID($idauto)
    {
        $venta = new Ventas();
        $nroventa = "";
        $idautov = $idauto;
        $this->limpiarSesionVtad();
        $carritov = session()->get('carritov', []);
        $lista = $venta->buscarVentaPorId($idauto);
        $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        session()->set('idventa', $idautov);
        $datosclientev = array();
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $razo = str_replace('"', ' ', $item['razo']);
                $descuentogeneral = ($ventascondescuento == 'S' ? $item['rcom_desc'] : 0);
                $datetime1 = date_create($item['fech']);
                $datetime2 = date_create($item['fvto']);
                $interval = date_diff($datetime2, $datetime1);
                // $oimp->dias=$fila['dias'];
                $ndias = $interval->days;
                $datosclientev = array(
                    'idauto' => $item['idauto'],
                    'almv' => $item['alma'],
                    'fechv' => $item['fech'],
                    'fvto' => $item['fvto'],
                    'formv' => $item['form'],
                    'tdocv' => $item['tdoc'],
                    'dolarv' => $item['dolar'],
                    'tipov' => $item['tipo'],
                    'monev' => $item['mone'],
                    'razov' => $razo,
                    'idcliev' => $item['idclie'],
                    'ruccliev' => $item['nruc'],
                    'dnicliev' => $item['ndni'],
                    'clienteretencion' => $item['clie_rete'],
                    'ndo2v' => $item['ndo2'],
                    'idvenv' => $item['codv'],
                    'optigv' => $item['incl'],
                    'dias' => $ndias,
                    'txtreferencia' => (isset($item['deta'])) ? $item['deta'] : '',
                    'subtotalv' => $item['valor'],
                    'igvv' => $item['igv'],
                    'impov' => $item['impo'],
                    'mensajesunat' => $item['rcom_mens'],
                    'rcom_idan' => $item['rcom_idan'],
                    'descuentogeneral' => $descuentogeneral
                );
                $nroventa = $item['ndoc'];
            }

            $preciosindescuento = $item["prec"];
            $descuentoxproducto = ($ventascondescuento == 'S' ? $item['kar_desc'] : 0);
            if ($descuentoxproducto != 0) {
                $preciosindescuento = $item['prec'] / (1 - ($descuentoxproducto / 100));
            }
            $carritov[] = array(
                'coda' => $item["Coda"],
                'descripcion' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'precio' => $item["prec"],
                'nreg' => $item["idkar"],
                'costo' => $item["costo"],
                'stock' => $item['TAlma'],
                'precio1' => $item['pre1'],
                'precio2' => $item['pre2'],
                'tipoproducto' => $item['tipro'],
                'precio3' => $item['pre3'],
                'idclie' => $item['idclie'],
                'caant' => $item['cant'],
                'descuento' => $descuentoxproducto,
                'preciosindescuento' => $preciosindescuento,
                'activo' => 'A'
            );
        }
        session()->set('opigv', 'N'); //ESTO PARA QUE SOLO SE VEA EL IGV DE MANERA VISUAL
        session()->set('moneda', 'SI'); //(SIGNIFICA QUE YA HA SIDO SELECCIONADA) ESTO PARA QUE SOLO SE VEA LA MONEDA DE MANERA VISUAL
        session()->set('carritov', $carritov);
        session()->set('datosclientev', $datosclientev);
        session()->set("mensajesunat", $datosclientev['mensajesunat']);
        $carritov = session()->get('carritov', []);
        $datosclientev = session()->get('datosclientev', []);

        $titulo = 'Actualizar Venta' . ' ' . $nroventa;
        session()->set('nroventa', $nroventa);

        $serie = substr($nroventa, 0, 4);
        $num = substr($nroventa, 4);

        session()->set('idventa', $idauto);

        $rcom_idan = $datosclientev['rcom_idan'];
        $importeanticipado = 0;
        $idautoanticipado = 0;
        $ndocanticipado = "";
        if ($rcom_idan != '0') {
            $datosvtaanticipado = $venta->obtenerdatosdevtaanticipada($rcom_idan);
            $importeanticipado = $datosvtaanticipado[0]['impo'];
            $idautoanticipado = $datosvtaanticipado[0]['idauto'];
            $ndocanticipado = $datosvtaanticipado[0]['ndoc'];
        }

        $cvista = \retornavista('ventasd', 'index');

        \session()->set('idcliev', $datosclientev['idcliev']);
        \session()->set('razov',  $datosclientev['razov']);
        \session()->set('ruccliev',  $datosclientev['ruccliev']);
        \session()->set('tdocv',  $datosclientev['tdocv']);
        \session()->set('clienteretencion',  $datosclientev['clienteretencion']);
        \session()->set('cndocv',  $serie);
        \session()->set('numv', $num);
        \session()->set('ndo2v',  $datosclientev['ndo2v']);
        \session()->set('almv',  $datosclientev['almv']);
        \session()->set('formv',  $datosclientev['formv']);
        \session()->set('monev',  $datosclientev['monev']);
        \session()->set('fechv',  $datosclientev['fechv']);
        \session()->set('idvenv',  $datosclientev['idvenv']);
        \session()->set('txtreferencia',  $datosclientev['txtreferencia']);
        \session()->set("vista", "E");
        // if (count($carritov) < 1) {
        //     header('Location: /vtas/vtasresumidas?vtaregxclavesol=1');
        //     return;
        // }
        return view($cvista, [
            'titulo' => $titulo,
            'datosclientev' => $datosclientev,
            'idventa' => $idautov,
            'serie' => $serie,
            'num' => $num,
            'carritov' => $carritov,
            'importeanticipado' => $importeanticipado,
            'idautoanticipado' => $idautoanticipado,
            'ndocanticipado' => $ndocanticipado
        ]);
    }
    function ventasresumidas()
    {
        $ctitulo = 'Reporte de ventas';
        return view('ventas/informes/indexlistarvtas', [
            "titulo" => $ctitulo
        ]);
    }
    function mostrarventasresumidas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $tipovta = $request->get("tipovta");
        $cmbformap = $request->get("cmbFormaP");
        $cmbmoneda = $request->get("cmbmoneda");
        $cmbtdoc = $request->get("cmbtdoc");
        $cmbAlmacen = $request->get("cmbAlmacen");
        $ventas = new Ventas();
        $listado = $ventas->mostrarventas($dfi, $dff, $tipovta, $cmbformap, $cmbmoneda, $cmbtdoc, $cmbAlmacen);
        return view('ventas/informes/listarvtas', [
            "listado" => $listado
        ]);
    }
    function indexlistarutilidades()
    {
        $ctitulo = 'Reporte de Ganancias';
        return view('ventas/informes/indexlistarvtasutilidades', [
            "titulo" => $ctitulo
        ]);
    }
    function mostrarvtasutilidades(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbAlmacen = $request->get("cmbAlmacen");
        $cmbvendedor = $request->get("cmbvendedor");
        $ventas = new Ventas();
        $listado = $ventas->mostrarventasutilidades($dfi, $dff, $cmbAlmacen, $cmbvendedor);
        return view('ventas/informes/listarvtasutilidades', [
            "listado" => $listado
        ]);
    }
    function indexlistarutilidadesdetallada()
    {
        $ctitulo = 'Reporte de Rentabilidad Detallado';
        return view('ventas/informes/indexlistarutilidadesdetallada', [
            "titulo" => $ctitulo
        ]);
    }
    function listarutilidadesdetalle(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbAlmacen = $request->get("cmbAlmacen");
        $cmbvendedor = $request->get("cmbvendedor");
        $cmbmarca = $request->get("cmbmarca");
        $cmbcategoria = $request->get("cmbcategoria");
        $ventas = new Ventas();
        $listado = $ventas->listarutilidadesdetalle($dfi, $dff, $cmbAlmacen, $cmbvendedor, $cmbmarca, $cmbcategoria);
        return view('ventas/informes/listarutilidadesdetalle', [
            "listado" => $listado
        ]);
    }
    function imprimirdirecto()
    {
        $fila = $_SESSION['datosovta'];
        $detalle = $_SESSION['detallev'];

        $oimp = new Imprimir();
        $cletras = new Cletras();
        $i = 1;
        foreach ($detalle as $item) {
            $subtotal = (floatval($item['cantidad']) * floatval($item['precio']) > 0) ?  round($item['cantidad'] * $item['precio'], 2) : 0;
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $item['unidad'],
                'descri' => $item['descripcion'],
                'cant' => $item['cantidad'],
                'prec' => $item['precio'],
                'descuento' => $item['descuento'],
                'preciosindescuento' => $item['preciosindescuento'],
                'subtotal' =>   $subtotal
            );
            // $tpeso += $item['cantidad'] * $item['precio'];
            if ($i == 1) {
                $oimp->empresa = session()->get('gene_empresa');
                $oimp->rucempresa = session()->get('gene_nruc');
                $oimp->direccionempresa = session()->get('gene_ptop');
                $oimp->moneda = (trim($fila['monev']) == 'S' ? 'SOLES' : 'DOLARES');
                $oimp->tdoc =  $fila['tdocv'];
                switch ($fila['tdocv']) {
                    case '01':
                        $oimp->tipocomprobante = ' FACTURA ELECTRONICA';
                        break;
                    case '03':
                        $oimp->tipocomprobante = ' BOLETA DE VENTA ELECTRONICA ';
                        break;
                    case '20':
                        $oimp->tipocomprobante = ' NOTA DE VENTA      ';
                        break;
                    default:
                        $oimp->tipocomprobante = ' NOTA DE CREDITO      ';
                        break;
                }
                // $oimp->tipocomprobante = $fila['tdoc'];
                $oimp->fecha = date("d/m/Y", strtotime($fila['fechv']));
                $oimp->guiaremision = $fila['ndo2v'];
                $oimp->optigv = $fila['optigv'];
                $oimp->clienteretencion = ($fila['tdocv'] == '01') ? $fila['txtclienteretencion'] : 'N';
                $oimp->referencia = $fila['txtreferencia'];
                // $oimp->fecha = $dfecha;
                $oimp->fechavto = date("d/m/Y", strtotime($fila['fechvv']));
                $oimp->vendedor = "OFICINA";
                $datetime1 = date_create($fila['fechvv']);
                $datetime2 = date_create($fila['fechv']);
                $interval = date_diff($datetime2, $datetime1);
                $oimp->dias = $interval->days;
                $oimp->numero =  substr($_SESSION['ndoc'], 0, 4) . '-' . substr($_SESSION['ndoc'], 4, 8);
                // $oimp->dias=$fila['dias'];
                $oimp->cliente = $fila['razov'];
                $oimp->direccioncliente = $fila['txtdireccion'];
                $oimp->detraccion = empty($fila['detraccion']) ? 0 : $fila['txtdetraccion'];
                $formadepago = "";
                switch ($fila['formv']) {
                    case 'E':
                        $formadepago = 'EFECTIVO';
                        break;
                    case 'C':
                        $formadepago =  'CRÉDITO';
                        break;
                    case 'D':
                        $formadepago =  'DEPÓSITO';
                        break;
                    case 'T':
                        $formadepago = 'TARJETA';
                        break;
                    case 'Y':
                        $formadepago =  'YAPE';
                        break;
                    case 'P':
                        $formadepago = 'PLIN';
                        break;
                }
                $oimp->formadepago = $formadepago;
                $oimp->totalanticipo = empty($fila['txttotalanticipo']) ? '0' :  $fila['txttotalanticipo'];
                $oimp->importeletras = $cletras->ValorEnLetras($fila['total'], $fila['monev'] === 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['subtotal'];
                $oimp->ruccliente = $fila['txtruccliente'];
                $oimp->dnicliente = $fila['txtdnicliente'];
                $oimp->igv = $fila['igv'];
                $oimp->vigv = session()->get('gene_igv');
                $oimp->usuario = empty($fila['usuario']) ? '' : $fila['usuario'];
                $oimp->total = $fila['total'];
                $oimp->fusua = Date('Y-m-d h:m:s');
                $oimp->descuentogeneral = empty($fila['descuentogeneral']) ? 0 : $fila['descuentogeneral'];
                $oimp->creditosporcuotas = empty($fila['creditosporcuotas']) ? [] : $fila['creditosporcuotas'];
                $rutapdf = 'descargas/' . $_SESSION['ndoc'] . '.pdf';
            }
            $i++;
        }
        # I ES PARA GUARDAR EN EL SERVIDOR
        # PARA DESCARGAR EL ARCHIVO ES D
        if ($_SESSION['config']['impresionticket'] == 'S') {
            $oimp->generarpdfticket($rutapdf, 'I');
        } else {
            $oimp->generapdf($rutapdf, 'I');
        }
        $_SESSION['datosovta'] = [];
        $_SESSION['detallev'] = [];
        $_SESSION['ndoc'] = "";
    }
    //Canje de guia remisión
    function indexcanjes()
    {
        $titulo = "Facturar Guías";
        return view('canjes/index', ['titulo' => $titulo]);
    }
    function listarDetallecanjesguias()
    {
        $carritov = session()->get('carritocanje', []);
        $total = number_format(CarritoServiceCanje::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceCanje::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        return view('canjes/detallecanje', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function listarDetalleCanje(Request $request)
    {
        $guia = new GuiaRemitente();
        $idguia = $request->get('idguia');
        $kardex = $guia->consultarGuiaDetalle($idguia, 'V');
        foreach ($kardex as $item) {
            $idautov = $item['idautov'];
            $idautog = $item['idautog'];
            $cguia = $item['idgui'];
            $carritov[] = array(
                'coda' => $item["coda"],
                'descripcion' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'precio' => $item["pre3"],
                'nreg' => 0,
                'costo' => $item["costo"],
                'stock' => 0,
                'precio1' => 0,
                'precio2' => 0,
                'precio3' => $item["pre3"],
                'idclie' => 0,
                'activo' => 'A',
                'tipoproducto' => $item['tipoproducto'],
                'idkar' => $item['idkar']
            );
        }
        $total = number_format(CarritoServiceCanje::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceCanje::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $gene_detra = session()->get('gene_gene_detr', '');
        // session()->set('carritocanje', $carritov);
        return view('canjes/detallecanje', [
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items,
            'guia' => $cguia,
            'gene_detra' => $gene_detra,
            'idautov' => $idautov,
            'idautog' => $idautog
        ]);
    }
    function registrarCanje(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliev");
        $validar->rule("required", "tdocv");
        // $validar->rule("required", "almv");
        $validar->rule("required", "fechv");
        $validar->rule("required", "monev");
        $validar->rule("required", "formv");
        $validar->rule("required", "fechvv");
        $validar->rule("required", "idvenv");
        $validar->rule("required", "subtotal");
        $validar->rule("required", "igv");
        $validar->rule("required", "total");
        $datetime1 = date_create($request->get('fechvv'));
        $datetime2 = date_create($request->get('fechv'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechv'))))) {
            $data = ["errors" => ['Fecha de emisión no válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechvv'))))) {
            $data = ["errors" => ['Fecha de vencimiento no válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($request->get("formv") == 'C') {
            if ($ndias <= 0) {
                $data = ["errors" => ['Es obligatorio los días de Crédito mayor a 0'], "estado" => 0];
                return $data;
            }
        }
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        if ($request->get('tdocv') == '03') {
            if ((floatval($request->get('total')) > 700) && (empty($request->get('txtdnicliente')))) {
                $data = ["errors" => ['No se puede registrar la venta, porque el cliente no tiene DNI'], "estado" => 0];
                return $data;
            }
        }
        $venta = new Ventas();
        $cabecera = array(
            "idautov" => $request->get("idautov"),
            "idautog" => $request->get("idautog"),
            "idcliev" => $request->get("idcliev"),
            "iddire" => $request->get("iddire"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get('razov'),
            "ndo2v" => $request->get("ndo2v"),
            // "almv" => $request->get("almv"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" =>  $request->get("totalitems"),
            "dias" => $request->get("txtdias"),
            "optigv" => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia")
        );
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        if ($this->validarDetalleCanje($detalle) == false) {
            return response()->json(['message' => 'No hay precio en el detalle'], 422);
        }
        $rpta = $venta->grabarVentaCanje($cabecera, $detalle);
        if ($rpta['estado'] == "1") {
            $carritocanje = $detalle;
            $this->limpiarSesionVtad();
            $_SESSION['idautov'] = "";
            $_SESSION['idautog'] = "";
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $carritocanje;
            $_SESSION['ndoc'] = $rpta['ndoc'];
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    //Canje de guia transportista
    function indexcanjestr()
    {
        $titulo = "Canjes x Transportista";
        $detalle = [];
        return view('canjestr/index', ['titulo' => $titulo, 'detalle' => $detalle]);
    }
    function listarDetalleCanjeTr(Request $request)
    {
        $guia = new GuiaTransportista();
        $idguia = $request->get('idguia');
        $kardex = $guia->consultarGuiaTrDetalle($idguia);
        foreach ($kardex as $item) {
            // $_SESSION['idautov'] = $item['idautov'];
            // $_SESSION['idautog'] = $item['idautog'];
            // $cguia = $item['idgui'];
            $detalle[] = array(
                'descri' => $item["entr_deta"],
                'unidad' => $item['entr_unid'],
                'cant' => $item['entr_cant'],
                'precio' => 0,
                'subt' => 0,
                'activo' => 'A'
            );
        }
        $_SESSION['idautog'] = $request->get('idguia');
        return view('canjestr/detalle', ['detalle' => $detalle]);
    }
    function registrarcanjetr(Request $request)
    {
        $ovalidar = $this->validar($request, 'O');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "iddire" => $request->get("iddire"),
            "tdocv" => $request->get("tdocv"),
            "cndocv" => "",
            "razov" => $request->get('razov'),
            "ndo2v" => $request->get("ndo2v"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => session()->get('usuario_id'),
            "nitem" => 0,
            "detraccion" => $request->get("detraccion")
        );

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $rpta = $venta->grabarVentaCanjetr($cabecera, $detalle);

        if ($rpta['estado'] == 1) {
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] = $detalle;
            $_SESSION['ndoc'] = $rpta['ndoc'];
            // $fila = $_SESSION['datosovta'];
            // $detalle = $_SESSION['detallev'];
            $_SESSION['idautog'] = "";
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    function listarvtasnota(Request $request)
    {
        $idCliente = $request->get('idCliente');
        $ventas = new Ventas();
        $listado = $ventas->consultarVentasPorCliente($idCliente);
        return view('notascredito/tm_listavtas', [
            "listado" => $listado
        ]);
    }
    function listardetallenota(Request $request)
    {
        $idauto = $request->get('idauto');
        $tipoventa = $request->get('tipoventa');
        $ventas = new Ventas();
        if ($tipoventa == 'K') {
            $listado = $ventas->consultarDetalleVtaDirecta($idauto);
        } else {
            $listado = $ventas->consultarDetalleVtaServicio($idauto);
        }
        return view('notascredito/detalle', [
            "listado" => $listado
        ]);
    }
    //Validar
    function validar($request, $tipovta = 'V')
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliev")->message("Es obligatorio el Cliente");
        $validar->rule("required", "tdocv");
        if ($tipovta == 'V') {
            $validar->rule("required", "almv");
        }
        $validar->rule("required", "fechv")->message("Fecha de Emisión no es Válida");
        $validar->rule("required", "monev");
        $validar->rule("required", "formv");
        $validar->rule("required", "fechvv")->message("Fecha de Vencimiento no es Válida");;
        $validar->rule("required", "idvenv");
        $validar->rule("required", "subtotal");
        $validar->rule("required", "igv");
        $validar->rule("required", "total");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors(), "estado" => 0];
            return $data;
        }
        $datetime1 = date_create($request->get('fechvv'));
        $datetime2 = date_create($request->get('fechv'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechv'))))) {
            $data = ["errors" => ['Fecha de Emisión no Válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechvv'))))) {
            $data = ["errors" => ['Fecha de Vencimiento no Válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($request->get("formv") == 'C') {
            if ($ndias <= 0) {
                $data = ["errors" => ['Es obligatorio los días de Crédito mayor a 0'], "estado" => 0];
                return $data;
            }
            if ($request->get('idcliev') == '2') {
                $data = ["errors" => ['No se puede dar crédito a un cliente generico'], "estado" => 0];
                return $data;
            }
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => ["Sesión vacía"], "estado" => 0];
            return $data;
        }
        if ($tipovta == 'V') {
            if (empty($_SESSION["carritov"])) {
                $data = ["errors" => ['Se requiere productos para registrar la venta'], "estado" => 0];
                return $data;
            }
            $carritov = session()->get('carritov', []);
            foreach ($carritov as $c) {
                if ($c['activo'] == 'A') {
                    if (floatval($c['precio']) == 0 || floatval($c['cantidad'] == 0)) {
                        $data = ["errors" => ['No se puede ingresar una venta con un precio o cantidad con valor 0. Ingrese un valor para el producto ' . trim($c['descripcion'])], "estado" => 0];
                        return $data;
                    }
                }
            }
            $validarstock = (!empty($_SESSION['config']['validarstock']) ? $_SESSION['config']['validarstock'] : 'N');
            if ($validarstock == 'S') {
                foreach ($_SESSION['carritov'] as $item) {
                    if (intval($item['cantidad']) > intval($item['stock'])) {
                        $data = ["errors" => ['El producto ' . $item['descripcion'] . ' solo tiene ' . $item['stock']], "estado" => 0];
                        return $data;
                    }
                }
            }
        }
        $crpta = session()->get("mensajesunat", '');
        if (substr($crpta, 0, 1) == '0') {
            $data = ["errors" => ['Este documento ya fue informado a SUNAT. No es posible actualizar'], "estado" => 0];
            return $data;
        }
        if ($request->get('tdocv') == '03') {
            if ((floatval($request->get('total')) > 700) && (empty($request->get('txtdnicliente')))) {
                $data = ["errors" => ['No se puede registrar la venta, porque el cliente no tiene DNI'], "estado" => 0];
                return $data;
            }
        }
        $data = ["errors" => ["ok"], "estado" => 1];
        return $data;
    }
    function validarcanjepedido($request, $tipovta = 'V')
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliev")->message("Es obligatorio el Cliente");
        $validar->rule("required", "tdocv");
        if ($tipovta == 'V') {
            $validar->rule("required", "almv");
        }
        $validar->rule("required", "fechv")->message("Fecha de Emisión no es Válida");
        $validar->rule("required", "monev");
        $validar->rule("required", "formv");
        $validar->rule("required", "fechvv")->message("Fecha de Vencimiento no es Válida");;
        $validar->rule("required", "idvenv");
        $validar->rule("required", "subtotal");
        $validar->rule("required", "igv");
        $validar->rule("required", "total");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors(), "estado" => 0];
            return $data;
        }
        $datetime1 = date_create($request->get('fechvv'));
        $datetime2 = date_create($request->get('fechv'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechv'))))) {
            $data = ["errors" => ['Fecha de Emisión no Válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('fechvv'))))) {
            $data = ["errors" => ['Fecha de Vencimiento no Válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($request->get("formv") == 'C') {
            if ($ndias <= 0) {
                $data = ["errors" => ['Es obligatorio los días de Crédito mayor a 0'], "estado" => 0];
                return $data;
            }
            if ($request->get('idcliev') == '2') {
                $data = ["errors" => ['No se puede dar crédito a un cliente generico'], "estado" => 0];
                return $data;
            }
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => ["Sesión vacía"], "estado" => 0];
            return $data;
        }
        if ($tipovta == 'V') {
            if (empty($_SESSION["carritov"])) {
                $data = ["errors" => ['Se requiere productos para registrar la venta'], "estado" => 0];
                return $data;
            }
            $carritov = session()->get('carritov', []);
            foreach ($carritov as $c) {
                if ($c['activo'] == 'A') {
                    if (floatval($c['precio']) == 0 || floatval($c['cantidad'] == 0)) {
                        $data = ["errors" => ['No se puede ingresar una venta con un precio o cantidad con valor 0. Ingrese un valor para el producto ' . trim($c['descripcion'])], "estado" => 0];
                        return $data;
                    }
                }
            }
            $validarstock = (!empty($_SESSION['config']['validarstock']) ? $_SESSION['config']['validarstock'] : 'N');
            if ($validarstock == 'S') {
                foreach ($_SESSION['carritov'] as $item) {
                    if (intval($item['cantidad']) > intval($item['stock'])) {
                        $data = ["errors" => ['El producto ' . $item['descripcion'] . ' solo tiene ' . $item['stock']], "estado" => 0];
                        return $data;
                    }
                }
            }
        }
        if ($request->get('tdocv') == '03') {
            if ((floatval($request->get('total')) > 700) && (empty($request->get('txtdnicliente')))) {
                $data = ["errors" => ['No se puede registrar la venta, porque el cliente no tiene DNI'], "estado" => 0];
                return $data;
            }
        }
        $data = ["errors" => ["ok"], "estado" => 1];
        return $data;
    }
    function validarDetalleCanje($detalleCanje)
    {
        foreach ($detalleCanje as $d) {
            if (empty($d['precio'])) {
                return false;
            }
        }
        return true;
    }
    function indexvtasxvendedor()
    {
        return view('ventasd/informes/indexvtasxvendedor', ['titulo' => 'Ventas por Vendedor']);
    }
    function listavtasxvendedor(Request $request)
    {
        $ventas = new Ventas();
        $listado = $ventas->mostrarresumenvtasvendedor($request->get('dfechai'), $request->get('dfechaf'), $request->get("nidv"), $request->get("cmbAlmacen"));
        return view('ventasd/informes/listavtasxvendedor', ["listado" => $listado]);
    }
    function indexventadproducto()
    {
        return \view('ventasd/informes/indexlistavdp', ['titulo' => 'Rotación de Productos - Ventas']);
    }
    function listarventadproducto(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $cmbmarca = $request->get("cmbmarca");
        $venta = new Ventas();
        $lista = $venta->listarVentasxProducto($dfi, $dff, $cmbAlmacen, $cmbmarca);
        return \view('ventasd/informes/re_lventasdproducto', ['listado' => $lista]);
    }
    function indexlistavxcliente()
    {
        return \view('ventasd/informes/indexlistavxcliente', ['titulo' => 'Informes de Vtas x Cliente']);
    }
    function listavtasxcliente(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $idclie = $request->get('idclie');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $venta = new Ventas();
        $lista = $venta->listarVentasxCliente($dfi, $dff, $idclie, $cmbAlmacen);
        $ventasByNdoc = array();
        $ndoc = '';
        $i = 0;
        foreach ($lista as $l) {
            if ($i == 0) {
                $ndoc = $l['ndoc'];
                $ventasByNdoc[$ndoc][$i] = $l;
                $i = $i + 1;
            } else {
                if ($ndoc == $l['ndoc']) {
                    $ventasByNdoc[$ndoc][$i] = $l;
                    $i = $i + 1;
                } else {
                    $i = 0;
                    $ndoc = $l['ndoc'];
                    $ventasByNdoc[$ndoc][$i] = $l;
                    $i = 1;
                }
            }
        }
        return \view('ventasd/informes/listavtasxcliente', ['listado' => $ventasByNdoc]);
    }
    function indexcanjespedidos()
    {
        $titulo = "Facturar Cotizaciones";
        return view('canjespedidos/index', ['titulo' => $titulo]);
    }
    function registrarcanjepedido(Request $request)
    {
        $ovalidar = $this->validarcanjepedido($request, 'N');
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "razov" => $request->get("razov"),
            "txtdireccion" => $request->get("txtdireccion"),
            "txtruccliente" => $request->get("txtruccliente"),
            "txtdnicliente" => $request->get("txtdnicliente"),
            'txtclienteretencion' => $request->get('txtclienteretencion'),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => $request->get("subtotal"),
            "igv" => $request->get("igv"),
            "total" => $request->get("total"),
            "nidus" => $request->get('idusua'),
            'idautop' => $request->get('idautop'),
            "nitem" => str_pad(count($detalle), 2, '0', STR_PAD_LEFT),
            'optigv' => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia"),
            "usuario" => $request->get('usuario')
        );

        $_SESSION['carritov'] = $detalle;
        $registro = $venta->grabarVentaGeneral($cabecera);

        $pedido = new Pedido();
        $cambestped = $pedido->cambiarEstado($request->get("idautop"));


        if ($cambestped['estado'] == 0) {
            return response()->json(['message' => 'Error al actualizar estado de pedido', 'error' => $cambestped['mensaje']], 422);
        }

        if ($registro['estado'] == 1) {
            $carritov = session()->get('carritov', []);
            $_SESSION['datosovta'] = $cabecera;
            $_SESSION['detallev'] =  $carritov;
            $_SESSION['ndoc'] = $registro['ndoc'];
            $this->limpiarSesionVtad();
            $rpta = array('mensaje' => "Se Genero la venta ", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta);
        } else {
            return response()->json(['message' => 'Error al registrar venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function verutilidad(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            $utilidad = CarritoService::verutilidad();
            return response()->json(['message' => Round($utilidad, 2)], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas'], 422);
        }
    }
    function detailchangedolar(Request $request)
    {
        $tmon = $request->get('moneda');
        if ($tmon == 'D') {
            CarritoService::cambiardetalledolar();
        }
        session()->set('moneda', 'SI');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasd', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function indexlistaventasxano()
    {
        $titulo = "Lista Vtas x Año";
        return view('ventasd/informes/indexlistavtasxano', ['titulo' => $titulo]);
    }
    function listaventasxano(Request $request)
    {
        $venta = new Ventas();
        $rpta = $venta->reporteestadistico($request->get('ano'), $request->get('cmbAlmacen'));
        return view('ventasd/informes/listavtasxano', ['listado' => $rpta]);
    }
    function indexvtasrapidas()
    {
        $this->limpiarSesionVtad();
        $titulo = "Venta Rápida";
        $serie = \session()->get('cndocv', '');
        $num = \session()->get('numv', '');
        $idventa = \session()->get('idventa', 0);
        $datosclientev = array();
        session()->set("vista", "R");
        return view('ventasrapidas/index', ['titulo' => $titulo, 'datosclientev' => $datosclientev, 'serie' => $serie, 'num' => $num, 'idventa' => $idventa]);
    }
    function listardetallevtarapida()
    {
        $btn = 'Grabar';
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        // $txtreferencia = \session()->get('txtreferencia', '');
        return view('ventasrapidas/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function agregaritemvtarapida(Request $request)
    {
        $idart = $request->get('txtcodigo');
        if ($this->verificarsiyaesta($idart)) {
            $data = [
                'message' => 'Producto ya agregado',
                'rpta' => 'N'
            ];
            return response()->json($data, 422);
        }
        $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El precio debe ser numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerica');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        $validarstock = (!empty($_SESSION['config']['validarstock']) ? $_SESSION['config']['validarstock'] : 'N');
        if ($validarstock == 'S') {
            $validar->rule("max", "txtcantidad", $stock)->message("Stock no disponible");
        }

        $validar->rule("min", "txtprecio", $preciomin)->message("Precio no permitido");
        $validar->labels([
            'precio' => 'txtprecio',
            'cantidad' => 'txtcantidad'
        ]);
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $producto = array();

        $producto = array(
            'coda' => $request->get("txtcodigo"),
            'descri' => $request->get("txtdescripcion"),
            'unidad' => $request->get('txtunidad'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get("txtprecio"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'tipoproducto' => $request->get('tipoproducto'),
            'costo' => $request->get('costo'),
            'caant' => 0
        );

        CarritoService::agregarItemVenta($producto, $request->get('cmbmoneda'));
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);

        $carritov = session()->get('carritov', []);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, [
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items,
            'carrito' => session()->get("carrito", [])
        ]);
    }
    function soloitemvtarapida(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'descri' => ($request->get('txtdescri')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval($request->get('txtprecio') <= 0.00  ? 1 : $request->get('txtprecio'))
        );
        CarritoService::editarProductoVenta($producto, $request->get('cmbmoneda'));
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function quitaritemvtarapida(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemVenta($pos);
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function limpiarvtarapida()
    {
        $this->limpiarSesionVtad();
        session()->set('moneda', 'S');
        $carritov = session()->get('carritov', []);
        $total = number_format(CarritoService::totalVenta(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsVenta(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ventasrapidas', 'detalle');
        return view($cvista, ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function indexvtassol()
    {
        return view('ventassol/index', ['titulo' => 'Registrar Venta x Clave Sol']);
    }
    function registrarvtassol(Request $request)
    {
        $venta = new Ventas();
        $cabecera = array(
            "idcliev" => $request->get("idcliev"),
            "tdocv" => $request->get("tdocv"),
            "cndoc1" => $request->get('cndoc1'),
            "cndoc2" => $request->get('cndoc2'),
            "razov" => $request->get('txtcliente'),
            "ndo2v" => $request->get("ndo2v"),
            "almv" => $request->get("almv"),
            "fechv" => $request->get("fechv"),
            "monev" => $request->get("monev"),
            "formv" => $request->get("formv"),
            "fechvv" => $request->get("fechvv"),
            "idvenv" => $request->get("idvenv"),
            "subtotal" => ($request->get('tdocv') == '07' ?  '-' . $request->get("subtotal") : $request->get("subtotal")),
            "igv" => ($request->get('tdocv') == '07' ?  '-' . $request->get("igv") : $request->get("igv")),
            "total" => ($request->get('total') == '07' ?  '-' . $request->get("total") : $request->get("total")),
            "nidus" => session()->get('usuario_id'),
            "nitem" => 0,
            'optigv' => $request->get("optigv"),
            "txtreferencia" => $request->get("txtreferencia")
        );
        $registro = $venta->grabarvtassol($cabecera);
        if ($registro['estado'] == 1) {
            $rpta = array('mensaje' => "Se Genero la venta ", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta, 200);
        } else {
            return response()->json(['message' => 'Error al Registrar Venta', 'error' => $registro['mensaje']], 422);
        }
    }
    function indexcanjearnotas()
    {
        $titulo = "Facturar Notas";
        return view('ventasd/indexcanjenotas', ['titulo' => $titulo]);
    }
    function listarnotastocanje(Request $request)
    {
        $idCliente = $request->get('idCliente');
        $ventas = new Ventas();
        $listado = $ventas->consultarNotasporCliente($idCliente);
        return view('ventasd/listanotastocanje', [
            "listado" => $listado
        ]);
    }
    function listardetallenotastocanje(Request $request)
    {
        $idauto = $request->get('idauto');
        $ventas = new Ventas();
        $listado = $ventas->consultarDetalleVtaDirecta($idauto);
        return view('ventasd/detallecanjenotas', [
            "listado" => $listado
        ]);
    }
    function registrarcanjearnota(Request $request)
    {
        $idauto = $request->get('idauto');
        $cmbdcto = $request->get('cmbdcto');
        $clienteretencion = $request->get('clienteretencion');
        $total = $request->get('total');
        $documentoantiguo = $request->get('documentoantiguo');
        $txtformapago = $request->get('txtformapago');
        $ventas = new Ventas();
        $registro = $ventas->facturarnotasdeventa($idauto, $cmbdcto, $clienteretencion, $total, $documentoantiguo, $txtformapago);
        if ($registro['estado'] == 1) {
            $rpta = array('mensaje' => "Se Genero la venta satisfactoriamente ", "ndoc" => $registro['ndoc'], "estado" => '1');
            return json_encode($rpta, 200);
        } else {
            return response()->json(['message' => 'Error al registrar venta' . $registro['mensaje'], 'error' => $registro['mensaje']], 422);
        }
    }
    function listarvtasxserviciostoanticipo(Request $request)
    {
        $idCliente = $request->get('idCliente');
        $ventas = new Ventas();
        $listado = $ventas->consultarVentasxserviciosxCliente($idCliente);
        return view('notascredito/tm_listavtas', [
            "listado" => $listado
        ]);
    }
    function indexvtasanuladas()
    {
        $ctitulo = 'Ventas Anuladas';
        return view('ventas/informes/indexlistarvtasanuladas', [
            "titulo" => $ctitulo
        ]);
    }
    function listarvtasanuladas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbtdoc = $request->get("cmbtdoc");
        $cmbalmacen = $request->get("cmbAlmacen");
        $ventas = new Ventas();
        $listado = $ventas->mostrarvtasanuladas($dfi, $dff, $cmbtdoc, $cmbalmacen);
        return view('ventas/informes/listarvtasanuladas', [
            "listado" => $listado
        ]);
        // return response()->json(['message' => 'Se logró listar correctamente', 'listado' => $listado], 200);
    }
    function indexlistavtasmodificadas()
    {
        $ctitulo = "Lista de Ventas Modificadas";
        return view('ventasd/informes/indexlistavtasmodificadas', ['titulo' => $ctitulo]);
    }
    function listarvtasmodificadas(Request $request)
    {
        $vtas = new Ventas();
        $listado = $vtas->listarventasmodificadas();
        return view('ventasd/informes/listavtasmodificadas', [
            "listado" => $listado
        ]);
    }
    function consultardetalleventaxndoc(Request $request)
    {
        $vtas = new Ventas();
        $ndoc = $request->get("ndoc");
        $lista = $vtas->consultardetalleventaxndoc($ndoc);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $lista], 200);
    }
    function indexmovimientosdetallados()
    {
        $ctitulo = 'Operaciones Detalladas';
        return view('ventasd/informes/indexmovimientosdetallados', [
            "titulo" => $ctitulo
        ]);
    }
    function listamovimientosdetalladas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbalmacen = $request->get("cmbalmacen");
        $cmbFormaP = $request->get("cmbFormaP");
        $cmbtdoc = $request->get("cmbtdoc");
        if ($request->get('cmbmovimiento') == 'V') {
            $ventas = new Ventas();
            $listado = $ventas->mostrarventasdetalladas($dfi, $dff, $cmbalmacen, $cmbFormaP, $cmbtdoc);
        } else {
            $compras = new Compra();
            $listado = $compras->mostrarcomprasdetalladas($dfi, $dff, $cmbalmacen, $cmbFormaP, $cmbtdoc);
        }
        return view('ventasd/informes/listamovimientosdetallados', [
            "listado" => $listado
        ]);
    }
    function indexlistavtasxcategoria()
    {
        $ctitulo = 'Detalle Ventas por Categoria';
        return view('ventasd/informes/indexlistavtasxcategoria', [
            "titulo" => $ctitulo
        ]);
    }
    function listavtasxcategoria(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbalmacen = $request->get("cmbalmacen");
        $cmbcategoria = $request->get("cmbcategoria");
        $ventas = new Ventas();
        $listado = $ventas->listarventasxcategoria($dfi, $dff, $cmbalmacen, $cmbcategoria);
        // $rangofechas = obtenertodasfechasxrango($dfi, $dff);
        // $listadofinal = [];
        // foreach ($listado as $l) {
        // }
        // echo '<pre>';
        // var_dump(agruparPorClave($listado, 'fech'));
        // echo '</pre>';
        return view('ventasd/informes/listavtasxcategoria', [
            "listado" => agruparPorClave($listado, 'fech')
        ]);
        // if ($request->get('cmbmovimiento') == 'V') {
        // } else {
        //     $compras = new Compra();
        //     $listado = $compras->mostrarcomprasdetalladas($dfi, $dff, $cmbalmacen, $cmbFormaP, $cmbtdoc);
        // }
    }
    function indexlistaclientesfrecuentes()
    {
        return \view('ventasd/informes/indexlistaclientesfrecuentes', ['titulo' => 'Clientes más frecuentes']);
    }
    function listaclientesfrecuentes(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $codt = $request->get('cmbalmacen');
        $cmbForma = $request->get('cmbForma');
        $venta = new Ventas();
        $lista = $venta->listarclientesfrecuentes($dfi, $dff, $codt, $cmbForma);
        $e = 0;
        foreach ($lista as $lg) {
            $lista[$e]['color'] = "rgb(" . rand(0, 255) . "," . rand(0, 255) . "," . rand(0, 255) . ")";
            $e++;
        }
        return \view('ventasd/informes/listaclientesfrecuentes', [
            'listado' => $lista,
            'listagrafico1' => array_slice($lista, 0, 20),
            'listagrafico2' => array_slice($lista, -20)
        ]);
    }
}
