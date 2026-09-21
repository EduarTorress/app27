<?php

namespace App\Controllers;

use App\Models\Caja;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;

class CajaController extends Controller
{
    function index()
    {
        return \view('liquidaciones/index', ['titulo' => 'Liquidar Caja']);
    }
    function buscar(Request $request)
    {
        $fech = $request->get('txtfech');
        $nidusua = $request->get('cmbusuarios');
        $codt = $request->get('cmbAlmacen');
        $caja = new Caja();
        if (!empty($_SESSION['config']['cajaxtienda'])) {
            $data = $caja->buscarmulti($fech, $nidusua, $codt);
        } else {
            $data = $caja->buscar($fech, $nidusua, $codt);
        }
        $lista = $data['lista'];
        $totalv = 0;
        $totalc = 0;
        $egresos = 0;
        $efectivo = 0;
        $ingresos = 0;
        foreach ($lista as $l) {
            if (floatval($l['egresos']) >= 0) {
                $egresos = $egresos + floatval($l['egresos']);
            }
            if ((floatval($l['egresos']) <= 0) && ($l['tipo'] == 'I')) {
                if ($l['idauto'] != '0') {
                    $totalv = $totalv + floatval($l['nimpo']);
                }
                $totalc = $totalc + floatval($l['nimpo']);
                if ($l['forma'] == 'E') {
                    $efectivo = $efectivo + floatval($l['nimpo']);
                }
            }
        }
        $totalv = number_format($totalv, 2, ",", "");
        $saldo = $caja->verSaldo('2021-01-01', $fech, $nidusua);
        return \view('liquidaciones/listamovimientos', [
            'lista' => $lista,
            'totalv' => $totalv,
            'efectivo' => $efectivo,
            'totalc' => $totalc,
            'egresos' => $egresos,
            'saldo' => $saldo['lista'][0],
            'ingresos' => $ingresos
        ]);
    }
    function generarticketcaja(Request $request)
    {
        $oimp = new Imprimir();
        $fecha = $request->get('fecha');
        $time = strtotime($fecha);
        $newformatfech = date('Y-m-d', $time);
        $oimp->fecha = ($newformatfech);
        $oimp->usuario = ($request->get('usuario'));
        $oimp->efectivo = Round(floatval($request->get('efectivo')), 2);
        $oimp->yape = ($request->get('yape'));
        $oimp->plin = ($request->get('plin'));
        $oimp->tarjeta = ($request->get('tarjeta'));
        $oimp->deposito = ($request->get('deposito'));
        $oimp->credito = ($request->get('credito'));
        $oimp->referencia = $request->get('txtreferencia');
        $oimp->sobrante = $request->get('sobrante');
        $oimp->egresos = $request->get('egresos');
        $efectivoconegreso = floatval($request->get('efectivo')) - floatval($request->get('egresos'));
        $oimp->total =  $efectivoconegreso + floatval($request->get('yape')) + floatval($request->get('plin')) + floatval($request->get('tarjeta')) + floatval($request->get('deposito')) + floatval($request->get('credito'));
        $rutapdf = 'ticketcaja.pdf';
        $oimp->generarticketcaja($rutapdf, 'I');
    }
    function indexIngresosEgresos()
    {
        return \view('liquidaciones/indexIngresosEgresos', ['titulo' => 'Registrar Ingresos - Egresos']);
    }
    function obtenernumerodocumento(Request $request)
    {
        $correlativo = SerieController::correlativo($_SESSION['nserie'], 'VV');
        if ($correlativo[0]['estado'] == 0) {
            $documento = '0';
            $idserie = 0;
        } else {
            $documento = $correlativo[0]['correlativo'];
            $idserie =  $correlativo[0]['idserie'];
        }
        return response()->json(['message' => $idserie, 'data' => $documento], 200);
    }
    function registrarIngresoEgreso(Request $request)
    {
        $caja = new Caja();
        $caja->dfecha = $request->get('dfecha');
        $caja->cndoc = $request->get('cndoc');
        $caja->cdeta = $request->get('cdeta');
        $caja->sdeudor = $request->get('sdeudor');
        $caja->sacreedor = $request->get('sacreedor');
        $caja->cmone = 'S';
        $caja->ndolar = session()->get("gene_dola");
        $caja->nidus = session()->get("usuario_id");
        $caja->cmbformapago = $request->get('cmbformapago');
        $caja->cargocajero = $request->get('cargocajero');
        $caja->nidt = $_SESSION['almacen'];
        $rpta = $caja->registramovimientoscaja($request->get('idserie'));
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
        }
    }
    function imprimirticketingresoyegreso(Request $request)
    {
        $caja = new Caja();
        $oimp = new Imprimir();
        $datoscaja = $caja->consultaringresoyegreso($request->get('documento'));
        // tipocomprobante
        // fecha
        // usuario
        // total
        // referencia
        // SELECT lcaj_fope AS fope,lcaj_fech AS fecha,IF(lcaj_acre>0,lcaj_acre,lcaj_deud) AS importe,lcaj_deta AS refe,
        //         IF(lcaj_deud>0,'Ingresos','Egresos') AS tipo,lcaj_dcto AS ndoc  FROM
        //         fe_lcaja AS l
        //         INNER JOIN fe_usua AS u ON u.idusua=l.lcaj_idus
        //         WHERE lcaj_dcto=:ndoc AND lcaj_acti='A' AND lcaj_form='E'
        foreach ($datoscaja['lista'] as $fila) {
            $oimp->empresa = $_SESSION['gene_empresa'];
            $oimp->rucempresa = $_SESSION['gene_nruc'];
            $oimp->direccionempresa = $_SESSION['gene_ptop'];
            $oimp->ndoc = $fila['ndoc'];
            $oimp->total = $fila['importe'];
            $oimp->tipocomprobante = $fila['tipo'];
            $oimp->fecha = $fila['fope'];
            $oimp->usuario = $_SESSION['usuario'];
            $oimp->referencia = $fila['refe'];
        }
        $oimp->generaticketingresoyegreso($request->get('rutapdf'));
    }
    public function registrarTransferencia(Request $request)
    {
        $caja = new Caja();

        $caja->dfecha = $request->get('txtfechat');
        $caja->cndoc = $request->get('txtnumerodocumentot');
        $caja->cdeta = $request->get('txtdetallet');
        $caja->sdeudor = $request->get('txtimportet');
        $caja->sacreedor = $request->get('txtsaldot');
        $caja->cmone = 'S';
        $caja->ndolar = session()->get("gene_dola");
        $caja->nidus = session()->get("usuario_id");
        $caja->cmbformapago = $request->get('cmbformapagot');
        $caja->nidt = $_SESSION['almacen'];
        $rpta = $caja->registramovimientoscaja($request->get('idserie'));
        if ($rpta['estado'] == '1') {
            return response()->json([
                'message' => $rpta['mensaje'],
                'data' => $rpta['data']
            ], 200);
        } else {
            return response()->json([
                'message' => $rpta['mensaje'],
                'data' => $rpta['data']
            ], 422);
        }
    }
    public function registrarretiroparabancos(Request $request)
    {
        $caja = new Caja();
        $caja->dfecha = $request->get('txtfecharb');
        $caja->cndoc = $request->get('txtnumerodocumentorb');
        $caja->cdeta = $request->get('txtdetallede');
        $caja->sdeudor = $request->get('txtimporterb');
        $caja->sacreedor = $request->get('txtimportede');
        $caja->cmone = 'S';
        $caja->ndolar = session()->get("gene_dola");
        $caja->nidus = session()->get("usuario_id");
        $caja->cmbformapago = $request->get('cmbformapagode');
        $caja->nidt = $_SESSION['almacen'];
        $rpta = $caja->registramovimientoscaja($request->get('idserie'));
        if ($rpta['estado'] == '1') {
            return response()->json([
                'message' => $rpta['mensaje'],
                'data' => $rpta['data']
            ], 200);
        } else {
            return response()->json([
                'message' => $rpta['mensaje'],
                'data' => $rpta['data']
            ], 422);
        }
    }
    function indexcomparabancos()
    {
        return \view('liquidaciones/indexcomparabancos', ['titulo' => 'Comparar Bancos']);
    }
    function listarcomparabancos(Request $request)
    {
        $fech = $request->get('txtfech');
        $nidusua = $request->get('cmbusuarios');
        $codt = $request->get('cmbAlmacen');
        $caja = new Caja();
        $data = $caja->listarcomparabancos($fech, $nidusua, $codt);
        return \view('liquidaciones/listarcomparabancos', [
            'lista' => $data['lista']
        ]);
    }
    // function registrarTransferencia(Request $request) {}
    // function indexregistrocajaybancos()
    // {
    //     $cuentasbanco = new NumerosCuenta();
    //     $listacuentasbanco = $cuentasbanco->listar('%%');
    //     $planescontables = new PlanesContables();
    //     $listarplanescontables = $planescontables->listar('10');
    //     $caja = new Caja();
    //     $rptampagos = $caja->listarmpagos();
    //     return \view('cajaybancos/index', [
    //         'titulo' => 'Registrar Datos a Libro Caja y Bancos',
    //         'listacuentasbanco' => $listacuentasbanco,
    //         'listarplanescontables' => $listarplanescontables,
    //         'listampagos' => $rptampagos['lista']
    //     ]);
    // }
    // function listaringresos()
    // {
    //     $caja = new Caja();
    //     $data = $caja->listaringresos();
    //     return \view('components/listaingresosxcancelar', [
    //         'ingresosxcancelar' => $data['lista']
    //     ]);
    // }
    // function listaregresos()
    // {
    //     $caja = new Caja();
    //     $data = $caja->listaregresos();
    //     return \view('components/listaegresosxcancelar', [
    //         'egresosxcancelar' => $data['lista']
    //     ]);
    // }
    // function registraringresolibro(Request $request)
    // {
    //     // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
    //     // data.append("cmbctas", $("#cmbctas").val());
    //     // data.append("cmbintereses", $("#cmbintereses").val());
    //     // data.append("txtintereses", $("#txtintereses").val());
    //     // data.append("cmbcomisiones", $("#cmbcomisiones").val());
    //     // data.append("txtcomisiones", $("#txtcomisiones").val());
    //     // data.append("txtnrooperacion", $("#txtnrooperacion").val());
    //     // data.append("txttipocambio", $("#txttipocambio").val());
    //     // data.append("txtinteres", $("#txtinteres").val());
    //     // data.append("txtcomision", $("#txtcomision").val());
    //     // data.append("txttotal", $("#txttotal").val());
    //     // data.append("cuentasxpagar", JSON.stringify(detalle));
    //     $cabecera = [
    //         'cmbmediopago' => $request->get('cmbmediopago'),
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbnrocuentas' => $request->get('cmbnrocuentas'),
    //         'cmbctas' => $request->get('cmbctas'),
    //         'cmbintereses' => $request->get('cmbintereses'),
    //         'txtintereses' => $request->get('txtintereses'),
    //         'cmbcomisiones' => $request->get('cmbcomisiones'),
    //         'txtcomisiones' => $request->get('txtcomisiones'),
    //         'txtnrooperacion' => $request->get('txtnrooperacion'),
    //         'txttipocambio' => $request->get('txttipocambio'),
    //         'txtinteres' => $request->get('txtinteres'),
    //         'txtcomision' => $request->get('txtcomision'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia')
    //     ];
    //     $detalle = json_decode($request->get("cuentasxpagar"));
    //     $detalle = json_decode(json_encode($detalle), true);
    //     $caja = new Caja();
    //     $rptacaja = $caja->grabaringresolibro($cabecera, $detalle);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function registraregresolibro(Request $request)
    // {
    //     // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
    //     // data.append("cmbctas", $("#cmbctas").val());
    //     // data.append("cmbintereses", $("#cmbintereses").val());
    //     // data.append("txtintereses", $("#txtintereses").val());
    //     // data.append("cmbcomisiones", $("#cmbcomisiones").val());
    //     // data.append("txtcomisiones", $("#txtcomisiones").val());
    //     // data.append("txtnrooperacion", $("#txtnrooperacion").val());
    //     // data.append("txttipocambio", $("#txttipocambio").val());
    //     // data.append("txtinteres", $("#txtinteres").val());
    //     // data.append("txtcomision", $("#txtcomision").val());
    //     // data.append("txttotal", $("#txttotal").val());
    //     // data.append("cuentasxpagar", JSON.stringify(detalle));
    //     $cabecera = [
    //         'cmbmediopago' => $request->get('cmbmediopago'),
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbnrocuentas' => $request->get('cmbnrocuentas'),
    //         'cmbctas' => $request->get('cmbctas'),
    //         'cmbintereses' => $request->get('cmbintereses'),
    //         'txtintereses' => $request->get('txtintereses'),
    //         'cmbcomisiones' => $request->get('cmbcomisiones'),
    //         'txtcomisiones' => $request->get('txtcomisiones'),
    //         'txtnrooperacion' => $request->get('txtnrooperacion'),
    //         'txttipocambio' => $request->get('txttipocambio'),
    //         'txtinteres' => $request->get('txtinteres'),
    //         'txtcomision' => $request->get('txtcomision'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia')
    //     ];
    //     $detalle = json_decode($request->get("cuentasxpagar"));
    //     $detalle = json_decode(json_encode($detalle), true);
    //     $caja = new Caja();
    //     $rptacaja = $caja->grabaregresolibro($cabecera, $detalle);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function indexregistrocajayefectivo()
    // {
    //     return \view('cajayefectivo/index', [
    //         'titulo' => 'Registrar Datos a Libro Caja Efectivo'
    //     ]);
    // }
    // function registraringresolibroefectivo(Request $request)
    // {
    //     $cabecera = [
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbcuentas' => $request->get('cmbcuentas'),
    //         'txtcuentas' => $request->get('txtcuentas'),
    //         'cmbmoneda' => $request->get('cmbmoneda'),
    //         'txtvalor' => $request->get('txtvalor'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia'),
    //         'txttipocambio' => $request->get('txttipocambio')
    //     ];
    //     $caja = new Caja();
    //     $rptacaja = $caja->registraringresolibroefectivo($cabecera);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function registraregresolibroefectivo(Request $request)
    // {
    //     $cabecera = [
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbcuentas' => $request->get('cmbcuentas'),
    //         'txtcuentas' => $request->get('txtcuentas'),
    //         'cmbmoneda' => $request->get('cmbmoneda'),
    //         'txtvalor' => $request->get('txtvalor'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia'),
    //         'txttipocambio' => $request->get('txttipocambio')
    //     ];
    //     $caja = new Caja();
    //     $rptacaja = $caja->registraregresolibroefectivo($cabecera);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function cambiarfecha(Request $request)
    // {
    //     $usua = $request->get("txtUsuario");
    //     $pass = $request->get("txtPassword");
    //     $ousuario = new Usuario();
    //     $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
    //     if (!empty($valor[0]['idusua'])) {
    //         return response()->json(['message' => 'ok', 'estado' => '1'], 200);
    //     } else {
    //         return response()->json(['message' => 'Las credenciales no son correctas', 'estado' => '0'], 422);
    //     }
    // }
    // function indexlistarcajabanco()
    // {
    //     $nc = new NumerosCuenta();
    //     $listabancos = $nc->listar('%%');
    //     return \view('cajaybancos/informes/indexlistar', [
    //         'titulo' => 'Reporte de Libro Caja y Bancos',
    //         'listabancos' => $listabancos
    //     ]);
    // }
    // function listarinformescajaybancos(Request $request)
    // {
    //     $txtfechai = $request->get('txtfechai');
    //     $txtfechaf = $request->get('txtfechaf');
    //     $banco = $request->get('cmbbancos');
    //     $caja = new Caja();
    //     $data = $caja->listarinformescajaybancos($txtfechai, $txtfechaf, $banco);
    //     $listarsaldoinicial = $caja->listarsaldoinicial($txtfechai, $banco);
    //     return \view('cajaybancos/informes/listarinformescajaybancos', [
    //         'listado' => $data['lista'],
    //         'listarsaldoinicial' => $listarsaldoinicial['lista']
    //     ]);
    // }
}
