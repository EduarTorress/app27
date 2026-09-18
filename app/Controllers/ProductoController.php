<?php

namespace App\Controllers;

use App\Models\Producto;
use App\Services\CarritoService;
use App\Services\Tipodecambio;
use Core\Foundation\Application;
use Valitron\Validator;
use Core\Routing\Controller;
use Core\Http\Request;
use App\Middlewares\AuthAdminMiddleware;
use App\Models\Presentacion;

class ProductoController extends Controller
{
    private $producto;
    function __construct()
    {
        $middleware = new AuthAdminMiddleware(['index']);
        $this->registerMiddleware($middleware);
        $this->producto = new Producto();
    }
    function index($opt)
    {
        $total = number_format(CarritoService::total(), 2, '.', '');
        $vista = \retornavista('admin/productos', 'index');
        session()->set("tiposel", $opt);
        switch ($opt) {
            case 0:
                $ctitulo = 'Lista P. / Un ITEM';
                break;
            case 1:
                $ctitulo = 'Lista P. / Var. ITEMS';
                break;
            case 3:
                $ctitulo = 'Lista Productos - Combos';
                break;
            case 4:
                $ctitulo = 'Lista Productos';
                return view('admin/productos/indexadmin', ['titulo' => $ctitulo, "totalpedido" => 0]);
                break;
            case 5:
                $ctitulo = 'Ajuste de Inventario';
                break;
            case 6:
                $ctitulo = 'Varillaje y Medición';
                break;
        }
        return view($vista, ['titulo' => $ctitulo, "totalpedido" => $total]);
    }
    function buscarProductoModal(Request $request)
    {
        $cgr = session()->get('carritogrr', 0);
        if ($cgr != 0) {
            $_SESSION['carritogr'] = $cgr;
        }
        $cgc = session()->get('carritogrc', 0);
        if ($cgc != 0) {
            $_SESSION['carritogc'] = $cgc;
        }
        $abuscar = $request->get('cbuscar');
        $opt = $request->get('option');
        $primeraLetra = letraDelSubdominio(obtenerUrlActual());
        if (is_numeric($primeraLetra)) {
            $isla = $primeraLetra;
        } else {
            $isla = 0;
            $opt = 'C';
        }
        $nd = session()->get("gene_dola");
        \session()->set('busquedaPV', $abuscar);
        $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $isla);
        \session()->set("listaPV", $lista);
        return view('components/listaproductosmodal', ['lista' => $lista]);
    }
    function buscar(Request $request)
    {
        $abuscar = $request->get('cbuscar');
        $opt =  $request->get('option');
        $nid = intval($request->get('cbuscar'));
        $nd =  session()->get('gene_dola');
        \session()->set('busqueda', $abuscar);
        $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $nid);
        $cvista = \retornavista('admin/productos', 're_listaproductos');
        \session()->set("lista", $lista);
        return view($cvista, ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Producto';
        return view('admin/productos/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function indexregistro()
    {
        $ctitulo = "Registrar Producto";
        $vista = \retornavista('admin/productos', 'vistaproducto');
        return view($vista, ['titulo' => $ctitulo]);
    }
    function registrarProducto(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtStockMin");
        // $validar->rule("required", "txtStockMax");
        $validar->rule("required", "txtporcpreces");
        $validar->rule("required", "txtporcprecem");
        $validar->rule("required", "txtporcprecma");
        $validar->rule("required", "txtcostosig");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $this->producto->txtdescrip = trim($request->get('txtdescrip'));
        $estadoexis = $this->producto->verificarsiyaexiste($request->get("cmbmarca"));
        if (count($estadoexis['listado']) > 0) {
            $data = ["errors" => ['El producto ya existe']];
            return response()->json($data, 422);
        }
        $datos = array(
            "txtcodigo" => $request->get("txtcodigo"),
            "txtdescrip" => $request->get("txtdescrip"),
            "cmbunidad" => $request->get("cmbunidad"),
            "txtcostosig" => $request->get("txtcostosig"),
            "txtcoston" => $request->get("txtcoston"),
            "txtpeso" => $request->get("txtpeso"),
            "cmbcategoria" => $request->get("cmbcategoria"),
            "cmbmarca" => $request->get("cmbmarca"),
            "cmbtipp" => $request->get("cmbtipp"),
            "txtcostot" => $request->get("txtcostot"),
            "cmbMoneda" => $request->get("cmbMoneda"),
            "txtcomisione" => $request->get("txtcomisione"),
            "txtcomisionc" => $request->get("txtcomisionc"),
            "txtporcprecma" => $request->get("txtporcprecma"),
            "txtporcpreces" => $request->get("txtporcpreces"),
            "txtporcprecem" => $request->get("txtporcprecem"),
            "txtprecioma" => $request->get("txtprecioma"),
            "txtprecioe" => $request->get("txtprecioe"),
            "txtpreciome" => $request->get("txtpreciome"),
            "txtStockMax" => $request->get("txtStockMax"),
            "txtStockMin" => $request->get("txtStockMin"),
            "dolar" =>  session()->get('gene_dola'),
            "nidusua" => session()->get('usuario_id'),
            "cmbgrupo" => $request->get('cmbgrupo')
        );
        if ($this->producto->registrarProducto($datos)) {
            return response()->json(['message' => 'Producto registrado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Error al registrar Producto'], 422);
        };
    }
    function consultarProductoPorID(Request $request)
    {
        $datosProducto = array(
            'idart' => $request->get('idart'),
            'idcat' => $request->get('idcat'),
            'idmar' => $request->get('idmar'),
            'unid' => $request->get('unid'),
            'idgrupo' => $request->get('idgrupo'),
            'descri' => $request->get('descri'),
            'codigo' => $request->get('codigo'),
            'peso' => $request->get('peso'),
            "idflete" => $request->get('idflete'),
            "prod_smin" => $request->get('prod_smin'),
            "prod_smax" => $request->get('prod_smax'),
            "costocigv" => $request->get('costocigv'),
            "costosigv" => $request->get('costosigv'),
            "flete" => $request->get("flete"),
            "tmon" => $request->get("tmon"),
            'prod_come' => $request->get('prod_come'),
            'prod_comc' => $request->get('prod_comc'),
            'prod_uti1' => (!empty(floatval($request->get('prod_uti1'))) ? round((floatval($request->get('prod_uti1')) * 100) - 100, 6) : 0),
            'prod_uti2' => (!empty(floatval($request->get('prod_uti2'))) ? round((floatval($request->get('prod_uti2')) * 100) - 100, 6) : 0),
            'prod_uti3' => (!empty(floatval($request->get('prod_uti3'))) ? round((floatval($request->get('prod_uti3')) * 100) - 100, 6) : 0),
            'pre1' => $request->get('pre1'),
            'pre2' => $request->get('pre2'),
            'pre3' => $request->get('pre3'),
            'tipop' => $request->get('tipop'),
            'uldc' => (empty($request->get('uldc')) ? '' :  $request->get('uldc'))


            // prod_come => comisión efectivo
            // prod_comc=> comisión crédito

            // % Precio mayor => prod_uti1
            // % Precio especial => prod_uti2
            // % Precio menor => prod_uti3

            // Precio mayor => pre1
            // Precio especial => pre2
            // Precio menor => pre3
        );
        $titulo = 'Actualizar Producto';
        return view('admin/productos/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => '', 'datosProducto' => $datosProducto]);
    }
    function actualizar(Request $request)
    {
        // $validar = new Validator($request->getBody());
        // $validar->rule("required", "txtdescrip");
        // $validar->rule("required", "txtpeso");
        // if (!$validar->validate()) {
        //     $data = ["errors" => $validar->errors()];
        //     return response()->json($data, 422);
        // }
        // $this->producto->txtidart = $request->get("txtidart");
        // $this->producto->txtdescrip = $request->get("txtdescrip");
        // $this->producto->cmbunidad = $request->get("cmbunidad");
        // $this->producto->txtStockMin = $request->get("txtStockMin");
        // $this->producto->txtprecio = $request->get("txtprecio");
        // $this->producto->txtpeso = floatval($request->get("txtpeso"));
        // $registro = $this->producto->actualizarProducto();
        // if ($registro['estado'] == "1") {
        //     return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        // } else {
        //     return response()->json(['message' => 'Error al registrar Producto'], 422);
        // };
        $validar = new Validator($request->getBody());
        $validar->rule("required", "txtdescrip");
        $validar->rule("required", "txtStockMin");
        $validar->rule("required", "txtStockMax");
        $validar->rule("required", "txtporcpreces");
        $validar->rule("required", "txtporcprecem");
        $validar->rule("required", "txtporcprecma");
        $validar->rule("required", "txtcostosig");
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        $datos = array(
            "txtcodigo" => $request->get("txtcodigo"),
            "txtdescrip" => $request->get("txtdescrip"),
            "cmbunidad" => $request->get("cmbunidad"),
            "txtcostosig" => $request->get("txtcostosig"),
            "txtcoston" => $request->get("txtcoston"),
            "txtpeso" => $request->get("txtpeso"),
            "cmbcategoria" => $request->get("cmbcategoria"),
            "cmbmarca" => $request->get("cmbmarca"),
            "cmbtipp" => $request->get("cmbtipp"),
            "txtcostot" => $request->get("txtcostot"),
            "cmbMoneda" => $request->get("cmbMoneda"),
            "txtcomisione" => $request->get("txtcomisione"),
            "txtcomisionc" => $request->get("txtcomisionc"),
            "txtporcprecma" => $request->get("txtporcprecma"),
            "txtporcpreces" => $request->get("txtporcpreces"),
            "txtporcprecem" => $request->get("txtporcprecem"),
            "txtStockMax" => $request->get("txtStockMax"),
            "txtStockMin" => $request->get("txtStockMin"),
            "dolar" =>  session()->get('gene_dola'),
            "nidusua" => session()->get('usuario_id'),
            "txtprecioma" => $request->get("txtprecioma"),
            "txtprecioe" => $request->get("txtprecioe"),
            "txtpreciome" => $request->get("txtpreciome"),
            "cmbgrupo" => $request->get("cmbgrupo"),
            'idart' => $request->get("idart"),
            'nflete' => $request->get('nflete')
        );
        if ($this->producto->actualizarProducto($datos)) {
            return response()->json(['message' => 'Producto actualizado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Error al actualizar Producto'], 422);
        };
    }
    // public function darBaja($id, Request $request)
    // {
    //     try {
    //         if ($this->producto->darBaja($id)) {
    //             return response()->json(['message' => 'Eliminado correctamente'], 200);
    //         } else {
    //             return response()->json(['message' => 'Error al eliminar'], 500);
    //         }
    //     } catch (\Exception $error) {
    //         return response()->json(['message' => 'Error al eliminar'], 500);
    //     }
    // }
    // function obtenerPresentacion(Request $request)
    // {
    //     $id = $request->get('id');
    //     $presentacion = new Presentacion();
    //     $presentaciones = $presentacion->listar($id, 3.85);
    //     return response()->json($presentaciones, 200);
    // }
    function updateStock(Request $request)
    {
        $correlativo = SerieController::correlativo('1', 'AJ');
        if ($correlativo[0]['estado'] == 0) {
            $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
            return $rpta;
        }
        $idserie = $correlativo[0]['idserie'];
        $cndoc = $correlativo[0]['correlativo'];
        $cabecera = array(
            'ctdoc' => 'AJ',
            'cform' => 'E',
            'cndoc' => $cndoc,
            'dfecha' => date('Y-m-d'),
            'dfechar' => date('Y-m-d'),
            'cdetalle' => 'Ajuste de Inventarios',
            'nv' => '0',
            'nigv' => '0',
            'nt' => '0',
            'cndo2' => '',
            'cm' => 'S',
            'ndolar' => session()->get("gene_dola"),
            'ni' => session()->get("gene_igv"),
            'ctg' => 'K',
            'ccodp' => '2',
            'cmvto' => 'C',
            'nus' => session()->get('usuario_id'),
            'opt' => '0',
            'nidcodt' =>  $_SESSION['idalmacen'],
            'n1' => 0,
            'n2' => 0,
            'n3' => 0,
            'nitem' => 0,
            'npvta' => 0,
            'idserie' => $idserie
        );
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        foreach ($detalle as $d) {
            if ($d['ingreso'] == '') {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        }
        $rptareg = $this->producto->updateStock($cabecera, $detalle);
        return $rptareg;
    }
    function registrarvarillajeymedicion(Request $request)
    {
        $correlativo = SerieController::correlativo('1', 'VM');
        if ($correlativo[0]['estado'] == 0) {
            $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
            return $rpta;
        }
        $idserie = $correlativo[0]['idserie'];
        $cndoc = $correlativo[0]['correlativo'];
        $cabecera = array(
            'ctdoc' => 'VM',
            'cform' => 'E',
            'cndoc' => $cndoc,
            'dfecha' => date('Y-m-d'),
            'dfechar' => date('Y-m-d'),
            'cdetalle' => 'Varillaje y Medición',
            'nv' => '0',
            'nigv' => '0',
            'nt' => '0',
            'cndo2' => '',
            'cm' => 'S',
            'ndolar' => session()->get("gene_dola"),
            'ni' => session()->get("gene_igv"),
            'ctg' => 'K',
            'ccodp' => '2',
            'cmvto' => 'C',
            'nus' => session()->get('usuario_id'),
            'opt' => '0',
            'nidcodt' =>  $_SESSION['idalmacen'],
            'n1' => 0,
            'n2' => 0,
            'n3' => 0,
            'nitem' => 0,
            'npvta' => 0,
            'idserie' => $idserie
        );
        $detalle = json_decode($request->get("detalle"));
        $detalle = json_decode(json_encode($detalle), true);

        foreach ($detalle as $d) {
            if ($d['ingreso'] == '') {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        }
        $rptareg = $this->producto->registrarvarillajeymedicion($cabecera, $detalle);
        return $rptareg;
    }
    function consultarvtasxprod(Request $request)
    {
        $p = new Producto();
        $p->txtidart = $request->get('txtidart');
        $rpta = $p->consultarvtasxprod($request->get('cmbano'));
        return view('admin/productos/listarinformes', ['listado' => $rpta['listado']]);
    }
    function consultarcompxprod(Request $request)
    {
        $p = new Producto();
        $p->txtidart = $request->get('txtidart');
        $rpta = $p->consultarcompxprod($request->get('cmbano'));
        return view('admin/productos/listarinformes', ['listado' => $rpta['listado'], 'listacompras' => 'true']);
    }
    function verdetallecombo(Request $request)
    {
        $p = new Producto();
        $p->txtidart = intval($request->get('txtidart'));
        $rpta = $p->verdetallecombo();
        return view('admin/productos/verdetallecombo', ['listado' => $rpta['listado']]);
    }
    function anularProducto($idart)
    {
        $p = new Producto();
        $rptaestmov = $p->verificarmovimientos($idart);
        if ($rptaestmov['estado'] == '1') {
            return response()->json(['message' => 'No se pudo eliminar, porque tiene transacciones (Compras o Ventas)'], 400);
        }
        $rpta = $p->anularProducto($idart);
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } else {
            return response()->json(['message' => 'Ocurrió un error'], 400);
        }
    }
    function consultarstockxminimos(Request $request)
    {
        $p = new Producto();
        $rpta = $p->consultarstockxminimos();
        return view('admin/productos/listarstocksminimos', ['listado' => $rpta['listado']]);
    }
    // function buscarproductoparacombo(Request $request)
    // {
    //     $abuscar = $request->get('cbuscar');
    //     $opt = $request->get('option') == 'nombre' ?  1 : ($request->get('option') == 'codigo' ? 0 : 2);
    //     $nid = intval($request->get('cbuscar'));
    //     $nd = Tipodecambio::dtipocambiosistema();
    //     $lista = $this->producto->BuscarProductos($abuscar, $nd, $opt, $nid);
    //     $cvista = \retornavista('components/', 'listaproductomodalparacombo');
    //     return view($cvista, ['lista' => $lista]);
    // }
}
