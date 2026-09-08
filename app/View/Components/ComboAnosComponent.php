<?php

namespace App\View\Components;

use Core\View\Component;

class ComboAnosComponent extends Component
{
    private $canos;
    function __construct($canos)
    {
        $this->canos = $canos;
    }
    function render()
    {
        return view('components/comboanos', ['canos' => $this->canos]);
    }
     function renderreport()
    {
        return view('components/comboanosreport', ['canos' => $this->canos]);
    }
       function renderreportmes()
    {
        return view('components/combomesreport');
    }
}
