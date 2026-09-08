<?php

namespace App\Controllers;

use App\Models\Caja;
use App\Models\CtasporCobrar;
use App\Models\Ventas;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;

class CobranzasController extends Controller
{
    function index()
    {
        return \view('cobranzas/index', ['titulo' => 'Gestionar Cobranzas']);
    }
    function listarvtos(Request $request)
    {
        $ctas = new CtasporCobrar();
        $idcliente = $request->get("idcliente");
        $txtfechai = $request->get("txtfechai");
        $txtfechaf = $request->get("txtfechaf");
        $lista = $ctas->vencimientosporcliente($idcliente, $txtfechai, $txtfechaf);
        return view('cobranzas/listarvtos', ['lista' => $lista]);
    }
    function indexlistacobranzastodo()
    {
        return \view('cobranzas/informes/indexlistarcobranzastodo', ['titulo' => 'Listar Cobranzas']);
    }
    function listarcobranzastodo(Request $request)
    {
        $ctas = new CtasporCobrar();
        $cmbformapago = $request->get("cmbformapago");
        $cmbalmacen = $request->get("cmbalmacen");
        $fecha = $request->get("txtfecha");
        $rpta = $ctas->listarcobranzastodo($cmbformapago, $cmbalmacen, $fecha);
        // return view('cobranzas/informes/listarcobranzastodo', ['listado' => $rpta['lista']]);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $rpta['lista']], 200);
    }
    function listarestadocuenta(Request $request)
    {
        $ctas = new CtasporCobrar();
        $idcliente = $request->get("idcliente");
        $cmbalmacen = $request->get("cmbalmacen");
        $cmbmoneda = $request->get("cmbmoneda");
        $lista = $ctas->listarestadocuenta($idcliente, $cmbalmacen, $cmbmoneda);
        return view('cobranzas/listarestadocuenta', ['lista' => $lista['lista']]);
    }
    function registrarcobranzas(Request $request)
    {
        $ctas = new CtasporCobrar();
        $ctas->txtdocumento = $request->get('txtdocumento');
        $ctas->txtfecha = $request->get('txtfecha');
        $ctas->txtimporte = $request->get('txtimporte');
        $ctas->cmbforma = $request->get('cmbforma');
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $ctas->registrarcobranzas($detalle);
        return response()->json(['message' => $rpta['mensaje'], 'listado' => []], 200);
    }
    function consultardetalleventa(Request $request)
    {
        $vtas = new Ventas();
        $idauto = $request->get("idauto");
        $lista = $vtas->consultardetalleventa($idauto);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $lista], 200);
    }
    function exportardocumentocondetalle(Request $request)
    {
        $ctasporcobrar = new CtasporCobrar();
        $txtfechai = $request->get("txtfechai");
        $txtfechaf = $request->get("txtfechaf");
        $st = $ctasporcobrar->exportarreportecreditoscondetalle($request->get('idcliente'), $txtfechai, $txtfechaf);
        $oimp = new Imprimir();

        $dctosagrupados = array();
        foreach ($st['lista'] as $k => $item) {
            $ndoc = $item["ndoc"];
            $dctosagrupados[$ndoc][] = $item;
        }

        foreach ($dctosagrupados as $k => $items) {
            $detalle = [];
            $j = 0;
            foreach ($items as $p) {
                $detalle[$j] = array(
                    'descri' => $p['descri'],
                    'unid' => $p['unid'],
                    'cant' => $p['cant'],
                    'prec' => $p['prec'],
                    'subtotal' => $p['cant'] * $p['prec']
                );
                $j += 1;
            }

            $documentos[] = array(
                'ndoc' => $items[0]["ndoc"],
                'razo' => $items[0]["razo"],
                'vendedor' => $items[0]["nomv"],
                'fvto' => $items[0]["fevto"],
                'dias' => $items[0]["dias"],
                'fech' => $items[0]["fech"],
                'importe' => $items[0]["importe"],
                'impo' => $items[0]["impo"],
                'detalle' => ($detalle)
            );
        }
        $oimp->empresa = $_SESSION['gene_empresa'];
        $oimp->rucempresa = $_SESSION['gene_nruc'];
        $oimp->direccionempresa = $_SESSION['gene_ptop'];
        $oimp->fecha = $txtfechai;
        $oimp->fechat = $txtfechaf;
        $rutapdf = "Sysven-Creditos.pdf";
        $oimp->generarpdfcreditos($documentos, $rutapdf);
    }
    function indexresumencreditos()
    {
        return \view('cobranzas/informes/indexresumencreditos', ['titulo' => 'Listar Resumen Créditos']);
    }
    function listarresumencreditos(Request $request)
    {
        $ctas = new CtasporCobrar();
        $cmbformapago = $request->get("cmbformapago");
        $cmbalmacen = $request->get("cmbalmacen");
        $fecha = $request->get("txtfecha");
        $rpta = $ctas->listarresumencreditos($cmbformapago, $cmbalmacen, $fecha);
        // return view('cobranzas/informes/listarcobranzastodo', ['listado' => $rpta['lista']]);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $rpta['lista']], 200);
    }
}
