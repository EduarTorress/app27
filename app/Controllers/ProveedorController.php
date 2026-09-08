<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Proveedor;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class ProveedorController extends Controller
{
    private $proveedor;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->proveedor = new Proveedor();
    }
    function index()
    {
        $titulo = 'Proveedores';
        return view('admin/proveedor/index', ['titulo' => $titulo]);
    }
    function buscar(Request $request)
    {
        $abuscar = trim($request->get('cbuscar'));
        $opt = intval($request->get("option"));
        $nid = 0;
        $lista = $this->proveedor->buscarProveedor($abuscar, $opt, $nid);
        $cmodo = $request->get("modo");
        return view('admin/proveedor/tm_listaproveedores', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function lista(Request $request)
    {
        $cbuscar = "%" . $request->get('cbuscar') . "%";
        $lista = $this->proveedor->listar($cbuscar);
        return view('admin/proveedor/listarproveedores', ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar proveedor';
        return view('admin/proveedor/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar proveedor';
        $proveedor = $this->proveedor->buscarid($id);
        return view('admin/proveedor/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $proveedor[0], 'id' => $id]);
    }
    function store(Request $request)
    {
        try {
            $proveedor = new Proveedor();
            $proveedor->txtRUC = $request->get('txtRUC');
            $proveedor->txtDNI = $request->get('txtDNI');
            $proveedor->txtNombre = $request->get('txtNombre');
            $proveedor->txtDireccion = $request->get('txtDireccion');
            $proveedor->txtCiudad = $request->get('txtCiudad');
            $proveedor->txtUbigeo = $request->get('cmbUbigeo');
            if (!empty($request->get('txtRUC'))) {
                $existe = $proveedor->consultarprovxruc($request->get('txtRUC'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Proveedor ya existente.'], 400);
                }
            }
            if (!empty($request->get('txtDNI'))) {
                $existe = $proveedor->consultarprovxdni($request->get('txtDNI'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Proveedor ya existente.'], 400);
                }
            }
            if (!empty($request->get('txtNombre'))) {
                $existe = $proveedor->consultarprovxrazon($request->get('txtNombre'));
                if ($existe == "T") {
                    return response()->json(['message' => 'Proveedor ya existente.'], 400);
                }
            }
            if ($proveedor->save()) {
                return response()->json(['message' => 'Registrado correctamente'], 201); // Created
            } else {
                return response()->json(['message' => 'Error al registrar'], 422); // Created
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        // if (!$this->unidad->buscarnombre($request->get('txtnombre'), $id)) {
        //     $data = [
        //         'message' => 'Errores de validacion',
        //         'errors' => array('txtnombre' => array('El nombre de Grupo Ya está Registrado'))
        //     ];
        //     return response()->json($data, 422);
        // }
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
            $proveedor = new Proveedor();
            $proveedor->txtRUC = $request->get('txtRUC');
            $proveedor->txtDNI = $request->get('txtDNI');
            $proveedor->txtNombre = $request->get('txtNombre');
            $proveedor->txtDireccion = $request->get('txtDireccion');
            $proveedor->txtCiudad = $request->get('txtCiudad');
            $proveedor->txtUbigeo = $request->get('cmbUbigeo');
            // $idProveedor = $proveedor->buscarid($id);
            // if (!empty($request->get('txtRUC'))) {
            //     $existe = $proveedor->consultarprovxruc($request->get('txtRUC'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Proveedor ya existente.'], 400);
            //     }
            // }
            // if (!empty($request->get('txtDNI'))) {
            //     $existe = $proveedor->consultarprovxdni($request->get('txtDNI'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Proveedor ya existente.'], 400);
            //     }
            // }
            // if (!empty($request->get('txtNombre'))) {
            //     $existe = $proveedor->consultarprovxrazon($request->get('txtNombre'));
            //     if ($existe == "T") {
            //         return response()->json(['message' => 'Proveedor ya existente.'], 400);
            //     }
            // }
            if ($proveedor->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 422);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error], 500);
        }
    }
    public function darBaja($id)
    {
        try {
            $proveedor = new Proveedor();
            // $unidades->chofer = $request->get('txtnombre');
            if ($proveedor->darBaja($id)) {
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al eliminar'], 422);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error' . $error], 500);
        }
    }
}
