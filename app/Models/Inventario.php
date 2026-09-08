<?php

namespace App\Models;

use PDO;
use Core\Routing\Modelo;

class Inventario extends Modelo
{
    public $dfechai = "";
    public $dfechaf = "";
    public $ncoda = 0;
    public $ntienda = 0;

    function listarkardex()
    {
        $sql = "SELECT ifnull(e.ndoc,'')  as nped,d.ndo2,d.fech,d.ndoc,d.tdoc,a.tipo,d.mone as cmoneda,a.cant,d.fusua,ifnull(g.nomb,'') as usua1,d.codt,
                a.prec,d.vigv as igv,d.dolar,f.nomb as usua,d.idcliente as codc,b.razo AS cliente,d.idprov as codp,c.razo AS proveedor,d.deta,a.alma
                FROM fe_kar as a
                inner JOIN fe_rcom as d on (d.idauto=a.idauto)
                left join fe_prov as c ON(d.idprov=c.idprov)
                left JOIN fe_clie as b ON(d.idcliente=b.idclie)
                LEFT JOIN fe_rped as e ON(e.idautop=d.idautop)
                inner join fe_usua as f ON(f.idusua=d.idusua)
                left join fe_usua as g   ON (g.idusua=d.idusua1)
                WHERE a.idart=:nidart and a.alma=:ntienda and d.acti<>'I' and d.fech<=:dff
                and a.acti<>'I' ORDER BY d.fech,d.tipom,a.idkar";
        $query = $this->prepare($sql);
        $query->execute([
            'nidart' => $this->ncoda,
            'ntienda' => $this->ntienda,
            'dff' => $this->dfechaf
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarkardexgeneral()
    {
        $sql = "SELECT q.Coda,b.Descri,b.Unid,si,compras,ventas,stock,0 AS cmd FROM (
                SELECT xx.Coda,SUM(si) AS si,SUM(compras) AS compras,SUM(ventas) AS ventas,SUM(si)+SUM(compras)-SUM(ventas) AS stock FROM(
                SELECT idart AS Coda,CAST(000000.00 AS DECIMAL(12,2)) AS si,cant AS compras,CAST(000000.00 AS DECIMAL(12,2)) AS ventas
                FROM fe_kar AS a
                INNER JOIN fe_rcom AS b ON b.Idauto=a.Idauto
                WHERE a.Tipo='C' AND a.Acti='A' AND b.Acti='A' AND b.fech BETWEEN :dfi AND :dff AND b.tcom<>'T' and b.codt=:ntienda
                UNION ALL
                SELECT idart AS Coda,CAST(000000.00 AS DECIMAL(12,2)) AS si,CAST(0000000.00 AS DECIMAL(12,2)) AS compras,cant AS ventas
                FROM fe_kar AS c
                INNER JOIN fe_rcom AS D ON D.Idauto=c.Idauto
                WHERE c.Tipo='V' AND c.Acti='A' AND D.Acti='A' AND D.fech BETWEEN :dfi AND :dff AND D.tcom<>'T' and D.codt=:ntienda
                UNION ALL
                SELECT idart AS Coda,IF(Tipo='C',cant,-cant) AS si,CAST(0000000.00 AS DECIMAL(12,2)) AS compras,CAST(000000.00 AS DECIMAL(12,2)) AS ventas
                FROM fe_kar AS z
                INNER JOIN fe_rcom AS yy ON yy.Idauto=z.Idauto
                WHERE z.Acti='A' AND yy.Acti='A' AND yy.fech<:dff and yy.alma=:ntienda AND yy.tcom<>'T')
                AS xx GROUP BY xx.coda) AS q
                INNER JOIN fe_art AS b ON b.idart=q.coda WHERE prod_acti='A' AND (si<>0 OR compras<>0 OR ventas<>0) ORDER BY b.Descri";
        $query = $this->prepare($sql);
        $query->execute([
            'ntienda' => $this->ntienda,
            'dfi' => $this->dfechai,
            'dff' => $this->dfechaf
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarexistenciaalmacen($cmbAlmacen)
    {
        // $a = ($cmbAlmacen == '0') ? ' and codt<>:cmbAlmacen  ' : ' and codt=:cmbAlmacen ';
        // $sql = "SELECT a.idart,c.descri,c.unid,cant,a.prec AS precio,
        // tipo,d.dolar AS dola,d.idauto,d.mone
        // FROM fe_kar AS a
        // INNER JOIN fe_art AS c ON(c.idart=a.idart)
        // INNER JOIN fe_rcom AS d ON(d.idauto=a.idauto)
        // WHERE fech<=:fech AND a.acti<>'I' AND d.acti<>'I' AND d.tcom<>'T' AND tdoc NOT IN('GI','20') ". $a." ORDER BY a.idart,fech,a.tipo,d.tdoc,d.ndoc";
        // $query = $this->prepare($sql);
        // $query->execute([
        //     'fech' => $this->dfechaf,
        //     'cmbAlmacen' => $cmbAlmacen
        // ]);
        // $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        // return $listado;
        $sql = "SELECT a.idart AS Nreg, a.idart,b.Descri AS descri, b.unid, a.uno, a.Dos, a.tres, a.cuatro, (a.uno + a.Dos + a.tres + a.cuatro) AS Total,prec,
                ((a.uno + a.Dos + a.tres + a.cuatro)) AS subtotal,b.prod_cod1,CAST(alma AS DECIMAL(10,2)) AS alma,dcat as categoria
                FROM (SELECT idart, SUM(CASE k.alma WHEN 1 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS uno,
                SUM(CASE k.alma WHEN 2 THEN IF(Tipo = 'C', cant, - cant) ELSE 0 END) AS Dos,
                SUM(CASE k.alma WHEN 3 THEN IF(Tipo = 'C', cant, - cant) ELSE 0 END) AS tres,
                SUM(CASE k.alma WHEN 4 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS cuatro,
                SUM(CASE k.alma WHEN 5 THEN IF(Tipo = 'C', cant, - cant) ELSE 0 END) AS cinco,
                SUM(CASE k.alma WHEN 6 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS seis";
        switch ($cmbAlmacen) {
            case "1":
                $sql .= ",SUM(CASE k.alma WHEN 1 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS alma ";
                break;
            case "2":
                $sql .= ",SUM(CASE k.alma WHEN 2 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS alma ";
                break;
            case "3":
                $sql .= ",SUM(CASE k.alma WHEN 3 THEN IF(Tipo = 'C', cant , - cant) ELSE 0 END) AS alma ";
                break;
            case "4":
                $sql .= ",SUM(CASE k.alma  WHEN 4  THEN IF(Tipo = 'C', cant, - cant) ELSE 0 END) AS alma ";
                break;
        }
        $sql .= "FROM
                fe_kar AS k INNER JOIN fe_rcom AS r ON r.Idauto = k.Idauto
                WHERE r.fech <= :fechf AND r.Acti <> 'I' AND k.Acti <> 'I' GROUP BY k.idart ) AS a
                INNER JOIN fe_art AS b ON b.idart = a.idart
                inner join fe_cat as c on b.idcat=c.idcat
                WHERE b.prod_acti <> 'I' order by alma desc";
        $query = $this->prepare($sql);
        $query->execute([
            'fechf' => $this->dfechaf
        ]);
        // var_dump($query->debugDumpParams());
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listarajustes($fechi, $fechf)
    {
        $sql = "SELECT r.*,u.nomb 
        FROM fe_rcom r
        inner join fe_usua u on r.idusua=u.idusua
        WHERE tdoc='AJ' AND acti='A' 
        AND fech between :fechi and :fechf and codt=:codt order by ndoc, fech";
        $query = $this->prepare($sql);
        $query->execute([
            'fechi' => $fechi,
            'fechf' => $fechf,
            'codt' => $_SESSION['idalmacen']
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function verdetalleajuste($idauto)
    {
        $sql = "SELECT a.descri,k.cant FROM fe_kar k INNER JOIN fe_art a ON k.idart=a.idart WHERE idauto=:idauto";
        $query = $this->prepare($sql);
        $query->execute([
            'idauto' => $idauto
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function calcularstock()
    {
        $sql = "CALL calcularstock()";
        $exec = $this->prepare($sql);
        $exec->execute();
    }
    function listarproductosmenosrotados($codt)
    {
        $sql = "SELECT
                t.idart,
                t.descri,
                t.UltimoMovimiento,
                DATEDIFF(t.UltimoMovimiento, NOW()) AS DiasSinMovimiento
            FROM (
                SELECT
                    k.idart,
                    a.`descri`,
                    MAX(c.fech) AS UltimoMovimiento
                FROM fe_kar k
                INNER JOIN fe_rcom c ON c.idauto = k.idauto
                INNER JOIN fe_art a ON k.idart=a.`idart`
                where codt=:codt and k.acti='A' and c.acti='A'
                GROUP BY k.idart
            ) t
            ORDER BY diassinmovimiento;";
        $query = $this->prepare($sql);
        $query->execute([
            'codt' => $codt
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function listacomparativohistorico($fech, $days)
    {
        $sql = "SELECT c.codt, d.idart, a.`descri`, SUM(d.cant) AS consumo,uno,dos,tre,cua,cin,sei
                FROM fe_kar d
                INNER JOIN fe_rcom c ON c.idauto = d.idauto
                INNER JOIN fe_art a ON d.`idart`=a.`idart`
                WHERE c.tcom = 'K' AND idcliente>0 AND impo>0 AND tdoc IN ('03','01','20') 
                AND c.acti='A' and d.acti='A' AND a.`prod_acti`='A'
                AND c.fech >= DATE_SUB(:fech, INTERVAL " . $days . " DAY)
                GROUP BY c.codt, d.idart";
        $query = $this->prepare($sql);
        $query->execute([
            'fech' => $fech
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
}
