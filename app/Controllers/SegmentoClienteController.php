<?php

namespace App\Controllers;

use App\Models\SegmentosClientes;
use Core\Routing\Controller;

class SegmentoClienteController extends Controller
{
    static function listar()
    {
        $segmento = new SegmentosClientes();
        $listasegmentos = $segmento->listar();
        return $listasegmentos;
    }
}
