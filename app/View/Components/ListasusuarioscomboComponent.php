<?php

namespace App\View\Components;

use App\Models\Usuario;
use Core\View\Component;

class ListasusuarioscomboComponent extends Component
{
    private $idusua;
    function __construct($idusua)
    {
        $this->idusua = $idusua;
    }
    function render()
    {
        $usuario = new Usuario();
        $lista = $usuario->buscarUsuarios('%%', 0, 0);
        return view('components/listausuarioscombo', ['lista' => $lista, 'idusua' => $this->idusua]);
    }
}
