<?php

namespace App\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Sucursal;
use Core\Http\Request;
use Core\Routing\Controller;

class InventarioController extends Controller
{
    public function indexkardex()
    {
        $titulo = 'Kardex x Producto';
        // $tiendas = SucursalController::listarsucursales();
        return view('inventarios/indexkardex', ["titulo" => $titulo]);
    }
    function listarkardex(Request $request)
    {
        $obj = new Inventario();
        $obj->ncoda = $request->get("ncoda");
        $obj->dfechaf = $request->get("dff");
        $obj->dfechai = $request->get("dfi");
        $obj->ntienda = $request->get("ntienda");
        $sucursal = new Sucursal();
        $lista = $obj->listarkardex();

        $calma = 0;
        $x = 0;
        $sw = "N";
        $ing = 0;
        $item = array();
        $egr = 0;
        $cm = "";
        foreach ($lista as $l) {
            if ($l['fech'] < $obj->dfechai) {
                if ($l['tipo'] == "C") {
                    $calma += $l['cant'];
                } else {
                    $calma -= $l['cant'];
                }
            } else {
                if ($x == 0) {
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => "",
                        "dcto" => "",
                        "razo" => "Stock Inicial",
                        "ingr" => 0,
                        "egre" => 0,
                        "saldo" => $calma,
                        "moneda" => "",
                        "precio" => 0,
                        "usua" => "",
                        "fusua" => "",
                        "usua1" => "",
                        "tipomvto" => ""
                    );
                }
                $x++;
                $sw = 'S';
                $nprecio = ($l['tipo'] == 'C' ? $l['prec'] * $l['igv'] : $l['prec']);
                if ($l['tipo'] == 'C') {
                    $calma = $calma + $l['cant'];
                    $ing += $l['cant'];
                    if (is_null($l['proveedor'])) {
                        if ($obj->ntienda == $l['ndo2']) {
                            $nh = $l['codt'];
                        } else {
                            $nh = intval($l['ndo2']);
                        }
                        $crazon = "Ingresa Desde " . ($nh > 0 ? $sucursal->nombresucursal($nh) : "");
                    } else {
                        $crazon = $l['proveedor'];
                    }
                    switch ($l['tdoc']) {
                        case '01':
                        case '09':
                            $cm = 'Compras';
                            break;
                        case 'II':
                            $cm = 'Inventarios';
                            break;
                        case 'AJ':
                            $cm = 'Ajustes';
                            break;
                        case 'TT':
                            $cm = 'Transferencias';
                            break;
                        case '99':
                            $cm = 'Reposiciones';
                            break;
                    }
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => $l['tdoc'],
                        "dcto" => $l['ndoc'],
                        "razo" => $crazon,
                        "ingr" => $l['cant'],
                        "egre" => 0,
                        "saldo" => $calma,
                        "moneda" => $l['cmoneda'],
                        "precio" => $nprecio,
                        "usua" => $l['usua'],
                        "fusua" => $l['fusua'],
                        "usua1" => $l['usua1'],
                        "tipomvto" => $cm
                    );
                } else {
                    $calma = $calma - $l['cant'];
                    $egr += $l['cant'];
                    if (is_null($l['cliente'])) {
                        $crazon = 'Salida A ' . (intval($l['ndo2']) > 0 ? $sucursal->nombresucursal($l['ndo2']) : '');
                    } else {
                        $crazon = $l['cliente'];
                    }
                    switch ($l['tdoc']) {
                        case '01':
                        case '03':
                        case '07':
                        case '08':
                        case '20':
                            $cm = 'Ventas';
                            break;
                        case 'TT':
                            $cm = 'Transferencias';
                            break;
                        case '99':
                            $cm = 'Reposiciones';
                            break;
                    }
                    $item[] = array(
                        "fecha" => $l['fech'],
                        "tdoc" => $l['tdoc'],
                        "dcto" => $l['ndoc'],
                        "razo" => $crazon,
                        "ingr" => 0,
                        "egre" => $l['cant'],
                        "saldo" => $calma,
                        "moneda" => $l['cmoneda'],
                        "precio" => $nprecio,
                        "usua" => $l['usua'],
                        "fusua" => $l['fusua'],
                        "usua1" => $l['usua1'],
                        "tipomvto" => $cm
                    );
                }
            }
        }
        if ($sw == 'N') {
            $opro = new Producto();
            $stock = $opro->calcularstockproducto($request->get("ncoda"), $request->get("ntienda"));
            $item[] = array(
                "fecha" => $request->get("dff"),
                "tdoc" => "",
                "dcto" => "",
                "razo" => "STOCK",
                "ingr" => 0,
                "egre" => 0,
                "saldo" => $stock,
                "moneda" => "",
                "precio" => 0,
                "usua" => "",
                "fusua" => "",
                "usua1" => "",
                "tipomvto" => ""
            );
        } else {
            $item[] = array(
                "fecha" => $request->get("dff"),
                "tdoc" => "",
                "dcto" => "",
                "razo" => "TOTALES",
                "ingr" => $ing,
                "egre" => $egr,
                "saldo" => 0,
                "moneda" => "",
                "precio" => 0,
                "usua" => "",
                "fusua" => "",
                "usua1" => "",
                "tipomvto" => ""
            );
        }
        return view('/inventarios/listakardex', ['listado' => $item]);
    }
    public function indexexistalmacen()
    {
        $titulo = 'Lista Stock x Almacen';
        return view('inventarios/indexexistalmacen', ["titulo" => $titulo]);
    }
    public function listarexistenciaalmacen(Request $request)
    {
        $inv = new Inventario();
        $inv->dfechaf = $request->get("txtfecha");
        $cmbAlmacen = $request->get("cmbAlmacen");
        $lista = $inv->listarexistenciaalmacen($cmbAlmacen);
        return view('/inventarios/listaexisalmacen', ['listado' => $lista, 'nalma' => $cmbAlmacen]);
    }
    public function indexlistastockvalorizado()
    {
        $titulo = 'Lista Stock Valorizado';
        return view('inventarios/indexlistastockvalorizado', ["titulo" => $titulo]);
    }
    public function listarstockvalorizado(Request $request)
    {
        $datapost = array(
            'fecha' => $request->get('txtfecha'),
            'variaspresentaciones' => 'N',
            'ruc' => $_SESSION['gene_nruc'],
            'tipocosto' => $request->get("tipocosto"),
            'cmbalmacen' => $request->get('cmbalmacen')
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://companiasysven.com/API/listarstockvalorizado.php',
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
        $data = json_decode($response, true);
        // var_dump($data);
        // // $inv = new Inventario();
        // // $inv->dfechaf = $request->get("txtfecha");
        // // $lista = $inv->listarexistenciaalmacen();
        // $lista=$data['result'];
        // $sa_to = 0;
        // $cost = 0;
        // // $nsaldo = 0;
        // // $saldo = 0;
        // // $xcant = 0;
        // $toti = 0;
        // $xdebe = 0;
        // $xprec = 0;
        // $cost = 0;
        // $inventario = [];
        // $listagrupada = agruparlistaporvalor($lista, 'idart');
        // // echo '<pre>';
        // // var_dump($listagrupada);
        // // echo '</pre>';
        // $idart = 0;
        // $producto = "";
        // $unidad = "";
        // foreach ($listagrupada as $l) {
        //     $stock = 0;
        //     foreach ($l as $inve) {
        //         $idart = $inve['idart'];
        //         $producto = $inve['descri'];
        //         $unidad = $inve['unid'];
        //         if ($inve['tipo'] == 'V') {
        //             $stock = $stock - $inve['cant'];
        //             $sa_to = $sa_to - ($cost * $inve['cant']);
        //         } else {
        //             $stock = $stock + $inve['cant'];
        //             $xprec = $inve['precio'];
        //             if ($xprec == '0') {
        //                 $xprec = $cost;
        //             }
        //             $toti = $toti + (($inve['cant'] == '0' ? 1 : $inve['cant']) * $xprec);
        //             $xdebe = Round($toti, 2);
        //             if ($stock < 0) {
        //                 if ($inve['cant'] <> 0) {
        //                     $sa_to = Round($stock * $xprec, 2);
        //                 } else {
        //                     $sa_to = $sa_to + $xdebe;
        //                 }
        //             } else {
        //                 if ($sa_to < 0) {
        //                     $sa_to = Round($stock * $xprec, 2);
        //                 } else {
        //                     if ($sa_to == 0) {
        //                         $sa_to = Round($stock * $xprec, 2);
        //                     } else {
        //                         $sa_to = Round($stock * $xprec, 2);
        //                     }
        //                 }
        //             }
        //             if ($toti <> 0) {
        //                 $cost = ($stock <> 0 ? Round($sa_to / $stock, 4) : $xprec);
        //             }
        //             if ($cost == 0) {
        //                 $cost = $xprec;
        //             }
        //         }
        //     }
        //     $i = [
        //         'idart' => $idart,
        //         'descri' => $producto,
        //         'unid' => $unidad,
        //         'stock' => round($stock, 4),
        //         'costo' => round($cost, 4),
        //         'importe' => round($stock * $cost, 4)
        //     ];
        //     array_push($inventario, $i);
        // }
        // // foreach ($listagrupada as $l) {
        // //     foreach ($l as $inve) {
        // //         if ($inve['tipo'] == 'V') {
        // //             $saldo = $saldo - $inve['cant'];
        // //             $sa_to = $sa_to - ($cost * $inve['cant']);
        // //         } else {
        // //             $xprec = $inve['precio'];
        // //             if ($xprec == '0') {
        // //                 $xprec = $cost;
        // //             }
        // //             $toti = $toti + (($inve['cant'] == '0' ? 1 : $inve['cant']) * $xprec);
        // //             $xdebe = Round($toti, 2);
        // //             $saldo = $saldo + $inve['cant'];
        // //             if ($saldo < 0) {
        // //                 if ($inve['cant'] <> 0) {
        // //                     $sa_to = Round($saldo * $xprec, 2);
        // //                 } else {
        // //                     $sa_to = $sa_to + $xdebe;
        // //                 }
        // //             } else {
        // //                 if ($sa_to < 0) {
        // //                     $sa_to = Round($saldo * $xprec, 2);
        // //                 } else {
        // //                     if ($sa_to == 0) {
        // //                         $sa_to = Round($saldo * $xprec, 2);
        // //                     } else {
        // //                         $sa_to = Round($sa_to * $xprec, 2);
        // //                     }
        // //                 }
        // //             }
        // //             if ($toti <> 0) {
        // //                 $cost = ($saldo <> 0 ? Round($sa_to / $saldo, 4) : $xprec);
        // //             }
        // //             if ($cost == 0) {
        // //                 $cost = $xprec;
        // //             }
        // //         }
        // //     }
        // //     if ($saldo <> 0) {
        // //         // Insert Into inventario(idart, Descri, Unid, alma, costo)Values(xcoda, cdescri, cUnid, saldo, cost)
        // //         $i = [
        // //             'idart' => $inve['idart'],
        // //             'descri' => $inve['descri'],
        // //             'unid' => $inve['unid'],
        // //             'stock' => round($saldo,4),
        // //             'costo' => round($cost,4),
        // //             'importe' => round($saldo * $cost,4)
        // //         ];
        // //         array_push($inventario, $i);
        // //     }
        // // }
        return view('/inventarios/listarstockvalorizado', ['listado' => $data['result']]);
        // echo '<pre>';
        // var_dump($inventario);
        // echo '<pre>';
        // store 0 To sa_to, cost, nsaldo, saldo, toti, xdebe
        // xcoda = inve.idart
        // cdescri = inve.Descri
        // cUnid = inve.Unid
        // Store 0 To xcant, xprec, cost
        // Do While !Eof() And inve.idart = xcoda
        // 	If inve.Tipo = "V"
        // 		saldo = saldo - cant
        // 		sa_to = sa_to - (cost * cant)
        // 	Else
        // 		xprec = Precio
        // 		If xprec = 0  Then
        // 			xprec = cost
        // 		Endif
        // 		toti = toti + (Iif(inve.cant = 0, 1, inve.cant) * xprec)
        // 		xdebe = Round(Iif(inve.cant = 0, 1, inve.cant) * xprec, 2)
        // 		saldo = saldo + cant
        // 		If saldo < 0 Then
        // 			If inve.cant <> 0 Then
        // 				sa_to = Round(saldo * xprec, 2)
        // 			Else
        // 				sa_to = sa_to + xdebe
        // 			Endif
        // 		Else
        // 			If sa_to < 0 Then
        // 				sa_to = Round(saldo * xprec, 2)
        // 			Else
        // 				If sa_to = 0 Then
        // 					sa_to = Round(saldo * xprec, 2)
        // 				Else
        // 					sa_to = Round(sa_to + xdebe, 2)
        // 				Endif
        // 			Endif
        // 		Endif
        // 		If toti <> 0 Then
        // 			cost = Iif(saldo <> 0, Round(sa_to / saldo, 4), xprec)
        // 		Endif
        // 		If cost = 0 Then
        // 			cost = xprec
        // 		Endif
        // 	Endif
        // 	Select inve
        // 	Skip
        // Enddo
    }
    function indexlistaajustes()
    {
        $titulo = 'Ajustes de Inventario';
        return view('inventarios/indexlistaajustes', ["titulo" => $titulo]);
    }
    function listaajustes(Request $request)
    {
        $fechi = $request->get('txtfechai');
        $fechf = $request->get('txtfechaf');
        $inv = new Inventario();
        $listado = $inv->listarajustes($fechi, $fechf);
        return view('inventarios/listaajustes', ["listado" => $listado]);
    }
    function verdetalleajuste(Request $request)
    {
        $idauto = $request->get('idauto');
        $inv = new Inventario();
        $listado = $inv->verdetalleajuste($idauto);
        $data = ['listado' => $listado, 'estado' => $idauto];
        return response()->json($data, 200);
    }
    function calcularstock()
    {
        $inv = new Inventario();
        $inv->calcularstock();
        $data = ['mensaje' => 'Se calculó el Stock correctamente, por favor ingrese nuevamente al sistema', 'estado' => '1'];
        return response()->json($data, 200);
    }
    public function indexproductosmenosrotados()
    {
        $titulo = 'Lista Productos menos rotados';
        return view('inventarios/indexproductosmenosrotados', ["titulo" => $titulo]);
    }
    public function listaproductosmenosrotados(Request $request)
    {
        $inv = new Inventario();
        $lista = $inv->listarproductosmenosrotados($request->get('cmbalmacen'));
        return view('/inventarios/listaproductosmenosrotados', [
            'listado' => $lista,
            'nalma' => $request->get('cmbalmacen'),
            'fecha' => $request->get('txtfecha')
        ]);
    }
    public function indexcomparativohistorico()
    {
        $titulo = 'Comparativo Historico';
        return view('inventarios/indexcomparativohistorico', ["titulo" => $titulo]);
    }
    public function listacomparativohistorico(Request $request)
    {
        $inv = new Inventario();
        $lista = $inv->listacomparativohistorico($request->get('txtfecha'), $request->get('txtdias'), $request->get('stock'));
        return view('/inventarios/listacomparativohistorico', [
            'listado' => $lista,
            'nalma' => $request->get('cmbalmacen'),
            'fecha' => $request->get('txtfecha'),
            'dias' => $request->get('txtdias'),
        ]);
    }
    public function indexkardexgeneral()
    {
        $titulo = 'Kardex General';
        // $tiendas = SucursalController::listarsucursales();
        return view('inventarios/indexkardexgeneral', ["titulo" => $titulo]);
    }
    function listarkardexgeneral(Request $request)
    {
        $obj = new Inventario();
        $obj->dfechaf = $request->get("dff");
        $obj->dfechai = $request->get("dfi");
        $obj->ntienda = $request->get("ntienda");
        $lista = $obj->listarkardexgeneral();
        return view('/inventarios/listakardexgeneral', ['listado' => $lista]);
    }
}
