<?php

namespace App\Models;

use Core\Clases\conexion;
use Core\Foundation\Application;
use Core\Http\Request;
use Core\Routing\Modelo;
use Exception;
use PDO;
use PDOException;
use App\Services\CarritoService;

class OCompras extends Modelo
{
    function registrar($datos)
    {
        try {
            $sqlrcompras = "SELECT FunIngresaRCompras(:cmbtdoc,:cmbformapago,:ndoc,:txtfechai,:txtfechar,:txtreferencia,
            :ntotal,:nt5,:nt8,'',:moneda,:txttipocambio,:igvgene,:tipogasto,:idprov,:tipogasto,:nidusua,0,
            :almacengene,0,0,0,0,0,:nt6,:nt8) AS ID";

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execrcompras = $pdo->prepare($sqlrcompras);
            $execrcompras->execute([
                'cmbtdoc' => $datos["cmbtdoc"],
                'cmbformapago' => $datos["cmbformapago"],
                'ndoc' => $datos["cndoc1"] . $datos["cndoc2"],
                'txtfechai' => $datos["txtfechai"],
                'txtfechar' => $datos["txtfechar"],
                'txtreferencia' => $datos["txtreferencia"],
                'ntotal' => $datos["nt1"] + $datos["nt2"] + $datos["nt3"] + $datos["nt4"],
                'nt5' => $datos["nt5"],
                'nt8' => $datos["nt8"],
                'moneda' => $datos["moneda"],
                'txttipocambio' => $datos["txttipocambio"],
                'igvgene' => session()->get("gene_igv"),
                'tipogasto' => $datos["tipogasto"],
                'idprov' => $datos["idprov"],
                'tipogasto' => $datos['tipogasto'],
                'nidusua' => $_SESSION['usuario_id'],
                'almacengene' =>  $_SESSION['idalmacen'],
                'nt6' => $datos["nt6"]
            ]);

            if ($execrcompras->errorCode() != '00000') {
                var_dump($execrcompras->debugDumpParams());
                $pdo->rollBack();
                return false;
            }

            $idautocompras = $execrcompras->fetchColumn();

            $sqlic = "CALL IngresaCuentas(:nt1,:nt2,:nt3,:nt4,:nt5,:nt6,:nt7,:nt8,
            :nidcta1,:nidcta2,:nidcta3,:nidcta4,:nidcta5,:nidcta6,:nidcta7,:nidcta8,
            :ct1,:ct2,:ct3,:ct4,:ct5,:ct6,:ct7,:ct8,:idautocompras)";

            $execic = $pdo->prepare($sqlic);
            $execic->execute([
                'nt1' => $datos["nt1"],
                'nt2' => $datos["nt2"],
                'nt3' => $datos["nt3"],
                'nt4' => $datos["nt4"],
                'nt5' => $datos["nt5"],
                'nt6' => $datos["nt6"],
                'nt7' => $datos["nt7"],
                'nt8' => $datos["nt8"],
                'nidcta1' => $datos["nidcta1"],
                'nidcta2' => $datos["nidcta2"],
                'nidcta3' => $datos["nidcta3"],
                'nidcta4' => $datos["nidcta4"],
                'nidcta5' => $datos["nidcta5"],
                'nidcta6' => $datos["nidcta6"],
                'nidcta7' => $datos["nidcta7"],
                'nidcta8' => $datos["nidcta8"],
                // 'ct1' => trim($datos["ct1"]),
                // 'ct2' => $datos["ct2"],
                // 'ct3' => $datos["ct3"],
                // 'ct4' => $datos["ct4"],
                // 'ct5' => $datos["ct5"],
                // 'ct6' => $datos["ct6"],
                // 'ct7' => $datos["ct7"],
                // 'ct8' => $datos["ct8"],
                'ct1' => 'D',
                'ct2' => 'D',
                'ct3' => 'D',
                'ct4' => 'D',
                'ct5' => 'D',
                'ct6' => 'D',
                'ct7' => 'D',
                'ct8' => 'H',
                'idautocompras' => $idautocompras
            ]);

            if ($execic->errorCode() != '00000') {
                var_dump($execic->debugDumpParams());
                var_dump($execic->errorInfo());
                $pdo->rollBack();
                return false;
            }

            if ($datos['cmbformapago'] == 'C') {
                $sqlidc = "SELECT FUNregistraDeudasCCtas(:nidauto, :nidprov, :cmoneda, :fecha, :impo, :nidusua, :almacen, 'web', :ccta) as NID";
                $execidc = $pdo->prepare($sqlidc);
                $execidc->execute([
                    'nidauto' =>  $idautocompras,
                    'nidprov' => $datos["idprov"],
                    'cmoneda' =>  $datos["moneda"],
                    'fecha' => $datos['txtfechai'],
                    'impo' => $datos["nt8"],
                    'nidusua' => session()->get("usuario_id"),
                    'almacen' =>  $_SESSION['idalmacen'],
                    'ccta' => '42.12.00'
                ]);

                $ididc = $execidc->fetchColumn();

                $sqlidd = "SELECT FUNINGRESADEUDAS(:nidr,:cndoc,:ctipo,:dfecha,:dfevto,:ctipo,:ndolar,:nimpo,:nidus,:cpc,:nidtda,:cnrou,:cdetalle,:csitua) as nid";
                foreach ($datos['cuentasxpagar'] as $e) {
                    $execidd = $pdo->prepare($sqlidd);
                    $execidd->execute([
                        'nidr' =>  $ididc,
                        'cndoc' => $datos["cndoc1"] . $datos["cndoc2"],
                        'ctipo' =>  $datos["cmbtipodocumentocuentasxpagar"],
                        'dfecha' => $datos["txtfechai"],
                        'dfevto' => $e["txtfechavto"],
                        'ctipo' => $datos['cmbtipodocumentocuentasxpagar'],
                        'ndolar' => $datos["txttipocambio"],
                        'nimpo' => $e["txtimporte"],
                        'nidus' => session()->get("usuario_id"),
                        'cpc' => 'web',
                        'nidtda' =>  $_SESSION['idalmacen'],
                        'cnrou' => '',
                        'cdetalle' => $e["txtreferenciacxpagar"],
                        'csitua' => ''
                    ]);
                }
            }

            if ($datos["moneda"] === 'D') {
                $acre = $datos["nt8"] * $datos["txttipocambio"];
            } else {
                $acre = $datos["nt8"];
            }

            $sqlcaja = "CALL ProIngresaDatosLcajaEefectivo(:txtfechar,'',:txtreferencia,:nidcta8,'0',:nt8,
            :moneda,:txttipocambio,:nidus,:idprov,:idautocompras,:cmbformapago,:ndoc,:cmbtdoc)";
            $execcaja = $pdo->prepare($sqlcaja);
            $execcaja->execute([
                'txtfechar' => $datos['txtfechar'],
                'txtreferencia' => $datos["txtreferencia"],
                'nidcta8' => $datos["nidcta8"],
                'nt8' => $acre,
                'moneda' => 'S',
                'txttipocambio' => $datos["txttipocambio"],
                'nidus' =>  $_SESSION['usuario_id'],
                'idprov' => $datos["idprov"],
                'idautocompras' => $idautocompras,
                'cmbformapago' => $datos["cmbformapago"],
                'ndoc' => $datos["cndoc1"] . $datos['cndoc2'],
                'cmbtdoc' => $datos["cmbtdoc"]
            ]);

            $execcaja->closeCursor();

            if ($execcaja->errorCode() != '00000') {
                var_dump($execcaja->debugDumpParams());
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
    function buscarxid($idauto)
    {
        $listado = [];
        $sql = "select a.dolar,a.fech,a.fecr,a.mone,a.idauto,a.vigv,a.valor,a.igv,a.impo,a.ndoc,a.deta,a.tcom,b.impo as impo1,c.nomb,b.nitem,c.ncta,b.ecta_tipo,a.form,
                b.idectas,a.nruc,a.razo,a.dire,a.tdoc,a.idprov
                from vrcompras as a 
                inner join fe_ectasc as b on b.idrcon=a.idauto 
                inner join fe_plan as c on c.idcta=b.idcta
                where a.idauto=:idauto";
        $query = $this->prepare($sql);
        $query->execute([
            'idauto' => $idauto
        ]);
        $listado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $listado;
    }
    function modificar($datos)
    {
        try {
            $sqlrcompras = "CALL ProActualizaRCompras(:cmbtdoc,:cmbformapago,:ndoc,:txtfechai,:txtfechar,:txtreferencia,
            :ntotal,:nt5,:nt8,'',:moneda,:txttipocambio,:igvgene,:tipogasto,:idprov,:tipogasto,:nidusua,0,
            :almacengene,0,0,:nt8,0,0,:idautocompra,:nt6,:nt8)";

            // ActualizaResumenDctoC(.tdoc,Left(.cmbforma.Value,1),.TXTSErie.Value+.TXTNUmero.Value,.txtfecha.Value,.txtfechar.Value,
            // .txtdetalle.Value,nt1+nt2+nt3+nt4,nt5,nt8,'',Left(.cmbmoneda.Value,1),;
            // .txtdolar.Value,ntigv,Left(.cmbtipo.Value,1),.txtcodigo.Value,Left(.cmbtipo.Value,1),goapp.idcajero,0,
            // goapp.tienda,0,0,nidctat,0,0,.nreg,nt6,nt8)

            $ncon = new conexion();
            $pdo = $ncon->conectar();
            $pdo->beginTransaction();
            $execrcompras = $pdo->prepare($sqlrcompras);
            $execrcompras->execute([
                'cmbtdoc' => $datos["cmbtdoc"],
                'cmbformapago' => $datos["cmbformapago"],
                'ndoc' => $datos["cndoc1"] . $datos["cndoc2"],
                'txtfechai' => $datos["txtfechai"],
                'txtfechar' => $datos["txtfechar"],
                'txtreferencia' => $datos["txtreferencia"],
                'ntotal' => $datos["nt1"] + $datos["nt2"] + $datos["nt3"] + $datos["nt4"],
                'nt5' => $datos["nt5"],
                'nt8' => $datos["nt8"],
                'moneda' => $datos["moneda"],
                'txttipocambio' => $datos["txttipocambio"],
                'igvgene' => session()->get("gene_igv"),
                'tipogasto' => $datos["tipogasto"],
                'idprov' => $datos["idprov"],
                'tipogasto' => $datos['tipogasto'],
                'nidusua' => $_SESSION['usuario_id'],
                'almacengene' =>  $_SESSION['idalmacen'],
                'nt6' => $datos["nt6"],
                'idautocompra' => $datos['idautocompra']
            ]);

            if ($execrcompras->errorCode() != '00000') {
                var_dump($execrcompras->debugDumpParams());
                $pdo->rollBack();
                return false;
            }

            $sqlic = "Call ProActualizactasc(:nt1,:nt2,:nt3,:nt4,:nt5,:nt6,:nt7,:nt8,
            :nidcta1,:nidcta2,:nidcta3,:nidcta4,:nidcta5,:nidcta6,:nidcta7,:nidcta8,
            :idv1,:idv2,:idv3,:idv4,:idv5,:idv6,:idv7,:idv8,
            :ct1,:ct2,:ct3,:ct4,:ct5,:ct6,:ct7,:ct8)";

            // Call ProActualizactasc(?nt1,?nt2,?nt3,?nt4,?nt5,?nt6,?nt7,?nt8,
            // ?nidcta1,?nidcta2,?nidcta3,?nidcta4,?nidctai,?nidctae,?nidcta7,?nidctat,
            // ?id1,?id2,?id3,?id4,?id5,?id6,?id7,?id8,
            // ?ct1,?ct2,?ct3,?ct4,?ct5,?ct6,?ct7,?ct8)

            $execic = $pdo->prepare($sqlic);
            $execic->execute([
                'nt1' => $datos["nt1"],
                'nt2' => $datos["nt2"],
                'nt3' => $datos["nt3"],
                'nt4' => $datos["nt4"],
                'nt5' => $datos["nt5"],
                'nt6' => $datos["nt6"],
                'nt7' => $datos["nt7"],
                'nt8' => $datos["nt8"],
                'nidcta1' => $datos["nidcta1"],
                'nidcta2' => $datos["nidcta2"],
                'nidcta3' => $datos["nidcta3"],
                'nidcta4' => $datos["nidcta4"],
                'nidcta5' => $datos["nidcta5"],
                'nidcta6' => $datos["nidcta6"],
                'nidcta7' => $datos["nidcta7"],
                'nidcta8' => $datos["nidcta8"],
                'idv1' => $datos["idv1"],
                'idv2' => $datos["idv2"],
                'idv3' => $datos["idv3"],
                'idv4' => $datos["idv4"],
                'idv5' => $datos["idv5"],
                'idv6' => $datos["idv6"],
                'idv7' => $datos["idv7"],
                'idv8' => $datos["idv8"],
                'ct1' => 'D',
                'ct2' => 'D',
                'ct3' => 'D',
                'ct4' => 'D',
                'ct5' => 'D',
                'ct6' => 'D',
                'ct7' => 'D',
                'ct8' => 'H',
            ]);

            if ($execic->errorCode() != '00000') {
                var_dump($execic->debugDumpParams());
                $pdo->rollBack();
                return false;
            }

            if ($datos["moneda"] === 'D') {
                $acre = $datos["nt8"] * $datos["txttipocambio"];
            } else {
                $acre = $datos["nt8"];
            }

            $sqlcaja = "CALL ProIngresaDatosLcajaEefectivo12(:txtfechar,'',:txtreferencia,:nidcta8,'0',:nt8,
            :moneda,:txttipocambio,:nidus,:idprov,:idautocompra,:cmbformapago,:ndoc,:cmbtdoc,:tienda)";
            $execcaja = $pdo->prepare($sqlcaja);
            $execcaja->execute([
                'txtfechar' => $datos['txtfechar'],
                'txtreferencia' => $datos["txtreferencia"],
                'nidcta8' => $datos["nidcta8"],
                'nt8' => $acre,
                'moneda' => 'S',
                'txttipocambio' => $datos["txttipocambio"],
                'nidus' =>  $_SESSION['usuario_id'],
                'idprov' => $datos["idprov"],
                'idautocompra' => $datos['idautocompra'],
                'cmbformapago' => $datos["cmbformapago"],
                'ndoc' => $datos["cndoc1"] . $datos['cndoc2'],
                'cmbtdoc' => $datos["cmbtdoc"],
                'tienda' => $_SESSION['idalmacen']
            ]);

            $execcaja->closeCursor();

            if ($execcaja->errorCode() != '00000') {
                var_dump($execcaja->debugDumpParams());
                $pdo->rollBack();
                return false;
            }
            $pdo->commit();
            return true;
        } catch (PDOException $pdo_error) {
            $pdo->rollBack();
            print_r($pdo_error->getMessage());
            return false;
        }
    }
}
