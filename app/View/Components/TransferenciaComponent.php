<?php

namespace App\View\Components;

use App\Models\Grupo;
use Core\View\Component;

class TransferenciaComponent extends Component
{
    function __construct() {}
    function render()
    {
        return view('components/transferencia', []);
    }
}
