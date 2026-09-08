<?php

namespace App\View\Components;

use App\Models\Unidad;
use Core\View\Component;

class UnidadComponent extends Component
{
    private $idunid;
    function __construct($idunid)
    {
        $this->idunid = $idunid;
    }
    function render()
    {
        $unidad = new Unidad();
        $lista = $unidad->listar();
        // $linkjson = file_get_contents('http://companiasysven.com/datasetunidades.json');
        // $json = json_decode($linkjson, true);
        return view('components/unidad', ['lista' => $lista['lista'], 'idunid' => $this->idunid]);
    }
}
