<?php

namespace App\View\Components;

use Core\View\Component;

class IGVComponent extends Component
{
    private $igv;
    function __construct($optigv)
    {
        $this->igv = $optigv;
    }
    function render()
    {
        return view('components/igv', ['optigv' => $this->igv]);
    }
}
