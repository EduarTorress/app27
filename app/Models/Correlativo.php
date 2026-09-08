<?php

namespace App\Models;

use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Correlativo extends Modelo
{
    public function Obtenercorrelativo($nserie, $ctdoc)
    {
        try {
            $cletra = '';
            switch ($ctdoc) {
                case '01':
                    $cletra = 'F';
                    break;
                case '03':
                    $cletra = 'B';
                    break;
                case '31':
                    $cletra = 'V';
                    break;
                case '20':
                    $cletra = 'P';
                    break;
                case '09':
                    $cletra = 'T';
                    break;
                case '07':
                    $cletra = 'FN';
                    break;
            }
            $serie = array();
            $lsql = "SELECT nume,items,idserie FROM fe_serie WHERE serie=:nserie AND tdoc=:ctdoc limit 1";
            $query = $this->prepare($lsql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                "nserie" => $nserie,
                "ctdoc" => $ctdoc
            ]);
            if ($query->rowCount() > 0) {
                foreach ($query as $r) {
                    if (strlen($cletra) == 2) {
                        $serie1 = $cletra . str_pad((string)$nserie, 2, "0", STR_PAD_LEFT);
                    } else {
                        $serie1 = $cletra . str_pad((string)$nserie, 3, "0", STR_PAD_LEFT);
                    }
                    if ($ctdoc == '21') {
                        $serie1 = str_pad((string)$nserie, 3, "0", STR_PAD_LEFT);
                        $correlativo = str_pad((string)$r['nume'], 7, "0", STR_PAD_LEFT);
                    } else {
                        $correlativo = str_pad((string)$r['nume'], 8, "0", STR_PAD_LEFT);
                    }
                    if ($ctdoc == 'VV') {
                        $serie1 = str_pad((string)$nserie, 4, "0", STR_PAD_LEFT);
                        $correlativo = str_pad((string)$r['nume'], 8, "0", STR_PAD_LEFT);
                    }
                    $serie = array([
                        'idserie'   => $r['idserie'],
                        'nume' => $r['nume'],
                        'correlativo' => $serie1 . $correlativo,
                        'estado' => 1
                    ]);
                }
            } else {
                $serie = array([
                    'idserie'   => 0,
                    'nume' => 0,
                    'correlativo' => '',
                    'estado' => 0
                ]);
            }
            return $serie;
        } catch (PDOException $e) {
            echo 'Error al conectar ' . $e;
        }
    }
}
