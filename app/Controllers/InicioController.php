<?php

namespace App\Controllers;

use Core\Routing\Controller;
use Core\View\View;

class InicioController extends Controller
{
  public function inicio()
  {
    return view('layouts/admin');;
  }
}
