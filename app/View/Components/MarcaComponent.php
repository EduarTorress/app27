<?php

namespace App\View\Components;

use App\Models\Marca;
use Core\View\Component;

class MarcaComponent extends Component
{
    private $idmar;
    function __construct($idmar)
    {
        $this->idmar = $idmar;
    }
    function render()
    {
        $marca = new Marca();
        $lista = $marca->listar('%%');
        return view('components/marca', ['lista' => $lista, 'idmar' => $this->idmar]);
    }
}
