<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Combo extends Modelo
{
    var $idproducto = "";
    var $costo = 0.00;

    function save($detalle)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $sqlclearcombo = "update fe_combos set com_acti='I' where idprodcomb=:idproducto";
            $execclearcombo = $pdo->prepare($sqlclearcombo);
            $execclearcombo->execute([
                'idproducto' => $this->idproducto
            ]);
            if ($execclearcombo->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execclearcombo->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sql = "INSERT INTO fe_combos (idprodcomb,com_idart,com_costo) VALUES(:idproducto,:idarts,:costo)";
            foreach ($detalle as $d) {
                $exec = $pdo->prepare($sql);
                $exec->execute([
                    'idproducto' => $this->idproducto,
                    'idarts' => $d['idart'],
                    'costo' => $d['costo']
                ]);
                $this->costo = $this->costo + $d['costo'];
            }
            $sqlupdateart = "update fe_art set cost=:costo,prec=:costo,premay=:costo,premen=:costo,pre3=:costo where idart=:idart";
            $execupdateart = $pdo->prepare($sqlupdateart);
            $execupdateart->execute([
                'idart' => $this->idproducto,
                'costo' => $this->costo
            ]);
            if ($execupdateart->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execupdateart->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($exec->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exec->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se generó el combo correctamente ", "ndoc" => '', "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }

    function buscarid()
    {
        $sql = "SELECT c.*,a.descri
        FROM fe_combos c 
        INNER JOIN fe_art a ON c.com_idart=a.idart 
        WHERE idprodcomb=:id AND com_acti='A'";
        $query = $this->prepare($sql);
        $query->execute(["id" => $this->idproducto]);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}
