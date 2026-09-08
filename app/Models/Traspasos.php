<?php

namespace App\Models;

use App\Controllers\SerieController;
use Core\Clases\conexion;
use Core\Routing\Modelo;
use PDO;
use PDOException;

class Traspasos extends Modelo
{
    public $ctdoc = "";
    public $cforma = "";
    public $cndoc = "";
    public $dfecha = "";
    public $dfechat = "";
    public $referencia = "";
    public $nv = 0;
    public $nigv = 0;
    public $sucursalingreso = "";
    public $sucursalsalida = "";
    public $n1 = "";
    public $n2 = "";
    public $n3 = "";
    public $nitems = "";
    public $npvta = "";
    public $copt = "";
    public $cptop = "";
    public $cptoll = "";
    public $transportista = "";
    public $ubigeo = 0;
    public $total = 0;

    function listarxFecha($dfi, $dff, $codt)
    {
        try {
            $c = ($codt == '0') ? ' and guia_codt<>:codt  ' : ' and guia_codt=:codt ';
            $sql = "SELECT g.*,r.rcom_reci,s.nomb as destino FROM fe_guias g 
                    inner join fe_rcom r on g.guia_idau=r.idauto
                    inner join fe_sucu s on r.ndo2=s.idalma 
                    WHERE guia_moti='T' AND guia_acti='A' and acti='A' AND guia_fech BETWEEN :dfi AND :dff " . $c . " order by guia_fope";
            $query = $this->prepare($sql);
            $query->execute([
                'dfi' => $dfi,
                'dff' => $dff,
                'codt' => $codt
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function listartraspasosxrecibir($codt)
    {
        try {
            $c = ($codt == '0') ? ' and ndo2<>:codt  ' : ' and ndo2=:codt ';
            $sql = "SELECT r.*,s.nomb
                    FROM fe_rcom r
                    INNER JOIN fe_sucu s ON r.codt=s.idalma
                    WHERE rcom_reci='P'" . $c . " and acti='A' order by fusua ";
            $query = $this->prepare($sql);
            $query->execute([
                'codt' => $codt
            ]);
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            return $rs;
        } catch (PDOException $e) {
            \print_r($e->getMessage());
        }
    }
    function buscarxid($idguia)
    {
        $sql = "SELECT guia_ndoc AS ndoc,guia_fech AS fech,guia_fect AS fechat,
                a.descri,a.`unid` AS unid,k.cant,entr_peso AS peso,g.guia_ptoll,g.guia_ptop AS ptop,a.`unid` AS kar_unid,
                k.idart AS coda,k.prec,k.idkar,g.guia_idtr,IFNULL(placa,'') AS placa,IFNULL(t.razon,'') AS razont,
                IFNULL(t.ructr,'') AS ructr,IFNULL(t.nombr,'') AS conductor,guia_mens,
                IFNULL(t.dirtr,'') AS direcciont,IFNULL(t.breve,'') AS brevete,
                IFNULL(t.cons,'') AS constancia,IFNULL(t.marca,'') AS marca,v.nruc,tran_tipo,
                IFNULL(t.placa1,'') AS placa1,r.ndoc AS dcto,tdoc,r.idcliente,rcom_mens,rcom_reci,k.alma,a.uno,a.dos,a.tre,a.cua,
                v.empresa AS Razo,'S' AS mone,guia_idgui AS idgui,r.idauto,guia_arch,guia_hash,guia_mens,r.ndo2,guia_ubig,r.deta
                FROM fe_guias AS g
                INNER JOIN fe_rcom AS r ON r.idauto=g.guia_idau
                INNER JOIN fe_kar AS k ON k.idauto=r.idauto
                INNER JOIN fe_art AS a ON a.idart=k.idart
                INNER JOIN fe_ent AS en ON k.idkar=en.entr_idkar
	            left join fe_tra as t on t.idtra=g.guia_idtr,fe_gene as v where guia_idgui=:idguia and tipo='V' and k.acti='A' order by idkar;";
        $query = $this->prepare($sql);
        $query->execute([
            'idguia' => $idguia
        ]);
        $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    function grabar()
    {
        // $this->dfecha = $cabecera["fechi"];
        // $this->dfechat = $cabecera["fechf"];

        // IngresaResumenTraspasosNorplast(This.Tdoc, 'E', This.Ndoc, This.Fecha, This.Fecha, This.Detalle, 0, 0, 0, This.Ndo2, 'S', ;
        //   fe_gene.dola, fe_gene.igv, 'T', 0, 'V', goApp.nidusua, 1, goApp.Tienda, 0, 0, 0, 0, 0, 'P')

        // CREATE DEFINER=`syscom`@`%.%.%` FUNCTION `FunIngresaCabeceraTraspasoN`(
        // ctdoc VARCHAR(2),cform CHAR,cndoc VARCHAR(12),dfecha DATE,dfechar DATE,cdetalle VARCHAR(120),
        // nv FLOAT,nigv FLOAT,nt FLOAT,cndo2 VARCHAR(10),cm CHAR,
        // ndolar FLOAT,ni FLOAT,ctg CHAR,ccodp INTEGER,cmvto CHAR,nus INTEGER,opt INTEGER,nidcodt INTEGER,
        // n1 INTEGER,n2 INTEGER,n3 INTEGER,nitem INTEGER,npvta FLOAT,copt CHAR) RETURNS INT

        $sqlrcom = "SELECT FunIngresaCabeceraTraspasoN(:tdoc,:form,:cndoc,:dfecha,:dfechar,:detalle,
        :nv,:nigv,:nt,:cndoc2,:cm,:ndolar,:ni,:ctg,:ccodp,:cmvto,:nus,:opt,:nidcot,
        :n1,:n2,:n3,:nitem,:npvta,:copt) AS ID";

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
            $exercom = $pdo->prepare($sqlrcom);
            $exercom->execute([
                'tdoc' => '09',
                'form' => 'E',
                'cndoc' => $this->cndoc,
                'dfecha' => $this->dfecha,
                'dfechar' => $this->dfecha,
                'detalle' => $this->referencia,
                'nv' => $this->nv,
                'nigv' => $this->nigv,
                'nt' => $this->total,
                'cndoc2' => $this->sucursalingreso,
                'cm' => 'S',
                'ndolar' => $_SESSION['gene_dola'],
                'ni' => $_SESSION['gene_igv'],
                'ctg' => 'T',
                'ccodp' => '0',
                'cmvto' => 'V',
                'nus' => $_SESSION['usuario_id'],
                'opt' => 1,
                'nidcot' => $_SESSION['idalmacen'],
                'n1' => 0,
                'n2' => 0,
                'n3' => 0,
                'nitem' => $this->nitems,
                'npvta' => 0,
                'copt' => 'E'
            ]);

            if ($exercom->errorCode() != '00000') {
                $pdo->rollBack();
                return false;
            }

            $idrcom = $exercom->fetchColumn();

            $sqlguias = "SELECT FunIngresaGuiasT(:dfecha,:cptop,:cptoll,:nidauto,:dfechat,:nidus,
            :cdeta,:nidtr,:cndoc,:nidt,:cubigeo) AS ID";

            // CREATE FUNCTION `FunIngresaGuiasT`(`dfecha` DATETIME, `cptop` VARCHAR(100), `cptoll` VARCHAR(150), `nidauto` INTEGER, `dfechat` DATETIME, 
            // `nidus` INTEGER, `cdeta` VARCHAR(150), `nidtr` INTEGER, `cndoc` VARCHAR(12), `nidt` INTEGER,
            // cubiego VARCHAR(8)) RETURNS INT

            $exeguias = $pdo->prepare($sqlguias);
            $exeguias->execute([
                'dfecha' => $this->dfecha,
                'cptop' => $this->cptop,
                'cptoll' => $this->cptoll,
                'nidauto' => $idrcom,
                'dfechat' => $this->dfechat,
                'nidus' => $_SESSION['usuario_id'],
                'cdeta' => $this->referencia,
                'nidtr' => $this->transportista,
                'cndoc' => $this->cndoc,
                'nidt' => $this->sucursalsalida,
                'cubigeo' => $this->ubigeo
            ]);

            if ($exeguias->errorCode() != '00000') {
                $pdo->rollBack();
                return false;
            }

            $idguias = $exeguias->fetchColumn();

            $sqliki = "SELECT FunIngresaKardex1(:nid,:cc,'V',:npr,:nct,:igv,'V',:ccod,:calma,:nidcosto1,'0') AS NID";
            $carritot = session()->get('carritot', []);
            $sqlasv = "CALL astock(:coda,:nalma,:ccant,'V')";
            $sqlguiase = "insert into fe_ent(entr_unid,entr_cant,entr_idar,entr_peso,entr_idgu,entr_idkar,entr_codi) values (:cunidad,:ncant,:codigo,:npeso,:nidg,:nidkar,:entr_codi)";
            $sw = 1;
            foreach ($carritot as $item) {
                if ($item['activo'] == 'A') {
                    $exeasv = $pdo->prepare($sqlasv);
                    $cant = floatval($item['cantidad']);
                    $exeasv->execute([
                        "coda" => $item['coda'],
                        "nalma" => $this->sucursalsalida,
                        "ccant" => $cant
                    ]);
                    if ($exeasv->errorCode() != '00000') {
                        enviarmensajerror($sqlasv, $exeasv->errorInfo());
                        $sw = 0;
                        break;
                    }

                    $execiki = $pdo->prepare($sqliki);
                    $cant = floatval($item['cantidad']);
                    $prec = floatval($item['precio']);
                    $igv = 'I';
                    $costo = empty($item['costo']) ? '0.00' : $item['costo'];
                    $execiki->execute([
                        "nid" => $idrcom,
                        "cc" => $item['coda'],
                        "npr" => $prec,
                        "nct" => $cant,
                        "ccod" => 0,
                        "calma" => $this->sucursalsalida,
                        "nidcosto1" => $costo,
                        'igv' => $igv
                    ]);
                    if ($execiki->errorCode() != '00000') {
                        enviarmensajerror($execiki, $execiki->errorInfo());
                        $sw = 0;
                        break;
                    }

                    $idkar = $execiki->fetchColumn();

                    $execguiase = $pdo->prepare($sqlguiase);
                    $ncant = floatval($item['cantidad']);
                    $npeso = floatval($item['precio']);
                    $unidad = $item['unidad'];
                    $execguiase->execute([
                        "cunidad" => $unidad,
                        "ncant" => $ncant,
                        "nidg" => $idguias,
                        "npeso" => $npeso,
                        "codigo" => $item['coda'],
                        "entr_codi" => '',
                        "nidkar" => $idkar
                    ]);
                    if ($execguiase->errorCode() != '0000') {
                        $sw = 0;
                        break;
                    }

                    $sqlikc = "SELECT FunIngresaKardex1(:nauto,:coda,'C',:prec,:cant,:igv,'K','0',:alm,'0','0') AS NID";
                    $sqlasc = "CALL astock(:coda,:nalma,:ccant,'C')";
                    $carritot = session()->get('carritot', []);
                    $sw = 1;
                    foreach ($carritot as $item) {
                        if ($item['activo'] == 'A') {
                            $exeikc = $pdo->prepare($sqlikc);
                            $cant = floatval($item['cantidad']);
                            $prec = floatval($item['precio']);
                            $exeikc->execute([
                                "nauto" => $idrcom,
                                "coda" => $item['coda'],
                                "prec" => $prec,
                                "cant" => $cant,
                                "igv" => 'I',
                                "alm" => $this->sucursalingreso
                            ]);
                            if ($exeikc->errorCode() != '00000') {
                                $sw = 0;
                                break;
                            }
                            $exeasc = $pdo->prepare($sqlasc);
                            $cant = floatval($item['cantidad']);
                            $exeasc->execute([
                                "coda" => $item['coda'],
                                "nalma" => $this->sucursalingreso,
                                "ccant" => $cant
                            ]);
                            if ($exeasc->errorCode() != '00000') {
                                $sw = 0;
                                break;
                            }
                        }
                    }
                }
            }

            if ($sw == 0) {
                $pdo->rollBack();
                return false;
            }
            if (!Serie::aumentarcorrelativo($idserie, $pdo)) {
                $pdo->rollBack();
                $rpta = array('mensaje' => 'Error al aumentar correlativo', "ndoc" => "", "estado" => '0');
                return $rpta;
            }
            if ($execguiase->errorCode() == '00000') {
                $pdo->commit();
            }
            $rpta = ['rpta' => 'Todo ok', 'ndoc' => $this->cndoc, 'estado' => true];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            $rpta = ['rpta' => 'Ocurrió un error', 'ndoc' => $this->cndoc, 'estado' => false];
        }
        return $rpta;
    }
    function aceptartraspaso($idauto)
    {
        try {
            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();

            $sqlupdate = "update fe_rcom set rcom_reci='E' where idauto=:idauto";
            $execupate = $pdo->prepare($sqlupdate);
            $execupate->execute([
                'idauto' => $idauto
            ]);

            $sqlikc = "SELECT FunIngresaKardex1(:nid,:cc,'V',:npr,:nct,:igv,'V',:ccod,:calma,:nidcosto1,'0') AS NID";
            $sqlasc = "CALL astock(:coda,:nalma,:ccant,'C')";
            $carritot = session()->get('detalletraspaso', []);

            $sw = 1;
            foreach ($carritot as $item) {
                $exeikc = $pdo->prepare($sqlikc);
                $cant = floatval($item['cant']);
                $prec = floatval($item['peso']);
                $exeikc->execute([
                    "nid" => $idauto,
                    "cc" => $item['idart'],
                    "npr" => $prec,
                    "nct" => $cant,
                    "igv" => 'I',
                    "ccod" => 0,
                    "calma" => $_SESSION['idalmacen'],
                    'nidcosto1' => 0
                    // 'karunid' => $item['unid'],
                    // 'karequi' => $item['cantequi'],
                    // 'tigv' => $afecto,
                    // 'lote' => empty($item['lote']) ? '' : $item['lote'],
                    // 'fechavto' => date('Y-m-d')
                ]);
                if ($exeikc->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
                $exeasc = $pdo->prepare($sqlasc);
                $cant = floatval($item['cant']);
                $exeasc->execute([
                    "coda" => $item['idart'],
                    "nalma" => $_SESSION['idalmacen'],
                    "ccant" => $cant
                ]);
                if ($exeasc->errorCode() != '00000') {
                    $sw = 0;
                    break;
                }
            }
            if ($sw == 0) {
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            $rpta = ['rpta' => 'Todo ok', 'ndoc' => '', 'estado' => '1'];
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            $rpta = ['rpta' => 'Ocurrió un error ', 'ndoc' => '', 'estado' => '0'];
        }
        return $rpta;
    }
    // function actualizar($cabecera)
    // {
    //     //     PROCEDURE `ProActualizaOrdenCompra`(
    //     //     dfecha DATE,nidpr INTEGER,cmone CHAR,
    //     //     cndoc VARCHAR(10),ctigv CHAR,cobse VARCHAR(200),caten VARCHAR(200),cdeta VARCHAR(200),
    //     //     nidus INTEGER,nid INTEGER,cdespacho VARCHAR(60),cforma VARCHAR(60),nv FLOAT,nigv FLOAT,nimpo FLOAT)

    //     $sqlaoc = "CALL PROACTUALIZAORDENCOMPRA(:dfecha,:nidpr,:cmone,:cndoc,:ctigv,:cobse,
    //         :caten,:cdeta,:nidus,:nid,:cdespacho,:cforma,:nv,:nigv,:nimpo)";
    //     try {
    //         $ncon = new conexion();
    //         $pdo = $ncon->conectar();
    //         $pdo->beginTransaction();
    //         $execaoc = $pdo->prepare($sqlaoc);
    //         $execaoc->execute([
    //             'dfecha' => $this->dfecha,
    //             'nidpr' => $cabecera["idprov"],
    //             'cmone' => $cabecera["mon"],
    //             'cndoc' => $_SESSION['cndococ'] . $_SESSION['numoc'],
    //             'ctigv' => 'I',
    //             'cobse' => $cabecera["txtobservacion"],
    //             'caten' => $cabecera["txtatencion"],
    //             'cdeta' => $cabecera["deta"],
    //             'nidus' => $_SESSION['usuario_id'],
    //             'nid' => $cabecera["nidauto"],
    //             'cdespacho' => $cabecera["txtdespacho"],
    //             'cforma' => $cabecera['txtobservacion'],
    //             'nv' => $cabecera["valor"],
    //             'nigv' => $cabecera["nigv"],
    //             'nimpo' => $cabecera["impo"],
    //         ]);

    //         $execaoc->closeCursor();

    //         if ($execaoc->errorCode() != '00000') {
    //             $pdo->rollBack();
    //             return false;
    //         }

    //         $sqlinserta = "CALL ProIngresaDetalleOCompra(:nauto,:coda,:cant,:prec)";
    //         $sqlactualiza = "CALL ProActualizaOCompra(:nauto,:opt,:coda,:cant,:prec)";
    //         $carritococ = session()->get('carritococ', []);

    //         $sw = 1;
    //         foreach ($carritococ as $item) {
    //             if ($item['activo'] == 'A') {
    //                 if ($item['nreg'] == 0) {
    //                     $exec = $pdo->prepare($sqlinserta);
    //                     $cant = floatval($item['cantidad']);
    //                     $prec = floatval($item['precio']);
    //                     $exec->execute([
    //                         "nauto" => $cabecera['nidauto'],
    //                         "coda" => $item['coda'],
    //                         "cant" => $cant,
    //                         "prec" => $prec
    //                     ]);
    //                 } else {
    //                     $exec = $pdo->prepare($sqlactualiza);
    //                     $cant = floatval($item['cantidad']);
    //                     $prec = floatval($item['precio']);
    //                     $exec->execute([
    //                         "nauto" => $cabecera['nidauto'],
    //                         "opt" => 'C',
    //                         "coda" => $item['coda'],
    //                         "cant" => $cant,
    //                         "prec" => $prec
    //                     ]);
    //                 }
    //             } else {
    //                 if ($item['nreg'] > 0) {
    //                     $exec = $pdo->prepare($sqlactualiza);
    //                     $cant = floatval($item['cantidad']);
    //                     $prec = floatval($item['precio']);
    //                     $exec->execute([
    //                         "nauto" => $cabecera['nidauto'],
    //                         "opt" => 'E',
    //                         "coda" => $item['coda'],
    //                         "cant" => $cant,
    //                         "prec" => $prec
    //                     ]);
    //                 }
    //             }
    //         }
    //         if ($sw == 0) {
    //             $pdo->rollBack();
    //             return false;
    //         }
    //         if ($exec->errorCode() == '00000') {
    //             $pdo->commit();
    //             $ncon->close();
    //         }
    //         return true;
    //     } catch (PDOException $pdo_error) {
    //         $pdo->rollBack();
    //         print_r($pdo_error->getMessage());
    //         return false;
    //     }
    // }
}
