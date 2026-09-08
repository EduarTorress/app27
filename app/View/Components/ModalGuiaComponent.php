<?php

namespace App\View\Components;

use Core\View\Component;

class ModalGuiaComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modallistaguias');
        return view($cvista);
    }
}
