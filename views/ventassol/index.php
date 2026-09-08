<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\IGVComponent;
use App\View\Components\ModalConfirmarLoginComponent;
use App\View\Components\PlanesComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$clie = new ModalClienteComponent();
echo $clie->render();
$prod = new ModalProductoComponent();
echo $prod->render();
$login = new ModalConfirmarLoginComponent();
echo $login->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                        <input type="hidden" id="txtidcliente" value="">
                        <input type="hidden" id="txtruccliente" value="">
                        <input type="hidden" id="txtdireccion" value="">
                        <input type="hidden" id="txtdnicliente" value="">
                        <input type="hidden" id="txtidauto" value="0">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                        <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $dctos = new DocumentoComponent('F');
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Número: </label>
                        <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" maxlength="4" id="cndoc1" value="<?php echo isset($serie) ?  $serie : '' ?>" style="width: 20%;" placeholder="F001">
                        <input type="text" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="<?php echo isset($num) ?  $num : '' ?>" style="width: 30%;" placeholder="00001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm">Guía:</label>
                        <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-00001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $empresa = new \App\View\Components\EmpresaComponent($_SESSION['idalmacen']);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $tpmoneda = new TipoMonedaComponent('S');
                    echo $tpmoneda->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $formapago = new FormadepagoComponent('');
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $dfechavto = new FechavtoComponent();
                    echo $dfechavto->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $vendedor = new VendedorComponent(0);
                    echo $vendedor->render();
                    ?>
                    <div class="form-group row" style="display: none">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Vcto. :</label>
                        <input type="date" class="form-control form-control-sm" value="" style="width:140px;" id="txtfechav" name="txtfechav">
                    </div>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="<?php echo (isset($datosclientev['txtreferencia']) ? $datosclientev['txtreferencia'] : '') ?>">
                </div>
            </div>
            <br>
            <div class="row">
                <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                <div class="col-sm-3" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                    <?php
                    $optigv = isset($datosclientev['optigv']) ? $datosclientev['optigv'] : '';
                    $igv = new IGVComponent($optigv);
                    echo $igv->render();
                    ?>
                </div>
            </div>
            <div class="card card-success card-outline" style="width:auto;">
                <br>
                <div class="row">
                    <div class="col-auto align-items-start">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                            </div>
                            <input type="text" onkeyup="calculartotalxsubtotal();" onclick="this.select();" class="form-control text-right text-sm" id="subtotal" readonly aria-label="Small" value="0.00" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </div>
                    <?php
                    $p1 = new PlanesComponent('70', 'cmbvalorvta1', 'txtdescvta1', "70.00.00", 'Ventas');
                    echo $p1->render();
                    ?>
                </div>
                <div class="row">
                    <div class="col-auto align-items-start">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-sm" id=""><strong>IGV&emsp;&emsp;&nbsp;&nbsp;</strong></span>
                            </div>
                            <input type="text" onkeyup="" class="form-control text-right text-sm" readonly id="igv" aria-label="Small" value="0.00" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </div>
                    <?php
                    $p2 = new PlanesComponent('42', 'cmbvalorvta2', 'txtdescvta2', "40.11.10", 'IGV EN CUENTA PROPIA');
                    echo $p2->render();
                    ?>
                </div>
                <div class="row">
                    <div class="col-auto align-items-start">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-sm" id=""><strong>TOTAL&emsp;</strong></span>
                            </div>
                            <input type="text" onkeyup="calculartotalxtotal();" onclick="this.select();" class="form-control text-right text-sm" id="total" aria-label="Small" value="0.00">
                        </div>
                    </div>
                    <?php
                    $p3 = new PlanesComponent('12', 'cmbvalorvta3', 'txtdescvta3', "12.13.00", 'EN COBRANZA');
                    echo $p3->render();
                    ?>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 text-end">
                        <div class="input-group">
                            <button class="btn btn-danger btn-sm" type="button" role="button" onclick="limpiardatos()">Limpiar</button>&nbsp;&nbsp;
                            <button class="btn btn-success btn-sm" type="button" role="button" onclick="grabarVenta();">Registrar</button>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<style>
    #cliente {
        color: black
    }

    #txtbuscar {
        background-color: white;
        color: black;
    }

    #tabla_clientes {
        color: black;
    }
</style>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        idcliente = 0;
        titulo("<?php echo $titulo ?>");
        $(".tipodocumentos option[value='GI']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='20']").remove();
        <?php if ($_SESSION['moneda'] == 'SI') : ?>
            $("#cmbmoneda").attr('disabled', true);
        <?php endif; ?>
        <?php if ($_SESSION['opigv'] == 'N') : ?>
            $(".igv").attr('disabled', true);
        <?php endif; ?>
        facturardolares = "<?php echo (!empty($_SESSION['config']['facturardolares']) ? $_SESSION['config']['facturardolares'] : 'N'); ?>";
        if (facturardolares == 'N') {
            $(".cmbmoneda option[value='D']").remove();
        }
        $("#cmbvalorvta2 option:selected").text("40.11.10");
    }

    function calculartotalxtotal() {
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        total_col = $("#total").val();
        let impo = (Number(total_col)).toFixed(2);
        let valor = (impo / valorigv).toFixed(2);
        let nigv = (impo - valor).toFixed(2);
        $("#igv").val(nigv);
        $("#subtotal").val(valor);
    }

    $("#modal_clientes").on("hidden.bs.modal", function() {
        grabarCabecera();
    });

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    function verutilidad() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function grabarCabecera() {}

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
    });

    function mostrardatoscliente() {
        txtruccliente = $("#txtruccliente").val();
        txtdireccion = $("#txtdireccion").val();
        txtdnicliente = $("#txtdnicliente").val();
        Swal.fire("RUC: " + txtruccliente + ". <br> DNI: " + txtdnicliente + ". <br>DIRECCIÓN: " + txtdireccion + ".");
    }

    function validarVenta() {
        idcliente = document.querySelector('#txtidcliente').value;
        total = document.querySelector('#total').value;
        ctdoc = $('#cmbdcto option:selected').val();
        ruc = document.querySelector('#txtruccliente').value;
        if (idcliente == 0) {
            toastr.info("Seleccione un Cliente", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc.trim() == '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc == 0) {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function grabarVenta() {
        if (!validarVenta()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidauto').value == '0') {
            cmensaje = '¿Registrar Venta?';
            grabar(cmensaje);
        }
    }

    function limpiardatos() {
        $("#cmbmoneda").attr('disabled', false);
        <?php $_SESSION['moneda'] = 'NO'; ?>
        <?php $_SESSION['opigv'] = 'I'; ?>
        $("#txtcliente").val("");
        $("#txtdias").val("");
        $("#titulo").val("Registrar venta");
        $("#txtidcliente").val("0");
        $("#txtruccliente").val("0");
        $("#ndo2").val("");
        $("#cmbforma").val("E");
        $("#cmbmoneda").val("S");
        $("#optigv").val("I");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        $("#txtreferencia").val("");
        $("#cndoc1").val("");
        $("#cndoc2").val("");
    }

    function grabar(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "Se guardará en el sistema como un nuevo documento. ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idcliev", $("#txtidcliente").val());
                data.append("cndoc1", $("#cndoc1").val());
                data.append("cndoc2", $("#cndoc2").val());
                data.append("txtcliente", $("#txtcliente").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("ndo2v", $("#ndo2").val());
                data.append("almv", $("#cmbAlmacen").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#subtotal").val());
                data.append("igv", $("#igv").val());
                data.append("total", $("#total").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                axios.post("/vtassol/registrar", data)
                    .then(function(respuesta) {
                        rpta = respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc;
                        Swal.fire({
                            title: "Se registro correctamente",
                            text: rpta,
                            icon: "success"
                        });
                        limpiardatos();
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                        console.log(error);
                    });
            }
        });
    }
</script>
<?php
$this->endSection("javascript");
?>