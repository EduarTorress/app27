<?php

namespace App\Middlewares;

use Core\Http\Middleware;

class AuthAdminMiddleware extends Middleware
{
    public function execute(string $method)
    {
        // verificar si el usuario ha iniciado sesion
        if (empty($this->methods) || in_array($method, $this->methods)) {
            if (is_null(session()->get('usuario_id'))) {
                // realizar una redireccion /login
                header("Location: /login");
            }
        }
    }
}
