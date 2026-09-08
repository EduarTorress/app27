<?php

namespace App\View\Components;

use Core\View\Component;

class ModalProveedorComponent extends Component
{
    function render()
    {        
        $cvista = \retornavista('components', 'modalproveedor');
        return view($cvista);
    }
}
