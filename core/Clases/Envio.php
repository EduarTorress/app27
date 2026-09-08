<?php

namespace Core\Clases;

use Core\Clases\apifacturacion;
use Core\Clases\conexion;
use Core\Clases\GeneradorXML;
use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;

class Envio
{
    public $empresa;
    public $dias;
    public $filas = array();
    public $xml, $crpta, $idauto;
    public $cdr = "";
    private $sql = "";
    public $nombrexml = "";

    public function generaenvios($ctdoc)
    {
        if ($ctdoc === '01' or $ctdoc === '07' or $ctdoc === '08') {
            $cserie = 'F';
        } else {
            $cserie = 'B';
        }
        $this->sql = "select idauto,a.tdoc,tcom
        FROM fe_rcom as a 
        inner JOIN fe_clie as b ON (a.idcliente=b.idclie)
        where  a.acti<>'I' and LEFT(ndoc,1)=:cserie and left(rcom_mens,1)<>'0'
        and impo<>0 and a.tdoc='01' and datediff(curdate(),fech)>=:ndias AND DATEDIFF(CURDATE(),a.fech)<=3
        union all
        SELECT  a.idauto,a.tdoc,a.tcom
        FROM fe_rcom as a
        inner JOIN fe_clie as b ON (a.idcliente=b.idclie)
        inner join fe_ncven g on g.ncre_idan=a.idauto 
        inner join fe_rcom as w on w.idauto=g.ncre_idau
        where a.acti<>'I' AND LEFT(a.ndoc,1) in ('F') and left(a.rcom_mens,1)<>'0'
        and  a.impo<>0  and w.tdoc=:ctdoc and a.tdoc in('07','08')  and datediff(curdate(),a.fech)>=:ndias and datediff(curdate(),a.fech)<=3";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":ndias", $this->dias);
        $st->bindParam(":ctdoc", $ctdoc);
        $st->bindParam(":cserie", $cserie);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        $xml = new GeneradorXML();
        foreach ($st as $row) {
            switch ($row['tdoc']) {
                case '01':
                    $xml->CrearXMLFatura($row['idauto'], $row['tcom']);
                    break;
                case '07':
                    $xml->CrearXMLNotaCredito($row['idauto'], $this->empresa);
                    break;
                case '08':
                    $xml->CrearXMLNotaDebito($row['idauto'], $this->empresa);
                    break;
            }
        }
    }
    public function consultardcto($nidauto, $ctipovta)
    {
        if ($ctipovta === 'S' or $ctipovta === 'T') {
            $consulta = "SELECT  r.idauto,r.ndoc,r.tdoc,r.fech AS dfecha,IF(r.mone='S','PEN','USD') AS mone,valor,
            cast(0 as decimal(12,2)) as  inafectas,CAST(0 AS DECIMAL(12,2)) AS gratificaciones,r.mone AS moneda,
            CAST(0 AS DECIMAL(12,2)) AS exoneradas,'10' AS tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,
            v.nruc AS rucempresa,v.empresa,v.ubigeo,r.mone AS moneda,
            v.ptop,v.ciudad,v.distrito,c.nruc,IF(tdoc='01','6','1') AS tipodoccliente,c.razo,
            CONCAT(TRIM(c.dire),' ',TRIM(c.ciud)) AS direccion,c.ndni,rcom_otro,CAST(0 AS DECIMAL(10,2)) AS costoref,deta,
            'PE' AS pais,r.igv,CAST(0 AS DECIMAL(12,2)) AS tdscto,CAST(0 AS DECIMAL(12,2)) AS Tisc,
            impo,CAST(0 AS DECIMAL(12,2)) AS montoper,'I' AS incl,
            CAST(0 AS DECIMAL(12,2)) AS totalpercepcion,IFNULL(k.detv_cant,1) AS cant,IFNULL(k.detv_prec,r.impo) AS prec,
            LEFT(r.ndoc,4) AS serie,SUBSTR(r.ndoc,5) AS numero,detv_desc AS descri,detv_idvt AS  coda,
            'NIU' AS unid,'ZZ' AS unid1,s.codigoestab,r.form,gene_usol,gene_csol,'PE' AS pais,
            gene_cert,clavecertificado
            FROM fe_rcom r
            INNER JOIN fe_clie c ON c.idclie=r.idcliente
            INNER JOIN fe_detallevta k ON k.detv_idau=r.idauto
            INNER JOIN fe_sucu s ON s.idalma=r.codt,fe_gene AS v
            WHERE r.idauto=:nidauto AND r.acti='A' AND detv_item>0 AND detv_acti='A'";
        } else {
            $consulta = "select r.idauto,r.ndoc,r.tdoc,r.fech as dfecha,if(r.mone='S','PEN','USD') as mone,valor,
            CAST(0 as decimal(12,2)) as inafectas,CAST(0 as decimal(12,2)) as gratificaciones,r.mone as moneda,
            CAST(0 as decimal(12,2)) as exoneradas,'10' as tigv,vigv,v.rucfirmad,v.razonfirmad,ndo2,
            v.nruc as rucempresa,v.empresa,v.ubigeo,r.mone as moneda,
            v.ptop,v.ciudad,v.distrito,c.nruc,if(tdoc='01','6','1') as tipodoccliente,c.razo,
            concat(TRIM(c.dire),' ',TRIM(c.ciud)) as direccion,c.ndni,rcom_otro,kar_cost as costoref,deta,
            'PE' as pais,r.igv,CAST(0 as decimal(12,2)) as tdscto,CAST(0 as decimal(12,2)) as Tisc,
            impo,CAST(0 as decimal(12,2)) as montoper,k.incl,
            CAST(0 as decimal(12,2)) as totalpercepcion,k.cant,k.prec,LEFT(r.ndoc,4) as serie,
            SUBSTR(r.ndoc,5) as numero,a.unid,a.descri,k.idart as coda,
            ifnull(unid_codu,'NIU')as unid1,s.codigoestab,r.form,gene_usol,gene_csol,'PE' as pais,
            gene_cert,clavecertificado
            from fe_rcom r
            inner join fe_clie c on c.idclie=r.idcliente
            inner join fe_kar k on k.idauto=r.idauto
            inner join fe_art a on a.idart=k.idart
            inner join fe_sucu s on s.idalma=r.codt
            left join fe_unidades as u on u.unid_codu=a.unid, fe_gene as v
            where r.idauto=:nidauto and r.acti='A' and k.acti='A' order by idkar";
        }
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($consulta);
        $st->bindParam(":nidauto", $nidauto);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        return $st;
    }
    public function ActualizaenvioCpe($condicion)
    {
        if ($condicion == 'T') {
            $csql = "update fe_rcom set rcom_fecd=curdate(),rcom_arch=:nxml,rcom_xml=:xml,rcom_cdr=:cdr,rcom_mens=:crpta where idauto=:idauto";
            $ncon = new conexion();
            $st = $ncon->conectar()->prepare($csql);
            $st->bindParam(":xml", $this->xml);
            $st->bindParam(":cdr", $this->cdr);
            $st->bindParam(":idauto", $this->idauto);
            $st->bindParam(":crpta", $this->crpta);
            $st->bindParam(":nxml", $this->nombrexml);
        } else {
            $csql = "update fe_rcom set rcom_fecd=curdate(),rcom_cdr=:cdr,rcom_mens=:crpta where idauto=:idauto";
            $ncon = new conexion();
            $st = $ncon->conectar()->prepare($csql);
            $st->bindParam(":cdr", $this->cdr);
            $st->bindParam(":idauto", $this->idauto);
            $st->bindParam(":crpta", $this->crpta);
        }
        $st->execute();
    }
    public function ObtenerCuotasCredito($nidauto)
    {
        $sql = "select ndoc,cast(impo as decimal(12,2)) as impo,fevto FROM fe_cred AS c 
        INNER JOIN fe_rcred AS r
        ON r.`rcre_idrc`=c.`cred_idrc` 
        WHERE rcre_idau=:idauto and impo>0 AND acti='A'";
        $ncon = new conexion();
        $stf = $ncon->conectar()->prepare($sql);
        $stf->bindParam(":idauto", $nidauto);
        $stf->fetchAll();
        $stf->execute();
        return $stf;
    }
    public function GrabaCDRTicket($cticket, $detalle)
    {
        $this->sql = 'update fe_resboletas set resu_cdr=:cdr,resu_feen=curdate(),resu_mens=:crpta where trim(resu_tick)=:cticket';
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":cdr", $this->cdr);
        $st->bindParam(":crpta", $this->crpta);
        $st->bindParam(":cticket", $cticket);
        $st->execute();
        $sql = "UPDATE fe_rcom SET rcom_mens=:np3,rcom_fecd=curdate() WHERE idauto=:np1";
        foreach ($detalle as $row) {
            $st = $ncon->conectar()->prepare($sql);
            $st->bindParam(':np1', $row['idauto']);
            $st->bindParam(':np3', $this->crpta);
            $st->execute();
        }
    }
    public function consultacdr()
    {
        $sql = " SELECT LEFT(a.ndoc,4) AS serie,SUBSTR(a.ndoc,5,8)  AS numero,a.tdoc,v.nruc AS rucempresa,
        gene_usol,gene_csol,rcom_arch,idauto
        FROM fe_rcom AS a,fe_gene AS v
        WHERE a.acti<>'I' AND LEFT(ndoc,1) IN ('F') AND LEFT(rcom_mens,1)<>'0' AND  
        impo<>0 AND a.tdoc='01' AND DATEDIFF(CURDATE(),a.fech)>=:dias and idcliente>0 AND DATEDIFF(CURDATE(),a.fech)<=3
        UNION ALL
        SELECT LEFT(a.ndoc,4) AS serie,SUBSTR(a.ndoc,5,8)  AS numero,a.tdoc,v.nruc AS rucempresa,
        gene_usol,gene_csol,a.rcom_arch,a.idauto
        FROM fe_rcom AS a
        INNER JOIN fe_ncven g ON g.ncre_idan=a.idauto
        INNER JOIN fe_rcom AS w ON w.idauto=g.ncre_idau,fe_gene AS v
        WHERE a.acti<>'I' AND LEFT(a.ndoc,1) IN ('F') AND LEFT(a.rcom_mens,1)<>'0'
        AND a.impo<>0  AND w.tdoc='01' AND a.tdoc IN('07','08')
        AND DATEDIFF(CURDATE(),a.fech)>=:dias  and a.idcliente>0 AND DATEDIFF(CURDATE(),a.fech)<=3 order by serie,numero";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($sql);
        $st->bindParam(":dias", $this->dias);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        $api = new apifacturacion();
        $emisor = array();
        foreach ($st as $row) {
            $cfile = $row['rucempresa'] . "-" . $row['tdoc'] . "-" .   $row['serie'] . "-" . $row['numero'];
            $emisor = array(
                'ruc'                        =>      $row['rucempresa'],
                'serie'                      =>      $row['serie'],
                'numero'                     =>      $row['numero'],
                'tdoc'                        =>     $row['tdoc'],
                'usuario_secundario'          =>     $row['gene_usol'],
                'clave_usuario_secundario'    =>     $row['gene_csol'],
                'archivo'                     =>     $cfile,
                'idauto'                      =>     $row['idauto'],
                'empresa'                     =>     $this->empresa
            );
            //echo '</br>'.$this->empresa;
            //echo "</br>".$row['serie'] .$row['numero'];
            $api->consultarComprobante($emisor);
        }
    }
    public function RegistraResumenEnvioBoletas($resumen, $ticket)
    {
        try {
            $sql = 'insert into fe_resboletas(resu_fech,resu_tdoc,resu_serie,resu_desd,
            resu_hast,resu_impo,resu_valo,resu_exon,resu_inaf,
            resu_igv,resu_grat,resu_mens,resu_hash,resu_arch,resu_tick,resu_xml)
            values(:dfecha,:ctdoc,:cserie,:cdesde,:chasta,:nimpo,:nvalor,:nexon,:ninaf,:nigv,:ngrati,:cmensaje,:chash,:carchivo,:cticket,:cxml)';
            $sw = true;
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            foreach ($resumen as $f) {
                $ninaf = 0;
                $cmensaje = "";
                $chash = "";
                $st = $pdo->prepare($sql);
                // $st->prepare($sql);
                $st->bindparam(":dfecha", $f['fech']);
                $st->bindparam(":ctdoc", $f['tdoc']);
                $st->bindparam(":cserie", $f['serie']);
                $st->bindparam(":cdesde", $f['desde']);
                $st->bindparam(":chasta", $f['hasta']);
                $st->bindparam(":nimpo", $f['impo']);
                $st->bindparam(":nvalor", $f['valor']);
                $st->bindparam(":nexon", $f['exon']);
                $st->bindparam(":ninaf", $ninaf);
                $st->bindparam(":nigv", $f['igv']);
                $st->bindparam(":ngrati", $f['grati']);
                $st->bindparam(":cmensaje", $cmensaje);
                $st->bindparam(":chash", $chash);
                $st->bindparam(":carchivo", $this->nombrexml);
                $st->bindparam(":cticket", $ticket);
                $st->bindparam(":cxml", $this->xml);
                $st->execute();
                if ($st->errorCode() > 0) {
                    $pdo->rollBack();
                    $sw = false;
                    break;
                }
            }
            if ($sw) {
                $sql = "update fe_gene set gene_nres=gene_nres+1 where idgene=1";
                $st = $pdo->prepare($sql);
                $st->execute();
                $pdo->commit();
            }
        } catch (PDOException $e) {
            print($e);
            $pdo->rollBack();
        }
    }
    public function ObtenerEmisor($df)
    {
        $sql = 'SELECT nruc,empresa,gene_nres,gene_nbaj,ptop,ubigeo,distrito,ciudad,gene_csol,gene_usol,gene_cert,clavecertificado FROM fe_gene WHERE idgene=1';
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($sql);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        $ncon = null;
        $emisor = array();
        $dfecha = strtotime(date('Y-m-d'));
        $a = date("Y", $dfecha);
        $m = date('m', $dfecha);
        $d = date('d', $dfecha);
        $serie = $a . $m . $d;
        foreach ($st as $r) {
            $correlativo = "RC-" . $serie . '-' . $r['gene_nres'];
            $emisor = array(
                'tipodoc' => '6',
                'rucempresa'  => $r['nruc'],
                'empresa'     => $r['empresa'],
                'direccion'   => $r['ptop'],
                'distrito'    => $r['distrito'],
                'provincia'   => $r['ciudad'],
                'departamento' => $r['ciudad'],
                'ubigeo'      => $r['ubigeo'],
                'usuario_secundario'        =>  trim($r['gene_usol']),
                'clave_usuario_secundario'  =>  trim($r['gene_csol']),
                'gene_nres'                 => $r['gene_nres'],
                'correlativo'              => $correlativo,
                'fechaemision'             => $df,
                'fechaenvio'               => date('Y-m-d'),
                'certificado'             => $r['gene_cert'],
                'clavecertificado'        => $r['clavecertificado']
            );
        }
        return $emisor;
    }
    public function ObtenerResumenBoletas($ndesde, $nhasta, $ctdoc)
    {
        $this->sql = 'select idauto,numero from(SELECT idauto,ndoc,
        cast(mid(ndoc,5) as unsigned) as numero FROM fe_rcom f where tdoc=:ctdoc and acti="A" and idcliente>0) as x
                       where numero between :ndesde and :nhasta';
        $ncon = new conexion();
        $stf = $ncon->conectar()->prepare($this->sql);
        $stf->bindParam(':ndesde', $ndesde);
        $stf->bindParam(':nhasta', $nhasta);
        $stf->bindParam(':ctdoc', $ctdoc);
        $stf->execute();
        return $stf;
    }
    public function ObtenerBoletasResumidas($df)
    {
        $this->sql = "select fech,tdoc,left(ndoc,4) as serie,substr(ndoc,5) as numero,If(Length(trim(c.ndni))<8,'0','1') as tipodoc,If(Length(trim(c.ndni))<8,'00000000',c.ndni) as ndni,
         c.razo,cast(if(f.mone='S',valor,valor*dolar) as decimal(12,2)) as valor,
         cast(if(f.mone='S',rcom_exon,rcom_exon*dolar) as decimal(12,2)) as rcom_exon,
         cast(if(f.mone='S',igv,igv*dolar) as decimal(12,2)) as igv,
         cast(if(f.mone='S',impo,impo*dolar) as decimal(12,2)) as impo,
         '' as trefe,'' as serieref,'' as numerorefe,f.idauto
         fROM fe_rcom f 
         inner join fe_clie c on c.idclie=f.idcliente 
         where tdoc='03' and f.fech=:df and acti='A' and idcliente>0 and LEFT(ndoc,1)='B' and left(f.rcom_mens,1)<>'0'
         union all
         select f.fech,f.tdoc,concat('BC',SUBSTR(f.ndoc,3,2)) as serie,substr(f.ndoc,5) as numero,'1' as tipodoc,c.ndni as ndni,
         c.razo,cast(abs(if(f.mone='S',f.valor,f.valor*f.dolar))  as decimal(12,2)) as valor,
         cast(abs(if(f.mone='S',f.rcom_exon,f.rcom_exon*f.dolar)) as decimal(12,2)) as rcom_exon,
         cast(abs(if(f.mone='S',f.igv,f.igv*f.dolar)) as decimal(12,2)) as igv,
         cast(abs(if(f.mone='S',f.impo,f.impo*f.dolar)) as decimal(12,2)) as impo,w.tdoc as trefe,left(w.ndoc,4) as serieref,substr(w.ndoc,5) as numerorefe,f.idauto
         FROM fe_rcom f
         inner join fe_ncven g on g.ncre_idan=f.idauto inner join fe_rcom as w on w.idauto=g.ncre_idau inner join fe_clie c on c.idclie=f.idcliente 
         where f.tdoc='07'  and f.acti='A' and f.idcliente>0 and w.tdoc='03' and f.fech=:df and left(f.rcom_mens,1)<>'0'
         union all
         select f.fech,f.tdoc,concat('BD',SUBSTR(f.ndoc,3,2)) as serie,substr(f.ndoc,5) as numero,'1' as tipodoc,c.ndni,
         c.razo,cast(abs(if(f.mone='S',f.valor,f.valor*f.dolar)) as decimal(12,2)) as valor,
         cast(abs(if(f.mone='S',f.rcom_exon,f.rcom_exon*f.dolar)) as decimal(12,2)) as rcom_exon,
         cast(abs(if(f.mone='S',f.igv,f.igv*f.dolar))  as decimal(12,2)) as igv,
         cast(abs(if(f.mone='S',f.impo,f.impo*f.dolar)) as decimal(12,2)) as impo,w.tdoc as trefe,left(w.ndoc,4) as serieref,substr(w.ndoc,5) as numerorefe,f.idauto
         FROM fe_rcom f
         inner join fe_ncven g on g.ncre_idan=f.idauto inner join fe_rcom as w on w.idauto=g.ncre_idau inner join fe_clie c on c.idclie=f.idcliente 
         where f.tdoc='08'  and f.acti='A' and f.idcliente>0 and w.tdoc='03' and f.fech=:df and left(f.rcom_mens,1)<>'0' order by serie,numero";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(':df', $df);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        $dfecha = strtotime(date('Y-m-d'));
        $dfe = strtotime($dfecha);
        $a = date("Y", $dfecha);
        $m = date('m', $dfecha);
        $d = date('d', $dfecha);
        $serie = $a . $m . $d;
        $i = 0;
        $detalle = array();
        foreach ($st as $row) {
            $i++;
            $detalle[] = array(
                'item'          =>         $i,
                'tdoc'       =>            '03',
                'serie'         =>         $row['serie'],
                'numero'   =>              $row['numero'],
                'condicion'     =>         '1', //1:alta, 2: Modificacion, 3: Anulacion o baja
                'moneda'        =>          'PEN',
                'importe_total' =>          $row['impo'],
                'valor_total'   =>          $row['valor'],
                'exonerado'     =>          $row['rcom_exon'],
                'igv_total'     =>          $row['igv'],
                'tipo_total'    =>          '01', //GRABADOS: 01, EXO: 02, INA:03
                'codigo_afectacion' =>      '1000',
                'nombre_afectacion' =>      'IGV',
                'tipo_afectacion'   =>      'VAT',
                'dni'               =>      $row['ndni'],
                'tipodoccliente'    =>      $row['tipodoc'],
                'idauto'            =>      $row['idauto']
            );
        }
        if ($i == 0) {
            $respuesta = [
                'estado' => '1',
                'mensaje' => 'No hay Boletas Para Enviar',
                'ticket' => ''
            ];
            // echo "</br>" . "No hay Boletas para enviar " . $this->empresa . ' ' . $df;
            return $respuesta;
        }
        $this->sql = "SELECT serie,tdoc,min(numero) as desde,max(numero) as hasta,sum(valor) as valor,SUM(rcom_exon) as exon,
        sum(igv) as igv,sum(impo) as impo
        from(select
        left(ndoc,4) as serie,substr(ndoc,5) as numero,round(if(f.mone='S',valor,valor*dolar),2) as valor,
        round(if(f.mone='S',rcom_exon,rcom_exon*dolar),2) as rcom_exon,round(if(f.mone='S',igv,igv*dolar),2) as igv,
        round(if(f.mone='S',impo,impo*dolar),2) as impo,tdoc
        fROM fe_rcom f where tdoc='03' and fech=:df and acti='A' and idcliente>0  order by ndoc) as x  group by serie
        union all
        SELECT serie,tdoc,min(numero) as desde,max(numero) as hasta,sum(valor) as valor,SUM(rcom_exon) as exon,
        sum(igv) as igv,sum(impo) as impo from(select
        concat('BC',SUBSTR(f.ndoc,3,2)) as serie,substr(f.ndoc,5) as numero,abs(round(if(f.mone='S',f.valor,f.valor*f.dolar),2)) as valor,
        abs(round(if(f.mone='S',f.rcom_exon,f.rcom_exon*f.dolar),2)) as rcom_exon,abs(round(if(f.mone='S',f.igv,f.igv*f.dolar),2)) as igv,
        abs(round(if(f.mone='S',f.impo,f.impo*f.dolar),2)) as impo,f.tdoc
        FROM fe_rcom f
        inner join fe_ncven g on g.ncre_idan=f.idauto 
        inner join fe_rcom as w on w.idauto=g.ncre_idau
        where f.tdoc='07'  and f.acti='A' and f.idcliente>0 and w.tdoc='03' and f.fech=:df order by f.ndoc) as x group by serie
        union all
        SELECT serie,tdoc,min(numero) as desde,max(numero) as hasta,sum(valor) as valor,SUM(rcom_exon) as exon,
        sum(igv) as igv,sum(impo) as impo  from(select
        concat('BD',SUBSTR(f.ndoc,3,2)) as serie,substr(f.ndoc,5) as numero,abs(round(if(f.mone='S',f.valor,f.valor*f.dolar),2)) as valor,
        abs(round(if(f.mone='S',f.rcom_exon,f.rcom_exon*f.dolar),2)) as rcom_exon,abs(round(if(f.mone='S',f.igv,f.igv*f.dolar),2)) as igv,
        abs(round(if(f.mone='S',f.impo,f.impo*f.dolar),2)) as impo,f.tdoc
        FROM fe_rcom f
        inner join fe_ncven g on g.ncre_idan=f.idauto 
        inner join fe_rcom as w on w.idauto=g.ncre_idau
        where f.tdoc='08'  and f.acti='A' and f.idcliente>0 and w.tdoc='03' and f.fech=:df  order by f.ndoc) as x group by serie";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(':df', $df);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        $resumen = array();
        foreach ($st as $row) {
            $resumen[] = array(
                'serie'    =>   $row['serie'],
                'tdoc'    =>   $row['tdoc'],
                'desde'    =>   $row['desde'],
                'hasta'    =>   $row['hasta'],
                'valor'    =>   $row['valor'],
                'exon'     =>   $row['exon'],
                'igv'      =>   $row['igv'],
                'impo'     =>   $row['impo'],
                'fech'     =>   $df,
                'grati'    =>    0
            );
        }
        $sw = 1;
        $cmensaje = "";
        foreach ($resumen as $row) {
            $cserie = $row['serie'];
            $nimpo = $row['impo'];
            $dfecha = $row['fech'];
            $this->sql = "select resu_tick,resu_impo FROM fe_resboletas WHERE
            resu_fech=:df AND resu_acti='A' AND LEFT(resu_tick,1)<>'' AND LEFT(resu_serie,4)=:cserie
            AND resu_impo=:nimpo ";
            $ncon = new conexion();
            $st = $ncon->conectar()->prepare($this->sql);
            $st->bindParam(':df', $df);
            $st->bindParam(':cserie', $cserie);
            $st->bindParam(':nimpo', $nimpo);
            $st->setFetchMode(PDO::FETCH_ASSOC);
            $st->execute();
            foreach ($st as $fila) {
                if (!empty($fila['resu_tick'])) {
                    $sw = 0;
                    $cmensaje = $fila['resu_tick'];
                    break;
                }
            }
            if ($sw == 0) {
                break;
            }
        }
        if ($sw == 0) {
            $respuesta = [
                "estado" => "2",
                "mensaje" => "Ya existen Boletas ENVIADAS ticket " . $cmensaje,
                "ticket" => ""
            ];
            return  $respuesta;
        } else {
            $emisor = $this->ObtenerEmisor($df);
            $objXML = new GeneradorXML();
            $nombreXML = $emisor['rucempresa'] . '-' . $emisor['correlativo'];
            $rutaXML = 'xml/' . $emisor['rucempresa'] . '/';
            $objXML->CrearXMLResumenDocumentos($emisor, $resumen, $detalle, $rutaXML . $nombreXML);
            $oapi = new apifacturacion();
            $rptae = $oapi->EnviarResumenComprobantes($emisor, $nombreXML, $resumen, $detalle);
            if ($rptae['estado'] == '0') {
                $this->nombrexml = $rptae['nombrexml'];
                $this->xml = $rptae['xml'];
                if (!empty($rptae['ticket'])) {
                    $this->RegistraResumenEnvioBoletas($resumen, $rptae['ticket']);
                    $respuesta = [
                        "estado" => "0",
                        "mensaje" => "Se Envio el Resumen de Boletas y Notas",
                        "ticket" => $rptae['ticket'],
                        "emisor" => $emisor,
                        "detalle" => $detalle,
                        "nombrexml" => $nombreXML
                    ];
                    return $respuesta;
                    //  $respuestaticket=$oapi->ConsultarTicket($emisor,$rptae['ticket'],$this->nombrexml,$detalle);
                    //  return $respuestaticket;
                } else {
                    $respuesta = [
                        "estado" => "2",
                        "mensaje" => $rptae['mensaje'],
                        "ticket" => $rptae['ticket']
                    ];
                    return $respuesta;
                }
            } else {
                $respuesta = [
                    "estado" => "3",
                    "mensaje" => $rptae['mensaje'],
                    "ticket" => ""
                ];
                return $respuesta;
            }
        }
    }
    public function consultarNotacredito($nidauto)
    {
        $this->sql = "select  r.idauto,r.ndoc,r.tdoc,r.fech as dfecha,if(r.mone='S','PEN','USD') as mone,abs(r.valor) as valor,
        '10' as tigv,r.vigv,v.rucfirmad,v.razonfirmad,r.ndo2,v.nruc as rucempresa,v.empresa,v.ubigeo,left(r.deta,2) as motivo,
        r.deta as deta,substr(r.deta,2) as detallenota,
        r.rcom_otro,kar_cost as costoref,
        v.ptop,v.ciudad,v.distrito,c.nruc,if(w.tdoc='01','6','1') as tipodoc,c.razo,concat(TRIM(c.dire),' ',TRIM(c.ciud)) as direccion,c.ndni,
        'PE' as pais,cast(abs(r.igv) as decimal(12,2)) as igv,
        cast(abs(r.impo) as decimal(12,2)) as impo,abs(if(k.cant=0,1,ifnull(k.cant,1))) as cant,ifnull(ABS(k.prec),ABS(r.impo)) as prec,
        LEFT(r.ndoc,4) as serie,SUBSTR(r.ndoc,5) as numero,
        if(k.cant=0,'ZZ',ifnull(a.unid,'ZZ')) as unid,ifnull(a.descri,r.deta) as descri,ifnull(k.idart,0) as coda,w.ndoc as refe,w.fech as fechrefe,
        w.tdoc as tref,s.codigoestab,ifnull(u.unid_codu,'NIU') as unid1,kar_cost as costoref,r.form,
        gene_usol,gene_csol,'PE' as pais, gene_cert,clavecertificado,r.mone as moneda
        from fe_rcom r
        inner join fe_clie c on c.idclie=r.idcliente
        left join fe_kar k on k.idauto=r.idauto
        left join fe_art a on a.idart=k.idart
        inner join fe_ncven f on f.ncre_idan=r.idauto
        inner join fe_rcom as w on w.idauto=f.ncre_idau
        inner join fe_sucu s on s.idalma=r.codt
        left join fe_unidades as u on u.unid_codu=a.unid ,fe_gene as v
        where r.idauto=:nidauto and r.acti='A' and r.tdoc='07'";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam('nidauto', $nidauto);
        $st->execute();
        return $st;
    }
    public function ReiniciacorrelativoResumenes()
    {
        $dfecha = strtotime(date('Y-m-d'));
        $this->sql = "update fe_gene set gene_nres=1,gene_nbaj=1,fech=:dfecha where idgene=1";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam('dfecha', $dfecha);
        $st->execute();
    }
    function consultarcpexenviar()
    {
        $ncon = new conexion();
        $this->sql = "SELECT idauto,ndoc,fech,razo,mone,valor,igv,impo,a.tdoc
        FROM fe_rcom AS a JOIN fe_clie AS b ON (a.idcliente=b.idclie)
        WHERE a.acti<>'I' AND LEFT(rcom_mens,1)<>'0' and left(ndoc,1)<>'E'
        AND impo<>0 AND a.tdoc IN('01','03')
        UNION ALL
        SELECT  a.idauto,a.ndoc,a.fech,b.razo,a.mone,a.valor,a.igv,a.impo,a.tdoc
        FROM fe_rcom AS a JOIN fe_clie AS b ON (a.idcliente=b.idclie)
        INNER JOIN fe_ncven g ON g.ncre_idan=a.idauto 
        INNER JOIN fe_rcom AS w ON w.idauto=g.ncre_idau
        WHERE a.acti<>'I' AND  LEFT(a.rcom_mens,1)<>'0' and left(a.ndoc,1)<>'E'
        AND a.impo<>0 AND a.tdoc IN('07','08') ORDER BY fech,ndoc";
        $st = $ncon->conectar()->prepare($this->sql);
        $st->execute();
        $query = $st->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    function consultarcpexenviarmulti()
    {
        $ncon = new conexion();
        $this->sql = "SELECT idauto,ndoc,fech,razo,mone,valor,igv,impo,a.tdoc
        FROM fe_rcom AS a JOIN fe_clie AS b ON (a.idcliente=b.idclie)
        WHERE a.acti<>'I' AND LEFT(rcom_mens,1)<>'0' and left(ndoc,1)<>'E'
        AND impo<>0 AND a.tdoc IN('01','03') and codt=:codt
        UNION ALL
        SELECT  a.idauto,a.ndoc,a.fech,b.razo,a.mone,a.valor,a.igv,a.impo,a.tdoc
        FROM fe_rcom AS a JOIN fe_clie AS b ON (a.idcliente=b.idclie)
        INNER JOIN fe_ncven g ON g.ncre_idan=a.idauto 
        INNER JOIN fe_rcom AS w ON w.idauto=g.ncre_idau
        WHERE a.acti<>'I' AND  LEFT(a.rcom_mens,1)<>'0' and left(a.ndoc,1)<>'E'
        AND a.impo<>0 AND a.tdoc IN('07','08') and w.codt=:codt ORDER BY fech,ndoc";
        $st = $ncon->conectar()->prepare($this->sql);
        $st->execute([
            'codt' => $_SESSION['idalmacen']
        ]);
        $query = $st->fetchAll(PDO::FETCH_ASSOC);
        return $query;
    }
    function boletasynotaspendientesporenviar()
    {
        $this->sql = "select resu_fech,enviados,resumen,resumen-enviados,enviados-resumen,DATEDIFF(CURDATE(),resu_fech) as dias
        FROM(SELECT resu_fech,CAST(SUM(enviados) AS DECIMAL(12,2)) AS enviados,CAST(SUM(resumen) AS DECIMAL(12,2))AS resumen FROM(
        SELECT resu_fech,CASE tipo WHEN 1 THEN resu_impo ELSE 0 END AS enviados,
        CASE tipo WHEN 2 THEN resu_impo ELSE 0 END AS Resumen,resu_mens,tipo FROM (
        SELECT resu_fech,resu_impo AS resu_impo,resu_mens,1 AS Tipo FROM fe_resboletas f
        WHERE  f.resu_acti='A' AND LEFT(resu_mens,1)='0'
        UNION ALL
        SELECT fech AS resu_fech,IF(mone='S',impo,impo*dolar) AS resu_impo,' ' AS resu_mens,2 AS Tipo FROM fe_rcom f
        WHERE   f.acti='A' AND tdoc='03' AND LEFT(ndoc,1)='B' AND f.idcliente>0
        UNION ALL
        SELECT f.fech AS resu_fech,IF(f.mone='S',ABS(f.impo),ABS(f.impo*f.dolar)) AS resu_impo,' ' AS resu_mens,2 AS Tipo FROM fe_rcom f
        INNER JOIN fe_ncven g ON g.ncre_idan=f.idauto
        INNER JOIN fe_rcom AS w ON w.idauto=g.ncre_idau
        WHERE f.acti='A' AND f.tdoc IN ('07','08') AND LEFT(f.ndoc,1)='F' AND w.tdoc='03' AND f.idcliente>0 ) AS x)
        AS y GROUP BY resu_fech ORDER BY resu_fech) AS zz  WHERE resumen-enviados>=1";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->execute();
        return $st;
    }
    function Muestratickets($df)
    {
        $this->sql = "select resu_feen,resu_fech,resu_tdoc,resu_serie,resu_desd,resu_hast,resu_valo,resu_exon,resu_inaf,resu_igv,
        resu_impo,resu_arch,resu_hash,resu_tick,resu_mens,resu_idre FROM fe_resboletas f,
        fe_gene as v where f.resu_acti='A' and (LEFT(resu_mens,1)<>'0' OR ISNULL(resu_mens)) and resu_fech=:df order by 
        resu_tick,resu_fech,resu_tdoc,resu_serie";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":df", $df);
        $st->execute();
        //$st->execute(["df"=>$df]);
        return $st;
    }
    function ObtenerDetalleboletasynotas($ctdoc, $ndesde, $nhasta, $cserie)
    {
        $this->sql = "select idauto,numero,tdoc,x.fech,impo,v.nruc,ndoc from(
			SELECT idauto,ndoc,cast(mid(ndoc,5) as unsigned) as numero,fech,impo,tdoc FROM fe_rcom f where tdoc=:ctdoc and acti='A' and idcliente>0) as x
            ,fe_gene as v
			where numero between :ndesde and :nhasta and LEFT(ndoc,4)=:cserie";
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":ctdoc", $ctdoc);
        $st->bindParam(":ndesde", $ndesde);
        $st->bindParam(":nhasta", $nhasta);
        $st->bindParam(":cserie", $cserie);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $st->execute();
        return $st;
    }
    public function ActualizaestadoCpe($nidauto, $cmensaje)
    {
        $ncon = new conexion();
        $this->sql = "UPDATE fe_rcom SET rcom_mens=:cmensaje,rcom_fecd=curdate() WHERE idauto=:nidauto";
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":cmensaje", $cmensaje);
        $st->bindParam(":nidauto", $nidauto);
        $st->execute();
        $ncon = null;
    }
    function ActualizaestadoResumenBoletas(string $cticket, string $crpta)
    {
        $this->sql = 'update fe_resboletas set resu_mens=:crpta,resu_feen=curdate() where trim(resu_tick)=:cticket';
        $ncon = new conexion();
        $st = $ncon->conectar()->prepare($this->sql);
        $st->bindParam(":crpta", $crpta);
        $st->bindParam(":cticket", $cticket);
        $st->execute();
    }
    function obtenerxmlycdr($nidauto)
    {
        $this->sql = "SELECT CAST(rcom_xml AS CHAR) AS rcom_xml,CAST(rcom_cdr AS CHAR),
        CONCAT(v.nruc,'-',tdoc,'-',LEFT(ndoc,4),'-',SUBSTR(ndoc,5),'.xml') AS nombrexml FROM fe_rcom AS r, fe_gene AS v where idauto=:nidauto";
        $ncon = new conexion();
        $query = $ncon->conectar()->prepare($this->sql);
        $query->execute([
            "nidauto" => $nidauto
        ]);
        $st = $query->fetch();
        return $st;
    }
    function eliminarticket($cticket)
    {
        try {
            $sql = "update fe_resboletas set resu_acti='I' where resu_tick=:cticket";
            $ncon = new conexion();
            $query = $ncon->conectar()->prepare($sql);
            $query->execute(["cticket" => $cticket]);
            $mensaje = ['rpta' => 1, 'mensaje' => 'Eliminado Correctamente'];
        } catch (Exception $e) {
            $mensaje = ['rpta' => 0, 'mensaje' => ' Error al Eliminar' . $e->getMessage()];
        }
        return  $mensaje;
    }
}
