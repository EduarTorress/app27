<?php

namespace App\Controllers;

use App\Models\ZonaClientes;
use Core\Routing\Controller;

class ZonaClientesController extends Controller
{
    static function listar($abuscar)
    {
        $zona = new ZonaClientes();
        $zonas = $zona->listar($abuscar);
        return $zonas;
    }
}
