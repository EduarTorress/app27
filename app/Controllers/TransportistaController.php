<?php

namespace App\Controllers;

use App\Models\Transportista;
use Core\Http\Request;
use Core\Routing\Controller;
use App\Middlewares\AuthAdminMiddleware;

class TransportistaController extends Controller
{
    private $transportista;

    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->transportista = new Transportista();
    }
    function buscar(Request $request)
    {
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $lista = $this->transportista->BuscarTransportista($abuscar, $opt);
        return view('admin/transportista/tm_listatransp', ['lista' => $lista]);
    }
    function seleccionadoTranportista(Request $request)
    {
        $datos = $request->get('datos');
        $transportista = array();
        $transportista = array(
            'txtIdTransportista' => $datos['parametro1'],
            'txttransportista' => $datos['parametro2'],
            'txtruc' => $datos['parametro5'],
            'txtPlaca' => $datos['parametro4'],
            'txtmarca' => $datos['parametro9'],
            'txtPlaca1' => "",
            'txtChoferVehiculo' => $datos['parametro3'],
            'txtbrevete' => $datos['parametro7'],
            'txtregmtc' => $datos['parametro6'],
            'txttipot' => $datos['parametro8']
        );
        \session()->set('transportista', $transportista);
        return response()->json([
            'message' => 'Tranportista seleccionado correctamente.'
        ], 200);
    }
    function listarChoferes(Request $request)
    {
        $lista = $this->transportista->listarchoferes();
        return view('components/modallistachoferes', ['lista' => $lista]);
    }
    function index()
    {
        $titulo = 'Transportistas';
        return view('admin/transportista/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $cbuscar = $request->get('cbuscar');
        $lista = $this->transportista->BuscarTransportista($cbuscar, '0');
        return view('admin/transportista/listatransportistas', ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Transportista';
        return view('admin/transportista/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Transportista';
        $transportista = $this->transportista->buscarid($id);
        return view('admin/transportista/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $transportista, 'id' => $id]);
    }
    function store(Request $request)
    {
        try {
            $transportista = new Transportista();
            $transportista->txtrazon = $request->get('txtrazon');
            $transportista->txtruc = $request->get('txtruc');
            $transportista->txttransportista = $request->get('txttransportista');
            $transportista->txtplaca = $request->get('txtplaca');
            $transportista->txtplaca1 = $request->get('txtplaca1');
            $transportista->txtbrevete = $request->get('txtbrevete');
            $transportista->txtmarca = $request->get('txtmarca');
            $transportista->txtconstancia = $request->get('txtconstancia');
            $transportista->cmbtipotransporte = $request->get('cmbtipotransporte');
            if ($transportista->save()) {
                return response()->json(['message' => 'Transportista registrado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al registrar transportista'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $transportista = new Transportista();
            $transportista->txtrazon = $request->get('txtrazon');
            $transportista->txtruc = $request->get('txtruc');
            $transportista->txttransportista = $request->get('txttransportista');
            $transportista->txtplaca = $request->get('txtplaca');
            $transportista->txtplaca1 = $request->get('txtplaca1');
            $transportista->txtbrevete = $request->get('txtbrevete');
            $transportista->txtmarca = $request->get('txtmarca');
            $transportista->txtconstancia = $request->get('txtconstancia');
            $transportista->cmbtipotransporte = $request->get('cmbtipotransporte');
            $idTransportista = $transportista->buscarid($id);

            if ($transportista->update($idTransportista['idtra'])) {
                return response()->json(['message' => 'Transportista actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar transportista'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
}
