<?php

namespace App\View\Components;

use Core\View\Component;

class TipoMonedaComponent extends Component
{
    private $cmoneda;
    function __construct($cmon)
    {
        $this->cmoneda = $cmon;
    }
    function render()
    {
        return view('components/tipomoneda', ['cmon' => $this->cmoneda]);
    }
     function renderreports()
    {
        return view('components/tipomonedareport');
    }
}
