<?php

namespace App\View\Components;

use App\Models\Usuario;
use Core\View\Component;

class ModalConfirmarLoginComponent extends Component
{
    function render()
    {
        $usuario = new Usuario();
        $usuarios = $usuario->consultarsoloadmin();
        return view('components/modalconfirmarlogin', ['usuarios' => $usuarios]);
    }
}
