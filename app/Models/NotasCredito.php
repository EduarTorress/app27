<?php

namespace App\Models;

use App\Controllers\SerieController;
use PDO;
use PDOException;
use Core\Clases\conexion;
use Core\Routing\Modelo;

class NotasCredito extends Modelo
{
    public $ctdoc = "";
    public $cform = "";
    public $cndoc = "";
    public $dfecha = "";
    public $cdetalle = "";
    public $nvalor = 0;
    public $nigv = 0;
    public $nt = 0;
    public $cndo2 = "";
    public $ndolar = 0;
    public $ni = 0;
    public $ctg = "K";
    public $cmvto = "V";
    public $nidcodt = 1;
    public $nitems = 0;
    public $nidtr = 0;
    public $nexon = 0;
    public $ndscto = 0;
    public $ndetr = 0;
    public $nidclie = 0;
    public $idtr = 0;
    public $crazo = "";
    public $nidauto = 0;
    public $ntotal = 0;
    public $cmon = "S";
    public $dfechavv = "";
    public $nvend = 4;
    public $ninic = 0;
    public $crefec = "VENTA AL CREDITO";
    public $ctipoc = "F";
    public $nalma = 1;
    public $prov = "";
    public $nidprov = 0;
    public $ndocventa = "";

    function registrar($detalle)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $motivo = trim(substr($this->cdetalle, 0, 2));
            $correlativo = SerieController::correlativo('1', '07');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ndocventa = substr($this->ndocventa, 0, 1);
            if (trim($ndocventa) == 'B') {
                $this->cndoc = str_replace('FN', 'BC', $this->cndoc);
            }

            //     ctdoc VARCHAR(2),cform CHAR,cndoc VARCHAR(12),dfecha DATE,dfechar DATE,cdetalle VARCHAR(120),
            //     nv DECIMAL(12,2),nigv DECIMAL(12,2),nt DECIMAL(12,2),cndo2 VARCHAR(10),cm CHAR,
            //     ndolar DECIMAL(6,4),ni DECIMAL(6,4),ctg CHAR,ccodp INTEGER,cmvto CHAR,nus INTEGER,opt INTEGER,nidcodt INTEGER,
            //     n1 INTEGER,n2 INTEGER,n3 INTEGER,cdetalle1 VARCHAR(120),npvta DECIMAL(10,2))

            $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
            if ($tipocompraexon  == 'N') {
                $sqlIDE = "select FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,:nv,:nigv,:nt,:cndo2,:cmon,:ndolar,:ni,
                :ctg,:ccodp,:cmvto,:nidusua,1,:nidcodt,:n1,:n2,:n3,'','1') AS ID";
            } else {
                $sqlIDE = "select FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,:nv,:nigv,:nt,:cndo2,:cmon,:ndolar,:ni,
                :ctg,:ccodp,:cmvto,:nidusua,1,:nidcodt,:n1,:n2,:n3,'','1','0') AS ID";
            }

            $exIDE = $pdo->prepare($sqlIDE);
            $exIDE->execute([
                'ctdoc' => $this->ctdoc,
                'cform' => $this->cform,
                'cndoc' => $this->cndoc,
                'dfecha' => $this->dfecha,
                'cdetalle' => $this->cdetalle,
                'nv' => ($motivo != '13' ?   $this->nvalor : 0),
                'nigv' => ($motivo != '13' ?   $this->nigv : 0),
                'nt' => ($motivo != '13' ?   $this->nt : 0),
                'cndo2' => "",
                'cmon' => $this->cmon,
                'ndolar' => session()->get("gene_dola"),
                'ni' => session()->get("gene_igv"),
                'ctg' => $this->ctg,
                'ccodp' => $this->nidclie,
                'cmvto' => $this->cmvto,
                'nidusua' => session()->get("usuario_id"),
                'nidcodt' => $this->nidcodt,
                'n1' => session()->get("gene_idctav"),
                'n2' => session()->get("gene_idctai"),
                'n3' => session()->get("gene_idctat")
                // 'nitems' => $this->nitems,
                // 'idtr' => $this->idtr,
                // 'nexon' => $this->nexon, //FALTA 
                // 'ndscto' => $this->ndscto //FALTA 
                // 'ndetr' => $this->ndetr //FALTA
            ]);

            if ($exIDE->errorCode() != '00000') {
                $pdo->rollBack();
                // var_dump($exIDE->debugDumpParams());
                // print_r($exIDE->errorCode());
                $rpta = array('mensaje' => var_dump($exIDE->errorInfo()) . 'exIDE', "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $id = $exIDE->fetchColumn();

            if ($motivo == '13') {
                $sqlINC = "select FUNINGRESANOTASCREDITOventas1(:niautoc,:nidautov,:monto,:nimpo) as id";
                $exINC = $pdo->prepare($sqlINC);
                $exINC->execute([
                    "nidautov" => $this->nidauto,
                    "niautoc" => $id,
                    "monto" => '0',
                    "nimpo" => $this->nt
                ]);

                if ($exINC->errorCode() != '00000') {
                    $pdo->rollBack();
                    $rpta = array('mensaje' => $exINC->errorInfo() . 'exINC', "ndoc" => "", "estado" => '0');
                    return $rpta;
                }

                if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                    $pdo->rollBack();
                    $rpta = array('mensaje' => "Error al aumentar el correlativo", "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
                $pdo->commit();
                $rpta = array('mensaje' => "Nota de crédito generada", "ndoc" => $this->cndoc, "estado" => '1');
                return $rpta;
            }
            $sqlICE = "call ProIngresaDatosLcajaEefectivo11(:dfech,:cndoc,'',:n3,:ntotal,'0','S','0',:nidusua,:nidclie,:nidauto,:cform,:cndoc,:ctdoc,1) ";
            $exICE = $pdo->prepare($sqlICE);
            $exICE->execute([
                'dfech' =>  $this->dfecha,
                'cndoc' => $this->cndoc,
                'n3' => session()->get("gene_idctat"),
                'ntotal' => '-' . $this->ntotal,
                'nidusua' => session()->get("usuario_id"),
                'nidauto' =>$id,
                'nidclie' => $this->nidclie,
                'cform' => $this->cform,
                'ctdoc' => $this->ctdoc
            ]);
            if ($exICE->errorCode() != '00000') {
                $pdo->rollBack();
                // var_dump($exICE->debugDumpParams());
                // print_r($exICE->errorCode());
                $rpta = array('mensaje' => $exICE->errorInfo() . 'exICE', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sqlAC = "Call ProActualizaCreditos(:nauto,:nu)";
            $exAC = $pdo->prepare($sqlAC);
            $exAC->execute([
                "nauto" => $this->nidauto,
                "nu" => session()->get("usuario_id")
            ]);
            if ($exAC->errorCode() != '00000') {
                $pdo->rollBack();
                // print_r($exAC->errorInfo());
                print_r($exAC->debugDumpParams());
                $rpta = array('mensaje' => $exAC->errorInfo() . 'exAC', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            // if (trim($this->cform) == 'C') {
            //     $sqlRC = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
            //     :ctipo,:cndoc,:nimpo,:ninic,:idvend,:nimpo,:nidusua,1,'web') as nid";
            //     $exRC = $pdo->prepare($sqlRC);
            //     $exRC->execute([
            //         "nauto" => $this->nidauto,
            //         "nid" => $this->nidclie,
            //         "cndoc" => $this->cndoc,
            //         "cmon" => $this->cmon,
            //         "crefe" => $this->crefec,
            //         "dfecha" => $this->dfecha,
            //         "dfevto" => $this->dfechav,
            //         "ctipo" => $this->ctipoc,
            //         "nimpo" => $this->ntotal,
            //         "ninic" => $this->ninic,
            //         "idvend" => $this->nvend,
            //         "nidusua" => session()->get("usuario_id")
            //     ]);
            //     if ($exRC->errorCode() != '00000') {
            //         $pdo->rollBack();
            //         // var_dump($exRC->debugDumpParams());
            //         // print_r($exRC->errorInfo());
            //         $rpta = array('mensaje' => $exRC->errorInfo() . 'exRC', "ndoc" => "", "estado" => '0');
            //         return $rpta;
            //     }
            // }
            if ($tipocompraexon == 'N') {
                $sqlIK = "SELECT FunIngresaKardex1(:nid,:cc,'V',:npr,:nct,'I','V',:ccod,:calma,:nidcosto1,'0') AS NID";
            } else {
                $sqlIK = "SELECT FunIngresaKardex1(:nid,:cc,'V',:npr,:nct,'I','V',:ccod,:calma,:nidcosto1,'0','0') AS NID";
            }
            $sqlAS = "CALL astock(:coda,:nalma,:ccant,'V')";
            $ik = 1;
            $as = 1;
            foreach ($detalle as $item) {
                if ((substr($this->cdetalle, 0, 2) == '07') || (substr($this->cdetalle, 0, 2) == '05')) {
                    if (floatval($item['devolucion'] > 0)) {
                        $exIK = $pdo->prepare($sqlIK);
                        $cant = floatval($item['devolucion']);
                        $prec = floatval($item['precio']);
                        // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $costo = 0;
                        $exIK->execute([
                            "nid" => $id,
                            "cc" => $item['codigo'],
                            "npr" => $prec,
                            "nct" => '-' . $cant,
                            "ccod" => $this->nvend,
                            "calma" => $this->nalma,
                            "nidcosto1" => $costo
                        ]);
                        if ($exIK->errorCode() != '00000') {
                            $ik = 0;
                            break;
                        }
                    }
                } else {
                    $exIK = $pdo->prepare($sqlIK);
                    $cant = floatval($item['devolucion']);
                    $prec = floatval($item['precio']);
                    // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                    $costo = 0;
                    $exIK->execute([
                        "nid" => $id,
                        "cc" => $item['codigo'],
                        "npr" => $prec,
                        "nct" => '-' . $cant,
                        "ccod" => $this->nvend,
                        "calma" => $this->nalma,
                        "nidcosto1" => $costo
                    ]);
                    if ($exIK->errorCode() != '00000') {
                        $ik = 0;
                        break;
                    }
                }
                $exAS = $pdo->prepare($sqlAS);
                $cant = floatval($item['devolucion']);
                $exAS->execute([
                    "coda" => $item['codigo'],
                    "nalma" => $this->nalma,
                    "ccant" => '-' . $cant
                ]);
                if ($exAS->errorCode() != '00000') {
                    $as = 0;
                    break;
                }
            }
            if ($ik == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exIK->errorInfo() . 'exIK', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($as == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exAS->errorInfo() . 'exAS', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sqlINC = "select FUNINGRESANOTASCREDITOventas1(:niautoc,:nidautov,:monto,:nimpo) as id";
            $exINC = $pdo->prepare($sqlINC);
            $exINC->execute([
                "nidautov" => $this->nidauto,
                "niautoc" => $id,
                "monto" => '0',
                "nimpo" => $this->nt
            ]);
            if ($exINC->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exINC->errorInfo() . 'exINC', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "Error al aumentar el correlativo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Nota de crédito generada", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function consultardcto($nidauto, $ctipovta)
    {
        if ($ctipovta == 'S') {
            $sql = "select 4 as codv,c.idauto,detv_idvt as idart,ABS(detv_cant) as cant,detv_prec as prec,c.codt as alma,
			c.fech as fech1,c.vigv,c.rcom_icbper as Ticbper,abs(c.valor) as valor,ABS(c.igv) as igv,
			c.fech,c.fecr,c.form,c.rcom_exon,c.ndo2,c.igv,c.idcliente,d.razo,d.nruc,d.dire,d.ciud,d.ndni,
			c.pimpo,u.nomb as usuario,c.deta,LEFT(c.ndoc,4) as serie,SUBSTR(c.ndoc,5) as numero,
			c.tdoc,c.ndoc,c.dolar as dola,c.mone,m.detv_desc as descri,m.detv_unid as Unid,c.fech as fvto,c.rcom_arch,
			c.rcom_hash,'Oficina' as nomv,abs(c.impo) as impo,w.ndoc as dcto,clie_corr,
			w.fech as fech1,w.tdoc as tdoc1,c.tcom as tipovta,c.fech as fvto,CAST(0 as decimal(12,2)) as acta,d.fono,CAST(0 as decimal(10,2)) as rcom_mdet,c.fusua,
			CAST(0 as decimal(12,2)) as costo,v.empresa,v.nruc as rucempresa,ptop
			FROM fe_rcom as c
			inner join fe_clie as d on(d.idclie=c.idcliente)
			inner join fe_usua as u on u.idusua=c.idusua
			inner join fe_detallevta as m on m.detv_idau=c.idauto
			inner join fe_ncven f on f.ncre_idan=c.idauto
			inner join fe_rcom as w on w.idauto=f.ncre_idau,fe_gene as v
			where c.idauto=:nidauto order by detv_ite1";
        } else {
            $sql = "select r.idauto,r.ndoc,r.tdoc,r.fech,r.mone,abs(r.valor) as valor,r.ndo2,ABS(r.igv) as igv,
            r.vigv,c.nruc,c.razo,c.dire,c.ciud,c.ndni,' ' as nomv,r.form,u.nomb as usuario,
            abs(r.igv) as igv,abs(r.impo) as impo,ifnull(abs(k.cant),CAST(1 as decimal(12,2))) as cant,r.rcom_icbper as Ticbper,
            ifnull(k.prec,ABS(r.impo)) as prec,LEFT(r.ndoc,4) as serie,SUBSTR(r.ndoc,5) as numero,r.tcom as tipovta,
            ifnull(a.unid,'') as unid,ifnull(a.descri,r.deta) as descri,r.deta,ifnull(k.idart,CAST(0 as decimal(8))) as idart,w.ndoc as dcto,clie_corr,
            w.fech as fech1,w.tdoc as tdoc1,r.rcom_hash,r.rcom_arch,r.fech as fvto,CAST(0 as decimal(12,2)) as acta,c.fono,CAST(0 as decimal(10,2)) as rcom_mdet,r.fusua,
            CAST(0 as decimal(12,2)) as costo,v.empresa,v.nruc as rucempresa,ptop,ve.nomv as vendedor
            from fe_rcom r
            inner join fe_clie c on c.idclie=r.idcliente
            left join fe_kar k on k.idauto=r.idauto
            left join fe_art a on a.idart=k.idart
            inner join fe_ncven f on f.ncre_idan=r.idauto
            inner join fe_rcom as w on w.idauto=f.ncre_idau
            inner join fe_vend ve on k.codv=ve.idven
            inner join fe_usua as u on u.idusua=r.idusua,fe_gene as v
            where r.idauto=:nidauto and r.acti='A' and r.tdoc='07'";
        }
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($sql);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute(["nidauto" => $nidauto]);
        return $st;
    }
    function registrarncporcompra($detalle)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
            if ($tipocompraexon == 'N') {
                $sqlIDE = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,
                            :nv,:nigv,:nt,:cndo2,:cmon,:ndolar,:vigv,:ctg,:ccodp,:cmvto,:nidusua,:opt,:nidcodt,
                            :n1,:n2,:n3,:nitems,:npvta) AS ID";
                $exIDE = $pdo->prepare($sqlIDE);
                $exIDE->execute([
                    'ctdoc' => $this->ctdoc,
                    'cform' => $this->cform,
                    'cndoc' => $this->cndoc,
                    'dfecha' => $this->dfecha,
                    'dfechar' => $this->dfecha,
                    'cdetalle' => "",
                    'nv' => $this->nvalor,
                    'nigv' =>  $this->nigv,
                    'nt' => $this->nt,
                    'cndo2' => "",
                    'cmon' => $this->cmon,
                    'ndolar' => session()->get("gene_dola"),
                    'vigv' => session()->get("gene_igv"),
                    'ctg' => $this->ctg,
                    'ccodp' => $this->nidprov,
                    'cmvto' => $this->cmvto,
                    'nidusua' => session()->get("usuario_id"),
                    'opt' => "0",
                    'nidcodt' => $this->nidcodt,
                    'n1' => session()->get("gene_idctav"),
                    'n2' => session()->get("gene_idctai"),
                    'n3' => session()->get("gene_idctat"),
                    'nitems' => $this->nitems,
                    'npvta' => "0"
                ]);
            } else {
                $sqlIDE = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,
                    :nv,:nigv,:nt,:cndo2,:cmon,:ndolar,:vigv,:ctg,:ccodp,:cmvto,:nidusua,:opt,:nidcodt,
                    :n1,:n2,:n3,:nitems,:npvta,:exon) AS ID";
                $exIDE = $pdo->prepare($sqlIDE);
                $exIDE->execute([
                    'ctdoc' => $this->ctdoc,
                    'cform' => $this->cform,
                    'cndoc' => $this->cndoc,
                    'dfecha' => $this->dfecha,
                    'dfechar' => $this->dfecha,
                    'cdetalle' => "",
                    'nv' =>  $this->nvalor,
                    'nigv' =>  $this->nigv,
                    'nt' =>  $this->nt,
                    'cndo2' => "",
                    'cmon' => $this->cmon,
                    'ndolar' => session()->get("gene_dola"),
                    'vigv' => session()->get("gene_igv"),
                    'ctg' => $this->ctg,
                    'ccodp' => $this->nidprov,
                    'cmvto' => $this->cmvto,
                    'nidusua' => session()->get("usuario_id"),
                    'opt' => "0",
                    'nidcodt' => $this->nidcodt,
                    'n1' => session()->get("gene_idctav"),
                    'n2' => session()->get("gene_idctai"),
                    'n3' => session()->get("gene_idctat"),
                    'nitems' => $this->nitems,
                    'npvta' => "0",
                    'exon' => $this->nexon
                ]);
            }

            if ($exIDE->errorCode() != '00000') {
                $pdo->rollBack();
                // var_dump($exIDE->debugDumpParams());
                // print_r($exIDE->errorCode());
                $rpta = array('mensaje' => $exIDE->errorInfo() . 'exCV', "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $id = $exIDE->fetchColumn();

            if ($this->cform == 'E') {
                $sqlie = "CALL ProIngresaDatosLcajaeEfectivo12(:fech,:cdcto,:cdeta,:idcta,:deudor,'0',
                :cmone,:ndolar,:nidus,'0',:nidauto,:cform,:cdcto,:ctdoc,:nidtda)";
                $queryie = $pdo->prepare($sqlie);
                $queryie->execute([
                    'fech' => $this->dfecha,
                    'cdeta' => $this->nidprov,
                    'idcta' => session()->get("gene_idctat"),
                    'deudor' => $this->nt,
                    'cmone' => 'S',
                    'ndolar' => session()->get("gene_dola"),
                    'nidus' => session()->get("usuario_id"),
                    'nidauto' => $id,
                    'cform' => $this->cform,
                    'cdcto' => $this->cndoc,
                    'ctdoc' => $this->ctdoc,
                    'nidtda' =>  $this->nidcodt
                ]);

                $queryie->closeCursor();

                if ($queryie->errorCode() != '00000') {
                    $queryie->debugDumpParams();
                    $pdo->rollBack();
                    $rpta = array('mensaje' => $queryie->errorInfo() . 'exie', "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            // $sqlICE = "call ProIngresaDatosLcajaEefectivo11(:dfech,:cndoc,:deta,:n3,:ntotal,'0','S','0',:nidusua,:nidclie,:nidauto,:cform,:cndoc,:ctdoc,1) ";
            // $exICE = $pdo->prepare($sqlICE);
            // $exICE->execute([
            //     'dfech' =>  $this->dfecha,
            //     'cndoc' => $this->cndoc,
            //     'deta' => $this->cliente,
            //     'n3' => session()->get("gene_idctat"),
            //     'ntotal' => $this->ntotal,
            //     'nidusua' => session()->get("usuario_id"),
            //     'nidauto' => $this->nidauto,
            //     'nidclie' => $this->nidclie,
            //     'cform' => $this->cform,
            //     'ctdoc' => $this->ctdoc
            // ]);

            // if ($exICE->errorCode() != '00000') {
            //     $pdo->rollBack();
            //     // var_dump($exICE->debugDumpParams());
            //     // print_r($exICE->errorCode());
            //     $rpta = array('mensaje' => $exICE->errorInfo() . 'exICE', "ndoc" => "", "estado" => '0');
            //     return $rpta;
            // }

            $sqlIK = "SELECT FunIngresaKardexNotaCreditoCompra(:nid,:cc,'0',:npr,:nct,'I','C',:ccod,:calma,:nidcosto1,0) AS NID";
            $sqlAS = "CALL astock(:coda,:nalma,:ccant,'V')";
            $ik = 1;
            $as = 1;
            foreach ($detalle as $item) {
                $exIK = $pdo->prepare($sqlIK);
                $cant = floatval($item['devolucion']);
                $prec = floatval($item['precio']);
                // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                $costo = 0;
                $exIK->execute([
                    "nid" => $id,
                    "cc" => $item['codigo'],
                    "npr" => $prec,
                    "nct" => '-' . $cant,
                    "ccod" => $this->nvend,
                    "calma" => $this->nalma,
                    "nidcosto1" => $costo
                ]);
                if ($exIK->errorCode() != '00000') {
                    var_dump($exIK->errorInfo());
                    $ik = 0;
                    break;
                }
                $exAS = $pdo->prepare($sqlAS);
                $cant = floatval($item['devolucion']);
                $exAS->execute([
                    "coda" => $item['codigo'],
                    "nalma" => $this->nalma,
                    "ccant" => '-' . $cant,
                ]);
                if ($exAS->errorCode() != '00000') {
                    $as = 0;
                    break;
                }
            }
            if ($ik == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exIK->errorInfo() . 'exIK', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($as == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exAS->errorInfo() . 'exAS', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sqlINC = "select FUNINGRESANOTASCREDITOventas1(:niautoc,:nidautov,:monto,:nimpo) as id";
            $exINC = $pdo->prepare($sqlINC);
            $exINC->execute([
                "nidautov" => $this->nidauto,
                "niautoc" => $id,
                "monto" => '0',
                "nimpo" => '0'
            ]);
            if ($exINC->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $exINC->errorInfo() . 'exINC', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Nota de crédito generada", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
}
