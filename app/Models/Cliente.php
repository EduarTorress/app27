<?php

namespace App\Models;

use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Cliente extends Modelo
{
    var $txtRUC = "";
    var $txtDNI = "";
    var $txtNombre = "";
    var $txtDireccion = "";
    var $txtCiudad = "";
    var $txtUbigeo = "";
    var $clienterete = "";

    function buscarClientes($buscar, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestraclientes(:abuscar,:opt,:nid)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar,
                'opt' => $opt,
                "nid" => $nid
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idclie" => $row['idclie'],
                        "nruc" => $row['nruc'],
                        "razo" => $row['razo'],
                        "dire" => $row['dire'],
                        "ciud" => $row['ciud'],
                        "ndni" => $row['ndni'],
                        "ubig" =>  $row['ubig'],
                        'clie_rete' => 'N'
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e->getMessage()];
        }
        return $data;
    }
    function consultarclientexruc($nruc)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_clie WHERE trim(nruc)=:nruc AND clie_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["nruc" => trim($nruc)]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function consultarclientexdni($ndni)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_clie WHERE trim(ndni)=:ndni AND clie_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["ndni" => trim($ndni)]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function consultarclientexrazon($razon)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_clie WHERE trim(razo)=:razon AND clie_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["razon" => trim($razon)]);
        $listado =  $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT idclie,razo,nruc,ndni,dire,ciud, '' as ubig FROM fe_clie WHERE razo LIKE :abuscar and clie_acti='A'";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idclie" => $row['idclie'],
                        "razo" => $row['razo'],
                        "nruc" => $row['nruc'],
                        "ndni" => $row['ndni'],
                        "dire" => $row['dire'],
                        "ciud" => $row['ciud'],
                        "ubig" => $row['ubig'],
                        'clie_rete' => ''
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
        $destinatario = array();
        $csql = "SELECT * FROM fe_clie WHERE idclie=:id AND clie_acti='A'";
        $query = $this->prepare($csql);
        $query->execute(["id" => $id]);
        foreach ($query as $row) {
            $destinatario = array(
                "idclie" => $row['idclie'],
                "razo" => $row['razo'],
                "nruc" => $row['nruc'],
                "ndni" => $row['ndni'],
                "dire" => $row['dire'],
                "ciud" => $row['ciud'],
                "ubig" => $row['ubig'],
                'clie_rete' => $row['clie_rete']
            );
        }
        return $destinatario;
    }
    function save()
    {
        $sql = "INSERT INTO fe_clie (razo,nruc,ndni,dire,ciud,ubig,fechclie,clie_idus) VALUES (:txtNombre,:txtRUC,:txtDNI,:txtDireccion,:txtCiudad,:txtUbigeo,now(),:idusua)";
        $query = $this->prepare($sql);
        $query->execute([
            'txtNombre' => $this->txtNombre,
            'txtRUC' => $this->txtRUC,
            'txtDNI' => $this->txtDNI,
            'txtDireccion' => $this->txtDireccion,
            'txtCiudad' => $this->txtCiudad,
            'txtUbigeo' => $this->txtUbigeo,
            'idusua' => $_SESSION['usuario_id']
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
        $sql = "UPDATE fe_clie SET razo=:txtNombre,nruc=:txtRUC,ndni=:txtDNI,dire=:txtDireccion,ciud=:txtCiudad,ubig=:txtUbigeo,
        clie_actu=:idusua,clie_feac=now() WHERE idclie=:txtID ";
        $query = $this->prepare($sql);
        $query->execute([
            'txtRUC' => $this->txtRUC,
            'txtDNI' => $this->txtDNI,
            'txtNombre' => $this->txtNombre,
            'txtDireccion' => $this->txtDireccion,
            'txtCiudad' => $this->txtCiudad,
            'txtUbigeo' => $this->txtUbigeo,
            'txtID' => $id,
            'idusua' => $_SESSION['usuario_id']
        ]);
        if ($query->errorCode() != '00000') {
            return false;
        } else {
            return true;
        }
    }
    function darBaja($id)
    {
        $sql = "update fe_clie set clie_acti='I' where idclie=:id";
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
}
