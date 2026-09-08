<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class DireccionGuia extends Modelo
{
    var $txtDireccion = "";
    var $txtCiudad = "";
    var $cmbUbigeo = "";
    var $txtIdDestinatario = "";
    var $txtIdRemitente = "";

    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT dire_iddi,dire_dire,dire_ciud,dire_ubig,dire_acti,dire_idre,dire_idde,c.razo 
        FROM fe_direcciones d INNER JOIN fe_prov c ON (d.dire_idre=c.idprov) WHERE razo LIKE :abuscar AND dire_acti='A'";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "dire_iddi" => $row['dire_iddi'],
                        "dire_dire" => $row['dire_dire'],
                        "dire_ciud" => $row['dire_ciud'],
                        "dire_ubig" => $row['dire_ubig'],
                        "dire_acti" => $row['dire_acti'],
                        "dire_idre" => $row['dire_idre'],
                        "dire_idde" => $row['dire_idde'],
                        "razo" => $row['razo']
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
        $direcciones = array();
        $csql = "SELECT dire_iddi,dire_dire,dire_ciud,dire_ubig,dire_acti,dire_idre,dire_idde,c.razo FROM fe_direcciones d INNER JOIN fe_prov c ON (d.dire_idre=c.idprov) WHERE dire_iddi=:id AND dire_acti='A'";
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $direcciones = array(
                "dire_iddi" => $row['dire_iddi'],
                "txtDireccion" => $row['dire_dire'],
                "txtCiudad" => $row['dire_ciud'],
                "cmbUbigeo" => $row['dire_ubig'],
                "dire_acti" => $row['dire_acti'],
                "txtIdRemitente" => $row['dire_idre'],
                "razo" => $row['razo']
            );
        }
        return $direcciones;
    }
    function save()
    {
        //SELECT dire_iddi,dire_dire,dire_ciud,dire_ubig,dire_acti,dire_idre,dire_idde 
        //txtNombre // ESTO NO IMPORTA
        //txtDireccion
        //txtCiudad
        //cmbUbigeo
        $sql = "INSERT INTO fe_direcciones (dire_dire,dire_ciud,dire_ubig,dire_idre,dire_idde) VALUES (:txtDireccion,:txtCiudad,:cmbUbigeo,:txtIdRemitente,:txtIdDestinatario)";
        $query = $this->prepare($sql);
        $query->execute([
            // var $txtDireccion = "";
            // var $txtCiudad = "";
            // var $cmbUbigeo = "";
            // var $txtIdDestinatario = "";
            // var $txtIdRemitente = "";
            'txtDireccion' => $this->txtDireccion,
            'txtCiudad' => $this->txtCiudad,
            'cmbUbigeo' => $this->cmbUbigeo,
            'txtIdDestinatario' => $this->txtIdDestinatario,
            'txtIdRemitente' => $this->txtIdRemitente
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
        // INSERT INTO fe_prov (dire_dire,dire_ciud,dire_ubig,dire_idre,dire_idde) VALUES (:txtDireccion,:txtCiudad,:cmbUbigeo,:txtIdRemitente,:txtIdDestinatario)";
        $sql = "UPDATE fe_direcciones SET dire_dire=:txtDireccion,dire_ciud=:txtCiudad,dire_ubig=:cmbUbigeo,dire_idre=:txtIdRemitente,dire_idde=:txtIdDestinatario WHERE dire_iddi=:txtID ";
        $query = $this->prepare($sql);
        $query->execute([
            'txtDireccion' => $this->txtDireccion,
            'txtCiudad' => $this->txtCiudad,
            'cmbUbigeo' => $this->cmbUbigeo,
            'txtIdDestinatario' => $this->txtIdDestinatario,
            'txtIdRemitente' => $this->txtIdRemitente,
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
        $sql = "update fe_direcciones set dire_acti='I' where dire_iddi=:id";
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
    function listarxremitente($nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT dire_iddi,dire_dire,dire_ciud,dire_ubig,dire_acti,dire_idre,dire_idde,c.razo FROM fe_direcciones d 
        INNER JOIN fe_prov c ON (d.dire_idre=c.idprov) WHERE dire_idre=:nid AND dire_acti='A'";
        $query = $this->prepare($csql);
        try {
            $query->execute(['nid' => $nid]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "dire_iddi" => $row['dire_iddi'],
                        "dire_dire" => $row['dire_dire'],
                        "dire_ciud" => $row['dire_ciud'],
                        "dire_ubig" => $row['dire_ubig'],
                        "dire_acti" => $row['dire_acti'],
                        "dire_idre" => $row['dire_idre'],
                        "dire_idde" => $row['dire_idde'],
                        "razo" => $row['razo']
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
