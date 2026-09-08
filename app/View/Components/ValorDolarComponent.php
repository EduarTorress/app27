<?php

namespace App\View\Components;

use Core\View\Component;
use App\Models\Dolar;

class ValorDolarComponent extends Component
{
    function render()
    {
        $dolar = new Dolar();
        $valdol = $dolar->obtenerDolar(date("Y-m-d"), 'C');
        return view('components/valordolar', ['dolar' => $valdol]);
    }
}
