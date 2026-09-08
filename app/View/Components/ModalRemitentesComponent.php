<?php

namespace App\View\Components;

use Core\View\Component;

class ModalRemitentesComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalremitentes');
        return view($cvista);
    }
}