<?php

namespace App\View\Components;

use Core\View\Component;

class ModalImprimir extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalimprimir');
        return view($cvista);
    }
}
