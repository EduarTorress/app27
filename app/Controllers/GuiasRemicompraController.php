<?php

namespace App\Controllers;

use App\Models\Cliente;
use Core\Http\Request;
use App\Models\GuiaRemitente;
use Core\Routing\Controller;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\GuiaCompraRemitente;
use App\Models\Proveedor;
use Core\Clases\Imprimir;
use Valitron\Validator;
use App\Services\CarritoServicegrcompra;
use DateTime;

class GuiasRemicompraController extends Controller
{
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        //$this->transportista = new Transportista();
    }
    function index()
    {
        if (empty($_SESSION['guiac']['txtIdauto'])) {
            session()->remove('carritogrc');
        }
        $carritov = session()->get('carritogc', []);
        $total = number_format(CarritoServicegrcompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServicegrcompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        return view('guiasc/index', ['titulo' => 'Emitir Guia', 'carritov' => $carritov, 'total' => $total, 'items' => $numero_items]);
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
        return view('guiasc/indexListar', ['titulo' => 'Listar Guias']);
    }
    function indexListarxenviar()
    {
        return view('guiasc/indexListarxenviar', ['titulo' => 'Guias por Informar']);
    }
    function indexListarGuias(Request $request)
    {
        $oguia = new GuiaRemitente();
        $dfi = $request->get('dfi');
        $dff = $request->get('dff');
        $lista = $oguia->listarPorFechas($dfi, $dff, 1);
        return \view('guiasc/re_guiasindex', ['listado' => $lista]);
    }
    function listarGuiasxenviar(Request $request)
    {
        $dplaca = $request->get('dplaca');
        $oguia = new GuiaRemitente();
        $lista = $oguia->listarxenviar($dplaca);
        return \view('guiasc/re_guiasxenviar', ['listado' => $lista]);
    }
    // function imprimir(Request $request)
    // {
    //     $oguia = new GuiaRemitente();
    //     $lista = $oguia->consultarGuiaDetalle($request->get("nidauto"));
    //     $oimp = new Imprimir();
    //     $i = 1;
    //     $tpeso = 0;
    //     $rutapdf = 'descargas/Guia' . '.pdf';
    //     foreach ($lista as $fila) {
    //         $oimp->items[] = array(
    //             'item' => $i,
    //             'unid' => $fila['unid'],
    //             'descri' => $fila['descri'],
    //             'cant' => $fila['cant'],
    //             'peso' => $fila['peso'],
    //             'subtotal' => round($fila['peso'], 2)
    //         );
    //         $tpeso += $fila['peso'];
    //         if ($i == 1) {
    //             $oimp->empresa = session()->get('gene_empresa');
    //             $oimp->rucempresa = session()->get('gene_nruc');
    //             $oimp->direccionempresa = $fila['ptopartida'];
    //             $oimp->tipocomprobante = 'GUIA DE REMISION REMITENTE';
    //             $oimp->numero = substr($fila['dcto'], 0, 4) . '-' . substr($fila['dcto'], -8, 8);
    //             $oimp->serie = substr($fila['dcto'], 0, 4);
    //             $oimp->ndoc = $fila['dcto'];
    //             $dfecha = date("d/m/Y", strtotime($fila['fech']));
    //             $oimp->fechat = date("d/m/Y", strtotime($fila['fechat']));
    //             $oimp->fecha = $dfecha;
    //             $oimp->tdoc = '09';
    //             $oimp->rucdestinatario = $fila['nruc'];
    //             $oimp->destinatario = $fila['razo'];
    //             $oimp->ptopartida = $fila['ptopartida'];
    //             $oimp->ptollegada = $fila['ptollegada'];
    //             $oimp->placa = $fila['placa'];
    //             $oimp->conductor = ($fila['conductor']);
    //             $oimp->brevete = $fila['brevete'];
    //             $oimp->marca = $fila['marca'];
    //             $oimp->constancia = $fila['constancia'];
    //             $oimp->ructransportista = $fila['ructr'];
    //             $oimp->nombretransportista = $fila['razont'];
    //             $oimp->referencia = $fila['detalle'];
    //             $oimp->tipotransporte  = $fila['tran_tipo'] == '01' ? 'Público' : 'Privado';
    //             $nombreXML = session()->get('gene_nruc') . $fila['dcto'];
    //             $rutapdf = 'descargas/' . $nombreXML . 'pdf';
    //         }
    //         $i++;
    //     }
    //     $oimp->totalpeso = $tpeso;
    //     $oimp->generarPDFGuiaRemitente($rutapdf);
    // }
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
                'descri' => $item['descripcion'],
                'cant' => $item['cantidad'],
                'peso' => $item['peso'],
                'scop' => $item['scop'],
                'subtotal' => round($item['peso'], 2)
            );
            $tpeso += $item['peso'];
            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = session()->get("gene_ptop"); // Modificado
                $oimp->tipocomprobante = 'GUIA REMITENTE ELECTRONICA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $oimp->fechat = $fila['dfecha'];
                $oimp->fecha = $fila['fechat'];
                $oimp->tdoc = '09';
                $oimp->rucdestinatario = session()->get("gene_nruc");
                $oimp->referencia = $fila['referencia'];
                $oimp->nombretransportista = $fila['transportista'];
                $oimp->ructransportista = $fila['ructransportista'];
                $oimp->remitente = $fila['proveedor'];
                $oimp->destinatario = session()->get("gene_empresa");
                $oimp->ptopartida = $fila['ptopartida'];
                $oimp->ptollegada = $fila['ptollegada'];
                $oimp->placa = $fila['placa'];
                $oimp->conductor = $fila["conductor"];
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->constancia = "Constancia";
                $oimp->rucremitente = $fila['rucproveedor'];
                $oimp->remitente = $fila['proveedor'];

                $rutapdf = 'descargas/' . $fila['ndoc'] . '.pdf';
            }
            $i++;
        }
        #I ES PARA GUARDAR EN EL SERVIDOR F
        # PARA DESCARGAR EL ARCHIVO ES D
        $oimp->totalpeso = $tpeso;
        // $estilo=$_SESSION['estilo'];

        $oimp->generarPDFGuiaRemitenteCompra($rutapdf, 'I');

        $_SESSION['datosguia'] = [];
        $_SESSION['detalle'] = [];
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoServicegrcompra::siesta($idart)) {
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
            'costo' => $request->get('costo'),
            'scop' => $request->get('scop')
        );

        CarritoServicegrcompra::agregar($producto);
        $total = number_format(CarritoServicegrcompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServicegrcompra::numeroItems(), 2, '0', STR_PAD_LEFT);

        $carritov = session()->get('carritogc', []);
        $cvista = \retornavista('guiasc', 'detalle');
        return view($cvista, [
            'carritov' => $carritov,
            'total' => $total,
            'items' => $numero_items,
            'carritogr' => session()->get("carritogc", [])
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoServicegrcompra::quitar($pos);
        $carritogr = session()->get('carritogc', []);
        $total = number_format(CarritoServicegrcompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServicegrcompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('guiasc', 'detalle');
        return view($cvista, ['carritov' => $carritogr, 'total' => $total, 'items' => $numero_items]);
    }
    function registrar(Request $request)
    {
        $ovalidar = $this->validar($request);
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $oguia = new GuiaCompraRemitente();
        $oguia->dfecha = $request->get("txtFechaEmision");
        $oguia->dfechat = $request->get("txtFechaTraslado");
        $oguia->cptop = $request->get("txtptopartida");
        $oguia->cptoll = $request->get("txtptollegada");
        $oguia->nidpr =  $request->get("idProveedor");
        $oguia->nidtr = $request->get("txtIdTransportista");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->cdetalle = "";
        $oguia->cubigeo1 = 0;
        $oguia->referencia = $request->get("txtreferencia");
        $oguia->cubigeo2 = $request->get("txtUbigeoproveedor");
        $oguia->brevete = $request->get("txtBrevete");

        $detalle = session()->get('carritogc', []);

        $rpta = $oguia->Grabarguiacompra($detalle);
        if ($rpta['estado'] == 1) {
            $datosguia = array(
                "empresa" => session()->get('gene_empresa'),
                "rucempresa" => session()->get('gene_nruc'),
                "direccionempresa" => session()->get("gene_ptop"),
                "numero" => substr($rpta['ndoc'], 0, 4) . '-' . substr($rpta['ndoc'], -8, 8),
                "serie" => substr($rpta['ndoc'], 0, 4),
                "ndoc" => $rpta['ndoc'],
                "dfecha" => Date("d/m/Y", strtotime($request->get("txtFechaEmision"))),
                "fechat" => Date("d/m/Y", strtotime($request->get("txtFechaTraslado"))),
                "tdoc" => "09",
                "rucproveedor" => $request->get("rucproveedor"),
                "proveedor" => $request->get("proveedor"),
                "ructransportista" => $request->get("txtructransportista"),
                "referencia" => $request->get("txtreferencia"),
                "ptopartida" => $request->get("txtptopartida"),
                "ptollegada" => $request->get("txtptollegada"),
                "placa1" => $request->get("txtPlaca1"),
                "transportista" => $request->get("txttransportista"),
                "placa" => $request->get("txtPlaca"),
                "conductor" => $request->get("txtChoferVehiculo"),
                "brevete" => $request->get("txtBrevete"),
                "marca" => $request->get("txtmarca"),
                "constancia" => $request->get("txtregmtc"),
                "referencia" => $request->get("txtreferencia")
            );
        }
        $carritodetalle = [];
        $carritov = session()->get('carritogc', []);
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
        session()->remove('carritogc');
        session()->remove('carritogrc');
        session()->remove('guiac');
        session()->remove('destinatario');
        session()->remove('transportista');
    }
    function soloItem(Request $request)
    {
        // $itemcarrito = CarritoServicegrcompra::item($request->get('indice'));
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'peso' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtpeso')),
            'scop' => $request->get('txtscop')
        );
        CarritoServicegrcompra::editar($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function listarDetalle()
    {
        $carritov = session()->get('carritogc', []);
        $btn = "Grabar";
        $total = number_format(CarritoServicegrcompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServicegrcompra::numeroItems(), 2, '0', STR_PAD_LEFT);
        // echo $numero_items;
        return view('guiasc/detalle', ['carritov' => $carritov, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function consultarGuiaPorId($idGuia)
    {
        // session()->set('arrayEliminados',[]);
        $id = $idGuia;
        $oguia = new GuiaRemitente();
        $lista = $oguia->consultarGuiaDetalle($id, 'C');
        $i = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $_SESSION['ndoc'] = $item['dcto'];
                $_SESSION['idautog'] = $item['idautog'];
                $guiac = array(
                    'fechaTraslado' => $item['fechat'],
                    'fechaEmision' => $item['fech'],
                    'ptollegada' => $item['ptollegada'],
                    'txtIdauto' => $item['idgui']
                );
                $proveedor = array(
                    'idprov' => $item['idprov'],
                    'razoprov' => $item['proveedor'],
                    'rucprov' => $item['rucproveedor'],
                    // 'guia_ubi1' => $item['guia_ubi1'],
                    // 'ubigDestinatario' => $item['guia_ubi2'],
                    'ubigprov' => $item['ubigprov'],
                    'direprov' => $item['ptopartida'],
                    'ciudprov' => $item['ciudad'] . ' ' . $item['distrito'],
                    'referencia' => $item['guia_dcto']
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
                'descripcion' => $item["descri"],
                'descri' => $item["descri"],
                'cantidad' => $item['cant'],
                'peso' => $item['peso'],
                'scop' => $item['entr_codi'],
                'stock' => 0,
                'precio1' => 0,
                'precio2' => 0,
                'precio3' => 0,
                'costo' => 0,
                'activo' => 'A'
            );
        }

        session()->set("estadoguia", $aceptado);
        // session()->set("carritogc", $detallegr);
        $_SESSION['carritogrc'] = $detallegr;
        session()->set('guiac', $guiac);
        session()->set('proveedor', $proveedor);
        session()->set('transportista', $transportista);

        $cvista = retornavista('guiasc', 'index');

        $carritov = session()->get('carritogrc', []);
        $total = number_format(CarritoServicegrcompra::total(), 2, '.', '');
        $numero_items = str_pad(CarritoServicegrcompra::numeroItems(), 2, '0', STR_PAD_LEFT);

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
        if ($ovalidar['estado'] == 0) {
            return response()->json($ovalidar['errors'], 422);
        }
        $estadoguia = substr(trim($_SESSION['estadoguia']), 0, 1);
        $array = explode(" ", $estadoguia);
        $validarphp = new Validator($array);

        $validarphp->rule("length", $array[0], 0)->message("Esta guia ya fue informada a SUNAT");

        if (!$validarphp->validate()) {
            $data = ["errors" => $validarphp->errors()];
            // var_dump($data);
            return response()->json($data, 422);
        }

        $oguia = new GuiaCompraRemitente();
        $oguia->idauto = $request->get("txtIdauto");
        $oguia->dfecha =  $request->get("txtFechaEmision");
        $oguia->dfechat = $request->get("txtFechaTraslado");
        $oguia->cptop = $request->get("txtptopartida");
        $oguia->cptoll = $request->get("txtptollegada");
        $oguia->nidpr =  $request->get("idProveedor");
        $oguia->nidtr = $request->get("txtIdTransportista");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->cdetalle = "";
        $oguia->cubigeo1 = 0;
        $oguia->referencia = $request->get("txtreferencia");
        $oguia->cubigeo2 = $request->get("txtUbigeoproveedor");
        $oguia->brevete = $request->get("txtBrevete");

        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $rpta = $oguia->actualizarguiacompra($detalle);

        $_SESSION['ndoc'] = "";
        $_SESSION['idautov'] = "";
        $_SESSION['idautog'] = "";

        session()->set('carritogc', []);

        return json_encode($rpta);
    }
    function validar($request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtUbigeoproveedor")->message('El Ubigeo del Remitemte es obligatorio');
        $validar->rule("required", "txtptopartida")->message('Es obligatorio la dirección del Punto de Partida');
        $validar->rule("required", "txtptollegada")->message('Es obligatorio la dirección del Punto de Llegada');
        $validar->rule('required', "txtFechaEmision")->message('Es obligatorio que la fecha de Emisión sea Válida');
        $validar->rule('required', "txtFechaTraslado")->message('Es obligatorio que la fecha de Traslado sea Válida');
        // $validar->rule('dateBefore', "txtFechaTraslado", "txtFechaEmision")->message("La fecha de traslado no puede ser antes que la fecha de emisión");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors(), "estado" => 0];
            return $data;
        }
        $a = new DateTime($request->get("txtFechaEmision"));
        $b = new DateTime($request->get("txtFechaTraslado"));
        if ($a > $b) {
            $data = ["errors" => ["La fecha de emisión no puede ser mayor a la de traslado"], "estado" => 0];
            return $data;
        }
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => ["Sesión Actual está vacía"], "estado" => 0];
            return $data;
        }
        $datemax = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
        if ($datemax > $request->get('txtFechaEmision')) {
            $data = ["errors" => ['La guía no puede tener más de un día de emisión comparada a la fecha actual.'], "estado" => 0];
            return $data;
        }
        $data = ["errors" => "ok", "estado" => 1];
        return $data;
    }
}
