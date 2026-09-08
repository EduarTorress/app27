<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Routing\Controller;
use App\Models\Dolar;
use App\Models\Empresa;

class ValorDolarController extends Controller
{
    function obtenerDolar(Request $request)
    {
        // $fech = $request->get('fech');
        // $dolar = new Dolar();
        // $valdol = $dolar->obtenerDolar($fech, 'C');
        // $valdol = number_format($valdol, 3, ".", "");
        // return view('components/valordolar', ['dolar' => $valdol]);
        $fech = $request->get('fech');
        $dolar = new Dolar();
        $valord = $dolar->obtenerDolar($fech, 'C');
        if ($valord == 0) {
            if ($request->get('fech') == date('Y-m-d')) {
                $valord = $_SESSION['gene_dola'];
            } else {
                $empresa = new Empresa();
                $valord = $empresa->obtenerdolar($fech);
            }
        }
        $valord = number_format($valord, 3, ".", "");
        // return response()->json(['valordolar' => $valord], 200);
        return view('components/valordolar', ['dolar' => $valord]);
    }
    function getvaluedolarocompra(Request $request)
    {
        $fech = $request->get('fech');
        $dolar = new Dolar();
        $valord = $dolar->obtenerDolar($fech, 'C');
        if ($valord == 0) {
            $empresa = new Empresa();
            $valord = $empresa->obtenerdolar($fech);
        }
        $valord = number_format($valord, 3, ".", "");
        return response()->json(['valordolar' => $valord], 200);
    }
}
