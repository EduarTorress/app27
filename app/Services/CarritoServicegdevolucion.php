<?php

namespace App\Services;

class CarritoServicegdevolucion
{
    public static function obtenerCantidadActual($presentacion): int
    {
        $carrito = session()->get('carritogd', []);
        foreach ($carrito as $item) {
            if ($item['presentacion']->id == $presentacion->id) {
                return $item['cantidad'];
            }
        }
        return 0;
    }
    public static function agregar($producto)
    {
        $carritov = session()->get('carritogd', []);
        $indice = false;
        $carritov[] = [
            'coda' => $producto['coda'],
            'descripcion' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'peso' => $producto['peso'],
            'stock' => $producto['stock'],
            'precio1' => 0,
            'precio2' => 0,
            'precio3' => 0,
            'costo' => 0,
            'nreg' => 0,
            'caant' => 0,
            'idcliente' => 0,
            'scop' => '',
            'presentaciones' => $producto['presentaciones'],
            'presseleccionada' => 0,
            'activo' => 'A'
        ];
        session()->set('carritogd', $carritov);
    }
    public static function buscarsiyaesta($idart)
    {
        $carrito = \session()->get("carritogd", []);
        $encontrado = \false;
        foreach ($carrito as $item) {
            if ($item['coda'] == $idart and $item['activo'] == 'A') {
                $encontrado = true;
            }
        }
        return $encontrado;
    }
    public static function editar($producto)
    {
        $carrito = session()->get('carritogd', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['cantidad'] = $producto['cantidad'];
        // $carrito[$indice]['unidad'] = $producto['unidad'];
        $carrito[$indice]['peso'] = $producto['peso'];
        $carrito[$indice]['scop'] = $producto['scop'];
        // $carrito[$indice]['cantequi'] = $producto['cantequi'];
        // $carrito[$indice]['presseleccionada'] = $producto['presseleccionada'];
        session()->set('carritogd', $carrito);
    }
    public static function numeroItems()
    {
        $carrito = session()->get('carritogd', []);
        $titems = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }
    public static function subtotal()
    {
        $carrito = session()->get('carritogd', []);
        $total = 0.00;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['peso'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }
    public static function total()
    {
        return self::subtotal();
    }
    public static function descuento()
    {
        return session()->get('descuento', 0.00);
    }
    public static function quitar($indice)
    {
        $carrito = session()->get('carritogd', []);
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['activo'] = 'I';
        session()->set('carritogd', $carrito);
    }
    public static function siesta($idart)
    {
        $valor = false;
        $carrito = \session()->get('carritogd', []);
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }
    public static function item($pos)
    {
        $carrito = \session()->get('carritogd', []);
        $itemcarrito = array();
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                $itemcarrito = array(
                    'coda' => $item['coda'],
                    'descripcion' => $item['descripcion'],
                    'unidad' => $item['unidad'],
                    'cantidad' => $item['cantidad'],
                    'peso' => $item['peso'],
                    'stock' => $item['stock'],
                    'nreg' => $item['nreg'],
                    'idcliente' => $item['idcliente'],
                    'activo' => 'A',
                    'poscpresentacion' => (isset($item['poscpresentacion'])) ?  $item['poscpresentacion'] : '',
                    'textopresentacion' => (isset($item['textopresentacion'])) ?  $item['textopresentacion'] : '',
                    'cantpresentacion' => (isset($item['cantpresentacion'])) ?  $item['cantpresentacion'] : '',
                    'precio1presentacion' => (isset($item['precio1presentacion'])) ?  $item['precio1presentacion'] : '',
                    'costopresentacion' => (isset($item['costopresentacion'])) ?  $item['costopresentacion'] : '',
                    'eptaidep' => (isset($item['eptaidep'])) ?  $item['eptaidep'] : '',
                    'txtsubtotal' => (isset($item['txtsubtotal'])) ?  $item['txtsubtotal'] : '',
                );
                break;
            }
        }
        return $itemcarrito;
    }
}
