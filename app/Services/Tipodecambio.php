<?php

namespace App\Services;

use App\Models;
use Core\Clases\conexion;
use PDO;

class Tipodecambio
{
    public static function dtipocambiosistema()
    {
        $ncon = new conexion();
        $csql = "select dola from fe_gene where idgene=1";
        $query = $ncon->conectar()->prepare($csql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $ndolar = $result->dola;
            }
        }
        return $ndolar;
    }
}
