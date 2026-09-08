<?php

namespace App\Controllers;

use App\Models\Documentos;
use Core\Routing\Controller;

class DocumentoController extends Controller
{
    private  $dctos;
    function __construct()
    {
        $this->dctos = new Documentos();
    }
    function Obtenerdctosocompras($buscar)
    {
        $lista = $this->dctos->listardocumentosocompras($buscar);
        return $lista;
    }
}
