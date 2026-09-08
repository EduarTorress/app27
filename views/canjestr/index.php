<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalGuiaTrComponent;
use App\View\Components\ModalImprimir;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$modal = new ModalGuiaTrComponent();
echo $modal->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <button class="btn btn-light" role="button" data-toggle="modal" data-target="#modal_guiastr"><i class="fa fa-file-text-o" aria-hidden="true"></i></button> &nbsp;&nbsp;
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" value="<?php echo isset($datosclientev['razov']) ?  trim($datosclientev['razov']) : ' ' ?>" disabled>
                        <input type="hidden" id="txtidcliente" value="<?php echo isset($datosclientev['idcliev']) ?  $datosclientev['idcliev'] : '' ?> ">
                        <input type="hidden" id="txtNumero" value="<?php echo isset($datosclientev['ndoc']) ?  $datosclientev['ndoc'] : '' ?> ">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idventa) ? $idventa : 0 ?>">
                        <!-- <button class="btn btn-outline-light" role="button" data-toggle="modal" data-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button> -->
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
                    <div class="form-group row">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" style="width: 60%;" id="ndo2" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-1" readonly>
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
                    <div class="form-group row">
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha:</label>
                        <input type="date" class="form-control form-control-sm" disabled value="<?php date("Y-m-d") ?>" style="width:125px;" id="txtfecha" name="txtfecha">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : 'S';
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
                    $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : 'E';
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
            <div class="table-responsive" id="detalle">
                <table id="tabla" class="table table-sm">
                    <thead>
                        <tr>
                            <th style="display:none">ID</th>
                            <th style="width: 400px;">Descripción</th>
                            <th style="width: 70px;" class="text-center">Unid.</th>
                            <th style="width: 70px;" class="text-center">Cant.</th>
                            <th style="width: 70px;" class="text-center">Precio</th>
                            <th style="width: 70px;" class="text-center">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fila">
                            <td style="display:none" class="controles">
                                <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="">
                            </td>
                            <td class="controles">
                                <input type="text" name="descripcion1" style="width: 100%;" class="descripcion" id="descripcion1" placeholder="Descripcion" value="">
                            </td>
                            <td class="text-center controles">
                                <input type="text" name="unidad1" style="width: 70px;" class="unidad" id="unidad1" value="">
                            </td>
                            <td class="text-center controles">
                                <input type="number" name="cantidad1" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="cantidad" id="cantidad1" value="">
                            </td>
                            <td class="text-center controles">
                                <input type="number" name="precio1" onkeyup="calcularPrecioTotal();" style="width:70px;" class="precio" id="precio1" value="">
                            </td>
                            <td class="text-center controles">
                                <input type="number" name="subt1" onkeyup="calcularPrecioTotal()" style="width:70px;" class="subt" id="subt1" value="" disabled>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" align="center">
                                <button id="agregar" class="btn btn-success" disabled>Adicionar</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="input-group">
                    <input type="text" id="txtsubtotal" name="txtsubtotal" placeholder="SUB TOTAL" class="form-control" value="<?php echo empty($datosclientev['subtotalv']) ?  '' :  $datosclientev['subtotalv'] ?>" style="width:140px;" disabled>
                    <input type="text" id="txtigv" name="txtigv" placeholder="IGV" class="form-control" value="<?php echo empty($datosclientev['igvv']) ?  '' :  $datosclientev['igvv'] ?>" disabled>
                    <input type="text" id="txttotal" name="txttotal" placeholder="TOTAL" class="form-control" value="<?php echo empty($datosclientev['impov']) ?  '' :  $datosclientev['impov'] ?>" disabled>
                </div>
                <input type="hidden" id="txtvalordetraccion" name="txtvalordetraccion" class="form-control" value="<?php echo round(floatval($gene_detra), 0); ?>" disabled>
                <input type="text" id="txtdetraccion" name="txtdetraccion" placeholder="0.00" class="form-control" disabled>
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
        $("#txtfecha").val("<?php echo date("Y-m-d") ?>")
        $("#txtfechavto").val("<?php echo  date("Y-m-d") ?>")
        buscarGuiasTr();
    }

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
        //     toastr.info("Ingrese Descripcíon",'Mensaje del Sistema');
        //     return
        // }
        // if (!ncant) {
        //     toastr.info("Ingrese Cantidad",'Mensaje del Sistema');
        //     return
        // }
        // if (!npeso) {
        //     toastr.info("Ingrese Peso",'Mensaje del Sistema');
        //     return
        // }
        $('#tabla tbody')
            .append(`<tr>
            <td style="display:none" class="controles">
                <input type="text" name="nreg${numTr}" style="width: 100%;" class="nreg" id="nreg${numTr}" placeholder="" value="0">
            </td>
           <td class="controles">
             <input type="text" name="descripcion${numTr}" style="width: 100%;" class="descripcion" id="descripcion${numTr}" onkeyup="mayusculas(this)" placeholder="Descripcion">
           </td>
           <td class="text-center controles">
             <input type="text" name="unidad${numTr}" style="width: 70px;" class="unidad"  onkeyup="mayusculas(this)" id="unidad${numTr}">
           </td>
           <td class="text-center controles">
             <input type="number" name="cantidad${numTr}" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="cantidad" id="cantidad${numTr}" value="cantidad${numTr}">
           </td>
            <td class="text-center controles">
             <input type="number" name="precio${numTr}" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="precio" id="precio${numTr}" value="precio${numTr}">
           </td>
           <td class="text-center controles">
             <input type="number" name="subt${numTr}" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="subt" id="subt${numTr}" value="subt${numTr}" disabled>
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
        // $("h2").text('Total: ' + total + ' €');
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

    function agregarprimeritem() {
        $('#tabla tbody')
            .append(`<tr>
            <td style="display:none" class="controles">
                <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="0">
            </td>
           <td class="controles">
             <input type="text" name="descripcion1" style="width: 100%;" class="descripcion" id="descripcion1" onkeyup="mayusculas(this)" placeholder="Descripcion">
           </td>
           <td class="text-center controles">
             <input type="number" name="cantidad1" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="cantidad" id="cantidad1" >
           </td>
           <td class="text-center controles">
           <input type="text" name="unidad1" style="width: 70px;" class="unidad" id="unidad1" >
           </td>
           <td class="text-center controles">
             <input type="number" name="precio1" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="precio" id="precio1">
           </td>
           <td class="text-center controles">
             <input type="number" name="subt1" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="subt" id="subt1" disabled>
           </td>
         </tr>`);
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
        grabar('¿Registrar Canje de Guia?');
    }

    function grabar(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "Se Graba en la Base de datos ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                // detalle = obtenerdetalletabla("tabla");
                // console.log(detalle);
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
                // console.log(detalle);
                data.append("idcliev", $("#txtidcliente").val());
                data.append("razov", $("#txtcliente").val());
                data.append("tdocv", $("#cmbdcto").val())
                data.append("ndo2v", $("#ndo2").val());
                data.append("txtdireccion", $("#txtdireccion").val());
                data.append("txtruccliente", $("#txtruccliente").val());
                data.append("txtdnicliente", $("#txtdnicliente").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#txtsubtotal").val());
                data.append("igv", $("#txtigv").val());
                data.append("ndias", $("#txtdias").val());
                data.append("total", $("#txttotal").val());
                data.append("detalle", JSON.stringify(detalle));
                data.append("detraccion", $("#txtdetraccion").val());
                axios.post("/vtas/registrarcanjetr", data)
                    .then(function(respuesta) {
                        toastr.success(' Se Genero el documento: ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        limpiar();
                        buscarGuiasTr();
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
                                    // console.log('hola movil')
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
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                    });
            }
        });
    }

    function limpiar() {
        document.querySelector('#txtcliente').value = "";
        document.getElementById("titulo").innerHTML = "Registrar Venta";
        document.getElementById("ndo2").innerHTML = "";
        document.querySelector("#txtidcliente").value = "0";
        document.querySelector("#txtruccliente").value = "0";
        document.querySelector("#txtdireccion").value = "";
        document.querySelector("#ndo2").value = "";
        document.querySelector("#cmbforma").value = "E";
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector("#txtdias").value = "0";
        $("#txtsubtotal").val('0.00');
        $("#txtigv").val('0.00');
        $("#txttotal").val('0.00');
        $('#tabla tbody tr').remove();
    }
</script>
<?php
$this->endSection("javascript");
?>