<?php

namespace App\Controllers;

use App\Models\Correlativo;
use App\Models\Serie;
use Core\Routing\Controller;

class SerieController extends Controller
{
    static function listarseries()
    {
        $series = new Serie();
        $lista = $series->mostrar();
        return $lista;
    }
    static function correlativo($nserie, $ctdoc)
    {
        $ocorr = new Correlativo();
        $nsgte = $ocorr->Obtenercorrelativo($_SESSION['nserie'], $ctdoc);
        return $nsgte;
    }
}
