<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Dashboard extends Modelo
{
    function totalproductos()
    {
        try {
            $sql = "SELECT COUNT(*) FROM fe_art WHERE prod_acti='A'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalclientes()
    {
        try {
            $sql = "SELECT COUNT(*) FROM fe_clie WHERE `clie_acti`='A'";
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalventas()
    {
        try {
            $sql = "SELECT IF(COUNT(*) IS NULL,0,COUNT(*)) 
            FROM fe_rcom 
            WHERE acti='A' and idcliente>0 AND fech=CURRENT_DATE() " .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ');
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function montoventassoles()
    {
        try {
            $sql = "SELECT IF(SUM(impo) IS NULL,0.00,SUM(impo)) 
            FROM fe_rcom 
            WHERE acti='A' and idcliente>0 AND fech=CURRENT_DATE() AND mone='S'" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ');
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function montoventasdolares()
    {
        try {
            $sql = "SELECT IF(SUM(impo) IS NULL,0.00,SUM(impo)) 
            FROM fe_rcom 
            WHERE acti='A' AND fech=CURRENT_DATE() AND mone='D'" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ');;
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalpedidos()
    {
        try {
            $sql = "SELECT IF(COUNT(*) IS NULL,0,COUNT(*)) 
            FROM fe_rped 
            WHERE acti='A' AND fech=CURRENT_DATE() " .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ');;
            $query = $this->prepare($sql);
            $query->execute();
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalventaspormes()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT MONTHNAME(fech) AS mes,COUNT(*) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idcliente>0 and year(fech)=year(CURRENT_DATE())" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
                " GROUP BY MONTH(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
     function totalcompraspormes()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT MONTHNAME(fech) AS mes,COUNT(*) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idprov>0 and year(fech)=year(CURRENT_DATE())" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
                " GROUP BY MONTH(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalpedidospormes()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT MONTHNAME(fech) AS mes,
            COUNT(*) AS total
            FROM fe_rped 
            WHERE acti='A' AND idclie>0 and year(fech)=year(CURRENT_DATE())" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
                " GROUP BY MONTH(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalmontocompras()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT YEAR(fech) AS ano,SUM(impo) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idprov>0" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
            " GROUP BY YEAR(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalmontoventas()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT YEAR(fech) AS ano,SUM(impo) AS total
            FROM fe_rcom 
            WHERE acti='A' AND idcliente>0" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
            " GROUP BY YEAR(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
    function totalmontopedidos()
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqllanguage = "SET lc_time_names = 'es_ES';";
            $execlaguage = $pdo->prepare($sqllanguage);
            $execlaguage->execute();

            $sql = "SELECT YEAR(fech) AS ano,SUM(impo) AS total
            FROM fe_rped 
            WHERE acti='A' AND idclie>0" .
                (!empty($_SESSION['config']['multiempresa']) ? ' and codt=' . $_SESSION['idalmacen'] : ' ') .
            " GROUP BY YEAR(fech)";
            $query = $pdo->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll();

            $pdo->commit();
            $ncon->close();
            return $resultado;
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo ('Error al consultar' . $e->getMessage());
        }
    }
}
