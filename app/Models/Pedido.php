<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Foundation\Application;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Pedido extends Modelo
{
    public function GrabarPedido($cabecera,  $idserie)
    {
        $rpta = [];
        $dfech = date("Y-m-d");
        $ncon = new conexion();
        $pdo = $ncon->conectar();
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sqlrped = "INSERT INTO fe_rped(fech,idclie,ndoc,impo,form,rped_idus,idpcped,fecho,idven,idtienda,tipopedido,rped_mone,detalle,tdoc,forma,plazo,validez,entrega,aten)
            VALUES(:dfech,:nidclie,:cndoc,:nimpo,:cform,:nidus,'WEB',LOCALTIME,:nidven,:nidtda,:ctp,:cmone,:cdetalle,:ctdoc,:txtdetallepago,:txtplazoentrega,:txtvalidezoferta,:txtlugarentrega,'')";
            $execrped = $pdo->prepare($sqlrped);
            $execrped->execute([
                'dfech' => $dfech,
                'nidclie' => $cabecera["idclie"],
                'cndoc' => $cabecera["ndoc"],
                'nimpo' => $cabecera["impo"],
                'cform' => $cabecera["form"],
                'nidus' => $cabecera["nidus"],
                'nidven' => $cabecera["nidven"],
                'nidtda' =>  $_SESSION['almacen'],
                'ctp' => $cabecera["ctp"],
                'cmone' => $cabecera["cmone"],
                'cdetalle' => $cabecera["detalle"],
                'ctdoc' => $cabecera['ctdoc'],
                'txtdetallepago' => $cabecera['txtdetallepago'],
                'txtvalidezoferta' => $cabecera['txtvalidezoferta'],
                'txtplazoentrega' => $cabecera['txtplazoentrega'],
                'txtlugarentrega' => $cabecera['txtlugarentrega']
            ]);

            $id = $pdo->lastInsertId();

            $sqldet = "INSERT INTO fe_ped(idart,cant,prec,idautop,incl) VALUES(:ncoda,:ncant,:nprec,:nidauto,:incl)";
            $carrito = session()->get('carrito', []);
            foreach ($carrito as $item) {
                if ($item['activo'] == 'A') {
                    $execdet = $pdo->prepare($sqldet);
                    $ncant = floatval($item['cantidad']);
                    $nprecio = floatval($item['precio']);
                    $execdet->execute([
                        "ncoda" => $item['coda'],
                        "ncant" => $ncant,
                        "nprec" => $nprecio,
                        "nidauto" => $id,
                        'incl' => $cabecera['optigvp']
                    ]);
                }
            }
            Serie::aumentarcorrelativo($idserie, $pdo);
            $pdo->commit();
            $rpta = ['mensaje' => 'Todo ok', 'estado' => '1'];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = ['mensaje' => $pdo_error->getMessage(), 'estado' => '0'];
        }
        return $rpta;
    }
    function actualizarpedido($cabecera)
    {
        $ncon = new conexion();
        $pdo = $ncon->conectar();
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sqlrped = "update fe_rped set idclie=:nidclie,impo=:nimpo,idven=:nidven,form=:cform,detalle=:cdetalle,
            tdoc=:ctdoc,forma=:txtdetallepago,plazo=:txtplazoentrega,validez=:txtvalidezoferta,entrega=:txtlugarentrega where idautop=:nidautop";
            $execrped = $pdo->prepare($sqlrped);
            $execrped->execute([
                "nidclie" => $cabecera['idclie'],
                "nimpo" => $cabecera["impo"],
                "nidautop" => $cabecera['idautop'],
                'cform' => $cabecera["form"],
                'nidven' => $cabecera["nidven"],
                'cdetalle' => $cabecera["detalle"],
                'ctdoc' => $cabecera['ctdoc'],
                'txtdetallepago' => $cabecera['txtdetallepago'],
                'txtvalidezoferta' => $cabecera['txtvalidezoferta'],
                'txtplazoentrega' => $cabecera['txtplazoentrega'],
                'txtlugarentrega' => $cabecera['txtlugarentrega']
            ]);

            $sqlinserta = "INSERT INTO fe_ped(idart,cant,prec,idautop,incl) VALUES(:ncoda,:ncant,:nprec,:nidauto,:incl)";
            // $sqlactualiza = "call ProActualizaDetallePedidos(:ncoda,:ncant,:nprec,:nidauto,:ctipoa,:incl)";
            $sqlactualiza = "UPDATE fe_ped SET idart=:ncoda,cant=:ncant,prec=:nprec,incl=:incl WHERE idped=:nidauto";
            $sqlbaja = "UPDATE fe_ped SET acti='I' WHERE idped=:nreg";
            $carrito = session()->get('carrito', []);
            foreach ($carrito as $item) {
                if ($item['activo'] == 'A') {
                    if ($item['nreg'] == 0) {
                        $query = $pdo->prepare($sqlinserta);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $query->execute([
                            "ncoda" => $item['coda'],
                            "ncant" => $ncant,
                            "nprec" => $nprecio,
                            "nidauto" => $cabecera['idautop'],
                            'incl' => $cabecera['optigvp']
                        ]);
                    } else {
                        $query = $pdo->prepare($sqlactualiza);
                        $ncant = floatval($item['cantidad']);
                        $nprecio = floatval($item['precio']);
                        $query->execute([
                            "ncoda" => $item['coda'],
                            "ncant" => $ncant,
                            "nprec" => $nprecio,
                            "nidauto" => $item['nreg'],
                            'incl' => $cabecera['optigvp']
                        ]);
                    }
                } else {
                    if ($item['nreg'] > 0) {
                        $query = $pdo->prepare($sqlbaja);
                        $query->execute([
                            "nreg" => $item['nreg']
                        ]);
                    }
                }
            }
            $pdo->commit();
            $ncon->close();
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function actualizarpedidoxposicion($cabecera)
    {
        $ncon = new conexion();
        $pdo = $ncon->conectar();
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sqlrped = "update fe_rped set idclie=:nidclie,impo=:nimpo,idven=:nidven,form=:cform,detalle=:cdetalle,tdoc=:ctdoc where idautop=:nidautop";
            $execrped = $pdo->prepare($sqlrped);
            $execrped->execute([
                "nidclie" => $cabecera['idclie'],
                "nimpo" => $cabecera["impo"],
                "nidautop" => $cabecera['idautop'],
                'cform' => $cabecera["form"],
                'nidven' => $cabecera["nidven"],
                'cdetalle' => $cabecera["detalle"],
                'ctdoc' => $cabecera['ctdoc']
            ]);

            $sqlbaja = "UPDATE fe_ped SET acti='I' WHERE idautop=:idautop";
            $execbaja = $pdo->prepare($sqlbaja);
            $execbaja->execute([
                "idautop" => $cabecera['idautop']
            ]);

            $sqlinserta = "SELECT FunIngresaDPedidos(:ncoda,:ncant,:nprec,:nidauto) AS NID";
            $carrito = session()->get('carrito', []);
            foreach ($carrito as $item) {
                if ($item['activo'] == 'A') {
                    $query = $pdo->prepare($sqlinserta);
                    $ncant = floatval($item['cantidad']);
                    $nprecio = floatval($item['precio']);
                    $query->execute([
                        "ncoda" => $item['coda'],
                        "ncant" => $ncant,
                        "nprec" => $nprecio,
                        "nidauto" => $cabecera['idautop']
                    ]);
                }
            }
            $pdo->commit();
            $ncon->close();
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function listarpedidosresumidos($dfi, $dff, $ctipop, $cmbAlmacen)
    {
        try {
            // $ctipo = ($ctipop == 'W' ? 'web' : '');
            $a = ($cmbAlmacen == '0') ? ' and idtienda<>:cmbAlmacen  ' : ' and idtienda=:cmbAlmacen ';
            // $cwhere = ($ctipop == '' ? '' : ($ctipop == 'W' ? ' and idpcped=:ctipo ' : ' and idpcped<>:ctipo '));
            $t = ($ctipop == '0') ? ' and idpcped<>:ctipop' : ' and idpcped=:ctipop ';
            $sql = "SELECT a.ndoc,a.fech,d.razo,e.nomv,x.nomb AS usuario,fecho,
            CAST(a.impo AS DECIMAL(12,2)) AS impo,a.idclie AS codigo,idautop FROM fe_rped AS a
            INNER JOIN fe_vend AS e ON(e.idven=a.idven)
            LEFT JOIN fe_clie AS d ON(d.idclie=a.idclie)
            INNER JOIN fe_usua AS x ON x.idusua=a.rped_idus 
            WHERE a.fech BETWEEN :dfi AND :dff AND a.acti='A' " . $t . $a . " ORDER BY a.ndoc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'ctipop' => $ctipop,
                'cmbAlmacen' => $cmbAlmacen
            ]);
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function buscarpedidoporid($id)
    {
        $sql = "SELECT `a`.`idart`      AS `idart`, `b`.`descri`     AS `descri`,
                `b`.`unid` AS `unid`,`a`.`cant`       AS `cant`, IFNULL(`m`.`idven`,0) AS `idven`,IFNULL(`m`.`nomv`,'') AS `vendedor`,
                `a`.`prec`       AS `prec`,`b`.`premay`     AS `premay`, `b`.`premen`     AS `premen`,`c`.`fech`       AS `fech`,
                `c`.`idautop`    AS `idautop`,`c`.`impo`       AS `impo`,`c`.`ndoc`,`c`.`aten`       AS `aten`, `c`.`forma`      AS `forma`,`c`.`plazo`      AS `plazo`,`c`.`validez`    AS `validez`,`c`.`entrega`    AS `entrega`,
                `c`.`detalle`    AS `detalle`,IFNULL(`d`.`idclie`,0) AS `idclie`,IFNULL(`d`.`razo`,'') AS `razo`,IFNULL(`d`.`nruc`,'') AS `nruc`,
                ifnull(d.ndni,'') as ndni,IFNULL(`d`.`dire`,'') AS `dire`,
                `c`.`rped_mone`  AS `rped_mone`,IFNULL(`d`.`ciud`,'') AS `ciud`, `d`.`fono`   AS `fono`,`d`.`fax` AS `fax`,`a`.`idped`  AS `nreg`,`c`.`form`       AS `form`,
                IFNULL(ROUND(IF(tmon='S',((b.prec*v.igv)+p.prec)*prod_uti1,((b.prec*v.igv*IF(b.prod_dola>v.dola,prod_dola,v.dola))+p.prec)*prod_uti1),2),0) AS pre1,
                IFNULL(ROUND(IF(tmon='S',((b.prec*v.igv)+p.prec)*prod_uti2,((b.prec*v.igv*IF(b.prod_dola>v.dola,prod_dola,v.dola))+p.prec)*prod_uti2),2),0) AS pre2,
                IFNULL(ROUND(IF(tmon='S',((b.prec*v.igv)+p.prec)*prod_uti3,((b.prec*v.igv*IF(b.prod_dola>v.dola,prod_dola,v.dola))+p.prec)*prod_uti3),2),0) AS pre3,
                IFNULL(ROUND(IF(tmon='S',((b.prec*v.igv)+p.prec)*prod_uti0,((b.prec*v.igv*IF(b.prod_dola>v.dola,prod_dola,v.dola))+p.prec)*prod_uti0),2),0) AS pre0,
                ROUND(IF(tmon='S',(b.prec*v.igv)+p.prec,(b.prec*v.igv*v.dola)+p.prec),2) AS costo,b.uno,b.dos,b.tre,b.cua,b.prod_idco AS idco,tdoc,v.empresa,a.incl,
                v.nruc as rucempresa,v.ptop,b.`tipro`,forma,plazo,validez,entrega
                FROM `fe_ped` `a`
                JOIN `fe_rped` `c` ON `a`.`idautop` = `c`.`idautop`
                JOIN `fe_art` `b`   ON `b`.`idart` = `a`.`idart`
                INNER JOIN fe_fletes p ON p.idflete=b.idflete
                LEFT JOIN `fe_clie` `d`  ON `d`.`idclie` = `c`.`idclie`
                LEFT JOIN `fe_vend` `m`  ON `m`.`idven` = `c`.`idven`, fe_gene v 
                WHERE `a`.`acti` = 'A'  AND `c`.`acti` = 'A'  and a.idautop=:id";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute(['id' => $id]);
        return $query;
    }
    function eliminarpedidoporid($id)
    {
        try {
            $sql = "update fe_rped set acti='I' where idautop=:id";
            $st = $this->prepare($sql);
            $st->execute([
                'id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
            return false;
        }
    }
    function listarpedidosresumidosweb($dfi, $dff)
    {
        try {
            $ls = "SELECT a.ndoc,a.fech,d.razo,e.nomv,x.nomb AS usuario,
            CAST(a.impo AS DECIMAL(12,2)) AS impo,a.idclie AS codigo,idautop FROM fe_rped AS a
            INNER JOIN fe_vend AS e ON(e.idven=a.idven)
            LEFT JOIN fe_clie AS d ON(d.idclie=a.idclie)
            INNER JOIN fe_usua AS x ON x.idusua=a.rped_idus 
            WHERE a.fech BETWEEN :dfi AND :dff AND a.acti='A' and idpcped='web' ORDER BY a.ndoc";
            $query = $this->prepare($ls);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff
            ]);
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
            return false;
        }
    }
    function listarparacanje()
    {
        try {
            $sql = "SELECT c.`idclie`,c.`razo`,c.`ndni`,c.`nruc`,c.`dire`,rp.`idautop`,rp.`ndoc`,rp.`fech`,rp.`impo`,rp.rped_mone as mone,
            rp.`tdoc`,rp.`form`,rp.`idven`,rped_idus,nomb as usuario
            FROM fe_rped rp
            INNER JOIN fe_clie AS c ON rp.`idclie`=c.`idclie`
            inner join fe_usua u on rp.rped_idus=u.idusua
            WHERE rp.`acti`='A' and facturado='N' and idtienda=:codt
            ORDER BY fech DESC";
            $query = $this->prepare($sql);
            $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute([
                'codt' => $_SESSION['idalmacen']
            ]);
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
            return false;
        }
    }
    function listardetalleparacanje($idautop)
    {
        try {
            $sql = "SELECT p.`idart`,trim(a.`descri`) as descri,p.`prec`,p.`cant`,a.unid,a.tipro as tipoproducto,cost as costo
            FROM fe_rped rp
            INNER JOIN fe_ped AS p ON rp.`idautop`=p.`idautop`
            INNER JOIN fe_art a ON p.`idart`=a.`idart`
            WHERE rp.`acti`='A' AND p.`acti`='A' AND rp.idautop=:idautop";
            $query = $this->prepare($sql);
            $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute([
                'idautop' => $idautop
            ]);
            return $query;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
            return false;
        }
    }
    function cambiarEstado($id)
    {
        try {
            $sql = "update fe_rped set facturado='S' where idautop=:id";
            $st = $this->prepare($sql);
            $st->execute([
                'id' => $id
            ]);
            $rpta = array('mensaje' => 'Todo ok', "ndoc" => "", "estado" => '1');
        } catch (PDOException $e) {
            $rpta = array('mensaje' => $e->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
}
