<?php

namespace App\View\Components;

use Core\View\Component;

class ModalVehiculoComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalvehiculo');
        return view($cvista);
    }
}
