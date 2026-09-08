<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;
use PDOStatement;

class Serie extends Modelo
{
    public $nidserie;
    function mostrar()
    {
        $csql = "SELECT * FROM fe_serie WHERE tdoc=21 ORDER BY 3";
        try {
            $query = $this->prepare($csql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    function obtenerSerieDadoAlma($almacen)
    {
        $csql = "SELECT idserie,su.nomb AS tienda,serie FROM fe_serie se INNER JOIN fe_sucu su ON se.codt=su.idalma WHERE codt=:almacen LIMIT 1";
        try {
            $query = $this->prepare($csql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'almacen' => $almacen
            ]);
            $resultado = $query->fetchAll();
            $_SESSION['idalmacen'] = $almacen;
            $_SESSION['almacen'] = $almacen;
            $_SESSION['serie'] = $resultado[0]['idserie'];
            $_SESSION['nserie'] = $resultado[0]['serie'];
            $_SESSION['tienda'] = $resultado[0]['tienda'];
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    function obtenerUltimoNumero($tdoc)
    {
        $csql = "SELECT nume FROM fe_serie WHERE tdoc=:tdoc AND codt=1 AND serie=1";
        try {
            $query = $this->prepare($csql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'tdoc' => $tdoc
            ]);
            $resultado = $query->fetchAll();
            return $resultado;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    static function aumentarcorrelativo($idserie, $pdo)
    {
        $csql = "update fe_serie set nume=nume+1 where idserie=:idserie";
        try {
            $query = $pdo->prepare($csql);
            $query->execute([
                'idserie' => $idserie
            ]);
            return true;
        } catch (PDOException $e) {
            echo 'ocurrio un error' . $e;
            return false;
        }
    }
}
