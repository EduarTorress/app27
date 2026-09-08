<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Marca extends Modelo
{
    var $nombre = "";
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraMarcas(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idmar" => $row['idmar'],
                        "dmar" => $row['dmar'],
                        "totalproductos" => $row['TotalProductos']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No Hay Resultados Para Mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e];
        }
        return $data;
    }
    function buscarid($id)
    {
        $marca = array();
        $csql = 'select idmar,dmar from fe_mar where idmar=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $marca = array(
                "idmar" => $row['idmar'],
                "dmar" => $row['dmar']
            );
        }
        return $marca;
    }
    function save()
    {
        $sql = "select Funcreamarcas(:cd,:nidusua,:pc) as id ";
        $query = $this->prepare($sql);
        $query->execute([
            'cd' => $this->nombre,
            'nidusua' => \session()->get('nidusua'),
            'pc' => ''
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "update fe_mar set dmar=:nombre where idmar=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'nombre' => $this->nombre,
            'id' => $id
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function buscarnombre($cnombre, $id)
    {
        $ctabla = "fe_mar";
        $ccampo = 'dmar';
        $datos = array(
            'tabla' => $ctabla,
            'campo' => $ccampo,
            'valor' => $cnombre,
            'id' => $id
        );
        if ($this->buscardato($datos)) {
            return true;
        } else {
            return false;
        }
    }
}
