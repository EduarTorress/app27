<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Unidad extends Modelo
{
    function listar()
    {
        $lista = array();
        $data = ['resultado' => false];
        $csql = "SELECT * FROM fe_unidades";
        $query = $this->prepare($csql);
        try {
             $query->execute();
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    array_push($lista, $row);
                }
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return ($data);
    }
}
