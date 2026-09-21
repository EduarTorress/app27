<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\VendedorComponent;
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
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-4">
                    <div class="input-group ">
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                        <input type="hidden" id="txtidcliente" value="0">
                        <input type="hidden" id="txtruccliente" value="">
                        <input type="hidden" id="txtformapago" value="E">
                        <input type="hidden" id="rcom_mens" value="">
                        <input type="hidden" id="txtdireccion" value="">
                        <input type="hidden" id="txtdnicliente" value="0">
                        <input type="hidden" id="txtclienteretencion" value="N">
                        <input type="hidden" id="txtidauto" value="0">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                        <button class="btn btn-outline-info" role="button" data-bs-toggle="modal" data-bs-target="#modal_ventas"><i style="color:black" class="fa fa-file-text-o"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm" for="">Nro documento:</label>
                        <input type="text" class="form-control form-control-sm" id="txtndoc" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline" style="width:max-content; width:auto;">
                        <div class="col-12" id="detalle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cargamodal">
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
        // $(".tipodocumentos option[value='03']").remove();
        // $(".tipodocumentos option[value='01']").remove();
        $(".tipodocumentos option[value='22']").remove();
        $(".tipodocumentos option[value='GI']").remove();
        $(".tipodocumentos option[value='20']").remove();
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $("#cmbvendedor").attr("readonly");
        $("#cmbdcto").val("01");
        $("#txtfecha").attr("readonly");
        // jQuery('#motivo').replaceWith(jQuery('#cmbMotivoNotaC'));
    }

    $('#modal_clientes').on('hidden.bs.modal', function(e) {
        idCliente = $("#txtidcliente").val();
        buscarVentasPorCliente(idCliente);
    });

    $("#modal_clientes").on("shown.bs.modal", function(e) {
        $('#detalle').html(" ");
        $("#txtbuscar").focus();
        $("#txtbuscar").select();
    });

    function buscarVentasPorCliente(idCliente) {
        axios.get('/vtas/listarnotastocanje', {
            "params": {
                "idCliente": idCliente,
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#cargamodal').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function seleccionarVenta(datos) {
        document.getElementById("txtidauto").value = datos.parametro1;
        document.getElementById("txtndoc").value = datos.parametro4;
        if ($('#txtformapago').length) {
            $('#txtformapago').val(datos.parametro10);
        }
        $('#rcom_mens').val(datos.parametro6);
        $("#modal_ventas").modal('hide');
        buscarDetallePorId(datos.parametro1, datos.parametro9);
        $(".codigo").css("display", "none");
    }

    function buscarDetallePorId(idauto, tipoventa) {
        axios.get('/vtas/listardetallenotastocanje', {
            "params": {
                "idauto": idauto,
                'tipoventa': tipoventa
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            calcularIGV();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del Sistema')
        });
    }

    function calcularIGV() {
        igv = 'I';
        var total_col = 0;
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(5).text();
            // t = t.replace(",", "")
            total_col += parseFloat(t);
        });
        // console.log(total_col)
        if (igv === 'I') {
            //Si el IGV está incluido
            let impo = (Number(total_col)).toFixed(2);
            valorigvgene = Number("<?php echo $_SESSION['gene_igv']; ?>");
            let valor = (impo / valorigvgene).toFixed(2);
            let nigv = (impo - valor).toFixed(2);
            $("#igv").val(nigv);
            $("#subtotal").val(valor);
            $("#total").val(impo);
        } else {
            //Si el IGV no está incluido
            impo = Number(total_col);
            $("#subtotal").val(impo.toFixed(2));
            valorigv = (impo * 0.18).toFixed(2);
            $("#igv").val(valorigv);
            // $("#igv").val("18");
            imponoigv = ((impo * 0.18) + impo);
            $("#total").val(imponoigv.toFixed(2));
        }
        let impor = document.querySelector("#total").value;
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
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
        if (ctdoc === '01' && ruc.trim() === '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc === '01' && ruc === 0) {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        var nroDoc = document.getElementById("txtndoc").value.trim().toUpperCase();
        if (nroDoc.startsWith("B") && ctdoc === "03") {
            toastr.error("El documento ya esta registrado como una boleta", 'Mensaje del Sistema');
            return false;
        }
        rcom_mens = $("#rcom_mens").val();
        if (rcom_mens.trim() === "") {
            toastr.error("No es posible actualizar, documento informado a SUNAT", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function grabar() {
        if (!validarVenta()) {
            return;
        }
        ctdoc = $('#cmbdcto option:selected').val();
        if (ctdoc === '03') {
            cmensaje = 'Desea convertir el documento actual en Boleta';
        } else {
            cmensaje = 'Desea convertir el documento actual en Factura';
        }
        registrar(cmensaje);
    }

    function limpiardatos() {
        $("#txtcliente").val("");
        $("#rcom_mens").val("");
        $("#txtformapago").val("E");
        $("#titulo").val("Facturar Ventas");
        $("#txtidcliente").val("0");
        $("#txtruccliente").val("0");
        $("#txtdnicliente").val("0");
        $("#txtndoc").val("");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        document.getElementById("grabar").innerHTML = "Grabar";
        $('#griddetalle tbody tr').remove();
    }

    function registrar(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "¿Seguro de registrar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, estoy seguro',
            cancelButtontext: "No"
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idauto", $("#txtidauto").val());
                data.append("cmbdcto", $("#cmbdcto").val());
                data.append("clienteretencion", $("#txtclienteretencion").val());
                data.append("total", $("#total").val());
                data.append("documentoantiguo", $("#txtndoc").val());
                data.append("txtformapago", $("#txtformapago").val());
                axios.post("/vtas/registrarcanjearnota", data)
                    .then(function(respuesta) {
                        rpta = respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc;
                        Swal.fire({
                            title: "Se registro correctamente",
                            text: rpta,
                            icon: "success"
                        });
                        // toastr.success(rpta);
                        limpiardatos();
                    }).catch(function(error) {
                        // mostrarerroresvalidacion(error);
                        toastr.error(error.response.data, 'Mensaje del sistema')
                    });
            }
        });
    }

    function limpiar() {
        window.location.href = "/vtas/indexcanjearnotas";
    }
</script>
<?php
$this->endSection("javascript");
?>