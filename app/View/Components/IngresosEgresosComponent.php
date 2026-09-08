<?php

namespace App\View\Components;

use App\Models\Grupo;
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
        return view('components/ingresosegresos', ['tipo' => $this->tipo]);
    }
}
