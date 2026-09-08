<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Caja extends Modelo
{
    var $txtnombre = "";
    var $dfecha = "";
    var $cndoc = "";
    var $cdeta = "";
    var $sdeudor = 0;
    var $sacreedor = 0;
    var $cmone = "";
    var $ndolar = "";
    var $nidus = "";
    var $nidt = "";
    var $cmbformapago = "";

    function buscar($fech, $nidusua, $codt)
    {
        $lista = [];
        $sql = "SELECT deta,ndoc,
			ROUND(CASE forma WHEN 'E' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS efectivo,
			ROUND(CASE forma WHEN 'C' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS credito,
			ROUND(CASE forma WHEN 'D' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS deposito,
			ROUND(CASE forma WHEN 'H' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS cheque,
			ROUND(CASE forma WHEN 'T' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS tarjeta,
			ROUND(CASE forma WHEN 'R' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS Centrega,
			ROUND(CASE forma WHEN 'Y' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS yape,
		    ROUND(CASE tipo WHEN 'S' THEN IF(forma='E',impo,0) ELSE 0 END,2) AS egresos,
			usuavtas,fechao,usua,forma,mone,tmon1,dola,nimpo,tipo,tdoc,idcredito,iddeudas,idauto,orden
			FROM(
			SELECT a.lcaj_tdoc AS tdoc,a.lcaj_form AS forma,IF(lcaj_deud<>0,'I',IF(lcaj_acre=0,'I','S')) AS tipo,lcaj_dcto AS ndoc,
			IF(lcaj_deud<>0,lcaj_deud,IF(lcaj_acre=0,lcaj_deud,lcaj_acre)) AS impo,
            lcaj_deta AS deta,lcaj_mone AS  mone,lcaj_idcr AS idcredito,lcaj_idde AS iddeudas,lcaj_idau AS idauto,
			c.nomb AS usua,a.lcaj_fope AS fechao,IFNULL(u.nomb,'') AS usuavtas,a.lcaj_mone AS tmon1,lcaj_dola AS dola,
			IF(a.lcaj_deud<>0,lcaj_deud,lcaj_acre) AS nimpo,'1' AS orden FROM
			fe_lcaja AS a INNER JOIN fe_usua AS c ON
			c.idusua=a.lcaj_idus LEFT JOIN fe_rcom AS r ON r.idauto=a.lcaj_idau LEFT JOIN fe_usua AS u
			ON u.idusua=r.idusua WHERE lcaj_fech=:fech AND lcaj_acti<>'I' AND lcaj_idau>0 AND a.lcaj_idus=:nidusua
			UNION ALL
			SELECT a.lcaj_tdoc,a.lcaj_form AS forma,IF(lcaj_deud<>0,'I','S') AS tipo,a.lcaj_ndoc AS ndoc,IF(a.lcaj_deud<>0,lcaj_deud,lcaj_acre) AS impo,
            a.lcaj_deta AS deta,a.lcaj_mone AS mone,lcaj_idcr AS idcredito,lcaj_idde AS iddeudas,lcaj_idau AS idauto,
			c.nomb AS usua,a.lcaj_fope AS fechao,IFNULL(u.nomb,'') AS usuavtas,a.lcaj_mone AS tmon1,a.lcaj_dola AS dola,a.lcaj_deud AS nimpo,
			IF(lcaj_deud>0,'2','3') AS orden FROM
			fe_lcaja AS a INNER JOIN fe_usua AS c ON c.idusua=a.lcaj_idus LEFT JOIN fe_rcom AS r ON r.idauto=a.lcaj_idau LEFT JOIN fe_usua AS u
			ON u.idusua=r.idusua
			WHERE lcaj_fech=:fech AND lcaj_acti<>'I' AND lcaj_idau=0 AND a.lcaj_idus=:nidusua)
			AS b ORDER BY orden,ndoc,tdoc";
        $query = $this->prepare($sql);
        $query->execute([
            'fech' => $fech,
            'nidusua' => $nidusua
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function buscarmulti($fech, $nidusua, $codt)
    {
        $lista = [];
        $sql = "SELECT deta,ndoc,
			ROUND(CASE forma WHEN 'E' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS efectivo,
			ROUND(CASE forma WHEN 'C' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS credito,
			ROUND(CASE forma WHEN 'D' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS deposito,
			ROUND(CASE forma WHEN 'H' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS cheque,
			ROUND(CASE forma WHEN 'T' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS tarjeta,
			ROUND(CASE forma WHEN 'R' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS Centrega,
			ROUND(CASE forma WHEN 'Y' THEN IF(tipo='I',impo,0) ELSE 0 END,2) AS yape,
		    ROUND(CASE tipo WHEN 'S' THEN IF(forma='E',impo,0) ELSE 0 END,2) AS egresos,
			usuavtas,fechao,usua,forma,mone,tmon1,dola,nimpo,tipo,tdoc,idcredito,iddeudas,idauto,orden
			FROM(
			SELECT a.lcaj_tdoc AS tdoc,a.lcaj_form AS forma,IF(lcaj_deud<>0,'I',IF(lcaj_acre=0,'I','S')) AS tipo,lcaj_dcto AS ndoc,
			IF(lcaj_deud<>0,lcaj_deud,IF(lcaj_acre=0,lcaj_deud,lcaj_acre)) AS impo,
            lcaj_deta AS deta,lcaj_mone AS  mone,lcaj_idcr AS idcredito,lcaj_idde AS iddeudas,lcaj_idau AS idauto,
			c.nomb AS usua,a.lcaj_fope AS fechao,IFNULL(u.nomb,'') AS usuavtas,a.lcaj_mone AS tmon1,lcaj_dola AS dola,
			IF(a.lcaj_deud<>0,lcaj_deud,lcaj_acre) AS nimpo,'1' AS orden FROM
			fe_lcaja AS a INNER JOIN fe_usua AS c ON
			c.idusua=a.lcaj_idus LEFT JOIN fe_rcom AS r ON r.idauto=a.lcaj_idau LEFT JOIN fe_usua AS u
			ON u.idusua=r.idusua WHERE lcaj_fech=:fech AND lcaj_acti<>'I' AND lcaj_idau>0 AND lcaj_codt=:codt 
			UNION ALL
			SELECT a.lcaj_tdoc,a.lcaj_form AS forma,IF(lcaj_deud<>0,'I','S') AS tipo,a.lcaj_ndoc AS ndoc,IF(a.lcaj_deud<>0,lcaj_deud,lcaj_acre) AS impo,
            a.lcaj_deta AS deta,a.lcaj_mone AS mone,lcaj_idcr AS idcredito,lcaj_idde AS iddeudas,lcaj_idau AS idauto,
			c.nomb AS usua,a.lcaj_fope AS fechao,IFNULL(u.nomb,'') AS usuavtas,a.lcaj_mone AS tmon1,a.lcaj_dola AS dola,a.lcaj_deud AS nimpo,
			IF(lcaj_deud>0,'2','3') AS orden FROM
			fe_lcaja AS a INNER JOIN fe_usua AS c ON c.idusua=a.lcaj_idus LEFT JOIN fe_rcom AS r ON r.idauto=a.lcaj_idau LEFT JOIN fe_usua AS u
			ON u.idusua=r.idusua
			WHERE lcaj_fech=:fech AND lcaj_acti<>'I' AND lcaj_idau=0 AND lcaj_codt=:codt)
			AS b ORDER BY orden,ndoc,tdoc";
        $query = $this->prepare($sql);
        $query->execute([
            'fech' => $fech,
            'codt' => $codt
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function verSaldo($fechi, $fechf, $nidusua)
    {
        $lista = [];
        $sql = "SELECT SUM(IF(a.lcaj_deud<>0,lcaj_deud,0)) AS ingresoss,SUM(IF(a.lcaj_acre<>0,lcaj_acre,0)) AS egresoss, SUM(IF(a.lcaj_deud<>0,lcaj_deud,0)) - SUM(IF(a.lcaj_acre<>0,lcaj_acre,0)) AS saldoanterior
	    FROM fe_lcaja  AS a WHERE  a.lcaj_fech BETWEEN :fechi AND :fechf AND a.lcaj_acti='A' AND a.lcaj_form='E' AND lcaj_idus=:nidusua";
        $query = $this->prepare($sql);
        $query->execute([
            'fechi' => $fechi,
            'fechf' => $fechf,
            'nidusua' => $nidusua
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function registramovimientoscaja($idserie = 0)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $sqlIC = "select FunIngresaDatosLcajaE(:dfecha,:cndoc,:cdeta,:idcta,:sdeudor,
            :sacreedor,:cmone,:ndolar,:nidus,:nidcp,:nidauto,:cform)";
            $exIC = $pdo->prepare($sqlIC);
            $exIC->execute([
                'dfecha' => $this->dfecha,
                'cndoc' =>  $this->cndoc,
                'cdeta' =>  $this->cdeta,
                'idcta' => 0,
                'sdeudor' =>  $this->sdeudor,
                'sacreedor' =>  $this->sacreedor,
                'cmone' =>  'S',
                'ndolar' => session()->get("gene_dola"),
                'nidus' => session()->get("usuario_id"),
                'nidcp' => 0,
                'nidauto' => 0,
                'cform' => 'E',
                'cdcto' => '',
                'ncodt' =>  $_SESSION['idalmacen'],
                'nturno' => 0
            ]);
            if ($idserie != 0) {
                if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                    $pdo->rollBack();
                    $rpta = array('mensaje' => 'Error al aumentar correlativo', "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registró correctamente", "data" => [], "estado" => '1');
        } catch (PDOException $pdoexception) {
            $pdo->rollback();
            $rpta = array('mensaje' => $pdoexception->getMessage(), "data" => [], "estado" => '0');
        }
        return $rpta;
    }
    function buscarcajaparaanular($ndoc)
    {
        $sql = "SELECT lcaj_ndoc as ndoc,lcaj_fech as fech,if(lcaj_deud>0,'INGRESO','EGRESO') as razo,lcaj_mone as mone,
        if(lcaj_deud>0,lcaj_deud,lcaj_acre) as importe,lcaj_idca as idauto,'1' as estadoenviado,'CE' as tdoc
         FROM fe_lcaja WHERE lcaj_ndoc LIKE :lcaj_ndoc AND lcaj_acti='A'";
        $query = $this->prepare($sql);
        $query->execute([
            'lcaj_ndoc' => '%' . $ndoc . '%'
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function anularmvtocaja($idauto, $idusua)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $sqlIC = "update fe_lcaja set lcaj_acti='I',lcaj_idus=:nidus where lcaj_idca=:id";
            $exIC = $pdo->prepare($sqlIC);
            $exIC->execute([
                'nidus' => $idusua,
                'id' =>  $idauto
            ]);
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se dió correctamente", "data" => [], "estado" => '1');
        } catch (PDOException $pdoexception) {
            $pdo->rollback();
            $rpta = array('mensaje' => $pdoexception->getMessage(), "data" => [], "estado" => '0');
        }
        return $rpta;
    }
    function consultaringresoyegreso($documento)
    {
        $lista = [];
        $sql = "SELECT lcaj_fope AS fope,lcaj_fech AS fecha,IF(lcaj_acre>0,lcaj_acre,lcaj_deud) AS importe,lcaj_deta AS refe,
                IF(lcaj_deud>0,'Ingresos','Egresos') AS tipo,lcaj_dcto AS ndoc  FROM
                fe_lcaja AS l
                INNER JOIN fe_usua AS u ON u.idusua=l.lcaj_idus
                WHERE lcaj_dcto=:ndoc AND lcaj_acti='A' AND lcaj_form='E'";
        $query = $this->prepare($sql);
        $query->execute([
            'ndoc' => $documento
        ]);
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    function registrarTransferencia() {}
    function registrarretiroparabancos() {}
    function listarcomparabancos($fech, $nidusua, $codt)
    {
        $lista = [];
        $sql = "SELECT l.lcaj_idle AS turno,
                /* EFECTIVO EN ISLA */
                SUM(
                    CASE
                        WHEN l.lcaj_form = 'E'
                        THEN COALESCE(l.lcaj_deud, 0)
                        ELSE 0
                    END
                ) AS efectivo_isla,
                /* DEPOSITADO EN CASH TODAY */
                SUM(
                    CASE
                        WHEN l.lcaj_tran = 'T'
                        THEN COALESCE(l.lcaj_deud, 0)
                        ELSE 0
                    END
                ) AS depositado_cash_today,
                /* DIFERENCIA */
                SUM(
                    CASE
                        WHEN l.lcaj_form = 'E'
                        THEN COALESCE(l.lcaj_deud, 0)
                        ELSE 0
                    END
                )-SUM(
                    CASE
                        WHEN l.lcaj_tran = 'T'
                        THEN COALESCE(l.lcaj_deud, 0)
                        ELSE 0
                    END
                ) AS diferencia,

                /* BANCOS */
                GROUP_CONCAT(
                    DISTINCT b.banc_nomb
                    ORDER BY b.banc_nomb
                    SEPARATOR ', '
                ) AS bancos
            FROM fe_lcaja l
            LEFT JOIN fe_cbancos cb ON cb.cban_idca = l.lcaj_idca
            LEFT JOIN fe_bancos b ON b.banc_idba = cb.cban_idba
            WHERE l.lcaj_acti = 'A' AND  lcaj_fech='" . $fech . "'" . (intval($nidusua) > 0 ? ' and lcaj_idus=' . $nidusua : '') .
            " GROUP BY l.lcaj_idle 
            ORDER BY l.lcaj_idle;";
        $query = $this->prepare($sql);
        $query->execute();
        $lista = $query->fetchAll(PDO::FETCH_ASSOC);
        $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
        return $data;
    }
    // function listaringresos()
    // {
    //     $lista = [];
    //     $sql = "Select ifnull(c.ndoc,e.ndoc) As ndoc,e.fech,xx.fevto,xx.saldo,
    //             b.rcre_impc,'C' As situa,b.rcre_idau,xx.ncontrol,e.tipo,rcre_idav,e.banco,ifnull(c.ndoc,' ') As docd,ifnull(c.tdoc,' ' ) As tdoc,e.nrou,
    //             e.mone,0 As dscto,rcre_codt As codt,xxx.razo,b.rcre_impc As importec,b.rcre_idau As Idauto,e.mone As moneda,b.rcre_idrc As idrc,xxx.idclie,
    //             d.idven,d.nomv,xx.rcre_idrc
    //             From (Select Ncontrol,Round(Sum(a.Impo-a.acta),2) As saldo,Max(fevto) As fevto,rcre_idrc From fe_cred As a
    //             INNER Join fe_rcred As b On(b.rcre_idrc=a.cred_idrc)
    //             Where a.Acti='A' And b.rcre_Acti='A'
    //             And b.rcre_codt=:codt
    //             Group By Ncontrol,rcre_idrc HAVING saldo<>0) As xx
    //             INNER Join fe_rcred As b On b.rcre_idrc=xx.rcre_idrc
    //             INNER Join fe_cred As e On e.idcred=xx.Ncontrol
    //             INNER Join fe_vend As d On(d.idven=b.rcre_codv)
    //             INNER Join fe_clie As xxx On xxx.idclie=b.rcre_idcl
    //             left  Join (Select tdoc,ndoc,Idauto From fe_rcom Where idcliente>0 And Acti='A') As c On(c.Idauto=b.rcre_idau)
    //             Order By fevto";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'codt' => $_SESSION['idalmacen']
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    // function listaregresos()
    // {
    //     $lista = [];
    //     $sql = "SELECT a.ndoc,a.fech as fech,a.fevto as fechv,a.saldo,a.moneda,a.importec as impo,tdoc,a.idpr,xx.idprov,
    //             situa,idauto,ncontrol,tipo,banco,docd,tdoc,codt,dola,idrd,xx.razo,rdeu_idct,nrou FROM vpdtespago AS a
    //             INNER JOIN fe_prov AS xx ON xx.idprov=a.idpr
    //             WHERE a.codt=:codt
    //             ORDER BY fevto";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'codt' => $_SESSION['idalmacen']
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    // function listarmpagos()
    // {
    //     $lista = [];
    //     $sql = "SELECT * FROM fe_mpago where pago_acti='A'";
    //     $query = $this->prepare($sql);
    //     $query->execute();
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    // function grabaringresolibro($cabecera, $detalle)
    // {
    //     try {
    //         $correlativo = SerieController::correlativo($_SESSION['nserie'], 'LC');
    //         if ($correlativo[0]['estado'] == 0) {
    //             $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
    //             return $rpta;
    //         }

    //         $idserie = $correlativo[0]['idserie'];
    //         $this->cndoc = $correlativo[0]['correlativo'];

    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();

    //         $sqlicb = "SELECT FunIngresaCajaBancos2(:cmbctas,:txtfechai,:txtnrooperacion,:cmbmediopago,:txtreferencia,0,:txtidcliente,
    //                     :txtndoc,:cmbnrocuentas,:inputcancelacion,0,1,0,:nd) as id;";
    //         $execicb = $pdo->prepare($sqlicb);
    //         $execicb->execute([
    //             'cmbctas' => $cabecera["cmbctas"],
    //             'txtfechai' => $cabecera["txtfechai"],
    //             'txtnrooperacion' => $cabecera['txtnrooperacion'],
    //             'cmbmediopago' => $cabecera["cmbmediopago"],
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'txtidcliente' => $detalle[0]["idrazo"],
    //             'txtndoc' => $this->cndoc,
    //             'cmbnrocuentas' => $cabecera["cmbnrocuentas"],
    //             // 'inputcancelacion' => $detalle[0]["inputcancelacion"],
    //             'inputcancelacion' => $cabecera['txttotal'],
    //             'nd' => $cabecera["txttipocambio"]
    //         ]);

    //         if ($execicb->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlicb, $execicb->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }

    //         $id = $execicb->fetchColumn();

    //         $sqlipc = "SELECT FunIngresaPagosCreditosCb(:txtndoc,:saldo,'P',:moneda,:cmbctas,:txtfecha,:txtfechavto,:tipo,
    //         :ncontrol,:nrou,:idrc,:idcliente,:nidusua,:idcabecera) as id;";
    //         $execipc = $pdo->prepare($sqlipc);
    //         $execipc->execute([
    //             'txtndoc' => $this->cndoc,
    //             'saldo' =>  $detalle[0]["saldo"],
    //             'moneda' => $detalle[0]["mon"],
    //             'cmbctas' => $cabecera["cmbctas"],
    //             'txtfecha' => $detalle[0]["fech"],
    //             'txtfechavto' => $detalle[0]["fechvto"],
    //             'tipo' => $detalle[0]["tdoc"],
    //             'ncontrol' => $detalle[0]["ncontrol"],
    //             'nrou' => $detalle[0]["nrou"],
    //             'idrc' => $detalle[0]["idrc"],
    //             'idcliente' => $detalle[0]["idrazo"],
    //             'nidusua' => $_SESSION["usuario_id"],
    //             'idcabecera' => $id
    //         ]);

    //         if ($execipc->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlipc, $execipc->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         // if ($sw == 0) {
    //         //     $pdo->rollBack();
    //         //     $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //         //     return $rpta;
    //         // }
    //         if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
    //             $pdo->rollBack();
    //             $rpta = array('mensaje' => 'Error al registrar el ingreso ', "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         $pdo->commit();
    //         $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
    //     } catch (PDOException $pdo_error) {
    //         $pdo->rollBack();
    //         $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
    //     }
    //     return $rpta;
    // }
    // function grabaregresolibro($cabecera, $detalle)
    // {
    //     try {
    //         $correlativo = SerieController::correlativo($_SESSION['nserie'], 'LC');
    //         if ($correlativo[0]['estado'] == 0) {
    //             $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
    //             return $rpta;
    //         }

    //         $idserie = $correlativo[0]['idserie'];
    //         $this->cndoc = $correlativo[0]['correlativo'];

    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();

    //         $sqlicb = "SELECT FunIngresaCajaBancos2(:cmbctas,:txtfechai,:txtnrooperacion,:cmbmediopago,:txtreferencia,:txtidprov,0,
    //         :txtndoc,:cmbnrocuentas,0,:inputcancelacion,1,0,:nd) as id;";
    //         $execicb = $pdo->prepare($sqlicb);
    //         $execicb->execute([
    //             'cmbctas' => $cabecera["cmbctas"],
    //             'txtfechai' => $cabecera["txtfechai"],
    //             'txtnrooperacion' => $cabecera['txtnrooperacion'],
    //             'cmbmediopago' => $cabecera["cmbmediopago"],
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'txtidprov' => $detalle[0]["idrazo"],
    //             'txtndoc' => $this->cndoc,
    //             'cmbnrocuentas' => $cabecera["cmbnrocuentas"],
    //             // 'inputcancelacion' => $detalle[0]["inputcancelacion"],
    //             'inputcancelacion' => $cabecera['txttotal'],
    //             'nd' => $cabecera["txttipocambio"]
    //         ]);

    //         $id = $execicb->fetchColumn();

    //         if (floatval($cabecera['txtinteres']) > 0) {
    //             $sqlcei = "SELECT FunIngresaDatosLcajaEDeudasInteres(:cmbctas,:txtfechai,:txtnrooperacion,:cmbmediopago,:txtreferencia,:txtidprov,0,:txtndoc,:idctainteres,0,:txtinteres,1,:txtidprov,:nd,:xc) as id;";
    //             $execei = $pdo->prepare($sqlcei);
    //             $execei->execute([
    //                 'cmbctas' => $cabecera["cmbctas"],
    //                 'txtfechai' => $cabecera["txtfechai"],
    //                 'txtnrooperacion' => $cabecera["txtnrooperacion"],
    //                 'cmbmediopago' => $cabecera["cmbmediopago"],
    //                 'txtreferencia' => $cabecera["txtreferencia"],
    //                 'txtidprov' => $detalle[0]["idrazo"],
    //                 'txtndoc' => $this->cndoc,
    //                 'idctainteres' => $cabecera["cmbintereses"],
    //                 'txtinteres' => $cabecera["txtintereses"],
    //                 'nd' => $cabecera["txttipocambio"],
    //                 'xc' => $id,
    //             ]);
    //             if ($execei->errorCode() != '00000') {
    //                 $pdo->rollBack();
    //                 enviarmensajerror($sqlcei, $execei->errorInfo());
    //                 var_dump($execei->errorInfo());
    //                 $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //                 return $rpta;
    //             }
    //         }
    //         if (floatval($cabecera['txtcomision']) > 0) {
    //             $sqlcei = "SELECT FunIngresaDatosLcajaEDeudasInteres(:cmbctas,:txtfechai,:txtnrooperacion,:cmbmediopago,:txtreferencia,:txtidprov,0,:txtndoc,:idctacomision,0,:txtcomision,1,:txtidprov,:nd,:xc) as id;";
    //             $execei = $pdo->prepare($sqlcei);
    //             $execei->execute([
    //                 'cmbctas' => $cabecera["cmbctas"],
    //                 'txtfechai' => $cabecera["txtfechai"],
    //                 'txtnrooperacion' => $cabecera["txtnrooperacion"],
    //                 'cmbmediopago' => $cabecera["cmbmediopago"],
    //                 'txtreferencia' => $cabecera["txtreferencia"],
    //                 'txtidprov' => $detalle[0]["idrazo"],
    //                 'txtndoc' => $this->cndoc,
    //                 'idctacomision' => $cabecera["cmbcomisiones"],
    //                 'txtcomision' => $cabecera["txtcomisiones"],
    //                 'nd' => $cabecera["txttipocambio"],
    //                 'xc' => $id,
    //             ]);
    //             if ($execei->errorCode() != '00000') {
    //                 $pdo->rollBack();
    //                 enviarmensajerror($sqlcei, $execei->errorInfo());
    //                 var_dump($execei->errorInfo());
    //                 $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //                 return $rpta;
    //             }
    //         }
    //         $sqlipc = "SELECT FUNINGRESAPAGOSdeudasCb(:txtfechai,:txtfechai,:nacta,:txtndoc,'P',:txtmoneda,:txtreferencia,'F',:nidrc,:nidusua,:ncontrol,'','',:nd,:xc) as id;";
    //         $execipc = $pdo->prepare($sqlipc);
    //         $execipc->execute([
    //             'txtfechai' => $cabecera["txtfechai"],
    //             'nacta' => $detalle[0]["inputcancelacion"],
    //             'txtndoc' => $this->cndoc,
    //             'txtmoneda' => $detalle[0]["mon"],
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'nidrc' => $detalle[0]["idrc"],
    //             'nidusua' => $_SESSION['usuario_id'],
    //             'ncontrol' => $detalle[0]['ncontrol'],
    //             'nd' => $cabecera["txttipocambio"],
    //             'xc' => $id
    //         ]);
    //         if ($execipc->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             var_dump($execipc->errorInfo());
    //             enviarmensajerror($sqlipc, $execipc->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
    //             $pdo->rollBack();
    //             $rpta = array('mensaje' => 'Error al registrar el egreso ', "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         $pdo->commit();
    //         $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
    //     } catch (PDOException $pdo_error) {
    //         $pdo->rollBack();
    //         $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
    //     }
    //     return $rpta;
    // }
    // function registraringresolibroefectivo($cabecera)
    // {
    //     try {
    //         $correlativo = SerieController::correlativo($_SESSION['nserie'], 'LC');
    //         if ($correlativo[0]['estado'] == 0) {
    //             $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
    //             return $rpta;
    //         }
    //         $idserie = $correlativo[0]['idserie'];
    //         $this->cndoc = $correlativo[0]['correlativo'];

    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();

    //         // 'txtfechai' => $request->get('txtfechai'),
    //         // 'cmbcuentas' => $request->get('cmbcuentas'),
    //         // 'txtcuentas' => $request->get('txtcuentas'),
    //         // 'cmbmoneda' => $request->get('cmbmoneda'),
    //         // 'txtvalor' => $request->get('txtvalor'),
    //         // 'txttotal' => $request->get('txttotal'),
    //         // 'txtreferencia' => $request->get('txtreferencia')
    //         // 'txttipocambio' => $request->get('txttipocambio')

    //         $sqlicb = "SELECT FunIngresaDatosLcajaE(:txtfechai,:txtndoc,:txtreferencia,:cmbcuentas,:txtvalor,0,:cmbmoneda,:txttipocambio,:cajero,:ncontrol) as id;";
    //         $execicb = $pdo->prepare($sqlicb);
    //         $execicb->execute([
    //             'txtfechai' => $cabecera["txtfechai"],
    //             'txtndoc' => $this->cndoc,
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'cmbcuentas' => $cabecera["cmbcuentas"],
    //             'txtvalor' => $cabecera["txtvalor"],
    //             'cmbmoneda' => $cabecera["cmbmoneda"],
    //             'txttipocambio' => $cabecera["txttipocambio"],
    //             'cajero' => $_SESSION['usuario_id'],
    //             'ncontrol' => 0
    //         ]);

    //         if ($execicb->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlicb, $execicb->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }

    //         $id = $execicb->fetchColumn();

    //         $sqlipc = "SELECT FunIngresaDatosLibroDiarioCP(:txtndoc,:debe,:haber,:txtreferencia,:cmbtipo,:txtnume,:cmbcuentas,:cond,
    //         :ni,'PCA',0,0,'S','',:ittd,:itth,:xc) as id;";
    //         $execipc = $pdo->prepare($sqlipc);
    //         $execipc->execute([
    //             'txtndoc' => $this->cndoc,
    //             'debe' => $cabecera["debe"],
    //             'haber' => 0,
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'cmbtipo' => 'D',
    //             'txtnume' => $this->cndoc,
    //             'cmbcuentas' => $cabecera["cmbcuentas"],
    //             'cond' => $cabecera["cond"],
    //             'ni' => $cabecera["ni"],
    //             'ittd' => $cabecera["ittd"],
    //             'itth' => $cabecera["itth"],
    //             'xc' => $id
    //         ]);
    //         if ($execipc->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlipc, $execipc->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
    //             $pdo->rollBack();
    //             $rpta = array('mensaje' => 'Error al registrar el ingreso ', "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         $pdo->commit();
    //         $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
    //     } catch (PDOException $pdo_error) {
    //         $pdo->rollBack();
    //         $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
    //     }
    //     return $rpta;
    // }
    // function registraregresolibroefectivo($cabecera)
    // {
    //     try {
    //         $correlativo = SerieController::correlativo($_SESSION['nserie'], 'LC');
    //         if ($correlativo[0]['estado'] == 0) {
    //             $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
    //             return $rpta;
    //         }
    //         $idserie = $correlativo[0]['idserie'];
    //         $this->cndoc = $correlativo[0]['correlativo'];

    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();

    //         $sqlicb = "SELECT FunIngresaDatosLcajaE(:txtfechai,:txtndoc,:txtreferencia,:cmbcuentas,:txtvalor,0,:cmbmoneda,:txttipocambio,:cajero,:ncontrol) as id;";
    //         $execicb = $pdo->prepare($sqlicb);
    //         $execicb->execute([
    //             'txtfechai' => $cabecera["txtfechai"],
    //             'txtndoc' => $this->cndoc,
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'cmbcuentas' => $cabecera["cmbcuentas"],
    //             'txtvalor' => $cabecera["txtvalor"],
    //             'cmbmoneda' => $cabecera["cmbmoneda"],
    //             'txttipocambio' => $cabecera["txttipocambio"],
    //             'cajero' => $_SESSION['usuario_id'],
    //             'ncontrol' => 0
    //         ]);

    //         if ($execicb->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlicb, $execicb->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }

    //         $id = $execicb->fetchColumn();

    //         $sqlipc = "SELECT FunIngresaDatosLibroDiarioCP(:txtndoc,:debe,:haber,:txtreferencia,:cmbtipo,:txtnume,:cmbcuentas,:cond,
    //         :ni,'PCA',0,0,'S','',:ittd,:itth,:xc) as id;";
    //         $execipc = $pdo->prepare($sqlipc);
    //         $execipc->execute([
    //             'txtndoc' => $this->cndoc,
    //             'debe' => 0,
    //             'haber' => $cabecera["haber"],
    //             'txtreferencia' => $cabecera["txtreferencia"],
    //             'cmbtipo' => 'H',
    //             'txtnume' => $this->cndoc,
    //             'cmbcuentas' => $cabecera["cmbcuentas"],
    //             'cond' => $cabecera["cond"],
    //             'ni' => $cabecera["ni"],
    //             'ittd' => $cabecera["ittd"],
    //             'itth' => $cabecera["itth"],
    //             'xc' => $id
    //         ]);
    //         if ($execipc->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             enviarmensajerror($sqlipc, $execipc->errorInfo());
    //             $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
    //             $pdo->rollBack();
    //             $rpta = array('mensaje' => 'Error al registrar el egreso ', "ndoc" => "", "estado" => '0');
    //             return $rpta;
    //         }
    //         $pdo->commit();
    //         $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
    //     } catch (PDOException $pdo_error) {
    //         $pdo->rollBack();
    //         $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
    //     }
    //     return $rpta;
    // }
    // function verificarsiexisteingreso()
    // {
    //     $existe = "F";
    //     $sql = "SELECT count(*) as existe FROM fe_lcaja WHERE lcaj_fech=:lcaj_fech AND lcaj_ndoc=:ndoc AND lcaj_acti='A'";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         "lcaj_fech" => $this->dfecha,
    //         "ndoc" => $this->cndoc
    //     ]);
    //     $listado = $query->fetchAll(PDO::FETCH_ASSOC);
    //     if ($listado[0]['existe'] > 0) {
    //         $existe = "T";
    //     }
    //     return $existe;
    // }
    // function buscarcajabancoparanular($ndoc)
    // {
    //     $sql = "SELECT 'CB' as tdoc, cban_idco as idauto,cban_ndoc as ndoc,cban_fech as fech,cban_deta as razo,'' as mone,if(cban_debe>0,cban_haber,cban_debe) as importe,'' as estadoenviado FROM fe_cbancos WHERE cban_ndoc like :ndoc and cban_acti='A'";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'ndoc' => '%' . $ndoc . '%'
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    // function anularcajabanco($idauto, $idusua)
    // {
    //     try {
    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();
    //         $sqlIC = "update fe_cbancos set cban_acti='I',cban_idus=:nidus where cban_idco=:id";
    //         $exIC = $pdo->prepare($sqlIC);
    //         $exIC->execute([
    //             'nidus' => $idusua,
    //             'id' =>  $idauto
    //         ]);
    //         $pdo->commit();
    //         $ncon->close();
    //         $rpta = array('mensaje' => "Se dió correctamente", "data" => [], "estado" => '1');
    //     } catch (PDOException $pdoexception) {
    //         $pdo->rollback();
    //         $rpta = array('mensaje' => $pdoexception->getMessage(), "data" => [], "estado" => '0');
    //     }
    //     return $rpta;
    // }
    // function listarinformescajaybancos($dfechai, $dfechaf, $banco)
    // {
    //     $lista = [];
    //     $sql = " SELECT a.cban_nume,a.cban_fech,b.pago_codi,b.pago_deta,a.cban_deta,if(a.cban_debe>0,ifnull(m.razo,''),ifnull(n.razo,'')) as razon,
    //    a.cban_ndoc,c.ncta,c.nomb,a.cban_debe,a.cban_haber,a.cban_idct,a.cban_idmp,a.cban_idco,a.cban_idcl,a.cban_idpr,a.cban_dola as dolar,cban_tran,
    //    cban_ttra as ttra,if(cban_debe<>0,'I','S') as tipo
    //    from fe_cbancos as a
    //    inner join fe_mpago as b on  b.pago_idpa=a.cban_idmp
    //    left join fe_clie as m on m.idclie=a.cban_idcl
    //    left join fe_prov as n on n.idprov=a.cban_idpr
    //    inner join fe_plan as c on c.idcta=a.cban_idct
    //    where a.cban_acti='A' AND a.cban_fech between :dfechai and :dfechaf and a.cban_idba=:banco order by a.cban_fech,tipo,a.cban_ndoc";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'dfechai' => $dfechai,
    //         'dfechaf' => $dfechaf,
    //         'banco' => $banco
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
    // function listarsaldoinicial($dfechai, $banco)
    // {
    //     $lista = [];
    //     $sql = "SELECT CAST(ifnull(SUM(a.cban_debe)-SUM(a.cban_haber),0) AS DECIMAL(12,2)) AS si
    //     FROM fe_cbancos AS a
    //     WHERE a.cban_acti='A' AND a.cban_fech<=:dfechai  AND a.cban_idba=:banco AND a.cban_idct>0";
    //     $query = $this->prepare($sql);
    //     $query->execute([
    //         'dfechai' => $dfechai,
    //         'banco' => $banco
    //     ]);
    //     $lista = $query->fetchAll(PDO::FETCH_ASSOC);
    //     $data = ['mensaje' => 'Todo ok', 'lista' => $lista, 'estado' => '1'];
    //     return $data;
    // }
}
