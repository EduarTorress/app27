<?php

namespace App\Services;

class CarritoServiceOrdenCompra
{
    public static function item($pos)
    {
        $carritococ = \session()->get('carritococ', []);
        $itemcompra = array();
        foreach ($carritococ as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                $itemcompra = array(
                    'coda' => $item['coda'],
                    'descri' => $item['descri'],
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
        return $itemcompra;
    }
    public static function numeroItems()
    {
        $carritococ = session()->get('carritococ', []);
        $titems = 0;
        foreach ($carritococ as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }
    public static function siesta($idart)
    {
        $valor = false;
        $carritococ = \session()->get('carritococ', []);
        foreach ($carritococ as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }
    public static function quitarItem($indice)
    {
        $carritococ = session()->get('carritococ', []);
        foreach ($carritococ as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritococ[$indice]['activo'] = 'I';
        session()->set('carritococ', $carritococ);
    }
    public static function subtotal()
    {
        $carritococ = session()->get('carritococ', []);
        $total = 0.00;
        foreach ($carritococ as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }
    public static function agregarItem($producto)
    {
        $carritococ = session()->get('carritococ', []);
        $carritococ[] = [
            'coda' => $producto['coda'],
            'descri' => $producto['descri'],
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
            'presentaciones' => $producto['presentaciones'],
            'presseleccionada' => $producto['presseleccionada'],
            'cantequi' => $producto['cantequi'],
            'checkafecto' => "false",
            'activo' => 'A'
        ];
        session()->set('carritococ', $carritococ);
    }
    public static function total()
    {
        return self::subtotal();
    }
    public static function editarProducto($producto)
    {
        $carritococ = session()->get('carritococ', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritococ as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritococ[$indice]['cantidad'] = $producto['cantidad'];
        $carritococ[$indice]['precio'] = $producto['precio'];
        $carritococ[$indice]['presseleccionada'] = $producto['presseleccionada'];
        $carritococ[$indice]['cantequi'] = $producto['cantequi'];
        $carritococ[$indice]['unidad'] = $producto['unidad'];
        session()->set('carritococ', $carritococ);
    }
    public static function editarProductocheckafecto($producto)
    {
        $carritococ = session()->get('carritococ', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritococ as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritococ[$indice]['checkafecto'] = $producto['checkafecto'];
        session()->set('carritococ', $carritococ);
    }
}
