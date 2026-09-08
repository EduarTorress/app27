<?php

namespace App\Models;

use Core\Routing\Modelo;
use PDOException;
use PDO;

class SegmentosClientes extends Modelo
{
    function Listar()
    {
        try {
            $sql = 'select segm_segm,segm_idse FROM fe_segmento where segm_acti="A" ORDER BY segm_idse';
            $query = $this->prepare($sql);
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (PDOException $e) {
            echo 'Ocurrio un error' . $e;
        }
    }
}
