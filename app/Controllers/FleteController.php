<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Flete;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class FleteController extends Controller
{
    private $flete;
    function __construct()
    {
        $middlweare = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middlweare);
        $this->flete = new Flete();
    }
    function index()
    {
        return view('admin/fletes/index', ['titulo' => 'Fletes']);
    }
    function lista(Request $request)
    {
        $lista = $this->flete->listar($request->get('cbuscar'));
        return view('admin/fletes/listafletes', ['lista' => $lista]);
    }
    static function listarfletes($cbuscar)
    {
        $flete = new Flete();
        $lista = $flete->listar($cbuscar);
        return $lista;
    }
    function create()
    {
        $titulo = 'Registrar Flete';
        return view('admin/fletes/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Flete';
        $flete = $this->flete->buscarid($id);
        return view('admin/fletes/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $flete, 'id' => $id]);
    }
    function store(Request $request)
    {
        if (!$this->flete->buscarnombre($request->get('txtnombre'), 0)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El Flete ya se encuentra registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El nombre de Flete es Obligatorio');
        $validator->rule('lengthMax', 'txtnombre', 50)->message('El nombre tiene que ser máximo de 50 caracteres');
        $validator->rule('lengthMin', 'txtnombre', 5)->message('El nombre tiene que ser mínima de 5 caracteres');
        $validator->rule('Min', 'txtcosto', 0)->message('El Importe debe ser mayor o igual a 0');
        $validator->labels([
            'nombre' => 'txtnombre',
            'precio' => 'txtcosto'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        // continuamos
        try {
            $flete = new Flete();
            $flete->setNombre($request->get('txtnombre'));
            $flete->setPrecio($request->get('txtcosto'));
            if ($flete->save()) {
                return response()->json(['message' => 'Flete registrada correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar flete '], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error], 500);
        }
    }
    public function update($id, Request $request)
    {
        if (!$this->flete->buscarnombre($request->get('txtnombre'), $id)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El nombre de Flete ya está registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El Nombre de Flete es Obligatorio');
        $validator->rule('lengthMax', 'txtnombre', 50)->message('La Longitud Máxima es 50 caracteres');
        $validator->rule('lengthMin', 'txtnombre', 5)->message('La Longitud Mínima es 5 caracteres');
        $validator->rule('Min', 'txtcosto', 0)->message('El Importe debe ser mayor o igual a 0');
        $validator->labels([
            'nombre' => 'txtnombre',
            'precio' => 'txtcosto'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $flete = new Flete();
            // $idflete = $flete->buscarid($id);
            $flete->setNombre($request->get('txtnombre'));
            $flete->setPrecio($request->get('txtcosto'));
            if ($flete->update($id)) {
                return response()->json(['message' => 'Flete actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error ' . $error], 500);
        }
    }
}
