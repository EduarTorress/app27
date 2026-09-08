<?php

namespace App\View\Components;

use Core\View\Component;

class MotivoNotasDebiComponent extends Component
{
    private $cmotivos;
    function __construct($moti)
    {
        $this->cmotivos = $moti;
    }
    function render()
    {
        return view('components/motivosnotasd', ['motivod' => $this->cmotivos]);
    }
}
