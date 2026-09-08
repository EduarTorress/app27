<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Unidades;
use App\Models\Vehiculos;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class VehiculosController extends Controller
{
    private $unidad;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->unidad = new Vehiculos();
    }
    function index()
    {
        $titulo = 'Vehículos';
        return view('admin/unidadesvehiculares/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $lista = $this->unidad->listar($request->get('cbuscar'));
        return view('admin/unidadesvehiculares/listaunidades', ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Vehiculo';
        return view('admin/unidadesvehiculares/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Vehículo';
        $grupo = $this->unidad->buscarid($id);
        return view('admin/unidadesvehiculares/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $grupo, 'id' => $id]);
    }
    function store(Request $request)
    {
        // continuamos
        try {
            // INSERT INTO (vehi_plac, vehi_pla2, vehi_cons, vehi_conf,_vehi_cond,vehi_ndni,vehi_brev) VALUES ()
            $unidad = new Vehiculos();
            $unidad->chofer = $request->get('txtChofer');
            $unidad->txtPlaca01 = $request->get('txtPlaca01');
            $unidad->txtPlaca02 = $request->get('txtPlaca02');
            $unidad->txtBrevete = $request->get('txtBrevete');
            $unidad->txtDni = $request->get('txtDNI');
            $unidad->txtTipoBrevete = $request->get('txtTipoBrevete');
            $unidad->txtConstancia = $request->get('txtConstancia');
            if ($unidad->save()) {
                return response()->json(['message' => 'Unidad Vehícular registrada correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar unidad vehícular '], 400); // Created
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
    public function update($id, Request $request)
    {
        // if (!$this->unidad->buscarnombre($request->get('txtnombre'), $id)) {
        //     $data = [
        //         'message' => 'Errores de validacion',
        //         'errors' => array('txtnombre' => array('El nombre de Grupo ya está Registrado'))
        //     ];
        //     return response()->json($data, 422);
        // }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtChofer')->message('El Nombre del Chofer es Obligatorio');
        $validator->labels([
            'nombre' => 'txtnombre'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $unidad = new Vehiculos();
            $unidad->chofer = $request->get('txtChofer');
            $unidad->txtPlaca01 = $request->get('txtPlaca01');
            $unidad->txtPlaca02 = $request->get('txtPlaca02');
            $unidad->txtBrevete = $request->get('txtBrevete');
            $unidad->txtDni = $request->get('txtDNI');
            $unidad->txtTipoBrevete = $request->get('txtTipoBrevete');
            $unidad->txtConstancia = $request->get('txtConstancia');
            // $idUnidades = $unidad->buscarid($id);
            if ($unidad->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Error al actualizar ' . $error], 500);
        }
    }
    public function darBaja($id, Request $request)
    {
        try {
            $unidades = new Vehiculos();
            // $unidades->chofer = $request->get('txtnombre');
            if ($unidades->darBaja($id)) {
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al eliminar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Error al eliminar' . $error], 500);
        }
    }
    function listarplacas()
    {
        $ov = new Vehiculos();
        $lista = $ov->listarplacas();
        return view('components/modallistaplacas', ["lista" => $lista]);
    }
    function listar()
    {
        $ov = new Vehiculos();
        $lista = $ov->listarVehiculos();
        return view('admin/vehiculo/re_vehiculos', ["lista" => $lista]);
    }
    function seleccionadoVehiculo(Request $request)
    {
        $vehiculo = array();
        $vehiculo = array(
            'txtChoferVehiculo' => $request->get("txtChoferVehiculo"),
            'txtIdVehiculo' => $request->get('txtIdVehiculo'),
            'txtPlaca' => $request->get("txtPlaca"),
            'txtserie' => $request->get("txtserie"),
            'txtBrevete' => $request->get("txtBrevete"),
            'txtPlaca1' => $request->get("txtPlaca1")
        );
        \session()->set('vehiculo', $vehiculo);
        return response()->json([
            'message' => 'Vehiculo seleccionado correctamente'
        ], 200);
    }
}
