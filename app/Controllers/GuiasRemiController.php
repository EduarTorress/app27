<?php

namespace App\Controllers;

use App\Models\Cliente;
use Core\Http\Request;
use App\Models\GuiaRemitente;
use Core\Routing\Controller;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\Proveedor;
use App\Models\Ventas;
use Core\Clases\Imprimir;
use Valitron\Validator;
use App\Services\CarritoService;

class GuiasRemiController extends Controller
{
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        //$this->transportista = new Transportista();
    }
    function index()
    {
        if (empty($_SESSION['guiar']['txtIdauto'])) {
            session()->remove('carritogrr');
        }
        $carritov = session()->get('carritogr', []);
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);
        return view('guiasr/index', ['titulo' => 'Emitir Guia', 'carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
    }
    function buscarDestinatario(Request $request)
    {
        $cliente = new Cliente();
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = $opt <> 2 ? 0 : intval($request->get('cbuscar'));
        $lista = $cliente->BuscarClientes($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/cliente/re_destinatarios', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function seleccionadoDestinatario(Request $request)
    {
        $destinatario = array();
        $destinatario = array(
            'nombre' => $request->get("nombre"),
            'idDestinatario' => $request->get('idDestinatario'),
            'destinatarioDireccion' => $request->get('destinatarioDireccion'),
            'ubigDestinatario' => $request->get('ubigDestinatario'),
            'rucDestinatario' => $request->get('rucDestinatario')
        );
        \session()->set('destinatario', $destinatario);
        return response()->json([
            'message' => 'Destinatario Seleccionado  correctamente'
        ], 200);
    }
    function buscarRemitente(Request $request)
    {
        $proveedor = new Proveedor();
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = $opt <> 2 ? 0 : intval($request->get('cbuscar'));
        $lista = $proveedor->buscarProveedor($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/proveedor/re_proveedores', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function seleccionadoRemitente(Request $request)
    {
        $remitente = array();
        $remitente = array(
            'razo' => $request->get("razo"),
            'idRemitente' => $request->get('idRemitente'),
            'remitenteDireccion' => $request->get('remitenteDireccion'),
            'ubigeoRemitente' => $request->get('ubigeoRemitente')
        );
        \session()->set('remitente', $remitente);
        return response()->json([
            'message' => 'Remitente seleccionado correctamente.'
        ], 200);
    }
    function seleccionadoTranportista(Request $request)
    {
        $transportista = array();
        $transportista = array(
            'idTransportista' => $request->get("idTransportista"),
            'nombre' => $request->get('nombre'),
            'txtRazonTransportista' => $request->get('txtRazonTransportista'),
            'txtplacaTransportista' => $request->get('txtplacaTransportista'),
            'txtMarcaTransportista' => $request->get('txtMarcaTransportista'),
            'txtPlaca' => $request->get("txtPlaca"),
            'txtPlaca1' => $request->get("txtPlaca1"),
            'txtBrevete' => $request->get("txtBrevete")
        );
        \session()->set('transportista', $transportista);
        return response()->json([
            'message' => 'Tranportista seleccionado correctamente.'
        ], 200);
    }
    function indexListar()
    {
        return view('guiasr/informes/indexListar', ['titulo' => 'Listar Guias']);
    }
    function indexListarxenviar()
    {
        return view('guiasr/informes/indexListarxenviar', ['titulo' => 'Guias por Informar']);
    }
    function listarGuias(Request $request)
    {
        $oguia = new GuiaRemitente();
        $lista = $oguia->listar();
        return \view('guiasr/re_guias', ['listado' => $lista]);
    }
    function listarGuiasparacanje(Request $request)
    {
        $oguia = new GuiaRemitente();
        $lista = $oguia->listarparacanje();
        return \view('guiasr/re_guias', ['listado' => $lista]);
    }
    function indexListarGuias(Request $request)
    {
        $oguia = new GuiaRemitente();
        $dfi = $request->get('dfi');
        $dff = $request->get('dff');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $lista = $oguia->listarPorFechas($dfi, $dff, $cmbAlmacen);
        return \view('guiasr/informes/re_guiasindex', ['listado' => $lista]);
    }
    function listarGuiasxenviar(Request $request)
    {
        $dplaca = $request->get('dplaca');
        $oguia = new GuiaRemitente();
        $lista = $oguia->listarxenviar($dplaca);
        return \view('guiasr/informes/re_guiasxenviar', ['listado' => $lista]);
    }
    function imprimir(Request $request)
    {
        $oguia = new GuiaRemitente();
        $lista = $oguia->consultarGuiaDetalle($request->get("nidauto"), $request->get("motivo"));
        $oimp = new Imprimir();
        $i = 1;
        $tpeso = 0;
        $rutapdf = 'descargas/guia' . '.pdf';
        foreach ($lista as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'peso' => $fila['peso'],
                'subtotal' => round($fila['cant'] * $fila['peso'], 2),
                'scop' => $fila['entr_codi']
            );
            $tpeso += $fila['cant'] * $fila['peso'];
            if ($i == 1) {
                $oimp->empresa = session()->get('gene_empresa');
                $oimp->rucempresa = session()->get('gene_nruc');
                $oimp->direccionempresa = session()->get("gene_ptop");
                $oimp->tipocomprobante = 'GUIA DE REMISION REMITENTE';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['fech']));
                $oimp->fechat = date("d/m/Y", strtotime($fila['fechat']));
                $oimp->fecha = $dfecha;
                $oimp->tdoc = '09';
                $oimp->rucdestinatario = (empty(trim($fila['nruc'])) ? $fila['ndni'] : $fila['nruc']);
                $oimp->destinatario = $fila['razo'];
                $oimp->ptopartida = $fila['ptopartida'];
                $oimp->ptollegada = $fila['ptollegada'];
                $oimp->placa = $fila['placa'];
                $oimp->placa1 = $fila['placa1'];
                $oimp->conductor = ($fila['conductor']);
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->constancia = $fila['constancia'];
                $oimp->ructransportista = $fila['ructr'];
                $oimp->qrsunat = $fila['guia_arch'];
                $oimp->nombretransportista = $fila['razont'];
                $oimp->referencia = $fila['detalle'];
                $oimp->tipotransporte  = $fila['tran_tipo'] == '01' ? 'Público' : 'Privado';
                if ($request->get("motivo") == 'C' || $request->get('motivo') == 'D') {
                    $oimp->rucremitente = $fila['rucproveedor'];
                    $oimp->remitente = $fila['proveedor'];
                }
                $nombreXML = session()->get('gene_nruc') . $fila['dcto'];
                $rutapdf = 'descargas/' . $nombreXML . 'pdf';
            }
            $i++;
        }
        $oimp->totalpeso = $tpeso;
        if ($request->get("motivo") == 'V') {
            $oimp->generarPDFGuiaRemitente($rutapdf);
        } else {
            if ($request->get('motivo') == 'C') {
                $oimp->generarPDFGuiaRemitentecompra($rutapdf);
            } else {
                $oimp->generarPDFGuiaxdevolucion($rutapdf);
            }
        }
    }
    function enviarsunat(Request $request)
    {
        $oguia = new GuiaRemitente();
        $lista = $oguia->consultarGuiaDetalle($request->get("nidauto"));
        $i = 1;
        $tpeso = 0;
        $items = array();
        $guia = array();
        foreach ($lista as $fila) {
            $items[] = array(
                'item' => $i,
                'coda' => $i,
                'unid' => 'NIU',
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'peso' => $fila['peso'],
                'subtotal' => round($fila['cant'] * $fila['peso'], 2)
            );
            $tpeso += $fila['cant'] * $fila['peso'];

            if ($i == 1) {
                $guia = array(
                    "empresa" => $fila['empresa'],
                    "rucempresa" => $fila['rucempresa'],
                    "direccionempresa" => $fila['ptop'],
                    "numero" => substr($fila['ndoc'], -8, 8),
                    "serie" => substr($fila['ndoc'], 0, 4),
                    "ndoc" => $fila['ndoc'],
                    "fecha" => $fila['fech'],
                    "fechat" => $fila['fecht'],
                    "tdoc" => '31',
                    "rucremitente" => $fila['rucremitente'],
                    "ubigeoremitente" => $fila['guia_ubi1'],
                    "ubigeodestinatario" => $fila['guia_ubi2'],
                    "rucdestinatario" => $fila['rucdestinatario'],
                    "remitente" => $fila['remitente'],
                    "destinatario" => $fila['destinatario'],
                    "ptopartida" => $this->$fila['ptopartida'],
                    "ptollegada" => $this->$fila['ptollegada'],
                    "placa" => $fila['placa'],
                    "conductor" => $fila['guia_cond'],
                    "brevete" => $fila['guia_brev'],
                    "marca" => $fila['marca'],
                    "constancia" => trim($fila['constancia']),
                    "idguia" => $fila['idauto'],
                    "gene_usol" => trim($fila['gene_usol']),
                    "gene_csol" => trim($fila['gene_csol']),
                    "gene_cert" => trim($fila['gene_cert']),
                    "clavecerti" => $fila['clavecerti'],
                    "regmtc" => $fila['gene_rmtc'],
                    "chofer" => $fila['chofer'],
                    "idauto" => $fila['idauto'],
                    "ptop" => $fila['ptop'],
                    "ciudad" => $fila['ciudad'],
                    "distrito" => $fila['distrito'],
                    "gene_rmtc" => $_SESSION['gene_gene_rmtc']
                );
            }
            $i++;
        }

        $guia['tpeso'] = $tpeso;
        $guia['items'] = $items;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/app88/envioguiatransportista.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($guia),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        return json_decode($response, true);
    }
    function descargarxml(Request $request)
    {
        $cfile = "http://companiasysven.com/app88/xml/" . substr($request->get("nombrexml"), 0, 11) . '/' . $request->get("nombrexml");
        return file_get_contents($cfile);
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
                'peso' => $item['peso'],
                'scop' => $item['scop'],
                'subtotal' => round($item['cantidad'] * $item['peso'], 2)
            );
            $tpeso += $item['cantidad'] * $item['peso'];
            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = ""; // Modificado
                $oimp->tipocomprobante = 'GUIA REMITENTE ELECTRONICA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $oimp->fechat = $fila['fechat'];
                $oimp->fecha = $fila['fecha'];
                $oimp->tdoc = '09';
                $oimp->rucdestinatario = $fila['txtrucDestinatario'];
                // echo "p" . $fila['remitente'];
                $oimp->nombretransportista = $fila['transportista'];
                $oimp->ructransportista = $fila['ructransportista'];
                $oimp->remitente = $fila['remitente'];
                $oimp->destinatario = $fila['destinatario'];
                $oimp->ptopartida = $fila['ptopartida'];
                $oimp->ptollegada = $fila['ptollegada'];
                $oimp->placa = $fila['placa'];
                $oimp->placa1 = $fila['placa1'];
                $oimp->conductor = $fila["conductor"];
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->referencia = isset($fila['referencia']) ? $fila['referencia'] : '';
                $oimp->tipotransporte  = $fila['txttipot'] == '01' ? 'Público' : 'Privado';
                $oimp->constancia = isset($fila['constancia']) ? $fila['constancia'] : '';
                $rutapdf = 'descargas/' . $fila['ndoc'] . '.pdf';
            }
            $i++;
        }
        #I ES PARA GUARDAR EN EL SERVIDOR F
        # PARA DESCARGAR EL ARCHIVO ES D
        $oimp->totalpeso = $tpeso;
        // $estilo=$_SESSION['estilo'];

        $oimp->generarPDFGuiaRemitente($rutapdf, 'I');

        $_SESSION['datosguia'] = [];
        $_SESSION['detalle'] = [];
    }
    ///////
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siestaguiasr($idart)) {
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
                'message' => 'Producto ya agregado a la guía',
                'rpta' => 'N'
            ];
            return response()->json($data, 422);
        }
        $validar = new Validator($request->getBody());
        $validar->labels([
            'peso' => 'txtpeso',
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
            'peso' => $request->get("txtpeso"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'costo' => $request->get('costo')
        );

        CarritoService::agregarItemGuiar($producto);
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);

        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items   
        // ], 200);

        $carritov = session()->get('carritogr', []);
        $cvista = \retornavista('guiasr', 'detalle');
        return view($cvista, [
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items,
            'carritogr' => session()->get("carritogr", [])
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemGuiar($pos);
        $carritogr = session()->get('carritogr', []);
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('guiasr', 'detalle');
        return view($cvista, ['carritov' => $carritogr, 'total' => $total, 'items' => $numero_items]);
    }
    function registrar(Request $request)
    {
        // $validar = new Validator($request->getBody());
        // $validar->rule("required", "txtubigeod")->message('El Ubigeo del Destinatario es Obligatorio');
        // if (!$validar->validate()) {
        //     $data = ["errors" => $validar->errors()];
        //     // var_dump($data);
        //     return response()->json($data, 422);
        // }
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == '0') {
            return response()->json($ovalidar, 422);
        }

        $oguia = new GuiaRemitente();
        $oguia->dfecha = Date("Y-m-d", strtotime($request->get("txtFechaEmision")));
        $oguia->dfechat = Date("Y-m-d", strtotime($request->get("txtFechaTraslado")));
        $oguia->tdoc = '09';
        $oguia->cptop = $request->get("txtDireccionRemitente");
        $oguia->cptoll = $request->get("txtDireccionDestinatario");
        $oguia->nidr =  0; //ID de remitente
        $oguia->nidd =  $request->get("idDestinatario");
        $oguia->nidtr = $request->get("idtransportista");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->nidv2 = 0;
        $oguia->cdetalle = $request->get("txtReferencia");
        $oguia->cubigeo1 = 0;
        $oguia->cubigeo2 = $request->get("txtubigeod");
        $oguia->brevete = $request->get("txtBrevete");
        $oguia->idautov = $request->get('idautov');

        $detalle = session()->get('carritogr', []);

        if ($oguia->idautov == "0") {
            $rpta = $oguia->grabar($detalle);
        } else {
            $rpta = $oguia->grabarguiafromvta($detalle);
        }

        if ($rpta['estado'] == 1) {
            $datosguia = array(
                "empresa" => session()->get('gene_empresa'),
                "rucempresa" => session()->get('gene_nruc'),
                "direccionempresa" => session()->get("gene_ptop"),
                "numero" => substr($rpta['ndoc'], 0, 4) . '-' . substr($rpta['ndoc'], -8, 8),
                "serie" => substr($rpta['ndoc'], 0, 4),
                "ndoc" => $rpta['ndoc'],
                "dfecha" =>  $request->get("txtFechaEmision"),
                "fechat" => $request->get("txtFechaTraslado"),
                "fecha" => $request->get("txtFechaEmision"),
                "tdoc" => "09",
                "rucremitente" => $request->get("txtNombreRemitente"),
                "txtrucDestinatario" => $request->get("txtrucDestinatario"),
                "ructransportista" => $request->get("txttruc"),
                "remitente" => $request->get("txtNombreRemitente"),
                "destinatario" => $request->get("txtNombreDestinatario"),
                "ptopartida" => $request->get("txtDireccionRemitente"),
                "ptollegada" => $request->get("txtDireccionDestinatario"),
                "placa1" => $request->get("txtPlaca1"),
                "transportista" => $request->get("txttransportista"),
                "placa" => $request->get("txtPlaca"),
                "conductor" => $request->get("txtChoferVehiculo"),
                "brevete" => $request->get("txtBrevete"),
                "marca" => $request->get("txtmarca"),
                "referencia" => $request->get('txtReferencia'),
                "constancia" => $request->get('txtregmtc'),
                "txttipot" => $request->get('txttipot')
            );
        }
        $carritodetalle = [];
        $carritov = session()->get('carritogr', []);
        foreach ($carritov as $c) {
            if ($c['activo'] == 'A') {
                array_push($carritodetalle, $c);
            }
        }
        $_SESSION['datosguia'] = $datosguia;
        $_SESSION['detalle'] = $carritodetalle;
        $this->limpiar();
        return json_encode($rpta);
    }
    function limpiar()
    {
        session()->remove('carritogr');
        session()->remove('carritogrr');
        session()->remove('destinatario');
        session()->remove('transportista');
    }
    function soloItem(Request $request)
    {
        // $itemcarrito = CarritoService::itemGuia($request->get('indice'));
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'peso' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtpeso')),
            'scop' => $request->get('txtscop')
        );
        CarritoService::editarProductoGuiar($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function listarDetalle()
    {
        $carritov = session()->get('carritogr', []);
        $btn = "Grabar";
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);
        return view('guiasr/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function consultarGuiaPorId($idGuia)
    {
        $id = $idGuia;
        $oguia = new GuiaRemitente();
        $lista = $oguia->consultarGuiaDetalle($id, 'V');
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $_SESSION['ndoc'] = $item['dcto'];
                $_SESSION['idautov'] = $item['idautov'];
                $_SESSION['idautog'] = $item['idautog'];
                $guiar = array(
                    'fechaTraslado' => $item['fechat'],
                    'fechaEmision' => $item['fech'],
                    'direccionRemitente' => $item['ptop'],
                    'txtIdauto' => $item['idgui'],
                    'txtReferencia' => $item['detalle'],
                    'txtnumerodocumento' => $item['ndoc']
                );
                $destinatario = array(
                    'idDestinatario' => $item['idclie'],
                    'nombre' => $item['razo'],
                    'destinatarioDireccion' => $item['ptollegada'],
                    'ubigDestinatario' => $item['guia_ubi2']
                );
                $transportista = array(
                    'txtIdTransportista' => $item['guia_idtr'],
                    'txttransportista' => $item['razont'],
                    'txtruc' => $item['ructr'],
                    'txtPlaca' => $item['placa'],
                    'txtmarca' => $item['marca'],
                    'txtChoferVehiculo' => $item['conductor'],
                    'txtbrevete' => $item['brevete'],
                    'txtregmtc' => $item['constancia'],
                    'txttipot' => $item['tran_tipo']
                );
                $aceptado = $item['guia_mens'];
            }
            $i++;
            $detallegr[] = array(
                'coda' => $item["coda"],
                'unidad' => $item['unid'],
                'nreg' => 0,
                'descri' => $item["descri"],
                'cantidad' => $item['cant'],
                'scop' => $item['entr_codi'],
                'peso' => $item['peso'],
                'stock' => 0,
                'precio1' => 0,
                'precio2' => 0,
                'precio3' => 0,
                'costo' => 0,
                'activo' => 'A'
            );
        }

        session()->set("estadoguia", $aceptado);
        // session()->set("carritogr", $detallegr);
        $_SESSION['carritogrr'] = $detallegr;
        session()->set('guiar', $guiar);
        session()->set('destinatario', $destinatario);
        session()->set('transportista', $transportista);

        $cvista = retornavista('guiasr', 'index');

        $carritov = session()->get('carritogrr', []);
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);

        $titulo = 'Actualizar guía';
        return view($cvista, [
            'titulo' => $titulo,
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items
        ]);
    }
    function actualizar(Request $request)
    {
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == '0') {
            return response()->json($ovalidar, 422);
        }
        $oguia = new GuiaRemitente();
        $venta = new Ventas();
        $tienedctosrelacionados = $venta->consultarsitienedctorelacionado(session()->get("idautov"));
        if (count($tienedctosrelacionados) > 0) {
            return response()->json(["errors" => ['No se puede actualizar porque tiene un documento relacionado']], 422);
        }
        $oguia->idauto = intval($request->get("txtIdauto"));
        $oguia->dfecha = Date("Y-m-d", strtotime($request->get("txtFechaEmision")));
        $oguia->dfechat = Date("Y-m-d", strtotime($request->get("txtFechaTraslado")));
        $oguia->cndoc = $request->get("txtnumerodocumento");
        $oguia->tdoc = '09';
        $oguia->cptop = $request->get("txtDireccionRemitente");
        $oguia->cptoll = $request->get("txtDireccionDestinatario");
        $oguia->nidr =  0; //ID de remitente
        $oguia->nidd =  $request->get("idDestinatario");
        $oguia->nidtr = $request->get("txtIdTransportista");
        $oguia->nidv2 = 0;
        $oguia->cdetalle = $request->get("txtReferencia");
        $oguia->cubigeo1 = 0;
        $oguia->cubigeo2 = $request->get("txtubigeod");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->brevete = $request->get("txtBrevete");

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $rpta = $oguia->actualizar($detalle);

        $_SESSION['ndoc'] = "";
        $_SESSION['idautov'] = "";
        $_SESSION['idautog'] = "";

        return json_encode($rpta);
    }
    function validar($request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idDestinatario")->message("Es obligatorio el Destinatario");
        $validar->rule("required", "txtFechaEmision")->message("Fecha de Emisión no es Válida");
        $validar->rule("required", "txtFechaTraslado")->message("Fecha de Traslado no es Válida");
        $validar->rule("required", "idtransportista")->message("Es obligatorio el Transportista");
        $validar->rule("lengthMin", "txtubigeod", 5)->message('El Ubigeo del Destinatario es obligatorio');
        $validar->rule("lengthMax", "txtubigeod", 6)->message('El Ubigeo del Destinatario es obligatorio');
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors(), "estado" => 0];
            return $data;
        }
        $datetime1 = date_create($request->get('txtFechaEmision'));
        $datetime2 = date_create($request->get('txtFechaTraslado'));
        $interval = date_diff($datetime2, $datetime1);
        if (!fechavalida(date('d/m/Y', strtotime($request->get('txtFechaEmision'))))) {
            $data = ["errors" => ['Fecha de Emisión no Válida'], "estado" => 0];
            return $data;
        }
        if (!fechavalida(date('d/m/Y', strtotime($request->get('txtFechaTraslado'))))) {
            $data = ["errors" => ['Fecha de Traslado no Válida'], "estado" => 0];
            return $data;
        }
        $ndias = $interval->days;
        if ($ndias > 1) {
            $data = ["errors" => ['La fecha de traslado no puede ser mayor a 1 Día'], "estado" => 0];
            return $data;
        }
        if ($ndias < 0) {
            $data = ["errors" => ['La fecha de traslado mo puede ser antes que la fecha de emisión'], "estado" => 0];
            return $data;
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => ["Sesión vacía, cierre el sistema y vuelva a ingresar"], "estado" => 0];
            return $data;
        }
        // if (empty($_SESSION["carritogr"])) {
        //     $data = ["errors" => ['Se requiere productos para registrar la Guia Remitente'], "estado" => 0];
        //     return $data;
        // }
        $crpta = session()->get("estadoguia", '');
        if (substr($crpta, 0, 1) == '0') {
            $data = ["errors" => ['Este Documento Ya está Informado a SUNAT no es posible actualizar'], "estado" => 0];
            return $data;
        }

        $datemax = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        if ($datemax > $request->get('txtFechaEmision')) {
            $data = ["errors" => ['La guía no puede tener más de un día de emisión comparada a la fecha actual.'], "estado" => 0];
            return $data;
        }
        $data = ["errors" => ["ok"], "estado" => 1];
        return $data;
    }
    function actualizarEstadoGuiaR(Request $request)
    {
        $id = $request->get("nidauto");
        $oguia = new GuiaRemitente();
        $rpta = $oguia->actualizarEstadoGuiaR($id);
        return json_encode($rpta);
    }
    function listarvtastocanje(Request $request)
    {
        $venta = new Ventas();
        $listado = $venta->listarventastocanje();
        return view('guiasr/modallistavtastocanje', ['listado' => $listado]);
    }
    function listardetalledevtatocanje(Request $request)
    {
        $venta = new Ventas();
        $listado = $venta->listardetallevtatocanje($request->get('idauto'));
        foreach ($listado as $item) {
            $carritov[] = array(
                'coda' => $item['idart'],
                'descri' => $item['descri'],
                'unidad' => $item['unid'],
                'cantidad' => $item['cant'],
                'peso' => 1,
                'stock' => 0,
                'precio1' => 0,
                'precio2' => 0,
                'precio3' => 0,
                'costo' => 0,
                'nreg' => $item['idkar'], //Se va a comportar como un idkar
                'scop' => '',
                'activo' => 'A'
            );
        }
        // session()->set('idauto', $request->get('idauto'));
        session()->set('carritogr', $carritov);
        $carritov = session()->get('carritogr', $carritov);
        $btn = "Grabar";
        $total = number_format(CarritoService::totalGuiar(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsGuiar(), 2, '0', STR_PAD_LEFT);
        return view('guiasr/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
}
