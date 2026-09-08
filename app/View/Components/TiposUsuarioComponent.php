<?php

namespace App\View\Components;

use App\Models\Usuario;
use Core\View\Component;

class TiposUsuarioComponent extends Component
{
    private $tipo;
    function __construct($tipo)
    {
        $this->tipo = $tipo;
    }
    function render()
    {
        $tiposusuarios = new Usuario();
        $listatiposusuarios = $tiposusuarios->listarTipos();
        return view('components/tiposusuario', ['lista' => $listatiposusuarios, 'tipo' => $this->tipo]);
    }
}
