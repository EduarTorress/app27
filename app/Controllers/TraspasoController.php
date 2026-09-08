<?php

namespace App\Controllers;

use App\Models\Compra;
use Core\Http\Request;
use App\Models\OrdenesCompra;
use App\Models\Sucursal;
use App\Models\Traspasos;
use App\Models\Ventas;
use App\Services\CarritoServiceTraspaso;
use Core\Clases\Imprimir;
use Core\Routing\Controller;
use Core\Routing\Modelo;
use Valitron\Validator;
use ZipArchive;

class TraspasoController extends Controller
{
    function index()
    {
        $idtraspaso = \session()->get('idtraspaso', 0);
        // if ($idtraspaso > 0) {
        //     $ctitulo = 'Act. Traspaso';
        // } else {
        // }
        $ctitulo = 'Regs. Traspaso';
        $seriet = \session()->get('cndot', '');
        $numt = \session()->get('numt', '');
        $v = "R";
        $sucursales = $_SESSION['sucursales'];
        return \view('traspasos/index', ['titulo' => $ctitulo, 'serie' => $seriet, 'num' => $numt, 'idtraspaso' => $idtraspaso, 'sucursales' => $sucursales, 'v' => $v]);
    }
    function listardetalle()
    {
        $carritot = session()->get('carritot', []);
        $idtraspaso = \session()->get('idtraspaso', 0);
        // if ($idtraspaso > 0) {
        //     $btn = 'Modificar';
        // } else {
        // }
        $btn = 'Grabar';
        $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('traspasos', 'detalle');
        return view($cvista, ['carritot' => $carritot, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function listardetallecompratocanje(Request $request)
    {
        // $compra = new Compra();
        // $listado = $compra->listardetalletocanjetraspaso($request->get('idauto'));
        // foreach ($listado as $item) {
        //     $c[] = array(
        //         'coda' => $item["idart"],
        //         'descri' => $item["descri"],
        //         'unid' => $item['unid'],
        //         'cant' => $item['cant'],
        //         'prec' => $item['peso'],
        //         'idkar' => $item["idkar"],
        //         'uno' => $item["uno"],
        //         'dos' => $item["dos"],
        //         'tre' => $item["tre"],
        //         'costo' => 0,
        //         'TAlma' => 0,
        //         'pre1' => 0,
        //         'pre2' => 0,
        //         'tipro' => 'K',
        //         'pre3' => 0,
        //         'idclie' => 0,
        //         'epta_idep' => empty($item['epta_idep']) ? 0 : $item['epta_idep'],
        //         'pres_desc' => empty(trim($item['pres_desc'])) ? 'UNID' : $item['pres_desc'],
        //         'epta_cant' => empty($item['epta_cant']) ? 1 : $item['epta_cant'],
        //         'epta_prec' => empty($item['epta_prec']) ? $item['prec'] : $item['epta_prec'],
        //         'presseleccionada' => empty($item['kar_epta']) ? 0 : $item['kar_epta'],
        //         'kar_equi' => empty($item['kar_equi']) ? 1 : $item['kar_equi'],
        //         'fechavto' => empty($item['kar_fvto']) ? date('Y-m-d') : $item['kar_fvto'],
        //         'lote' => empty($item['kar_lote']) ? '' : $item['kar_lote'],
        //         'caant' => $item['cant'],
        //         'activo' => 'A'
        //     );
        // }
        // $ltagrupada = array();
        // foreach ($c as $k => $producto) {
        //     $idart = $producto["idkar"];
        //     $ltagrupada[$idart][] = $producto;
        // }

        // foreach ($ltagrupada as $k => $items) {
        //     $presentaciones = [];
        //     $j = 0;
        //     foreach ($items as $p) {
        //         $presentaciones[$j] = array(
        //             'epta_idep' => $p['epta_idep'],
        //             'pres_desc' => $p['pres_desc'],
        //             'epta_cant' => $p['epta_cant'],
        //             'epta_prec' => $p['epta_prec']
        //         );
        //         $j += 1;
        //     }

        //     $carritot[] = array(
        //         'coda' => $items[0]["coda"],
        //         'descri' => $items[0]["descri"],
        //         'unidad' => $items[0]['unid'],
        //         'cantidad' => $items[0]['cant'],
        //         'precio' => $items[0]["prec"],
        //         'nreg' => $items[0]["idkar"],
        //         'costo' => $items[0]["costo"],
        //         'stock' => $items[0]['TAlma'],
        //         'precio1' => $items[0]['pre1'],
        //         'precio2' => $items[0]['pre2'],
        //         'tipoproducto' => $items[0]['tipro'],
        //         'precio3' => $items[0]['pre3'],
        //         'idclie' => $items[0]['idclie'],
        //         'presentaciones' => json_encode($presentaciones),
        //         'presseleccionada' => $items[0]['presseleccionada'],
        //         'cantequi' => $items[0]['kar_equi'],
        //         'caant' => $items[0]['caant'],
        //         'lote' => $items[0]['lote'],
        //         'fechavto' => $items[0]['fechavto'],
        //         'uno' => $items[0]['uno'],
        //         'dos' => $items[0]['dos'],
        //         'tre' => $items[0]['tre'],
        //         'activo' => 'A'
        //     );
        // }

        // session()->set('carritot', $carritot);
        // $carritot = session()->get('carritot', $carritot);
        // $btn = "Grabar";
        // $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
        // $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        // return view('traspasos/detalle', ['carritot' => $carritot, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function LimpiarSesion()
    {
        session()->remove('carritot');
        session()->remove('transportista');
    }
    function limpiar()
    {
        $this->LimpiarSesion();
        $carritot = session()->get('carritot', []);
        $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('traspasos', 'detalle');
        return view($cvista, ['carritot' => $carritot, 'total' => $total, 'items' => $numero_items]);
    }
    function agregaritem(Request $request)
    {
        // $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado a la orden de compra',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
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
            'stockuno' => $request->get('stockuno'),
            'stockdos' => $request->get('stockdos'),
            'stocktre' => $request->get('stocktre'),
            'presseleccionada' => $request->get('presseleccionada')
        );
        CarritoServiceTraspaso::agregarItem($producto);
        $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        $carritot = session()->get('carritot', []);
        $cvista = \retornavista('traspasos', 'detalle');
        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items,
        //     'carritoc' => session()->get("carritoc", [])
        // ], 200);
        return view($cvista, [
            'carritot' => $carritot,
            'total' => $total,
            'items' => $numero_items
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoServiceTraspaso::quitarItem($pos);
        $carritot = session()->get('carritot', []);
        $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('traspasos', 'detalle');
        return view($cvista, ['carritot' => $carritot, 'total' => $total, 'items' => $numero_items]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoServiceTraspaso::siesta($idart)) {
            return true;
        } else {
            return false;
        }
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
        CarritoServiceTraspaso::editarProducto($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function obtenerdireccionsucursal($idsucursal)
    {
        $sucudire = "";
        if (empty($_SESSION['sucursales'])) {
            $modelo = new Modelo();
            $modelo->cargarsucursalesindex();
            $sucursales = $_SESSION['sucursales'];
        } else {
            $sucursales = $_SESSION['sucursales'];
        };
        foreach ($sucursales as $sucu) {
            if ($sucu['idalma'] == $idsucursal) {
                $sucudire = $sucu['dire'] . ' ' . $sucu['ciud'];
            }
        }
        return $sucudire;
    }
    function obtenerubigeosucursalsalida($idsucursal)
    {
        $ubigeo = "";
        if (empty($_SESSION['sucursales'])) {
            $modelo = new Modelo();
            $modelo->cargarsucursalesindex();
            $sucursales = $_SESSION['sucursales'];
        } else {
            $sucursales = $_SESSION['sucursales'];
        };
        foreach ($sucursales as $sucu) {
            if ($sucu['idalma'] == $idsucursal) {
                $ubigeo = $sucu['ubigeo'];
            }
        }
        return $ubigeo;
    }
    function verificarsihaystock($carritot)
    {
        $nombsucu = verificarenquesucursalestoy();
        switch ($nombsucu) {
            case "uno":
                foreach ($carritot as $c) {
                    if (floatval($c['uno']) < floatval($c['cantidad'])) {
                        return array('mensaje' => 'No hay stock', 'producto' => $c['descri'], 'stockdisponible' => $c['uno'], 'estado' => '0');
                    }
                }
                break;
            case "dos":
                foreach ($carritot as $c) {
                    if (floatval($c['dos']) < floatval($c['cantidad'])) {
                        return array('mensaje' => 'No hay stock', 'producto' => $c['descri'], 'stockdisponible' => $c['uno'], 'estado' => '0');
                    }
                }
                break;
            case "tre":
                foreach ($carritot as $c) {
                    if (floatval($c['tre']) < floatval($c['cantidad'])) {
                        return array('mensaje' => 'No hay stock', 'producto' => $c['descri'], 'stockdisponible' => $c['uno'], 'estado' => '0');
                    }
                }
                break;
        }
        return array('mensaje' => 'Todo ok', 'producto' => ' ', 'estado' => '1');
    }
    function grabar(Request $request)
    {
        if (empty($_SESSION["carritot"])) {
            return response()->json(['message' => 'Se requiere productos para registrar traspaso'], 422);
        }
        $rptahaystock = $this->verificarsihaystock($_SESSION['carritot']);
        if ($rptahaystock['estado'] == '0') {
            return response()->json(['message' => 'No hay stock en el producto ' . $rptahaystock['producto'] . ', solo tiene:  ' . $rptahaystock['stockdisponible'] . ' unidades'], 422);
        }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "fechaemision");
        $validar->rule("required", "fechatraslado");
        $validar->rule("required", "txtIdTransportista");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $t = new Traspasos();
        $t->dfecha = $request->get('fechaemision');
        $t->dfechat = $request->get('fechatraslado');
        $t->referencia = $request->get('referencia');
        $t->sucursalsalida = $request->get('sucursalsalida');
        $t->cptop = $this->obtenerdireccionsucursal($request->get('sucursalsalida'));
        $t->sucursalingreso = $request->get('sucursalingreso');
        $t->cptoll = $this->obtenerdireccionsucursal($request->get('sucursalingreso'));
        $t->referencia = $request->get('referencia');
        $t->transportista = $request->get('txtIdTransportista');
        $t->n1 = session()->get('gene_idctav');
        $t->n2 = session()->get('gene_idctai');
        $t->n3 = session()->get('gene_idctat');
        $t->n3 = session()->get('gene_idctat');
        $t->nv = 0;
        $t->nigv = 0;
        $t->total = 0;
        $t->ubigeo = $this->obtenerubigeosucursalsalida($request->get('sucursalingreso'));
        $t->nitems = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
        $rpta = $t->grabar();
        if ($rpta['estado']) {
            $datosguia = array(
                "empresa" => session()->get('gene_empresa'),
                "rucempresa" => session()->get('gene_nruc'),
                "direccionempresa" => session()->get("gene_ptop"),
                "numero" => substr($rpta['ndoc'], 0, 4) . '-' . substr($rpta['ndoc'], -8, 8),
                "serie" => substr($rpta['ndoc'], 0, 4),
                "ndoc" => $rpta['ndoc'],
                "dfecha" => Date("d/m/Y", strtotime($request->get("fechaemision"))),
                "dfechat" => Date("d/m/Y", strtotime($request->get("fechatraslado"))),
                "fecha" => Date("d/m/Y"),
                "tdoc" => "09",
                "cptop" => $this->obtenerdireccionsucursal($request->get('sucursalsalida')),
                "cptoll" => $this->obtenerdireccionsucursal($request->get('sucursalingreso')),
                // "rucremitente" => $request->get("txtNombreRemitente"),
                // "txtrucDestinatario" => $request->get("txtrucDestinatario"),
                "ructransportista" => $request->get("txtructransportista"),
                // "remitente" => $request->get("txtNombreRemitente"),
                // "destinatario" => $request->get("txtNombreDestinatario"),
                // "ptopartida" => $request->get("txtDireccionRemitente"),
                // "ptollegada" => $request->get("txtDireccionDestinatario"),
                "placa1" => $request->get("txtPlaca1"),
                "transportista" => $request->get("txttransportista"),
                "placa" => $request->get("txtPlaca"),
                "conductor" => $request->get("txtChoferVehiculo"),
                "brevete" => $request->get("txtBrevete"),
                "marca" => $request->get("txtmarca"),
                "referencia" => $request->get('referencia'),
                'tran_tipo' => $request->get('txttipot')
            );
            $carritodetalle = [];
            $carritot = session()->get('carritot', []);
            foreach ($carritot as $c) {
                if ($c['activo'] == 'A') {
                    array_push($carritodetalle, $c);
                }
            }
            $_SESSION['datosguia'] = $datosguia;
            $_SESSION['detalle'] = $carritodetalle;

            $this->LimpiarSesion();
            return response()->json($rpta, 200);
            // $carritot = session()->get('carritot', []);
            // $total = number_format(CarritoServiceTraspaso::total(), 2, '.', '');
            // $numero_items = str_pad(CarritoServiceTraspaso::numeroItems(), 2, '0', STR_PAD_LEFT);
            // $cvista = \retornavista('traspasos', 'detalle');
            // return view($cvista, ['carritot' => $carritot, 'total' => $total, 'items' => $numero_items]);
        } else {
            return response()->json(['message' => 'Error al registrar el traspaso'], 422);
        }
    }
    function imprimirdirecto()
    {
        $fila = $_SESSION['datosguia'];
        $detalle = $_SESSION['detalle'];
        $oimp = new Imprimir();
        $i = 1;
        $tpeso = 0;
        foreach ($detalle as $item) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $item['unidad'],
                'descri' => $item['descri'],
                'cant' => $item['cantidad'],
                'peso' => $item['precio'],
                'scop' => '',
                'subtotal' => round($item['cantidad'] * $item['precio'], 2)
            );
            $tpeso += $item['cantidad'] * $item['precio'];
            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = "";
                $oimp->tipocomprobante = 'GUIA REMISIÓN ELECTRONICA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $oimp->fechat = $fila['dfechat'];
                $oimp->fecha = $fila['dfecha'];
                $oimp->tdoc = '09';

                // $oimp->rucdestinatario = $fila['txtrucDestinatario'];
                $oimp->nombretransportista = $fila['transportista'];
                $oimp->ructransportista = $fila['ructransportista'];
                // $oimp->remitente = $fila['remitente'];
                // $oimp->destinatario = $fila['destinatario'];
                $oimp->tipotransporte  = $fila['tran_tipo'] == '01' ? 'Público' : 'Privado';

                $oimp->ptopartida = $fila['cptop'];
                $oimp->ptollegada = $fila['cptoll'];
                $oimp->placa = $fila['placa'];
                $oimp->conductor = $fila["conductor"];
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->referencia = isset($fila['referencia']) ? $fila['referencia'] : '';
                $oimp->constancia = "Constancia";
                $rutapdf = 'descargas/' . $fila['ndoc'] . '.pdf';
            }
            $i++;
        }
        #I ES PARA GUARDAR EN EL SERVIDOR F
        # PARA DESCARGAR EL ARCHIVO ES D
        $oimp->totalpeso = $tpeso;
        // $estilo=$_SESSION['estilo'];

        $oimp->generarPDFguiatraspaso($rutapdf, 'I');

        $_SESSION['datosguia'] = [];
        $_SESSION['detalle'] = [];
    }
    function indexlistar()
    {
        return \view('traspasos/informes/indexlistar', ['titulo' => 'Listar Guias x Traspasos']);
    }
    function listarxfecha(Request $request)
    {
        $dfi = $request->get('dfi');
        $dff = $request->get('dff');
        $cmbalmacen = $request->get('cmbalmacen');
        $traspasos = new Traspasos();
        $lista = $traspasos->listarxFecha($dfi, $dff, $cmbalmacen);
        return \view('traspasos/informes/listatraspasos', ['listado' => $lista]);
    }
    function imprimir(Request $request)
    {
        $t = new Traspasos();
        $st = $t->buscarxid($request->get('nidauto'));
        $rutapdf = 'descargas/guia' . '.pdf';
        $oimp = new Imprimir();
        $i = 1;
        $tpeso = 0;
        foreach ($st as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['kar_unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'peso' => $fila['prec'],
                'subtotal' => round($fila['cant'] * $fila['prec'], 2)
            );
            $tpeso += $fila['cant'] * $fila['peso'];
            if ($i == 1) {
                $oimp->empresa = session()->get('gene_empresa');
                $oimp->rucempresa = session()->get('gene_nruc');
                $oimp->direccionempresa = session()->get("gene_ptop");
                $oimp->tipocomprobante = 'GUIA DE REMISION ELECTRÓNICA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['fech']));
                $oimp->fechat = date("d/m/Y", strtotime($fila['fechat']));
                $oimp->fecha = $dfecha;
                $oimp->tdoc = '09';
                $oimp->rucdestinatario = $fila['nruc'];
                // $oimp->destinatario = $fila['razo'];
                $oimp->ptopartida = $fila['ptop'];
                $oimp->ptollegada = $fila['guia_ptoll'];
                $oimp->placa = $fila['placa'];
                $oimp->conductor = ($fila['conductor']);
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->constancia = $fila['constancia'];
                $oimp->ructransportista = $fila['ructr'];
                $oimp->qrsunat = $fila['guia_arch'];
                $oimp->nombretransportista = $fila['razont'];
                $oimp->referencia = $fila['deta'];
                $oimp->tipotransporte  = $fila['tran_tipo'] == '01' ? 'Público' : 'Privado';
                $name = session()->get('gene_nruc') . $fila['dcto'];
                $rutapdf = 'descargas/' . $name . 'pdf';
            }
            $i++;
            $oimp->totalpeso = $tpeso;
        }
        $oimp->generarPDFguiatraspaso($rutapdf);
    }

    //
    //
    //
    //
    //

    // function consultadocumentoxid($idauto)
    // {
    //     $traspaso = new Traspasos();
    //     $nrocompra = "";
    //     $this->LimpiarSesion();
    //     $carritot = session()->get('carritot', []);
    //     $lista = $traspaso->buscarxid($idauto);
    //     $i = 0;
    //     $montototal = 0;
    //     $subtotal = 0;
    //     foreach ($lista as $item) {
    //         if ($i == 0) {
    //             $transportista = array(
    //                 'txtIdTransportista' => $item['guia_idtr'],
    //                 'txttransportista' => $item['razont'],
    //                 'txtruc' => $item['ructr'],
    //                 'txtPlaca' => $item['placa'],
    //                 'txtPlaca1' => $item['placa1'],
    //                 'txtmarca' => $item['marca'],
    //                 'txtChoferVehiculo' => $item['conductor'],
    //                 'txtbrevete' => $item['brevete'],
    //                 'txtregmtc' => $item['constancia'],
    //                 'txttipot' => $item['tran_tipo']
    //             );
    //             // $sql = "select guia_ndoc as ndoc,guia_fech as fech,guia_fect as fechat,
    //             // a.descri,a.unid,k.cant,a.peso,g.guia_ptoll,g.guia_ptop as ptop,kar_unid,
    //             // k.idart as coda,k.prec,k.idkar,g.guia_idtr,ifnull(placa,'') as placa,ifnull(t.razon,'') as razont,
    //             // ifnull(t.ructr,'') as ructr,ifnull(t.nombr,'') as conductor,guia_mens,
    //             // ifnull(t.dirtr,'') as direcciont,ifnull(t.breve,'') as brevete,
    //             // ifnull(t.cons,'') as constancia,ifnull(t.marca,'') as marca,v.nruc,tran_tipo,
    //             // ifnull(t.placa1,'') as placa1,r.ndoc as dcto,tdoc,r.idcliente,rcom_mens,rcom_reci,k.alma,a.uno,a.dos,a.tre,a.cua,
    //             // v.empresa as Razo,'S' as mone,guia_idgui as idgui,r.idauto,guia_arch,guia_hash,guia_mens,r.ndo2,guia_ubig,r.deta
    //             // FROM fe_guias as g
    //             // inner join fe_rcom as r on r.idauto=g.guia_idau
    //             // inner join fe_kar as k on k.idauto=r.idauto
    //             // inner join fe_art as a on a.idart=k.idart
    //             // left join fe_tra as t on t.idtra=g.guia_idtr,fe_gene as v where guia_idgui=:idguia and tipo='V' and k.acti='A'";

    //             $datosproveedoroc = array(
    //                 'idauto' => $item['idauto'],
    //                 'idgui' => $item['idgui'],
    //                 'fech' => $item['fech'],
    //                 'fechat' => $item['fechat'],
    //                 'referencia' => $item['deta'],
    //                 'tdococ' => '',
    //                 'dolaroc' => '',
    //                 'tipooc' => '',
    //                 'moneoc' => '',
    //                 'razooc' => $item['razo'],
    //                 'idprovoc' => $item['ocom_idpr'],
    //                 'ndo2oc' => '',
    //                 'optigvoc' => 'I',
    //                 'pimpo' => $item['ocom_impo'],
    //                 'obsoc' => $item['ocom_form'],
    //                 'despoc' => $item['ocom_desp'],
    //                 'ateoc' => $item['ocom_aten']
    //             );
    //             $nrocompra = $item['ocom_ndoc'];
    //             $idauto = $item['ocom_idroc'];
    //         }
    //         $subtotal = $item['doco_prec'] * $item['doco_cant'];
    //         $montototal = $subtotal + $subtotal;
    //         $i++;
    //         $c[] = array(
    //             'coda' => $item["doco_coda"],
    //             'descri' => $item["descri"],
    //             'unidad' => $item['unid'],
    //             'cantidad' => $item['doco_cant'],
    //             'precio' => $item["doco_prec"],
    //             'nreg' => $item["doco_iddo"],
    //             'idprov' => $item['ocom_idpr'],
    //             'subtotal' => $item['doco_prec'] * $item['doco_cant'],
    //             'activo' => 'A',
    //         );
    //     }
    //     $items = $i;
    //     session()->set('proveedoroc', $datosproveedoroc);
    //     session()->set('carritot', $c);
    //     $titulo = 'Actualizar Orden de Compra' . ' ' . $nrocompra;
    //     // session()->set('nrocompra', $nrocompra);
    //     $serie = substr($nrocompra, 0, 4);
    //     $num = substr($nrocompra, 4);

    //     session()->set('idordencompra', $idauto);

    //     $cvista = \retornavista('ordenescompra', 'index');
    //     $v = "M";

    //     \session()->set('idprovoc', $datosproveedoroc['idprovoc']);
    //     \session()->set('razooc',  $datosproveedoroc['razooc']);
    //     \session()->set('tdococ',  $datosproveedoroc['tdococ']);
    //     \session()->set('cndococ',  $serie);
    //     \session()->set('numoc', $num);
    //     \session()->set('ndo2oc',  $datosproveedoroc['ndo2oc']);
    //     \session()->set('almoc',  $datosproveedoroc['almoc']);
    //     \session()->set('formoc',  $datosproveedoroc['formoc']);
    //     \session()->set('moneoc',  $datosproveedoroc['moneoc']);
    //     \session()->set('fechioc',  $datosproveedoroc['fechoc']);
    //     \session()->set('fechfoc',  $datosproveedoroc['fecroc']);
    //     \session()->set('dolaroc',  $datosproveedoroc['dolaroc']);
    //     \session()->set('optigvoc',  $datosproveedoroc['optigvoc']);
    //     \session()->set('obsoc',  $datosproveedoroc['obsoc']);
    //     \session()->set('despoc',  $datosproveedoroc['despoc']);
    //     \session()->set('ateoc',  $datosproveedoroc['ateoc']);
    //     // if (count($carritoc) < 1) {
    //     //     header('Location: /ocompras/buscarcompra/' . $idauto);
    //     //     return;
    //     // }
    //     return view(
    //         $cvista,
    //         [
    //             'titulo' => $titulo,
    //             'datosproveedoroc' => $datosproveedoroc,
    //             'idordencompra' => $idauto,
    //             'serie' => $serie,
    //             'num' => $num,
    //             'v' => $v,
    //             'carritot' => $c,
    //             'items' => $items,
    //             'total' => $montototal
    //         ]
    //     );
    // }
    // function grabarSesion(Request $request)
    // {
    //     \session()->set('idprovoc', $request->get('idprov'));
    //     \session()->set('razooc', $request->get('razo'));
    //     \session()->set('tdococ', $request->get('tdoc'));
    //     \session()->set('cndococ', $request->get('cndoc'));
    //     \session()->set('numoc', $request->get('num'));
    //     \session()->set('ndo2oc', $request->get('ndo2'));
    //     \session()->set('almoc', $request->get('alm'));
    //     \session()->set('formoc', $request->get('form'));
    //     \session()->set('moneoc', $request->get('mone'));
    //     \session()->set('fechioc', $request->get('fechi'));
    //     \session()->set('fechfoc', $request->get('fechf'));
    //     \session()->set('dolaroc', $request->get('dolar'));
    //     \session()->set('optigvoc', $request->get('optigv'));
    //     \session()->set('obsoc', $request->get('txtobservacion'));
    //     \session()->set('despoc', $request->get('txtdespacho'));
    //     \session()->set('ateoc', $request->get('txtatencion'));
    // }
    function indexlistarxrecibir()
    {
        return \view('traspasos/informes/indexlistartraspasosxrecibir', ['titulo' => 'Listar Guias x Traspasos']);
    }
    function listarxrecibir(Request $request)
    {
        $cmbalmacen = $request->get('cmbalmacen');
        $traspasos = new Traspasos();
        $lista = $traspasos->listartraspasosxrecibir($cmbalmacen);
        return \view('traspasos/informes/listatraspasosxrecibir', ['listado' => $lista]);
    }
    function verdetalletraspaso(Request $request)
    {
        $vtas = new Ventas();
        $idauto = $request->get("idauto");
        $lista = $vtas->consultardetalleventa($idauto);
        $_SESSION['detalletraspaso'] = $lista;
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $lista], 200);
    }
    function aceptartraspaso(Request $request)
    {
        $traspasos = new Traspasos();
        $lista = $traspasos->aceptartraspaso($request->get('idauto'));
        $_SESSION['detalletraspaso'] = [];
        return response()->json(['message' => 'Se logró registrar correctamente', 'estado' =>  $lista['estado']], 200);
    }
}
