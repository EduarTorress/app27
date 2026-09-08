<?php

namespace App\Services;

class CarritoServiceCanje
{
    public static function obtenerCantidadActual($presentacion): int
    {
        $carrito = session()->get('carritocanje', []);
        foreach ($carrito as $item) {
            if ($item['presentacion']->id == $presentacion->id) {
                return $item['cantidad'];
            }
        }
        return 0;
    }
    public static function agregarItemVenta($producto)
    {
        $carritov = session()->get('carritocanje', []);
        $indice = false;
        $carritov[] = [
            'coda' => $producto['coda'],
            'descripcion' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'stock' => $producto['stock'],
            'precio1' => $producto['precio1'],
            'precio2' => $producto['precio2'],
            'precio3' => $producto['precio3'],
            'costo' => $producto['costo'],
            'nreg' => 0,
            'idcliente' => 0,
            'activo' => 'A'
        ];
        session()->set('carritocanje', $carritov);
    }
    public static function buscarsiyaesta($idart)
    {
        $carrito = \session()->get("carritocanje", []);
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
        $carrito = session()->get('carritocanje', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['cantidad'] = $producto['cantidad'];
        $carrito[$indice]['precio'] = $producto['precio'];
        // 'poscpresentacion' => $producto['poscpresentacion'],
        // 'textopresentacion' => $producto['textopresentacion'],
        // 'cantpresentacion' => $producto['cantpresentacion'],
        // 'precio1presentacion' => $producto['precio1presentacion'],
        // 'costopresentacion' => $producto['costopresentacion'],
        // 'eptaidep' => $producto['eptaidep'],
        $carrito[$indice]['poscpresentacion'] = $producto['poscpresentacion'];
        $carrito[$indice]['textopresentacion'] = $producto['textopresentacion'];
        $carrito[$indice]['cantpresentacion'] = $producto['cantpresentacion'];
        $carrito[$indice]['precio1presentacion'] = $producto['precio1presentacion'];
        $carrito[$indice]['costopresentacion'] = $producto['costopresentacion'];
        $carrito[$indice]['txtsubtotal'] = $producto['txtsubtotal'];
        $carrito[$indice]['eptaidep'] = $producto['eptaidep'];
        session()->set('carritocanje', $carrito);
    }
    public static function numeroItems()
    {
        $carrito = session()->get('carritocanje', []);
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
        $carrito = session()->get('carritocanje', []);
        $total = 0.00;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
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
        $carrito = session()->get('carritocanje', []);
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['activo'] = 'I';
        session()->set('carritocanje', $carrito);
    }
    public static function aplicarCupon($cupon)
    {
        session()->set('cupon', $cupon);
        $subtotal = CarritoServiceCanje::subtotal();
        $descuento = 0.00;
        if ($cupon->tipo == 'MONTO') {
            $descuento = $cupon->monto;
        } else if ($cupon->tipo == 'PORCENTAJE') {
            $descuento = $subtotal * $cupon->monto / 100;
        }
        session()->set('descuento', $descuento);
    }
    public static function siesta($idart)
    {
        $valor = false;
        $carrito = \session()->get('carritocanje', []);
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
    ///////
    public static function itemVenta($pos)
    {
        $carritov = \session()->get('carritocanje', []);
        $itemprod = array();
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                $itemprod = array(
                    'coda' => $item['coda'],
                    'descripcion' => $item['descri'],
                    'unidad' => $item['unidad'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'stock' => $item['stock'],
                    'precio1' => $item['precio1'],
                    'precio2' => $item['precio2'],
                    'precio3' => $item['precio3'],
                    'costo' => $item['costo'],
                    'nreg' => $item['nreg'],
                    'activo' => 'A'
                );
                break;
            }
        }
        return $itemprod;
    }
    public static function numeroItemsVenta()
    {
        $carritov = session()->get('carritocanje', []);
        $titems = 0;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }
    public static function siestaventas($idart)
    {
        $valor = false;
        $carritov = \session()->get('carritocanje', []);
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }
    public static function quitarItemVenta($indice)
    {
        $carritov = session()->get('carritocanje', []);
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['activo'] = 'I';
        session()->set('carritocanje', $carritov);
    }
    public static function subtotalVentas()
    {
        $carritov = session()->get('carritocanje', []);
        $total = 0.00;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }
    public static function totalVenta()
    {
        return self::subtotalVentas();
    }
    public static function editarProductoVenta($producto)
    {
        $carritov = session()->get('carritocanje', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['cantidad'] = $producto['cantidad'];
        $carritov[$indice]['precio'] = $producto['precio'];
        session()->set('carritocanje', $carritov);
    }
}
