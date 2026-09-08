<?php

namespace App\Controllers;

use App\Models\Empresa;
use App\Services\Importadatos;
use Core\Routing\Controller;
use Core\Http\Request;
use Core\Foundation\Application;

class EmpresaController extends Controller
{
    function  __construct()
    {
    }
    public function index()
    {
        return view('empresa/lista', [
            "titulo" => 'Datos de Empresas'
        ]);
    }
    public function datos(Request $request)
    {
        $empresasel = $request->get('empresa', '');
        setempresa($empresasel);
        // $app = Application::getInstance();
        $oempresa = new Empresa();
        $listado = $oempresa->obtenerdatosempresa();
        //header('Content-Type:application/json');
        //$data=json_encode(['message' => 'No hay resultados', 'listado' => []]); 
        return view('empresa/re_datos', [
            'listado' => $listado
        ]);
    }
    function importarucyotros(Request $request)
    {
        $datos = Importadatos::consultarucydni($request->get("ruc"));
        return $datos;
    }
    function obtenervalordolar(Request $request)
    {
        $empresa = new Empresa();
        $rpta = $empresa->obtenerdolar($request->get('txtfecha'));
        $rpta = json_decode($rpta, true);
        $valordolar = $rpta['data'][0]['precio_venta'];
        return $valordolar;
    }
}