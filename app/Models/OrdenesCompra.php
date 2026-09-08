<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class OrdenesCompra extends Modelo
{
    public $ctdoc = "";
    public $cforma = "";
    public $cndoc = "";
    public $dfecha = "";
    public $dfechar = "";
    public $cdetalle = "";
    public $nimpo1 = 0;
    public $nimpo2 = 0;
    public $nimpo3 = 0;
    public $nimpo4 = 0;
    public $nimpo5 = 0;
    public $nimpo6 = 0;
    public $nimpo7 = 0;
    public $nimpo8 = 0;
    public $cguia = "";
    public $cmoneda = "";
    public $ndolar = 0;
    public $vigv = 0;
    public $ctipo = "";
    public $nidprov = 0;
    public $ctipo1 = "";
    public $nidusua = 0;
    public $nidt = 0;
    public $nreg = 0;
    public $nidcta1 = 0;
    public $nidcta2 = 0;
    public $nidcta3 = 0;
    public $nidcta4 = 0;
    public $nidctai = 0;
    public $nidctae = 0;
    public $nidcta7 = 0;
    public $nidctat = 0;
    public $idcta1 = 0;
    public $idcta2 = 0;
    public $idcta3 = 0;
    public $idcta4 = 0;
    public $idcta5 = 0;
    public $idcta6 = 0;
    public $idcta7 = 0;
    public $idcta8 = 0;
    public $ct1 = "";
    public $ct2 = "";
    public $ct3 = "";
    public $ct4 = "";
    public $ct5 = "";
    public $ct6 = "";
    public $ct7 = "";
    public $ct8 = "";
    public $cproveedor = "";
    public $serie = "";
    public $ndoc = "";
    public $nforma = 0;
    public $nmontor = 0;
    public $cformaregistrada = "";
    public $ntienepagos = 0;
    public $cencontrado = "";
    public $conedaregistrada = "";

    function listarxFecha($dfi, $dff, $txtidproveedor)
    {
        try {
            $b = ($txtidproveedor == '0') ? ' and ocom_idpr<>:txtidproveedor  ' : ' and ocom_idpr=:txtidproveedor ';
            $sql = "SELECT ocom_idroc AS nidauto,ocom_ndoc as ndoc,ocom_fech AS fech,ocom_idpr AS idprov,ocom_impo AS impo,p.razo
                FROM fe_rocom o
                inner join fe_prov as p on o.ocom_idpr=p.idprov
                where ocom_acti='A'
                " . $b .
                "  AND ocom_fech between :dfi and :dff ORDER BY ocom_fech";
            $query = $this->prepare($sql);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'txtidproveedor' => $txtidproveedor
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function buscarOrdenCompraPorId($idauto)
    {
        $sql = "SELECT `b`.`doco_iddo`  AS `doco_iddo`,	  `b`.`doco_coda`  AS `doco_coda`,	  `b`.`doco_cant`  AS `doco_cant`,	  `b`.`doco_prec`  AS `doco_prec`,
                `c`.`descri`     AS `descri`,	  `c`.`prod_smin`  AS `prod_smin`,
                `c`.`unid`       AS `unid`,c.prod_cod1,	  `c`.`prod_smax`  AS `prod_smax`,	  `a`.`ocom_valor` AS `ocom_valor`,
                `a`.`ocom_igv`   AS `ocom_igv`,	  `a`.`ocom_impo`  AS `ocom_impo`,	  `a`.`ocom_idroc` AS `ocom_idroc`,	  `a`.`ocom_fech`  AS `ocom_fech`,
                `a`.`ocom_idpr`  AS `ocom_idpr`,	  `a`.`ocom_desp`  AS `ocom_desp`,	  `a`.`ocom_form`  AS `ocom_form`,	  `a`.`ocom_mone`  AS `ocom_mone`,
                `a`.`ocom_ndoc`  AS `ocom_ndoc`,	  `a`.`ocom_tigv`  AS `ocom_tigv`,	  `a`.`ocom_obse`  AS `ocom_obse`,	  `a`.`ocom_aten`  AS `ocom_aten`,
                `a`.`ocom_deta`  AS `ocom_deta`,	  `a`.`ocom_idus`  AS `ocom_idus`,	  `a`.`ocom_fope`  AS `ocom_fope`,	  `a`.`ocom_idpc`  AS `ocom_idpc`,
                `a`.`ocom_idac`  AS `ocom_idac`,	  `a`.`ocom_fact`  AS `ocom_fact`,	  `d`.`razo`       AS `razo`,	  `e`.`nomb`       AS `nomb`,d.nruc 
                FROM `fe_rocom` `a`
                JOIN `fe_docom` `b` ON `b`.`doco_idro` = `a`.`ocom_idroc`
                JOIN `fe_art` `c` ON `b`.`doco_coda` = `c`.`idart`
                JOIN `fe_prov` `d` ON `d`.`idprov` = `a`.`ocom_idpr`
                JOIN `fe_usua` `e` ON `e`.`idusua` = `a`.`ocom_idus`
                WHERE `a`.`ocom_acti` <> 'I'   AND `b`.`doco_acti` <> 'I' AND a.ocom_idroc=:nidauto";
        $query = $this->prepare($sql);
        $query->execute([
            'nidauto' => $idauto
        ]);
        $rs=$query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function grabar($cabecera)
    {
        $this->dfecha = $cabecera["fechi"];
        $this->dfechar = $cabecera["fechf"];

        $sqlioc = "SELECT FunIngresaOrdenCompra(:dfecha,:nidpr,:cmone,
         :cndoc,:ctigv,:cobse,:caten,:cdeta,:cidpc,:nidus,:cdespacho,:cforma,:nv,:nigv,:nimpo) AS ID";

        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], 'OC');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execioc = $pdo->prepare($sqlioc);
            $execioc->execute([
                'dfecha' => $this->dfecha,
                'nidpr' => $cabecera["idprov"],
                'cmone' => $cabecera["mon"],
                'cndoc' =>  $this->cndoc,
                'ctigv' => 'I',
                'cobse' => $cabecera["txtobservacion"],
                'caten' => $cabecera["txtatencion"],
                'cdeta' => $cabecera["deta"],
                'cidpc' => 'WEB',
                'nidus' => $_SESSION['usuario_id'],
                'cdespacho' => $cabecera["txtdespacho"],
                'cforma' => $cabecera['txtobservacion'],
                'nv' => $cabecera["valor"],
                'nigv' => $cabecera["nigv"],
                'nimpo' => $cabecera["impo"],
            ]);

            if ($execioc->errorCode() != '00000') {
                var_dump($execioc->debugDumpParams());
                $pdo->rollBack();
                return false;
            }

            $id = $execioc->fetchColumn();

            $sqlid = "CALL ProIngresaDetalleOCompra(:nauto,:coda,:cant,:prec)";
            $carritococ = session()->get('carritococ', []);

            $sw = 1;
            foreach ($carritococ as $item) {
                if ($item['activo'] == 'A') {
                    $execid = $pdo->prepare($sqlid);
                    $cant = floatval($item['cantidad']);
                    $prec = floatval($item['precio']);
                    $execid->execute([
                        "nauto" => $id,
                        "coda" => $item['coda'],
                        "cant" => $cant,
                        "prec" => $prec
                    ]);
                    if ($execid->errorCode() != '00000') {
                        $sw = 0;
                        break;
                    }
                }
            }

            if ($sw == 0) {
                $execid->debugDumpParams();
                $pdo->rollBack();
                return false;
            }

            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al aumentar correlativo', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($execid->errorCode() == '00000') {
                $pdo->commit();
            }
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function actualizar($cabecera)
    {
        $this->dfecha = $cabecera["fechi"];
        $this->dfechar = $cabecera["fechf"];

        //     PROCEDURE `ProActualizaOrdenCompra`(
        //     dfecha DATE,nidpr INTEGER,cmone CHAR,
        //     cndoc VARCHAR(10),ctigv CHAR,cobse VARCHAR(200),caten VARCHAR(200),cdeta VARCHAR(200),
        //     nidus INTEGER,nid INTEGER,cdespacho VARCHAR(60),cforma VARCHAR(60),nv FLOAT,nigv FLOAT,nimpo FLOAT)

        $sqlaoc = "CALL PROACTUALIZAORDENCOMPRA(:dfecha,:nidpr,:cmone,:cndoc,:ctigv,:cobse,
            :caten,:cdeta,:nidus,:nid,:cdespacho,:cforma,:nv,:nigv,:nimpo)";
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execaoc = $pdo->prepare($sqlaoc);
            $execaoc->execute([
                'dfecha' => $this->dfecha,
                'nidpr' => $cabecera["idprov"],
                'cmone' => $cabecera["mon"],
                'cndoc' => $_SESSION['cndococ'] . $_SESSION['numoc'],
                'ctigv' => 'I',
                'cobse' => $cabecera["txtobservacion"],
                'caten' => $cabecera["txtatencion"],
                'cdeta' => $cabecera["deta"],
                'nidus' => $_SESSION['usuario_id'],
                'nid' => $cabecera["nidauto"],
                'cdespacho' => $cabecera["txtdespacho"],
                'cforma' => $cabecera['txtobservacion'],
                'nv' => $cabecera["valor"],
                'nigv' => $cabecera["nigv"],
                'nimpo' => $cabecera["impo"],
            ]);

            $execaoc->closeCursor();

            if ($execaoc->errorCode() != '00000') {
                $pdo->rollBack();
                return false;
            }

            $sqlinserta = "CALL ProIngresaDetalleOCompra(:nauto,:coda,:cant,:prec)";
            $sqlactualiza = "CALL ProActualizaOCompra(:nauto,:opt,:coda,:cant,:prec)";
            $carritococ = session()->get('carritococ', []);

            $sw = 1;
            foreach ($carritococ as $item) {
                if ($item['activo'] == 'A') {
                    if ($item['nreg'] == 0) {
                        $exec = $pdo->prepare($sqlinserta);
                        $cant = floatval($item['cantidad']);
                        $prec = floatval($item['precio']);
                        $exec->execute([
                            "nauto" => $cabecera['nidauto'],
                            "coda" => $item['coda'],
                            "cant" => $cant,
                            "prec" => $prec
                        ]);
                    } else {
                        $exec = $pdo->prepare($sqlactualiza);
                        $cant = floatval($item['cantidad']);
                        $prec = floatval($item['precio']);
                        $exec->execute([
                            "nauto" => $cabecera['nidauto'],
                            "opt" => 'C',
                            "coda" => $item['coda'],
                            "cant" => $cant,
                            "prec" => $prec
                        ]);
                    }
                } else {
                    if ($item['nreg'] > 0) {
                        $exec = $pdo->prepare($sqlactualiza);
                        $cant = floatval($item['cantidad']);
                        $prec = floatval($item['precio']);
                        $exec->execute([
                            "nauto" => $cabecera['nidauto'],
                            "opt" => 'E',
                            "coda" => $item['coda'],
                            "cant" => $cant,
                            "prec" => $prec
                        ]);
                    }
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                return false;
            }
            if ($exec->errorCode() == '00000') {
                $pdo->commit();
                $ncon->close();
            }
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
}
