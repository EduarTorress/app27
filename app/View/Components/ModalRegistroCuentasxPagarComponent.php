<?php

namespace App\View\Components;

use Core\View\Component;

class ModalRegistroCuentasxPagarComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalregistrocuentasxpagar');
        return view($cvista);
    }
}
