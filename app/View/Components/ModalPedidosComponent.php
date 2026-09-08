<?php

namespace App\View\Components;

use Core\View\Component;

class ModalPedidosComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modallistapedidos');
        return view($cvista);
    }
}
