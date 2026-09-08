<?php

namespace App\Controllers;

use Core\Routing\Controller;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\Combo;
use Core\Http\Request;

class CombosController extends Controller
{
    private $combo;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->combo = new Combo();
    }
    function modalcreatedetalle(Request $request)
    {
        $modo = "N";
        $ctitulo = "Agregar Detalle al combo";
        $this->combo = new Combo();
        $this->combo->idproducto=$request->get('txtidproducto');
        $combo=$this->combo->buscarid();
        $vista = \retornavista('admin/combos/', 'create');
        return view($vista, ['titulo' => $ctitulo, 'modo' => $modo,'combo'=>$combo]);
    }
    function registrarcombo(Request $request)
    {
        $this->combo = new Combo();
        $this->combo->idproducto=$request->get('txtidproducto');
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $this->combo->save($detalle);
        return json_encode($rpta);
    }
}
