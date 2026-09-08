<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;

class Transportista extends Modelo
{
    // let txtrazon = document.querySelector("#txtrazon").value;
    // let txtruc = document.querySelector("#txtruc").value;
    // let txttransportista = document.querySelector("#txttransportista").value;
    // let txtplaca = document.querySelector("#txtplaca").value;
    // let txtplaca1 = document.querySelector("#txtplaca1").value;
    // let txtbrevete = document.getElementById("txtbrevete").value
    // let txtmarca = document.getElementById("txtmarca").value;

    var $txtrazon = "";
    var $txtruc = "";
    var $txttransportista = "";
    var $txtplaca = "";
    var $txtplaca1 = "";
    var $txtbrevete = "";
    var $txtmarca = "";
    var $txtconstancia = "";
    var $cmbtipotransporte = "";

    function BuscarTransportista($buscar, $opt)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call ProMuestraTransportista(:abuscar,:opt)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar,
                'opt' => $opt
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idtra" => $row['idtra'],
                        "placa" => $row['placa'],
                        "razon" => $row['razon'],
                        "ructr" => $row['ructr'],
                        "nombr" => $row['nombr'],
                        "dirtr" => $row['dirtr'],
                        "breve" => $row['breve'],
                        "marca" => $row['marca'],
                        "constancia" => $row['cons'],
                        "tipot" => $row['tran_tipo'],
                        "placa1" => $row['placa1']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
                // echo json_encode($data);
            }
            return $data;
        } catch (PDO $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e];
            //echo json_encode($data);
            //   return false;
        }
        return json_encode($data);
    }
    function listarchoferes()
    {
        $lsql = "SELECT idtra,nombr,breve FROM fe_tra WHERE tran_acti='A' ORDER BY nombr";
        $query = $this->prepare($lsql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        return $query;
    }
    function buscarid($id)
    {
        $transportista = array();
        $csql = "SELECT * FROM fe_tra WHERE idtra=:id AND tran_acti='A'";
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $transportista = array(
                "idtra" => $row['idtra'],
                "placa" => $row['placa'],
                "razon" => $row['razon'],
                "ructr" => $row['ructr'],
                "nombr" => $row['nombr'],
                "dirtr" => $row['dirtr'],
                "breve" => $row['breve'],
                "marca" => $row['marca'],
                "constancia" => $row['cons'],
                "tipot" => $row['tran_tipo'],
                "placa1" => $row['placa1']
            );
        }
        return $transportista;
    }
    function save()
    {
        $sql = "INSERT INTO fe_tra (razon,ructr,nombr,placa,placa1,breve,marca,cons,tran_tipo) VALUES (:txtrazon,:txtruc,:txttransportista,:txtplaca,:txtplaca1,:txtbrevete,:txtmarca,:cons,:tran_tipo)";
        $query = $this->prepare($sql);
        $query->execute([
            'txtrazon' => $this->txtrazon,
            'txtruc' => $this->txtruc,
            'txttransportista' => $this->txttransportista,
            'txtplaca' => $this->txtplaca,
            'txtplaca1' => $this->txtplaca1,
            'txtbrevete' => $this->txtbrevete,
            'txtmarca' => $this->txtmarca,
            'cons' => $this->txtconstancia,
            'tran_tipo' => $this->cmbtipotransporte
        ]);
        if ($query->errorCode() != '00000') {
            // var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "UPDATE fe_tra SET razon=:txtrazon,ructr=:txtruc,nombr=:txttransportista,placa=:txtplaca,placa1=:txtplaca1,breve=:txtbrevete,marca=:txtmarca,cons=:txtconstancia,tran_tipo=:tran_tipo  WHERE idtra=:id";
        $query = $this->prepare($sql);
        $query->execute([
            'txtrazon' => $this->txtrazon,
            'txtruc' => $this->txtruc,
            'txttransportista' => $this->txttransportista,
            'txtplaca' => $this->txtplaca,
            'txtplaca1' => $this->txtplaca1,
            'txtbrevete' => $this->txtbrevete,
            'txtmarca' => $this->txtmarca,
            'tran_tipo' => $this->cmbtipotransporte,
            'txtconstancia' => $this->txtconstancia,
            'id' => $id
        ]);
        if ($query->errorCode() != '00000') {
            // var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
}
