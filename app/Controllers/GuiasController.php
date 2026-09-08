<?php

namespace App\Controllers;

use App\Models\Cliente;
use Core\Http\Request;
use App\Models\GuiaTransportista;
use Core\Routing\Controller;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\Proveedor;
use Core\Clases\Imprimir;
use Valitron\Validator;

class GuiasController extends Controller
{
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        //$this->transportista = new Transportista();
    }
    function index()
    {
        $detalleg = [];
        return view('guias/index', ['titulo' => 'Guia Transportista', 'detalleg' => $detalleg]);
    }
    function buscarDestinatario(Request $request)
    {
        $cliente = new Cliente();
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = intval($request->get('cbuscar'));
        $lista = $cliente->BuscarClientes($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/cliente/tm_destinatarios', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function seleccionadoDestinatario(Request $request)
    {
        $destinatario = array();
        $destinatario = array(
            'nombre' => $request->get("nombre"),
            'idDestinatario' => $request->get('idDestinatario'),
            'destinatarioDireccion' => $request->get('destinatarioDireccion'),
            'ubigDestinatario' => $request->get('ubigDestinatario')
        );
        \session()->set('destinatario', $destinatario);
        return response()->json([
            'message' => 'Destinatario seleccionado  correctamente'
        ], 200);
    }
    function buscarRemitente(Request $request)
    {
        $proveedor = new Proveedor();
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = $opt <> 2 ? 0 : intval($request->get('cbuscar'));
        $lista = $proveedor->muestraProveedoresModal($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/proveedor/tm_remitentes', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function seleccionadoRemitente(Request $request)
    {
        $remitente = array();
        $remitente = array(
            'razo' => $request->get("razo"),
            'idRemitente' => $request->get('idRemitente'),
            'remitenteDireccion' => $request->get('remitenteDireccion'),
            'ubigeoRemitente' => $request->get('ubigeoRemitente'),
            'rucRemitente' => $request->get("rucRemitente")
        );
        \session()->set('remitente', $remitente);
        return response()->json([
            'message' => 'Remitente seleccionado correctamente.'
        ], 200);
    }
    function registrar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtubigeor")->message('El Ubigeo del Remitente es Obligatorio');
        $validar->rule("required", "txtubigeod")->message('El Ubigeo del Destinatario es Obligatorio');
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            // var_dump($data);
            return response()->json($data, 422);
        }
        $oguia = new GuiaTransportista();
        $oguia->dfecha = Date("Y-m-d", strtotime($request->get("txtFechaEmision")));
        $oguia->dfechat = Date("Y-m-d", strtotime($request->get("txtFechaTraslado")));
        $oguia->tdoc = '31';
        $oguia->cptop = $request->get("txtDireccionRemitente");
        $oguia->cptoll = $request->get("txtDireccionDestinatario");
        $oguia->nidr = $request->get("idRemitente");
        $oguia->nidd = $request->get("idDestinatario");
        $oguia->nidv1 = $request->get("idVehiculo");;
        $oguia->nidv2 = 0;
        $oguia->cdetalle = $request->get("txtReferencia");
        $oguia->cubigeo1 = $request->get("txtubigeor");
        $oguia->cubigeo2 = $request->get("txtubigeod");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->brevete = $request->get("txtBrevete");
        $detalle = json_decode($request->get("detalle"));
        // var_dump(json_decode($detalle));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $oguia->grabar($detalle);
        if ($rpta['estado'] == 1) {
            $datosguia = array(
                "empresa" => session()->get('gene_empresa'),
                "rucempresa" => session()->get('gene_nruc'),
                "direccionempresa" => session()->get("gene_ptop"),
                "numero" => substr($rpta['ndoc'], 0, 4) . '-' . substr($rpta['ndoc'], -8, 8),
                "serie" => substr($rpta['ndoc'], 0, 4),
                "ndoc" => $rpta['ndoc'],
                "dfecha" => Date("d/m/Y"),
                "fechat" => Date("d/m/Y"),
                "fecha" => Date("d/m/Y"),
                "tdoc" => "31",
                "rucremitente" => $request->get("txtrucremitente"),
                "rucdestinatario" => $request->get("txtrucDestinatario"),
                "remitente" => $request->get("txtNombreRemitente"),
                "destinatario" => $request->get("txtNombreDestinatario"),
                "ptopartida" => $request->get("txtDireccionRemitente"),
                "ptollegada" => $request->get("txtDireccionDestinatario"),
                "placa1" => $request->get("txtPlaca1"),
                "placa" => $request->get("txtPlaca"),
                "conductor" => $request->get("txtChoferVehiculo"),
                "brevete" => $request->get("txtBrevete"),
                "marca" => $request->get("txtMarca"),
                "referencia" => $request->get("txtReferencia")
            );
            // $this->imprimirdirecto($datosguia, $detalle);
        }
        $_SESSION['datosguia'] = $datosguia;
        $_SESSION['detalle'] = $detalle;
        return json_encode($rpta);
    }
    function indexListar()
    {
        return view('guias/indexListar', ['titulo' => 'Listar Guias']);
    }
    function indexListarxenviar()
    {
        return view('guias/indexListarxenviar', ['titulo' => 'Guias por Informar']);
    }
    function listarGuias(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $dplaca = $request->get('dplaca');
        $oguia = new GuiaTransportista();
        $lista = $oguia->listar($dfi, $dff, $dplaca);
        return \view('guias/re_guias', ['listado' => $lista]);
    }
    function listarGuiasxenviar(Request $request)
    {
        $dplaca = $request->get('dplaca');
        $oguia = new GuiaTransportista();
        $lista = $oguia->listarxenviar($dplaca);
        return \view('guias/re_guiasxenviar', ['listado' => $lista]);
    }
    function imprimir(Request $request)
    {
        $oguia = new GuiaTransportista();
        $lista = $oguia->consultarguia($request->get("nidauto"));
        $oimp = new Imprimir();
        $i = 1;
        $tpeso = 0;
        $rutapdf = 'descargas/Guia' . '.pdf';
        foreach ($lista as $fila) {
            $oimp->items[] = array(
                'item' => $i,
                'unid' => $fila['unid'],
                'descri' => $fila['descri'],
                'cant' => $fila['cant'],
                'peso' => $fila['peso'],
                'subtotal' => round($fila['cant'] * $fila['peso'], 2)
            );
            $tpeso += $fila['cant'] * $fila['peso'];
            if ($i == 1) {
                $oimp->empresa = $_SESSION['gene_empresa'];
                $oimp->rucempresa = $_SESSION['gene_nruc'];
                $oimp->direccionempresa = $fila['ptop'];
                $oimp->tipocomprobante = 'GUIA DE REMISION ELECTRONICA TRANSPORTISTA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['fech']));
                $oimp->fechat = date("d/m/Y", strtotime($fila['fecht']));
                $oimp->fecha = $dfecha;
                $oimp->tdoc = '31';
                $oimp->rucremitente = $fila['rucremitente'];
                $oimp->rucdestinatario = $fila['rucdestinatario'];
                $oimp->remitente = utf8_decode($fila['remitente']);
                $oimp->destinatario = utf8_decode($fila['destinatario']);
                $oimp->ptopartida = $this->em($fila['ptopartida']);
                $oimp->ptollegada = $this->em($fila['ptollegada']);
                $oimp->placa = $fila['placa'];
                $oimp->placa1 = $fila['placa1'];
                $oimp->conductor = ($fila['chofer']);
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->constancia = $fila['constancia'];
                $oimp->referencia = $fila['guia_deta'];
                $rutapdf = 'descargas/' . $fila['nombrexml'] . 'pdf';
            }
            $i++;
        }
        $oimp->totalpeso = $tpeso;
        $oimp->generapdfguiatransportista($rutapdf);
    }
    function em($word)
    {
        $word = strtolower($word);
        $word = str_replace("@", "%40", $word);
        $word = str_replace("`", "%60", $word);
        $word = str_replace("¢", "%A2", $word);
        $word = str_replace("£", "%A3", $word);
        $word = str_replace("¥", "%A5", $word);
        $word = str_replace("|", "%A6", $word);
        $word = str_replace("«", "%AB", $word);
        $word = str_replace("¬", "%AC", $word);
        $word = str_replace("¯", "%AD", $word);
        $word = str_replace("º", "%B0", $word);
        $word = str_replace("±", "%B1", $word);
        $word = str_replace("ª", "%B2", $word);
        $word = str_replace("µ", "%B5", $word);
        $word = str_replace("»", "%BB", $word);
        $word = str_replace("¼", "%BC", $word);
        $word = str_replace("½", "%BD", $word);
        $word = str_replace("¿", "%BF", $word);
        $word = str_replace("À", "A", $word);
        $word = str_replace("Á", "A", $word);
        $word = str_replace("Â", "A", $word);
        $word = str_replace("Ã", "A", $word);
        $word = str_replace("Ä", "A", $word);
        $word = str_replace("Å", "A", $word);
        $word = str_replace("Æ", "AE", $word);
        $word = str_replace("Ç", "C", $word);
        $word = str_replace("È", "E", $word);
        $word = str_replace("É", "E", $word);
        $word = str_replace("Ê", "E", $word);
        $word = str_replace("Ë", "E", $word);
        $word = str_replace("Ì", "I", $word);
        $word = str_replace("Í", "I", $word);
        $word = str_replace("Î", "I", $word);
        $word = str_replace("Ï", "I", $word);
        $word = str_replace("Ð", "D", $word);
        $word = str_replace("Ñ", "N", $word);
        $word = str_replace("Ò", "O", $word);
        $word = str_replace("Ó", "O", $word);
        $word = str_replace("Ô", "O", $word);
        $word = str_replace("Õ", "O", $word);
        $word = str_replace("Ö", "O", $word);
        $word = str_replace("Ø", "O", $word);
        $word = str_replace("Ù", "U", $word);
        $word = str_replace("Ú", "U", $word);
        $word = str_replace("Û", "U", $word);
        $word = str_replace("Ü", "U", $word);
        $word = str_replace("Ý", "Y", $word);
        $word = str_replace("Þ", "P", $word);
        $word = str_replace("ß", "B", $word);
        $word = str_replace("à", "a", $word);
        $word = str_replace("á", "a", $word);
        $word = str_replace("â", "a", $word);
        $word = str_replace("ã", "a", $word);
        $word = str_replace("ä", "a", $word);
        $word = str_replace("å", "a", $word);
        $word = str_replace("æ", "ae", $word);
        $word = str_replace("ç", "c", $word);
        $word = str_replace("è", "e", $word);
        $word = str_replace("é", "e", $word);
        $word = str_replace("ê", "e", $word);
        $word = str_replace("ë", "e", $word);
        $word = str_replace("ì", "i", $word);
        $word = str_replace("í", "i", $word);
        $word = str_replace("î", "i", $word);
        $word = str_replace("ï", "i", $word);
        $word = str_replace("ð", "o", $word);
        $word = str_replace("ñ", "n", $word);
        $word = str_replace("ò", "o", $word);
        $word = str_replace("ó", "o", $word);
        $word = str_replace("ô", "o", $word);
        $word = str_replace("õ", "o", $word);
        $word = str_replace("ö", "o", $word);
        $word = str_replace("÷", "%F7", $word);
        $word = str_replace("ø", "%F8", $word);
        $word = str_replace("ù", "u", $word);
        $word = str_replace("ú", "u", $word);
        $word = str_replace("û", "u", $word);
        $word = str_replace("ü", "u", $word);
        $word = str_replace("ý", "y", $word);
        $word = str_replace("þ", "%FE", $word);
        $word = str_replace("ÿ", "y", $word);
        return strtoupper($word);
    }
    function enviarsunatguiatr(Request $request)
    {
        $oguia = new GuiaTransportista();
        $lista = $oguia->consultarguia($request->get("nidauto"));
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
                    "ptopartida" => $this->em($fila['ptopartida']),
                    "ptollegada" => $this->em($fila['ptollegada']),
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
                    "ndnichofer" => $fila['vehi_ndni'],
                    "gene_rmtc" => $_SESSION['gene_gene_rmtc']
                );
            }
            $i++;
        }

        $guia['tpeso'] = $tpeso;
        $guia['items'] = $items;
        // echo json_encode($guia);
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
    function enviarsunatguiar(Request $request)
    {
        $datosenvio = json_encode(array("ruc" => $request->get("ruc"), "idauto" => $request->get("nidauto"), "motivo" => $request->get("motivo")));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/app88/envioguia.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $datosenvio,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
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
                'unid' => "",
                'descri' => $item['descripcion'],
                'cant' => $item['cantidad'],
                'peso' => $item['peso'],
                'subtotal' => round($item['cantidad'] * $item['peso'], 2)
            );
            $tpeso += $item['cantidad'] * $item['peso'];
            if ($i == 1) {
                $oimp->empresa = $fila['empresa'];
                $oimp->rucempresa = $fila['rucempresa'];
                $oimp->direccionempresa = session()->get("gene_ptop");
                $oimp->tipocomprobante = 'GUIA DE REMISION ELECTRONICA TRANSPORTISTA';
                $oimp->numero = substr($fila['ndoc'], 0, 4) . '-' . substr($fila['ndoc'], -8, 8);
                $oimp->serie = substr($fila['ndoc'], 0, 4);
                $oimp->ndoc = $fila['ndoc'];
                $dfecha = date("d/m/Y", strtotime($fila['fecha']));
                $oimp->fechat = $fila['fecha'];
                $oimp->fecha = $fila['fecha'];
                $oimp->tdoc = '31';
                $oimp->rucremitente = $fila['rucremitente'];
                $oimp->rucdestinatario = $fila['rucdestinatario'];
                // echo "p" . $fila['remitente'];
                $oimp->remitente = $fila['remitente'];
                $oimp->destinatario = $fila['destinatario'];
                $oimp->ptopartida = $fila['ptopartida'];
                $oimp->ptollegada = $fila['ptollegada'];
                $oimp->placa = $fila['placa'];
                $oimp->conductor = $fila["conductor"];
                $oimp->brevete = $fila['brevete'];
                $oimp->marca = $fila['marca'];
                $oimp->referencia = $fila['referencia'];
                $oimp->constancia = "Constancia";
                $rutapdf = 'descargas/' . $fila['ndoc'] . '.pdf';
            }
            $i++;
        }
        #I ES PARA GUARDAR EN EL SERVIDOR F
        # PARA DESCARGAR EL ARCHIVO ES D
        $oimp->totalpeso = $tpeso;
        // $estilo=$_SESSION['estilo'];
        $oimp->generapdfguiatransportista($rutapdf, 'I');
        $_SESSION['datosguia'] = [];
        $_SESSION['detalle'] = [];
        // header('Location: C:\laragon\www\app19\public\descargas\probando.pdf');
    }
    function consultarGuiaPorId($idGuia)
    {
        // session()->set('arrayEliminados',[]);
        $id = $idGuia;
        $oguia = new GuiaTransportista();
        $lista = $oguia->consultarguia($id);
        $i = 0;
        foreach ($lista as $item) {
            $_SESSION['ndoc'] = $item['ndoc'];
            if ($i == 0) {
                $remitente = array(
                    'idauto' => $item['idauto'],
                    'fechaEmision' => $item['fech'],
                    'fechaTraslado' => $item['fecht'],
                    'razo' => $item['dest'],
                    'idRemitente' => $item['idprov'],
                    'remitenteDireccion' => $item['ptopartida'],
                    'txtReferencia' => $item['guia_deta'],
                    'ndoc' => $item['ndoc'],
                    'ubigeoRemitente' => $item['guia_ubi1']
                );
                $destinatario = array(
                    'idDestinatario' => $item['idclie'],
                    'nombre' => $item['remi'],
                    'destinatarioDireccion' => $item['ptollegada'],
                    'ubigDestinatario' => $item['guia_ubi2']
                );
                $vehiculo = array(
                    'txtChoferVehiculo' => $item['guia_cond'],
                    'txtIdVehiculo' => $item['vehi_idve'],
                    'txtBrevete' => $item['guia_brev'],
                    'txtPlaca' => $item['placa'],
                    'txtPlaca1' => $item['placa1']
                );
                $aceptado = $item['guia_mens'];
                // $nroventa = $item['ndoc'];
            }
            $detalleg[] = array(
                // 'coda' => $item["Coda"],
                'nreg' => $item["nreg"],
                'descri' => $item["descri"],
                'cant' => $item['cant'],
                'peso' => $item['peso'],
                'activo' => 'A'
            );
        }
        $pesot = 0;
        foreach ($detalleg as $d) {
            $total = $d['cant'] * $d['peso']; //PRIMER VALOR
            $pesot = $pesot + $total;
        }

        session()->set("estadoguia", $aceptado);
        session()->set("remitente", $remitente);
        session()->set("destinatario", $destinatario);
        session()->set("vehiculo", $vehiculo);

        $cvista = \retornavista('guias', 'index');

        $titulo = 'Actualizar Guía';

        return view($cvista, [
            'titulo' => $titulo,
            'remitente' => $remitente,
            'destinatario' => $destinatario,
            'vehiculo' => $vehiculo,
            'detalleg' => $detalleg,
            'pesot' => $pesot
        ]);
    }
    function actualizar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtubigeor")->message('El Ubigeo del Remitente es Obligatorio');
        $validar->rule("required", "txtubigeod")->message('El Ubigeo del Destinatario es Obligatorio');
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            // var_dump($data);
            return response()->json($data, 422);
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
        $oguia = new GuiaTransportista();
        $oguia->idauto = $request->get("txtIdauto");
        $oguia->dfecha =   Date("Y-m-d", strtotime($request->get("txtFechaEmision")));
        $oguia->dfechat =  Date("Y-m-d", strtotime($request->get("txtFechaTraslado")));
        $oguia->tdoc = '31';
        $oguia->cptop = $request->get("txtDireccionRemitente");
        $oguia->cptoll = $request->get("txtDireccionDestinatario");
        $oguia->nidr =  $request->get("idRemitente");
        $oguia->nidd =  $request->get("idDestinatario");
        $oguia->nidv1 = $request->get("idVehiculo");
        $oguia->nidv2 = 0;
        $oguia->cdetalle = $request->get("txtReferencia");
        $oguia->cubigeo1 = $request->get("txtubigeor");
        $oguia->cubigeo2 = $request->get("txtubigeod");
        $oguia->conductor = $request->get("txtChoferVehiculo");
        $oguia->brevete = $request->get("txtBrevete");
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        $arrayEliminados = $request->get("arrayEliminados");
        if ($arrayEliminados == 'null') {
            $arrayEliminados = [];
        } else {
            $arrayEliminados = explode(",", $arrayEliminados);
        }
        $rpta = $oguia->actualizar($detalle, $arrayEliminados);

        $datosguia = array(
            "empresa" => session()->get('gene_empresa'),
            "rucempresa" => session()->get('gene_nruc'),
            "direccionempresa" => session()->get("gene_ptop"),
            "numero" => "-",
            "serie" => "+",
            "ndoc" => "-",
            "dfecha" => Date("d/m/Y", strtotime($request->get("txtFechaEmision"))),
            "fechat" => Date("d/m/Y", strtotime($request->get("txtFechaTraslado"))),
            "fecha" => Date("d/m/Y", strtotime($request->get("txtFechaEmision"))),
            "tdoc" => "31",
            "rucremitente" => "",
            "rucdestinatario" => "",
            "remitente" => $request->get("txtNombreRemitente"),
            "destinatario" => $request->get("txtNombreDestinatario"),
            "ptopartida" => $request->get("txtDireccionRemitente"),
            "ptollegada" => $request->get("txtDireccionDestinatario"),
            "placa1" => $request->get("txtPlaca1"),
            "placa" => $request->get("txtPlaca"),
            "conductor" => $request->get("txtChoferVehiculo"),
            "brevete" => $request->get("txtBrevete"),
            "referencia" => $request->get("txtReferencia"),
            "marca" => "",
        );

        $_SESSION['datosguia'] = $datosguia;
        $_SESSION['detalle'] = $detalle;
        return json_encode($rpta);
    }
    function listarGuiasTrparacanje()
    {
        $oguia = new GuiaTransportista();
        $lista = $oguia->listarparacanje();
        return \view('guias/re_guiasmodal', ['listado' => $lista]);
    }
    function actualizarEstadoGuiaTr(Request $request)
    {
        $id = $request->get("nidauto");
        $oguia = new GuiaTransportista();
        $rpta = $oguia->actualizarEstadoGuiaTr($id);
        return json_encode($rpta);
    }
}
