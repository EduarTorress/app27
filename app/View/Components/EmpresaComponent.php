<?php

namespace App\View\Components;

use App\Controllers\SucursalController;
use Core\View\Component;

class EmpresaComponent extends Component
{
  private $empresa;
  function __construct($cempresa)
  {
    $this->empresa = $cempresa;
  }
  public function render()
  {
    $empresas = SucursalController::listarsucursales();
    return view('components/header', ['empresas' => $empresas, 'cempresa' => $this->empresa]);
  }
}
