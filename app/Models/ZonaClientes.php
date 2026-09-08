<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;

class ZonaClientes extends Modelo
{
    function listar(string $abuscar)
    {
        try {
            $sql = 'call PROMUESTRAZONAS(:abuscar)';
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([':abuscar' => $abuscar]);
            return $query;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
