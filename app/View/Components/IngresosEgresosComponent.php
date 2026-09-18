<?php

namespace App\View\Components;

use App\Models\Grupo;
use App\Models\Usuario;
use Core\View\Component;

class IngresosEgresosComponent extends Component
{
    private $tipo;
    function __construct($tipo)
    {
        $this->tipo = $tipo;
    }
    function render()
    {
        $usuarios = new Usuario();
        $datausuarios = $usuarios->buscarUsuarios('%%', 0, 0);
        return view('components/ingresosegresos', ['tipo' => $this->tipo, 'usuarios' => $datausuarios['lista']['items']]);
    }
}
