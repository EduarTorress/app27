<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Categoria extends Modelo
{
    protected $table = 'fe_cat';
    var $nombre;
    var $nutil1;
    var $nutil2;
    var $idgrupo;

    function listar($cbuscar, $nidgrupo)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraLineas(:abuscar,:nidgrupo)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $cbuscar,
                'nidgrupo' => $nidgrupo
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idcat" => $row['idcat'],
                        "dcat" => $row['dcat'],
                        "totalproductos" => $row['Total_Productos']
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
        $categoria = array();
        $csql = 'select idcat,dcat,idgrupo from fe_cat where idcat=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $categoria = array(
                "idcat" => $row['idcat'],
                "dcat" => $row['dcat'],
                "idgrupo" => $row['idgrupo']
            );
        }
        return $categoria;
    }
    function save()
    {
        $this->nutil1 = 0;
        $this->nutil2 = 0;
        $sql = "select FunCreaLinea(:cd,:nidusua,:pc,:nutil1,:nutil2,:nidg)";
        $query = $this->prepare($sql);
        $query->execute([
            'cd' => $this->nombre,
            'nidusua' => \session()->get('nidusua'),
            'pc' => '',
            'nutil1' => $this->nutil1,
            'nutil2' => $this->nutil2,
            'nidg' => $this->idgrupo
        ]);
        if ($query->errorCode() == '00000') {
            return true;
        } else {
            return false;
        }
    }
    function update($id)
    {
        $sql = "update fe_cat set dcat=:nombre,idgrupo=:nidgrupo where idcat=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'nombre' => $this->nombre,
            'nidgrupo' => $this->idgrupo,
            'id' => $id
        ]);
        if ($query->errorCode() == '00000') {
            return true;
        } else {
            return false;
        }
    }
      function eliminar($id)
    {
        $sql = "update fe_cat set line_acti='I' where idcat=:id";
        $query = $this->prepare($sql);
        $query->execute([
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
        $ctabla = "fe_cat";
        $ccampo = 'dcat';
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
