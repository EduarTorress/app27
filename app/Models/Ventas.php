<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Ventas extends Modelo
{
    public $fechv = "";
    public $fechvv = "";
    public $n1 = 0;
    public $n2 = 0;
    public $n3 = 0;
    public $cndoc = "";

    function mostrarventas($dfi, $dff, $tipovta, $cmbFormaP, $cmbmoneda, $cmbtdoc, $cmbAlmacen)
    {
        try {
            $t = ($tipovta == '0') ? ' and tcom<>:tipovta' : ' and tcom=:tipovta ';
            $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            // $m = ($cmbmoneda == '0') ? ' and mone<>:cmbmoneda  ' : ' and mone=:cmbmoneda ';
            $m = ' and mone=:cmbmoneda ';
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,form,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,fusua,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,tdoc,u.nomb as usuario,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie)
                inner join fe_usua as u on (a.idusua=u.idusua),fe_gene as v
                where a.fech between :dfi and :dff and a.acti='A' and tdoc<>'09'" . $t . $f . $m . $a . $tc . " order by fusua,fech,ndoc";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'tipovta' => $tipovta,
                'cmbFormaP' => $cmbFormaP,
                'cmbmoneda' => $cmbmoneda,
                'cmbtdoc' => $cmbtdoc,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            return $query;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function mostrarventasutilidades($dfi, $dff, $cmbAlmacen, $cmbvendedor)
    {
        try {
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $v = ($cmbvendedor == '0') ? ' and codv<>:cmbvendedor  ' : ' and codv=:cmbvendedor ';

            // SELECT Ndoc,fech,cliente,Vendedor,Importe,(SUM(Utilidad)*100)/SUM(costototal) AS porcentaje,SUM(Utilidad) AS Utilidad,Idauto FROM
            $sql = "SELECT c.idauto,k.idart AS Coda,b.Descri,b.Unid,cant,CAST(kar_cost  AS DECIMAL(12,4)) AS costounitario,
                    CAST(IF(c.Mone='S',k.Prec,k.Prec*c.dolar)  AS DECIMAL(12,4))AS PrecioVenta,
                    CAST(cant*kar_cost AS DECIMAL(12,2)) AS costototal,
                    CAST(cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar) AS DECIMAL(12,2)) AS ventatotal,
                    CAST((cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar))-(cant*k.kar_cost) AS DECIMAL(12,2)) AS Utilidad,
                    cc.Razo AS cliente,v.`nomv` AS Vendedor,c.Idauto,Ndoc,fech,IF(c.Mone='S',Impo,Impo*c.dolar) AS Importe
                    FROM fe_rcom AS c
                    INNER JOIN fe_kar AS k ON k.Idauto=c.Idauto
                    INNER JOIN fe_art AS b ON b.idart=k.idart
                    INNER JOIN fe_clie AS cc ON cc.idclie=c.idcliente
                    INNER JOIN fe_vend AS v ON v.idven=k.Codv
                    WHERE k.Acti='A' AND c.Acti='A' and impo>0 AND c.fech BETWEEN :dfi AND :dff" . $a . $v . "AND c.tcom<>'T' ORDER BY fech,Ndoc";

            // $sql = "Select c.ndoc as Ndoc,c.impo as Importe,prod_cod1,b.Descri,m.dmar As marca,b.Unid,cant,Cast(kar_cost  As Decimal(12,4)) As costounitario,
            //         Cast(If(c.Mone='S',k.Prec,k.Prec*c.dolar)  As Decimal(12,4))As PrecioVenta,
            //         Cast(cant*kar_cost As Decimal(12,2)) As costototal,
            //         Cast(cant*If(c.Mone='S',k.Prec,k.Prec*c.dolar)  As Decimal(12,2)) As ventatotal,
            //         Cast((cant*If(c.Mone='S',k.Prec,k.Prec*c.dolar))-(cant*k.kar_cost) As Decimal(12,2)) As Utilidad,
            //         Cast((((cant*If(c.Mone='S',k.Prec,k.Prec*c.dolar))-(cant*k.kar_cost))*100)/(cant*kar_cost) As Decimal(6,2)) As porcentaje,
            //         cc.Razo As cliente,v.`nomv` As Vendedor,Ndoc,fech,c.Idauto,k.idart As Coda
            //         From fe_rcom As c
            //         inner Join fe_kar As k On k.Idauto=c.Idauto
            //         inner Join fe_art As b On b.idart=k.idart
            //         inner Join fe_mar As m On m.idmar=b.idmar
            //         inner Join fe_clie As cc On cc.idclie=c.idcliente
            //         inner Join fe_vend As v On v.idven=k.Codv
            //         Where k.Acti='A' And c.Acti='A' And c.fech Between :dfi AND :dff And c.tcom<>'T' " . $a . " Order By Descri";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbAlmacen' => $cmbAlmacen,
                'cmbvendedor' => $cmbvendedor
            ]);
            // var_dump($query->debugDumpParams());
            $rs = $query->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function listarutilidadesdetalle($dfi, $dff, $cmbAlmacen, $cmbvendedor, $cmbmarca, $cmbcategoria)
    {
        try {
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $v = ($cmbvendedor == '0') ? ' and codv<>:cmbvendedor  ' : ' and codv=:cmbvendedor ';
            $m = ($cmbmarca == '0') ? ' and b.idmar<>:cmbmarca  ' : ' and b.idmar=:cmbmarca ';
            $c = ($cmbcategoria == '0') ? ' and b.idcat<>:cmbcategoria  ' : ' and b.idcat=:cmbcategoria ';

            // -- AND b.idmar=<<This.nmarca>>
            // -- AND b.idcat=<<This.nlinea>>

            $sql = "SELECT ndoc,b.Descri,m.dmar AS marca,b.Unid,cant,CAST(kar_cost  AS DECIMAL(12,4)) AS costounitario,
                CAST(IF(c.Mone='S',k.Prec,k.Prec*c.dolar)  AS DECIMAL(12,4))AS PrecioVenta,
                CAST(cant*kar_cost AS DECIMAL(12,2)) AS costototal,
                CAST(cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar)  AS DECIMAL(12,2)) AS ventatotal,
                CAST((cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar))-(cant*k.kar_cost) AS DECIMAL(12,2)) AS Utilidad,
                CAST((((cant*IF(c.Mone='S',k.Prec,k.Prec*c.dolar))-(cant*k.kar_cost))*100)/(cant*kar_cost) AS DECIMAL(6,2))AS porcentaje,
                cc.Razo AS cliente,v.`nomv` AS Vendedor,Ndoc,T.nomb AS tienda,fech,c.Idauto,k.idart AS coda
                FROM fe_rcom AS c
                INNER JOIN fe_kar AS k ON k.Idauto=c.Idauto
                INNER JOIN fe_art AS b ON b.idart=k.idart
                INNER JOIN fe_mar AS m ON m.idmar=b.idmar
                INNER JOIN fe_clie AS cc ON cc.idclie=c.idcliente
                INNER JOIN fe_vend AS v ON v.idven=k.Codv
                INNER JOIN fe_sucu AS T ON T.idalma=c.codt
                WHERE k.Acti='A' AND c.Acti='A' AND c.fech BETWEEN :dfi AND :dff AND c.tcom<>'T'" . $a . $v . $m . $c . " ORDER BY Descri";
            $query = $this->prepare($sql);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbAlmacen' => $cmbAlmacen,
                'cmbvendedor' => $cmbvendedor,
                'cmbmarca' => $cmbmarca,
                'cmbcategoria' => $cmbcategoria
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function reporteestadistico($año, $cmbAlmacen)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        $sql = "SELECT q.mes,SUM(q.sucu1) AS '1',SUM(q.sucu2) AS '2',SUM(q.sucu3) AS '3',SUM(q.sucu1+q.sucu2+q.sucu3) AS tot FROM ( SELECT w.mes,
        SUM(CASE w.codt WHEN 1 THEN w.impo ELSE 0 END) AS sucu1,
        SUM(CASE w.codt WHEN 2 THEN w.impo ELSE 0 END) AS sucu2,
        SUM(CASE w.codt WHEN 3 THEN w.impo ELSE 0 END) AS sucu3
        FROM (SELECT MONTH(a.fech) AS mes,YEAR(a.fech) AS año,a.form,IF(a.mone='S',a.impo,a.impo*a.dolar) AS impo,a.codt FROM fe_rcom AS a
        INNER JOIN fe_clie AS b ON b.idclie=a.idcliente
        WHERE a.acti='A' AND YEAR(fech)=:ano " . $a . " AND rcom_entr='P' ORDER BY fech) AS w  GROUP BY mes,codt) AS q GROUP BY mes";
        $query = $this->prepare($sql);
        $query->execute([
            'ano' => $año,
            'cmbAlmacen' => $cmbAlmacen
        ]);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    function mostraroventas($dfi, $dff, $nidt)
    {
        try {
            if ($nidt === 0) {
                $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.fech between :dfi and :dff  and a.acti='A' AND impo<>0 and tcom='T' order by fech,ndoc";
                $query = $this->prepare($sql);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff
                ]);
            } else {
                $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.fech between :dfi and :dff  and a.acti='A' and codt=:nidt and tcom='T' AND impo<>0 order by fech,ndoc";
                // $db=new conexion();
                $query = $this->prepare($sql);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff,
                    'nidt' => $nidt
                ]);
            }
            return $query;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function mostrarventasdetalladas($dfi, $dff, $cmbAlmacen, $cmbFormaP, $cmbtdoc)
    {
        try {
            $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbalmacen  ' : ' and codt=:cmbalmacen ';
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $sql = "select  a.tdoc,a.ndoc,a.fech,c.razo as cliente,d.descri as producto,d.unid,e.cant,e.prec,a.mone,f.nomb as usuario,
			    ifnull(l.dcat,'') as categoria,ifnull(desgrupo,'') as grupo,ifnull(m.dmar,'') as marca,
			    ifnull(prod_acti,'') as estado,round(if(d.tmon='S',(d.prec*z.igv)+b.prec,(d.prec*z.igv*z.dola)+b.prec),2) as costo,
			    e.cant*e.prec as impo,a.form,c.nruc,c.ndni,d.idart,a.fusua as hora,a.igv,a.valor
                fROM fe_rcom as a 
				inner join fe_clie as c on c.idclie=a.idcliente
				inner join fe_kar as e on e.idauto=a.idauto
				inner join fe_art as d on d.idart=e.idart
				left join fe_cat as l on l.idcat=d.idcat
				left join fe_grupo as g on g.idgrupo=l.idgrupo
				left  join fe_mar as m on m.idmar=d.idmar
				left join fe_fletes as b on b.idflete=d.idflete
				inner join fe_usua as f on f.idusua=a.idusua,fe_gene as z
				where a.fech between :dfi AND :dff and a.acti='A' and e.acti='A' " . $a . $f . $tc . " order by a.fusua";
            $query = $this->prepare($sql);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbFormaP' => $cmbFormaP,
                'cmbtdoc' => $cmbtdoc,
                'cmbalmacen' => $cmbAlmacen
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    public function consultardcto($nidauto, $ctipovta)
    {
        if ($ctipovta === 'S' or $ctipovta === 'T') {
            $consulta = "SELECT  r.idauto,r.ndoc,r.tdoc,r.fech AS dfecha,IF(r.mone='S','PEN','USD') AS mone,valor,
            cast(0 as decimal(12,2)) as  inafectas,CAST(0 AS DECIMAL(12,2)) AS gratificaciones,r.mone AS moneda,
            CAST(0 AS DECIMAL(12,2)) AS exoneradas,'10' AS tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,'N' as clie_rete,fusua,
            v.nruc AS rucempresa,v.empresa,v.ubigeo,r.mone AS moneda,
            v.ptop,v.ciudad,v.distrito,c.nruc,IF(tdoc='01','6','1') AS tipodoccliente,c.razo,
            CONCAT(TRIM(c.dire)) AS direccion,c.ndni,rcom_otro,CAST(0 AS DECIMAL(10,2)) AS costoref,deta,
            'PE' AS pais,r.igv,CAST(0 AS DECIMAL(12,2)) AS tdscto,CAST(0 AS DECIMAL(12,2)) AS Tisc,
            impo,CAST(0 AS DECIMAL(12,2)) AS montoper,'I' AS incl,0 AS rcom_idan,
            CAST(0 AS DECIMAL(12,2)) AS totalpercepcion,IFNULL(k.detv_cant,1) AS cant,IFNULL(k.detv_prec,r.impo) AS prec,
            LEFT(r.ndoc,4) AS serie,SUBSTR(r.ndoc,5) AS numero,detv_desc AS descri,detv_idvt AS  coda,
            'NIU' AS unid,'ZZ' AS unid1,s.codigoestab,r.form,v.gene_usol,v.gene_csol,'PE' AS pais,'OFICINA' AS vendedor,
            v.gene_cert,v.clavecertificado,ifnull(p.fevto,r.fech) as fvto,IF(rcom_detr='',0,rcom_detr) AS rcom_detr,us.nomb as usuario
            FROM fe_rcom r
            INNER JOIN fe_clie c ON c.idclie=r.idcliente
            inner join fe_usua us on r.idusua=us.idusua
            INNER JOIN fe_detallevta k ON k.detv_idau=r.idauto
            INNER JOIN fe_sucu s ON s.idalma=r.codt
            left join (select rcre_idau,min(c.fevto) as fevto from fe_rcred as r inner join fe_cred as c on c.cred_idrc=r.rcre_idrc
            where rcre_acti='A' and acti='A' and rcre_idau=:nidauto group by rcre_idau) as p on p.rcre_idau=r.idauto,fe_gene AS v
            WHERE r.idauto=:nidauto AND r.acti='A' AND detv_item>0 AND detv_acti='A'";
        } else {
            $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
            $consulta = "SELECT r.idauto,r.ndoc,r.tdoc,r.fech AS dfecha,IF(r.mone='S','PEN','USD') AS mone,valor,fusua,
            CAST(0 AS DECIMAL(12,2)) AS inafectas,CAST(0 AS DECIMAL(12,2)) AS gratificaciones,r.mone AS moneda,'N' as clie_rete,
            CAST(0 AS DECIMAL(12,2)) AS exoneradas,'10' AS tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,
            r.mone AS moneda,
            c.nruc,IF(tdoc='01','6','1') AS tipodoccliente,c.razo, CONCAT(TRIM(c.dire)) AS direccion,
            c.ndni,rcom_otro,kar_cost AS costoref,deta, 'PE' AS pais,r.igv,CAST(0 AS DECIMAL(12,2)) AS tdscto,
            CAST(0 AS DECIMAL(12,2)) AS Tisc, impo,CAST(0 AS DECIMAL(12,2)) AS montoper,k.incl,p.nomv AS vendedor,
            CAST(0 AS DECIMAL(12,2)) AS totalpercepcion,k.cant,k.prec,LEFT(r.ndoc,4) AS serie, SUBSTR(r.ndoc,5) AS numero,0 AS rcom_idan ,
            a.unid,a.descri as descri,k.idart AS coda, IFNULL(unid_codu,'NIU')AS unid1,s.codigoestab,r.form,v.gene_usol,v.gene_csol,
            'PE' AS pais, v.gene_cert,v.clavecertificado,IFNULL(p.fevto,r.fech) AS fvto,k.incl,us.nomb as usuario"
                . ($ventascondescuento == 'S' ? ' ,rcom_desc,kar_desc ' : ' ') . "
            FROM fe_rcom r 
            INNER JOIN fe_clie c ON c.idclie=r.idcliente  
            INNER JOIN fe_kar k ON k.idauto=r.idauto 
            LEFT JOIN `fe_vend` `p` ON ((`p`.`idven` = `k`.`codv`))
            INNER JOIN fe_art a ON a.idart=k.idart
            inner join fe_mar m on a.idmar=m.idmar
            INNER JOIN fe_sucu s ON s.idalma=r.codt 
            inner join fe_usua us on r.idusua=us.idusua
            LEFT JOIN fe_unidades AS u ON u.unid_codu=a.unid 
            LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS p ON p.rcre_idau=r.idauto, 
            fe_gene AS v WHERE r.idauto=:nidauto AND r.acti='A' AND k.acti='A'";
        }
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($consulta);
        $st->execute(["nidauto" => $nidauto]);
        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function consultarcreditosporcuotas($idauto)
    {
        try {
            $sql = "select ndoc,impo as txtimporte,fevto as txtfechavt FROM fe_cred AS c
                    INNER JOIN fe_rcred AS r
                    ON r.rcre_idrc=c.cred_idrc
                    WHERE rcre_idau=:idauto and impo>0 and acti='A'";
            $query = $this->prepare($sql);
            $query->execute([
                'idauto' => $idauto
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function grabaroVentaGeneral($cabecera, $detalle)
    {
        try {
            $correlativo = SerieController::correlativo('1', $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se puede obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
                $nidcta1 = session()->get("gene_idctav");
                $nidcta2 = session()->get("gene_idctai");
                $nidcta3 = session()->get("gene_idctat");
            } else {
                $nidcta1 = 0;
                $nidcta2 = 0;
                $nidcta3 = 0;
            }

            $ls = "SELECT FuningresaDocumentoElectronicoDetraccion(
                :tdocv,:formv,:cndocv,:fechv,'',:subtotal,:igv,:total,:ndo2v,:monev,
                :dola,:vigv,'T',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'027','0.00','0.00','0.00',:txtdetraccion,:retencion) AS ID";

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' => $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera['monev'],
                'dola' => session()->get("gene_dola"),
                'vigv' => session()->get("gene_igv"),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $cabecera["almv"],
                'n1' => $nidcta1,
                'n2' =>  $nidcta2,
                'n3' => $nidcta3,
                'txtdetraccion' => $cabecera["txtdetraccion"],
                'retencion' => (floatval($_SESSION['gene_montoretencion']) <= floatval($cabecera['total']) ? ($cabecera['txtclienteretencion'] == 'S' ? round($cabecera['total'] * $_SESSION['gene_retencion'], 2) : 0) : 0)
            ]);
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();

            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' => $cabecera["fechv"],
                'cndocv' => $this->cndoc,
                'razov' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);

            // $query->debugDumpParams();

            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"]
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sql1 = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";
            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $query1 = $pdo->prepare($sql1);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);

                $query1->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $id,
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                //  $query1->debugDumpParams();
                if ($query1->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query1->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "Error al actualizar correlativo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function buscarOVentaPorId($idauto)
    {
        $sql = "select `c`.`rcom_icbper` AS `rcom_icbper`, `a`.`kar_icbper`  AS `kar_icbper`, `c`.`rcom_mens`   AS `rcom_mens`,IFNULL(m.fevto,c.fech) AS fvto,
                `c`.`idusua`      AS `idusua`, `a`.`kar_comi`    AS `kar_comi`, `a`.`codv`        AS `codv`,`a`.`idauto`      AS `idauto`,
                `a`.`alma`        AS `alma`, `a`.`kar_idco`    AS `idcosto`, `a`.`idkar`       AS `idkar`, `a`.`idart`       AS `Coda`,
                `a`.`cant`        AS `cant`, `a`.`prec`        AS `prec`, `c`.`valor`       AS `valor`, `c`.`igv`         AS `igv`,
                `c`.`impo`        AS `impo`, `c`.`fech`        AS `fech`, `c`.`fecr`        AS `fecr`, `c`.`form`        AS `form`,
                `c`.`deta`        AS `deta`, `c`.`exon`        AS `exon`, `c`.`ndo2`        AS `ndo2`, `c`.`rcom_entr`   AS `rcom_entr`,
                `c`.`idcliente`   AS `idclie`, `d`.`razo`        AS `razo`, `d`.`nruc`        AS `nruc`, `d`.`dire`        AS `dire`,
                `d`.`ciud`        AS `ciud`,  `d`.`ndni`        AS `ndni`, `a`.`tipo`        AS `tipo`, `c`.`tdoc`        AS `tdoc`,
                `c`.`ndoc`        AS `ndoc`, `c`.`dolar`       AS `dolar`, `c`.`mone`        AS `mone`,  `b`.`descri`      AS `descri`,
                `b`.`unid`        AS `unid`, `b`.`pre1`        AS `pre1`, `b`.`peso`        AS `peso`, `b`.`pre2`        AS `pre2`,
                `c`.`vigv`        AS `vigv`, `a`.`dsnc`        AS `dsnc`, `a`.`dsnd`        AS `dsnd`, `a`.`gast`        AS `gast`,
                `c`.`idcliente`   AS `idcliente`, `c`.`codt`        AS `codt`, `b`.`pre3`        AS `pre3`, `b`.`cost`        AS `costo`,
                `b`.`uno`         AS `uno`, `b`.`dos`         AS `dos`, (`b`.`uno` + `b`.`dos`) AS `TAlma`, `c`.`fusua`       AS `fusua`,
                `p`.`nomv`        AS `Vendedor`, `q`.`nomb`        AS `Usuario`, `c`.`rcom_idtr`   AS `rcom_idtr`, `c`.`rcom_tipo`   AS `rcom_tipo`,c.rcom_detr
                FROM `fe_rcom` `c`
                JOIN `fe_kar` `a`  ON ((`a`.`idauto` = `c`.`idauto`))
                JOIN `vlistaprecios` `b`  ON ((`b`.`idart` = `a`.`idart`))
                JOIN `fe_clie` `d`  ON ((`d`.`idclie` = `c`.`idcliente`))
                LEFT JOIN `fe_vend` `p`  ON ((`p`.`idven` = `a`.`codv`))
                JOIN `fe_usua` `q`  ON ((`q`.`idusua` = `c`.`idusua`))
                LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc
                WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS m ON m.rcre_idau=c.idauto
                WHERE `c`.`acti` <> 'I'  AND `a`.`acti` <> 'I' and a.idauto=:nidauto";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }
    function buscarVentaPorId($idauto)
    {
        $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        $sql = "Select a.kar_Cost  As kar_Cost, c.idusua,a.kar_comi  As kar_comi, a.codv As codv,c.fech As fvto,
        a.idauto As idauto, c.codt As alma, a.kar_idco  As idcosto, a.idkar As idkar,'N'as clie_rete,
        a.idart As Coda, a.cant As cant, a.Prec As prec, c.valor  As valor,c.rcom_exon,
        c.igv As igv, c.Impo As impo, c.fech As fech, c.fecr As fecr, c.Form As form, c.Deta As deta,
        c.exon As exon,c.Ndo2 As ndo2, c.rcom_entr As rcom_entr,c.idcliente As idclie, d.razo As razo, d.nruc As nruc,
        d.Dire As Dire, d.ciud As ciud, d.ndni As ndni, a.tipo As tipo, c.Tdoc As tdoc, c.Ndoc As ndoc, c.dolar As dolar,c.Mone As mone, b.Descri As descri,
        IFNULL(xx.idcaja,0) As idcaja, b.Unid As unid, b.premay As pre1, b.tipro As tipro,
        b.peso As peso, b.premen As pre2,IFNULL(z.vend_idrv,0) As nidrv, c.vigv As vigv, a.dsnc As dsnc, a.dsnd As dsnd,a.gast As gast, c.idcliente As idcliente,
        c.codt As codt, b.pre3 As pre3,b.cost As costo, b.uno As uno,b.Dos As Dos,b.tre,b.cua,(b.uno + b.Dos+b.tre+b.cua+a.cant) As TAlma,
        c.fusua As fusua, 'OFICINA' As vendedor, q.nomb As usuario, a.Incl As incl,c.rcom_mens As rcom_mens,rcom_idtr,0 as rcom_idan"
            . ($ventascondescuento == 'S' ? ' ,rcom_desc,kar_desc ' : ' ') . "
        From fe_art b
        INNER Join fe_kar a On a.idart = b.idart
        inner join fe_mar m on b.idmar=m.idmar
        INNER  Join fe_rcom c On a.idauto = c.idauto
        Left Join fe_caja xx On xx.idauto = c.idauto
        INNER Join fe_clie d On c.idcliente = d.idclie
        INNER Join fe_usua q On q.idusua = c.idusua
        Left Join (Select vend_idau,vend_idrv From fe_rvendedor Where vend_acti='A') As z  On z.vend_idau = c.idauto
        Where c.Acti <> 'I' And a.Acti <> 'I' And c.idauto=:nidauto Order By idkar";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }
    function mostrsroventas($idauto)
    {
        $sql = "SELECT b.nruc,b.ndni,b.razo,b.dire,b.ciud,a.dolar,a.fech,a.fecr,a.mone,a.idauto,a.vigv,a.valor,a.igv,
        a.impo,ndoc,a.deta,a.tcom,a.idcliente as idclie,a.ndo2,4 as codv,codt as alma,clie_rete,
        w.impo AS impo1,c.nomb,w.nitem,c.ncta,w.tipo,a.form,rcom_detr,rcom_mdet,
        w.idectas,w.idcta,a.rcom_dsct,rcom_idtr,rcom_mens,IFNULL(p.fevto,a.fech) AS fvto,
        tdoc
        FROM fe_rcom AS a
        INNER JOIN fe_ectas AS w ON w.idrven=a.idauto
        INNER JOIN fe_plan AS c ON c.idcta=w.idcta
        INNER JOIN fe_clie AS b ON b.idclie=a.idcliente
        LEFT JOIN (SELECT rcre_idau,MIN(c.fevto) AS fevto FROM fe_rcred AS r INNER JOIN fe_cred AS c ON c.cred_idrc=r.rcre_idrc
        WHERE rcre_acti='A' AND acti='A' AND rcre_idau=:nidauto GROUP BY rcre_idau) AS p ON p.rcre_idau=a.idauto
        WHERE a.idauto=:nidauto AND w.acti='A' ";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }
    function mostrardetalloventas($idauto)
    {
        $sql = "select q.detv_desc AS descri,q.detv_item,q.detv_ite1,q.detv_ite2,detv_prec AS prec,
        detv_cant AS cant,detv_unid as unidad,detv_idvt AS nreg from fe_detallevta AS q where q.detv_idau=:nidauto AND detv_acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'nidauto' => $idauto
        ]);
        return $query;
    }
    function actualizarOVenta($cabecera, $detalle)
    {
        $ls = "CALL ProActualizaCabeceraVentascdetraccion(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,
            :nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,:cmvto,:nus,'1',:idalmacen,:n1,:n2,:n3,'0','0',:txtdetraccion,:nidauto,'027',:retencion)";
        try {

            if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
                $nidcta1 = session()->get("gene_idctav");
                $nidcta2 = session()->get("gene_idctai");
                $nidcta3 = session()->get("gene_idctat");
            } else {
                $nidcta1 = 0;
                $nidcta2 = 0;
                $nidcta3 = 0;
            }

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'ctdoc' => $cabecera["tdocv"],
                'cndoc' => $cabecera["ndoc"],
                'dfecha' => $cabecera["fechv"],
                'cform' => $cabecera["formv"],
                'cdetalle' => $cabecera["detav"],
                'nv' => $cabecera["subtotal"],
                'nigv' => $cabecera["igv"],
                'nt' => $cabecera["total"],
                'cndo2' => $cabecera['ndo2v'],
                'cm' => 'S',
                'ndolar' =>  session()->get('gene_dola'),
                'ni' => session()->get("gene_igv"),
                'ctg' => 'T',
                'ccodp' => $cabecera["idcliev"],
                'cmvto' => 'V',
                'nus' => $cabecera["nidus"],
                'idalmacen' => $_SESSION['idalmacen'],
                'n1' =>  $nidcta1,
                'n2' =>  $nidcta2,
                'n3' =>  $nidcta3,
                'txtdetraccion' => $cabecera["txtdetraccion"],
                'nidauto' => $cabecera["nidautov"],
                'retencion' => (floatval($_SESSION['gene_montoretencion']) <= floatval($cabecera['total']) ? ($cabecera['txtclienteretencion'] == 'S' ? round($cabecera['total'] * $_SESSION['gene_retencion'], 2) : 0) : 0)
            ]);

            $st->closeCursor();

            if ($st->errorCode() != '00000') {
                // \print_r($st->errorInfo());
                // \print_r($st->debugDumpParams());
                // \print_r($st->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => "No se actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $cabecera["fechv"],
                'cndocv' => $cabecera["ndoc"],
                'razov' => $cabecera['razov'],
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $cabecera["nidautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);
            // $query->debugDumpParams();
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $query->closeCursor();

            $lsqlcred = "Call ProActualizaCreditos(:nauto,:nu)";
            $stsqlcreditos = $pdo->prepare($lsqlcred);
            $stsqlcreditos->execute([
                "nauto" => $cabecera['nidautov'],
                "nu" => session()->get("usuario_id")
            ]);
            $stsqlcreditos->closeCursor();

            if ($stsqlcreditos->errorCode() != '00000') {

                $pdo->rollBack();
                $rpta = array('mensaje' => "Al actualizar ID de créditos", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["nidautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $cabecera["ndoc"],
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $cabecera["ndoc"],
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"]
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $sql1 = "update fe_detallevta set detv_acti='I' where detv_idau=:nidauto";
            $query1 = $pdo->prepare($sql1);
            $query1->execute([
                'nidauto' =>  $cabecera["nidautov"]
            ]);
            // $query->debugDumpParams();
            if ($query1->errorCode() != '00000') {
                // \print_r($query1->errorInfo());
                // \print_r($query1->debugDumpParams());
                // \print_r($query1->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => "No Se Actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sql1 = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";
            // $carritov = session()->get('carritov', []);
            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $query1 = $pdo->prepare($sql1);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);
                // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                $query1->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $cabecera["nidautov"],
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                //  $query1->debugDumpParams();
                if ($query1->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }

            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "No se Actualizo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se actualizo correctamente", "ndoc" => $cabecera["ndoc"], "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            $rpta = array('mensaje' => "No se actualizo", "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function grabarVentaGeneral($cabecera)
    {
        $this->fechv = $cabecera["fechv"];
        $this->fechvv = $cabecera["fechvv"];

        // $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        // if ($ventascondescuento == 'S') {
        // $ls = "SELECT FuningresaDocumentoElectronicoDescuento(:tdocv,:formv,:cndocv,:fechv,:txtreferencia,:subtotal,
        // :igv,:total,:ndo2v,:monev,:dola,:vigv,'K',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,:idautoanticipo,'0','0.00','0',
        // :retencion," . $cabecera['descuentogeneral'] . ") AS ID";
        // }

        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }
        // $rete = 0;
        // if ($cabecera['tdocv'] == '01') {
        //     // $rete = (floatval($_SESSION['gene_montoretencion']) <= floatval($cabecera['total']) ? ($cabecera['txtclienteretencion'] == 'S' ? round($cabecera['total'] * ($_SESSION['gene_retencion'] / 100), 2) : 0) : 0);
        // }
        try {
            $correlativo = SerieController::correlativo('1', $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ncon = new conexion();
            $pdo = $ncon->conectar();

            // ctdoc VARCHAR(2),cform CHAR,cndoc VARCHAR(12),dfecha DATE,cdetalle VARCHAR(220),
            // nv DECIMAL(12,2),nigv DECIMAL(12,2),nt DECIMAL(12,2),cndo2 VARCHAR(10),cm CHAR,
            // ndolar DECIMAL(6,4),ni DECIMAL(6,4),ctg CHAR,ccodp INTEGER,nidturno INTEGER,nus INTEGER,nidcodt INTEGER,
            // n1 INTEGER,n2 INTEGER,n3 INTEGER,tgratuitas DECIMAL(12,2),idtr DECIMAL(10,2),nexon DECIMAL(12,2),ndscto DECIMAL(12,2)

            $ls = "SELECT FuningresaDocumentoElectronico(:tdocv,:formv,:cndocv,:fechv,:txtreferencia,:subtotal,:igv,:total,:ndo2v,:monev,
            :dola,:vigv,'K',:idcliev,:nidturno,:nidus,:almv,:n1,:n2,:n3,:totalgratuitas,:idtr,:exon,:descuento) AS ID";

            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' =>  $this->fechv,
                'txtreferencia' => $cabecera["txtreferencia"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera["monev"],
                'dola' => session()->get('gene_dola'),
                'vigv' => session()->get('gene_igv'),
                'idcliev' => $cabecera["idcliev"],
                'nidturno' => 0,
                'nidus' => $cabecera["nidus"],
                'almv' => $cabecera["almv"],
                'n1' => ($nidcta1),
                'n2' => ($nidcta2),
                'n3' => ($nidcta3),
                'totalgratuitas' => 0,
                'idtr' => 0,
                'exon' => 0,
                'descuento' => 0
            ]);

            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($ls, $st->errorInfo());
                $rpta = array('mensaje' => $st->errorInfo() . var_dump($st->debugDumpParams()), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();

            if ($cabecera['formv'] == 'C') {
                // nauto INTEGER,nid INTEGER,cndoc VARCHAR(12),
                // cest CHAR,cmon CHAR,crefe VARCHAR(60),dfecha DATE,dfevto DATE,
                // ctipo CHAR,cdocp VARCHAR(12),nimpo FLOAT,ninic FLOAT,
                // idven INTEGER,nimpoo FLOAT,nidus INTEGER,nidtda INTEGER,cpc VARCHAR(45)
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:almv,'') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" =>  $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera['idvenv'],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    'almv' => $cabecera['almv']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            // dfecha DATE,cndoc VARCHAR(12),
            // cdeta VARCHAR(100),idcta INTEGER,sdeudor DECIMAL(12,2),
            // sacreedor DECIMAL(12,2),cmone CHAR,ndolar DECIMAL(5,3),nidus INTEGER,nidcp INTEGER,nidauto INTEGER,
            // cform CHAR,cdcto VARCHAR(12),ctdoc VARCHAR(2),ncodt INTEGER,
            // nturno INTEGER,ndscto DECIMAL(12,2),creft VARCHAR(50),tipot CHAR,ctarjetabco VARCHAR(50)

            $sql = "call ProIngresaDatosLcajaEfectivoCturnos20(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,
            :cform,:cndocv,:ctdoc,:almv,:nturno,:descuento,:referenciatarjeta,:tipotarjeta,:tarjetabanco) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'razov' => $cabecera['razov'],
                'n3' => $nidcta3,
                'total' => floatval($cabecera['txtefectivo']) <= 0 ? $cabecera["total"] : $cabecera['txtpago'],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"],
                'nturno' => 0,
                'descuento' => 0,
                'referenciatarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                'tipotarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                'tarjetabanco' => ($cabecera["formv"] != 'E' ? ' ' : ' ')
            ]);
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $query->errorInfo());
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            //Si es un pago mixto entra a la condicion :) 
            if (floatval($cabecera['txtefectivo']) > 0) {
                $sql = "call ProIngresaDatosLcajaEfectivoCturnos20(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,
                :cform,:cndocv,:ctdoc,:almv,:nturno,:descuento,:referenciatarjeta,:tipotarjeta,:tarjetabanco) ";
                $query = $pdo->prepare($sql);
                $query->execute([
                    'fechv' =>  $this->fechv,
                    'cndocv' => $this->cndoc,
                    'razov' => $cabecera['razov'],
                    'n3' => $nidcta3,
                    'total' => $cabecera["txtefectivo"],
                    'nidus' => $cabecera["nidus"],
                    'nidauto' => $id,
                    'nidclie' => $cabecera["idcliev"],
                    'cform' => $cabecera["formv"],
                    'ctdoc' => $cabecera["tdocv"],
                    'almv' => $cabecera["almv"],
                    'nturno' => 0,
                    'descuento' => 0,
                    'referenciatarjeta' => ' ',
                    'tipotarjeta' => ' ',
                    'tarjetabanco' => ' '
                ]);
                if ($query->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sql, $query->errorInfo());
                    $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            // nid INTEGER,cc INTEGER,ct CHAR,npr DECIMAL(12,5),
            // nct DECIMAL(12,4),cincl CHAR,tmvto CHAR,ccodv INTEGER,calma INTEGER,nidcont INTEGER,npreref DECIMAL(12,5),
            // npreciol DECIMAL(12,5)

            $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
            $sqliki = "SELECT FunIngresaKardex1(:nid,:cc,'V',:npr,:nct,:igv,'V',:ccod,:calma,:nidcont,:nidcosto1,0" . (($tipocompraexon == 'N') ? '' : ',0') . ") AS NID";
            $carritov = session()->get('carritov', []);
            $sqlas = "CALL astock(:coda,:nalma,:ccant,'V')";
            $sw = 1;
            foreach ($carritov as $item) {
                if ($item['activo'] == 'A') {
                    $execas = $pdo->prepare($sqlas);
                    $cant = floatval($item['cantidad']);
                    $execas->execute([
                        "coda" => $item['coda'],
                        "nalma" => $cabecera['almv'],
                        "ccant" => $cant
                    ]);
                    if ($execas->errorCode() != '00000') {
                        enviarmensajerror($sqlas, $execas->errorInfo());
                        var_dump($execas->errorInfo());
                        $sw = 0;
                        break;
                    }
                    $execiki = $pdo->prepare($sqliki);
                    $cant = floatval($item['cantidad']);
                    $prec = floatval($item['precio']);
                    $igv = $cabecera['optigv'];
                    $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                    $execiki->execute([
                        "nid" => $id,
                        "cc" => $item['coda'],
                        "npr" => $prec,
                        "nct" => $cant,
                        "ccod" => $cabecera['idvenv'],
                        "calma" => $cabecera['almv'],
                        "nidcont" => 0,
                        "nidcosto1" => $costo,
                        'igv' => $igv
                    ]);
                    if ($execiki->errorCode() != '00000') {
                        var_dump($execiki->errorInfo());
                        enviarmensajerror($execiki, $execiki->errorInfo());
                        $sw = 0;
                        break;
                    }
                }
            }
            if (!empty($cabecera['idautop'])) {
                $sqlsc = "call ProIngresaCanjePedidosF(:idauto,:idautop) ";
                $execsc = $pdo->prepare($sqlsc);
                $execsc->execute([
                    'idauto' =>  $id,
                    'idautop' => $cabecera['idautop']
                ]);
                // $query->debugDumpParams();
                if ($execsc->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sql, $execsc->errorInfo());
                    $rpta = array('mensaje' => $execsc->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "No se registro", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        } finally {
            $ncon->close();
        }
        return $rpta;
    }
    function actualizarVenta($cabecera)
    {
        $this->fechv = $cabecera["fechv"];
        $this->cndoc = $cabecera["cndocv"];
        // ctdoc VARCHAR(2),cform CHAR,cndoc VARCHAR(12),dfecha DATE,dfechar DATE,cdetalle VARCHAR(120),
        // nv DECIMAL(12,2),nigv DECIMAL(12,2),nt DECIMAL(12,2),cndo2 VARCHAR(10),cm CHAR,
        // ndolar DECIMAL(6,4),ni DECIMAL(6,4),ctg CHAR,ccodp INTEGER,cmvto CHAR,nus INTEGER,opt INTEGER,nidcodt INTEGER,
        // n1 INTEGER,n2 INTEGER,n3 INTEGER,cdetalle1 VARCHAR(120),npvta DECIMAL(10,2),nidauto INTEGER
        $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
        if ($tipocompraexon == 'N') {
            $sql = "CALL ProActualizaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,:nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,
            :cmvto,:nus,:nicbper,:nidcodt,:n1,:n2,:n3,:idautoanticipo,:npvta,:nidauto)";
        } else {
            $sql = "CALL ProActualizaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfecha,:cdetalle,:nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,
            :cmvto,:nus,:nicbper,:nidcodt,:n1,:n2,:n3,:idautoanticipo,:npvta,:nidauto,'0')";
        }
        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($sql);
            $st->execute([
                'ctdoc' => $cabecera["tdocv"],
                'cndoc' => $cabecera["cndocv"],
                'dfecha' =>  $cabecera["fechv"],
                'cform' => $cabecera["formv"],
                'cdetalle' => $cabecera["txtreferencia"],
                'nv' => $cabecera["subtotal"],
                'nigv' => $cabecera["igv"],
                'nt' => $cabecera["total"],
                'cndo2' => $cabecera["ndo2v"],
                'cm' => $cabecera["monev"],
                'ndolar' => session()->get('gene_dola'),
                'ni' =>  session()->get("gene_igv"),
                'ctg' => 'K',
                'ccodp' => $cabecera["idcliev"],
                'cmvto' => 'V',
                'nus' => $cabecera["nidus"],
                'nicbper' => '1',
                'nidcodt' => $cabecera["almv"],
                'n1' => ($nidcta1),
                'n2' => ($nidcta2),
                'n3' => ($nidcta3),
                'idautoanticipo' => $cabecera['txtidautovtaanticipo'],
                'npvta' => '0',
                'nidauto' => $cabecera["nidautov"]
            ]);
            $st->closeCursor();
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $st->errorInfo());
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $sql = "call ProIngresaDatosLcajaEfectivoCturnos20(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,
            :cform,:cndocv,:ctdoc,:almv,:nturno,:descuento,:referenciatarjeta,:tipotarjeta,:tarjetabanco) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $cabecera["fechv"],
                'cndocv' => $cabecera["cndocv"],
                'razov' => $cabecera['razov'],
                'n3' => $nidcta3,
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' =>  $cabecera["nidautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"],
                'nturno' => 0,
                'descuento' => 0,
                'referenciatarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                'tipotarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                'tarjetabanco' => ($cabecera["formv"] != 'E' ? ' ' : ' ')
            ]);
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $query->errorInfo());
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            if (floatval($cabecera['txtefectivo']) > 0) {
                $sql = "call ProIngresaDatosLcajaEfectivoCturnos20(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,
                :cform,:cndocv,:ctdoc,:almv,:nturno,:descuento,:referenciatarjeta,:tipotarjeta,:tarjetabanco) ";
                $query = $pdo->prepare($sql);
                $query->execute([
                    'fechv' =>  $cabecera["fechv"],
                    'cndocv' => $cabecera["cndocv"],
                    'razov' => $cabecera['razov'],
                    'n3' => $nidcta3,
                    'total' => $cabecera["total"],
                    'nidus' => $cabecera["nidus"],
                    'nidauto' =>  $cabecera["nidautov"],
                    'nidclie' => $cabecera["idcliev"],
                    'cform' => $cabecera["formv"],
                    'ctdoc' => $cabecera["tdocv"],
                    'almv' => $cabecera["almv"],
                    'nturno' => 0,
                    'descuento' => 0,
                    'referenciatarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                    'tipotarjeta' => ($cabecera["formv"] != 'E' ? ' ' : ' '),
                    'tarjetabanco' => ($cabecera["formv"] != 'E' ? ' ' : ' ')
                ]);
                if ($query->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sql, $query->errorInfo());
                    $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $lsqlcred = "Call ProActualizaCreditos(:nauto,:nu)";
            $stsqlcreditos = $pdo->prepare($lsqlcred);
            $stsqlcreditos->execute([
                "nauto" => $cabecera['nidautov'],
                "nu" => session()->get("usuario_id")
            ]);
            $stsqlcreditos->closeCursor();
            if ($stsqlcreditos->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($lsqlcred, $stsqlcreditos->errorInfo());
                $rpta = array('mensaje' => $stsqlcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $sqldbcred = "UPDATE fe_cred SET acti='I' WHERE ndoc=:ndoc";
            $execdbcred = $pdo->prepare($sqldbcred);
            $execdbcred->execute([
                "ndoc" => $this->cndoc,
            ]);
            if ($stsqlcreditos->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sqldbcred, $execdbcred->errorInfo());
                $rpta = array('mensaje' => $execdbcred->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:almv,'') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["nidautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" =>  $cabecera["cndocv"],
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera["idvenv"],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    'almv' => $cabecera['almv']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            $carritov = session()->get('carritov', []);
            $sqlas = "CALL ProActualizaStock(:coda,:nalma,:ccant,'V',:ccant)";
            if ($tipocompraexon == 'N') {
                $sqlinserta = "SELECT FunIngresaKardex1(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:vcom) AS IDD";
                $sqlactualiza = "CALL ProActualizaKardex1(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom)";
            } else {
                $sqlinserta = "SELECT FunIngresaKardex1(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:vcom,'0') AS IDD";
                $sqlactualiza = "CALL ProActualizaKardex1(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom,'0')";
            }
            $sw = 1;
            foreach ($carritov as $item) {
                if ($item['activo'] == 'A') {
                    $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
                    if ($ventascondescuento == 'S') {
                        $sqlinserta = "SELECT FunIngresaKardex1Descuento(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:vcom," . $item['descuento'] . ") AS IDD";
                        $sqlactualiza = "CALL ProActualizaKardex1Descuento(:nid,:cc,:nicbper,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom," . $item['descuento'] . ")";
                    }
                    if ($item['nreg'] == 0) {
                        $query = $pdo->prepare($sqlinserta);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => 'V',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => $cabecera["idvenv"],
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "vcom" => '0'
                        ]);
                    } else {
                        $query = $pdo->prepare($sqlactualiza);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => 'V',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => $cabecera["idvenv"],
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "nidkar" => $item['nreg'],
                            "op" => '1',
                            "xcom" => '0'
                        ]);
                    }
                } else {
                    if ($item['nreg'] > 0) {
                        $query = $pdo->prepare($sqlactualiza);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                        $query->execute([
                            "nid" => $cabecera["nidautov"],
                            "cc" => $item['coda'],
                            "nicbper" => 'V',
                            "npr" => $nprecio,
                            "nct" => $ncant,
                            "cincl" => $cabecera["optigv"],
                            "tmvto" => 'K',
                            "ccodv" => $cabecera["idvenv"],
                            "calma" => $cabecera["almv"],
                            "nidcosto1" => $costo,
                            "nidkar" => $item['nreg'],
                            "op" => '0',
                            "xcom" => '0'
                        ]);
                    }
                }
                if ($query->errorCode() != '00000') {
                    $sw = 0;
                    var_dump($query->errorInfo());
                    enviarmensajerror($sqlinserta . ' o ' . $sqlactualiza, $query->errorInfo());
                    break;
                }
                $execas = $pdo->prepare($sqlas);
                $cant = floatval($item['cantidad']);
                $ncaant = floatval($item['caant']);
                $execas->execute([
                    "coda" => $item['coda'],
                    "nalma" => $cabecera['almv'],
                    "ccant" => $cant,
                    "ncaant" => $ncaant
                ]);
                if ($execas->errorCode() != '00000') {
                    enviarmensajerror($sqlas, $execas->errorInfo());
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al ejecutar', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($query->errorCode() == '00000') {
                $pdo->commit();
                $ncon->close();
                $rpta = array('mensaje' => "Se actualizo satisfactoriamente", "ndoc" => $cabecera["cndocv"], "estado" => '1');
            }
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function grabarVentaCanje($cabecera, $detallecanje)
    {
        $this->fechv = $cabecera["fechv"];
        $this->fechvv = $cabecera["fechvv"];
        $sqlacg = "CALL ProActualizaCanjeguia(:tdocv,:formv,:cndocv,:fechv,:fechvv,'',:subtotal,:igv,:total,:ndo2v,:monev,:dola,:vigv,'K',
        :idcliev,'V',:nidus,1,:almv,:n1,:n2,:n3,:iddire,:idautog,:idauto,:retencion)";
        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }
        try {
            $correlativo = SerieController::correlativo('1', $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execacg = $pdo->prepare($sqlacg);
            $execacg->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' =>  $cabecera["fechv"],
                'fechvv' =>  $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera["monev"],
                'dola' => session()->get('gene_dola'),
                'vigv' => session()->get("gene_igv"),
                'idcliev' => $cabecera["idcliev"],
                'nidus' => $cabecera["nidus"],
                'almv' => $_SESSION['idalmacen'],
                'n1' => ($nidcta1),
                'n2' => ($nidcta2),
                'n3' => ($nidcta3),
                'iddire' => 0,
                'idautog' => $cabecera["idautog"],
                'idauto' => $cabecera["idautov"],
                'retencion' => (floatval($_SESSION['gene_montoretencion']) <= floatval($cabecera['total']) ? ($cabecera['txtclienteretencion'] == 'S' ? round($cabecera['total'] * $_SESSION['gene_retencion'], 2) : 0) : 0)
            ]);
            if ($execacg->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                enviarmensajerror($sqlacg, $execacg->errorInfo());
                $rpta = array('mensaje' => 'Error al registrar', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $execacg->closeCursor();
            // $id = $st->fetchColumn();
            $sqlidce = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,'',:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $execidce = $pdo->prepare($sqlidce);
            $execidce->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'n3' => $nidcta3,
                'total' => $cabecera["total"],
                'nidus' =>  $cabecera["nidus"],
                'nidauto' => $cabecera["idautov"],
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $_SESSION['idalmacen']
            ]);
            // $query->debugDumpParams();
            if ($execidce->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sqlidce, $execidce->errorInfo());
                $rpta = array('mensaje' => "No se actualizaron los créditos", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $execidce->closeCursor();
            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,:almv,' ') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $cabecera["idautov"],
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $this->fechv,
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => $cabecera["idvenv"],
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"],
                    'almv' => $cabecera['almv']
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlcreditos, $stcreditos->errorInfo());
                    $rpta = array('mensaje' => 'Ocurrió un error', "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            $sw = 1;
            $sqlk = "update fe_kar set prec=:prec,incl=:igv,codv=:codv,kar_Cost=:costo where idart=:idart and idauto=:idauto";
            $idauto = $cabecera["idautov"];
            $optigv = $cabecera['optigv'];
            foreach ($detallecanje as $item) {
                $execk = $pdo->prepare($sqlk);
                $execk->execute([
                    "prec" => $item['precio'],
                    "idart" => $item["id"],
                    "igv" => $optigv,
                    'codv' => $cabecera["idvenv"],
                    'costo' => $item["costo"],
                    "idauto" => $idauto
                ]);
                if ($execk->errorCode() != '00000') {
                    $sw = 0;
                    enviarmensajerror($sqlk, $execk->errorInfo());
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $execk->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => "Error al actualizar correlativo", "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => "No se registro " . $pdo_error->getMessage(), "ndoc" => '', "estado" => '0');
        }
        return $rpta;
    }
    function grabarventacanjetr($cabecera, $detalle)
    {
        try {
            $correlativo = SerieController::correlativo('1', $cabecera["tdocv"]);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ls = "SELECT FuningresaDocumentoElectronicoDetraccion(
                :tdocv,:formv,:cndocv,:fechv,'web', :subtotal,:igv,:total,:ndo2v,:monev,
                :dola,:vigv,'T',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'027','0.00','0.00',:detraccion) AS ID";
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' => $cabecera["fechv"],
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera['monev'],
                'dola' => session()->get("gene_dola"),
                'vigv' => session()->get("gene_igv"),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $_SESSION['idalmacen'],
                'n1' => session()->get("gene_idctav"),
                'n2' => session()->get("gene_idctai"),
                'n3' => session()->get("gene_idctat"),
                'detraccion' => $cabecera["detraccion"]
            ]);
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());S
                $rpta = array('mensaje' => $st->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $st->fetchColumn();
            $st->closeCursor();
            $sqlacguiatr = "update fe_guiastr set guia_idau=:nid where guia_idgui=:nidg";
            $stguiastr = $pdo->prepare($sqlacguiatr);
            $stguiastr->execute([
                'nid' => $id,
                'nidg' => session()->get('idautog')
            ]);
            if ($stguiastr->errorCode() != '00000') {
                $pdo->rollBack();
                // $st->debugDumpParams();
                // print_r($st->errorCode());
                $rpta = array('mensaje' => $stguiastr->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $stguiastr->closeCursor();
            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,'',:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $cabecera["fechv"],
                'cndocv' => $this->cndoc,
                'n3' => session()->get('gene_idctat'),
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $_SESSION['idalmacen']
            ]);
            // $query->debugDumpParams();
            if ($query->errorCode() != '00000') {
                // \print_r($query->errorInfo());
                // \print_r($query->debugDumpParams());
                // \print_r($query->errorCode());
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $query->closeCursor();
            if ($cabecera['formv'] == 'C') {
                $sqlcreditos = "select FunRegistraCreditos(:nauto,:nid,:cndoc,'C',:cmon,:crefe,:dfecha,:dfevto,
                :ctipo,:cdocp,:nimpo,:ninic,:idven,:nimpoo,:nidus,1,'web') as nid";
                $stcreditos = $pdo->prepare($sqlcreditos);
                $stcreditos->execute([
                    "nauto" => $id,
                    "nid" => $cabecera["idcliev"],
                    "cndoc" => $this->cndoc,
                    "cmon" => $cabecera['monev'],
                    "crefe" => "VENTA AL CREDITO",
                    "dfecha" => $cabecera["fechv"],
                    "dfevto" => $cabecera['fechvv'],
                    "ctipo" => "F",
                    "cdocp" => $this->cndoc,
                    "nimpo" => $cabecera["total"],
                    "ninic" => 0,
                    "idven" => 4,
                    "nimpoo" => $cabecera["total"],
                    "nidus" => $cabecera["nidus"]
                ]);
                if ($stcreditos->errorCode() != '00000') {
                    $pdo->rollBack();
                    // $st->debugDumpParams();
                    // print_r($st->errorCode());
                    // print_r($st->errorInfo());
                    $rpta = array('mensaje' => $stcreditos->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }
            $sql1 = "CALL ProIngresaDetalleVta(:cdesc,:nitem,'0','0',:nid,:nprecio,:ncant,:cunid)";
            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $query1 = $pdo->prepare($sql1);
                $i++;
                $desc = $item['descripcion'];
                $cant = floatval($item['cantidad']);
                $unidad = $item['unidad'];
                $prec = floatval($item['precio']);
                // $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                $query1->execute([
                    "cdesc" => $desc,
                    "nitem" => $i,
                    "nid" => $id,
                    "nprecio" => $prec,
                    "ncant" => $cant,
                    "cunid" => $unidad
                ]);
                //  $query1->debugDumpParams();
                if ($query1->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query1->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function anularVentaPorID($id, $idusua)
    {
        try {
            $sql = "call ProAnulaTransacciones(@estado,'','','V',:id,:nu,'S',:dfecha,:nu1,:idtienda)";
            $st = $this->prepare($sql);
            $st->execute([
                'id' => $id,
                'nu' => session()->get("usuario_id"),
                'dfecha' => date('Y-m-d'),
                'nu1' => $idusua,
                'idtienda' => $_SESSION['idalmacen']
            ]);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    function buscarDetalleAnularVenta($num, $tdoc, $tipom)
    {
        $sql = "SELECT a.idauto,a.ndoc,a.fech,a.mone,b.razo,a.impo AS importe,a.idcliente AS codi,idauto,form,a.idusua AS idusuav,rcom_mens,LEFT(rcom_mens,1) AS estadoenviado,tdoc FROM
                fe_rcom AS a JOIN fe_clie AS b ON(a.idcliente=b.idclie) WHERE a.ndoc=:num AND tdoc=:tdoc AND tipom=:tipom
                and a.acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
            'tdoc' => $tdoc,
            'tipom' => $tipom
        ]);
        return $query;
    }
    function consultarVentasPorCliente($idCliente)
    {
        $sql = "SELECT ndoc AS dcto,a.fech,b.nruc,b.razo,IF(a.mone='S','Soles','Dólares') AS mone,
        a.valor,a.rcom_exon,CAST(0 AS DECIMAL(12,2)) AS inafecto,b.idclie,b.ndni,
        a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml,tcom
        FROM fe_rcom AS a 
        INNER JOIN fe_clie AS b ON (a.idcliente=b.idclie),fe_gene AS v
        WHERE a.acti='A' AND impo<>0 and impo>0 AND a.`idcliente`=:idCliente and tdoc not in ('07','08','09','20') ORDER BY fech,ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idCliente' => $idCliente
        ]);
        return $query;
    }
    function consultarVentasxserviciosxCliente($idCliente)
    {
        $sql = "SELECT ndoc AS dcto,a.fech,b.nruc,b.razo,IF(a.mone='S','Soles','Dólares') AS mone,
        a.valor,a.rcom_exon,CAST(0 AS DECIMAL(12,2)) AS inafecto,b.idclie,b.ndni,
        a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml,tcom
        FROM fe_rcom AS a 
        INNER JOIN fe_clie AS b ON (a.idcliente=b.idclie),fe_gene AS v
        WHERE a.acti='A' AND impo<>0 AND a.`idcliente`=:idCliente and tcom='T' ORDER BY fech,ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idCliente' => $idCliente
        ]);
        return $query;
    }
    function consultarDetalleVtaDirecta($idauto)
    {
        $sql = "SELECT idkar,a.idart,a.`descri`,idauto,k.`prec`,cant,acti as activo
        FROM fe_kar k
        INNER JOIN fe_art a ON k.`idart`=a.`idart` 
        WHERE idauto=:idauto and acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    function consultarDetalleVtaServicio($idauto)
    {
        $sql = "SELECT detv_idvt AS idkar, '1' AS idart, 'SERVICIO' AS descri,detv_idau AS idauto,detv_prec AS prec,detv_cant AS cant, detv_acti AS activo
        FROM fe_detallevta
        WHERE detv_idau=:idauto AND detv_acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    function registroventasple($mes, $ano)
    {
        $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
        if ($multiempresa == 'N') {
            $a = ' and codt<>:codt ';
            $codt = 0;
        } else {
            $a = ' and codt=:codt ';
            $codt = $_SESSION['idalmacen'];
        }
        // $sql = "select a.form,a.fecr,a.fech,a.tdoc,if(length(trim(a.ndoc))<=10,LEFT(a.ndoc,3),left(a.ndoc,4)) as serie,
        // if(length(trim(a.ndoc))<=10,MID(a.ndoc,4,7),Mid(a.ndoc,5,8)) as ndoc,
        // b.nruc,b.razo,if(a.mone='S',a.valor,round(a.valor*a.dolar,2)) as valor,
        // if(a.mone='S',rcom_exon,round(rcom_exon*a.dolar,2)) as exon,
        // if(a.mone='S',rcom_inaf,round(rcom_inaf*a.dolar,2)) as inafecto,
        // if(a.mone='S',a.igv,round(a.igv*a.dolar,2)) as igv,
        // if(a.mone='S',a.impo,round(a.impo*a.dolar,2)) as importe,
        // if(a.mone='S',rcom_otro,round(rcom_otro*a.dolar,2)) as grati,
        // a.pimpo,rcom_icbper as icbper,
        // a.mone,a.dolar as dola,a.vigv,a.idcliente as codigo,
        // a.deta as detalle,a.idauto,b.ndni,rcom_mens as mensaje,tcom 
        // FROM fe_rcom as a inner join fe_clie  as b ON(b.idclie=a.idcliente)
        // where month(fech)=:mes and year(fech)=:ano and tdoc in('01','07','08','03') and acti<>'I'" . $a . " order by fecr,ndoc";
        $sql = " Select a.form,a.fecr,a.fech,a.tdoc,Left(a.Ndoc,4) As serie,tt.nomb as tipodoc, 
        If(Length(Trim(a.Ndoc))<=10,mid(a.Ndoc,4,7),mid(a.Ndoc,5,8)) As ndoc,
        b.nruc,b.razo,a.valor,rcom_exon As exon,a.igv,a.Impo As importe,rcom_otro As grati,a.pimpo,rcom_icbper As icbper,
        if(a.mone='S',rcom_inaf,round(rcom_inaf*a.dolar,2)) as inafecto,
        a.mone,a.dolar As dola,a.vigv,a.idcliente As codigo,
        a.Deta As detalle,a.idauto,b.ndni,rcom_mens As mensaje,ifnull(p.Fevto,a.fech) As fvto From fe_rcom As a
        inner Join fe_clie  As b On(b.idclie=a.idcliente)
        inner join fe_tdoc as tt on (a.tdoc=tt.tdoc)
        Left Join (Select rcre_idau,Min(c.Fevto) As Fevto From fe_rcred As r inner Join fe_cred As c On c.cred_idrc=r.rcre_idrc Where rcre_acti='A' And Acti='A' And month(fech)=:mes and year(fech)=:ano Group By rcre_idau)  As p On p.rcre_idau=a.Idauto
        Where month(fech)=:mes and year(fech)=:ano and a.tdoc In('01','07','08','03') and impo<>0 And Acti<>'I'" . $a . " Order By fecr,Ndoc";
        $exec = $this->prepare($sql);
        $exec->setFetchMode(PDO::FETCH_ASSOC);
        $exec->execute([
            'mes' => $mes,
            'ano' => $ano,
            'codt' => $codt
        ]);
        // var_dump($exec->debugDumpParams());
        $query = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    function registroventasnc($mes, $ano)
    {
        $sql = "SELECT a.ndoc,a.tdoc,a.fech,b.ncre_idnc AS idn,ncre_idan FROM (SELECT ncre_idnc,ncre_idau,ncre_idan FROM fe_ncven AS n
                INNER JOIN fe_rcom AS r ON r.idauto=n.ncre_idan
                WHERE MONTH(r.fech)=:mes AND YEAR(r.fech)=:ano AND r.acti='A' AND ncre_acti='A' ) AS b
                INNER JOIN fe_rcom AS a ON a.idauto=b.ncre_idau";
        $exec = $this->prepare($sql);
        $exec->setFetchMode(PDO::FETCH_ASSOC);
        $exec->execute([
            'mes' => $mes,
            'ano' => $ano
        ]);
        // var_dump($exec->debugDumpParams());
        $query = $exec->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    function mostrarresumenvtasvendedor($dfi, $dff, $nidv, $cmbAlmacen)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        // $sql = "SELECT c.nomv AS nomb,e.mone,d.razo,e.idcliente,SUM(e.impo) AS impo,SUM(e.`valor`) AS valor,SUM(e.igv) AS igv FROM
        // (SELECT e.idauto,
        // if(mone='S',valor,round(valor*dolar,2)) as valor,
        // if(mone='S',igv,round(igv*dolar,2)) as igv,
        // if(mone='S',impo,round(impo*dolar,2)) as impo,
        // idcliente,k.codv,mone  FROM fe_rcom AS e 
        // INNER JOIN fe_kar AS k ON k.idauto=e.idauto WHERE e.ACTI<>'I' AND k.acti<>'I' 
        // AND e.fech  BETWEEN :dfi AND :dff AND k.`codv`=:nidv
        // GROUP BY idcliente,k.codv,mone) AS e
        // INNER JOIN fe_clie AS d ON d.idclie=e.idcliente 
        // inner JOIN fe_vend AS c ON c.idven=e.codv GROUP BY e.idcliente,nomv,mone ";
        $sql = "SELECT c.nomv AS nomb,e.mone,d.razo,e.idcliente,IF(mone='S',impo,ROUND(impo*dolar,2)) AS impo,e.ndoc,fech,
                IF(mone='S',valor,ROUND(valor*dolar,2)) AS valor,
                IF(mone='S',igv,ROUND(igv*dolar,2)) AS igv
                FROM fe_rcom AS e
                INNER JOIN fe_kar AS k ON e.`idauto`=k.`idauto`
                INNER JOIN fe_clie AS d ON d.idclie=e.idcliente
                INNER JOIN fe_vend AS c ON c.idven=k.codv
                WHERE fech BETWEEN :dfi AND :dff
                AND k.`codv`=:nidv AND idclie>0 and impo<>0 and e.acti='A' " . $a . " group by e.idauto";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'dfi' => $dfi,
            'dff' => $dff,
            'nidv' => $nidv,
            'cmbAlmacen' => $cmbAlmacen
        ]);
        return $query;
    }
    function listarVentasxProducto($dfi, $dff, $cmbAlmacen, $cmbmarca)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        $b = ($cmbmarca == '0') ? ' and m.idmar<>:cmbmarca  ' : ' and m.idmar=:cmbmarca ';
        $sql = 'SELECT a.idart AS coda,a.Descri,a.unid,IFNULL(z.cant,0) AS cant,IFNULL(importe,0) AS importe,
                IFNULL(mes,0) AS mes,m.dmar AS marca,c.dcat AS linea,g.desgrupo AS grupo  
                FROM fe_art AS a
                INNER JOIN fe_mar AS m ON m.idmar=a.idmar
                INNER JOIN fe_cat AS c ON c.idcat=a.idcat
                INNER JOIN fe_grupo AS g ON g.idgrupo=c.idgrupo
                LEFT JOIN  (
                SELECT a.idart AS coda,a.cant,IF(b.mone="S",cant*a.Prec*b.vigv,cant*a.Prec*b.dolar*b.vigv) AS importe,
                e.razo AS referencia,a.alma,MONTH(b.fech) AS mes 
                FROM fe_kar AS a
                INNER JOIN fe_art AS z ON z.idart=a.idart
                INNER JOIN fe_rcom AS b ON b.idauto=a.idauto
                INNER JOIN fe_clie AS e ON e.idclie=b.idcliente
                WHERE a.Acti="A" AND b.Acti="A" AND b.fech BETWEEN :dfi AND :dff' . $a . '  AND tdoc NOT IN("AJ","II")) AS z 
                ON a.idart=z.coda WHERE prod_acti="A"' . $b . ' ORDER BY importe DESC';
        $query = $this->prepare($sql);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff,
            'cmbAlmacen' => $cmbAlmacen,
            'cmbmarca' => $cmbmarca
        ]);
        $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function listarVentasxCliente($dfi, $dff, $idclie, $cmbAlmacen)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        $sql = "SELECT x.fech,x.fecr,x.tdoc,x.ndoc,x.ndo2,x.mone,
        if(x.mone='S',valor,round(valor*x.dolar,2)) as valor,x.pimpo,
        if(x.mone='S',rcom_exon,round(rcom_exon*x.dolar,2)) as exon,
        if(x.mone='S',rcom_inaf,round(rcom_inaf*x.dolar,2)) as inafecto,
        if(x.mone='S',x.igv,round(x.igv*x.dolar,2)) as igv,
        if(x.mone='S',x.impo,round(x.impo*x.dolar,2)) as impo,
        x.dolar AS dola,x.form,x.idauto,x.idcliente,
        y.cant,y.prec,ROUND(y.cant*y.prec,2) AS importe,dsnc,dsnd,gast,
        z.descri,z.unid,w.nomb AS usuario,x.fusua FROM fe_rcom x
        INNER JOIN fe_kar y ON y.idauto=x.idauto
        INNER JOIN fe_usua w ON w.idusua=x.idusua
        INNER JOIN fe_art z  ON z.idart=y.idart
        WHERE x.fech BETWEEN :dfi AND :dff AND x.idcliente=:idclie
        AND x.acti='A' AND tdoc IN (01,03,20) and x.idcliente<>0 AND y.acti='A' " . $a . " ORDER BY fech,x.tdoc,x.ndoc";
        $query = $this->prepare($sql);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff,
            "idclie" => $idclie,
            'cmbAlmacen' => $cmbAlmacen
        ]);
        $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function listarventastocanje()
    {
        try {
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,idcliente,b.`dire`,b.`ubig`,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie),fe_gene as v
                where a.acti='A' and tdoc<>'09' and tdoc<>'20' and tdoc<>'07' and tcom='K' and impo>0 and MONTH(a.fech)=MONTH(LOCALTIME()) and codt=:codt order by fech desc";
            $query = $this->prepare($sql);
            $query->execute([
                'codt' => $_SESSION['idalmacen']
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function listardetallevtatocanje($idauto)
    {
        try {
            $sql = "SELECT a.idart,descri,unid,cant,idkar,idauto FROM fe_kar k
            inner join fe_art a on k.idart=a.idart
            WHERE idauto=:idauto";
            $query = $this->prepare($sql);
            $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute([
                'idauto' => $idauto
            ]);
            return $query;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function grabarvtassol($cabecera)
    {
        $this->fechv = $cabecera["fechv"];
        $this->fechvv = $cabecera["fechvv"];
        if ($cabecera['tdocv'] == '01' || $cabecera['tdocv'] == '03') {
            $nidcta1 = session()->get("gene_idctav");
            $nidcta2 = session()->get("gene_idctai");
            $nidcta3 = session()->get("gene_idctat");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }
        $sql = "SELECT FuningresaDocumentoElectronico(:tdocv,:formv,:cndocv,:fechv,:txtreferencia,:subtotal,:igv,:total,:ndo2v,:monev,
            :dola,:vigv,'K',:idcliev,'V',:nidus,:almv,:n1,:n2,:n3,'0','0','0.00','0','0') AS ID";
        try {
            $this->cndoc = $cabecera['cndoc1'] . $cabecera['cndoc2'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $exec = $pdo->prepare($sql);
            $exec->execute([
                'tdocv' => $cabecera["tdocv"],
                'formv' => $cabecera["formv"],
                'cndocv' => $this->cndoc,
                'fechv' =>  $this->fechv,
                'subtotal' => $cabecera["subtotal"],
                'igv' => $cabecera["igv"],
                'total' => $cabecera["total"],
                'ndo2v' => $cabecera["ndo2v"],
                'monev' => $cabecera["monev"],
                'dola' => session()->get('gene_dola'),
                'vigv' => session()->get('gene_igv'),
                'nidus' => $cabecera["nidus"],
                'idcliev' => $cabecera["idcliev"],
                'almv' => $cabecera["almv"],
                'n1' => $nidcta1,
                'n2' => $nidcta2,
                'n3' => $nidcta3,
                'txtreferencia' => $cabecera["txtreferencia"]
            ]);
            if ($exec->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $exec->errorInfo());
                $rpta = array('mensaje' => $exec->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $id = $exec->fetchColumn();
            $sql = "call ProIngresaDatosLcajaEefectivo11(:fechv,:cndocv,:razov,:n3,:total,'0','S','0',:nidus,:nidclie,:nidauto,:cform,:cndocv,:ctdoc,:almv) ";
            $query = $pdo->prepare($sql);
            $query->execute([
                'fechv' =>  $this->fechv,
                'cndocv' => $this->cndoc,
                'razov' => $cabecera['razov'],
                'n3' => $nidcta3,
                'total' => $cabecera["total"],
                'nidus' => $cabecera["nidus"],
                'nidauto' => $id,
                'nidclie' => $cabecera["idcliev"],
                'cform' => $cabecera["formv"],
                'ctdoc' => $cabecera["tdocv"],
                'almv' => $cabecera["almv"]
            ]);
            // $query->debugDumpParams();
            if ($query->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $query->errorInfo());
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function consultardetalleventa($idauto)
    {
        try {
            $sql = "SELECT a.idart,descri,a.unid AS unid,cant,idkar,idauto,k.prec FROM fe_kar k
            INNER JOIN fe_art a ON k.idart=a.idart
            WHERE idauto=:idauto AND k.acti='A'";
            $query = $this->prepare($sql);
            $query->execute([
                'idauto' => $idauto
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function consultarNotasporCliente($idCliente)
    {
        $sql = "SELECT ndoc AS dcto,a.fech,b.nruc,b.razo,IF(a.mone='S','Soles','Dólares') AS mone,
        a.valor,a.rcom_exon,CAST(0 AS DECIMAL(12,2)) AS inafecto,b.idclie,b.ndni,form,rcom_mens,
        a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml,tcom
        FROM fe_rcom AS a 
        INNER JOIN fe_clie AS b ON (a.idcliente=b.idclie),fe_gene AS v
        WHERE a.acti='A' AND impo<>0 AND a.`idcliente`=:idCliente and tdoc in('20','03') ORDER BY fech,ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idCliente' => $idCliente
        ]);
        return $query;
    }
    function facturarnotasdeventa($idauto, $cmbtdoc, $clienteretencion, $total, $documentoantiguo, $txtformapago)
    {
        try {
            $correlativo = SerieController::correlativo('1', $cmbtdoc);
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();


            // $sql = "update fe_rcom set ndoc=:ndoc,tdoc=:tdoc,fech=:fech,fecr=:fech,idusua1=:idusua,rcom_mret=:retencion where idauto=:idauto";
            // $exec = $pdo->prepare($sql);
            // $exec->execute([
            //     'tdoc' => $cmbtdoc,
            //     'ndoc' => $this->cndoc,
            //     'fech' => date('Y-m-d'),
            //     'idusua' => $_SESSION['usuario_id'],
            //     'idauto' => $idauto,
            //     'retencion' => (floatval($_SESSION['gene_montoretencion']) <= floatval($total) ? ($clienteretencion == 'S' ? round($total * $_SESSION['gene_retencion'], 2) : 0) : 0)
            // ]);

            // Ejecutar procedimiento almacenado
            $sql = "CALL ProConvierteDocumentoaFactura(:id,:tipodocumento,:documento,:documentoantiguo,:formapago,:usuario)";
            $exec = $pdo->prepare($sql);
            $exec->execute([
                'id' => $idauto,
                'tipodocumento' => $cmbtdoc,
                'documento' => $this->cndoc,
                'documentoantiguo' => $documentoantiguo,
                'formapago' => $txtformapago,
                'usuario' => $_SESSION['usuario_id']
            ]);

            if ($exec->errorCode() != '00000') {
                $pdo->rollBack();
                enviarmensajerror($sql, $exec->errorInfo());
                $rpta = array('mensaje' => $exec->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $primerValor = substr($documentoantiguo, 0, 1);
            if ($primerValor === 'P') {
                $igv = $_SESSION['gene_igv'];
                $subtotal = $total / $igv;
                $vigv = $total - $subtotal;
                $sqlctas = 'CALL IngresaCuentasV(:nv,:nigv,:nt,:n1,:n2,:n3,"H","H","D",:idauto,0,0)';
                $execctas = $pdo->prepare($sqlctas);
                $execctas->execute([
                    'nv' => $subtotal,
                    'nigv' => $vigv,
                    'nt' => $total,
                    'n1' => session()->get("gene_idctav"),
                    'n2' => session()->get("gene_idctai"),
                    'n3' => session()->get("gene_idctat"),
                    'idauto' => $idauto
                ]);
                if ($execctas->errorCode() != '00000') {
                    $pdo->rollBack();
                    enviarmensajerror($sqlctas, $execctas->errorInfo());
                    $rpta = array('mensaje' => 'ivc' . $execctas->errorInfo(), "ndoc" => "", "estado" => '0');
                    return $rpta;
                }
            }

            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al aumentar el correlativo', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se registro correctamente", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function obtenerdatosdevtaanticipada($idauto)
    {
        $sql = "SELECT * from fe_rcom where idauto=:idauto and acti='A'";
        $query = $this->prepare($sql);
        $query->execute([
            'idauto' => $idauto
        ]);
        $rs =   $query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function mostrarvtasanuladas($dfi, $dff, $cmbtdoc, $cmbAlmacen)
    {
        try {
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $sql = "select ndoc as dcto,a.fech,b.nruc,b.razo,if(a.mone='S','Soles','Dólares') as mone,
                a.valor,a.rcom_exon,CAST(0 as decimal(12,2)) as inafecto,fusua,
                a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,b.clie_corr,tcom,tdoc,u.nomb as usuario,
                CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml
                FROM fe_rcom as a 
                inner JOIN fe_clie as b ON (a.idcliente=b.idclie)
                inner join fe_usua as u on (a.idusua=u.idusua),fe_gene as v
                where a.fech between :dfi and :dff and a.acti='A' and tdoc<>'09' and idcliente=1" . $tc . $a . " order by fech,ndoc";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbtdoc' => $cmbtdoc,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            $lista = $query->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function consultarsitienedctorelacionado($idauto)
    {
        try {
            $sql = "select * from fe_rcom where idauto=:idauto and acti='A' and tdoc in ('03','01','20')";
            $query = $this->prepare($sql);
            // print($query->debugDumpParams());
            $query->execute([
                'idauto' => $idauto
            ]);
            $lista = $query->fetchAll(PDO::FETCH_ASSOC);
            return $lista;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function listarventasmodificadas()
    {
        $sql = "SELECT r.*,u.nomb as usuario,c.razo as cliente,lres_fech as fechaoperacion
                FROM fe_rcom r
                inner join fe_clie c on r.idcliente=c.idclie
                inner join fe_aresumen a on r.idauto=a.lres_idau
                INNER JOIN fe_usua u ON a.lres_idus=u.idusua
                WHERE r.idcliente>0 AND acti='A' AND tdoc IN ('01','03','GI') 
                AND idusua1>0 AND impo>0 and lres_idus>0 ORDER BY lres_fech";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        return $query;
    }
    function consultardetalleventaxndoc($ndoc)
    {
        try {
            $sql = "SELECT a.idart,descri,a.unid AS unid,cant,idkar,r.idauto,k.prec,a.peso
            FROM fe_kar k
            INNER JOIN fe_art a ON k.idart=a.idart
            inner join fe_rcom r ON k.idauto=r.idauto
            WHERE ndoc=:ndoc AND k.acti='A'";
            $query = $this->prepare($sql);
            $query->execute([
                'ndoc' => $ndoc
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function listarventasxcategoria($dfi, $dff, $cmbAlmacen, $cmbcategoria)
    {
        try {
            $f = ($cmbcategoria == '0') ? ' and c.idcat<>:cmbcategoria  ' : ' and c.idcat=:cmbcategoria ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbalmacen  ' : ' and codt=:cmbalmacen ';
            $sql = "SELECT c.idcat,c.`dcat`,fech,SUM(k.cant*k.prec) AS total
                    FROM fe_rcom r
                    INNER JOIN fe_kar k ON r.idauto=k.`idauto`
                    INNER JOIN fe_art a ON k.idart=a.`idart`
                    INNER JOIN fe_cat c ON c.`idcat`=a.`idcat`
                    WHERE r.fech between :dfi and :dff and r.acti='A' AND c.`line_acti`='A' and idcliente>0 AND k.`acti`='A' " . $a . $f .
                " GROUP BY fech,idcat
                ORDER BY fech,c.`idcat`";
            $query = $this->prepare($sql);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbalmacen' => $cmbAlmacen,
                'cmbcategoria' => $cmbcategoria
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al consultar ' . $e->getMessage());
        }
    }
    function listaprediccionventasxmes($cmbFormaP, $cmbtdoc, $cmbAlmacen)
    {
        try {
            $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
            $sql = "SELECT MONTH(fech) AS mes,SUM(impo) AS importe,COUNT(ndoc) AS cantidadventas
                    FROM fe_rcom 
                    WHERE idcliente>0 AND acti='A' AND impo>0 AND fech>= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                    " .  $f . $a . $tc . " GROUP BY MONTH(fech) ORDER BY fusua DESC";
            $query = $this->prepare($sql);
            $query->execute([
                'cmbFormaP' => $cmbFormaP,
                'cmbtdoc' => $cmbtdoc,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            echo ('Error al Consultar' . $e->getMessage());
        }
    }
    function listarclientesfrecuentes($dfi, $dff, $cmbAlmacen, $cmbFormaP)
    {
        $a = ($cmbAlmacen == '0') ? ' and r.`codt`<>:cmbAlmacen  ' : ' and r.`codt`=:cmbAlmacen ';
        $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
        $sql = "SELECT c.`idclie`,c.razo,SUM(valor) AS valor,SUM(igv) AS igv, SUM(impo) AS importe
                FROM fe_rcom r
                INNER JOIN fe_clie c ON r.`idcliente`=c.`idclie`
                WHERE r.acti='A'and fech between :dfi and :dff AND c.`clie_acti`='A' AND impo>0" . $a . $f . "
                GROUP BY c.`idclie` ORDER BY importe DESC";
        $query = $this->prepare($sql);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff,
            "cmbAlmacen" => $cmbAlmacen,
            'cmbFormaP' => $cmbFormaP
        ]);
        $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
}
