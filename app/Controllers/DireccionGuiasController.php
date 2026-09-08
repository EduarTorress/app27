<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\DireccionGuia;
use App\Models\Remitente;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class DireccionGuiasController extends Controller
{
    private $direccion;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->direccion = new DireccionGuia();
    }
    function index()
    {
        $titulo = 'Direcciones';
        return view('admin/direccion/index', ['titulo' => $titulo]);
    }
    function lista(Request $request)
    {
        $cbuscar = "%" . $request->get('cbuscar') . "%";
        $lista = $this->direccion->listar($cbuscar);
        return view('admin/direccion/listadireccion', ['lista' => $lista]);
    }
    static function listarDireccion($cbuscar)
    {
        $direccion = new DireccionGuia();
        $lista = $direccion->listar($cbuscar);
        return $lista;
    }
    function listarxremitente(Request $request)
    {
        $lista = $this->direccion->listarxremitente($request->get("idremitente"));
        return view('admin/direccion/listardireccionr', ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Dirección';
        return view('admin/direccion/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Dirección';
        $direccion = $this->direccion->buscarid($id);
        return view('admin/direccion/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $direccion, 'id' => $id]);
    }
    function store(Request $request)
    {
        // var $txtDireccion = "";
        // var $txtCiudad = "";
        // var $cmbUbigeo = "";
        // var $txtIdDestinatario = "";
        // var $txtIdRemitente = "";
        try {
            $direccionGuia = new DireccionGuia();
            $direccionGuia->txtDireccion = $request->get('txtDireccion');
            $direccionGuia->txtCiudad = $request->get('txtCiudad');
            $direccionGuia->cmbUbigeo = $request->get('cmbUbigeo');
            $direccionGuia->txtIdDestinatario = $request->get('txtIdDestinatario');
            $direccionGuia->txtIdRemitente = $request->get('txtIdRemitente');

            if ($direccionGuia->save()) {
                return response()->json(['message' => 'Dirección registrada correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar dirección'], 400); // Created
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
    public function update($id, Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtDireccion')->message('El nombre es obligatorio');
        $validator->labels([
            'nombre' => 'txtDireccion'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $direccionGuia = new DireccionGuia();
            $direccionGuia->txtDireccion = $request->get('txtDireccion');
            $direccionGuia->txtCiudad = $request->get('txtCiudad');
            $direccionGuia->cmbUbigeo = $request->get('cmbUbigeo');
            $direccionGuia->txtIdDestinatario = $request->get('txtIdDestinatario');
            $direccionGuia->txtIdRemitente = $request->get('txtIdRemitente');

            // $idDireccion = $direccionGuia->buscarid($id);

            if ($direccionGuia->update($id)) {
                // echo $idRemitente['idprov'];
                return response()->json(['message' => 'Direccion actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
    public function darBaja($id, Request $request)
    {
        try {
            $direccionGuia = new DireccionGuia();
            // $unidades->chofer = $request->get('txtnombre');
            if ($direccionGuia->darBaja($id)) {
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al eliminar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error], 500);
        }
    }
}
