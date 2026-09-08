<?php

namespace App\View\Components;

use Core\View\Component;

class ModalGuiaTrComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modallistaguiastr');
        return view($cvista);
    }
}
