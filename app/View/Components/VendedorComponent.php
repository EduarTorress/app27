<?php

namespace App\View\Components;

use App\Models\Vendedor;
use Core\View\Component;

class VendedorComponent extends Component
{
    private $idven;
    function __construct($idven)
    {
        $this->idven = $idven;
    }
    function render()
    {
        $vendedor = new Vendedor();
        $vendedores = $vendedor->listar('');
        return view('components/vendedor', ['vendedores' => $vendedores, 'idven' => $this->idven]);
    }
    function renderreports()
    {
        $vendedor = new Vendedor();
        $vendedores = $vendedor->listar('');
        return view('components/vendedorreport', ['vendedores' => $vendedores, 'idven' => $this->idven]);
    }
}
