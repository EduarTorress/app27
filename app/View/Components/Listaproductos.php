<?php
namespace App\View\Components;

use Core\View\Component;

class Listaproductos extends Component
{
    var $modo="";
    function render()
    {
        $lista = session()->get('lista', []);
        $cvista = \retornavista('components', 're_listaproductos');
        return view($cvista,['lista'=>$lista]);   
    }
}
