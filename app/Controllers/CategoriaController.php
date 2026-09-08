<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Categoria;
use Core\Http\Request;
use Core\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Valitron\Validator;

class CategoriaController extends Controller
{
    private $categoria;
    private $grupo;
    public function __construct()
    {
        $middleware = new AuthAdminMiddleware();
        $this->registerMiddleware($middleware);
        $this->categoria = new Categoria();
    }
    public function index()
    {
        return view('admin/categorias/index', ['titulo' => 'Categorias']);
    }
    public function search(Request $request)
    {
        $lista = $this->categoria->listar($request->get('cbuscar'), 0);
        return view('admin/categorias/listarcategorias', [
            'lista' => $lista
        ]);
        // header('Content-Type:application/json');
        // return json_encode(['message' => 'No hay resultados', 'listado' => []]);
    }
    public function create()
    {
        $titulo = 'Registrar Categoria';
        $grupos = GrupoController::listargrupos('');
        return view('admin/categorias/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0, 'grupos' => $grupos]);
    }
    function edit($id)
    {
        $titulo = 'Editar Categoria';
        $grupos = GrupoController::listargrupos('');
        $categoria = $this->categoria->buscarid($id);
        return view('admin/categorias/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $categoria, 'grupos' => $grupos, 'id' => $id]);
    }
    function eliminar($id)
    {
        $this->categoria->eliminar($id);
        return json_encode(['message' => 'Eliminado satisfactoriamente', 'listado' => []]);
    }
    public function store(Request $request)
    {
        // validaction
        // 100 - 199    => Informativos
        // 200 - 299    => Satisfactorios
        // 300          => Redirecciones
        // 400          => Errores del cliente
        // 500          => Errores del servidor
        if (!$this->categoria->buscarnombre($request->get('txtnombre'), 0)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El nombre de categoria ya está registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El Nombre de Categoria es obligatorio');
        $validator->rule('lengthMax', 'txtnombre', 50)->message('La Longitud Máxima es 50 caracteres');
        $validator->rule('lengthMin', 'txtnombre', 5)->message('La Longitud Mínima es 5 caracteres');
        $validator->rule('min', 'idgrupo', 1)->message('Seleccione un Grupo para esta Categoria');
        $validator->rule('integer', 'idgrupo')->message('El Valor de Grupo debe ser Númerico');
        $validator->labels([
            'nombre' => 'txtnombre',
            'grupo' => 'cmbgrupos'
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
            $categoria = new Categoria();
            $categoria->nombre = $request->get('txtnombre');
            $categoria->idgrupo = \intval($request->get("idgrupo"));
            if ($categoria->save()) {
                return response()->json(['message' => 'Categoría registrada correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar Categoria '], 400); // Created
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error'], 500);
        }
    }
    public function update($id, Request $request)
    {
        if (!$this->categoria->buscarnombre($request->get('txtnombre'), $id)) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => array('txtnombre' => array('El nombre de Categoria ya está registrado'))
            ];
            return response()->json($data, 422);
        }
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El nombre de Categoria es Obligatorio');
        $validator->rule('lengthMax', 'txtnombre', 50)->message('La Longitud Máxima es 50 caracteres');
        $validator->rule('lengthMin', 'txtnombre', 5)->message('La Longitud Mínima es 5 caracteres');
        $validator->rule('integer', 'idgrupo')->message('El Valor de Grupo debe ser Númerico');
        $validator->labels([
            'nombre' => 'txtnombre',
            'categoria' => 'cmbgrupos'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $categoria = new Categoria();
            // $idgrupo = $categoria->buscarid($id);
            $categoria->nombre = $request->get('txtnombre');
            $categoria->idgrupo = $request->get('idgrupo');
            if ($categoria->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
    function obtenergrupoporcat(Request $request)
    {
        $id = $request->get('idcat');
        $grupo = $this->categoria->buscarid($id);
        return response()->json(['message' => $grupo['idgrupo']], 200);
    }
}
