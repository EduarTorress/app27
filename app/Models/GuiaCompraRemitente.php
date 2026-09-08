<?php

namespace App\Models;

use App\Controllers\SerieController;
use PDO;
use PDOException;
use Core\Clases\conexion;

class GuiaCompraRemitente extends GuiaRemitente
{
    public int $idauto = 0;
    public string $tdoc = "09";
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
    public int $nidtr = 0;
    public int $nidpr = 0;
    public string $cdetalle = "";
    public string $conductor = "";
    public string $brevete = "";
    public string $placa1 = "";
    public string $constancia1 = "";
    public string $motivo = "V";
    public string $referencia = "";
    public string $tipotransporte = "";

    function Grabarguiacompra($detalle)
    {
        try {
            $correlativo = SerieController::correlativo('1', '09');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $ls = "select FunIngresaGuiasxComprasRemitente(:dfecha,:cptop,:cptoll,0,:dfechat,:nidus,:cdeta,:nidtr,:cndoc,1,:referencia,:dfecha,:nidpr,:cubigeo2,:txtChoferVehiculo,:txtBrevete) as id";
            $st = $pdo->prepare($ls);
            $st->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'referencia' => $this->referencia,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                'cndoc' => $this->cndoc,
                'cubigeo2' => $this->cubigeo2,
                'txtChoferVehiculo' => $this->conductor,
                'txtBrevete' => $this->brevete,
                'nidtr' => $this->nidtr,
                'nidpr' => $this->nidpr
            ]);

            if ($st->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $st->setFetchMode(PDO::FETCH_ASSOC);
            $valor = $st->fetch();
            $id = $valor['id'];
            $sqlguiase = "insert into fe_ent(entr_unid,entr_cant,entr_idar,entr_peso,entr_idgu,entr_idkar,entr_codi)values(:cunidad,:ncant,:codigo,:npeso,:nidg,:nidkar,:entr_codi)";

            $i = 0;
            $sw = 1;
            foreach ($detalle as $item) {
                $i++;
                if ($item['activo'] == 'A') {
                    $query = $pdo->prepare($sqlguiase);
                    $ncant = floatval($item['cantidad']); //28000
                    $npeso = floatval($item['peso']); //1 
                    $query->execute([
                        "cunidad" => "NIU",
                        "ncant" => $npeso,
                        "nidg" => $id,
                        "npeso" => $ncant,
                        "codigo" => $item['coda'],
                        "entr_codi" => $item['scop'],
                        "nidkar" => 0
                    ]);
                    if ($query->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }
                }
            }

            if ($sw == 0) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(),  "ndoc" => "", "estado" => '0');
                return $rpta;
            }

            $lsprov = "UPDATE fe_prov SET ubig=:cubig WHERE idprov=:idprov";
            $stprov = $pdo->prepare($lsprov);
            $stprov->execute([
                'cubig' => $this->dfecha,
                'idprov' => $this->cubigeo2
            ]);

            if ($stprov->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $stprov->errorInfo(), "estado" => '0');
                return $rpta;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => $query->errorInfo(), "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se Genero la Guia ", "ndoc" => $this->cndoc, "estado" => '1');
            return $rpta;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
            return $rpta;
        }
    }
    function actualizarguiacompra($detalle)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $lsql = "UPDATE fe_guias SET guia_fech=:dfecha,guia_ptop=:ptop,guia_ptoll=:ptoll,guia_fect=:dfechat,guia_deta='',guia_idtr=:nidtr,
            guia_ndoc=:cndoc,guia_codt=:nidtda,guia_ubig=:cubigeo,guia_idpr=:idprov,guia_dcto=:creferencia,guia_idus=:nidus,guia_cond=:txtChoferVehiculo,guia_brev=:txtBrevete WHERE guia_idgui=:nidg";

            $st2 = $pdo->prepare($lsql);
            $st2->execute([
                'dfecha' => $this->dfecha,
                'ptop' => $this->cptop,
                'ptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'nidtr' => $this->nidtr,
                'cndoc' => session()->get('ndoc'),
                'nidtda' => 1,
                'cubigeo' => $this->cubigeo2,
                'idprov' => $this->nidpr,
                'creferencia' => $this->referencia,
                'nidus' => session()->get('usuario_id'),
                'txtChoferVehiculo' => $this->conductor,
                'txtBrevete' => $this->brevete,
                'nidg' => session()->get("idautog")
            ]);

            if ($st2->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st2->errorInfo(), "estado" => '0');
                return $rpta;
            }

            $sqldelent = "update fe_ent set entr_acti='I' where entr_idgu=:idautog";
            $st4 = $pdo->prepare($sqldelent);
            $st4->execute([
                'idautog' => session()->get("idautog")
            ]);

            if ($st4->errorCode() != '0000') {
                $pdo->rollBack();
                $rpta = array('mensaje' => $st4->errorInfo(), "estado" => '0');
                return $rpta;
            }
            $sqlguiase = "insert into fe_ent(entr_unid,entr_cant,entr_idar,entr_peso,entr_idgu,entr_idkar,entr_codi)values(:cunidad,:ncant,:codigo,:npeso,:nidg,:nidkar,:entr_codi)";

            $i = 0;
            $sw = 1; //MANEJO DE ERROR
            foreach ($detalle as $item) {
                $i++;
                $query = $pdo->prepare($sqlguiase);
                $ncant = floatval($item['cantidad']);
                $npeso = floatval($item['precio']);
                $query->execute([
                    "cunidad" => "NIU",
                    "ncant" => $ncant,
                    "nidg" => session()->get("idautog"),
                    "npeso" => $npeso,
                    "codigo" => $item['codigo'],
                    "entr_codi" => $item['scop'],
                    "nidkar" => 0
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

            $pdo->commit();
            $ncon->close();
            $rpta = array('mensaje' => "Se modifico la guia ", "ndoc" => $_SESSION['ndoc'], "estado" => '1');
            return $rpta;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
            return $rpta;
        }
    }
    function listar()
    {
        try {
            $sql = "SELECT guia_idgui AS idauto,guia_fech AS fech,guia_fect AS fecht,c.idclie as idclie, c.razo AS dest,guia_ptop AS dirrem,
            guia_ptoll AS dirdes,guia_ndoc AS ndoc,v.nruc,c.ndni,guia_mens,
            CONCAT(v.nruc,'-31-',LEFT(guia_ndoc,4),'-',SUBSTR(guia_ndoc,5)) AS nombrexml
            FROM fe_guias g
            INNER JOIN fe_rcom AS r ON r.idauto=g.`guia_idau`
            INNER JOIN fe_clie c ON c.`idclie` = r.`idcliente`,
            fe_gene AS v
            WHERE guia_acti='A' AND  g.guia_fech AND tdoc='09'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
    function consultarGuiaCompraDetalle($nidauto)
    {
        try {
            $sql = "SELECT guia_ndoc AS ndoc,guia_fech AS fech,guia_fect AS fechat,guia_deta AS detalle,
                   a.descri,a.unid,e.entr_cant AS cant,e.entr_peso as peso,g.guia_ptop as ptopartida,g.guia_ptoll AS ptollegada,
                   k.idart AS coda,e.entr_iden AS idem,k.idkar AS idkar,g.guia_idtr,k.codv,
                   IFNULL(placa,'') AS placa,IFNULL(t.razon,'') AS razont,guia_mens,guia_arch,k.prec,
                   IFNULL(t.ructr,'') AS ructr,IFNULL(t.nombr,'') AS conductor,
                   IFNULL(t.dirtr,'') AS direcciont,IFNULL(t.breve,'') AS brevete,
                   IFNULL(t.cons,'') AS constancia,IFNULL(t.marca,'') AS marca,c.nruc,c.ndni,
                   IFNULL(t.placa1,'') AS placa1,g.guia_ndoc AS dcto,c.idclie,
                   c.razo,guia_idgui AS idgui,r.idauto,'09' AS tdoc,
                   guia_ptop AS ptop,v.ciudad,v.distrito,IFNULL(t.tran_tipo,'01') AS tran_tipo,
                   r.idauto AS idautov,guia_idgui AS idautog,guia_ubi1 AS guia_ubi2
                   FROM  fe_guias AS g
                   INNER JOIN fe_ent AS e ON e.entr_idgu=g.guia_idgui
                   INNER JOIN fe_kar AS k ON k.`idkar`=e.`entr_idkar`
                   INNER JOIN fe_art AS a ON a.idart=k.idart
                   INNER JOIN fe_rcom AS r ON r.idauto=g.guia_idau
                   INNER JOIN fe_clie AS c ON c.idclie=r.idcliente
                   inner JOIN fe_tra AS t ON t.idtra=g.guia_idtr,fe_gene AS v
                   WHERE guia_idgui=:nidg AND r.tdoc='09' AND entr_acti='A' AND k.acti='A'";
            $query = $this->prepare($sql);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            $query->execute(['nidg' => $nidauto]);
            return $query;
        } catch (PDOException $e) {
            $rpta = array("mensaje" => $e->getMessage(), "estado" => '0', "lista" => []);
            return $rpta;
        }
    }
}
