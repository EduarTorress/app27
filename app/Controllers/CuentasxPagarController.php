<?php

namespace App\Controllers;

use App\Models\Caja;
use App\Models\CtasporCobrar;
use App\Models\CtasporPagar;
use App\Models\Ventas;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;

class CuentasxPagarController extends Controller
{
    function index()
    {
        return \view('cuentasxpagar/index', ['titulo' => 'Gestionar Pagos']);
    }
    function listarvtos(Request $request)
    {
        $datapost = array(
            'fechainicial' => $request->get('txtfechai'),
            'fechafinal' => $request->get('txtfechaf'),
            'ruc' => $_SESSION['gene_nruc'],
            'idproveedor' => $request->get('idproveedor'),
            'moneda' => $request->get('cmbmoneda')
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listarcuentasxpagar.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // var_dump($response);
        $data = json_decode($response, true);
        return view('cuentasxpagar/listarvtos', ['lista' => $data['result']]);
    }
    function indexlistarctasxpagartodo()
    {
        return \view('cuentasxpagar/informes/indexlistarctasxpagartodo', ['titulo' => 'Listar Cuentas x Pagar']);
    }
    function listartodasctasxpagar(Request $request)
    {
        // $ctas = new CtasporCobrar();
        // $cmbformapago = $request->get("cmbformapago");
        // $cmbalmacen = $request->get("cmbalmacen");
        // $fecha = $request->get("txtfecha");
        // $rpta = $ctas->listarcobranzastodo($cmbformapago, $cmbalmacen, $fecha);
        // // return view('cobranzas/informes/listarcobranzastodo', ['listado' => $rpta['lista']]);
        $datapost = array(
            'fechafinal' => $request->get('txtfechaf'),
            'ruc' => $_SESSION['gene_nruc'],
            'moneda' => $request->get('cmbmoneda')
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listartodasctasxpagar.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // var_dump($response);
        $data = json_decode($response, true);
        // return view('cuentasxpagar/listarvtos', ['lista' => $data['result']]);
        return response()->json(['message' => 'Se logró listar correctamente', 'listado' =>  $data['result']], 200);
    }
    function listarestadocuenta(Request $request)
    {
        $datapost = array(
            'idproveedor' => $request->get('idproveedor'),
            'cmbalmacen' => $request->get('cmbalmacen'),
            'ruc' => $_SESSION['gene_nruc'],
            'moneda' => $request->get('cmbmoneda')
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listarestadoctaxproveedor.php',
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // var_dump($response);
        $data = json_decode($response, true);
        return view('cuentasxpagar/listarestadocuenta', ['lista' => $data['result']]);
    }
    function registrarpagos(Request $request)
    {
        $ctas = new CtasporPagar();
        $ctas->txtdocumento = $request->get('txtdocumento');
        $ctas->txtfecha = $request->get('txtfecha');
        $ctas->txtimporte = $request->get('txtimporte');
        $ctas->cmbforma = $request->get('cmbforma');
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $rpta = $ctas->registrarpagosproveedores($detalle);
        return response()->json(['message' => $rpta['mensaje'], 'listado' => []], 200);
    }
}
