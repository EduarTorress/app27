<?php

namespace App\Models;

use App\Controllers\SerieController;
use PDO;
use PDOException;
use Core\Clases\conexion;

class GuiasDevolucion extends GuiaRemitente
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
    public string $motivo = "D";
    public string $referencia = "";
    public string $tipotransporte = "";

    function grabar($detalle)
    {
        try {
            $correlativo = SerieController::correlativo($_SESSION['nserie'], '09');
            if ($correlativo[0]['estado'] == 0) {
                $rpta = array('mensaje' => 'No se pudo obtener el correlativo', "estado" => '0');
                return $rpta;
            }
            $idserie = $correlativo[0]['idserie'];
            $this->cndoc = $correlativo[0]['correlativo'];
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $ls = "select FunIngresaGuiasXdCompras(:dfecha,:cptop,:cptoll,0,:dfechat,:nidus,:cdeta,:nidtr,:cndoc,:almacen,:nidpr,:cubigeo2) as id";
            $st = $pdo->prepare($ls);
            $st->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'dfechat' => $this->dfechat,
                'nidus' => session()->get('usuario_id'),
                'cdeta' => $this->cdetalle,
                   'nidtr' => $this->nidtr,
                'cndoc' => $this->cndoc,
                'almacen' => $_SESSION['idalmacen'],
                'nidpr' => $this->nidpr,
                'cubigeo2' => $this->cubigeo2
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
                    $ncant = floatval($item['cantidad']);
                    $npeso = floatval($item['peso']);
                    $query->execute([
                        "cunidad" => $item['unidad'],
                        "ncant" => $ncant,
                        "nidg" => $id,
                        "npeso" =>  $npeso,
                        "codigo" => $item['coda'],
                        "entr_codi" => 0,
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
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            $rpta = array('mensaje' => $pdo_error->getMessage(), "ndoc" => "", "estado" => '0');
        }
        return $rpta;
    }
}
