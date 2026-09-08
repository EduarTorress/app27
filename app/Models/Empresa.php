<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Empresa extends Modelo
{
    function obtenerdatosempresa()
    {
        try {
            $sql = "SELECT empresa,nruc,gene_usol,gene_csol,gene_usol1,gene_csol1 FROM fe_gene WHERE idgene=1";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo ('Error al Conectar' . $e->getMessage());
        }
    }
    function actualizaFecha()
    {
        $fechaact = date("Y-m-d");
        $fechasis = session()->get("gene_fech");
        if ($fechasis < $fechaact) {
            $valordolar = $this->obtenerdolar($fechaact);
            if (empty($valordolar) || $valordolar == 'null' || $valordolar == null) {
                $valordolar = session()->get("gene_dola");
            }
            session()->set("gene_dola", $valordolar);
            $this->cambiargene($fechaact, $valordolar);
        }
    }
    function obtenerdolar($df)
    {
        $url = 'https://companiasysven.com/tc.php';
        $data = array("dfi" => $df, "dff" => $df);

        $postdata = json_encode($data);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $rpta = json_decode($response, true);
            $valordolar = $rpta['data'][0]['precio_venta'];
            return $valordolar;
        }
    }
    function cambiargene($fech, $dolar)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $sqlgene = "update fe_gene set fech=:fech,dola=:dolar";
            $execgene = $pdo->prepare($sqlgene);
            $execgene->execute([
                'fech' => $fech,
                'dolar' => $dolar
            ]);
            $sqlvalordolar = "INSERT INTO fe_mon(fech,valor) VALUES (:fech,:mon)";
            $execvalordolar = $pdo->prepare($sqlvalordolar);
            $execvalordolar->execute([
                'fech' => $fech,
                'mon' => $dolar
            ]);
        } catch (PDOException $e) {
            echo ('Error al realizar la actualizacion' . $e->getMessage());
        }
    }
}
