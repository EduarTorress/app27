<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class DatosGlobales extends Modelo
{
    public function informacion()
    {
        try {
            $sql = "SELECT * FROM fe_gene where idgene=1";
            $query = $this->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $query->fetchAll();
            // return $rs;
            // $_SESSION['fe_gene']=$rs;
            \session()->set('fe_gene', $rs);
        } catch (PDOException $e) {
            echo ('Error al Conectar' . $e->getMessage());
        }
    }
    // function info()
    // {
    //     try {
    //         $sql = "SELECT * FROM fe_gene where idgene=1";
    //         $query = $this->prepare($sql);
    //         $query->execute();
    //         $resultado = $query->fetchAll();
    //         return $resultado;
    //     } catch (PDOException $e) {
    //         echo ('Error al Conectar' . $e->getMessage());
    //     }
    // }
}
