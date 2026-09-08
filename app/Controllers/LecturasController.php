<?php

namespace App\Controllers;

use App\Models\Lecturas;
use Core\Http\Request;
use Core\Routing\Controller;

class LecturasController extends Controller
{
    function indexhistorial()
    {
        $ctitulo = "Historial de Lecturas";
        return view('lecturas/informes/indexhistorial', ['titulo' => $ctitulo]);
    }
    function buscarhistorial(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbalmacen = $request->get("cmbalmacen");
        $lecturas = new Lecturas();
        $listado = $lecturas->buscarhistorial($dfi, $dff, $cmbalmacen);
        return view('lecturas/informes/listarhistorial', [
            "listado" => $listado
        ]);
    }
    function indexregistro()
    {
        $ctitulo = "Registrar Lecturas";
        return view('lecturas/indexregistro', ['titulo' => $ctitulo]);
    }
    function listaregistro(Request $request)
    {
        $cmbalmacen = $request->get("cmbalmacen");
        $lecturas = new Lecturas();
        $listado = $lecturas->listaregistro($cmbalmacen);
        return view('lecturas/listaregistro', [
            "listado" => $listado
        ]);
    }
    function registrarlecturas(Request $request)
    {
        verificarSesion();
        $lecturas = new Lecturas();
        $detalle = json_decode($request->get("detalle"));
        $lecturas->detalle = json_decode(json_encode($detalle), true);
        $rpta = $lecturas->registrar();
        if ($rpta['estado'] == "1") {
            return response()->json(['message' => $rpta['mensaje'], 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
}
