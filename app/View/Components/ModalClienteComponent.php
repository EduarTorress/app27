<?php

namespace App\View\Components;

use Core\View\Component;

class ModalClienteComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalclientes');
        return view($cvista);
    }
}
