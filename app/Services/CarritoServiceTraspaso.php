<?php

namespace App\Services;

class CarritoServiceTraspaso
{
    public static function item($pos)
    {
        $carritot = \session()->get('carritot', []);
        $itemcompra = array();
        foreach ($carritot as $posicion => $item) {
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
        $carritot = session()->get('carritot', []);
        $titems = 0;
        foreach ($carritot as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }
    public static function siesta($idart)
    {
        $valor = false;
        $carritot = \session()->get('carritot', []);
        foreach ($carritot as $item) {
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
        $carritot = session()->get('carritot', []);
        foreach ($carritot as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritot[$indice]['activo'] = 'I';
        session()->set('carritot', $carritot);
    }
    public static function subtotal()
    {
        $carritot = session()->get('carritot', []);
        $total = 0.00;
        foreach ($carritot as $item) {
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
        $carritot = session()->get('carritot', []);
        $carritot[] = [
            'coda' => $producto['coda'],
            'descri' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'preciocopia' => $producto['precio'],
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
            'uno' => $producto['stockuno'],
            'dos' => $producto['stockdos'],
            'tre' => $producto['stocktre'],
            'checkafecto' => "false",
            'lote' => '',
            'fechavto' => '',
            'activo' => 'A'
        ];
        session()->set('carritot', $carritot);
    }
    public static function total()
    {
        return self::subtotal();
    }
    public static function editarProducto($producto)
    {
        $carritot = session()->get('carritot', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritot as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritot[$indice]['cantidad'] = $producto['cantidad'];
        $carritot[$indice]['precio'] = $producto['precio'];
        $carritot[$indice]['preciocopia'] = $producto['precio'];
        $carritot[$indice]['presseleccionada'] = $producto['presseleccionada'];
        $carritot[$indice]['cantequi'] = $producto['cantequi'];
        $carritot[$indice]['unidad'] = $producto['unidad'];
        $carritot[$indice]['lote'] = empty($producto['lote']) ? ' ' : $producto['lote'];
        $carritot[$indice]['fechavto'] = empty($producto['fechavto']) ? '' : $producto['fechavto'];
        session()->set('carritot', $carritot);
    }
}
