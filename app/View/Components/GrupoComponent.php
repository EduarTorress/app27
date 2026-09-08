<?php

namespace App\View\Components;

use App\Models\Grupo;
use Core\View\Component;

class GrupoComponent extends Component
{
    private $idgrup;
    function __construct($idgrup)
    {
        $this->idgrup = $idgrup;
    }
    function render()
    {
        $grupo = new Grupo();
        $lista = $grupo->listar('');
        return view('components/grupo', ['lista' => $lista, 'idgrup' => $this->idgrup]);
    }
    // 
    // $cgrupo = isset($datosProducto['idgrupo']) ? $datosProducto['idgrupo'] : '';
    // $grupo = new \App\View\Components\GrupoComponent($cgrupo);
    // echo $grupo->render();
    // 
}
