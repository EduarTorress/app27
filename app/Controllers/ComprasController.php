<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\Compra;
use App\Models\NotasCredito;
use App\Models\OCompras;
use App\Models\Ventas;
use App\Services\CarritoService;
use Core\Foundation\Application;
use Core\Routing\Controller;
use Valitron\Validator;
use ZipArchive;

class ComprasController extends Controller
{
    function index()
    {
        $dctos = new DocumentoController();
        $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");
        return \view('compras/co_compras', ['titulo' => 'Registrar Otras Compras', 'lista' => $listadctos]);
    }
    function indexcompra()
    {
        // $datosproveedorp = session()->get('proveedor', []);
        $fechaa = date("Y-m-d");
        $idcompra = \session()->get('idcompra', 0);
        if ($idcompra > 0) {
            $ctitulo = 'Act. Compra';
        } else {
            $ctitulo = 'Regs. Compra';
        }
        $serie = \session()->get('cndoc', '');
        $num = \session()->get('num', '');
        $datosproveedor = array(
            'idprov' => \session()->get('idprov', 0),
            'razo' => \session()->get('razo', ''),
            'tdoc' => \session()->get('tdoc', 0),
            'rucc' => \session()->get('rucc', 0),
            'cndoc' => $serie,
            'num' => $num,
            'ndo2' => \session()->get('ndo2', ''),
            'mone' => \session()->get('mone', ''),
            'form' => \session()->get('form', ''),
            'alm' => \session()->get('alm',  $_SESSION['idalmacen']),
            'fech' => \session()->get('fechi', ''),
            'fecr' => \session()->get('fechf', ''),
            'optigv' => \session()->get('optigv', 'I')
            //DETALLE
        );
        $v = "R";
        return \view('compras/index', ['titulo' => $ctitulo, 'datosproveedor' => $datosproveedor, 'serie' => $serie, 'num' => $num, 'idcompra' => $idcompra, 'fechaa' => $fechaa, 'v' => $v]);
    }
    function grabarSesion(Request $request)
    {
        \session()->set('idprov', $request->get('idprov'));
        \session()->set('razo', $request->get('razo'));
        \session()->set('rucc', $request->get('ruc'));
        \session()->set('tdoc', $request->get('tdoc'));
        \session()->set('cndoc', $request->get('cndoc'));
        \session()->set('num', $request->get('num'));
        \session()->set('ndo2', $request->get('ndo2'));
        \session()->set('alm', $request->get('alm'));
        \session()->set('form', $request->get('form'));
        \session()->set('mone', $request->get('mone'));
        \session()->set('fechi', $request->get('fechi'));
        \session()->set('fechf', $request->get('fechf'));
        \session()->set('dolar', $request->get('dolar'));
        \session()->set('optigv', $request->get('optigv'));
    }
    function listardetalle()
    {
        $carritoc = session()->get('carritoc', []);
        $idcompra = \session()->get('idcompra', 0);
        if ($idcompra > 0) {
            $btn = 'Modificar';
        } else {
            $btn = 'Grabar';
        }
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items, 'btn' => $btn]);
    }
    function verificarsiyaesta($idart)
    {
        if (CarritoService::siestacompras($idart)) {
            return true;
        } else {
            return false;
        }
    }
    function agregaritem(Request $request)
    {
        $idart = $request->get('txtcodigo');
        if ($this->verificarsiyaesta($idart)) {
            $data = [
                'message' => 'Producto ya agregado a la compra',
                'rpta' => 'N'
            ];
            return response()->json($data, 422);
        }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser númerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        $validar->labels([
            'precio' => 'txtprecio',
            'cantidad' => 'txtcantidad'
        ]);
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $producto = array();
        $producto = array(
            'coda' => $request->get("txtcodigo"),
            'descri' => $request->get("txtdescripcion"),
            'unidad' => $request->get('txtunidad'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get("txtprecio"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'costo' => $request->get('costo')
        );

        CarritoService::agregarItemCompra($producto);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);

        $carritoc = session()->get('carritoc', []);
        $cvista = \retornavista('compras', 'detalle');

        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items,
        //     'carritoc' => session()->get("carritoc", [])
        // ], 200);
        return view($cvista, [
            'carritoc' => $carritoc,
            'total' => $total,
            'items' => $numero_items
        ]);
    }
    function quitaritem(Request $request)
    {
        $pos = $request->get('indice');
        CarritoService::quitarItemCompra($pos);
        $carritoc = session()->get('carritoc', []);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items]);
    }
    function LimpiarSesion()
    {
        session()->remove('carritoc');
        session()->remove('fusua');
        session()->remove('proveedor');
        session()->remove('idcompra');
        session()->remove('razo');
        session()->remove('tdoc');
        session()->remove('cndoc');
        session()->remove('num');
        session()->remove('ndo2');
        session()->remove('alm');
        session()->remove('form');
        session()->remove('mone');
        session()->remove('fechi');
        session()->remove('fechf');
        session()->remove('dolar');
        session()->remove('optigv');
    }
    function limpiar()
    {
        $this->LimpiarSesion();
        $carritoc = session()->get('carritoc', []);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
        $cvista = \retornavista('compras', 'detalle');
        return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items]);
    }
    function soloItem(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'cantidad' => floatval(($request->get('txtcantidad') <= 0.00) ? 1 : $request->get('txtcantidad')),
            'precio' => floatval(($request->get('txtprecio') <= 0.00) ? 1 : $request->get('txtprecio'))
        );
        CarritoService::editarProductoCompra($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function indexListaCompras()
    {
        return \view('compras/informes/cab_lcompras', ['titulo' => 'Listar Compras']);
    }
    function listarComprasXFecha(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbmoneda = $request->get('cmbmoneda');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $cmbFormaP = $request->get('cmbFormaP');
        $cmbtdoc = $request->get('cmbdcto');
        $compra = new Compra();
        $lista = $compra->listarComprasxFecha($dfi, $dff, $cmbmoneda, $cmbAlmacen, $cmbFormaP, $cmbtdoc);
        return \view('compras/informes/listacompras', ['listado' => $lista]);
    }
    function checkafecto(Request $request)
    {
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'checkafecto' => $request->get('marcado'),
        );
        CarritoService::editarProductocheckafecto($producto);
        return response()->json([
            'message' => 'Item actualizado correctamente',
            'array' => $producto
        ], 200);
    }
    function indexListaComprasPLE()
    {
        return \view('compras/informes/cab_lcomprasPLE', ['titulo' => 'Registro de compras']);
    }
    function listarComprasXFechaPLE(Request $request)
    {
        // $compra = new Compra();
        // $lista = $compra->listarComprasxFechaPLE($request->get('mes'), $request->get('ano'));
        // return \view('compras/informes/listacomprasPLE', ['listado' => $lista]);
        $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
        if ($multiempresa == 'N') {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc']
            );
        } else {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc'],
                'codt' => $_SESSION['idalmacen']
            );
        }
        $listado = $this->obtenerlistadople($datapost);
        return \view('compras/informes/listacomprasPLE', ['listado' => $listado]);
    }
    function exportarsire(Request $request)
    {
        // $compra = new Compra();
        // $listado = $compra->listarComprasxFechaPLE($request->get('mes'), $request->get('ano'));
        // $listadonc = $compra->registrocomprasnc($request->get('mes'), $request->get('ano'));
        $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
        if ($multiempresa == 'N') {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc']
            );
        } else {
            $datapost = array(
                'mes' => $request->get('mes'),
                'ano' => $request->get('ano'),
                'ruc' => $_SESSION['gene_nruc'],
                'codt' => $_SESSION['idalmacen']
            );
        }
        $listado = $this->obtenerlistadople($datapost);
        $listadonc = $this->obtenerlistadonotascreditople($datapost);

        $sire = "";
        $fechanota = "";
        $ndocnota = "";
        $serienota = "";
        $tiponota = "";
        foreach ($listado as $l) {
            if ($l['tdoc'] == '07' || $l['tdoc'] == '09') {
                foreach ($listadonc as $lnc) {
                    if ($l['ndoc'] == $lnc['ndoc']) {
                        $fechanota = $lnc['fech'];
                        $serienota = substr($lnc['ndoc'], 0, 4);
                        $ndocnota = substr($lnc['ndoc'], 5, 12);
                        $tiponota = $lnc['tdoc'];
                    }
                }
            }

            // <<Trim(rucempresa)>>|<<Trim(Empresa)>>|<<Periodo>>|<<car>>|<<fechae>>|<<Iif(tipocomp='14',fechae,fvto)>>|
            // <<tipocomp>>|<<Serie>>|<<Iif(fdua=0,'',fdua)>>|<<nrocomp>>|<<''>>|<<tipodocp>>|<<nruc>>|<<Alltrim(proveedor)>>|
            // <<Base>>|<<igv>>|<<exporta>>|<<igvex>>|<<inafecta>>|<<igvng>>|<<Exon>>|<<isc>>|<<icbper>>|<<otros>>|<<Total>>|
            // <<Mone>>|<<Iif(Moneda='S','',Tipocambio)>>|<<Iif(fechn=Ctod("01/01/0001"),'',fechn)>>|<<Iif(tipon='00','',tipon)>>|
            // <<Iif(Left(serien,1)='-','',Trim(serien))>>|<<''>>|<<Iif(Left(ndocn,1)='-','',Round(Val(ndocn),0))>>|<<Tipo>>|<<''>>|
            // <<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<''>>|<<Alltrim(Auto)>>|<<porcigv>>|<<''>>|<<Ccostos>>|<<Trim(ncta)>>|
            // <<Trim(ncta1)>>|

            $fdua = ($l['tdoc'] == 50 ? $request->get('ano') : '0000');
            $tipodoc = empty($l['ruc']) ? "6" : "1";
            $ruc = empty($l['ruc']) ? $l['dni'] : $l['ruc'];
            $porigv = (floatval($_SESSION['gene_igv']) * 100) - 100;
            //SI ES DNI ES 6 Y SI ES RUC ES 1;

            $sire .= trim($_SESSION['gene_nruc']) . "|" . trim($_SESSION['gene_empresa']) . "|" . trim($request->get('namemes')) . "|" . "" . "|" . $l['fech'] . "|" . ($l['tdoc'] == '14' ?  $l['feche'] :  $l['fechvto']) . "|"
                . $l['tdoc'] . "|" . $l['serie'] . "|" . ($fdua == '0000' ? '' : $fdua) . "|" . $l['ndoc'] . "|" . "" . "|"
                . trim($tipodoc) . "|" . trim($ruc) . "|" . trim($l['razo']) . "|" . $l['valor'] . "|" . $l['vigv'] . "|" . "0" . "|"
                . "0" . "|" . $l['inafecto'] . "|" . "0" . "|" . $l['exon'] . "|" . "0" . "|" . "0" . "|" . "0" .  "|" . $l['importe'] . "|"
                . $l['mone'] . "|" . ($l['mone'] == 'S' ? '' : $_SESSION['gene_dola']) . "|" . $fechanota . "|" . $tiponota . "|" . $serienota . "|" . $ndocnota . "|" . "" . "|"
                . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . "" . "|" . $l['auto'] . "|" . $porigv . "|" . "0" . "|" . "" . "|" . "" . "|" . "\n";
        }

        $namefile = 'LE' . $_SESSION['gene_nruc'] . trim($request->get('ano')) . trim($request->get('mes')) . '00080400021112';
        $rutasire = $namefile . ".txt";
        file_put_contents($rutasire, $sire);

        // #2 create zip archive
        $zip = new ZipArchive();
        $zipFile = $namefile . '.zip';
        if ($zip->open($zipFile, ZipArchive::CREATE)) {
            $zip->addFile($rutasire);
        }
        $zip->close();
    }
    function obtenerlistadople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarcomprasple.php',
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
        return $data['result'];
    }
    function obtenerlistadonotascreditople($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/API/listarcomprasncple.php',
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
        return $data['result'];
    }
    function indexlistacomprasxprov()
    {
        return \view('compras/informes/indexlistacomprasxprov', ['titulo' => 'Informes de Compras x Proveedor']);
    }
    function listacomprasxprov(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $txtidproveedor = $request->get('txtidproveedor');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $compra = new Compra();
        $lista = $compra->listarcomprasxproveedor($dfi, $dff, $txtidproveedor, $cmbAlmacen);
        $comprasbyNdoc = array();
        $ndoc = '';
        $i = 0;
        foreach ($lista as $l) {
            if ($i == 0) {
                $ndoc = $l['ndoc'];
                $comprasbyNdoc[$ndoc][$i] = $l;
                $i = $i + 1;
            } else {
                if ($ndoc == $l['ndoc']) {
                    $comprasbyNdoc[$ndoc][$i] = $l;
                    $i = $i + 1;
                } else {
                    $i = 0;
                    $ndoc = $l['ndoc'];
                    $comprasbyNdoc[$ndoc][$i] = $l;
                    $i = 1;
                }
            }
        }
        return \view('compras/informes/listacomprasxprov', ['listado' => $comprasbyNdoc]);
    }
    function validaranoactual($txtfechai, $txtfechar)
    {
        $anoactual = date('Y');
        $txtfechai = strtotime($txtfechai);
        $fechai = date('Y', $txtfechai);
        if ($fechai != $anoactual) {
            return 1;
        }
        $txtfechar = strtotime($txtfechar);
        $fechar = date('Y', $txtfechar);
        if ($anoactual != $fechar) {
            return 1;
        }
        return 0;
    }
    function grabar(Request $request)
    {
        $validarano = $this->validaranoactual($request->get('fechi'), $request->get('fechf'));
        if ($validarano == 1) {
            return response()->json(['errors' => 'El año de la fecha es diferente al actual'], 422);
        }
        $compra = new Compra();
        $existecompra = $compra->validarsicompraexiste($request->get('cndoc'), $request->get('idprov'));
        if (count($existecompra) > 0) {
            return response()->json(['errors' => 'Número de compra ya registrado previamente'], 422);
        }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        if (empty($_SESSION["carritoc"])) {
            return response()->json(['message' => 'Se requiere productos para registrar la compra'], 422);
        }

        if (!validarrucregistro($request->get("txtrucproveedor"), $request->get("tdoc"))) {
            return response()->json(['errors' => 'No se puede registrar una COMPRA a la misma empresa'], 422);
        }

        $compra = new Compra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";

        $cuentasxpagar = json_decode($request->get("cuentasxpagar"));
        $cuentasxpagar = json_decode(json_encode($cuentasxpagar), true);

        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitem" => str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT),
            'cmbtipodocumentocuentasxpagar' => $request->get('cmbtipodocumentocuentasxpagar'),
            'cuentasxpagar' => $cuentasxpagar,
            "igv" => $request->get("igv"),
            'pimpo' => $request->get('pimpo'),
            'actualizarprecios' => $request->get('actualizarprecios'),
            'exonerado' => empty($request->get('exonerado')) ? '' : $request->get('exonerado')
        );

        if ($compra->grabarCompra($cabecera)) {
            $this->LimpiarSesion();
            $carritoc = session()->get('carritoc', []);
            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('compras', 'detalle');
            return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items]);
        } else {
            return response()->json(['message' => 'Error al registrar compra'], 422);
        }
    }
    function buscarCompraPorId($idauto)
    {
        $compra = new Compra();
        $nrocompra = "";
        $this->LimpiarSesion();
        $carritoc = session()->get('carritoc', []);
        $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
        if ($tipocompraexon == 'N') {
            $lista = $compra->buscarCompraPorID($idauto);
        } else {
            $lista = $compra->buscarCompraPorIDcontipoexon($idauto);
        }
        // session()->set('idauto', $idauto);
        $i = 0;
        $montototal = 0;
        $subtotal = 0;
        foreach ($lista as $item) {
            if ($i == 0) {
                $datosproveedor = array(
                    'idauto' => $item['idauto'],
                    'alm' => $item['alma'],
                    'fech' => $item['fech'],
                    'fecr' => $item['fecr'],
                    'form' => $item['form'],
                    'tdoc' => $item['tdoc'],
                    'dolar' => $item['dolar'],
                    'tipo' => $item['tipo'],
                    'mone' => $item['mone'],
                    'razo' => $item['razo'],
                    'idprov' => $item['idprov'],
                    'ndo2' => $item['ndo2'],
                    'optigv' => $item['incl'],
                    'pimpo' => $item['pimpo'],
                    'fusua' => $item['fusua']
                    // 'detalle' => (isset($item['detalle'])) ? $item['detalle'] : '',
                );
                $nrocompra = $item['ndoc'];
                $idauto = $item['idauto'];
            }

            if ($tipocompraexon == 'N') {
                $carritoc[] = array(
                    'coda' => $item["idart"],
                    'descri' => $item["descri"],
                    'unidad' => $item['unid'],
                    'cantidad' => $item['cant'],
                    'precio' => $item["prec"],
                    'nreg' => $item["idkar"],
                    'idprov' => $item['idprov'],
                    'activo' => 'A',
                    'subtotal' => $item['prec'] * $item['cant']
                );
            } else {
                $checkafecto = (floatval($item['kar_tigv']) > 1 ? "true" : "false");
                $carritoc[] = array(
                    'coda' => $item["idart"],
                    'descri' => $item["descri"],
                    'unidad' => $item['unid'],
                    'cantidad' => $item['cant'],
                    'precio' => $item["prec"],
                    'nreg' => $item["idkar"],
                    'idprov' => $item['idprov'],
                    'activo' => 'A',
                    'subtotal' => $item['prec'] * $item['cant'],
                    'checkafecto' => $checkafecto
                );
            }
            $subtotal = $item['prec'] * $item['cant'];
            $montototal = $subtotal + $subtotal;
            $i++;
        }
        $items = $i;

        session()->set('proveedor', $datosproveedor);
        session()->set('carritoc', $carritoc);
        $titulo = 'Actualizar Compra' . ' ' . $nrocompra;

        // session()->set('nrocompra', $nrocompra);

        $serie = substr($nrocompra, 0, 4);
        $num = substr($nrocompra, 4);

        session()->set('idcompra', $idauto);

        $cvista = \retornavista('compras', 'index');
        $v = "M";

        \session()->set('idprov', $datosproveedor['idprov']);
        \session()->set('razo',  $datosproveedor['razo']);
        \session()->set('tdoc',  $datosproveedor['tdoc']);
        \session()->set('cndoc',  $serie);
        \session()->set('num', $num);
        \session()->set('ndo2',  $datosproveedor['ndo2']);
        \session()->set('alm',  $datosproveedor['alm']);
        \session()->set('form',  $datosproveedor['form']);
        \session()->set('mone',  $datosproveedor['mone']);
        \session()->set('fechi',  $datosproveedor['fech']);
        \session()->set('fechf',  $datosproveedor['fecr']);
        \session()->set('dolar',  $datosproveedor['dolar']);
        \session()->set('optigv',  $datosproveedor['optigv']);
        $fusua = date_create(date('Y-m-d', strtotime($datosproveedor['fusua'])));
        $factual = date_create(date('Y-m-d'));
        $interval = date_diff($fusua, $factual);
        $days = $interval->format("%a");
        $diasatraso = (empty($_SESSION['gene_diasedicion']) ? 3 : $_SESSION['gene_diasedicion']);
        if (floatval($days) >= intval($diasatraso)) {
            \session()->set('fusua',  $datosproveedor['fusua']);
        }

        if (count($carritoc) < 1) {
            header('Location: /ocompras/buscarcompra/' . $idauto);
            return;
        }
        return view($cvista, ['titulo' => $titulo, 'datosproveedor' => $datosproveedor, 'idcompra' => $idauto, 'serie' => $serie, 'num' => $num, 'v' => $v, 'carritoc' => $carritoc, 'items' => $items, 'total' => $montototal]);
    }
    function modificar(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "tdoc");
        $validar->rule("required", "cndoc");
        $validar->rule("required", "idprov");
        $validar->rule("required", "impo");
        // $validar->rule("required", "coda");
        $validar->rule("required", "form");
        $validar->rule("required", "mon");
        $validar->rule("required", "alm");
        // $validar->rule("required", "ndo2");
        $validar->rule("required", "dolar");
        $validar->rule("required", "igv");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        if (empty($_SESSION["carritoc"])) {
            return response()->json(['message' => 'Se requiere productos para registrar la compra'], 422);
        }

        $compra = new Compra();
        $var =  $request->get('deta');
        $deta = (isset($var)) ? $request->get('deta') : "";
        $cabecera = array(
            "tdoc" => $request->get("tdoc"),
            "cndoc" => $request->get("cndoc"),
            "form" => $request->get("form"),
            "fechi" => $request->get("fechi"),
            "fechf" => $request->get("fechf"),
            "deta" => $deta,
            "valor" => $request->get("valor"),
            "nigv" => $request->get("nigv"),
            "impo" => $request->get("impo"),
            "ndo2" => $request->get("ndo2"),
            "mon" => $request->get("mon"),
            "dolar" => $request->get("dolar"),
            //IGV 
            //CTG
            "idprov" => $request->get("idprov"),
            'txtproveedor' => $request->get('txtproveedor'),
            //CMVTO
            "nidus" => session()->get('usuario_id'),
            //OPT
            "alm" => $request->get("alm"),
            //N1
            //N2
            //N3
            "nitems" => str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT),
            "igv" => $request->get("igv"),
            "nidauto" => \session()->get('idcompra'),
            'pimpo' => $request->get('pimpo'),
            'actualizarprecios' => $request->get('actualizarprecios'),
            'exonerado' => empty($request->get('exonerado')) ? '' : $request->get('exonerado')
        );
        if ($compra->actualizarCompra($cabecera)) {
            $inventariocontroller = new InventarioController();
            $inventariocontroller->calcularstock();
            $this->LimpiarSesion();
            $carritoc = session()->get('carritoc', []);
            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);
            $cvista = \retornavista('compras', 'detalle');
            return view($cvista, ['carritoc' => $carritoc, 'total' => $total, 'items' => $numero_items]);
        } else {
            return response()->json(['message' => 'Error al modificar compra'], 422);
        }
    }
    function indexcompradproducto()
    {
        return \view('compras/informes/indexlistacdp', ['titulo' => 'Rotación de Productos - Compras']);
    }
    function listarcompradproducto(Request $request)
    {
        $dfi = $request->get('dfechai');
        $dff = $request->get('dfechaf');
        $cmbAlmacen = $request->get('cmbAlmacen');
        $compra = new Compra();
        $lista = $compra->listarComprasxProducto($dfi, $dff, $cmbAlmacen);
        return \view('compras/informes/re_lcomprasdproducto', ['listado' => $lista]);
    }
    function indexocompra()
    {
        $titulo = 'Registrar Otras Compras';
        $dctos = new DocumentoController();
        $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");
        return view('ocompras/index', ['titulo' => $titulo, 'listadctos' => $listadctos]);
    }
    function registrarocompra(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idprov");
        $validar->rule("required", "cndoc1");
        $validar->rule("required", "cndoc2");

        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $cuentasxpagar = json_decode($request->get("cuentasxpagar"));
        $cuentasxpagar = json_decode(json_encode($cuentasxpagar), true);
        $datosregistro = [
            'idprov' => $request->get('idprov'),
            'cmbtdoc' => $request->get('cmbtdoc'),
            'cndoc1' => $request->get('cndoc1'),
            'cndoc2' => $request->get('cndoc2'),
            'txtfechai' => $request->get('txtfechai'),
            'txtfechar' => $request->get('txtfechar'),
            'txtfechavto' => $request->get('txtfechavto'),
            'cmbformapago' => $request->get('cmbformapago'),
            'txttipocambio' => $request->get('txttipocambio'),
            'moneda' => $request->get('moneda'),
            'tipogasto' => $request->get('tipogasto'),
            'nt1' => $request->get('nt1'),
            'nt2' => $request->get('nt2'),
            'nt3' => $request->get('nt3'),
            'nt4' => $request->get('nt4'),
            'nt5' => $request->get('nt5'),
            'nt6' => $request->get('nt6'),
            'nt7' => $request->get('nt7'),
            'nt8' => $request->get('nt8'),
            'nidcta1' => $request->get('nidcta1'),
            'nidcta2' => $request->get('nidcta2'),
            'nidcta3' => $request->get('nidcta3'),
            'nidcta4' => $request->get('nidcta4'),
            'nidcta5' => $request->get('nidcta5'),
            'nidcta6' => $request->get('nidcta6'),
            'nidcta7' => $request->get('nidcta7'),
            'nidcta8' => $request->get('nidcta8'),
            'ct1' => $request->get('ct1'),
            'ct2' => $request->get('ct2'),
            'ct3' => $request->get('ct3'),
            'ct4' => $request->get('ct4'),
            'ct5' => $request->get('ct5'),
            'ct6' => $request->get('ct6'),
            'ct7' => $request->get('ct7'),
            'ct8' => $request->get('ct8'),
            'txtreferencia' => $request->get('txtreferencia'),
            'cuentasxpagar' => $request->get('cuentasxpagar'),
            'cmbtipodocumentocuentasxpagar' => $request->get('cmbtipodocumentocuentasxpagar'),
            'cuentasxpagar' => $cuentasxpagar
        ];
        $oCompra = new OCompras();
        if ($oCompra->registrar($datosregistro)) {
            return response()->json(['message' => 'Se registro correctamente', 'ndoc' => $request->get('cndoc1') . $request->get('cndoc2')], 200);
        } else {
            return response()->json(['message' => 'No se generó satisfactoriamente', 'ndoc' => ''], 422);
        }
    }
    function buscarOCompraPorID($idauto)
    {
        $ocompras = new OCompras();

        $datos = $ocompras->buscarxid($idauto);
        $titulo = 'Actualizar ' . $datos[0]['ndoc'];

        $serie = substr($datos[0]['ndoc'], 0, 4);
        $num = substr($datos[0]['ndoc'], 4, 12);

        $dctos = new DocumentoController();
        $listadctos = $dctos->Obtenerdctosocompras($cbuscar = "");

        $cvista = \retornavista('ocompras', 'index');

        return view($cvista, [
            'titulo' => $titulo,
            'idautocompra' => $idauto,
            'serie' => $serie,
            'num' => $num,
            'datos' => $datos,
            'listadctos' => $listadctos
        ]);
    }
    function modificarocompra(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "idprov");
        $validar->rule("required", "cndoc1");
        $validar->rule("required", "cndoc2");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }

        $datosregistro = [
            'idautocompra' => $request->get('idautocompra'),
            'idprov' => $request->get('idprov'),
            'cmbtdoc' => $request->get('cmbtdoc'),
            'cndoc1' => $request->get('cndoc1'),
            'cndoc2' => $request->get('cndoc2'),
            'txtfechai' => $request->get('txtfechai'),
            'txtfechar' => $request->get('txtfechar'),
            'txtfechavto' => $request->get('txtfechavto'),
            'cmbformapago' => $request->get('cmbformapago'),
            'txttipocambio' => $request->get('txttipocambio'),
            'moneda' => $request->get('moneda'),
            'tipogasto' => $request->get('tipogasto'),
            'nt1' => $request->get('nt1'),
            'nt2' => $request->get('nt2'),
            'nt3' => $request->get('nt3'),
            'nt4' => $request->get('nt4'),
            'nt5' => $request->get('nt5'),
            'nt6' => $request->get('nt6'),
            'nt7' => $request->get('nt7'),
            'nt8' => $request->get('nt8'),
            'nidcta1' => $request->get('nidcta1'),
            'nidcta2' => $request->get('nidcta2'),
            'nidcta3' => $request->get('nidcta3'),
            'nidcta4' => $request->get('nidcta4'),
            'nidcta5' => $request->get('nidcta5'),
            'nidcta6' => $request->get('nidcta6'),
            'nidcta7' => $request->get('nidcta7'),
            'nidcta8' => $request->get('nidcta8'),
            'idv1' => $request->get('idv1'),
            'idv2' => $request->get('idv2'),
            'idv3' => $request->get('idv3'),
            'idv4' => $request->get('idv4'),
            'idv5' => $request->get('idv5'),
            'idv6' => $request->get('idv6'),
            'idv7' => $request->get('idv7'),
            'idv8' => $request->get('idv8'),
            'ct1' => $request->get('ct1'),
            'ct2' => $request->get('ct2'),
            'ct3' => $request->get('ct3'),
            'ct4' => $request->get('ct4'),
            'ct5' => $request->get('ct5'),
            'ct6' => $request->get('ct6'),
            'ct7' => $request->get('ct7'),
            'ct8' => $request->get('ct8'),
            'txtreferencia' => $request->get('txtreferencia')
        ];
        $oCompra = new OCompras();
        if ($oCompra->modificar($datosregistro)) {
            return response()->json(['message' => 'Se modificó correctamente', 'ndoc' => $request->get('cndoc1') . $request->get('cndoc2')], 200);
        } else {
            return response()->json(['message' => 'No se modificó satisfactoriamente', 'ndoc' => ''], 422);
        }
    }
    function indexnotascredito()
    {
        $ctitulo = "Nota de Crédito x Compra";
        return view('notascreditocompra/index', ['titulo' => $ctitulo]);
    }
    function listarcomprastonota(Request $request)
    {
        $idproveedor = $request->get('idproveedor');
        $compras = new Compra();
        $listado = $compras->consultarcomprasporproveedor($idproveedor);
        return view('notascreditocompra/tm_listacompras', [
            "listado" => $listado
        ]);
    }
    function listardetallenota(Request $request)
    {
        $idauto = $request->get('idauto');
        $ventas = new Ventas();
        $listado = $ventas->consultarDetalleVtaDirecta($idauto);
        return view('notascreditocompra/detalle', [
            "listado" => $listado
        ]);
    }
    function registrarnotacredito(Request $request)
    {
        if (empty(session()->get('usuario_id'))) {
            $data = ["errors" => "Sesión vacía"];
            return response()->json($data, 422);
        }
        $nc = new NotasCredito();
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);
        $nc->cndoc = $request->get('txtndocnotacredito');
        $nc->ctdoc = "07";
        $nc->cform = $request->get("form");
        $nc->dfecha = $request->get("fech");
        $nc->cdetalle = $request->get("cmbMotivo");
        $nc->nvalor = $request->get("subtotal");
        $nc->nigv = $request->get("igv");
        $nc->nt = $request->get("total");
        $nc->cndo2 = $request->get("cndo2v");
        $nc->prov = $request->get("razo");
        $nc->nidprov = $request->get("idprov");
        $nc->nitems = count($detalle);
        $nc->ntotal = $request->get("total");
        $nc->nidauto = $request->get("idauto");
        $nc->dfechavv = $request->get("fechvv");
        $nc->nalma = $_SESSION['idalmacen'];
        $nc->nidcodt = $_SESSION['idalmacen'];
        $rpta = $nc->registrarncporcompra($detalle);
        if ($rpta['estado'] == "1") {
            return response()->json(['message' => $rpta['mensaje'], 'ndoc' => $rpta['ndoc']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje']], 422);
        }
    }
    function indexlistacomprasmodificadas()
    {
        $ctitulo = "Lista de Compras Modificadas";
        return view('compras/informes/indexlistacomprasmodificadas', ['titulo' => $ctitulo]);
    }
    function listarcomprasmodificadas(Request $request)
    {
        $compras = new Compra();
        $listado = $compras->listarcomprasmodificadas();
        return view('compras/informes/listacomprasmodificadas', [
            "listado" => $listado
        ]);
    }
    function obtenerdetallexdocumento($datapost)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://companiasysven.com/app88/parsearxml.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $datapost,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/xml"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }
    function importarcompraxarchivo(Request $request)
    {
        session()->set('carritoc', []);
        $archivoxml = $request->get('archivo');
        $comprobante = ($this->obtenerdetallexdocumento($archivoxml));
        $i = 0;
        foreach ($comprobante['carrito_de_compras'] as $item) {
            $c[] = array(
                'indice' => $i++,
                'coda' => 0,
                'descri' => $item["descripcion"],
                'unidad' => $item['unidad'],
                'cantidad' => $item['cantidad'],
                'precio' => $item["precio"],
                'preciocopia' => $item['precio'],
                'nreg' => 0,
                'idprov' => 0,
                'subtotal' => $item['precio'] * $item['cantidad'],
                'activo' => 'A',
                'checkafecto' => "false"
            );
        }
        session()->set('carritoc', $c);
        return response()->json(['message' => $comprobante], 200);
    }
    function agregaritemxposicion(Request $request)
    {
        // $idart = $request->get('txtcodigo');
        // if ($this->verificarsiyaesta($idart)) {
        //     $data = [
        //         'message' => 'Producto ya agregado a la compra',
        //         'rpta' => 'N'
        //     ];
        //     return response()->json($data, 422);
        // }
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtprecio")->message('Precio es obligatorio');
        $validar->rule("required", "txtcantidad")->message('Cantidad es obligatoria');
        $validar->rule("numeric", "txtprecio")->message('El Precio debe ser númerico');
        $validar->rule("numeric", "txtcantidad")->message('Cantidad debe de ser númerico');
        $validar->rule("min", "txtcantidad", 1)->message('La Cantidad debe de ser mayor a 0');
        $validar->labels([
            'precio' => 'txtprecio',
            'cantidad' => 'txtcantidad'
        ]);
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $producto = array();
        $producto = array(
            'indice' => $request->get('indice'),
            'coda' => $request->get("txtcodigo"),
            'descri' => $request->get("txtdescripcion"),
            'unidad' => $request->get('txtunidad'),
            'cantidad' => $request->get('txtcantidad'),
            'precio' => $request->get("txtprecio"),
            'precio1' => $request->get("precio1"),
            'precio2' => $request->get("precio2"),
            'precio3' => $request->get("precio3"),
            'stock' => $request->get('stock'),
            'costo' => $request->get('costo'),
            'presentaciones' => $request->get('presentaciones'),
            'cantequi' => $request->get('cantequi'),
            'presseleccionada' => $request->get('presseleccionada')
        );

        CarritoService::agregarItemCompraxposicion($producto);
        $total = number_format(CarritoService::totalCompra(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItemsCompra(), 2, '0', STR_PAD_LEFT);

        $carritoc = session()->get('carritoc', []);
        $cvista = \retornavista('compras', 'detalle');

        // return response()->json([
        //     'message' => 'Item agregado correctamente',
        //     'total' => $total,
        //     'numero_items' => $numero_items,
        //     'carritoc' => session()->get("carritoc", [])
        // ], 200);
        $checknodescontarstock = \session()->get('checknodescontarstock', 'false');

        return view($cvista, [
            'carritoc' => $carritoc,
            'total' => $total,
            'items' => $numero_items,
            'checknodescontarstock' => $checknodescontarstock
        ]);
    }
    function indeximportarfotos()
    {
        return \view('compras/informes/indexlistafotos', ['titulo' => 'Importar Compras x Fotos']);
    }
    function listarfotos(Request $request)
    {
        $dfi = date('Y-m-d', strtotime(str_replace('-', '/', $request->get('dfechai'))));
        $dff =  date('Y-m-d', strtotime(str_replace('-', '/', $request->get('dfechaf'))));
        // $cmbmoneda = $request->get('cmbmoneda');
        // $cmbAlmacen = $request->get('cmbAlmacen');
        // $cmbFormaP = $request->get('cmbFormaP');
        // $cmbtdoc = $request->get('cmbdcto');
        // $compra = new Compra();
        // $lista = $compra->listarComprasxFecha($dfi, $dff, $cmbmoneda, $cmbAlmacen, $cmbFormaP, $cmbtdoc);
        $path    = $_SERVER['DOCUMENT_ROOT'] . '/img/compras/norbil';
        $files = array_diff(scandir($path), array('.', '..'));
        $listado = [];
        foreach ($files as $f) {
            $fechafoto = date('Y-m-d', filectime($path . "/" . $f));
            if ($fechafoto >= $dfi  && $fechafoto <= $dff) {
                array_push($listado, $f);
            }
        }
        return \view('compras/informes/listarfotos', ['listado' => $listado]);
    }
    function importarfoto()
    {
        $carpeta = "correa";
        if (session()->get("gene_nruc") == '10470458530') {
            $carpeta = "norbil";
        } else {
            $carpeta = "walter";
        }
        try {
            $ruta = $_SERVER['DOCUMENT_ROOT'] . '/img/compras/' . $carpeta;
            $files = array_diff(scandir($ruta), array('.', '..'));
            $cantfiles = count($files);
            if ($cantfiles == 0) {
                $cantfiles = 1;
            } else {
                $cantfiles = $cantfiles + 1;
            }
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $tempname1 = $_FILES['txtimage']['tmp_name'];
            move_uploaded_file($tempname1, $ruta . '/' . $cantfiles . '.jpeg');
            return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error ' . $error->getMessage()], 500);
        }
    }
}
