<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class PagosPlanilla extends Modelo
{
    var $sueldo = "";
    var $acta = "";
    var $idemp = "";
    var $fech = "";
    var $idusua = "";
    var $fusua = "";
    var $referencia = "";

    function buscarsaldoxusuario($mes, $ano, $nidusua)
    {
        $lista = [];
        $sql = "SELECT SUM(p.sueldo) - SUM(actas) AS pendiente,u.idusua,u.nomb,if(SUM(p.sueldo) is null,0,SUM(p.sueldo)) AS sueldo
                FROM fe_pagos p
                INNER JOIN fe_usua u ON p.idemp=u.idusua
                WHERE MONTH(fech)=:mes AND YEAR(fech)=:ano
                AND u.idusua=:nidusua AND acti='A'";
        $query = $this->prepare($sql);
        $query->execute([
            'mes' => $mes,
            'ano' => $ano,
            'nidusua' => $nidusua
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'data' => $lista, 'estado' => '1'];
        return $data;
    }
    function buscarsaldo($mes, $ano)
    {
        $lista = [];
        $sql = "SELECT p.idemp as idusua,SUM(p.sueldo) - SUM(actas) AS pendiente,u.idusua,u.nomb,SUM(p.sueldo) AS sueldo
                FROM fe_pagos p
                INNER JOIN fe_usua u ON p.idemp=u.idusua
                WHERE MONTH(fech)=:mes AND YEAR(fech)=:ano
                and acti='A'  GROUP BY u.idusua";
        $query = $this->prepare($sql);
        $query->execute([
            'mes' => $mes,
            'ano' => $ano
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'data' => $lista, 'estado' => '1'];
        return $data;
    }
    function registrarpago()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $sqlIC = "INSERT INTO fe_pagos(sueldo,actas,idemp,fech,usua,fusua,idpcpa) VALUES (:sueldo,:acta,:idemp,:fech,:idusua,:fusua,:referencia)";
            $exIC = $pdo->prepare($sqlIC);
            $exIC->execute([
                'sueldo' => $this->sueldo,
                'acta' =>  $this->acta,
                'idemp' =>  $this->idemp,
                'fech' =>  $this->fech,
                'idusua' =>  $this->idusua,
                'fusua' =>  $this->fusua,
                'referencia' => $this->referencia
            ]);
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registró correctamente", "data" => [], "estado" => '1');
        } catch (PDOException $pdoexception) {
            $pdo->rollback();
            $rpta = array('mensaje' => $pdoexception->getMessage(), "data" => [], "estado" => '0');
        }
        return $rpta;
    }
    function buscarsihaysaldopendientexusuario($idusua)
    {
        $sql = "SELECT MONTH(fech) AS mes,YEAR(fech) AS ano,sum(sueldo)-SUM(actas) AS pendiente FROM fe_pagos WHERE idemp=:idusua AND acti='A' GROUP BY MONTH(fech),YEAR(fech)";
        $query = $this->prepare($sql);
        $query->execute([
            'idusua' => $idusua
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function anularmvto($idauto)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $sqlIC = "update fe_pagos set acti='I',idusua1=:idusua1,fusua=CURRENT_TIMESTAMP() where idpagos=:idpago";
            $exIC = $pdo->prepare($sqlIC);
            $exIC->execute([
                'idusua1' => $_SESSION['usuario_id'],
                'idpago' =>  $idauto
            ]);
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se dió correctamente", "data" => [], "estado" => '1');
        } catch (PDOException $pdoexception) {
            $pdo->rollback();
            $rpta = array('mensaje' => $pdoexception->getMessage(), "data" => [], "estado" => '0');
        }
        return $rpta;
    }
    // function consultarmovimientos($mes, $ano)
    // {
    //     $lista = [];
    //     $sql = "select * from fe_pagos where acti='A' and month(fech)=:mes and year(fech)=:ano";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'mes' => $mes,
    //         'ano' => $ano
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'data' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    function consultarmovimientosxusuario($mes, $ano, $idusua)
    {
        $lista = [];
        $sql = "select p.*,u.nomb from fe_pagos p inner join fe_usua u on p.usua=u.idusua where acti='A' and month(fech)=:mes and year(fech)=:ano and idemp=:idusua ";
        $query = $this->prepare($sql);
        $query->execute([
            'mes' => $mes,
            'ano' => $ano,
            'idusua' => $idusua
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'data' => $lista, 'estado' => '1'];
        return $data;
    }
}
