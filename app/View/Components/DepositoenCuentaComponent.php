<?php

namespace App\View\Components;

use App\Models\NumerosCuenta;
use Core\View\Component;

class DepositoenCuentaComponent extends Component
{
    function __construct() {}
    function render()
    {
        $numerocuenta = new NumerosCuenta();
        $listanumeros = $numerocuenta->listar('%%');
        return view('components/depositoencuenta', ['numeroscuenta' => $listanumeros]);
    }
}
