<?php

namespace App\Models;

use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Grupo extends Modelo
{
    var $nombre = "";
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraGrupos(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idgrupo" => $row['idgrupo'],
                        "desgrupo" => $row['desgrupo'],
                        "totalcat" => $row['Total_Categorias']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e];
        }
        return $data;
    }
    function buscarid($id)
    {
        $grupo = array();
        $csql = 'select idgrupo,desgrupo from fe_grupo where idgrupo=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $grupo = array(
                "idgrupo" => $row['idgrupo'],
                "desgrupo" => $row['desgrupo']
            );
        }
        return $grupo;
    }
    function save()
    {
        $sql = "select FuncreaGrupo(:cd,:nidusua,:pc) as id ";
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
        $sql = "update fe_grupo set desgrupo=:nombre where idgrupo=:id";
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
        $ctabla = "fe_grupo";
        $ccampo = 'desgrupo';
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
