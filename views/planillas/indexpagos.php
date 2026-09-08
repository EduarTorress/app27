<?php

use App\View\Components\ComboAnosComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form class="form-inline">
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent(session()->get("usuario_id"));
                        echo $lu->render();
                        ?>
                    </form>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divoi" role="tab" aria-selected="true">Registrar Pagos</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divoi" role="tabpanel"><br>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label" for="">AÑO</label>
                                <div class="col-sm-3">
                                    <?php
                                    $ano = new ComboAnosComponent('');
                                    echo $ano->renderreport();
                                    ?>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label" for="">MES</label>
                                <div class="col-sm-3">
                                    <?php
                                    $mes = new ComboAnosComponent('');
                                    echo $mes->renderreportmes();
                                    ?>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">SUELDO</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="txtsueldo" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">PAGADO</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="txtpagado" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">SALDO PENDIENTE</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="txtpendiente" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">A PAGAR</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="txtapagar" placeholder="0.00">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">REFERENCIA</label>
                                <div class="col">
                                    <input type="text" class="form-control" id="txtdetalle">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm">
                                    <button class="btn btn-primary" onclick="vermovimientosxusuario()">Visualizar Movimientos</button>
                                    <button type="button" onclick="limpiar();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
                                    <button type="button" onclick="registrarIngreso();" class="btn btn-success float-right" id="btnregistrar"><i class="fas fa-plus-circle"></i> Registrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="divpagos"></div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbusuarios").append($('<option>', {
            value: 0,
            text: 'SELECCIONE'
        }))
        $("#btnregistrar").attr('disabled', 'disabled');
        $("#cmbusuarios").val("0");
    }

    $('#cmbusuarios').on('change', function() {
        listarsaldoxusuario();
    });

    $('#cmbmes').on('change', function() {
        listarsaldoxusuario();
    });

    $('#cmbano').on('change', function() {
        listarsaldoxusuario();
    });

    function listarsaldoxusuario() {
        idusua = $("#cmbusuarios").val();
        if (idusua != '0') {
            axios.get('/pagosplanilla/buscarsaldoxusuario', {
                "params": {
                    "cmbmes": $("#cmbmes").val(),
                    "cmbano": $("#cmbano").val(),
                    "cmbidusua": $("#cmbusuarios").val()
                }
            }).then(function(respuesta) {
                toastr.success("Se cargaron los datos satisfactoriamente", 'Mensaje del Sistema');
                rpta = respuesta.data.data;
                $("#txtsueldo").val(rpta[0].sueldo);
                $("#txtpendiente").val(rpta[0].pendiente);
                $("#txtpagado").val((Number(rpta[0].sueldo) - Number(rpta[0].pendiente)).toFixed(2));
                if (Number(rpta[0].pendiente) == 0) {
                    $("#btnregistrar").attr('disabled', 'disabled');
                    $("#txtapagar").attr('disabled', 'disabled');
                } else {
                    $("#btnregistrar").removeAttr('disabled');
                    $("#txtapagar").removeAttr('disabled');
                    $("#txtapagar").select();
                }
            }).catch(function(error) {
                console.log(error);
                toastr.error('Error al cargar. ' + error.response.data.message, 'Mensaje del sistema')
            });
        }
    }

    function registrarIngreso() {
        validacion = validarIngreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Pago?",
                text: "Se registrará en el sistema.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("txtsueldo", $("#txtsueldo").val());
                    data.append("txtpagado", $("#txtpagado").val());
                    data.append("txtpendiente", $("#txtpendiente").val());
                    data.append("txtapagar", $("#txtapagar").val());
                    data.append("txtdetalle", $("#txtdetalle").val());
                    data.append("cmbusuarios", $("#cmbusuarios").val());
                    data.append("cmbmes", $("#cmbmes").val());
                    data.append("cmbano", $("#cmbano").val());
                    axios.post("/pagosplanilla/registrarpago", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                text: "Se generó el pago satisfactoriamente",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                            listarsaldoxusuario();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", 'Mensaje del Sistema');
        }
    }

    function validarIngreso() {
        importe = $("#txtapagar").val();
        if (importe == '' || importe == 0 || importe == '0') {
            toastr.error("Ingrese un importe", 'Mensaje del Sistema');
            return false;
        }
        pendiente = $("#txtpendiente").val();
        txtapagar = $("#txtapagar").val();
        if (Number(txtapagar) > Number(pendiente)) {
            toastr.error("No se puede pagar más del sueldo", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function limpiar() {
        // var elements = document.getElementsByTagName("input");
        // for (var ii = 0; ii < elements.length; ii++) {
        //     if (elements[ii].type == "text" || elements[ii].type == "number") {
        //         elements[ii].value = "";
        //     }
        // }
        $("#txtapagar").val("");
        $("#txtdetalle").val("")
    }

    function vermovimientosxusuario() {
        idusua = $("#cmbusuarios").val();
        if (idusua != '0') {
            axios.get('/pagosplanilla/mostrarmodalpagosausuarios', {
                "params": {
                    "mes": $("#cmbmes").val(),
                    "ano": $("#cmbano").val(),
                    "cmbidusua": idusua,
                    "nombredelmes": $("#cmbmes option:selected").text()
                }
            }).then(function(respuesta) {
                $("#divpagos").html(respuesta.data)
                $("#modalpagos").modal("show");
            }).catch(function(error) {
                toastr.error('Error al cargar ' + error, 'Mensaje del sistema')
            });
        } else {
            toastr.error("No ha seleccionado ningun trabajador", 'Mensaje del Sistema');
        }
    }

    function darbaja(datos, htmltr) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/pagosplanilla/eliminar/' + datos.parametro1;
                axios.post(ruta).then(function(respuesta) {
                    // console.log(respuesta.data);
                    let rpta = (respuesta.data.estado)
                    if (rpta == '1') {
                        toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                        row = $(htmltr).parent().parent();
                        $(row).remove();
                    }
                }).catch(function(error) {
                    console.log(error);
                    toastr.error('Error al eliminar', 'Mensaje del Sistema');
                });
            }
        })
    }
</script>
<?php
$this->endSection('javascript');
?>