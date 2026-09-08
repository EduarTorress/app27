<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Dashboard;
use App\Models\DatosGlobales;
use Core\Routing\Controller;
use App\Models\ValorIGV;
use App\Models\Ventas;
use Core\Http\Request;
use Phpml\Regression\LeastSquares;
use Valitron\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $middleware = new AuthAdminMiddleware(['inicio']);
        $this->registerMiddleware($middleware);
    }
    public function inicio()
    {
        return view('admin/info');
    }
    public function index()
    {
        if (empty(session()->get('usuario_id'))) {
            return view('auth/login');
        }

        return view('admin/dashboard');
    }
    public function obtenerDatos(Request $request)
    {
        $validar = new Validator($request->getBody());
        $validar->rule("required", "alm")->message('Almacen es Obligatorio');
        $validar->rule("required", "serie")->message('Serie es Obligatorio');
        if (!$validar->validate()) {
            $data = ["errors" => $validar->errors()];
            return response()->json($data, 422);
        }
        \session()->set('alm', $request->get('alm'));
        \session()->set('serie', $request->get('serie'));
        // return view('admin/dashboard');
        return response()->json([
            'message' => 'Se grabo correctamente'
        ], 200);
    }
    public function obtenerpanel()
    {
        $dashboard = new Dashboard();
        $totalproductos = $dashboard->totalproductos();
        $totalclientes = $dashboard->totalclientes();
        $totalventas = $dashboard->totalventas();
        $montoventassoles = $dashboard->montoventassoles();
        $montoventasdolares = $dashboard->montoventasdolares();
        $totalpedidos = $dashboard->totalpedidos();
        $totalventaspormes = $dashboard->totalventaspormes();
        $totalcompraspormes = $dashboard->totalcompraspormes();
        $totalpedidospormes = $dashboard->totalpedidospormes();
        $totalmontoventas = $dashboard->totalmontoventas();
        $totalmontocompras = $dashboard->totalmontocompras();
        $totalmontopedidos = $dashboard->totalmontopedidos();
        return view('layouts/panel', [
            'totalproductos' => $totalproductos,
            'totalclientes' => $totalclientes,
            'totalventas' => $totalventas,
            'montoventassoles' => $montoventassoles,
            'montoventasdolares' => $montoventasdolares,
            'totalpedidos' => $totalpedidos,
            'totalventaspormes' => $totalventaspormes,
            'totalpedidospormes' => $totalpedidospormes,
            'totalmontoventas' => $totalmontoventas,
            'totalcompraspormes' => $totalcompraspormes,
            'totalmontocompras' => $totalmontocompras,
            'totalmontopedidos' => $totalmontopedidos
        ]);
    }
    function calcularfechavto(Request $request)
    {
        $fecha = $request->get('txtfecha');
        if (empty($request->get('txtdias'))) {
            $dias = ' + ' . '0' . ' days';
        } else {
            $dias = ' + ' . $request->get('txtdias') . ' days';
        }
        $fechavto = strtotime($fecha . $dias);
        return date('Y-m-d', $fechavto);
    }
    public function indexprediccionventasxmes()
    {
        $titulo = 'Predicción de Ventas por Meses';
        return view('ai/indexprediccionventasxmes', ["titulo" => $titulo]);
    }
    public function listaprediccionventasxmes(Request $request)
    {
        $inv = new Ventas();
        $cmbForma = $request->get("cmbFormaP");
        $cmbtdoc = $request->get("cmbtdoc");
        $cmbAlmacen = $request->get("cmbAlmacen");
        $lista = $inv->listaprediccionventasxmes($cmbForma, $cmbtdoc, $cmbAlmacen);
        
        foreach ($lista as $row) {
            $meses[] = [(int)$row['mes']];;
            $importe[] = (float)$row['importe'];
        }
        // Predecir siguiente mes
        $siguientemes = ((int)date('m')) + 1;

        // Verificar que haya suficientes datos
        if (count($meses) < 6) {
            return view('/ai/listaprediccionventasxmes', ['listado' => $lista, 'siguientemes' => $siguientemes, 'prediction' => 0]);
        }

        // Entrenar modelo
        $regression = new LeastSquares();
        $regression->train($meses, $importe);
        $prediction = $regression->predict([$siguientemes]);

        return view('/ai/listaprediccionventasxmes', ['listado' => $lista, 'siguientemes' => $siguientemes, 'prediction' => round($prediction, 3)]);
    }
}
