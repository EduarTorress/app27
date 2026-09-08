<?php

namespace App\View\Components;

use App\Models\PlanesContables;
use Core\View\Component;

class PlanesComponent extends Component
{
    private $cplan;
    private $cname;
    private $ctxtdescri;

    private $cplanselect;
    private $cdescriselect;

    function __construct($plan, $name, $txtdescri, $planselect, $descriselect)
    {
        $this->cplan = $plan;
        $this->cname = $name;
        $this->ctxtdescri = $txtdescri;
        $this->cplanselect = $planselect;
        $this->cdescriselect = $descriselect;
    }
    function render()
    {
        $plan = new PlanesContables();
        $listadoplanes = $plan->listar($this->cplan);
        return view('components/planescomponent', [
            'listadoplanes' => $listadoplanes,
            'cname' => $this->cname,
            'cplan' => $this->cplan,
            'ctxtdescri' => $this->ctxtdescri,
            'cplanselect' => $this->cplanselect,
            'cdescriselect' => $this->cdescriselect
        ]);
    }
}
