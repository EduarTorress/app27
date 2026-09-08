<?php

namespace App\View\Components;

use Core\View\Component;

class ModalProductoComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modalproductos');
        return view($cvista);
    }
}
