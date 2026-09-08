<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Documentos extends Modelo
{
    function listardocumentosocompras($buscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestradctos(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['tdoc'] == '01' or $row['tdoc'] == '03' or $row['tdoc'] == '07' or $row['tdoc'] == '08') {
                        $item = array(
                            "tdoc" => $row['tdoc'],
                            "nomb" => $row['nomb'],
                            "idtdoc" => $row['idtdoc']
                        );
                        array_push($lista["items"], $item);
                    }
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
                // echo json_encode($data);
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
            //echo json_encode($data);
            //   return false;
        }
        return $data;
    }
    function listardocumentosventas($buscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestradctos(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['tdoc'] == '01' or $row['tdoc'] == '03' or $row['tdoc'] == '20') {
                        $item = array(
                            "tdoc" => $row['tdoc'],
                            "nomb" => $row['nomb'],
                            "idtdoc" => $row['idtdoc']
                        );
                        array_push($lista["items"], $item);
                    }
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
    function listardocumentosanular($buscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestradctos(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['tdoc'] == '01' or $row['tdoc'] == '03' or $row['tdoc'] == '20' or $row['tdoc'] == '09' or $row['tdoc'] == '31' or $row['tdoc'] == 'AJ' or $row['tdoc'] == 'GI' or $row['tdoc'] == '07' or $row['tdoc'] == '09') {
                        $item = array(
                            "tdoc" => $row['tdoc'],
                            "nomb" => $row['nomb'],
                            "idtdoc" => $row['idtdoc']
                        );
                        array_push($lista["items"], $item);
                    }
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
    function listardocumentosoventas($buscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestradctos(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['tdoc'] == '01' or $row['tdoc'] == '03'  or $row['tdoc'] == '07' or $row['tdoc'] == '08' or $row['tdoc'] == '20'  or $row['tdoc'] == 'GI') {
                        $item = array(
                            "tdoc" => $row['tdoc'],
                            "nomb" => $row['nomb'],
                            "idtdoc" => $row['idtdoc']
                        );
                        array_push($lista["items"], $item);
                    }
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
}
