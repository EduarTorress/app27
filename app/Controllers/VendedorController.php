<?php

namespace App\Controllers;

use App\Models\Vendedor;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class VendedorController extends Controller
{
    static function listar(string $abuscar)
    {
        $vendedor = new Vendedor();
        $vendedores = $vendedor->listar($abuscar);
        return $vendedores;
    }
    function index()
    {
        $titulo = 'Vendedores';
        return view('admin/vendedores/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $cbuscar = "%" . $request->get('cbuscar') . "%";
        $vendedor = new Vendedor();
        $vendedores = $vendedor->listar($cbuscar);
        return view('admin/vendedores/listavendedores', ['lista' => $vendedores]);
    }
    function create()
    {
        $titulo = 'Registrar Vendedor';
        return view('admin/vendedores/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Vendedor';
        $vendedor = new Vendedor();
        $vendedor = $vendedor->buscarid($id);
        return view('admin/vendedores/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $vendedor, 'id' => $id]);
    }
    function store(Request $request)
    {
        try {
            $vendedor = new Vendedor();
            $vendedor->txtnombre = $request->get('txtNombre');
            if ($vendedor->save()) {
                return response()->json(['message' => 'Registrado correctamente'], 200); // Created
            } else {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtNombre')->message('El nombre es obligatorio');
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
            $vendedor = new Vendedor();
            $vendedor->txtnombre = $request->get('txtNombre');
            if ($vendedor->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error->getMessage()], 500);
        }
    }
}
