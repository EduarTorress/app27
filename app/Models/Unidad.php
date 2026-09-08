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
        $lista['items'] = array();
        $csql = "SELECT unid FROM fe_art GROUP BY unid ORDER BY unid;";
        $query = $this->prepare($csql);
        try {
            $query->execute();
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "unid" => $row['unid']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
            return $data;
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return json_encode($data);
    }
}
