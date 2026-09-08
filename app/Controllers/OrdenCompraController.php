<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\OrdenesCompra;
use App\Services\CarritoServiceOrdenCompra;
use Core\Clases\Imprimir;
use Core\Routing\Controller;
use Valitron\Validator;
use ZipArchive;

class OrdenCompraController extends Controller
{
    function index()
    {
        $fechaoc = date("Y-m-d");
        $idordencompra = \session()->get('idordencompra', 0);
        if ($idordencompra > 0) {
            $ctitulo = 'Act. Orden Compra';
        } else {
            $ctitulo = 'Regs. Orden Compra';
        }
        $serieoc = \session()->get('cndococ', '');
        $numoc = \session()->get('numoc', '');

        $datosproveedoroc = array(
            'idprovoc' => \session()->get('idprovoc', 0),
            'razooc' => \session()->get('razooc', ''),
            'tdococ' => \session()->get('tdococ', 0),
            'cndococ' => $serieoc,
            'numoc' => $numoc,
            'ndo2oc' => \session()->get('ndo2oc', ''),
            'moneoc' => \session()->get('moneoc', ''),
            'formoc' => \session()->get('formoc', ''),
            'almoc' => \session()->get('almoc', $_SESSION['idalmacen']),
            'fechoc' => \session()->get('fechioc', ''),
            'fecroc' => \session()->get('fechfoc', ''),
            'optigvoc' => \session()->get('optigvoc', ''),
            'obsoc' => \session()->get('obsoc', ''),
            'despoc' => \session()->get('despoc', ''),
            'ateoc' => \session()->get('ateoc', '')
        );
        $v = "R";
        return \view('ordenescompra/index', [
            'titulo' => $ctitulo,
            'datosproveedoroc' => $datosproveedoroc,
            'serieoc' => $serieoc,
            'numoc' => $numoc,
            'idordencompra' => $idordencompra,
            'fechaoc' => $fechaoc,
            'v' => $v
        ]);
    }
    function grabarSesion(Request $request)
    {
        \session()->set('idprovoc', $request->get('idprov'));
        \session()->set('razooc', $request->get('razo'));
        \session()->set('tdococ', $request->get('tdoc'));
        \session()->set('cndococ', $request->get('cndoc'));
        \session()->set('numoc', $request->get('num'));
        \session()->set('ndo2oc', $request->get('ndo2'));
        \session()->set('almoc', $request->get('alm'));
        \session()->set('formoc', $request->get('form'));
        \session()->set('moneoc', $request->get('mone'));
        \session()->set('fechioc', $request->get('fechi'));
        \session()->set('fechfoc', $request->get('fechf'));
        \session()->set('dolaroc', $request->get('dolar'));
        \session()->set('optigvoc', $request->get('optigv'));
        \session()->set('obsoc', $request->get('txtobservacion'));
        \session()->set('despoc', $request->get('txtdespacho'));
        \session()->set('ateoc', $request->get('txtatencion'));
    }
    function listardetalle()
    {
        $carritococ = session()->get('carritococ', []);
        $idordencompra = \session()->get('idordencompra', 0);
        if ($idordencompra > 0) {
            $btn = 'Modificar';
        } else {
            $btn = 'Grabar';
        }
        $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ordenescompra', 'detalle');
        return view($cvista, ['carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoServiceOrdenCompra::siesta($idart)) {
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
                'message' => 'Producto ya agregado a la orden de compra',
                'rpta' => 'N'
            ];
            return response()->json($data, 422);
        }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser númerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
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
            'costo' => $request->get('costo'),
            'presentaciones' => $request->get('presentaciones'),
            'cantequi' => $request->get('cantequi'),
            'presseleccionada' => $request->get('presseleccionada')
        );
        CarritoServiceOrdenCompra::agregarItem($producto);
        $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        $carritococ = session()->get('carritococ', []);
        $cvista = \retornavista('ordenescompra', 'detalle');
        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items,
        //     'carritoc' => session()->get("carritoc", [])
        // ], 200);
        return view($cvista, [
            'carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoServiceOrdenCompra::quitarItem($pos);
        $carritococ = session()->get('carritococ', []);
        $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ordenescompra', 'detalle');
        return view($cvista, ['carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items]);
    }
    function LimpiarSesion()
    {
        session()->remove('carritococ');
        session()->remove('proveedoroc');
        session()->remove('idordencompra');
        session()->remove('razooc');
        session()->remove('tdococ');
        session()->remove('cndococ');
        session()->remove('numoc');
        session()->remove('ndo2oc');
        session()->remove('almoc');
        session()->remove('formoc');
        session()->remove('moneoc');
        session()->remove('fechioc');
        session()->remove('fechfoc');
        session()->remove('dolaroc');
        session()->remove('optigvoc');
        session()->remove('obsoc');
        session()->remove('despoc');
        session()->remove('ateoc');
    }
    function limpiar()
    {
        $this->LimpiarSesion();
        $carritococ = session()->get('carritococ', []);
        $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('ordenescompra', 'detalle');
        return view($cvista, ['carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items]);
    }
    function soloItem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'unidad' => ($request->get('unidad')),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval(($request->get('txtprecio') <= 0.00) ? 1 : $request->get('txtprecio')),
            'cantequi' => $request->get('cantequi'),
            'presseleccionada' => $request->get('presseleccionada'),
            'activo' => 'A'
        );
        CarritoServiceOrdenCompra::editarProducto($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    // function checkafecto(Request $request)
    // {
    //     $producto = array();
    //     $producto = array(
    //         'indice' => $request->get('indice'),
    //         'checkafecto' => $request->get('marcado'),
    //     );
    //     CarritoService::editarProductocheckafecto($producto);
    //     return response()->json([
    //         'message' => 'Item actualizado correctamente',
    //         'array' => $producto
    //     ], 200);
    // }
    function grabar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");
        $cserie = $request->get('cndoc');
        $cserie = $cserie . substr(0, 4);
        switch ($request->get('tdoc')) {
            case '01':
                $validar->rule('regex', $cserie, '/^[F]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
            case '03':
                $validar->rule('regex', $cserie, '/^[B|B]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
        }
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty($_SESSION["carritococ"])) {
            return response()->json(['message' => 'Se requiere productos para registrar orden de compra'], 422);
        }
        $ocompra = new OrdenesCompra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";
        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitem" => str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT),
            "igv" => $request->get("igv"),
            'pimpo' => $request->get('pimpo'),
            'txtobservacion' => $request->get('txtobservacion'),
            'txtdespacho' => $request->get('txtdespacho'),
            'txtatencion' => $request->get('txtatencion')
        );
        if ($ocompra->grabar($cabecera)) {
            $this->LimpiarSesion();
            $carritococ = session()->get('carritococ', []);
            $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
            $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('ordenescompra', 'detalle');
            return view($cvista, ['carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items]);
        } else {
            return response()->json(['message' => 'Error al registrar la orden de compra'], 422);
        }
    }
    function buscarOrdenCompraPorId($idauto)
    {
        $ocompra = new OrdenesCompra();
        $nrocompra = "";
        $this->LimpiarSesion();
        $carritococ = session()->get('carritococ', []);
        $lista = $ocompra->buscarOrdenCompraPorId($idauto);
        $i = 0;
        $montototal = 0;
        $subtotal = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $datosproveedoroc = array(
                    'idauto' => $item['ocom_idroc'],
                    'almoc' => '0',
                    'fechoc' => $item['ocom_fech'],
                    'fecroc' => $item['ocom_fech'],
                    'formoc' => '',
                    'tdococ' => '',
                    'dolaroc' => '',
                    'tipooc' => '',
                    'moneoc' => '',
                    'razooc' => $item['razo'],
                    'idprovoc' => $item['ocom_idpr'],
                    'ndo2oc' => '',
                    'optigvoc' => 'I',
                    'pimpo' => $item['ocom_impo'],
                    'obsoc' => $item['ocom_form'],
                    'despoc' => $item['ocom_desp'],
                    'ateoc' => $item['ocom_aten']
                );
                $nrocompra = $item['ocom_ndoc'];
                $idauto = $item['ocom_idroc'];
            }
            $subtotal = $item['doco_prec'] * $item['doco_cant'];
            $montototal = $subtotal + $subtotal;
            $i++;
            $c[] = array(
                'coda' => $item["doco_coda"],
                'descri' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['doco_cant'],
                'precio' => $item["doco_prec"],
                'nreg' => $item["doco_iddo"],
                'idprov' => $item['ocom_idpr'],
                'subtotal' => $item['doco_prec'] * $item['doco_cant'],
                'activo' => 'A',
            );
        }
        $items = $i;
        session()->set('proveedoroc', $datosproveedoroc);
        session()->set('carritococ', $c);
        $titulo = 'Actualizar Orden de Compra' . ' ' . $nrocompra;
        // session()->set('nrocompra', $nrocompra);
        $serie = substr($nrocompra, 0, 4);
        $num = substr($nrocompra, 4);

        session()->set('idordencompra', $idauto);

        $cvista = \retornavista('ordenescompra', 'index');
        $v = "M";

        \session()->set('idprovoc', $datosproveedoroc['idprovoc']);
        \session()->set('razooc',  $datosproveedoroc['razooc']);
        \session()->set('tdococ',  $datosproveedoroc['tdococ']);
        \session()->set('cndococ',  $serie);
        \session()->set('numoc', $num);
        \session()->set('ndo2oc',  $datosproveedoroc['ndo2oc']);
        \session()->set('almoc',  $datosproveedoroc['almoc']);
        \session()->set('formoc',  $datosproveedoroc['formoc']);
        \session()->set('moneoc',  $datosproveedoroc['moneoc']);
        \session()->set('fechioc',  $datosproveedoroc['fechoc']);
        \session()->set('fechfoc',  $datosproveedoroc['fecroc']);
        \session()->set('dolaroc',  $datosproveedoroc['dolaroc']);
        \session()->set('optigvoc',  $datosproveedoroc['optigvoc']);
        \session()->set('obsoc',  $datosproveedoroc['obsoc']);
        \session()->set('despoc',  $datosproveedoroc['despoc']);
        \session()->set('ateoc',  $datosproveedoroc['ateoc']);
        // if (count($carritoc) < 1) {
        //     header('Location: /ocompras/buscarcompra/' . $idauto);
        //     return;
        // }
        return view(
            $cvista,
            [
                'titulo' => $titulo,
                'datosproveedoroc' => $datosproveedoroc,
                'idordencompra' => $idauto,
                'serie' => $serie,
                'num' => $num,
                'v' => $v,
                'carritococ' => $c,
                'items' => $items,
                'total' => $montototal
            ]
        );
    }
    function modificar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");
        $cserie = $request->get('cndoc');
        $cserie = $cserie . substr(0, 4);
        switch ($request->get('tdoc')) {
            case '01':
                $validar->rule('regex', $cserie, '/^[F]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
            case '03':
                $validar->rule('regex', $cserie, '/^[A-Z]{1,1}[D|N0-9]{1,1}[0-9]{2,2}$/');
                break;
        }
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty($_SESSION["carritococ"])) {
            return response()->json(['message' => 'Se requiere productos para registrar la orden de compra'], 422);
        }
        $ocompra = new OrdenesCompra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";
        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitems" => str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT),
            "igv" => $request->get("igv"),
            'pimpo' => $request->get('pimpo'),
            "nidauto" => \session()->get('idordencompra'),
            'txtobservacion' => $request->get('txtobservacion'),
            'txtdespacho' => $request->get('txtdespacho'),
            'txtatencion' => $request->get('txtatencion')
        );
        if ($ocompra->actualizar($cabecera)) {
            $this->LimpiarSesion();
            $carritococ = session()->get('carritococ', []);
            $total = number_format(CarritoServiceOrdenCompra::total(), 2, '.', '');
            $numero_items = str_pad(CarritoServiceOrdenCompra::numeroItems(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('ordenescompra', 'detalle');
            return view($cvista, ['carritococ' => $carritococ, 'total' => $total, 'items' => $numero_items]);
        } else {
            return response()->json(['message' => 'Error al modificar la orden de compra'], 422);
        }
    }
    function indexLista()
    {
        return \view('ordenescompra/informes/indexlistaordencompra', ['titulo' => 'Listar Ordenes de Compra']);
    }
    function listarXFecha(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $txtidproveedor = $request->get('txtidproveedor');
        $ocompra = new OrdenesCompra();
        $lista = $ocompra->listarxFecha($dfi, $dff, $txtidproveedor);
        return \view('ordenescompra/informes/listaordenescompra', ['listado' => $lista]);
    }
    function imprimir(Request $request)
    {
        $oc = new OrdenesCompra();
        $st = $oc->buscarOrdenCompraPorId($request->get('nidauto'));
        $namepdf = "";
        $oimp = new Imprimir();
        $i = 1;
        foreach ($st as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['doco_cant'],
                'prec' => $fila['doco_prec'],
                'subtotal' => round($fila['doco_cant'] * $fila['doco_prec'], 2)
            );

            if ($i == 1) {
                $oimp->empresa = $_SESSION['gene_empresa'];
                $oimp->rucempresa = $_SESSION['gene_nruc'];
                $oimp->direccionempresa = $_SESSION['gene_ptop'];
                $oimp->numero = $fila['ocom_ndoc'];
                $namepdf = $fila['ocom_ndoc'] . '.pdf';
                $dfecha = date("d/m/Y", strtotime($fila['ocom_fech']));
                $oimp->fecha = $dfecha;
                $oimp->ruccliente = $fila['nruc'];
                $oimp->dnicliente = '';
                $oimp->guiaremision = '';
                $oimp->referencia = isset($fila['ocom_deta']) ? $fila['ocom_deta'] : '';
                $oimp->detraccion = '0';
                $oimp->optigv = 'I';
                $oimp->cliente = ($fila['razo']);
                $oimp->vendedor = '';
                $oimp->direccioncliente =  '';
                $oimp->formadepago =  $fila['ocom_form'];
                $oimp->constancia =  $fila['ocom_aten'];
                $oimp->vendedor =  $fila['ocom_desp'];
                $oimp->moneda = ($fila['ocom_mone'] == 'S' ? 'SOLES' : 'DOLARES');
                $oimp->valorgravado = $fila['ocom_valor'];
                $oimp->igv = $fila['ocom_igv'];
                $oimp->total = $fila['ocom_impo'];
                $oimp->vigv = $_SESSION['gene_igv'];
            }
            $i++;
        }
        $oimp->generapdfordencompra($namepdf);
    }
}
