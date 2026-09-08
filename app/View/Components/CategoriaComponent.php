<?php

namespace App\View\Components;

use App\Models\Categoria;
use Core\View\Component;

class CategoriaComponent extends Component
{
    private $idcat;
    function __construct($idcat)
    {
        $this->idcat = $idcat;
    }
    function render()
    {
        $categoria = new Categoria();
        $lista = $categoria->listar('%%', '0');
        return view('components/categoria', ['lista' => $lista, 'idcat' => $this->idcat]);
    }
}
