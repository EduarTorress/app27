<?php

use App\View\Components\ModalProveedorComponent;
use App\View\Components\PlanesComponent;
use App\View\Components\ModalRegistroCuentasxPagarComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Documento:</label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm tipodocumentos" id="cmbtdoc">
                                    <?php $tdocselect = (empty($datos) ? ' ' : $datos[0]['tdoc']); ?>
                                    <?php foreach ($listadctos['lista']['items'] as $row) : ?>
                                        <option value="<?php echo $row['tdoc'] ?>" <?php echo (($tdocselect == $row['tdoc']) ? 'selected ' : ''); ?>><?php echo $row['nomb'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="idautocompra" value="<?php echo (empty($idautocompra) ? '0' : $idautocompra) ?>">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Número:&emsp;</label>
                            </div>
                            <div class="col-md-4 ">
                                <div class="input-group">
                                    <input type="text" id="cndoc1" maxlength="4" onkeyup="mayusculas(this); isFormatSerieOcompra()" class="form-control form-control-sm col-2" onkeyup="javascript:this.value=this.value.toUpperCase();" id="txtserie" placeholder="F001" value="<?php echo (empty($serie) ? '' : $serie); ?>">
                                    <input type="text" id="cndoc2" maxlength="8" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm col-4" id="txtnumero" placeholder="00000001" value="<?php echo (empty($num) ? '' : $num); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm ">Fecha Emi.:</label>
                            </div>
                            <div class="col-md-2">
                                <input type="date" style="" class="form-control form-control-sm" onblur="consultarvalordolar();" id="txtfechai" placeholder=""
                                    value="<?php echo (empty($datos) ? date('Y-m-d') : $datos[0]['fech']); ?>">
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Fecha Reg:</label>
                            </div>
                            <div class="col-md-2 ">
                                <input type="date" style="" class="form-control form-control-sm" id="txtfechaf" placeholder="" value="<?php echo (empty($datos) ? date('Y-m-d') : $datos[0]['fecr']) ?>">
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Fecha Vto:</label>
                            </div>
                            <div class="col-md-2 ">
                                <input type="date" style="" class="form-control form-control-sm" id="txtfechavto" placeholder="" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Moneda:&emsp;</label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm" id="cmbmoneda">
                                    <?php $mone = (empty($datos) ? 'S' : $datos[0]['mone']); ?>
                                    <option value="S" <?php echo (($mone == 'S') ? 'selected ' : ''); ?>>Soles</option>
                                    <option value="D" <?php echo (($mone == 'D') ? 'selected ' : ''); ?>>Dólares</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Valor Dolar</label>
                            </div>
                            <div class="col-md-2 ">
                                <input type="text" style="" class="form-control form-control-sm" id="txttipocambio" placeholder="0.00" value="<?php echo (empty($datos) ? ' ' : $datos[0]['dolar']) ?>">
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Tipo Pago:</label>
                            </div>
                            <div class="col-md-2 ">
                                <select class="form-control form-control-sm" id="cmbformapago">
                                    <?php $form = (empty($datos) ? 'E' : $datos[0]['form']); ?>
                                    <option value="E" <?php echo (($form == 'E') ? 'selected ' : ''); ?>>Efectivo</option>
                                    <option value="C" <?php echo (($form == 'C') ? 'selected ' : ''); ?>>Crédito</option>
                                    <option value="D" <?php echo (($form == 'D') ? 'selected ' : ''); ?>>Déposito</option>
                                    <option value="T" <?php echo (($form == 'T') ? 'selected ' : ''); ?>>Tarjeta </option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="" class=" col-form-label col-form-label-sm">Tipo:</label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm" id="tipogasto">
                                    <?php $tipogasto = (empty($datos) ? '1' : $datos[0]['tcom']); ?>
                                    <option value="1" <?php echo (($tipogasto == '1') ? 'selected ' : ''); ?>>1 Mercaderia,Materia Prima,Suministro,Envases y Embalajes</option>
                                    <option value="2" <?php echo (($tipogasto == '2') ? 'selected ' : ''); ?>>2 Activo Fijo</option>
                                    <option value="3" <?php echo (($tipogasto == '3') ? 'selected ' : ''); ?>>3 Otros Gastos No Considerados en 1 y 2</option>
                                    <option value="4" <?php echo (($tipogasto == '4') ? 'selected ' : ''); ?>>4 Gastos de Educación,Recreación, Salud, Culturales Representación,Capacitación,De Viaje,Mantenimiento de Vehiculo Y de Premios</option>
                                    <option value="5" <?php echo (($tipogasto == '5') ? 'selected ' : ''); ?>>5 Otros Gastos No Incluidos en el Numeral 4</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">R.U.C:</label> &ensp;&ensp;&ensp;
                            </div>
                            <div class="col-md-2 ">
                                <input readonly type="text" style="" class="form-control form-control-sm" id="txtrucproveedor" placeholder="Ingrese RUC" value="<?php echo (empty($datos) ? ' ' : $datos[0]['nruc']) ?>" maxlength="11" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                            </div>
                            <div class="col-auto ">
                                <label for="" class="col-form-label col-form-label-sm">Nombre:</label>&emsp;
                            </div>
                            <div class="col-md-6 ">
                                <div class="input-group">
                                    <input type="hidden" id="txtidproveedor" value="<?php echo (empty($datos[0]) ? '0' : $datos[0]['idprov']) ?>">
                                    <input type="hidden" id="txtUbigeoproveedor">
                                    <input type="text" style="" class="form-control form-control-sm" id="txtproveedor" placeholder="Razón Social" value="<?php echo (empty($datos) ? ' ' : $datos[0]['razo']) ?>" readonly>
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto" style="display:none;">
                                <label for="" class="col-form-label col-form-label-sm">Ciudad :</label>&emsp;
                            </div>
                            <div class="col-md-2" style="display:none;">
                                <input type="text" class="form-control form-control-sm" id="txtciudad" placeholder="Ciudad" value="" readonly>
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Dirección:</label>
                            </div>
                            <div class="col-md-6 ">
                                <input type="text" style="" class="form-control form-control-sm" id="txtptopartida" placeholder="Dirección" value="<?php echo (empty($datos) ? ' ' : $datos[0]['dire']) ?>" readonly>
                            </div>
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Valor IGV:</label>&emsp;
                            </div>
                            <div class="col-auto">
                                <select name="cmbvigv" class="form-control form-control-sm" id="cmbvigv">
                                    <option value="0.18">18</option>
                                    <option value="0.10">10</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Base 1:</label>&emsp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onblur="calculartodo();" onfocus="this.select();" class="form-control form-control-sm" id="txtbase1" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[0]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[0]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[0]['nomb'];
                            $pcomp1 = new PlanesComponent('6%', 'cmbvalorcompra1', 'txtdesccompra1', $cplanselect, $cdescriselect);
                            echo $pcomp1->render();
                            ?>
                            <input type="hidden" id="idv1" value="<?php echo (empty($datos) ? ' ' : $datos[0]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Base 2:</label>&emsp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onblur="calculartodo();" onfocus="this.select();" class="form-control form-control-sm" id="txtbase2" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[1]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[1]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[1]['nomb'];
                            $pcomp2 = new PlanesComponent('63', 'cmbvalorcompra2', 'txtdesccompra2', $cplanselect, $cdescriselect);
                            echo $pcomp2->render();
                            ?>
                            <input type="hidden" id="idv2" value="<?php echo (empty($datos) ? ' ' : $datos[1]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Base 3:</label>&emsp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onblur="calculartodo();" onfocus="this.select();" class="form-control form-control-sm" id="txtbase3" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[2]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[2]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[2]['nomb'];
                            $pcomp3 = new PlanesComponent('63', 'cmbvalorcompra3', 'txtdesccompra3', $cplanselect, $cdescriselect);
                            echo $pcomp3->render();
                            ?>
                            <input type="hidden" id="idv3" value="<?php echo (empty($datos) ? ' ' : $datos[2]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Base 4:</label>&emsp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onblur="calculartodo();" onfocus="this.select();" class="form-control form-control-sm" id="txtbase4" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[3]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[3]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[3]['nomb'];
                            $pcomp4 = new PlanesComponent('63', 'cmbvalorcompra4', 'txtdesccompra4', $cplanselect, $cdescriselect);
                            echo $pcomp4->render();
                            ?>
                            <input type="hidden" id="idv4" value="<?php echo (empty($datos) ? ' ' : $datos[3]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Exon :</label>&emsp;&ensp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onkeyup="addtotal();" onfocus="this.select();" class="form-control form-control-sm" id="txtExon" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[4]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[4]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[4]['nomb'];
                            $pcomp5 = new PlanesComponent('65', 'cmbvalorexonerado', 'txtdescexonerado', $cplanselect, $cdescriselect);
                            echo $pcomp5->render();
                            ?>
                            <input type="hidden" id="idv5" value="<?php echo (empty($datos) ? ' ' : $datos[4]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">IGV :</label>&emsp;&ensp;&ensp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onfocus="this.select();" onkeyup="modifyigv()" class="form-control form-control-sm" id="txtigv" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[5]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[5]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[5]['nomb'];
                            $pcomp6 = new PlanesComponent('40', 'cmbvalorigv', 'txtdescigv', $cplanselect, $cdescriselect);
                            echo $pcomp6->render();
                            ?>
                            <input type="hidden" id="idv6" value="<?php echo (empty($datos) ? ' ' : $datos[5]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Otros:</label>&emsp;&ensp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" style="" onkeyup="addtotal();" onfocus="this.select();" class="form-control form-control-sm" id="txtotros" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[6]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[6]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[6]['nomb'];
                            $pcomp7 = new PlanesComponent('40', 'cmbotrostributos', 'txtdescotros', $cplanselect, $cdescriselect);
                            echo $pcomp7->render();
                            ?>
                            <input type="hidden" id="idv7" value="<?php echo (empty($datos) ? ' ' : $datos[6]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Total :</label>&emsp;&ensp;
                            </div>
                            <div class="col-md-2">
                                <input type="number" readonly style="" onfocus="this.select();" class="form-control form-control-sm" id="txttotal" placeholder="0.00" value="<?php echo (empty($datos) ? '0.00' : $datos[7]['impo1']) ?>">
                            </div>
                            <?php
                            $cplanselect = empty($datos) ? '0' : $datos[7]['ncta'];
                            $cdescriselect = empty($datos) ? '' : $datos[7]['nomb'];
                            $pcomp8 = new PlanesComponent('42', 'cmbtotalcompra', 'txtdesctotal', $cplanselect, $cdescriselect);
                            echo $pcomp8->render();
                            ?>
                            <input type="hidden" id="idv8" value="<?php echo (empty($datos) ? ' ' : $datos[7]['idectas']) ?>">
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <label for="" class="col-form-label col-form-label-sm">Referen. :</label>
                            </div>
                            <div class="col-6">
                                <input type="text" onfocus="this.select();" class="form-control form-control-sm" id="txtdetalle" placeholder="Ingresar detalle">
                            </div>
                        </div>
                        <hr>
                        <div class="row text-end">
                            <div class="col-8">
                            </div>
                            <div class="col-4 text-end">
                                <button class="btn btn-success" id="btngrabar" onclick="grabarocompra();">Grabar</button>
                                <button class="btn btn-danger" onclick="limpiarcampos();">Limpiar/Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$mdrcxp = new ModalRegistroCuentasxPagarComponent();
echo $mdrcxp->render();
?>
<?php
$this->endSection("contenido");
?>
<?php
$this->startsection("javascript");
?>
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        text-align: right;
    }
</style>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        idautocompra = $("#idautocompra").val();
        if (idautocompra != '0') {
            $("#btngrabar").text("Modificar")
        } else {
            consultarvalordolar();
        }
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
    }

    function consultarvalordolar() {
        const data = new FormData();
        axios.get('/ocompra/getvaluedolar', {
            "params": {
                "fech": $("#txtfechai").val()
            }
        }).then(function(respuesta) {
            $("#txttipocambio").val(respuesta.data.valordolar);
        }).catch(function(error) {
            console.log(error);
            toastr.error(error, "Mensaje del sistema");
        });
    }

    function limpiarcampos() {
        idautocompra = $("#idautocompra").val("")
        if (idautocompra != "0") {
            window.location.href = '/ocompras/index';
        }
        $("#cndoc1").val("");
        $("#cndoc2").val("");
        $("#txtrucproveedor").val("");
        $("#txtidproveedor").val("0");
        $("#txtptopartida").val("");
        $("#txtUbigeoproveedor").val("");
        $("#txtbase1").val("0.00");
        $("#txtbase2").val("0.00");
        $("#txtbase3").val("0.00");
        $("#txtbase4").val("0.00");
        $("#txtExon").val("0.00");
        $("#txtigv").val("0.00");
        $("#txtotros").val("0.00");
        $("#txttotal").val("0.00");
        $("#txtdetalle").val("");
        $("#txtproveedor").val("")
        $("#modalregistrocuentasxpagar").modal('hide');
    }

    function calculartodo() {
        total = 0;
        valorigv = Number($("#cmbvigv").val());

        txtbase1 = Number($("#txtbase1").val());
        valorigvbase1 = txtbase1 * valorigv
        totalbase1 = valorigvbase1 + txtbase1;

        txtbase2 = Number($("#txtbase2").val());
        valorigvbase2 = txtbase2 * valorigv
        totalbase2 = valorigvbase2 + txtbase2;

        txtbase3 = Number($("#txtbase3").val());
        valorigvbase3 = txtbase3 * valorigv
        totalbase3 = valorigvbase3 + txtbase3;

        txtbase4 = Number($("#txtbase4").val());
        valorigvbase4 = txtbase4 * valorigv
        totalbase4 = valorigvbase4 + txtbase4;

        importetotal = totalbase1 + totalbase2 + totalbase3 + totalbase4;
        igvtotal = valorigvbase1 + valorigvbase2 + valorigvbase3 + valorigvbase4;

        $("#txttotal").val(importetotal.toFixed(2));
        $("#txtigv").val(igvtotal.toFixed(2));
    }

    // Modal Registro Cuentas x Pagar INICIO

    $('#modalregistrocuentasxpagar').on('shown.bs.modal', function() {
        $("#txtnumeroletras").select();
    });

    function crearfilas() {
        let num = document.querySelector("#cndoc2").value
        let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
        cantidadletras = $("#txtnumeroletras").val();
        $("#tblletras tbody").empty();
        for (var i = 0; i < Number(cantidadletras); i++) {
            var fila = '<tr>' +
                '<td><input type="text" class="ndoc" style="font-size:10px;" value="' + cndoc + '" readonly></td>' +
                '<td><input type="text" class="txtdiasvto" style="font-size:10px;" onkeypress="isNumber(event);" onfocus="this.select();" onkeyup="calcularfechaxdias(this)" ></td>' +
                '<td><input type="date" class="txtfechavto" style="font-size:10px;" value="<?php echo date('Y-m-d'); ?>"></td>' +
                '<td><input type="text" class="txtreferenciacxpagar" style="font-size:10px;"></td>' +
                '<td><input type="text" class="txtimporte" style="font-size:10px;" onkeypress="isNumber(event)" onfocus="this.select();"></td>' +
                '</tr>';
            $('#tblletras tbody').append(fila);
        }
    }

    function calcularfechaxdias(t) {
        txtdias = $(t).val();
        txtfecha = $("#txtfechai").val();
        txtfechavto = $(t).parent().next().find("input");
        calcularfechavto(txtfecha, txtdias, txtfechavto);
    }

    function calcularfechavto(txtfecha, txtdias, txtfechavto) {
        axios.get('/calcularfechavto', {
            "params": {
                "txtfecha": txtfecha,
                'txtdias': txtdias
            }
        }).then(function(respuesta) {
            $(txtfechavto).val(respuesta.data);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, "Error del Sistema")
        });
    }
    // Modal Registro Cuentas x Pagar  END

    function addtotal() {
        calculartodo();
        txttotal = Number($("#txttotal").val());
        txtExon = Number($("#txtExon").val());
        txtotros = Number($("#txtotros").val());
        importetotal = txttotal + txtExon + txtotros;
        $("#txttotal").val(importetotal.toFixed(2));
    }

    function modifyigv() {
        $("#cmbvigv").val("0.10");
        total = 0;
        valorigv = Number($("#cmbvigv").val());

        txtbase1 = Number($("#txtbase1").val());
        valorigvbase1 = txtbase1 * valorigv
        totalbase1 = valorigvbase1 + txtbase1;

        txtbase2 = Number($("#txtbase2").val());
        valorigvbase2 = txtbase2 * valorigv
        totalbase2 = valorigvbase2 + txtbase2;

        txtbase3 = Number($("#txtbase3").val());
        valorigvbase3 = txtbase3 * valorigv
        totalbase3 = valorigvbase3 + txtbase3;

        txtbase4 = Number($("#txtbase4").val());
        valorigvbase4 = txtbase4 * valorigv
        totalbase4 = valorigvbase4 + txtbase4;

        importetotal = totalbase1 + totalbase2 + totalbase3 + totalbase4;
        igvtotal = valorigvbase1 + valorigvbase2 + valorigvbase3 + valorigvbase4;

        $("#txttotal").val(importetotal.toFixed(2));
        $("#txtigv").val(igvtotal.toFixed(2));
    }

    function validar() {
        txttotal = $("#txttotal").val();
        txtidproveedor = $("#txtidproveedor").val();
        cndoc1 = $("#cndoc1").val();
        cndoc2 = $("#cndoc2").val();

        if (txttotal == "0.00") {
            toastr.info("Digite sus gastos", 'Mensaje del Sistema');
            return false;
        }
        if (txtidproveedor == "0") {
            toastr.info("Seleccione un proveedor", 'Mensaje del Sistema');
            return false;
        }
        if (cndoc1.length < 4) {
            toastr.info("Ingrese la serie de la compra", 'Mensaje del Sistema');
            return false;
        }
        if (cndoc2.length < 8 || cndoc2 == '00000000') {
            toastr.info("Ingrese el número de la compra", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function grabarocompra() {
        if (!validar()) {
            return;
        }
        idautocompra = $("#idautocompra").val();
        if (idautocompra == '0') {
            cmbformapago = $("#cmbformapago").val();
            if (cmbformapago == 'C') {
                total = $("#txttotal").val();
                total = Number(total).toFixed(2);
                $("#txtimportefinal").val(total);
                $("#modalregistrocuentasxpagar").modal('show');
            } else {
                grabar('');
            }
        } else {
            modificar("");
        }
    }

    function grabar(cmensaje = "") {
        if (!validar()) {
            return;
        }
        const detalle = []
        e = 0;
        totalsuma = 0;
        formapago = $("#cmbformapago").val();
        if (formapago == 'C') {
            $("#tblletras tbody tr").each(function() {
                json = "";
                $(this).find("td input").each(function() {
                    $this = $(this);
                    json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                    valor = $this.val();
                    if ($this.attr("class") == 'txtimporte') {
                        if (Number(valor) == 0 || valor == "0" || valor == " ") {
                            e = 1;
                        }
                    }
                    if ($this.attr("class") == 'txtdiasvto') {
                        if (Number(valor) == 0 || valor == "0" || valor == " ") {
                            e = 1;
                        }
                    }
                    if ($this.attr("class") == 'txtimporte') {
                        totalsuma += Number(valor);
                    }
                });
                obj = JSON.parse('{' + json.substr(1) + '}');
                detalle.push(obj)
            });
            if (e == 1) {
                toastr.error("Complete los datos correctamente", 'Mensaje del sistema');
                return;
            }
            importetotal = $("#total").val();
            if (Number(totalsuma) > Number(importetotal)) {
                toastr.error("El monto sumado no debe ser mayor al total", 'Mensaje del sistema');
                return;
            }
            txtnumeroletras = $("#txtnumeroletras").val();
            if (txtnumeroletras.length == 0 || txtnumeroletras == '' || Number(txtnumeroletras) == 0) {
                toastr.error("Ingrese el número de letras", 'Mensaje del sistema');
                return;
            }
        }
        Swal.fire({
            title: "¿Registrar Compra?",
            text: "Se insertará en la base de datos",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idprov", $("#txtidproveedor").val());
                data.append("cmbtdoc", $("#cmbtdoc").val());
                data.append("cndoc1", $("#cndoc1").val());
                data.append("cndoc2", $("#cndoc2").val());
                data.append("txtfechai", $("#txtfechai").val());
                data.append("txtfechar", $("#txtfechaf").val());
                data.append("txtfechavto", $("#txtfechavto").val());
                data.append("cmbformapago", $("#cmbformapago").val());
                data.append("moneda", $("#cmbmoneda").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("tipogasto", $("#tipogasto").val());
                data.append("cmbtipodocumentocuentasxpagar", $("#cmbtipodocumentocuentasxpagar").val());

                data.append("nt1", $("#txtbase1").val());
                data.append("nt2", $("#txtbase2").val());
                data.append("nt3", $("#txtbase3").val());
                data.append("nt4", $("#txtbase4").val());
                data.append("nt5", $("#txtigv").val());
                data.append("nt6", $("#txtExon").val());
                data.append("nt7", $("#txtotros").val());
                data.append("nt8", $("#txttotal").val());

                data.append("nidcta1", $("#cmbvalorcompra1 option:selected").attr("id"));
                data.append("nidcta2", $("#cmbvalorcompra2 option:selected").attr("id"));
                data.append("nidcta3", $("#cmbvalorcompra3 option:selected").attr("id"));
                data.append("nidcta4", $("#cmbvalorcompra4 option:selected").attr("id"));
                data.append("nidcta5", $("#cmbvalorigv option:selected").attr("id"));
                data.append("nidcta6", $("#cmbvalorexonerado option:selected").attr("id"));
                data.append("nidcta7", $("#cmbotrostributos option:selected").attr("id"));
                data.append("nidcta8", $("#cmbtotalcompra option:selected").attr("id"));

                data.append("ct1", $("#txtdesccompra1").val());
                data.append("ct2", $("#txtdesccompra2").val());
                data.append("ct3", $("#txtdesccompra3").val());
                data.append("ct4", $("#txtdesccompra4").val());
                data.append("ct5", $("#txtdescigv").val());
                data.append("ct6", $("#txtdescexonerado").val());
                data.append("ct7", $("#txtdescotros").val());
                data.append("ct8", $("#txtdesctotal").val());

                data.append("txtreferencia", $("#txtdetalle").val());
                data.append("cuentasxpagar", JSON.stringify(detalle));
                axios.post("/ocompras/registrar", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Compra registrada ",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        limpiarcampos();
                    }).catch(function(error) {
                        toastr.error(error.response.data, "Mensaje del Sistema");
                        console.log(error);
                    });
            }
        });
    }

    function modificar(cmensaje = "") {
        Swal.fire({
            title: "¿Modificar Compra?",
            text: "Se grabará con las nuevas modificaciones en el sistema.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idautocompra", $("#idautocompra").val());
                data.append("idprov", $("#txtidproveedor").val());
                data.append("cmbtdoc", $("#cmbtdoc").val());
                data.append("cndoc1", $("#cndoc1").val());
                data.append("cndoc2", $("#cndoc2").val());
                data.append("txtfechai", $("#txtfechai").val());
                data.append("txtfechar", $("#txtfechaf").val());
                data.append("txtfechavto", $("#txtfechavto").val());
                data.append("cmbformapago", $("#cmbformapago").val());
                data.append("moneda", $("#cmbmoneda").val());
                data.append("txttipocambio", $("#txttipocambio").val());
                data.append("tipogasto", $("#tipogasto").val());

                data.append("nt1", $("#txtbase1").val());
                data.append("nt2", $("#txtbase2").val());
                data.append("nt3", $("#txtbase3").val());
                data.append("nt4", $("#txtbase4").val());
                data.append("nt5", $("#txtigv").val());
                data.append("nt6", $("#txtExon").val());
                data.append("nt7", $("#txtotros").val());
                data.append("nt8", $("#txttotal").val());

                data.append("nidcta1", $("#cmbvalorcompra1 option:selected").attr("id"));
                data.append("nidcta2", $("#cmbvalorcompra2 option:selected").attr("id"));
                data.append("nidcta3", $("#cmbvalorcompra3 option:selected").attr("id"));
                data.append("nidcta4", $("#cmbvalorcompra4 option:selected").attr("id"));
                data.append("nidcta5", $("#cmbvalorigv option:selected").attr("id"));
                data.append("nidcta6", $("#cmbvalorexonerado option:selected").attr("id"));
                data.append("nidcta7", $("#cmbotrostributos option:selected").attr("id"));
                data.append("nidcta8", $("#cmbtotalcompra option:selected").attr("id"));

                data.append("idv1", $("#idv1").val());
                data.append("idv2", $("#idv2").val());
                data.append("idv3", $("#idv3").val());
                data.append("idv4", $("#idv4").val());
                data.append("idv5", $("#idv5").val());
                data.append("idv6", $("#idv6").val());
                data.append("idv7", $("#idv7").val());
                data.append("idv8", $("#idv8").val());

                data.append("ct1", $("#txtdesccompra1").val());
                data.append("ct2", $("#txtdesccompra2").val());
                data.append("ct3", $("#txtdesccompra3").val());
                data.append("ct4", $("#txtdesccompra4").val());
                data.append("ct5", $("#txtdescigv").val());
                data.append("ct6", $("#txtdescexonerado").val());
                data.append("ct7", $("#txtdescotros").val());
                data.append("ct8", $("#txtdesctotal").val());

                data.append("txtreferencia", $("#txtdetalle").val());
                axios.post("/ocompras/modificar", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Compra modificada",
                            text: respuesta.data.message,
                            icon: "success"
                        });
                        limpiarcampos();
                        window.location.href = '/ocompras/index';
                    }).catch(function(error) {
                        toastr.error(error.response.data, "Mensaje del sistema");
                        console.log(error);
                    });
            }
        });
    }
</script>
<script>
    $("#cmbvigv").on("change", function() {
        addtotal();
    });

    $('#modal_proveedor').on('shown.bs.modal', function() {
        $('#txtbuscarprov').focus();
    });

    $('#modal_proveedor').on('hidden.bs.modal', function() {
        $("#txtbase1").select();
    });

    $('#txtbase1').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorcompra1").focus();
            $("#cmbvalorcompra1").click();
        }
    });

    $('#txtbase2').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorcompra2").focus();
            $("#cmbvalorcompra2").click();
        }
    });

    $('#txtbase3').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorcompra3").focus();
            $("#cmbvalorcompra3").click();
        }
    });

    $('#txtbase4').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorcompra4").focus();
            $("#cmbvalorcompra4").click();
        }
    });

    $('#txtExon').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorexonerado").focus();
            $("#cmbvalorexonerado").click();
        }
    });

    $('#txtigv').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbvalorigv").focus();
            $("#cmbvalorigv").click();
        }
    });

    $('#txtotros').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbotrostributos").focus();
            $("#cmbotrostributos").click();
        }
    });

    $('#txttotal').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#cmbtotalcompra").focus();
            $("#cmbtotalcompra").click();
        }
    });

    function entertest(u) {
        var enterPressed = 1;
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    tr = $(u).parent().parent().next();
                    inputcantidad = $(tr).find("input");
                    $(inputcantidad[0]).select();
                    $(inputcantidad[0]).click();
                }
                enterPressed++;
                return;
            }
        };
    }
</script>
<?php
$this->endSection("javascript");
?>