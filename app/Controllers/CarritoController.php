<?php

namespace App\Controllers\Tienda;

use App\Models\Producto;
use App\Services\CarritoService;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $total = CarritoService::total();
        $subtotal = CarritoService::subtotal();
        $descuento = CarritoService::descuento();
        return view("tienda/cart", [
            'carrito' => $carrito,
            'total' => $total,
            'subtotal' => $subtotal,
            'descuento' =>  $descuento
        ]);
    }
    public function agregarItem(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'cantidad');
        $validator->rule('required', 'medida_id');
        $validator->rule('required', 'color_id');
        $validator->rule('required', 'producto_id');
        if (!$validator->validate()) {
            $mensajes_error = "";
            foreach ($validator->errors() as $error) {
                $mensajes_error = $mensajes_error . $error[0];
            }
            $data = [
                'message' => $mensajes_error,
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        $producto_id = $request->get('producto_id');
        $medida_id = $request->get('medida_id');
        $color_id = $request->get('color_id');
        $cantidad = $request->get('cantidad');
        // Verificar que exista una presentacion 
        $presentacion = Presentacion::where('producto_id', $producto_id)
            ->where('color_id', $color_id)
            ->where('medida_id', $medida_id)
            ->first();
        if (is_null($presentacion)) {
            return response()->json(['message' => 'Presentación no existe', 409]);
        }

        // verificar si la presentacion fue agregar previamente
        $cantidad_actual = CarritoService::obtenerCantidadActual($presentacion) + $cantidad;

        // verificar el stock
        if ($cantidad_actual > $presentacion->stock) {
            return response()->json(['message' => 'Stock no disponible'], 409);
        }

        // agregar item
        CarritoService::agregar($presentacion, $cantidad_actual);

        $total = number_format(CarritoService::total(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, '0', STR_PAD_LEFT);

        return response()->json([
            'message' => 'Item agregado correctamente',
            'total' => $total,
            'numero_items' => $numero_items
        ], 200);
    }
    public function actualizarMinicart()
    {
        $carrito = session()->get('carrito', []);
        $total = CarritoService::total();
        return view('tienda/minicart', [
            'carrito' => $carrito,
            'total' => $total
        ]);
    }
    public function quitarItem(Request $request)
    {
        $indice = $request->get('indice');
        CarritoService::quitar($indice);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $subtotal = number_format(CarritoService::subtotal(), 2, '.', '');
        $descuento = number_format(CarritoService::descuento(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, "0", STR_PAD_LEFT);
        return response()->json([
            'message' => 'Item quitado correctamente',
            'total' => $total,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'numero_items' => $numero_items,
        ]);
    }
    public function tablaCarrito()
    {
        $carrito = session()->get('carrito', []);
        return view('tienda/tabla-carrito', ['carrito' => $carrito]);
    }
    public function actualizarCantidadItem(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'cantidad');
        $validator->rule('required', 'indice');
        if (!$validator->validate()) {
            return response()->json(['message' => 'Error de valicación'], 422);
        }

        $carrito = session()->get('carrito', []);
        $indice = $request->get('indice');
        $cantidad = $request->get('cantidad');

        $item = $carrito[$indice];

        $presentacion = Presentacion::find($item['presentacion']->id);

        if ($presentacion->stock < $cantidad) {
            return response()->json(['message' => 'Stock no disponible'], 409);
        }

        $carrito[$indice] = [
            'presentacion' => $presentacion,
            'cantidad' => $cantidad
        ];

        session()->set('carrito', $carrito);

        $total = number_format(CarritoService::total(), 2, '.', '');
        $subtotal = number_format(CarritoService::subtotal(), 2, '.', '');
        $descuento = number_format(CarritoService::descuento(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, "0", STR_PAD_LEFT);
        return response()->json([
            'message' => 'Cantidad actualizada correctamente',
            'total' => $total,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'numero_items' => $numero_items,
        ], 200);
    }
    public function aplicarCupon(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'codigo_cupon');
        if (!$validator->validate()) {
            return response()->json(['message' => 'Error de validacion']);
        }
        $codigo_cupon = $request->get('codigo_cupon');
        $cupon = Cupon::where('codigo', $codigo_cupon)->orderBy('fecha_vencimiento', 'DESC')->first(); // objeto del Modelo Cupon
        if (is_null($cupon)) {
            return response()->json(["message" => "Cupón no existe"], 409);
        }
        // mysql
        $fecha_hora_actual = date('Y-m-d H:i:s');
        $cupon = Cupon::where('id', $cupon->id)->where('fecha_vencimiento',  '>', $fecha_hora_actual)->first();
        if (is_null($cupon)) {
            return response()->json(["message" => "Cupón expirado"], 409);
        }

        // php
        // $fecha_hora_actual = strtotime("now"); // unixtime
        // $fecha_vencimiento = strtotime($cupon->fecha_vencimiento); // unixtime
        // if ($fecha_vencimiento < $fecha_hora_actual) {
        //     return response()->json(["message" => "Cupón expirado"]);
        // }

        // monto minimo
        $monto_minimo = $cupon->monto_minimo;
        $total = CarritoService::subtotal();

        if ($monto_minimo > $total) {
            return response()->json(["message" => "Monto mínimo para el cupón no alcanzado"], 409);
        }

        // OK
        CarritoService::aplicarCupon($cupon);
        $total = number_format(CarritoService::total(), 2, '.', '');
        $subtotal = number_format(CarritoService::subtotal(), 2, '.', '');
        $descuento = number_format(CarritoService::descuento(), 2, '.', '');
        $numero_items = str_pad(CarritoService::numeroItems(), 2, "0", STR_PAD_LEFT);
        return response()->json([
            'message' => 'Cupón aplicado correctamente', 'subtotal' => $subtotal,
            'descuento' => $descuento,
            'numero_items' => $numero_items,
            'total' => $total,
        ], 200);
    }
}
