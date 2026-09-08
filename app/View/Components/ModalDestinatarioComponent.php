<?php

namespace App\View\Components;

use Core\View\Component;

class ModalDestinatarioComponent extends Component
{
    function render()
    {
        $cvista = \retornavista('components', 'modaldestinatario');
        return view($cvista);
    }
}