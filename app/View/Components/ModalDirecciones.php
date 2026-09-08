<?php
namespace App\View\Components;

use Core\View\Component;

class ModalDirecciones extends Component
{
    function render(){
        $cvista = \retornavista('components', 'modaldirecciones');
        return view($cvista);
    }
}
