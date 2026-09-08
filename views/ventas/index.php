<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalImprimir;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$clie = new ModalClienteComponent();
echo $clie->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" value="<?php echo isset($datosclientev['razov']) ?  trim($datosclientev['razov']) : ' ' ?>" readonly>
                        <input type="hidden" id="txtidcliente" value="<?php echo isset($datosclientev['idcliev']) ?  $datosclientev['idcliev'] : '' ?> ">
                        <input type="hidden" id="txtNumero" value="<?php echo isset($datosclientev['ndoc']) ?  $datosclientev['ndoc'] : '' ?> ">
                        <input type="hidden" id="txtclienteretencion" value="<?php echo isset($datosclientev['clienteretencion']) ?  $datosclientev['clienteretencion'] : 'N' ?>">
                        <!-- <input type="hidden" id="txtruccliente" value="<?php echo isset($datosclientev['ruccliev']) ?  $datosclientev['ruccliev'] : '' ?> ">
                        <input type="hidden" id="txtdnicliente" value="<?php echo isset($datosclientev['dnicliev']) ?  $datosclientev['dnicliev'] : '' ?> "> -->
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idventa) ? $idventa : 0 ?>">
                        <button class="btn btn-outline-light" id="btnmdclientes" role="button"><i style="color:black" class="fas fa-user-alt"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <!-- <div class="form-group row">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Serie y Número de Venta : </label>
                        <input type="text" class="form-control form-control-sm" maxlength="4" id="cndoc1" value="<?php echo isset($serie) ?  $serie : '' ?>" style="width: 20%;" placeholder="F001">
                        <input type="text" onkeypress="return validarNumeros(event);" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="<?php echo isset($num) ?  $num : '' ?>" style="width: 30%;" placeholder="00001">        
                    </div> -->
                    <div class="input-group mb-1">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" style="width: 60%;" id="ndo2" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-1">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div style="display:none">
                        <?php
                        $cempresa = isset($datosclientev['almv']) ? $datosclientev['almv'] : '';
                        $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                        echo $empresa->render();
                        ?>
                    </div>
                    <div class="input-group mb-1">
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha:</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosclientev['fechv']) ? date("Y-m-d") : $datosclientev['fechv'] ?>" style="width:125px;" id="txtfecha" name="txtfecha">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : '';
                    $tpmoneda = new TipoMonedaComponent($cmon);
                    echo $tpmoneda->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" placeholder="RUC" id="txtruccliente" class="form-control" value="<?php echo isset($datosclientev['ruccliev']) ?  $datosclientev['ruccliev'] : '' ?> " disabled>
                        <input type="text" placeholder="DNI" id="txtdnicliente" class="form-control" value="<?php echo isset($datosclientev['dnicliev']) ?  $datosclientev['dnicliev'] : '' ?> " disabled>
                        <input type="text" placeholder="Dirección" id="txtdireccion" name="txtdireccion" class="form-control" value="<?php echo isset($datosclientev['direcliev']) ?  $datosclientev['direcliev'] : '' ?> " disabled>
                    </div>
                </div>
                <div class="col-sm-3">
                    <?php
                    $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : '';
                    $formapago = new FormadepagoComponent($cforma);
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    $dfechavto = new FechavtoComponent();
                    echo $dfechavto->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $nidv = isset($datosclientev['idvenv']) ? $datosclientev['idvenv'] : 0;
                    $vendedor = new VendedorComponent($nidv);
                    echo $vendedor->render();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla" class="table table-sm">
                    <thead>
                        <tr>
                            <th style="display:none">ID</th>
                            <th style="width: 400px;">Descripción</th>
                            <th style="width: 70px;" class="text-center">Unid.</th>
                            <th style="width: 70px;" class="text-center">Cant.</th>
                            <th style="width: 70px;" class="text-center">Precio</th>
                            <th style="width: 70px;" class="text-center">Importe</th>
                            <th style="width: 70px;" class="text-center">Opcion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach ($detalle as $indice => $item) : ?>
                            <?php if ($item['activo'] == 'A') { ?>
                                <tr class="fila">
                                    <td style="display:none" class="controles">
                                        <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="<?php echo $item['nreg'] ?>">
                                    </td>
                                    <td class="controles">
                                        <input type="text" onclick="this.select()" name="descripcion1" style="width: 100%;" onkeypress="pasarsiguientecelda(this,event);" class="descripcion" id="descripcion1" placeholder="Descripcion" value="<?php echo $item['descri'] ?>">
                                    </td>
                                    <td class="text-center controles">
                                        <input type="text" onclick="this.select()" name="unidad1" style="width: 70px;" onkeypress="pasarsiguientecelda(this,event);" class="unidad" id="unidad1" value="<?php echo $item['unidad'] ?>">
                                    </td>
                                    <td class="text-center controles">
                                        <input type="number" onclick="this.select()" name="cantidad1" onkeyup="calcularPrecioTotal();" onkeypress="pasarsiguientecelda(this,event);" style="width: 70px;" class="cantidad" id="cantidad1" value="<?php echo $item['cant'] ?>">
                                    </td>
                                    <td class="text-center controles">
                                        <input type="number" onclick="this.select()" name="precio1" onkeyup="calcularPrecioTotal();" onkeypress="pasarsiguientefila(this,event);" style="width:70px;" class="precio" id="precio1" value="<?php echo $item['precio'] ?>">
                                    </td>
                                    <td class="text-center controles">
                                        <input type="number" name="subt1" onkeyup="calcularPrecioTotal()" style="width:70px;" class="subt" id="subt1" value="<?php echo $item['subt'] ?>" disabled>
                                    </td>
                                    <td class="text-center">
                                        <button class="borrar btn btn-danger" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" align="center">
                                <button id="agregar" class="btn btn-success">Adicionar</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="input-group">
                    <input type="text" id="" name="" style="font-weight: bold;" value="SUB. TOTAL:" class="form-control" value="" style="width:140px;" readonly>
                    <input type="text" id="txtsubtotal" name="txtsubtotal" placeholder="SUB TOTAL" class="form-control" value="<?php echo empty($datosclientev['subtotalv']) ?  '' :  $datosclientev['subtotalv'] ?>" style="width:140px;" disabled>
                    <input type="text" id="" name="" style="font-weight: bold;" value="VALOR IGV:" class="form-control" value="" style="width:140px;" readonly>
                    <input type="text" id="txtigv" name="txtigv" placeholder="IGV" class="form-control" value="<?php echo empty($datosclientev['igvv']) ?  '' :  $datosclientev['igvv'] ?>" disabled>
                    <input type="text" id="" name="" style="font-weight: bold;" value="VALOR TOTAL:" class="form-control" value="" style="width:140px;" readonly>
                    <input type="text" id="txttotal" name="txttotal" placeholder="TOTAL" class="form-control" value="<?php echo empty($datosclientev['impov']) ?  '' :  $datosclientev['impov'] ?>" disabled>
                </div>
                <input type="hidden" id="txtvalordetraccion" name="txtvalordetraccion" class="form-control" value="<?php echo round(floatval($gene_detra), 0); ?>" disabled>
                <div class="input-group">
                    <input type="text" id="" name="" class="form-control" style="font-weight: bold;" value="DETRACCION:" style="width:140px;" readonly>
                    <input type="text" id="txtdetraccion" name="txtdetraccion" placeholder="0.00" class="form-control" disabled value="<?php echo empty($datosclientev['rcom_detr']) ?  '' :  $datosclientev['rcom_detr'] ?>">
                </div>
                <br>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success" onclick="grabarVenta();">Grabar</button>
                    <button class="btn btn-warning" onclick="limpiar()">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
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
        $("#txtfechavto").val("<?php echo (empty($datosclientev['fvto']) ?  date("Y-m-d") :  $datosclientev['fvto']) ?>")
        calcularPrecioTotal();
        $(".tipodocumentos option[value='GI']").remove();
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        <?php if ($idventa <> 0) : ?>
            $("#cmbdcto").attr('disabled', true);
        <?php endif; ?>
    }

    $(document).ready(function() {
        idcliente = $("#txtidcliente").val();
        if (idcliente.trim() == '0') {
            $("#modal_clientes").modal("show");
            $("#txtbuscar").val("VENTAS DEL DIA");
        }
    });

    //// ACA INICIA JAVASCRIPT
    $("#agregar").on("click", function() {
        var numTr = $('#tabla tbody tr').length + 1;
        num = numTr - 1
        cdesc = "#descripcion" + num;
        cdesc = $(cdesc).val();
        let npeso = $("#precio" + num).val();
        let ncant = $("#cantidad" + num).val();
        //LUEGO DESCOMENTAR
        // if (!cdesc) {
        //     toastr.info("Ingrese Descripcíon");
        //     return
        // }
        // if (!ncant) {
        //     toastr.info("Ingrese Cantidad");
        //     return
        // }
        // if (!npeso) {
        //     toastr.info("Ingrese Peso");
        //     return
        // }
        $('#tabla tbody')
            .append(`<tr>
            <td style="display:none" class="controles">
                <input type="text" name="nreg${numTr}" style="width: 100%;" class="nreg" id="nreg${numTr}" placeholder="" value="0">
            </td>
           <td class="controles">
             <input type="text" onclick="this.select()" name="descripcion${numTr}" style="width: 100%;" class="descripcion" id="descripcion${numTr}" onkeyup="mayusculas(this);" onkeypress="pasarsiguientecelda(this,event);" placeholder="Descripcion">
           </td>
           <td class="text-center controles">
             <input type="text" onclick="this.select()" name="unidad${numTr}" style="width: 70px;" class="unidad" onkeyup="mayusculas(this);" onkeypress="pasarsiguientecelda(this,event);" id="unidad${numTr}">
           </td>
           <td class="text-center controles">
             <input type="number" onclick="this.select()" name="cantidad${numTr}" onkeyup="calcularPrecioTotal();" onkeypress="pasarsiguientecelda(this,event);" style="width: 70px;" class="cantidad" id="cantidad${numTr}" value="cantidad${numTr}">
           </td>
            <td class="text-center controles">
             <input type="number" onclick="this.select()" name="precio${numTr}" onkeyup="calcularPrecioTotal();" onkeypress="pasarsiguientefila(this,event);" style="width: 70px;" class="precio" id="precio${numTr}" value="precio${numTr}">
           </td>
           <td class="text-center controles">
             <input type="number" onclick="this.select()" name="subt${numTr}" onkeyup="calcularPrecioTotal();" style="width: 70px;" class="subt" id="subt${numTr}" value="subt${numTr}" disabled>
           </td>
           <td class="text-center">
             <button class="borrar btn btn-danger" >Eliminar</button>
           </td>
         </tr>`);
        let cvar = `#descripcion${numTr}`
        $(cvar).focus();
    });

    function calcularPrecioTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;

        $("#tabla tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').val());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').val());
            var pesot = cantidad * peso;
            $(this).find('.subt').val(pesot.toFixed(2));
            pesoTotal.push(pesot);
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#txttotal").val(total.toFixed(2));
            vigv = <?php echo session()->get("gene_igv"); ?>;
            subtotal = (total / vigv);
            igv = (total - subtotal);
            $("#txtigv").val(igv.toFixed(2));
            $("#txtsubtotal").val(subtotal.toFixed(2));
        } else {
            $("#txttotal").val("0.00");
            $("#txtigv").val("0.00");
            $("#txtsubtotal").val("0.00");
        }
        if (total > 400) {
            txtvalordetraccion = $("#txtvalordetraccion").val();
            txtdetraccion = (Number(txtvalordetraccion) * total) / 100;
            $("#txtdetraccion").val(txtdetraccion);
            // 4*1000/100
        } else {
            $("#txtdetraccion").val("0.00");
        }
    }


    $(document).on('click', '.borrar', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        calcularPrecioTotal();
        txtIdauto = $("#txtIdauto").val();
        if (txtIdauto != '') {
            // console.log('hola')
            fila = $(this).parent('td').parent('tr');
            nreg = fila.find('td .nreg').val();
            // console.log(nreg)
            if (nreg != 0) {
                if (typeof arrayEliminados === 'undefined') {
                    // console.log('No existe, se creará');
                    arrayEliminados = [];
                    arrayEliminados.push(nreg)
                } else {
                    arrayEliminados.push(nreg)
                }
            }
            localStorage.setItem("arrayEliminados", arrayEliminados);
        }
    });

    $('#btnmdclientes').click(function() {
        $("#modal_clientes").modal("show");
    });

    $("#modal_clientes").on("hidden.bs.modal", function() {
        grabarCabecera();
        $("#cmbdcto").focus();
        $("#cmbdcto").click();
        enterPressed = 0
    });

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
        $("#txtbuscar").select();
        $('#txtcliente').one('focus', function(e) {
            $("#cmbdcto").focus();
            $("#cmbdcto").click();
        });
    });

    $('#cmbdcto').one('focus', function(e) {
        $("#cmbdcto").focus();
        $("#cmbdcto").click();
    });

    function entertipodocumento(u) {
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    $("#agregar").click();
                }
                enterPressed++;
                return;
            }
        };
    }

    $("#modal_clientes").on("hidden.bs.modal", function() {
        grabarCabecera();
    });

    function grabarCabecera() {
        razon = document.getElementById("txtcliente").value
        razov = razon.replace('"', '')
        data = new FormData();
        data.append("idcliev", $("#txtidcliente").val());
        data.append("razov", razov);
        data.append("ruccliev", $("#txtruccliente").val());
        data.append("dnicliev", $("#txtdnicliente").val());
        data.append("clienteretencion", $("#txtclienteretencion").val());
        data.append("direcliev", $("#txtdireccion").val())
        data.append("tdocv", $("#cmbdcto").val());
        data.append("ndoc", $("#txtNumero").val());
        data.append("numv", '');
        data.append("ndo2v", $("#ndo2").val());
        data.append("almv", $("#cmbAlmacen").val());
        data.append("fechv", $("#txtfecha").val());
        data.append("monev", $("#cmbmoneda").val());
        data.append("formv", $("#cmbforma").val());
        data.append("fechvv", $("#txtfechavto").val());
        data.append("idvenv", $("#cmbvendedor").val());
        data.append("ndias", $("#txtdias").val());
        axios.post("/vtas/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión" + error, "Mensaje del Sistema");
            });
    }

    function validarVenta() {
        idcliente = document.querySelector('#txtidcliente').value;
        total = document.querySelector('#txttotal').value;
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
        if (ctdoc === '01' && ruc.trim() === '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc === '01' && ruc === 0) {
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
        } else {
            cmensaje = '¿Actualizar Venta? ';
            actualizar(cmensaje);
        }
    }

    function grabar(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "Se guardará en la base de datos ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const detalle = []
                $("#tabla tbody tr").each(function() {
                    json = "";
                    $(this).find("td input").each(function() {
                        $this = $(this);
                        json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                    })
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                data = new FormData();
                data.append("idcliev", $("#txtidcliente").val());
                data.append("razov", $("#txtcliente").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("ndo2v", $("#ndo2").val());
                data.append("txtdireccion", $("#txtdireccion").val());
                data.append("txtruccliente", $("#txtruccliente").val());
                data.append("txtdnicliente", $("#txtdnicliente").val());
                data.append("txtclienteretencion", $("#txtclienteretencion").val());
                data.append("almv", $("#cmbAlmacen").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#txtsubtotal").val());
                data.append("igv", $("#txtigv").val());
                data.append("ndias", $("#txtdias").val());
                data.append("total", $("#txttotal").val());
                data.append("txtdetraccion", $("#txtdetraccion").val());
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/ovtas/registrar", data)
                    .then(function(respuesta) {
                        toastr.success(' Se Genero el documento: ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        var cruta = '/vtas/imprimirdirecto/';
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', cruta, true);
                        xhr.responseType = 'blob';
                        xhr.onload = function(e) {
                            if (this.status == 200) {
                                var w = screen.width;
                                url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                                // console.log(w)
                                if (w <= 768) {
                                    var req = new XMLHttpRequest();
                                    req.open("GET", url, true);
                                    req.responseType = "blob";
                                    req.onload = function(event) {
                                        var blob = req.response;
                                        // console.log(blob.size);
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = respuesta.data.ndoc + ".pdf"
                                        link.click();
                                    };
                                    req.send();
                                } else {
                                    $("#pdfguia").attr("src", url)
                                    $("#abrirguia").click();
                                }
                            }
                        };
                        xhr.send();
                        limpiar();
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                    });
            }
        });
    }

    function actualizar(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "Se modificará en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const detalle = []
                $("#tabla tbody tr").each(function() {
                    json = "";
                    $(this).find("td input").each(function() {
                        $this = $(this);
                        json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                    })
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                data = new FormData();
                data.append("idautov", $("#txtidauto").val());
                data.append("idcliev", $("#txtidcliente").val());
                data.append("razov", $("#txtcliente").val());
                data.append("txtclienteretencion", $("#txtclienteretencion").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("ndoc", $("#txtNumero").val());
                data.append("ndo2v", $("#ndo2").val());
                data.append("almv", $("#cmbAlmacen").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#txtsubtotal").val());
                data.append("igv", $("#txtigv").val());
                data.append("total", $("#txttotal").val());
                data.append("txtdetraccion", $("#txtdetraccion").val());
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/ovtas/actualizar", data)
                    .then(function(respuesta) {
                        Swal.fire(' Se actualizo la venta correctamente ');
                        limpiar();
                        window.location.href = "/vtas/vtasresumidas";
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                    });
            }
        });
    }

    function limpiar() {
        axios.post("/ovtas/limpiar").then(function(respuesta) {
            document.querySelector('#txtcliente').value = "";
            document.getElementById("titulo").innerHTML = "Registrar Venta";
            document.getElementById("ndo2").innerHTML = "";
            document.querySelector("#txtidcliente").value = "0";
            document.querySelector("#txtruccliente").value = "0";
            $("#txtdnicliente").val("0");
            document.querySelector("#txtdireccion").value = "";
            document.querySelector("#ndo2").value = "";
            document.querySelector("#cmbforma").value = "E";
            document.querySelector("#cmbmoneda").value = "S";
            document.querySelector("#txtdias").value = "0";
            $("#txtsubtotal").val('0.00');
            $("#txtigv").val('0.00');
            $("#txttotal").val('0.00');
            $('#tabla tbody tr').remove();
        }).catch(function(error) {
            console.log(error)
        });
    }

    //Eventos
    var txtfecha = document.getElementById("txtfecha");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfecha").val();
        grabarCabecera();
    }, true);

    var ndo2 = document.getElementById("ndo2");
    ndo2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    $('#modal_clientes').on('shown.bs.modal', function() {
        $('#txtbuscar').focus();
    });

    function pasarsiguientecelda(html, evt) {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if (code == 13) {
            inputsiguiente = $(html).parent().next().find("input");
            $(inputsiguiente).click();
            $(inputsiguiente).select();
            // console.log($(inputsiguiente).val());
        }
    }

    function pasarsiguientefila(html, evt) {
        var code = (evt.which) ? evt.which : evt.keyCode;
        if (code == 13) {
            inputsiguiente = $(html).parent().parent().next();
            if (inputsiguiente.html() === undefined || inputsiguiente.html() == 'undefined') {
                // console.log('indefinido - no existe fila')
                $("#agregar").click();
            }
            // else {
            //     console.log($(inputsiguiente).html());
            //     console.log('definido - si existe fila');
            // }
            // $(inputunidad).click();
            // $(inputunidad).select();
            // console.log($(inputunidad).val());
        }
    }
</script>
<?php
$this->endSection("javascript");
?>