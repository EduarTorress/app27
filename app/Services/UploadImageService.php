<?php

namespace App\Services;

use Gumlet\ImageResize;
use Ramsey\Uuid\Uuid;

class UploadImageService
{

    protected const WIDTHS = [100, 300, 500, 700, 1000, 1500, 2500];

    public static function upload($imagen, $ruta_destino)
    {
        $nombre = Uuid::uuid4();
        $extension = $imagen->getClientOriginalExtension(); // jpg, jpeg, png, gif
        $nombre_completo = $nombre . '.' . $extension;
        try {
            $imagen->move($ruta_destino, $nombre_completo);
            // creacion de la imagen en distintos anchos
            $ruta_completa_imagen = $ruta_destino . "/" . $nombre_completo;
            $info_imagen = getimagesize($ruta_completa_imagen); // array(ancho, alto)\
            $ancho_imagen = $info_imagen[0]; // 1200
            $indice_widths = 0;
            while (self::WIDTHS[$indice_widths] < $ancho_imagen) {
                // generar la imagen
                $imagen_reducida = new ImageResize($ruta_completa_imagen);
                $imagen_reducida->resizeToWidth(self::WIDTHS[$indice_widths]);
                $ruta_destino_imagen_reducida = $ruta_destino . "/" . $nombre_completo . 'x' . self::WIDTHS[$indice_widths] . '.' . $extension;
                // 176acb3e-407b-4f1e-b0ad-60ef89b407b7x100.jpg
                // 176acb3e-407b-4f1e-b0ad-60ef89b407b7x300.jpg
                // 176acb3e-407b-4f1e-b0ad-60ef89b407b7x500.jpg
                $imagen_reducida->save($ruta_destino_imagen_reducida);
                $indice_widths++;
            }
        } catch (\Exception $error) {
            return null;
        }
        return $nombre_completo;
    }
}
