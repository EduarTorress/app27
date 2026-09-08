<?php

namespace App\Controllers;

use App\Models\Sucursal;
use Core\Http\Request;
use Core\Routing\Controller;

class SucursalController  extends Controller
{
    function __construct()
    {
    }
    static function listarsucursales()
    {
        // $tiendas = new Sucursal();
        // $lista = $tiendas->mostrar();
        $lista = $_SESSION['sucursales'];
        return $lista;
    }
    static function nombresucursal($idalma)
    {
        $tiendas = new Sucursal();
        $nombre = $tiendas->nombresucursal($idalma);
        return $nombre;
    }
    public function index()
    {
        $titulo = 'Sucursales';
        return view('admin/sucursales/index', ['titulo' => $titulo]);
    }
    function buscar()
    {
        $sucursal = new Sucursal();
        $lista = $sucursal->mostrar();
        return view('admin/sucursales/listasucursales', ['lista' => $lista]);
    }
    function create()
    {
        $titulo = 'Registrar Sucursal';
        return view('admin/sucursales/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $titulo = 'Editar Sucursal';
        $sucursal = new Sucursal();
        $datos = $sucursal->consultarsucursalxid($id);
        return view('admin/sucursales/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $datos[0], 'id' => $id]);
    }
    function store(Request $request)
    {
        try {
            $sucursal = new Sucursal();
            $sucursal->txtnombre = $request->get('txtnombre');
            $sucursal->txtdireccion = $request->get('txtdireccion');
            $sucursal->txtciudad = $request->get('txtciudad');
            $sucursal->cmbUbigeo = $request->get('cmbUbigeo');
            if ($sucursal->save()) {
                return response()->json(['message' => 'Registrado correctamente'], 200); // Created
            } else {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Error al registrar' . $error->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $sucursal = new Sucursal();
            $sucursal->txtnombre = $request->get('txtnombre');
            $sucursal->txtdireccion = $request->get('txtdireccion');
            $sucursal->txtciudad = $request->get('txtciudad');
            $sucursal->cmbUbigeo = $request->get('cmbUbigeo');
            $sucursal->txtidsucu = $request->get('txtidsucu');
            if ($sucursal->update()) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Ocurrió un error ' . $error->getMessage()], 500);
        }
    }
}
