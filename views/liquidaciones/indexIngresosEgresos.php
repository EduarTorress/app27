<?php
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
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divoi" role="tab" aria-selected="true">Otros Ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#dive" role="tab" aria-selected="false">Egresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divt" role="tab" aria-selected="false">Transferencia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divrb" role="tab" aria-selected="false">Retiro para Bancos</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divoi" role="tabpanel"><br>
                            <?php
                            $tipo = 'I';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="dive" role="tabpanel"><br>
                            <?php
                            $tipo = 'E';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="divt" role="tabpanel"><br>
                            <?php
                            $tra = new \App\View\Components\TransferenciaComponent();
                            echo $tra->render();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="divrb" role="tabpanel"><br>
                            <?php
                            $retba = new \App\View\Components\RetirarparaBancosComponent();
                            echo $retba->render();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbusuarios").attr('disabled', true);
        idserie = 0;
    }

    $(document).ready(function() {
        obtenernumeracion();
    });

    function obtenernumeracion() {
        axios.get('/cajas/obtenernumerodocumento').then(function(respuesta) {
            numerodocumento = respuesta.data.data;
            if (numerodocumento != '0') {
                llenarnumeracion(numerodocumento);
                idserie = respuesta.data.message;
            }
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function llenarnumeracion(documento) {
        $("#txtnumerodocumentoi").val(documento);
        $("#txtnumerodocumentoi").attr("readonly", true);
        $("#txtnumerodocumentoe").val(documento);
        $("#txtnumerodocumentoe").attr("readonly", true);
    }

    function registrar(tipo) {
        if (tipo == 'I') {
            dtipo = 'Ingreso';
            registrarIngreso();
        } else {
            dtipo = 'Egreso'
            registrarEgreso();
        }
    }

    function registrarIngreso() {
        validacion = validarIngreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Ingreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechai").val());
                    data.append("cndoc", $("#txtnumerodocumentoi").val());
                    data.append("cdeta", $("#txtdetallei").val());
                    data.append("sdeudor", $("#txtimportei").val());
                    data.append("sacreedor", 0);
                    data.append("cmbformapago", $("#cmbformapagoi").val());
                    data.append("cargocajero", $("cmbcajeroi").val());
                    data.append("tipo", 'I');
                    data.append("idserie", idserie);
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            imprimiringreso();
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoi").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", 'Mensaje del Sistema');
        }
    }

    function registrarEgreso() {
        validacion = validarEgreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Egreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechae").val());
                    data.append("cndoc", $("#txtnumerodocumentoe").val());
                    data.append("cdeta", $("#txtdetallee").val());
                    data.append("sdeudor", 0);
                    data.append("sacreedor", $("#txtimportee").val());
                    data.append("cmbformapago", $("#cmbformapagoe").val());
                    data.append("tipo", 'E');
                    data.append("cargocajero", $("cmbcajeroe").val());
                    data.append("idserie", idserie);
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            imprimiregreso();
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoe").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
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
        cndoc = $("#txtnumerodocumentoi").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallei").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportei").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function validarEgreso() {
        cndoc = $("#txtnumerodocumentoe").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallee").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportee").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function limpiar() {
        var elements = document.getElementsByTagName("input");
        for (var ii = 0; ii < elements.length; ii++) {
            if (elements[ii].type == "text") {
                elements[ii].value = "";
            }
        }
        $("#txtimportei").val('');
        $("#txtimportee").val('');
        $("#txtmontoatransferir").val('');
        obtenernumeracion();
        $('#btngrabar').removeAttr('disabled')
        $("#cmbcajeroi").val('0');
        $("#cmbcajeroe").val('0');
        $("#contenedorCajero").find('select')
            .addClass('cajero-bloqueado')
            .prop('disabled', true);
    }

    function registrarretiroparabancos() {
        // Primero puedes colocar aquí tu función de validación
        var validacion = validarRetiroParaBancos();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Retiro para Bancos?",
                text: "Se registrará en la base de datos.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, proceder",
                cancelButtonText: "No, cancelar"
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    var data = new FormData();
                    // ==========================================
                    // DATOS DEL FORMULARIO PRINCIPAL
                    // ==========================================

                    data.append("txtnumerodocumentorb", $("#txtnumerodocumentorb").val());
                    data.append("txtfecharb", $("txtfecharb").val());
                    data.append("txtsaldorb", $("#txtsaldorb").val());
                    data.append("txtimporterb", $("#txtimporterb").val());

                    // ==========================================
                    // DATOS DE LA MODAL - DEPOSITO EN CUENTA
                    // ==========================================

                    data.append("cmbnumeroscuenta", $("#cmbnumeroscuenta").val());
                    data.append("txtfechade", $("#txtfechade").val());
                    data.append("txttipocambiode", $("#txttipocambiode").val());
                    data.append("txtoperacionde", $("#txtoperacionde").val());
                    data.append("txtimportede", $("#txtimportede").val());
                    data.append("cmbformapagode", $("#cmbformapagode").val());
                    data.append("txtdetallede", $("#txtdetallede").val());

                    data.append("idserie", idserie);

                    // ==========================================
                    // ENVIAR AL CONTROLADOR
                    // ==========================================

                    axios.post("/cajas/registrarretiroparabancos", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El retiro se registró correctamente.",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            // Limpiar formulario
                            limpiaretiroparabancos();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", "Mensaje del Sistema");
        }
    }

    function validarRetiroParaBancos() {
        if ($("#txtnumerodocumentorb").val().trim() == "") {
            return false;
        }
        if ($("#txtfecharb").val() == "") {
            return false;
        }
        if ($("#txtsaldorb").val() == "") {
            return false;
        }
        if ($("#txtimporterb").val() == "") {
            return false;
        }
        // Datos de la modal
        if ($("#cmbnumeroscuenta").val() == "") {
            return false;
        }
        if ($("#txtfechade").val() == "") {
            return false;
        }
        if ($("#txtimportede").val() == "") {
            return false;
        }
        return true;
    }

    function limpiaretiroparabancos() {
        $("#txtnumerodocumentorb").val("");
        $("#txtfecharb").val("<?php echo date('Y-m-d'); ?>");
        $("#txtsaldorb").val("");
        $("#txtimporterb").val("");

        $("#cmbnumeroscuenta").val($("#cmbnumeroscuenta option:first").val());
        $("#txtfechade").val("<?php echo date('Y-m-d'); ?>");

        $("#txtoperacionde").val("");
        $("#txtimportede").val("");
        $("#cmbformapagode").val("D");
        $("#txtdetallede").val("");

        var modal = bootstrap.Modal.getInstance(
            document.getElementById("modaldepositoencuenta")
        );
        if (modal) {
            modal.hide();
        }
        $('#btngrabar').removeAttr('disabled')
    }

    function registrartransferencia() {
        var validacion = validarTransferencia();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Transferencia?",
                text: "Se registrará en la base de datos.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, proceder",
                cancelButtonText: "No, cancelar"
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    var data = new FormData();
                    // Datos del formulario
                    data.append("txtnumerodocumentot", $("#txtnumerodocumentot").val());
                    data.append("txtfechat", $("#txtfechat").val());
                    data.append("cmbformapagot", $("#cmbformapagot").val());
                    data.append("txtsaldot", $("#txtsaldot").val());
                    data.append("txtimportet", $("#txtimportet").val());
                    data.append("txtdetallet", $("#txtdetallet").val());
                    data.append("idserie", idserie);
                    axios.post("/cajas/registrarTransferencia", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "La transferencia se realizó correctamente",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiartransferencia();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", "Mensaje del Sistema");
        }
    }

    function validarTransferencia() {
        if ($("#txtnumerodocumentot").val().trim() == "") {
            return false;
        }
        if ($("#txtfechat").val() == "") {
            return false;
        }
        if ($("#cmbformapagot").val() == "") {
            return false;
        }
        if ($("#txtsaldot").val() == "") {
            return false;
        }
        if ($("#txtimportet").val() == "") {
            return false;
        }
        return true;
    }

    function limpiartransferencia() {
        $("#txtnumerodocumentot").val("");
        $("#txtfechat").val("<?php echo date('Y-m-d') ?>");
        $("#cmbformapagot").val("D");
        $("#txtsaldot").val("");
        $("#txtimportet").val("");
        $("#txtdetallet").val("");
        $('#btngrabar').removeAttr('disabled')
    }

    function imprimiringreso() {
        nombrepdf = 'ticket.pdf';
        var params = "documento=" + $("#txtnumerodocumentoi").val() + '&rutapdf=' + nombrepdf;
        var xhr = new XMLHttpRequest();
        var cruta = '/caja/imprimirticketingresoyegreso';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

    function imprimiregreso() {
        nombrepdf = 'ticket.pdf';
        var params = "documento=" + $("#txtnumerodocumentoe").val() + '&rutapdf=' + nombrepdf;
        var xhr = new XMLHttpRequest();
        var cruta = '/caja/imprimirticketingresoyegreso';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }
</script>
<?php
$this->endSection('javascript');
?>