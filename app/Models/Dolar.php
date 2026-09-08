<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;

class Dolar extends Modelo
{
    function obtenerDolar($fech, $tipo)
    {
        try {
            $sql = "SELECT Fundtipocambio(:fech,:tipo) AS vta";
            $query = $this->prepare($sql);
            $query->execute([
                'fech' => $fech,
                'tipo' => $tipo
            ]);
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
}
