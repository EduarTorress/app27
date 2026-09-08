<?php

namespace App\Controllers;

use App\Models\Correlativo;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Vendedor;
use App\Services\CarritoService;
use Core\Clases\Imprimir;
use Core\Foundation\Application;
use Core\Routing\Controller;
use Valitron\Validator;
use Core\Http\Request;

class PedidoController extends Controller
{
    function listarpedido()
    {
        $datoscliente = array(
            'idcliente' => isset($_SESSION['cliente']) ? $_SESSION['cliente']['idcliente'] : '',
            'nombre' => isset($_SESSION['cliente']) ? $_SESSION['cliente']['nombre'] : '',
            'txtdireccion' => isset($_SESSION['cliente']) ? $_SESSION['cliente']['txtdireccion'] : '',
            'txtdnicliente' => isset($_SESSION['cliente']) ? $_SESSION['cliente']['txtdnicliente'] : '',
            'txtruccliente' => isset($_SESSION['cliente']) ? $_SESSION['cliente']['ruc'] : '',
            'idven' => session()->get('idven', 1),
            'tdoc' => session()->get('tdoc', 'B'),
            'form' => session()->get('form', 'E'),
            'mone' => session()->get('mone', 'S'),
            'optigvp' => session()->get('optigvp', 'I')
        );
        $idpedido = \session()->get('idpedido', 0);
        if ($idpedido > 0) {
            $ctitulo = 'Actualizar Cotización';
        } else {
            $ctitulo = 'Registrar Cotización';
        }
        $cvista = \retornavista('pedidos', 're_carcompras');
        $vendedor = new Vendedor();
        $listav = $vendedor->listar('');
        return view($cvista, ['titulo' => $ctitulo, 'datoscliente' => $datoscliente, 'idautop' => $idpedido, 'vendedores' => $listav]);
    }
    function indexespecial()
    {
        $datoscliente = array(
            'idcliente' => '',
            'nombre' => '',
            'txtdireccion' => '',
            'txtdnicliente' => '',
            'txtruccliente' => '',
            'idven' => '0',
            'tdoc' => 'B',
            'form' => 'E',
            'mone' => 'S',
            'optigvp' => 'I'
        );
        $idpedido = 0;
        $ctitulo = 'Registrar Cotización';
        $cvista = \retornavista('pedidos', 'indexespecial');
        $vendedor = new Vendedor();
        $listav = $vendedor->listar('');
        return view($cvista, ['titulo' => $ctitulo, 'datoscliente' => $datoscliente, 'idautop' => $idpedido, 'vendedores' => $listav]);
    }
    function listarcarrito()
    {
        $carrito = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $datoscliente = session()->get('cliente', []);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items, 'datoscliente' => $datoscliente]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siesta($idart)) {
            return true;
        } else {
            return false;
        }
    }
    function agregaritem(Request $request)
    {
        $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado a la cotización',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        // $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatorio');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser Numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser Númerico');
        $validar->rule("min", "txtcantidad", 0)->message('La Cantidad debe de ser mayor a 0');
        $validar->rule("min", "txtprecio", 1)->message('El precio debe de ser mayor a 0');
        //$validar->rule("max", "txtcantidad", $stock)->message("Stock no Disponible");
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
            'costo' => $request->get('costo'),
            'tipoproducto' => $request->get('tipoproducto')
        );

        CarritoService::agregar($producto);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);

        return response()->json([
            'message' => 'Item agregado correctamente',
            'total' => $total,
            'numero_items' => $numero_items,
            'carrito' => session()->get("carrito", [])
        ], 200);
    }
    function agregaritemrapido(Request $request)
    {
        $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado a la cotización',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        // $stock = $request->get("stock");
        $preciomin = min($request->get("precio1"), $request->get("precio2"), $request->get("precio3"));
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatorio');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser Numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser Númerico');
        $validar->rule("min", "txtcantidad", 0)->message('La Cantidad debe de ser mayor a 0');
        // $validar->rule("min", "txtprecio", 1)->message('El precio debe de ser mayor a 0');
        //$validar->rule("max", "txtcantidad", $stock)->message("Stock no Disponible");
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
            'costo' => $request->get('costo'),
            'tipoproducto' => $request->get('tipoproducto'),
            'mone' => $request->get('')
        );

        CarritoService::agregar($producto);

        $carrito = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items, 'indice' => count($carrito)]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitar($pos);
        $carrito = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items]);
    }
    function cambiaritem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get('txtprecio'),
            'codigo' => $request->get('txtcodigo'),
            'txtdescripcion' => $request->get('txtdescripcion'),
            'txtunidad' => $request->get('txtunidad'),
            'txtcantidad' => 1,
            'precio1' => $request->get('precio1'),
            'costo' => $request->get('costo'),
            'precio2' => $request->get('precio2'),
            'precio3' => $request->get('precio3'),
            'stock' => $request->get('stock'),
        );
        if (!$this->verificarsiyaesta($request->get('txtcodigo'))) {
            CarritoService::cambiaritem($producto);
            $carrito = session()->get('carrito', []);
            $total = number_format(CarritoService::total(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('pedidos', 're_pedido');
            return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items]);
        } else {
            $data = ['message' => 'Producto ya agregado al pedido', 'rpta' => 'N'];
            return response()->json($data, 422);
        }
    }
    function editaritem(Request $request)
    {
        $itemcarrito = CarritoService::item($request->get('indice'));
        // $stock = $itemcarrito["stock"];
        $preciomin = min($itemcarrito["precio1"], $itemcarrito["precio2"], $itemcarrito["precio3"]);
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es Obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es Obligatorio');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser Numerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser Númerico');
        $validar->rule("min", "txtcantidad", 0)->message('La Cantidad debe de ser mayor a 0');
        $validar->rule("min", "txtprecio", 0)->message('El precio debe de ser mayor a 0');
        // $validar->rule("max", "txtcantidad", $stock)->message("Stock no Disponible");
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
            'indice' => $request->get('indice'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get('txtprecio'),
            'cmbmoneda' => $request->get('cmbmoneda')
        );

        CarritoService::editar($producto);
        $carrito = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items]);
    }
    function soloItem(Request $request)
    {
        $itemcarrito = CarritoService::item($request->get('indice'));
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval($request->get('txtprecio') < $itemcarrito["precio3"]  ? $itemcarrito["precio3"] : $request->get('txtprecio'))
        );
        CarritoService::editar($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function grabar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idcliente");
        $validar->rule("required", "total");
        $validar->rule("required", "codv");
        $validar->rule("required", "forma");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        $seri = session()->get("nserie");
        if (empty($seri)) {
            $data = ["errors" => "No existe serie"];
            return response()->json($data, 422);
        }
        $numeropedido = new Correlativo();
        $nsgte = $numeropedido->Obtenercorrelativo($seri, '21');
        $idserie = $nsgte[0]['idserie'];
        $pedido = new Pedido();
        $var =    $request->get('detalle');
        $cdetalle = (isset($var)) ? $request->get('detalle') : "";
        $cdire = $request->get('direccion');
        $cdireccion = (isset($cdire)) ? $request->get('direccion') : "";
        $centre = $request->get('entrega');
        $centrega = (isset($centre)) ? $request->get('entrega') : "";
        $cabecera = array(
            "idclie" => $request->get("idcliente"),
            "impo" => $request->get("total"),
            "ndoc" => $nsgte[0]['correlativo'],
            "form" => $request->get("forma"),
            "nidus" => session()->get('usuario_id'),
            "nidven" => $request->get("codv"),
            "nitda" =>  $_SESSION['almacen'],
            "ctp" => "p",
            "ccontacto" => "",
            "cdire1" => $cdireccion,
            "cfono" => "",
            "ndias" => 0,
            "centrega" => $centrega,
            "ctransportista" => "",
            "cmone" => $request->get('mone'),
            "detalle" => $cdetalle,
            "ctdoc" => $request->get("ctdoc"),
            'optigvp' => $request->get('optigvp'),
            'txtdetallepago' => $request->get('txtdetallepago'),
            'txtvalidezoferta' => $request->get('txtvalidezoferta'),
            'txtplazoentrega' => $request->get('txtplazoentrega'),
            'txtlugarentrega' => $request->get('txtlugarentrega')
        );

        $rpta = $pedido->GrabarPedido($cabecera,  $idserie);
        if ($rpta['estado'] == '1') {
            $this->LimpiarSesion();
            $carrito = session()->get('carrito', []);
            $total = number_format(CarritoService::total(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('pedidos', 're_pedido');
            return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items, 'nropedido' => $nsgte[0]['correlativo']]);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    function changedolarp(Request $request)
    {
        $tmon = $request->get('monedap');
        if ($tmon == 'D') {
            CarritoService::cambiardetalledolarpedido();
        } else {
            CarritoService::cambiardetallesolespedido();
        }
        session()->set('monedap', 'SI');
        $carritop = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carritop, 'total' => $total, 'items' => $numero_items]);
    }
    function limpiar()
    {
        $this->LimpiarSesion();
        $carrito = session()->get('carrito', []);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('pedidos', 're_pedido');
        return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items]);
    }
    function listarpedidos()
    {
        return \view('pedidos/informes/indexListaPedidos', ['titulo' => 'Reporte de Cotizaciones']);
    }
    function listarpedidosfechas(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $ctipop = $request->get('ctipopedidos');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $pedido = new Pedido();
        $lista = $pedido->listarpedidosresumidos($dfi, $dff, $ctipop, $cmbAlmacen);
        return \view('pedidos/informes/listaPedidos', ['listado' => $lista]);
    }
    function buscarpedido($id, Request $request)
    {
        $pedido = new Pedido();
        $nropedido = "";
        $idautop = $id;
        $this->LimpiarSesion();
        $carrito = session()->get('carrito', []);
        $lista = $pedido->buscarpedidoporid($id);
        session()->set('idpedido', $id);
        // $cliente = session()->get('cliente', []);
        $datosclientep = array();
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $datosclientep = array(
                    'nombre' => $item['razo'],
                    'idcliente' => $item['idclie'],
                    'entrega' => (isset($item['entrega'])) ? $item['entrega'] : '',
                    'txtdireccion' => (isset($item['rped_dire'])) ? $item['rped_dire'] : '',
                    'detalle' => (isset($item['detalle'])) ? $item['detalle'] : '',
                    'idven' => $item['idven'],
                    'form' => $item['form'],
                    'tdoc' => $item['tdoc'],
                    'ruc' => $item['nruc'],
                    'mone' => $item['rped_mone'],
                    'optigvp' => $item['incl'],
                    'txtdnicliente' => $item['ndni'],
                    'forma' => $item['forma'],
                    'plazo' => $item['plazo'],
                    'validez' => $item['validez'],
                    'entrega' => $item['entrega'],
                );
                $nropedido = $item['ndoc'];
                $idautop = $item['idautop'];
            }
            $carrito[] = array(
                'coda' => $item["idart"],
                'descri' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'precio' => $item["prec"],
                'precio1' => $item["pre1"],
                'precio2' => $item["pre2"],
                'precio3' => $item["pre3"],
                'stock' => $item['uno'] + $item['dos'] + $item['tre'] + $item['cua'],
                'costo' => $item['costo'],
                'nreg' => $item['nreg'],
                'idcliente' => $item['idclie'],
                'tipoproducto' => $item['tipro'],
                'activo' => 'A'
            );
        }
        //   CarritoService::agregar($producto);
        session()->set('monedap', 'SI');
        session()->set('igvsololectura', 'SI');
        session()->set('carrito', $carrito);
        session()->set('cliente', $datosclientep);
        $carrito = session()->get('carrito', []);
        $datoscliente = session()->get('cliente', []);
        // if ($lista) {
        //     $titulo = 'Actualizar Pedido' . ' ' . $nropedido;
        // } else {
        //     $titulo = 'Registrar Pedido';
        // }
        $titulo = 'Actualizar Pedido' . ' ' . $nropedido;
        session()->set('nropedido', $nropedido);
        $cvista = \retornavista('pedidos', 're_carcompras');
        $vendedor = new Vendedor();
        $listav = $vendedor->listar('');
        return view($cvista, ['titulo' => $titulo, 'datoscliente' => $datoscliente, 'idautop' => $idautop, 'nropedido' => $nropedido, 'vendedores' => $listav]);
    }
    function actualizar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $cndoc = $request->get("ndoc");
        $validar->rule("required", "idcliente");
        $validar->rule("required", "total");
        $validar->rule("required", "idauto");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $pedido = new Pedido();
        $detalle =    $request->get('detalle');
        $cdetalle = (isset($detalle)) ? $request->get('detalle') : "";
        $cdire = $request->get('direccion');
        $cdireccion = (isset($cdire)) ? $request->get('direccion') : "";
        $centre = $request->get('entrega');
        $centrega = (isset($centre)) ? $request->get('entrega') : "";
        $cabecera = array(
            "idautop" => \session()->get("idpedido"),
            "idclie" => $request->get("idcliente"),
            "impo" => $request->get("total"),
            "ndoc" => session()->get('nropedido'),
            "form" => $request->get("forma"),
            "nidus" => session()->get('usuario_id'),
            "nidven" => $request->get("codv"),
            "nitda" => 1,
            "ctp" => "p",
            "ccontacto" => "",
            "cdire1" => $cdireccion,
            "cfono" => "",
            "ndias" => 0,
            "centrega" => $centrega,
            "ctransportista" => "",
            "cmone" => "S",
            "detalle" => $cdetalle,
            "ctdoc" => $request->get("ctdoc"),
            'optigvp' => $request->get('optigvp'),
            'txtdetallepago' => $request->get('txtdetallepago'),
            'txtvalidezoferta' => $request->get('txtvalidezoferta'),
            'txtplazoentrega' => $request->get('txtplazoentrega'),
            'txtlugarentrega' => $request->get('txtlugarentrega')
        );
        $carrito = $request->get("carrito");
        // if ($_SESSION['config']['cambiarproductoxposicion'] == 'S') {
        //     $prta = $pedido->actualizarpedidoxposicion($cabecera);
        // } else {
        $prta = $pedido->actualizarpedido($cabecera);
        // }
        if ($prta) {
            $this->LimpiarSesion();
            $carrito = session()->get('carrito', []);
            $total = number_format(CarritoService::total(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('pedidos', 're_pedido');
            return view($cvista, ['carrito' => $carrito, 'total' => $total, 'items' => $numero_items, 'nropedido' => $cndoc]);
        } else {
            return response()->json(['message' => 'Error al actualizar Pedido'], 400);
        }
    }
    function verutilidad(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            $utilidad = CarritoService::verutilidadpedido();
            return response()->json(['message' => Round($utilidad, 2)], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas'], 422);
        }
    }
    function eliminarpedidoporid($id)
    {
        $pedido = new Pedido();
        if ($pedido->eliminarpedidoporid($id)) {
            return response()->json(['message' => 'Pedido eliminado satisfactoriamente.'], 200);
        } else {
            return response()->json(['message' => 'Error al eliminar Pedido'], 400);
        }
    }
    function LimpiarSesion()
    {
        session()->remove('carrito');
        session()->remove('cliente');
        \session()->remove('idpedido');
        \session()->remove('nropedido');
        \session()->set('codv', 0);
    }
    function imprimirpedido(Request $request)
    {
        $pedido = new Pedido();
        $lista = $pedido->buscarpedidoporid($request->get("nidauto"));
        $oimp = new Imprimir();
        $i = 1;
        $rutapdf = 'descargas/' . 'pedido.pdf';
        foreach ($lista as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'prec' => $fila['prec'],
                'subtotal' => round($fila['cant'] * $fila['prec'], 2)
            );

            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = $fila['ptop'];
                $oimp->tipocomprobante = 'COTIZACION';
                $oimp->numero = substr($fila['ndoc'], 0, 3) . '-' . substr($fila['ndoc'], -7, 7);
                $oimp->serie = substr($fila['ndoc'], 0, 3);
                $oimp->ndoc = substr($fila['ndoc'], -7, 7);
                $dfecha = date("d/m/Y", strtotime($fila['fech']));
                $oimp->optigv = $fila['incl'];
                $oimp->fecha = $dfecha;
                $oimp->tdoc = '20';
                $oimp->ruccliente = $fila['nruc'];
                $oimp->dnicliente = $fila['ndni'];
                $oimp->cliente = utf8_decode($fila['razo']);
                $oimp->vendedor = $fila['vendedor'];
                $oimp->direccioncliente =  utf8_decode($fila['dire']);
                $oimp->formadepago = ($fila['form'] == 'E' ? 'CONTADO' : 'CREDITO');
                $oimp->moneda = $fila['rped_mone'] === 'S' ? 'SOLES' : 'DOLARES';
                $oimp->total = $fila['impo'];
                $oimp->forma = $fila['forma'];
                $oimp->plazo = $fila['plazo'];
                $oimp->validez = $fila['validez'];
                $oimp->entrega = $fila['entrega'];
                $rutapdf = 'descargas/' . $fila['ndoc'] . 'pdf';
            }
            $i++;
        }
        $oimp->generapdfpedido($rutapdf);
    }
    function detalle($id)
    {
        if ($id == 'z') {
            $ctitulo = "Registrar Item";
            $tipo = "N";
            $itemcarrito = array();
        } else {
            $ctitulo = "Editar Item";
            $tipo = "E";
            $itemcarrito = CarritoService::item($id);
        }
        $cvista = \retornavista('pedidos', 'editaritem');
        return view($cvista, ['titulo' => $ctitulo, 'tipo' => $tipo, 'itemcarrito' => $itemcarrito]);
    }
    function grabarSesion(Request $request)
    {
        \session()->set('tdoc', $request->get('cmbdcto'));
        \session()->set('form', $request->get('cmbforma'));
        \session()->set('idven', $request->get('cmbvendedor'));
        \session()->set('mone', $request->get('cmbmoneda'));
        \session()->set('optigvp', $request->get('optigvp'));
    }
    function listarpedidosparacanje()
    {
        $p = new Pedido();
        $lista = $p->listarparacanje();
        return \view('canjespedidos/re_pedidosmodal', ['listado' => $lista]);
    }
    function listardetallepedidoxid(Request $request)
    {
        $pedido = new Pedido();
        $idautop = $request->get('idautop');
        $kardex = $pedido->listardetalleparacanje($idautop);
        $subtotal = 0;
        $cantidaditems = 0;
        foreach ($kardex as $item) {
            $carritov[] = array(
                'coda' => $item["idart"],
                'tipoproducto' => $item['tipoproducto'],
                'costo' => $item['costo'],
                'descripcion' => $item["descri"],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'precio' => $item["prec"],
                'subtotal' => $item['cant'] * $item['prec']
            );
            $subtotal = ($item['cant'] * $item['prec']) + $subtotal;
            $cantidaditems = $cantidaditems + 1;
        }
        $total = $subtotal;
        $numero_items = $cantidaditems;
        return view('canjespedidos/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
}
