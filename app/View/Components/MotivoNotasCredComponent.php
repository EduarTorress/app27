<?php

namespace App\View\Components;

use Core\View\Component;

class MotivoNotasCredComponent extends Component
{

    private $cmotivos;
    function __construct($moti)
    {
        $this->cmotivos = $moti;
    }
    function render()
    {
        return view('components/motivosnotac', ['motivos' => $this->cmotivos]);
    }
}
