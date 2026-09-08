<?php

namespace App\Controllers;

use App\Models\Caja;
use App\Models\NumerosCuenta;
use App\Models\PagosPlanilla;
use App\Models\PlanesContables;
use App\Models\Usuario;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;

class PagoPlanillaController extends Controller
{
    function indexpagos()
    {
        return \view('planillas/indexpagos', ['titulo' => 'Registrar Pagos']);
    }
    function buscarsaldoxusuario(Request $request)
    {
        $pp = new PagosPlanilla();
        $mes = $request->get('cmbmes');
        $ano = $request->get('cmbano');
        $idusua = $request->get('cmbidusua');
        $rpta = $pp->buscarsaldoxusuario($mes, $ano, $idusua);
        if (count($rpta['data']) < 1 || $rpta['data'][0]['sueldo'] == 0) {
            $usuario = new Usuario();
            $sueldo = $usuario->consultarsueldoxusuario($idusua);
            $sueldo = floatval($sueldo[0]['sueldo']);
            if (floatval($sueldo) == 0) {
                $rpta = [
                    'mensaje' => 'No se puede registrar un pago porque el trabajador no tiene sueldo.',
                    'data' => [
                        [
                            [
                                'pendiente' => 0,
                                'idusua' => $idusua,
                                'sueldo' => 0
                            ]
                        ]
                    ]
                ];
                return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
            } else {
                $rpta = [
                    'mensaje' => 'Todo ok',
                    'data' => [
                        [
                            'pendiente' => $sueldo,
                            'idusua' => $idusua,
                            'sueldo' => $sueldo
                        ]
                    ]
                ];
                $pp->sueldo = $sueldo;
                $pp->acta = 0;
                $pp->idemp = $idusua;
                $pp->fech = $request->get('cmbano') . $request->get('cmbmes') . date('d');
                $pp->idusua = $_SESSION['usuario_id'];
                $pp->fusua = date('Y-m-d h:m:s');
                $pp->referencia = 'SUELDO INICIAL DEL MES';
                $registro = $pp->registrarpago();
                if ($registro['estado'] == '1') {
                    return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
                } else {
                    return response()->json(['message' => 'Ocurrio un error', 'data' => $rpta['data']], 422);
                }
            }
        } else {
            if ($rpta['estado'] == '1') {
                return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
            } else {
                return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
            }
        }
    }
    function registrarpago(Request $request)
    {
        // data.append("txtsueldo", $("#txtsueldo").val());
        // data.append("txtpagado", $("#txtpagado").val());
        // data.append("txtpendiente", $("#txtpendiente").val());
        // data.append("txtapagar", $("#txtapagar").val());
        // data.append("txtdetalle", $("#txtdetalle").val());
        // data.append("cmbusuarios", $("#cmbusuarios").val());
        // data.append("cmbmes", $("#cmbmes").val());
        // data.append("cmbano", $("#cmbano").val());
        $pagosplanilla = new PagosPlanilla();
        $pagosplanilla->sueldo = 0;
        $pagosplanilla->acta = $request->get('txtapagar');
        $pagosplanilla->idemp = $request->get('cmbusuarios');
        $pagosplanilla->fech = $request->get('cmbano') . $request->get('cmbmes') . date('d');
        $pagosplanilla->idusua = $_SESSION['usuario_id'];
        $pagosplanilla->fusua = date('Y-m-d h:m:s');
        $pagosplanilla->referencia = $request->get('txtdetalle');
        $rpta = $pagosplanilla->registrarpago();
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
        }
    }
    function indexreporte()
    {
        return \view('planillas/indexreporte', ['titulo' => 'Planilla por mes']);
    }
    function buscarreporte(Request $request)
    {
        $pp = new PagosPlanilla();
        $u = new Usuario();
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $datafinal = [];
        $datasaldo = $pp->buscarsaldo($mes, $ano);
        $usuarios = $u->buscarUsuarios('%%', 0, 0);
        foreach ($usuarios['lista']['items'] as $usuario) {
            $elementpago = [];
            $saldopendiente = 0;
            $saldopago = 0;
            $sueldo = 0;
            foreach ($datasaldo['data'] as $ds) {
                if (trim($ds['idusua']) == trim($usuario['idusua'])) {
                    $saldopendiente = $ds['pendiente'];
                    $saldopago = floatval($ds['sueldo']) -  floatval($ds['pendiente']);
                    $sueldo = $ds['sueldo'];
                }
            }
            $elementpago = [
                'idusua' => $usuario['idusua'],
                'nomb' => $usuario['nomb'],
                'sueldo' => $sueldo,
                'cargo' => $usuario['tipo'],
                'saldopendiente' => $saldopendiente,
                'saldopago' => $saldopago,
                'ano' => $ano,
                'mes' => $request->get('descripcionmes')
            ];
            array_push($datafinal, $elementpago);
        }
        return response()->json(['message' => 'Todo ok', 'listado' => $datafinal], 200);
    }
    function mostrarmodalpagosausuarios(Request $request)
    {
        $mes = $request->get('mes');
        $nombredelmes = $request->get('nombredelmes');
        $ano = $request->get('ano');
        $cmbidusua = $request->get('cmbidusua');
        $pp = new PagosPlanilla();
        $lista = $pp->consultarmovimientosxusuario($mes, $ano, $cmbidusua);
        return \view('planillas/mdpagosplanillas', ['titulo' => 'Todos los Pagos', 'listado' => $lista['data'], 'mes' => $nombredelmes]);
    }
    function eliminar($id)
    {
        $pp = new PagosPlanilla();
        $rpta = $pp->anularmvto($id);
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => 'Pago eliminado satisfactoriamente', 'estado' => '1'], 200);
        } else {
            return response()->json(['message' => 'Error al eliminar pago', 'estado' => '0'], 400);
        }
    }
    // function verificarsaldopendientexusuario(Request $request)
    // {
    //     $pp = new PagosPlanilla();
    //     $rpta = $pp->buscarsihaysaldopendientexusuario($request->get('idusua'));
    //     $estado = '0';
    //     if ($rpta['estado'] == '1') {
    //         foreach ($rpta['lista'] as $r) {
    //             if ($r['pendiente'] > 0) {
    //                 $estado = '1';
    //                 break;
    //             }
    //         }
    //     }
    //     return $estado;
    // }
}
