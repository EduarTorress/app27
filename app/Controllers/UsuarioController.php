<?php

namespace App\Controllers;

use App\Models\PagosPlanilla;
use App\Models\Usuario;
use Core\Http\Request;
use Core\Routing\Controller;

class UsuarioController extends Controller
{
    private Usuario $usuario;
    function __construct()
    {
        $this->usuario = new Usuario();
    }
    public function ActualizaPassword()
    {
        $this->usuario->encryptacontraseñas();
    }
    public function BuscarUsuario($nombre)
    {
        $obj = $this->usuario->verificarusuario($nombre);
        return $obj;
    }
    public function index()
    {
        $titulo = 'Lista de Usuarios';
        return view('admin/usuarios/index', ['titulo' => $titulo]);
    }
    function buscar(Request $request)
    {
        $abuscar = trim($request->get('cbuscar'));
        $lista = $this->usuario->buscarUsuarios($abuscar, 0, 0);
        $cmodo = $request->get("modo");
        return view('admin/usuarios/listausuarios', ['lista' => $lista, 'modo' => $cmodo]);
    }
    function create()
    {
        $titulo = 'Registrar usuario';
        // $tiposusuarios = new TipoUsuarios();
        return view('admin/usuarios/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function store(Request $request)
    {
        try {
            $usuario = new Usuario();
            $usuario->txtnombre = $request->get('txtnombre');
            $usuario->txtclave = $request->get('txtclave');
            $usuario->cmbtipousuario = $request->get('cmbtipousuario');
            $usuario->sueldo = $request->get('txtsueldo');
            if ($usuario->save()) {
                return response()->json(['message' => 'Registrado correctamente'], 200); // Created
            } else {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error->getMessage()], 500);
        }
    }
    function verificar(Request $request)
    {
        $usua = $request->get("txtUsuario");
        $pass = $request->get("txtPassword");
        $ousuario = new Usuario();
        $valor = $ousuario->verificarUsuarioLogueado(trim($usua), $pass);
        if (!empty($valor[0]['idusua'])) {
            return response()->json(['message' => 'Las credenciales son correctas', 'estado' => '1'], 200);
        } else {
            return response()->json(['message' => 'Las credenciales no son correctas', 'estado' => '0'], 422);
        }
    }
    function edit($id)
    {
        $titulo = 'Editar Usuario';
        $datos = $this->usuario->buscarUsuarios('', 1, $id);
        return view('admin/usuarios/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $datos['lista']['items'][0], 'id' => $id]);
    }
    public function update($id, Request $request)
    {
        try {
            $usuario = new Usuario();
            $usuario->txtidusua = $request->get('txtidusua');
            $usuario->txtnombre = $request->get('txtnombre');
            $usuario->txtclave = $request->get('txtclave');
            $usuario->sueldo = $request->get('txtsueldo');
            $usuario->cmbtipousuario = $request->get('cmbtipousuario');
            $sueldo = $usuario->consultarsueldoxusuario($request->get('txtidusua'));
            if (floatval($sueldo[0]['sueldo']) <> floatval($request->get('txtsueldo'))) {
                $pp = new PagosPlanilla();
                $rptapp = $pp->buscarsihaysaldopendientexusuario($request->get('txtidusua'));
                foreach ($rptapp['lista'] as $pp) {
                    if (floatval($pp['pendiente']) > 0) {
                        return response()->json(['message' => 'No se puede actualizar el sueldo porque hay cuentas pendientes'], 400);
                    }
                }
            }
            if ($usuario->update()) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error al actualizar' . $error->getMessage()], 500);
        }
    }
}
