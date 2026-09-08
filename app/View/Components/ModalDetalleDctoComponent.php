<?php

namespace App\View\Components;

use Core\View\Component;

class ModalDetalleDctoComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modaldetalledcto');
        return view($cvista);
    }
}
