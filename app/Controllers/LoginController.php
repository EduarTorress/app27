<?php

namespace App\Controllers;

use App\Models\DatosGlobales;
use App\Models\Usuario;
use App\Models\Empresa;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;
use App\Models\Serie;
use Core\Routing\Modelo;

class LoginController extends Controller
{
    private Usuario $usuario;
    function __construct()
    {
        $this->usuario = new Usuario();
    }
    public function login()
    {
        $errores = session()->getFlash('errores', []);
        $inputs = session()->getFlash('inputs', []);
        return view('auth/login', [
            "errores" => $errores,
            "inputs" => $inputs
        ]);
    }
    public function store(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'usuario');
        $validator->rule('required', 'password');
        if (!$validator->validate()) {
            $errores = $validator->errors();
            session()->setFlash('inputs', $request->getBody());
            session()->setFlash('errores', $errores);
            header('Location: /login');
            return;
        }
        $password = $request->get("password");
        $valor = $this->usuario->verificarusuario(trim($request->get("usuario")));
        if (!empty($valor[0]['idusua'])) {
            if (password_verify($password, $valor[0]['clave']) === false) {
                session()->setFlash('errores', ['password' => ['Contraseña incorrecta']]);
                session()->setFlash('inputs', $request->getBody());
                header("Location: /login");
                return;
            }
            session()->set('usuario_id', $valor[0]['idusua']);
            session()->set('usuario', $valor[0]['nomb']);
            // session()->set('tipoacceso', $request->get('cmbtipoacceso'));
            session()->set('tipousuario', left($valor[0]['tipo'], 1));
            session()->set('usua_prec', trim($valor[0]['usua_prec']));
            $_SESSION['monedap'] = 'NO';
            $_SESSION['moneda'] = 'NO';
            $_SESSION['igvsololectura'] = 'NO';
            $_SESSION['opigv'] = 'I';
            $ser = new Serie();
            cargarconfig();
            $ser->obtenerSerieDadoAlma(obtenercodigoisla());
            $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
            if ($multiempresa == 'N') {
                datosglobales();
            } else {
                datosglobalesmulti($request->get("cmbAlmacen"));
            }
            $em = new Empresa();
            $em->actualizaFecha();
            $modelo = new Modelo();
            $modelo->cargarsucursalesindex();
            header("location: /admin");
        } else {
            session()->setFlash('errores', ['usuario' => ['No existe usuario']]);
            session()->setFlash('inputs', $request->getBody());
            header('location: /login');
            return;
        };
    }
    function salir()
    {
        session()->cerrarsesion();
        header("location: /login");
    }
}
