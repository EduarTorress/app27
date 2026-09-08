<?php

namespace App\View\Components;

use App\Controllers\SucursalController;
use Core\Routing\Modelo;
use Core\View\Component;

class AlmacenComponent extends Component
{
  private $empresa;
  function __construct($cempresa)
  {
    $this->empresa = $cempresa;
  }
  public function render()
  {
    if (empty($_SESSION['sucursales'])) {
      $modelo = new Modelo();
      $modelo->cargarsucursalesindex();
      $empresas = $_SESSION['sucursales'];
    } else {
      $empresas = $_SESSION['sucursales'];
    };
    return view('components/almacen', ['empresas' => $empresas, 'cempresa' => $this->empresa]);
  }
}
