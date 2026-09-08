<?php

namespace App\View\Components;

use Core\View\Component;

class ModalGestionStockComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalgestionstock');
        return view($cvista);
    }
}
