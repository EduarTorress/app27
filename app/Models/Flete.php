<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Flete extends Modelo
{
    private $nombre;
    private $precio;
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraFletes(:abuscar)";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idflete" => $row['idflete'],
                        "desflete" => $row['desflete'],
                        "prec" => $row['prec'],
                        "totalproductos" => 0
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
        $flete = array();
        $csql = 'select idflete,desflete,prec from fe_fletes where idflete=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $flete = array(
                "idflete" => $row['idflete'],
                "desflete" => $row['desflete'],
                "prec" => $row['prec'],
            );
        }
        return $flete;
    }
    function save()
    {
        $sql = "select FunCreaFletes(:cd,:nprecio,:nidusua,:pc) as id ";
        $query = $this->prepare($sql);
        $query->execute([
            'cd' => $this->nombre,
            'nprecio' => $this->precio,
            'nidusua' => \session()->get('nidusua'),
            'pc' => ''
        ]);
        if ($query->errorCode() == '00000') {
            return true;
        } else {
            return false;
        }
    }
    function update($id)
    {
        $sql = "update fe_fletes set desflete=:nombre,prec=:nprecio where idflete=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'nombre' => $this->nombre,
            'nprecio' => $this->precio,
            'id' => $id
        ]);
        if ($query->errorCode() == '00000') {
            return true;
        } else {
            return false;
        }
    }
    function buscarnombre($cnombre, $id)
    {
        $ctabla = "fe_fletes";
        $ccampo = 'desflete';
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
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function setPrecio($precio)
    {
        $this->precio = $precio;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function getPrecio()
    {
        return $this->precio;
    }
}
