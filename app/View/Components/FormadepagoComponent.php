<?php

namespace App\View\Components;

use Core\View\Component;

class FormadepagoComponent extends Component
{
    private $cformapago;
    function __construct($cforma)
    {
        $this->cformapago = $cforma;
    }
    function render()
    {
        return view('components/formadepago', ['cform' => $this->cformapago]);
    }
      function renderreports()
    {
        return view('components/formadepagoreports');
    }
}
