<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class PlanesContables extends Modelo
{
    var $idplan = "";
    var $nrocuenta = "";
    var $txtnombre = "";
    var $txtoperacion = "";
    var $txtcuentadestinodebe = "";
    var $txtcuentadestinohaber = "";
    var $txtcodigosunat = "";
    var $cmbtipocta = "";

    function listar($numero)
    {
        $listado = [];
        $sql = 'select * from fe_plan where left(ncta,2) like left(:numero,2) and plan_acti="A" ORDER BY idcta DESC';
        $exec = $this->prepare($sql);
        $exec->execute(["numero" => $numero]);
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listartodosxnumerocuenta($numero)
    {
        $listado = [];
        $sql = "select * from fe_plan where ncta like :numero and plan_acti='A'";
        $exec = $this->prepare($sql);
        $exec->execute(["numero" => $numero]);
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listartodosxnombre($nombre)
    {
        $listado = [];
        $sql = "select * from fe_plan where nomb like :nombre and plan_acti='A'";
        $exec = $this->prepare($sql);
        $exec->execute(["nombre" => $nombre]);
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarparanroscuenta()
    {
        $listado = [];
        $sql = 'select * from fe_plan where left(ncta,4)="10.4" and plan_acti="A" ORDER BY idcta DESC';
        $exec = $this->prepare($sql);
        $exec->execute();
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarcuentasdestinod()
    {
        $listado = [];
        $sql = "SELECT cdestinod FROM fe_plan WHERE plan_acti='A' AND cdestinod<>'' GROUP BY cdestinod";
        $exec = $this->prepare($sql);
        $exec->execute();
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarcuentasdestinoh()
    {
        $listado = [];
        $sql = "SELECT cdestinoh FROM fe_plan WHERE plan_acti='A' AND cdestinoh<>'' GROUP BY cdestinoh";
        $exec = $this->prepare($sql);
        $exec->execute();
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function buscarid($id)
    {
        $listado = [];
        $sql = "SELECT * FROM fe_plan WHERE plan_acti='A' AND idcta=:idcta";
        $exec = $this->prepare($sql);
        $exec->execute(['idcta' => $id]);
        $listado = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $listado[0];
    }
    function save()
    {
        $sql = "INSERT INTO fe_plan(nomb,ncta,plan_oper,cdestinod,cdestinoh,tipocta,ctasunat) VALUES (:txtnombre,:nrocuenta,:txtoperacion,:txtcuentadestinodebe,:txtcuentadestinohaber,:cmbtipocta,:txtcodigosunat);";
        $query = $this->prepare($sql);
        $query->execute([
            'txtnombre' => $this->txtnombre,
            'nrocuenta' => $this->nrocuenta,
            'txtoperacion' => $this->txtoperacion,
            'txtcuentadestinodebe' => $this->txtcuentadestinodebe,
            'txtcuentadestinohaber' => $this->txtcuentadestinohaber,
            'txtcodigosunat' => $this->txtcodigosunat,
            'cmbtipocta' => $this->cmbtipocta
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function update()
    {
        $sql = "UPDATE fe_plan SET nomb=:txtnombre,ncta=:nrocuenta,plan_oper=:txtoperacion,cdestinod=:txtcuentadestinodebe,
        cdestinoh=:txtcuentadestinohaber,tipocta=:cmbtipocta,ctasunat=:txtcodigosunat WHERE idcta=:id";
        $exec = $this->prepare($sql);
        $exec->execute([
            'txtnombre' => $this->txtnombre,
            'nrocuenta' => $this->nrocuenta,
            'txtoperacion' => $this->txtoperacion,
            'txtcuentadestinodebe' => $this->txtcuentadestinodebe,
            'txtcuentadestinohaber' => $this->txtcuentadestinohaber,
            'cmbtipocta' => $this->cmbtipocta,
            'txtcodigosunat' => $this->txtcodigosunat,
            'id' => $this->idplan
        ]);
        if ($exec->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
}
