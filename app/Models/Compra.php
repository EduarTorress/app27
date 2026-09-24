<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;
use App\Services\CarritoService;

class Compra extends Modelo
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

    function listarComprasxFecha($dfi, $dff, $cmbmoneda, $cmbAlmacen, $cmbFormaP, $cmbtdoc)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        $tc = ($cmbtdoc == '0') ? ' and tdoc<>:cmbtdoc' : ' and tdoc=:cmbtdoc ';
        $f = ($cmbFormaP == '0') ? ' and form<>:cmbFormaP  ' : ' and form=:cmbFormaP ';
        try {
            $ls = "SELECT a.idauto,a.ndoc AS dcto,a.ndo2,a.fech,a.fecr,b.razo,a.form,mone,tcom,nomb as usuario,
            a.valor,a.igv,a.impo,a.tdoc,fusua
            FROM fe_rcom AS a
            INNER JOIN fe_prov AS b USING(idprov)
            inner join fe_usua as u on a.idusua=u.idusua
            WHERE a.fech BETWEEN :dfi AND :dff AND a.acti<>'I' and tdoc in('01','02','03','GI','07','08') AND mone=:cmbmoneda " . $a . $f . $tc .
                " ORDER BY fech,ndoc";
            $query = $this->prepare($ls);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'cmbmoneda' => $cmbmoneda,
                'cmbAlmacen' => $cmbAlmacen,
                'cmbFormaP' => $cmbFormaP,
                'cmbtdoc' => $cmbtdoc
            ]);
            //print_r($query->errorInfo());
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function buscarDetalleAnularCompra($num, $tdoc)
    {
        $sql = "SELECT a.idauto,a.ndoc,a.fech,a.mone,b.razo,a.impo AS importe,a.idcliente AS codi,idauto,form,a.idusua AS idusuav,rcom_mens,LEFT(rcom_mens,1) AS estadoenviado,tdoc FROM
                fe_rcom AS a JOIN fe_prov AS b ON(a.idprov=b.idprov) WHERE a.ndoc=:num AND tdoc=:tdoc and a.acti='A' and codt=:codt";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
            'tdoc' => $tdoc,
            'codt' => $_SESSION['idalmacen']
        ]);
        return $query;
    }
    function listarComprasxFechaPLE($mes, $ano)
    {
        try {
            $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
            if ($multiempresa == 'N') {
                $a = ' and codt<>:codt ';
                $codt = 0;
            } else {
                $a = ' and codt=:codt ';
                $codt = $_SESSION['idalmacen'];
            }

            // $sql = "SELECT a.form,a.fecr,a.fech,a.tdoc,IF(LENGTH(TRIM(a.ndoc))<=10,LEFT(a.ndoc,3),LEFT(a.ndoc,4)) AS serie,
            // IF(LENGTH(TRIM(a.ndoc))<=10,MID(a.ndoc,4,7),MID(a.ndoc,5,8)) AS ndoc,ndoc as dcto,
            // b.nruc,b.razo,IF(a.mone='S',a.valor,ROUND(a.valor*a.dolar,2)) AS valor,ndo2,
            // IF(a.mone='S',rcom_exon,ROUND(rcom_exon*a.dolar,2)) AS exon,
            // IF(a.mone='S',rcom_inaf,ROUND(rcom_inaf*a.dolar,2)) AS inafecto,
            // IF(a.mone='S',a.igv,ROUND(a.igv*a.dolar,2)) AS igv,
            // IF(a.mone='S',a.impo,ROUND(a.impo*a.dolar,2)) AS impo,
            // IF(a.mone='S',rcom_otro,ROUND(rcom_otro*a.dolar,2)) AS grati,
            // a.pimpo,rcom_icbper AS icbper,
            // a.mone,a.dolar AS dola,a.vigv,a.idprov AS codigo,
            // a.deta AS detalle,a.idauto,b.ndni,rcom_mens AS mensaje,tcom 
            // FROM fe_rcom AS a INNER JOIN fe_prov  AS b ON(b.idprov=a.idprov)
            // WHERE fech BETWEEN :dfi AND  :dff  AND tdoc IN('01','07','08','03') AND acti<>'I' " . $a . " ORDER BY fecr,ndoc";

            $sql = "SELECT a.idauto AS auto,form,fecr,fech,tdoc,IF(LENGTH(TRIM(a.ndoc))<=10,LEFT(a.ndoc,3),LEFT(a.ndoc,4)) AS serie,
                IF(LENGTH(TRIM(a.ndoc))<=10,SUBSTR(a.ndoc,4),SUBSTR(a.ndoc,5)) AS ndoc,nruc,razo,ndo2,ndoc as dcto,
                ROUND(v1+v2+v3+v4,2) AS valorg,c.exon,c.igv AS igvg,c.otros,ROUND(IF(tdoc<>'41',v1+v2+v3+v4+c.exon+c.igv+otros,0),2) AS importe,
                ROUND(IF(tdoc='41',IF(mone='d',c.impo*a.dolar,c.impo),IF(mone='d',pimpo*a.dolar,pimpo)),2) AS pimpo,valor,c.igv,c.impo,
                a.deta,CAST(a.dolar AS DECIMAL(8,3)) AS dola,mone,a.idprov AS codigo,
                IF(tdoc='07',fech,IF(tdoc='08',fech,CAST('0001-01-01' AS DATE))) AS fechn,
                IF(tdoc='07','01',IF(tdoc='08','01',' ')) AS tref,
                IF(tdoc='07',a.ndoc,IF(tdoc='08',a.ndoc,' ')) AS refe,
                ndni,CAST('0' AS UNSIGNED) AS t,
                IFNULL(rcom_detr,'')  AS detra,rcom_fecd AS fechad,
                vigv,tipom AS tipo,fech AS fevto,a.rcom_icbper AS icbper,'m002' AS cuo,IFNULL(p.ncta,'') AS ncta,IFNULL(r.ncta,'') AS ncta1
                FROM fe_rcom AS a INNER JOIN
                (SELECT a.idauto,
                SUM(CASE c.nitem WHEN 1 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS v1,
                SUM(CASE c.nitem WHEN 2 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS v2,
                SUM(CASE c.nitem WHEN 3 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS v3,
                SUM(CASE c.nitem WHEN 4 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS v4,
                SUM(CASE c.nitem WHEN 5 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS igv,
                SUM(CASE c.nitem WHEN 6 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS exon,
                SUM(CASE c.nitem WHEN 7 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS otros,
                SUM(CASE c.nitem WHEN 8 THEN IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END) AS impo,
                CASE
                WHEN SUM(CASE c.nitem WHEN 1 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 1 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 2 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 2 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 3 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 3 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 4 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 4 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 5 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 5 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 6 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 6 THEN c.`idcta` END)
                WHEN SUM(CASE c.nitem WHEN 7 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 7 THEN c.`idcta` END)
                ELSE
                SUM(CASE c.nitem WHEN 8 THEN c.`idcta` END)
                END AS ctav,
                CASE
                WHEN SUM(CASE c.nitem WHEN 8 THEN
                IF(a.mone='s',c.impo,ROUND(c.impo*a.dolar,2)) ELSE 0 END)>0 THEN
                SUM(CASE c.nitem WHEN 8 THEN c.`idcta` END) END AS ctat
                FROM fe_rcom AS a
                INNER JOIN fe_ectasc AS c ON(c.idrcon=a.idauto)
                WHERE acti='A' and tdoc<>'GI' and a.impo<>0 and month(fecr)=:mes AND year(fecr)=:ano " . $a . " 
                GROUP BY idauto)
                AS c  ON(c.idauto=a.idauto)
                JOIN fe_prov  AS b ON(b.idprov=a.idprov)
                LEFT JOIN fe_plan AS p ON p.idcta=c.ctav
                LEFT JOIN fe_plan AS r ON r.idcta=c.ctat
                ORDER BY fech,serie,ndoc";
            $query = $this->prepare($sql);
            $query->execute([
                'mes' => $mes,
                'ano' => $ano,
                'codt' => $codt
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function listarcomprasxproveedor($dfi, $dff, $idprov, $cmbAlmacen)
    {
        $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        $sql = "SELECT x.fech,x.fecr,x.tdoc,x.ndoc,x.ndo2,x.mone,
        if(x.mone='S',valor,round(valor*x.dolar,2)) as valor,x.pimpo,
        if(x.mone='S',rcom_exon,round(rcom_exon*x.dolar,2)) as exon,
        if(x.mone='S',rcom_inaf,round(rcom_inaf*x.dolar,2)) as inafecto,
        if(x.mone='S',x.igv,round(x.igv*x.dolar,2)) as igv,
        if(x.mone='S',x.impo,round(x.impo*x.dolar,2)) as impo,
        x.dolar AS dola,x.form,x.idauto,x.idprov,
        y.cant,y.prec,ROUND(y.cant*y.prec,2) AS importe,dsnc,dsnd,gast,
        z.descri,z.unid,w.nomb AS usuario,x.fusua FROM fe_rcom x
        INNER JOIN fe_kar y ON y.idauto=x.idauto
        INNER JOIN fe_usua w ON w.idusua=x.idusua
        INNER JOIN fe_art z  ON z.idart=y.idart
        WHERE x.fech BETWEEN :dfi AND :dff AND x.idprov=:idprov
        AND x.acti='A' AND tdoc IN (01,03) AND y.acti='A' " . $a . " ORDER BY fech,x.tdoc,x.ndoc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            "dfi" => $dfi,
            "dff" => $dff,
            "idprov" => $idprov,
            'cmbAlmacen' => $cmbAlmacen
        ]);
        return $query;
    }
    function registrocomprasnc($mes, $ano)
    {
        $sql = "SELECT a.Ndoc,a.Tdoc,a.fech,b.ncre_idnc AS idn,ncre_idan  FROM (
                SELECT ncre_idnc,ncre_idau,ncre_idan FROM fe_nccom AS N
                INNER JOIN fe_rcom AS r ON r.idauto=N.ncre_idan
                WHERE MONTH(r.fecr)=:mes AND YEAR(r.fecr)=:ano  AND r.Acti='A' AND ncre_acti='A' ) AS b
                INNER JOIN  fe_rcom AS a ON a.idauto=b.ncre_idau";
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
    function buscarCompraPorID($idauto)
    {
        $sql = "SELECT idauto,alma,idkar,descri,unid,tipro,idart,incl,ndoc,valor,igv,impo,pimpo,prod_idco,fusua,
                cant,prec,fech,fecr,form,exon,ndo2,vigv,idprov,tipo,tdoc,dolar,mone,razo,dire,ciud,nruc,lcaj_idus FROM
                vmuestracompras AS a 
                LEFT JOIN (SELECT lcaj_idau,lcaj_idus FROM fe_lcaja  WHERE lcaj_acti='A' )AS l ON l.lcaj_idau=a.idauto
                WHERE idauto=:idauto ORDER BY idkar";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    function buscarCompraPorIDcontipoexon($idauto)
    {
        $sql = "SELECT idauto,alma,idkar,descri,unid,tipro,idart,incl,ndoc,valor,igv,impo,pimpo,prod_idco,rcom_exon,kar_tigv,
                cant,prec,fech,fecr,form,exon,ndo2,vigv,idprov,tipo,tdoc,dolar,mone,razo,dire,ciud,nruc,lcaj_idus FROM
                vmuestracompras AS a 
                LEFT JOIN (SELECT lcaj_idau,lcaj_idus FROM fe_lcaja  WHERE lcaj_acti='A' )AS l ON l.lcaj_idau=a.idauto
                WHERE idauto=:idauto ORDER BY idkar";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idauto' => $idauto
        ]);
        return $query;
    }
    function validarsicompraexiste($ndoc, $idprov)
    {
        $existe = [];
        $sql = "Select * from fe_rcom where idprov=:idprov and ndoc=:ndoc and acti='A' and impo>0";
        $query = $this->prepare($sql);
        $query->execute([
            'ndoc' => $ndoc,
            'idprov' => $idprov
        ]);
        $existe =  $query->fetchAll(PDO::FETCH_ASSOC);
        return $existe;
    }
    function grabarCompra($cabecera)
    {
        $this->dfecha = $cabecera["fechi"];
        $this->dfechar = $cabecera["fechf"];

        if ($cabecera['tdoc'] == '01' || $cabecera['tdoc'] == '12' || $cabecera['tdoc'] == '50') {
            $nidcta1 = session()->get("gene_idctacv");
            $nidcta2 = session()->get("gene_idctaci");
            $nidcta3 = session()->get("gene_idctact");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
            if ($tipocompraexon == 'N') {
                $sql = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,
                :nv,:nigv,:nt,:cndo2,:cm,:ndolar,:vigv,:ctg,:ccodp,:cmvto,:nus,:opt,:nidcodt,
                :n1,:n2,:n3,:nitem,:npvta) AS ID";
                $st = $pdo->prepare($sql);
                $st->execute([
                    'ctdoc' => $cabecera["tdoc"],
                    'cndoc' => $cabecera["cndoc"],
                    'cform' => $cabecera["form"],
                    'dfecha' => $this->dfecha,
                    'dfechar' => $this->dfechar,
                    'cdetalle' => $cabecera["deta"],
                    'nv' => $cabecera["valor"],
                    'nigv' => $cabecera["nigv"],
                    'nt' => $cabecera["impo"],
                    'cndo2' => $cabecera["ndo2"],
                    'cm' => $cabecera["mon"],
                    'ndolar' => $cabecera["dolar"],
                    'vigv' => session()->get("gene_igv"),
                    'ctg' => '1',
                    'ccodp' => $cabecera["idprov"],
                    'cmvto' => '1',
                    'nus' => $cabecera["nidus"],
                    'opt' => '0',
                    'nidcodt' => $cabecera["alm"],
                    'n1' => ($nidcta1),
                    'n2' => ($nidcta2),
                    'n3' => ($nidcta3),
                    'nitem' => 0,
                    'npvta' => $cabecera['pimpo']
                ]);
            } else {
                $sql = "SELECT FunIngresaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,
                :nv,:nigv,:nt,:cndo2,:cm,:ndolar,:vigv,:ctg,:ccodp,:cmvto,:nus,:opt,:nidcodt,
                :n1,:n2,:n3,:nitem,:npvta,:exon) AS ID";
                $st = $pdo->prepare($sql);
                $st->execute([
                    'ctdoc' => $cabecera["tdoc"],
                    'cndoc' => $cabecera["cndoc"],
                    'cform' => $cabecera["form"],
                    'dfecha' => $this->dfecha,
                    'dfechar' => $this->dfechar,
                    'cdetalle' => $cabecera["deta"],
                    'nv' => $cabecera["valor"],
                    'nigv' => $cabecera["nigv"],
                    'nt' => $cabecera["impo"],
                    'cndo2' => $cabecera["ndo2"],
                    'cm' => $cabecera["mon"],
                    'ndolar' => $cabecera["dolar"],
                    'vigv' => session()->get("gene_igv"),
                    'ctg' => '1',
                    'ccodp' => $cabecera["idprov"],
                    'cmvto' => '1',
                    'nus' => $cabecera["nidus"],
                    'opt' => '0',
                    'nidcodt' => $cabecera["alm"],
                    'n1' => $nidcta1,
                    'n2' => $nidcta2,
                    'n3' => $nidcta3,
                    'nitem' => 0,
                    'npvta' => $cabecera['pimpo'],
                    'exon' => $cabecera['exonerado']
                ]);
            }

            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                return false;
            }

            $id = $st->fetchColumn();

            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $dolar = number_format($cabecera["dolar"], 3, '.', '');
            if ($cabecera["mon"] === 'D') {
                $acre = $total * $dolar;
            } else {
                $acre = $total;
            }

            if ($cabecera['form'] == 'E') {
                $sqll = "CALL ProIngresaDatosLcajaEfectivoCturnos20(:fech,:cdcto,:cdeta,:idcta,0,:sacreedor,
                :cmone,:ndolar,:nidus,'0',:nidauto,:cform,:cdcto,:ctdoc,:nidtda,0,0,'','','')";
                $idusuacaja = ((!empty($_SESSION['config']['compranoingresacaja'])) ? 0 : $cabecera["nidus"]);
                $queryy = $pdo->prepare($sqll);
                $queryy->execute([
                    'fech' => $this->dfechar,
                    'cdeta' => $cabecera['txtproveedor'],
                    'idcta' => $nidcta3,
                    'sacreedor' => $acre,
                    'cmone' => 'S',
                    'ndolar' => $cabecera["dolar"],
                    'nidus' => $idusuacaja,
                    'nidauto' => $id,
                    'cform' => $cabecera["form"],
                    'cdcto' => $cabecera["cndoc"],
                    'ctdoc' => $cabecera["tdoc"],
                    'nidtda' => $cabecera["alm"]
                ]);

                $queryy->closeCursor();

                if ($queryy->errorCode() != '00000') {
                    $queryy->debugDumpParams();
                    // \print_r($queryy->errorInfo());
                    $pdo->rollBack();
                    return false;
                }
            }

            if (!empty($_SESSION['config']['nocuentasxpagar'])) {
                if (!empty($_SESSION['config']['depositocomocredito'])) {
                    if ($cabecera['form'] == 'C' || $cabecera['form'] == 'D') {
                        $sqlidc = "SELECT FUNregistraDeudasCCtas(:nidauto, :nidprov, :cmoneda, :fecha, :impo, :nidusua, :almacen, 'web', :ccta) as NID";
                        $execidc = $pdo->prepare($sqlidc);
                        $execidc->execute([
                            'nidauto' =>  $id,
                            'nidprov' => $cabecera["idprov"],
                            'cmoneda' =>  $cabecera["mon"],
                            'fecha' => $this->dfecha,
                            'impo' => $cabecera["impo"],
                            'nidusua' => session()->get("usuario_id"),
                            'almacen' => $cabecera["alm"],
                            'ccta' => '97'
                        ]);

                        $ididc = $execidc->fetchColumn();

                        $sqlidd = "SELECT FUNINGRESADEUDAS(:nidr,:cndoc,:ctipo,:dfecha,:dfevto,:ctipo,:ndolar,:nimpo,:nidus,:cpc,:nidtda,:cnrou,:cdetalle,:csitua) as nid";
                        foreach ($cabecera['cuentasxpagar'] as $e) {
                            $execidd = $pdo->prepare($sqlidd);
                            $execidd->execute([
                                'nidr' =>  $ididc,
                                'cndoc' => $cabecera["cndoc"],
                                'ctipo' =>  $cabecera["cmbtipodocumentocuentasxpagar"],
                                'dfecha' => $this->dfecha,
                                'dfevto' => $e["txtfechavto"],
                                'ctipo' => $cabecera['cmbtipodocumentocuentasxpagar'],
                                'ndolar' => $cabecera["dolar"],
                                'nimpo' => $e["txtimporte"],
                                'nidus' => session()->get("usuario_id"),
                                'cpc' => 'web',
                                'nidtda' => $cabecera["alm"],
                                'cnrou' => '',
                                'cdetalle' => $e["txtreferenciacxpagar"],
                                'csitua' => ''
                            ]);
                        }
                    }
                } else {
                    if ($cabecera['form'] == 'C') {
                        $sqlidc = "SELECT FUNregistraDeudasCCtas(:nidauto, :nidprov, :cmoneda, :fecha, :impo, :nidusua, :almacen, 'web', :ccta) as NID";
                        $execidc = $pdo->prepare($sqlidc);
                        $execidc->execute([
                            'nidauto' =>  $id,
                            'nidprov' => $cabecera["idprov"],
                            'cmoneda' =>  $cabecera["mon"],
                            'fecha' => $this->dfecha,
                            'impo' => $cabecera["impo"],
                            'nidusua' => session()->get("usuario_id"),
                            'almacen' => $cabecera["alm"],
                            'ccta' => '97'
                        ]);

                        $ididc = $execidc->fetchColumn();

                        $sqlidd = "SELECT FUNINGRESADEUDAS(:nidr,:cndoc,:ctipo,:dfecha,:dfevto,:ctipo,:ndolar,:nimpo,:nidus,:cpc,:nidtda,:cnrou,:cdetalle,:csitua) as nid";
                        foreach ($cabecera['cuentasxpagar'] as $e) {
                            $execidd = $pdo->prepare($sqlidd);
                            $execidd->execute([
                                'nidr' =>  $ididc,
                                'cndoc' => $cabecera["cndoc"],
                                'ctipo' =>  $cabecera["cmbtipodocumentocuentasxpagar"],
                                'dfecha' => $this->dfecha,
                                'dfevto' => $e["txtfechavto"],
                                'ctipo' => $cabecera['cmbtipodocumentocuentasxpagar'],
                                'ndolar' => $cabecera["dolar"],
                                'nimpo' => $e["txtimporte"],
                                'nidus' => session()->get("usuario_id"),
                                'cpc' => 'web',
                                'nidtda' => $cabecera["alm"],
                                'cnrou' => '',
                                'cdetalle' => $e["txtreferenciacxpagar"],
                                'csitua' => ''
                            ]);
                        }
                    }
                }
            }

            if ($tipocompraexon == 'N') {
                // nid INTEGER,cc INTEGER,ct CHAR,npr DECIMAL(12,5),
                // nct DECIMAL(12,4),cincl CHAR,tmvto CHAR,ccodv INTEGER,calma INTEGER,nidcont INTEGER,npreref DECIMAL(12,5),
                // npreciol DECIMAL(12,5)
                $sql = "SELECT FunIngresaKardex1(:nauto,:coda,'C',:prec,:cant,:igv,'K','0',:alm,0,0,0) AS NID";
                $carritoc = session()->get('carritoc', []);
                $sw = 1;
                foreach ($carritoc as $item) {
                    if ($item['activo'] == 'A') {
                        $query = $pdo->prepare($sql);
                        $cant = floatval($item['cantidad']);
                        $prec = floatval($item['precio']);
                        $query->execute([
                            "nauto" => $id,
                            "coda" => $item['coda'],
                            "prec" => $prec,
                            "cant" => $cant,
                            "igv" => $cabecera['igv'],
                            "alm" => $cabecera['alm']
                        ]);
                        if ($query->errorCode() != '00000') {
                            $sw = 0;
                            break;
                        }
                    }
                }
            } else {
                $sql = "SELECT FunIngresaKardex1(:nauto,:coda,'C',:prec,:cant,:igv,'K','0',:alm,0,0,0,:tigv) AS NID";
                $carritoc = session()->get('carritoc', []);
                $sw = 1;
                foreach ($carritoc as $item) {
                    if ($item['activo'] == 'A') {
                        $query = $pdo->prepare($sql);
                        $afecto = "1.00";
                        if ((trim($item['checkafecto']) == "true")) {
                            $afecto = "1.18";
                        }
                        $cant = floatval($item['cantidad']);
                        $prec = floatval($item['precio']);
                        $query->execute([
                            "nauto" => $id,
                            "coda" => $item['coda'],
                            "prec" => $prec,
                            "cant" => $cant,
                            "igv" => $cabecera['igv'],
                            "alm" => $cabecera['alm'],
                            'tigv' => $afecto
                        ]);
                        if ($query->errorCode() != '00000') {
                            $sw = 0;
                            break;
                        }
                    }
                }
            }
            if ($sw == 0) {
                $query->debugDumpParams();
                $pdo->rollBack();
                return false;
            }
            if ($cabecera['actualizarprecios'] == 'S') {
                $sqlc = "CALL ProActualizaPreciosProducto(:coda,:fech,:prec,:nauto,:prov,:cm,:tigv,:dolar,'0')";
                $queryc = $pdo->prepare($sqlc);
                foreach ($carritoc as $item) {
                    if ($item['activo'] == 'A') {
                        if ($cabecera['igv'] == 'N') {
                            $prec = floatval($item['precio']);
                        } else {
                            $prec = floatval($item['precio']) / floatval($_SESSION['gene_igv']);
                        }
                        if (!empty($_SESSION['config']['convercostsolesprodcompra'])) {
                            if ($cabecera['mon'] == 'D') {
                                $prec = $prec * floatval($cabecera['dolar']);
                            }
                        }
                        $queryc->execute([
                            "coda" => $item['coda'],
                            "fech" => $this->dfecha,
                            "prec" => round($prec, 4),
                            "nauto" => $id,
                            "prov" => $cabecera["idprov"],
                            "cm" => $cabecera["mon"],
                            "tigv" => $_SESSION['gene_igv'],
                            "dolar" => $cabecera["dolar"]
                        ]);
                        if ($queryc->errorCode() != '00000') {
                            break;
                        }
                    }
                }
            }

            $sql1 = "CALL astock(:coda,:nalma,:ccant,'C')";
            $query1 = $pdo->prepare($sql1);
            $wc = 1;

            foreach ($carritoc as $item) {
                if ($item['activo'] == 'A') {
                    $cant = floatval($item['cantidad']);
                    $query1->execute([
                        "coda" => $item['coda'],
                        "nalma" => $cabecera['alm'],
                        "ccant" => $cant
                    ]);
                    if ($query1->errorCode() != '00000') {
                        $wc = 0;
                        break;
                    }
                }
            }
            if ($wc == 0) {
                $pdo->rollBack();
                return false;
            }
            if ($query1->errorCode() == '00000') {
                $pdo->commit();
            }
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function actualizarCompra($cabecera)
    {
        $this->dfecha = $cabecera["fechi"];
        $this->dfechar = $cabecera["fechf"];
        $igv = session()->get("gene_igv");

        if ($cabecera['tdoc'] == '01' || $cabecera['tdoc'] == '12' || $cabecera['tdoc'] == '50') {
            $nidcta1 = session()->get("gene_idctacv");
            $nidcta2 = session()->get("gene_idctaci");
            $nidcta3 = session()->get("gene_idctact");
        } else {
            $nidcta1 = 0;
            $nidcta2 = 0;
            $nidcta3 = 0;
        }

        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            if ($cabecera['tdoc'] == 'GI' || $cabecera['tdoc'] == '09') {
                $sqlac = "UPDATE fe_ectasc SET ecta_acti='I' WHERE ecta_idrcon=:idauto";
                $exeac = $pdo->prepare($sqlac);
                $exeac->execute([
                    'idauto' => $cabecera["nidauto"],
                ]);
                $exeac->closeCursor();
                if ($exeac->errorCode() != '00000') {
                    $exeac->debugDumpParams();
                    $pdo->rollBack();
                    return false;
                }
            }

            $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
            $sql = "CALL ProActualizaCabeceraCV(:ctdoc,:cform,:cndoc,:dfecha,:dfechar,:cdetalle,
                        :nv,:nigv,:nt,:cndo2,:cm,:ndolar,:ni,:ctg,:ccodp,
                        :cmvto,:nus,:opt,:nidcodt,:n1,:n2,:n3,0,
                        :npvta,:nidauto" . ($tipocompraexon == 'N' ? ' ' : $cabecera['exonerado'] . ',') . ")";
            $st = $pdo->prepare($sql);
            $st->execute([
                'ctdoc' => $cabecera["tdoc"],
                'cndoc' => $cabecera["cndoc"],
                'cform' => $cabecera["form"],
                'dfecha' => $this->dfecha,
                'dfechar' => $this->dfechar,
                'cdetalle' => $cabecera["deta"],
                'nv' => $cabecera["valor"],
                'nigv' => $cabecera["nigv"],
                'nt' => $cabecera["impo"],
                'cndo2' => $cabecera["ndo2"],
                'cm' => $cabecera["mon"],
                'ndolar' => $cabecera["dolar"],
                'ni' => $igv,
                'ctg' => '1',
                'ccodp' => $cabecera["idprov"],
                'cmvto' => '1',
                'nus' => $cabecera["nidus"],
                'opt' => '0',
                'nidcodt' => $cabecera["alm"],
                'n1' => ($nidcta1),
                'n2' => ($nidcta2),
                'n3' => ($nidcta3),
                'npvta' => $cabecera['pimpo'],
                'nidauto' => $cabecera["nidauto"]
            ]);

            // var_dump($st->debugDumpParams());
            $st->closeCursor();
            if ($st->errorCode() != '00000') {
                $pdo->rollBack();
                return false;
            }

            $total = number_format(CarritoService::totalCompra(), 2, '.', '');
            $dolar = number_format($cabecera["dolar"], 3, '.', '');
            if ($cabecera["mon"] === 'D') {
                $acre = $total * $dolar;
            } else {
                $acre = $total;
            }

            if ($cabecera['form'] == 'E') {
                $sqlc = "CALL ProIngresaDatosLcajaEefectivo12(:fech,:cdcto,:cdeta,:idcta,'0',:sacreedor,
                :cmone,:ndolar,:nidus,'0',:nidauto,:cform,:cdcto,:ctdoc,:tienda,0,0,'','','')";
                $idusuacaja = ((!empty($_SESSION['config']['compranoingresacaja'])) ? 0 : $cabecera["nidus"]);
                $queryc = $pdo->prepare($sqlc);
                $queryc->execute([
                    'fech' => $this->dfechar,
                    'cdeta' => $cabecera["txtproveedor"],
                    'idcta' => $nidcta3,
                    'sacreedor' => $acre,
                    'cmone' => 'S',
                    'ndolar' => $cabecera["dolar"],
                    'nidus' => $idusuacaja,
                    'nidauto' => $cabecera["nidauto"],
                    'cform' => $cabecera["form"],
                    'cdcto' => $cabecera["cndoc"],
                    'ctdoc' => $cabecera["tdoc"],
                    'tienda' => $_SESSION['idalmacen']
                ]);
                $queryc->closeCursor();
                if ($queryc->errorCode() != '00000') {
                    $queryc->debugDumpParams();
                    $pdo->rollBack();
                    return false;
                }
            }

            if ($tipocompraexon == 'N') {
                $sqlinserta = "SELECT FunIngresaKardex1(:nid,:cc,:ct,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,0,:nidcosto1,:nidcosto1) AS IDD";
                $sqlactualiza = "CALL ProActualizaKardex1(:nid,:cc,:ct,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom)";
                $carritoc = session()->get('carritoc', []);
                $sw = 1;

                foreach ($carritoc as $item) {
                    // $nreg = isset($item['nreg']) ?  $item['nreg'] : '0';
                    if ($item['activo'] == 'A') {
                        if ($item['nreg'] == 0) {
                            $query = $pdo->prepare($sqlinserta);
                            $ncant = floatval($item['cantidad']);
                            $nprecio = floatval($item['precio']);
                            $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                            $query->execute([
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
                                "nidcosto1" => $costo,
                                "vcom" => '0'
                            ]);
                        } else {
                            $query = $pdo->prepare($sqlactualiza);
                            $ncant = floatval($item['cantidad']);
                            $nprecio = floatval($item['precio']);
                            $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                            $query->execute([
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
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
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
                                "nidcosto1" => $costo,
                                "nidkar" => $item['nreg'],
                                "op" => '0',
                                "xcom" => '0'
                            ]);
                        }
                    }
                }
            } else {
                $sqlinserta = "SELECT FunIngresaKardex1(:nid,:cc,:ct,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,0,:nidcosto1,:nidcosto1,:tigv) AS IDD";
                $sqlactualiza = "CALL ProActualizaKardex1(:nid,:cc,:ct,:npr,:nct,:cincl,:tmvto,:ccodv,:calma,:nidcosto1,:nidkar,:op,:xcom,:tigv)";
                $carritoc = session()->get('carritoc', []);
                $sw = 1;
                foreach ($carritoc as $item) {
                    // $nreg = isset($item['nreg']) ?  $item['nreg'] : '0';
                    if ($item['activo'] == 'A') {
                        if ($item['nreg'] == 0) {
                            $query = $pdo->prepare($sqlinserta);
                            $afecto = "1.00";
                            if ((trim($item['checkafecto']) == "true")) {
                                $afecto = "1.18";
                            }
                            $ncant = floatval($item['cantidad']);
                            $nprecio = floatval($item['precio']);
                            $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                            $query->execute([
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
                                "nidcosto1" => $costo,
                                "vcom" => '0',
                                'tigv' => $afecto
                            ]);
                        } else {
                            $query = $pdo->prepare($sqlactualiza);
                            $afecto = "1.00";
                            if ((trim($item['checkafecto']) == "true")) {
                                $afecto = "1.18";
                            }
                            $ncant = floatval($item['cantidad']);
                            $nprecio = floatval($item['precio']);
                            $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                            $query->execute([
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
                                "nidcosto1" => $costo,
                                "nidkar" => $item['nreg'],
                                "op" => '1',
                                "xcom" => '0',
                                'tigv' => $afecto
                            ]);
                        }
                    } else {
                        if ($item['nreg'] > 0) {
                            $query = $pdo->prepare($sqlactualiza);
                            $afecto = "1.00";
                            if ((trim($item['checkafecto']) == "true")) {
                                $afecto = "1.18";
                            }
                            $ncant = floatval($item['cantidad']);
                            $nprecio = floatval($item['precio']);
                            $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                            $query->execute([
                                "nid" => $cabecera["nidauto"],
                                "cc" => $item['coda'],
                                "ct" => 'C',
                                "npr" => $nprecio,
                                "nct" => $ncant,
                                "cincl" => $cabecera["igv"],
                                "tmvto" => 'K',
                                "ccodv" => '0',
                                "calma" => $cabecera["alm"],
                                "nidcosto1" => $costo,
                                "nidkar" => $item['nreg'],
                                "op" => '0',
                                "xcom" => '0',
                                'tigv' => $afecto
                            ]);
                        }
                    }
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                return false;
            }
            if ($query->errorCode() == '00000') {
                $pdo->commit();
                $ncon->close();
            }
            if ($cabecera['actualizarprecios'] == 'S') {
                $sqlc = "CALL ProActualizaPreciosProducto(:coda,:fech,:prec,:nauto,:prov,:cm,:tigv,:dolar,'0')";
                $queryc = $pdo->prepare($sqlc);
                foreach ($carritoc as $item) {
                    if ($item['activo'] == 'A') {
                        if ($cabecera['igv'] == 'N') {
                            $prec = floatval($item['precio']);
                        } else {
                            $prec = floatval($item['precio']) / floatval($_SESSION['gene_igv']);
                        }
                        if (!empty($_SESSION['config']['convercostsolesprodcompra'])) {
                            if ($cabecera['mon'] == 'D') {
                                $prec = $prec * floatval($cabecera['dolar']);
                            }
                        }
                        $queryc->execute([
                            "coda" => $item['coda'],
                            "fech" => $this->dfecha,
                            "prec" => round($prec, 3),
                            "nauto" => $cabecera['nidauto'],
                            "prov" => $cabecera["idprov"],
                            "cm" => $cabecera["mon"],
                            "tigv" => $igv,
                            "dolar" => $cabecera["dolar"]
                        ]);
                        if ($queryc->errorCode() != '00000') {
                            break;
                        }
                    }
                }
            }
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function listarComprasxProducto($dfi, $dff, $cmbAlmacen)
    {
        try {
            $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
            $sql = "SELECT a.`descri` AS 'PRODUCTO',TRIM(m.`dmar`) AS 'MARCA',TRIM(a.`unid`) AS 'UNIDAD',sum(k.cant) as cantidad,
            SUM( IF( k.`alma` = '1', k.`cant`, 0 ) ) AS 'SUCURSAL 1',
            SUM( IF( k.`alma` = '2', k.`cant`, 0 ) ) AS 'SUCURSAL 2',
            SUM( IF( k.`alma` = '3', k.`cant`, 0 ) ) AS 'SUCURSAL 3',
            SUM( IF( k.`alma` = '4', k.`cant`, 0 ) ) AS 'SUCURSAL 4',
            SUM( IF( k.`alma` = '5', k.`cant`, 0 ) ) AS 'SUCURSAL 5',
            TRIM(g.`desgrupo`) AS 'GRUPO', TRIM(c.`dcat`) AS 'LINEA'
            FROM fe_art a
            INNER JOIN fe_mar m ON a.`idmar`=m.`idmar`
            INNER JOIN fe_cat c ON a.`idcat`=c.`idcat`
            INNER JOIN fe_grupo g ON c.`idgrupo`=g.`idgrupo`
            INNER JOIN fe_kar k ON a.`idart`=k.`idart`
            INNER JOIN fe_rcom r ON k.`idauto`=r.`idauto`
            INNER JOIN fe_sucu s ON k.`alma`=s.`idalma`
            WHERE r.`acti`='A' AND k.`acti`='A' AND r.idcliente=0 and tdoc<>'AJ'
            AND a.`prod_acti`='A' AND 
            r.`fech` BETWEEN :dfi AND :dff " . $a . " GROUP BY a.`idart`";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                "dfi" => $dfi,
                "dff" => $dff,
                "cmbAlmacen" => $cmbAlmacen
            ]);
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
            return false;
        }
    }
    function anularcompraxid($id, $idusua)
    {
        try {
            $sql = "call ProAnulaTransacciones(@estado,'','','C',:id,:nu,'S',:dfecha,:nu1,:idtienda)";
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
    function listardetalletocanjetraspaso($idauto)
    {
        try {
            $sql = "SELECT a.idart,descri,p.pres_desc AS unid,cant,idkar,idauto,kar_equi,kar_epta,epta_prec,epta_pres,epta_cant,pres_desc,epta_acti,epta_idep,a.peso,uno,dos,tre
                    FROM fe_art a
                    LEFT JOIN fe_epta e ON (a.idart=e.epta_idar)
                    LEFT JOIN `fe_presentaciones` p ON (e.epta_pres=p.pres_idpr)
                    LEFT JOIN fe_kar k ON a.idart=k.idart
                    WHERE idauto=:idauto AND epta_acti='A' and k.acti='A' AND kar_epta=epta_idep";
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
    function consultarcomprasporproveedor($idprov)
    {
        $sql = "SELECT ndoc AS dcto,a.fech,b.nruc,b.razo,IF(a.mone='S','Soles','Dólares') AS mone,
        a.valor,a.rcom_exon,CAST(0 AS DECIMAL(12,2)) AS inafecto,b.idprov,b.ndni,
        a.igv,a.impo,rcom_mens,a.tdoc,a.ndoc,idauto,rcom_arch,tcom,
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml,tcom
        FROM fe_rcom AS a 
        INNER JOIN fe_prov AS b ON (a.idprov=b.idprov),fe_gene AS v
        WHERE a.acti='A' AND impo<>0 AND a.`idprov`=:idprov and tdoc not in ('07','08','09') ORDER BY fech desc";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'idprov' => $idprov
        ]);
        return $query;
    }
    function listarcomprasmodificadas()
    {
        $sql = "SELECT r.*,u.nomb as usuario,p.razo as proveedor,lres_fech as fechaoperacion
                FROM fe_rcom r
                inner join fe_prov p on r.idprov=p.idprov
                inner join fe_aresumen a on r.idauto=a.lres_idau
                INNER JOIN fe_usua u ON a.lres_idus=u.idusua
                WHERE r.idprov>0 AND acti='A' AND tdoc IN ('01','03','GI') 
                AND idusua1>0 AND impo>0 and lres_idus>0 ORDER BY lres_fech";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        return $query;
    }
    function mostrarcomprasdetalladas($dfi, $dff, $cmbAlmacen, $cmbFormaP, $cmbtdoc)
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
				inner join fe_prov as c on c.idprov=a.idprov
				inner join fe_kar as e on e.idauto=a.idauto
				inner join fe_art as d on d.idart=e.idart
				left join fe_cat as l on l.idcat=d.idcat
				left join fe_grupo as g on g.idgrupo=l.idgrupo
				left  join fe_mar as m on m.idmar=d.idmar
				left join fe_fletes as b on b.idflete=d.idflete
				inner join fe_usua as f on f.idusua=a.idusua,fe_gene as z
				where a.fech between :dfi AND :dff and a.acti='A' and e.acti='A' " . $a . $f . $tc . " order by a.fech";
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
}
