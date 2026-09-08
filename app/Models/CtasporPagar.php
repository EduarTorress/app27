<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDOException;
use PDO;

class CtasporPagar extends Modelo
{
    var $txtdocumento;
    var $txtfecha;
    var $txtimporte;
    var $cmbforma;
    var $cmbmoneda;

    function vencimientosporproveedor($idclie, $dfi, $dff)
    {
    }
    function listarcobranzastodo($formapago, $codt, $fecha)
    {
        try {
            $lista = array();
            //   And rcre_form='<<this.cformapago>>'
            //   And rcre_codt=<<This.Tienda>>
            //   a.fech<='<<df>>'
            $f = ($formapago == '0') ? ' and rcre_form<>:formapago  ' : ' and rcre_form=:formapago ';
            $a = ($codt == '0') ? ' and rcre_codt<>:codt  ' : ' and rcre_codt=:codt ';
            $sql = "Select idauto,c.nruc,c.razo As proveedor,c.idclie As codp,a.mone,If(a.mone='S',saldo,0) As tsoles,If(a.mone='D',saldo,0) As tdolar,
                        c.clie_idzo,ifnull(T.ndoc,a.ndoc) As ndoc,
                        ifnull(T.tdoc,'') As tdoc,ifnull(T.fech,a.fech) As fech,b.fech As fecha,v.nomv As vendedor,a.tipo,s.nomb As Tienda From
                        (Select a.Ncontrol,Min(fevto) As fech,Sum(a.Impo-a.acta) As saldo
                        From fe_cred As a
                        INNER Join fe_rcred As xx  On xx.rcre_idrc=a.cred_idrc
                        Where a.fech<=:fecha And a.Acti<>'I' and xx.rcre_Acti<>'I'" . $f . $a .
                "Group By a.Ncontrol Having saldo<>0) As b
                        INNER Join fe_cred As a On a.idcred=b.Ncontrol
                        INNER Join fe_rcred As r On r.rcre_idrc=a.cred_idrc
                        INNER Join fe_clie As c On c.idclie=r.rcre_idcl
                        INNER Join fe_vend As v On v.idven=r.rcre_codv
                        INNER Join fe_sucu As s On s.idalma=r.rcre_codt
                        Left Join (Select idauto,ndoc,tdoc,fech From fe_rcom Where Acti='A' And idcliente>0) As T On T.Idauto=r.rcre_idau
                        Order By proveedor";
            $exec = $this->prepare($sql);
            $exec->execute([
                'fecha' => $fecha,
                'formapago' => $formapago,
                'codt' => $codt,
            ]);
            $lista = $exec->fetchAll(PDO::FETCH_ASSOC);
            $data = ["estado" => true, 'lista' => $lista, 'mensaje' => "Todo ok"];
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
    function exportarreportecreditoscondetalle($idcliente, $fechai, $fechaf)
    {
        try {
            $lista = array();
            $sql = "SELECT xx.idclie,v.importe,v.fevto,DATEDIFF(CURDATE(),v.fevto) AS dias, v.rcre_idrc,rr.rcre_fech AS fech,razo,
                    rr.rcre_idau AS idauto,rr.rcre_form AS form,rcre_codv AS idven,vv.nomv,
                    IFNULL(cc.ndoc,'') AS docd,IFNULL(cc.tdoc,'') AS tdoc,a.ndoc,
                    a.mone,a.banc,a.tipo,a.dola,a.nrou,a.banco,a.idcred,a.fech AS fepd,v.ncontrol,a.estd,v.rcre_idrc,
                    aa.`descri`,kk.`cant`,kk.`prec`,cc.impo,aa.unid
                    FROM (
                    SELECT ncontrol,rcre_idrc,rcre_idcl,MAX(c.fevto ) AS  fevto ,ROUND(SUM((c.impo - c.acta )),2) AS  importe  FROM
                    fe_rcred AS r INNER JOIN fe_cred AS c ON c. cred_idrc =r. rcre_idrc  
                    WHERE r. rcre_Acti ='A' AND c. acti ='A' AND r.rcre_idcl=:idclie
                    GROUP BY  c.ncontrol,r.rcre_idrc,r.rcre_idcl  HAVING (ROUND(SUM((c.impo - c.acta )),2) <> 0)) AS v
                    INNER JOIN fe_clie AS  xx  ON xx.idclie =v.rcre_idcl 
                    INNER JOIN fe_rcred AS rr ON rr.rcre_idrc =v.rcre_idrc
                    INNER JOIN fe_vend AS vv ON vv.idven =rr. rcre_codv  
                    INNER JOIN fe_kar AS kk ON rr.`rcre_idau`=kk.idauto
                    INNER JOIN fe_art AS aa ON kk.idart=aa.idart
                    LEFT JOIN
                    (SELECT tdoc,ndoc,idauto,impo FROM fe_rcom WHERE acti='A' AND idcliente=:idclie) AS cc ON cc.idauto=rr.rcre_idau  
                    INNER JOIN fe_cred AS a ON a.idcred=v.ncontrol WHERE kk.acti='A' and fech between :dfi and :dff ORDER BY fevto,idkar";
            $exec = $this->prepare($sql);
            $exec->execute([
                'idclie' => $idcliente,
                'dfi' => $fechai,
                'dff' => $fechaf
            ]);
            $lista = $exec->fetchAll(PDO::FETCH_ASSOC);
            $data = ["estado" => true, 'lista' => $lista, 'mensaje' => "Todo ok"];
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
    function listarestadocuenta($idcliente, $cmbalmancen, $cmbmoneda)
    {
        try {
            $lista = array();
            $a = ($cmbalmancen == '0') ? ' and rcre_codt<>:cmbalmancen  ' : ' and rcre_codt=:cmbalmancen ';
            $sql = "select b.rcre_idcl,a.fech as fepd,a.fevto as fevd,a.ndoc,b.rcre_impc as impc,b.rcre_inic as inic,a.impo as impd,a.acta as actd,a.dola,
		    a.tipo,a.banc,ifnull(c.ndoc,'00000000000') as docd,a.mone as mond,a.estd,a.idcred as nr,b.rcre_idrc,dolar,
		    b.rcre_codv as codv,b.rcre_idau as idauto,ifnull(c.tdoc,'00') as refe,d.nomv FROM fe_cred as a
		    inner join fe_rcred as b ON(b.rcre_idrc=a.cred_idrc) left join fe_rcom as c ON(c.idauto=b.rcre_idau)
		    inner join fe_vend as d ON(d.idven=b.rcre_codv)
		    WHERE b.rcre_idcl=:idcliente AND a.mone=:cmbmoneda" . $a . "
		    and a.acti<>'I' and rcre_acti<>'I' ORDER BY a.ncontrol,a.idcred,a.fech";
            $exec = $this->prepare($sql);
            $exec->execute([
                'idcliente' => $idcliente,
                'cmbalmancen' => $cmbalmancen,
                'cmbmoneda' => $cmbmoneda
            ]);
            $lista = $exec->fetchAll(PDO::FETCH_ASSOC);
            $data = ["estado" => true, 'lista' => $lista, 'mensaje' => "Todo ok"];
        } catch (PDOException $e) {
            $data = ["estado" => false, 'lista' => $lista, 'mensaje' => "Error al conectar " . $e];
        }
        return $data;
    }
    function registrarpagosproveedores($detalle)
    {
        $data = array();
        $con = new conexion();
        $pdo = $con->conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            // FUNINGRESAPAGOSdeudas
            // FunIngresaDatosLcajaEDeudas
            foreach ($detalle as $d) {
                if (floatval($d['cancelar']) > 0) {
                    $sqlpc = "SELECT FUNINGRESAPAGOSdeudas(:dfecha,:dfevto,:nacta,:cndoc,'P',:mone,:cb1,:ctipo,
                    :nidrc,:nidus,:ncontrol,:cnrou,:cpc,:ndolar) AS id";
                    $exepc = $pdo->prepare($sqlpc);
                    $exepc->execute([
                        'dfecha' => $this->txtfecha,
                        'dfevto' => $d['fechvto'],
                        'nacta' => $d['cancelar'],
                        'cndoc' => $d['ndoc'],
                        'mone' => $d['mon'],
                        'cb1' => $this->txtdocumento,
                        'ctipo' => $d['tipo'],
                        'nidrc' => $d['rdeu_idrd'],
                        'nidus' => $_SESSION['usuario_id'],
                        'ncontrol' => $d['ncontrol'],
                        'cnrou' => '',
                        'cpc' => 'web',
                        'ndolar' => $_SESSION['gene_dola']
                    ]);

                    $id = $exepc->fetchColumn();

                    if ($d['tdoc'] == '01' ||  $d['tdoc'] == '03' || $d['tdoc'] == '07' || $d['tdoc'] == '08') {
                        $plan = $_SESSION['gene_gene_idcre'];
                    } else {
                        $plan = 0;
                    }
                    $sqlic = "SELECT FunIngresaDatosLcajaEDeudas(:dfech,:txtdocumento,:razo,:plan,:pagos,0,:cmbmoneda,:dolar,:cajero,:nidc,:idauto,:forma,:cndoc) AS NID";
                    $exeic = $pdo->prepare($sqlic);
                    $exeic->execute([
                        'dfech' => $this->txtfecha,
                        'txtdocumento' => $this->txtdocumento,
                        'razo' => $d['razo'],
                        'plan' => $plan,
                        'pagos' => $d['cancelar'],
                        'cmbmoneda' => $this->cmbmoneda,
                        'dolar' => $_SESSION['gene_dola'],
                        'cajero' => $_SESSION['usuario_id'],
                        'nidc' => $id,
                        'idauto'=>$d['idauto'],
                        'forma' => $this->cmbforma,
                        'cndoc' => $d['ndoc']
                    ]);
                }
            }
            $pdo->commit();
            $data = ["mensaje" => 'Todos las cancelacione(s) se registraron satisfactoriamente', 'estado' => '1'];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $data = ["mensaje" => 'Hubieron problemas al registrar ' . $pdo_error->getMessage(), 'estado' => '0'];
        }
        return ($data);
    }
}
