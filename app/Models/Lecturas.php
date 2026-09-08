<?php

namespace App\Models;

use PDO;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDOException;

class Lecturas extends Modelo
{
    var $nidcon = 0;
    var $nidtur = 0;
    var $ninic = 0;
    var $ninim = 0;
    var $dfecha = "";
    var $nidus = 0;
    var $ccoda = 0;
    var $clado = "";
    var $nprecio = 0;
    var $nidle = 0;
    var $detalle = [];

    function buscarhistorial($dfi, $dff, $cmbalmacen)
    {
        $nlado1 = 0;
        $nlado2 = 0;
        switch ($cmbalmacen) {
            case 1:
                $nlado1 = 1;
                $nlado2 = 2;
                break;
            case 2:
                $nlado1 = 3;
                $nlado2 = 4;
                break;
            case 3:
                $nlado1 = 5;
                $nlado2 = 6;
                break;
            case 4:
                $nlado1 = 7;
                $nlado2 = 8;
                break;
        }
        $sql = "SELECT descri AS producto,lect_cfinal as final,lect_inic AS inicial,lect_cfinal-lect_inic as cantidad,lect_prec as Precio,
		Round((lect_cFinal-lect_inic)*lect_prec,2) As Ventas,
		lect_mfinal as montofinal,lect_inim as montoinicial,lect_mfinal-lect_inim as monto,lect_lado AS manguera,lect_idco AS surtidor,
		u.nomb as Cajero,lect_fope as InicioTurno,lect_fope1 as FinTurno,lect_idtu as turno,lect_idle as Idlecturas,lect_idar AS codigo,lect_sgtel,'a' as orden
		FROM fe_lecturas AS l
		INNER JOIN fe_art AS a ON a.idart=l.lect_idar
		inner join fe_usua as u on u.idusua=l.lect_idus
		WHERE lect_acti='A' and lect_fech=:fechafinal and lect_idco in(:nlado1,:nlado2) order by u.nomb,descri,lect_idco";
        //  and lect_idin=<<this.nidlectura>>
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($sql);
        $st->execute([
            'fechafinal' => $dff,
            'nlado1' => $nlado1,
            'nlado2' => $nlado2,
        ]);
        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function registrar()
    {
        $data = array();
        $con = new conexion();
        $pdo = $con->conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();

            $idle = 0;
            switch ($_SESSION['idalmacen']) {
                case 1:
                    $idle = $_SESSION['gene_idle1'];
                    $sqllecturas = "UPDATE fe_gene SET idle1=" . (intval($_SESSION['gene_idle1']) + 1) . " ; " .
                        "UPDATE fe_lecturas SET lect_esta='C' WHERE lect_idco in (1, 2) AND lect_acti='A' AND lect_esta='A';";
                    $execlecturas = $pdo->prepare($sqllecturas);
                    $execlecturas->execute();
                    break;
                case 2:
                    $idle = $_SESSION['gene_idle2'];
                    $sqllecturas = "UPDATE fe_gene SET idle2=" . (intval($_SESSION['gene_idle2']) + 1) . " ; " .
                        "UPDATE fe_lecturas SET lect_esta='C' WHERE lect_idco in (3,4) AND lect_acti='A' AND lect_esta='A';";
                    $execlecturas = $pdo->prepare($sqllecturas);
                    $execlecturas->execute();
                    break;
                case 3:
                    $idle = $_SESSION['gene_idle3'];
                    $sqllecturas = "UPDATE fe_gene SET idle3=" . (intval($_SESSION['gene_idle3']) + 1) . " ; " .
                        "UPDATE fe_lecturas SET lect_esta='C' WHERE lect_idco in (5,6) AND lect_acti='A' AND lect_esta='A';";
                    $execlecturas = $pdo->prepare($sqllecturas);
                    $execlecturas->execute();
                    break;
                case 4:
                    $idle = $_SESSION['gene_idle4'];
                    $sqllecturas = "UPDATE fe_gene SET idle4=" . (intval($_SESSION['gene_idle4']) + 1) . " ; " .
                        "UPDATE fe_lecturas SET lect_esta='C' WHERE lect_idco in (7,8) AND lect_acti='A' AND lect_esta='A';";
                    $execlecturas = $pdo->prepare($sqllecturas);
                    $execlecturas->execute();
                    break;
            }

            $sql = "SELECT FUNINGRESALECTURA(:nidcon,:nidtur,:ninic,
                    :ninim,:dfecha,:nidus,:ccoda,:clado,:nprecio,:nidle) AS id";
            $exec = $pdo->prepare($sql);
            foreach ($this->detalle as $d) {
                $exec->execute([
                    'nidcon'  => $d['surtidor'],
                    'nidtur'  => 0,
                    'ninic'   => $d['cantidad'],
                    'ninim'   => $d['monto'],
                    'dfecha'  => date('Y-m-d'),
                    'nidus'   => session()->get("usuario_id"),
                    'ccoda'   => $d['idart'],
                    'clado'   => $d['lado'],
                    'nprecio' => $d['precio'],
                    'nidle'   => $idle
                ]);
            }
            $pdo->commit();
            $data = ["mensaje" => 'Se guardo satisfactoriamente la lectura ', 'estado' => '1'];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $data = ["mensaje" => 'Hubieron problemas al registrar' . $pdo_error->getMessage(), 'estado' => '0'];
        } finally {
            $con->close();
        }
        return json_encode($data);
    }
    function listaregistro($cmbalmacen)
    {
        $sql = "call ListarContometroxisla(:cmbalmacen)";
        //  and lect_idin=<<this.nidlectura>>
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($sql);
        $st->execute([
            'cmbalmacen' => $cmbalmacen,
        ]);
        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
}
