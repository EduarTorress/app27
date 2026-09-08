<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Grupo;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class GrupoController extends Controller
{
    private $grupo;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->grupo = new Grupo();
    }
    function index()
    {
        $titulo = 'Grupos';
        return view('admin/grupos/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $lista = $this->grupo->listar($request->get('cbuscar'));
        return view('admin/grupos/listagrupos', ['lista' => $lista]);
    }
    static function listargrupos($cbuscar)
    {
        $grupo = new Grupo();
        $lista = $grupo->listar($cbuscar);
        return $lista;
    }
    function create()
    {
        $titulo = 'Registrar Grupo';
        return view('admin/grupos/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Grupo';
        $grupo = $this->grupo->buscarid($id);
        return view('admin/grupos/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $grupo, 'id' => $id]);
    }
    function store(Request $request)
    {
        if (!$this->grupo->buscarnombre($request->get('txtnombre'), 0)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El nombre de Grupo ya está registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El Nombre de Grupo es Obligatorio');
        $validator->rule('lengthMax', 'txtnombre', 50)->message('La Longitud Máxima es 50 caracteres');
        $validator->rule('lengthMin', 'txtnombre', 5)->message('La Longitud Mínima es 5 caracteres');
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
        // continuamos
        try {
            $grupo = new Grupo();
            $grupo->nombre = $request->get('txtnombre');
            if ($grupo->save()) {
                return response()->json(['message' => 'Grupo registrado correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar Grupo '], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error], 500);
        }
    }
    public function update($id, Request $request)
    {
        if (!$this->grupo->buscarnombre($request->get('txtnombre'), $id)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El nombre de Grupo ya está registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El Nombre de Grupo es obligatorio');
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
            $grupo = new Grupo();
            // $idgrupo = $grupo->buscarid($id);
            $grupo->nombre = $request->get('txtnombre');
            if ($grupo->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error], 500);
        }
    }
}
