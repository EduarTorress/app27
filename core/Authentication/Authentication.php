<?php

namespace Core\Authentication;

use App\Models\Usuario;

class Authentication
{
    public function user()
    {
        return Usuario::find(session()->get('usuario_id'));
    }

    public function isAuth(): bool
    {
        if (is_null(session()->get('usuario_id'))) {
            return false;
        }
        // para casos que el usuario fue eliminado, o esta deshabilitado
        if (is_null($this->user())) {
            session()->remove('usuario_id');
            return false;
        }
        return true;
    }
}
