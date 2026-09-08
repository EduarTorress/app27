<?php

namespace App\Controllers;

use App\Models\Usuario;
use Core\Http\Request;
use Core\Routing\Controller;
use Core\View\View;
use Valitron\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        $errores = session()->getFlash('errores', []);
        $inputs = session()->getFlash('inputs', []);
        return view('auth/register', [
            "errores" => $errores,
            "inputs" => $inputs
        ]);
    }
    public function store(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'apellido_paterno');
        $validator->rule('lengthMin', 'apellido_paterno', 3);
        $validator->rule('lengthMax', 'apellido_paterno', 10);
        $validator->rule('required', 'apellido_materno');
        $validator->rule('required', 'nombres');
        $validator->rule('required', 'email');
        $validator->rule('required', 'password');
        $validator->rule('required', 'password_confirmacion');
        $validator->rule('email', 'email');
        $validator->rule('equals', 'password', 'password_confirmacion');
        if (!$validator->validate()) {
            $errores = $validator->errors();
            session()->setFlash('inputs', $request->getBody());
            session()->setFlash('errores', $errores);
            header('Location: /register');
            return;
        }
        try {
            $usuario = new Usuario();
            $usuario->apellido_paterno = $request->get("apellido_paterno");
            $usuario->apellido_materno = $request->get("apellido_materno");
            $usuario->nombres = $request->get('nombres');
            $usuario->email = $request->get('email');
            $usuario->password = password_hash($request->get('password'), PASSWORD_DEFAULT);
            $usuario->save();
            header("Location: /login");
        } catch (\Exception $error) {
            header("Location: /register");
        }
    }
}