<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;

class Sucursal extends Modelo
{
    var $txtnombre = "";
    var $txtdireccion = "";
    var $txtciudad = "";
    var $cmbUbigeo = "";
    var $txtidsucu = "";

    function mostrar()
    {
        $csql = "call ProMuestraAlmacenes()";
        try {
            $query = $this->prepare($csql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    function nombresucursal($idalma)
    {
        $sql = 'select nomb from fe_sucu where idalma=:idalma';
        $query = $this->prepare($sql);
        // $query->SetFetchMode(PDO::FETCH_ASSOC);
        $query->execute(['idalma' => $idalma]);
        $sucursal = $query->fetch(PDO::FETCH_ASSOC);
        return $sucursal['nomb'];
    }
    function consultarsucursalxid($idsucu)
    {
        $sql = 'select * from fe_sucu where idalma=:idsucu';
        $query = $this->prepare($sql);
        $query->execute(['idsucu' => $idsucu]);
        $sucursal = $query->fetchAll(PDO::FETCH_ASSOC);
        return $sucursal;
    }
    function save()
    {
        $sql = "INSERT INTO fe_sucu(nomb,dire,ciud,ubigeo) VALUE(:nomb,:dire,:ciud,:ubigeo);";
        $query = $this->prepare($sql);
        $query->execute([
            'nomb' => $this->txtnombre,
            'dire' => $this->txtdireccion,
            'ciud' => $this->txtciudad,
            'ubigeo' => $this->cmbUbigeo
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function update()
    {
        $sql = "UPDATE fe_sucu SET nomb=:nomb,dire=:dire,ciud=:ciud,ubigeo=:ubigeo WHERE idalma=:idsucu";
        $query = $this->prepare($sql);
        $query->execute([
            'nomb' => $this->txtnombre,
            'dire' => $this->txtdireccion,
            'ciud' => $this->txtciudad,
            'ubigeo' => $this->cmbUbigeo,
            'idsucu' => $this->txtidsucu
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
}
