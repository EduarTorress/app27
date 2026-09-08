<?php

namespace App\View\Components;

use Core\View\Component;

class ModalTransportistaComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modaltransportista');
        return view($cvista);
    }
}
