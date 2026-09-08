<?php

namespace App\Models;

use App\Controllers\SerieController;
use PDO;
use PDOException;
use Core\Clases\conexion;
use Core\Routing\Modelo;

class GuiaTransportista extends Modelo
{
    public int $idauto = 0;
    public string $tdoc = "31";
    public string $cndoc = "";
    public $dfecha = "";
    public string $dfechat = "";
    public int $nidg = 0;
    public string $cptop = "";
    public string $cptoll = "";
    public string $cdctorelacionado = "";
    public string $tdocrel = "";
    public int $ntienda = 0;
    public string $cubigeo1 = " ";
    public string $cubigeo2 = " ";
    public int $nidv1 = 0;
    public int $nidv2 = 0;
    public int $nidr = 0;
    public int $nidd = 0;
    public string $cdetalle = "";
    public string $conductor = "";
    public string $brevete = "";
    public string $placa1 = "";
    public string $constancia1 = "";
    public int $nidtr = 0;
    public string $motivo = "";

    function Grabar($detalle)
    {
        $ls = "select FunIngresaGuiastr(:dfecha,:cptop,:cptoll,:dfechat,:nidus,:cdeta,:cndoc,
        :nidtda,:cubigeo1,:cubigeo2,:nidv1,:nidv2,:nidr,:nidd,:ctdocr,:cdctor,:cconductor,:cplaca1,
        :cconstancia1,:cbrevete,:nidtr,:tdoc,:motivo,'',:nidauto) as id ";
        //$dfech = date("Y-m-d");
        try {
            $correlativo = SerieController::correlativo('1', '31');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                'cndoc' => $this->cndoc,
                'nidtda' => $_SESSION['idalmacen'],
                'cubigeo1' => $this->cubigeo1,
                'cubigeo2' => $this->cubigeo2,
                'nidv1' => $this->nidv1,
                'nidv2' => $this->nidv2,
                'nidr' => $this->nidr,
                'nidd' => $this->nidd,
                'ctdocr' => $this->tdocrel,
                'cdctor' => $this->cdctorelacionado,
                'cconductor' => $this->conductor,
                'cplaca1' => $this->placa1,
                'cconstancia1' => $this->constancia1,
                'cbrevete' => $this->brevete,
                'nidtr' => $this->nidtr,
                'tdoc' => $this->tdoc,
                'motivo' => $this->motivo,
                'nidauto' => 0
            ]);

            if ($st->errorCode() != '0000') {
                $pdo->rollBack();
                // var_dump($st->debugDumpParams());
                $rpta = array('mensaje' => $st->errorInfo(), "estado" => '0');
                return $rpta;
            }
            $st->setFetchMode(PDO::FETCH_ASSOC);
            $valor = $st->fetch();
            // $connection->query("SELECT @parametroSalida")->fetch();
            // $var 
            $id = $valor['id'];
            // $id = $pdo->lastInsertId();
            $sql = "insert into fe_ent(entr_unid,entr_cant,entr_deta,entr_codi,entr_peso,entr_idgtr)values(:cunidad,:ncant,:cdesc,:codigo,:npeso,:nidg)";
            $sw = 1;
            foreach ($detalle as $item) {
                $query = $pdo->prepare($sql);
                $ncant = floatval($item['cantidad']);
                $npeso = floatval($item['peso']);
                $query->execute([
                    "cunidad" => "NIU",
                    "ncant" => $ncant,
                    "nidg" => $id,
                    "cdesc" => $item['descripcion'],
                    "npeso" => $npeso,
                    "codigo" => ""
                ]);
                if ($query->errorCode() != '0000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            // $sql = 'update fe_serie set nume=nume+1 where idserie=:idserie';
            // $query = $pdo->prepare($sql);
            // $query->execute(["idserie" => $idserie]);
            // if ($query->errorCode() !== '00000') {
            //     $pdo->rollBack();
            //     $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
            //     return $rpta;
            // }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al actualizar correlativo', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se genero la Guia Transportista correctamente ", "ndoc" => $this->cndoc, "estado" => '1');
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
    function actualizar($detalle, $arrayEliminados)
    {
        $ls = "update fe_guiastr set guia_fech=:dfecha, guia_fect=:dfechat, guia_ptop=:cptop,guia_ptoll=:cptoll,guia_idus=:nidus,
          guia_idre=:nidr,guia_idde=:nidd, guia_idv1=:nidv1,guia_ubi1=:cubigeo1,guia_ubi2=:cubigeo2,guia_deta=:cdetalle,
          guia_cond=:cconductor,guia_brev=:cbrevete,guia_con1=:cconstancia1 where guia_idgui=:idauto";
        //   :cdeta,:cndoc,:nidtda,:cubigeo1,:cubigeo2,:nidv1,:nidv2,:nidr,:nidd,:ctdocr,:cdctor
        try {
            $ncon = new conexion();
            // $this->cndoc = $nsgte[0]['correlativo'];
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $st = $pdo->prepare($ls);
            $st->execute([
                'dfecha' => $this->dfecha,
                'idauto' => $this->idauto,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' =>  $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                // 'cdeta' => $this->cdetalle,
                // 'cndoc' => $this->cndoc,
                // 'nidtda' => $this->ntienda,
                'cubigeo1' => $this->cubigeo1,
                'cubigeo2' => $this->cubigeo2,
                'nidv1' => $this->nidv1,
                // 'nidv2' => $this->nidv2,
                'nidr' => $this->nidr,
                'nidd' => $this->nidd,
                'cconductor' => $this->conductor,
                'cbrevete' => $this->brevete,
                'cdetalle' => $this->cdetalle,
                'cconstancia1' => $this->constancia1
                // 'ctdocr' => $this->tdocrel,
                // 'cdctor' => $this->cdctorelacionado
            ]);
            if ($st->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $id = $this->idauto;

            $sqlinserta = "insert into fe_ent(entr_unid,entr_cant,entr_deta,entr_codi,entr_peso,entr_idgtr)values(:cunidad,:ncant,:cdesc,:codigo,:npeso,:nidg)";

            $sqlactualiza = "update fe_ent set entr_unid=:cunidad, entr_cant=:ncant,entr_deta=:cdesc,entr_codi=:codigo,entr_peso=:npeso where entr_idgtr=:nidg and entr_iden=:idde";

            $sqldadobaja = "update fe_ent set entr_acti='I' where entr_iden=:idde";
            $sw = 1;
            foreach ($detalle as $item) {
                if ($item['nreg'] == 0) {
                    $query = $pdo->prepare($sqlinserta);
                    $ncant = floatval($item['cantidad']);
                    $npeso = floatval($item['peso']);
                    $query->execute([
                        "cunidad" => "UNID",
                        "ncant" => $ncant,
                        "nidg" => $id,
                        "cdesc" => $item['descripcion'],
                        "npeso" => $npeso,
                        "codigo" => ""
                    ]);
                    if ($query->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }
                } else {
                    $query = $pdo->prepare($sqlactualiza);
                    $identr = $item['nreg'];
                    $ncant = floatval($item['cantidad']);
                    $npeso = floatval($item['peso']);
                    $query->execute([
                        "cunidad" => "UNID",
                        "ncant" => $ncant,
                        "nidg" => $id,
                        "cdesc" => $item['descripcion'],
                        "npeso" => $npeso,
                        "codigo" => "",
                        "idde" => $identr
                    ]);
                    if ($query->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
                return $rpta;
            }
            foreach ($arrayEliminados as $eliminado) {
                $identr = $eliminado;
                $query = $pdo->prepare($sqldadobaja);
                $query->execute([
                    "idde" => $identr
                ]);
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se modificó la Guia Transportista satisfactoriamente ", "ndoc" => $_SESSION['ndoc'], "estado" => '1');
            return $rpta;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
            return $rpta;
        }
    }
    function listar($dfi, $dff, $dplaca)
    {
        try {
            $p = ($dplaca == 0) ? ' ' : ' and g.`guia_idv1`=:placa ';
            $sql = "SELECT guia_idgui as idauto,guia_fech AS fech,guia_fect AS fecht, c.razo AS dest,guia_ptop AS dirrem,
            p.`razo` as remi,guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,guia_mens,
            concat(v.nruc,'-31-',left(guia_ndoc,4),'-',substr(guia_ndoc,5)) as nombrexml
            FROM fe_guiastr g
            INNER JOIN fe_clie c ON g.`guia_idde`=c.`idclie`
            INNER JOIN fe_prov p ON g.guia_idre=p.idprov,fe_gene as v
            WHERE guia_acti='A' and guia_codt=:codt and g.guia_fech BETWEEN :dfi AND :dff" . $p;
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if ($dplaca == 0) {
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff,
                    'codt' => $_SESSION['idalmacen']
                ]);
            } else {
                $query->execute([
                    'dfi' => $dfi,
                    'dff' => $dff,
                    'placa' => $dplaca,
                    'codt' => $_SESSION['idalmacen']
                ]);
            }
            // echo $dplaca;
            // echo $query->debugDumpParams();
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function listarxenviar($dplaca)
    {
        try {
            $p = ($dplaca == 0) ? ' ' : ' and g.`guia_idv1`=:placa ';
            $sql = "SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.razo AS dest,guia_ptop AS dirrem,
            '' AS remi,guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml,'R' AS tipoguia,guia_moti AS motivo
            FROM fe_guias g
            INNER JOIN fe_rcom AS r ON r.idauto=g.guia_idau 
            INNER JOIN fe_clie c ON c.`idclie`= r.idcliente,fe_gene AS v
            WHERE guia_acti='A' AND LEFT(guia_mens,1)<>'0' AND r.acti='A' and guia_moti='V'
            UNION ALL
            SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.razo AS dest,guia_ptop AS dirrem,
            '' AS remi,guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,
            CONCAT(v.nruc,'-09-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml,'R' AS tipoguia,guia_moti AS motivo
            FROM fe_guias g
            INNER JOIN fe_prov c ON c.`idprov`= g.guia_idpr,fe_gene AS v
            WHERE guia_acti='A' AND LEFT(guia_mens,1)<>'0' and guia_moti='C'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            // echo $dplaca;
            // echo $query->debugDumpParams();
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function consultarguia($nidauto)
    {
        try {
            $sql = "SELECT guia_idgui as idauto,guia_fech AS fech,guia_fect AS fecht, c.razo AS remi,guia_ptop AS ptopartida,
            p.`razo` as dest,guia_ptoll AS ptollegada,guia_ndoc AS ndoc,v.nruc as rucempresa,
            concat(v.nruc,'-31-',left(guia_ndoc,4),'-',substr(guia_ndoc,5)) as nombrexml,
            entr_cant as cant,entr_deta as descri,entr_peso as peso,entr_unid as unid,
            empresa,ptop,c.nruc as rucdestinatario,p.nruc as rucremitente,c.razo as destinatario,
            p.razo as remitente,vehi_plac as placa,vehi_marc as marca,vehi_cond as chofer,
            vehi_cons as constancia,vehi_brev as brevete,gene_usol,gene_csol,gene_cert,clavecertificado as
            clavecerti,distrito,ciudad,ubigeo,gene_rmtc,c.idclie,p.`idprov`,vv.vehi_idve,e.entr_iden AS nreg,
            g.guia_ubi1,g.guia_ubi2,vehi_pla2 as placa1,guia_cond,guia_brev,guia_mens,vehi_ndni,guia_deta
            FROM fe_guiastr g
            INNER JOIN fe_clie c ON g.`guia_idde`=c.`idclie`
            INNER JOIN fe_prov p ON g.guia_idre=p.idprov
            INNER JOIN fe_ent AS e ON e.entr_idgtr=g.guia_idgui
            inner join fe_vehiculos as vv on vv.vehi_idve=g.guia_idv1,fe_gene as v
            WHERE guia_acti='A' and g.guia_idgui=:nidg and entr_acti='A'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(['nidg' => $nidauto]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function listarparacanje()
    {
        try {
            $sql = "SELECT guia_idgui as idauto,guia_fech AS fech,guia_fect AS fecht, c.razo AS dest,guia_ptop AS dirrem,
            p.`razo` as remi,guia_ptoll AS dirdes,guia_ndoc AS ndoc,c.idclie,c.ndni,c.nruc,guia_mens,
            concat(v.nruc,'-31-',left(guia_ndoc,4),'-',substr(guia_ndoc,5)) as nombrexml
            FROM fe_guiastr g
            INNER JOIN fe_clie c ON g.`guia_idde`=c.`idclie`
            INNER JOIN fe_prov p ON g.guia_idre=p.idprov,fe_gene as v
            WHERE guia_acti='A' and guia_idau=0 order by fech desc";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function consultarGuiaTrDetalle($idguia)
    {
        try {
            $sql = "SELECT entr_deta, entr_unid, entr_cant FROM fe_ent WHERE entr_idgtr=:idguiatr AND entr_acti='A'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute([
                'idguiatr' => $idguia
            ]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function actualizarEstadoGuiaTr($id)
    {
        $sql = "UPDATE fe_guiastr SET guia_mens='0 El comprobante fue aceptado' WHERE guia_idgui=:id";
        $query = $this->prepare($sql);
        $query->execute([
            "id" => $id
        ]);
        if ($query->errorCode() != '0000') {
            $rpta = array('mensaje' => $query->errorInfo(), "estado" => '0');
        } else {
            $rpta = array('mensaje' => "Se actualizo correctamente", "estado" => '1');
        }
        return $rpta;
    }
    function listarguiatrresumen($num, $tdoc, $tipom)
    {
        $sql = "SELECT a.guia_idgui as idauto,a.guia_ndoc as ndoc,a.guia_fech as fech,'S'  as mone,b.razo,0 as  importe,a.idcliente AS codi,idauto,form,a.idusua AS idusuav,rcom_mens,LEFT(rcom_mens,1) AS estadoenviado FROM
                fe_rcom AS a JOIN fe_clie AS b ON(a.idcliente=b.idclie) WHERE a.ndoc=:num AND tdoc=:tdoc AND tipom=:tipom and a.acti='A'";
        $query = $this->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute([
            'num' => $num,
            'tdoc' => $tdoc,
            'tipom' => $tipom
        ]);
        return $query;
    }
}
