<?php

namespace App\View\Components;

use Core\View\Component;

class ModalPreciosComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalprecios');
        return view($cvista);
    }
}
