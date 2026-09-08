<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Usuario extends Modelo
{
    var $txtidusua = "";
    var $txtnombre = "";
    var $txtclave = "";
    var $cmbtipousuario = "";
    var $sueldo = "";

    public function encryptacontraseñas()
    {
        try {
            $sql = "select idusua,clave from fe_usua where activo='S'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            foreach ($query as $row) {
                $password = password_hash($row['clave'], PASSWORD_DEFAULT);
                $nidusua = $row['idusua'];
                $sql = 'update fe_usua set password=:pass where idusua=:idusuario';
                $rs = $this->prepare($sql);
                $rs->execute(
                    [
                        'pass' => $password,
                        'idusuario' => $nidusua
                    ]
                );
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    public function verificarusuario($usua)
    {
        try {
            $data = array();
            $sql = "select idusua,nomb,password,tipo from fe_usua as a where nomb=:nombre and a.activo='S' ";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(['nombre' =>  $usua]);
            if ($query->rowCount() > 0) {
                foreach ($query as $r) {
                    $data = array([
                        'nomb'   => $r['nomb'],
                        'idusua' => $r['idusua'],
                        'clave'  => $r['password'],
                        'tipo' => $r['tipo'],
                        'usua_prec' => ''
                    ]);
                }
            } else {
                $data = array([
                    'nomb'   => "",
                    'idusua' => '',
                    'clave'  => '',
                    'tipo' => '',
                    'usua_prec' => ''
                ]);
            }
            return $data;
        } catch (PDOException $e) {
            echo 'Error al Conectar' . $e;
        }
    }
    public function verificarUsuarioLogueado($usua, $pass)
    {
        try {
            $data = array();
            $sql = "select idusua,nomb,password from fe_usua as a where nomb=:nombre and clave=:pass and a.activo='S' ";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'nombre' =>  $usua,
                'pass' => $pass
            ]);
            if ($query->rowCount() > 0) {
                foreach ($query as $r) {
                    $data = array([
                        'nomb'   => $r['nomb'],
                        'idusua' => $r['idusua'],
                        'clave'  => $r['password']
                    ]);
                }
            } else {
                $data = array([
                    'nomb'   => "",
                    'idusua' => '',
                    'clave'  => ''
                ]);
            }
            return $data;
        } catch (PDOException $e) {
            echo 'Error al Conectar' . $e;
        }
    }
    public function verificarusuarioadministador($usua, $pass)
    {
        try {
            $data = array();
            $sql = "select idusua,nomb,password from fe_usua as a where nomb=:nombre and clave=:pass and a.activo='S' and tipo='Administrador' ";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'nombre' =>  $usua,
                'pass' => $pass
            ]);
            if ($query->rowCount() > 0) {
                foreach ($query as $r) {
                    $data = array([
                        'nomb'   => $r['nomb'],
                        'idusua' => $r['idusua'],
                        'clave'  => $r['password']
                    ]);
                }
            } else {
                $data = array([
                    'nomb'   => "",
                    'idusua' => '',
                    'clave'  => ''
                ]);
            }
            return $data;
        } catch (PDOException $e) {
            echo 'Error al Conectar' . $e;
        }
    }
    function buscarUsuarios($buscar, $opt, $nid)
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "call PromuestraUsuarioS(:abuscar,:opt,:nid)";
        $query = $this->prepare($csql);
        try {
            $query->execute([
                'abuscar' => $buscar,
                'opt' => $opt,
                'nid' => $nid
            ]);
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "idusua" => $row['idusua'],
                        "nomb" => $row['nomb'],
                        "tipo" => $row['tipo'],
                        "clave" => $row['clave'],
                        'sueldo' => empty($row['sueldo']) ? 0 : $row['sueldo']
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
    function save()
    {
        $sql = "INSERT INTO fe_usua(nomb,clave,activo,tipo,fechusua,usuausua,password,idpcusua) VALUES(:nomb,:clave,'S',:tipousuario,LOCALTIME(),:usuausua,:pass,:idpcusua);";
        $query = $this->prepare($sql);
        $password = password_hash($this->txtclave, PASSWORD_DEFAULT);
        $query->execute([
            'nomb' => $this->txtnombre,
            'clave' => $this->txtclave,
            'tipousuario' => $this->cmbtipousuario,
            'usuausua' =>  session()->get('usuario'),
            'pass' => $password,
            'idpcusua' => 'web'
        ]);
        if ($query->errorCode() != '00000') {
            //var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    function update()
    {
        $sql = "update fe_usua set nomb=:nomb,clave=:clave,tipo=:tipousuario,usuausua=:usuausua,password=:pass where idusua=:idusua";
        $query = $this->prepare($sql);
        $password = password_hash($this->txtclave, PASSWORD_DEFAULT);
        $query->execute([
            'nomb' => $this->txtnombre,
            'clave' => $this->txtclave,
            'tipousuario' => $this->cmbtipousuario,
            'usuausua' =>  session()->get('usuario'),
            'pass' => $password,
            'idusua' => $this->txtidusua
        ]);
        if ($query->errorCode() != '00000') {
            //var_dump($query->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    function consultarsoloadmin()
    {
        $sql = "select * from fe_usua where activo='S' and tipo='Administrador'";
        $query = $this->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    function consultarsueldoxusuario($idusua)
    {
        $sql = "select sueldo from fe_usua where activo='S' and idusua=:idusua";
        $query = $this->prepare($sql);
        $query->execute([
            'idusua' => $idusua
        ]);
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    function listarTipos()
    {
        $lista = array();
        $data = ['resultado' => false];
        $lista['items'] = array();
        $csql = "SELECT DISTINCT(tipo) FROM `fe_usua` WHERE tipo<>'' AND activo='S'";
        $query = $this->prepare($csql);
        try {
            $query->execute();
            if ($query->rowcount()) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $item = array(
                        "tipo" => $row['tipo']
                    );
                    array_push($lista["items"], $item);
                }
                $data = array();
                $data = ["estado" => true, 'lista' => $lista, 'mensaje' => 'Ok'];
            } else {
                $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "No hay resultados para mostrar"];
            }
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e->getMessage()];
        }
        return $data;
    }
}
