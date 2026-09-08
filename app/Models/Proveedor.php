<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDO;
use PDOException;

class Proveedor extends Modelo
{
    var $txtRUC = "";
    var $txtDNI = "";
    var $txtNombre = "";
    var $txtDireccion = "";
    var $txtCiudad = "";
    var $txtUbigeo = "";

    function buscarProveedor($buscar, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestraproveedor(:abuscar,:opt,:nid)";
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
                        "idprov" => $row['idprov'],
                        "nruc" => $row['nruc'],
                        "razo" => $row['razo'],
                        "fono" => $row['fono'],
                        "dire" => $row['dire'],
                        "ciud" => $row['ciud'],
                        "fax" => $row['fax'],
                        "email" => $row['email'],
                        "celu" => $row['celu'],
                        "refe" => $row['refe'],
                        "prov_rpm" => $row['prov_rpm'],
                        "prov_idus" => $row['prov_idus'],
                        "prov_actu" => $row['prov_actu'],
                        "fechprov" => $row['fechprov'],
                        "prov_feac" => $row['prov_feac'],
                        "ubig" => ''
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDO $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar" . $e];
        }
        return $data;
    }
    function listar($cbuscar)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT idprov,razo,nruc,ndni,dire,ciud,ubig FROM fe_prov WHERE razo LIKE :abuscar and prov_acti='A'";
        $query = $this->prepare($csql);
        try {
            $query->execute(['abuscar' => $cbuscar]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idprov" => $row['idprov'],
                        "razo" => $row['razo'],
                        "nruc" => $row['nruc'],
                        "ndni" => $row['ndni'],
                        "dire" => $row['dire'],
                        "ciud" => $row['ciud'],
                        "ubig" => $row['ubig']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
    function muestraProveedoresModal($buscar, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call promuestraproveedor(:abuscar,:opt,:nid)";
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
                        "idprov" => $row['idprov'],
                        "nruc" => $row['nruc'],
                        "razo" => $row['razo'],
                        "fono" => $row['fono'],
                        "dire" => $row['dire'],
                        "ciud" => $row['ciud'],
                        "fax" => $row['fax'],
                        "email" => $row['email'],
                        "celu" => $row['celu'],
                        "refe" => $row['refe'],
                        "prov_rpm" => $row['prov_rpm'],
                        "prov_idus" => $row['prov_idus'],
                        "prov_actu" => $row['prov_actu'],
                        "fechprov" => $row['fechprov'],
                        "prov_feac" => $row['prov_feac'],
                        "ubig" => $row['ubig']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
                // echo json_encode($data);
            }
        } catch (PDO $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
            //echo json_encode($data);
            //   return false;
        }
        return $data;
    }
    function consultarprovxruc($nruc)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_prov WHERE trim(nruc)=:nruc AND prov_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["nruc" => trim($nruc)]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function consultarprovxdni($ndni)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_prov WHERE trim(ndni)=:ndni AND prov_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["ndni" => trim($ndni)]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function consultarprovxrazon($razon)
    {
        $existe = "F";
        $sql = "SELECT count(*) as existe FROM fe_prov WHERE trim(razo)=:razon AND prov_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["razon" => trim($razon)]);
        $listado =  $query->fetchAll(PDO::FETCH_ASSOC);
        if ($listado[0]['existe'] > 0) {
            $existe = "T";
        }
        return $existe;
    }
    function buscarid($id)
    {
        $csql = "SELECT * FROM fe_prov WHERE idprov=:id AND prov_acti=:cacti";
        $query = $this->prepare($csql);
        $query->execute([
            'id' => $id,
            'cacti' => 'A'
        ]);
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    function save()
    {
        $sql = "INSERT INTO fe_prov (razo, nruc, ndni,dire,ciud,ubig,prov_idus,fechprov) VALUES (:txtNombre,:txtRUC,:txtDNI,:txtDireccion,:txtCiudad,:txtUbigeo,:idusua,now())";
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
            // var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    function update($id)
    {
        $sql = "UPDATE fe_prov SET razo=:txtNombre,nruc=:txtRUC,ndni=:txtDNI,dire=:txtDireccion,ciud=:txtCiudad,
                ubig=:txtUbigeo,prov_actu=:idusua,prov_feac=now()
                WHERE idprov=:txtID ";
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
        $sql = "update fe_prov set prov_acti='I' where idprov=:id";
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
