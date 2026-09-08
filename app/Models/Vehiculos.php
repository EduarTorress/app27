<?php

namespace App\Models;

use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Vehiculos extends Modelo
{
    var $chofer = "";
    var $txtPlaca01 = "";
    var $txtPlaca02 = "";
    var $txtBrevete = "";
    var $txtDni = "";
    var $txtTipoBrevete = "";
    var $txtConstancia = "";

    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT * FROM fe_vehiculos WHERE vehi_cond LIKE :abuscar and vehi_acti='A'";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "vehi_idve" => $row['vehi_idve'],
                        "vehi_plac" => $row['vehi_plac'],
                        "vehi_pla2" => $row['vehi_pla2'],
                        "vehi_cons" => $row['vehi_cons'],
                        "vehi_conf" => $row['vehi_conf'],
                        "vehi_cond" => $row['vehi_cond'],
                        "vehi_ndni" => $row['vehi_ndni'],
                        "vehi_brev" => $row['vehi_brev']
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
        $vehiculo = array();
        $sql = "SELECT * FROM fe_vehiculos WHERE vehi_idve=:id AND vehi_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $vehiculo = array(
                "vehi_idve" => $row['vehi_idve'],
                "vehi_plac" => $row['vehi_plac'],
                "vehi_pla2" => $row['vehi_pla2'],
                "vehi_cons" => $row['vehi_cons'],
                "vehi_conf" => $row['vehi_conf'],
                "vehi_cond" => $row['vehi_cond'],
                "vehi_ndni" => $row['vehi_ndni'],
                "vehi_brev" => $row['vehi_brev']
            );
        }
        return $vehiculo;
    }
    function save()
    {
        // var $chofer = "";
        // var $txtPlaca01 = "";
        // var $txtPlaca02 = "";
        // var $txtBrevete = "";
        // var $txtDni = "";
        // var $txtTipoBrevete = "";
        // var $txtConstancia = "";
        $sql = "INSERT INTO fe_vehiculos (vehi_plac, vehi_pla2, vehi_cons, vehi_conf,vehi_cond,vehi_ndni,vehi_brev) VALUES (:placa1,:placa2,:cons,:conf,:chofer,:ndni,:brev)";
        $query = $this->prepare($sql);
        $query->execute([
            // :placa,:placa2,:cons,:conf,:chofer,:ndni,:brev
            'chofer' => $this->chofer,
            'placa1' => $this->txtPlaca01,
            'placa2' => $this->txtPlaca02,
            'cons' => $this->txtConstancia,
            'conf' => $this->txtTipoBrevete,
            'ndni' => $this->txtDni,
            'brev' => $this->txtBrevete
        ]);
        if ($query->errorCode() != '00000') {
            var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "UPDATE fe_vehiculos SET vehi_plac=:placa1,vehi_pla2=:placa2,vehi_cons=:cons,vehi_conf=:conf,vehi_cond=:chofer,vehi_ndni=:ndni,vehi_brev=:brev WHERE vehi_idve=:txtID ";
        $query = $this->prepare($sql);
        $query->execute([
            'chofer' => $this->chofer,
            'placa1' => $this->txtPlaca01,
            'placa2' => $this->txtPlaca02,
            'cons' => $this->txtConstancia,
            'conf' => $this->txtTipoBrevete,
            'ndni' => $this->txtDni,
            'brev' => $this->txtBrevete,
            'txtID' => $id
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function darBaja($id)
    {
        $sql = "update fe_vehiculos set vehi_acti='I' where vehi_idve=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'id' => $id
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function listarplacas()
    {
        $lsql = "SELECT vehi_plac, vehi_pla2 FROM fe_vehiculos";
        $query = $this->prepare($lsql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        return $query;
    }
    function listarVehiculos()
    {
        try {
            $sql = "SELECT vehi_idve,vehi_plac,vehi_pla2,vehi_cond,vehi_brev,vehi_seri,vehi_marc FROM fe_vehiculos WHERE vehi_Acti='A'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $lista = array();
            $data = ['resultado' => false];
            $lista['items'] = array();
            $query->execute();
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "vehi_idve" => $row['vehi_idve'],
                        "vehi_plac" => $row['vehi_plac'],
                        "vehi_pla2" => $row['vehi_pla2'],
                        "vehi_cond" => $row['vehi_cond'],
                        "vehi_brev" => $row['vehi_brev'],
                        "vehi_seri" => $row['vehi_seri'],
                        "vehi_marc" => $row['vehi_marc']
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
}
