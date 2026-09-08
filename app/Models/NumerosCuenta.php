<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class NumerosCuenta extends Modelo
{
    var $idcuenta = "";
    var $nrocuenta = "";
    var $cmbbancos = "";
    var $cmbmoneda = "";
    var $txtreferencia = "";
    var $cuentasociada = "";

    function listar($cbuscar)
    {
        $lista = array();
        $csql = 'SELECT c.*,b.banc_nomb FROM fe_ctasb c INNER JOIN fe_bancos b ON c.ctas_idba=b.banc_idba WHERE ctas_acti="A"';
        $query = $this->prepare($csql);
        $query->execute();
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        return $lista;
    }
    function save()
    {
        $sql = "INSERT INTO fe_ctasb(ctas_ctas,ctas_idba,ctas_mone,ctas_deta,ctas_ncta) VALUES (:nrocuenta,:cmbbancos,:cmbmoneda,:txtreferencia,:cuentasociada)";
        $query = $this->prepare($sql);
        $query->execute([
            'nrocuenta' => $this->nrocuenta,
            'cmbbancos' => $this->cmbbancos,
            'cmbmoneda' => $this->cmbmoneda,
            'txtreferencia' => $this->txtreferencia,
            'cuentasociada' => $this->cuentasociada
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function buscarid($id)
    {
        $lista = array();
        $csql = 'SELECT c.*,b.banc_nomb FROM fe_ctasb c INNER JOIN fe_bancos b ON c.ctas_idba=b.banc_idba WHERE ctas_acti="A" AND ctas_idct=:id';
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        return $lista[0];
    }
    function update()
    {
        $sql = "update fe_ctasb set ctas_ctas=:nrocuenta,ctas_idba=:cmbbancos,ctas_mone=:cmbmoneda,ctas_deta=:txtreferencia,ctas_ncta=:cuentasociada where ctas_idct=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'nrocuenta' => $this->nrocuenta,
            'cmbbancos' => $this->cmbbancos,
            'cmbmoneda' => $this->cmbmoneda,
            'txtreferencia' => $this->txtreferencia,
            'cuentasociada' => $this->cuentasociada,
            'id' => $this->idcuenta
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
}
