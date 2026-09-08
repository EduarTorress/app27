<?php

namespace App\View\Components;

use Core\View\Component;

class RetirarparaBancosComponent extends Component
{
    function __construct() {}
    function render()
    {
        return view('components/retiroparabancos', []);
    }
}
