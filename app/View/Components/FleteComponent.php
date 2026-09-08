<?php

namespace App\View\Components;

use App\Models\Flete;
use Core\View\Component;

class FleteComponent extends Component
{
    private $idflete;
    function __construct($idflete)
    {
        $this->idflete = $idflete;
    }
    function render()
    {
        $flete = new Flete();
        $lista = $flete->listar('');
        return view('components/flete', ['lista' => $lista, 'idflete' => $this->idflete]);
    }
}
