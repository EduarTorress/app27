<?php

namespace App\View\Components;

use Core\View\Component;

class EstadoComponent extends Component
{
    private $cest;
    function __construct($cest)
    {
        $this->cest = $cest;
    }
    function render()
    {
        return view('components/estado', ['cest' => $this->cest]);
    }
}
