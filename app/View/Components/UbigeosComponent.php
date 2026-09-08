<?php

namespace App\View\Components;

use Core\View\Component;

class UbigeosComponent extends Component
{
    var $modo = "";
    var $ubigeo = "";
    function __construct($cmodo, $cubigeo)
    {
        $this->modo = $cmodo;
        $this->ubigeo = $cubigeo;
    
    }
    function render()
    {
        $ubigeos = listarubigeos();
        return view('components/ubigeos', ['ubigeos' => $ubigeos, "modo" => $this->modo, "ubigeo" => $this->ubigeo]);
    }
}
