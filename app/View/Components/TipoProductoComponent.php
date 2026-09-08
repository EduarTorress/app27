<?php

namespace App\View\Components;

use Core\View\Component;

class TipoProductoComponent extends Component
{

    private $ctipp;
    function __construct($ctipp)
    {
        $this->ctipp = $ctipp;
    }
    function render()
    {
        return view('components/tipoproducto', ['ctipp' => $this->ctipp]);
    }
}
