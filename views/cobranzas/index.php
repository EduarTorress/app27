<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalDetalleDctoComponent;
use App\View\Components\TipoMonedaComponent;

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
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group ">
                                <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                                <input type="hidden" id="txtidcliente" value="0">
                                <input type="hidden" id="txtruccliente" value=" ">
                                <input type="hidden" id="txtdireccion" value="">
                                <input type="hidden" id="txtdnicliente" value=""> <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                                <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divec" role="tab" aria-selected="true">Vencimientos x Cliente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#divestadocuenta" role="tab" aria-selected="false">Estado de Cuenta</a>
                        </li>
                        <!--   <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divsp" role="tab" aria-selected="false">Saldos Pendientes</a>
                        </li> -->
                    </ul>
                    <div class="tab-content" id="">
                        <div class="tab-pane fade show active" id="divec" role="tabpanel"><br>
                            <div class="row">
                                <div class="col-6 form-inline">
                                    <label class="my-1 mr-2" for="">Inicio:</label>
                                    <input type="date" class="form-control form-control-sm" value="2025-01-01" id="txtfechai" name="txtfechai"> &nbsp;
                                    <label class="my-1 mr-2" for="">Hasta:</label>
                                    <input type="date" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" id="txtfechaf" name="txtfechaf"> &nbsp;
                                    <button class="btn btn-success" onclick="listarvtos();">Listar Vencimientos</button>
                                    <button class="btn btn-outline-warning" onclick="consultardocumentoscondetalle();">Exportar Créditos</button>
                                </div>
                                <div class="col-6">
                                </div>
                            </div>
                            <br><br>
                            <div id="resultadosvtos"></div>
                        </div>
                        <div class="tab-pane fade" id="divestadocuenta" role="tabpanel"><br>
                            <div class="row">
                                <div class="col-6 form-inline">
                                    <?php
                                    $ec = new EmpresaComponent('');
                                    echo $ec->render();
                                    ?>
                                    &nbsp;&nbsp;
                                    <label class="my-1 mr-2" for="">Moneda:</label>
                                    <select name="select" class="form-control form-control-sm" id="cmbmoneda">
                                        <option value="S" selected>Soles</option>
                                        <option value="D">Dólares</option>
                                    </select>
                                    <button class="btn btn-success" onclick="listarestadocuenta();">Consultar Estado de Cuenta</button>
                                </div>
                            </div>
                            <br><br>
                            <div id="resultadosec"></div>
                        </div>
                        <!--  <div class="tab-pane fade" id="divsp" role="tabpanel"><br>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_cancelacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Cancelación de Documentos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $formapago = new FormadepagoComponent('E');
                echo $formapago->render();
                ?>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="nombre">Documento:</label>
                    <div class="col-sm-8">
                        <input type="text" name="txtdocumento" id="txtdocumento" class="form-control" onkeyup="mayusculas(this)" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="nombre">Fecha:</label>
                    <div class="col-sm-8">
                        <input type="date" name="txtfecha" id="txtfecha" class="form-control" onkeyup="mayusculas(this)" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="nombre">Importe:</label>
                    <div class="col-sm-8">
                        <input type="text" name="txtimporte" id="txtimporte" class="form-control" onkeyup="mayusculas(this)" readonly value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" onclick="grabartransacciones();" class="btn btn-success">Registrar</button>
            </div>
        </div>
    </div>
</div>
<?php
$md = new ModalDetalleDctoComponent();
echo $md->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
    }

    function seleccionarcliente(datos) {
        razon = datos.parametro2
        razon = razon.replace('"', '')
        document.getElementById('txtcliente').value = razon;
        document.getElementById("txtidcliente").value = datos.parametro1;
        document.getElementById("txtruccliente").value = datos.parametro3;
        document.getElementById("txtdireccion").value = datos.parametro5;
        document.getElementById("txtdnicliente").value = datos.parametro4;
        $('#modal_clientes').modal('toggle');
    }

    function mostrardatoscliente() {
        txtruccliente = $("#txtruccliente").val();
        txtdireccion = $("#txtdireccion").val();
        txtdnicliente = $("#txtdnicliente").val();
        Swal.fire("RUC: " + txtruccliente + ". <br> DNI: " + txtdnicliente + ". <br>DIRECCIÓN: " + txtdireccion + ".");
    }

    $('#modal_clientes').on('shown.bs.modal', function() {
        moverCursorFinalTexto("txtbuscar");
        $("#txtbuscar").click();
        $("#txtbuscar").focus();
        $("#txtbuscar").select();
    });

    function consultardocumentoscondetalle() {
        idcliente = $("#txtidcliente").val();
        if (idcliente == "0") {
            toastr.error("Seleccione el cliente", 'Mensaje del Sistema');
            return;
        }
        fechai = $("#txtfechai").val();
        fechaf = $("#txtfechaf").val();
        var params = "idcliente=" + idcliente + "&txtfechai=" + fechai + "&txtfechaf=" + fechaf;
        var xhr = new XMLHttpRequest();
        var cruta = '/cobranzas/exportardocumentocondetalle';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Sysven-Creditos.pdf";
                link.click();
            }
        };
        xhr.send();
    }

    function listarvtos() {
        idcliente = $("#txtidcliente").val();
        if (idcliente == 0) {
            toastr.warning("Seleccione un Cliente", 'Mensaje del Sistema')
            return;
        }
        axios.get('/cobranzas/listarvtos', {
            "params": {
                "idcliente": idcliente,
                "txtfechai": $("#txtfechai").val(),
                "txtfechaf": $("#txtfechaf").val()
            }
        }).then(function(respuesta) {
            $("#resultadosec").empty();
            const contenido_tabla = respuesta.data;
            $('#resultadosvtos').html(contenido_tabla);
            calculartotal();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function listarestadocuenta() {
        idcliente = $("#txtidcliente").val();
        if (idcliente == 0) {
            toastr.warning("Seleccione un Cliente", 'Mensaje del Sistema')
            return;
        }
        axios.get('/cobranzas/listarestadocuenta', {
            "params": {
                "idcliente": idcliente,
                "cmbalmacen": $("#cmbAlmacen").val(),
                "cmbmoneda": $("#cmbmoneda").val(),
            }
        }).then(function(respuesta) {
            $("#resultadosvtos").empty();
            const contenido_tabla = respuesta.data;
            $('#resultadosec').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function calculartotal(e) {
        total_col = 0;
        $('#tabla tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(13).find("input").val();
            total_col += parseFloat(t);
        });
        if (isNaN(total_col)) {
            total_col = 0;
        }
        $("#tabla tfoot tr th:eq(13)").find(".th-inner").text(total_col);
    }

    function verificarvalor() {
        $('#tabla tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(13).find("input").val();
            if (t.trim() == ' ' || t.trim() == '' || t.length == 0) {
                $(this).find('td').eq(13).find("input").val("0")
            }
        });
    }

    function limpiartodo() {
        $('#tabla tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(13).find("input").val();
            $(this).find('td').eq(13).find("input").val("0");
        });
        calculartotal();
    }

    function openmodal() {
        if (validar() == false) {
            toastr.error("Debe ingresar los saldos a cancelar", 'Mensaje del sistema');
            return;
        }
        $("#modal_cancelacion").modal('show');
        total_col = 0;
        $('#tabla tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(13).find("input").val();
            total_col += parseFloat(t);
        });
        $("#txtimporte").val(total_col);
    }

    $('#modal_cancelacion').on('shown.bs.modal', function() {
        moverCursorFinalTexto("txtdocumento");
        $("#txtdocumento").click();
        $("#txtdocumento").focus();
        $("#txtdocumento").select();
    });

    $('#modal_cancelacion').on('hidden.bs.modal', function() {
        $("#txtdocumento").val("")
    });

    function grabartransacciones() {
        if (validardetallecancelacion() == false) {
            toastr.error("Ingrese todos los campos", 'Mensaje del sistema');
            return;
        }
        Swal.fire({
            title: "¿Desea registrar los saldos ingresados?",
            text: "Se registrará como pagos en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si,proceder'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const detalle = [];
                $("#tabla tbody tr").each(function() {
                    json = "";
                    $(this).find("td:not(.dtr-control)").each(function() {
                        $this = $(this);
                        if ($this.attr("class") == 'cancelar') {
                            json += ',"' + $this.attr("class") + '":"' + $this.find("input").val().trim() + '"'
                        } else {
                            clase = $this.attr("class");
                            clase = clase.replace("d-lg-none ", "");
                            json += ',"' + clase + '":"' + $this.text().trim() + '"';
                        }
                    })
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                data = new FormData();
                data.append("txtdocumento", $("#txtdocumento").val());
                data.append("txtimporte", $("#txtimporte").val());
                data.append("txtfecha", $("#txtfecha").val());
                data.append("cmbforma", $("#cmbforma").val())
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/cobranzas/registrarcobranzas", data)
                    .then(function(respuesta) {
                        // console.log(respuesta)
                        limpiartodo();
                        $("#modal_cancelacion").modal('hide')
                        Swal.fire({
                            title: "Transacción registrada",
                            text: "Se generaron los pagos correctamente",
                            icon: "success"
                        });
                        listarvtos();
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            toastr.error("Error al registrar" + error, "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function validar() {
        total_col = 0;
        $('#tabla tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(13).find("input").val();
            total_col += parseFloat(t);
        });
        if (isNaN(total_col)) {
            total_col = 0;
        }
        if (total_col == 0) {
            return false;
        }
        return true;
    }

    function validardetallecancelacion() {
        documento = $("#txtdocumento").val();
        if (documento.length == 0 || documento == '' || documento == ' ') {
            return false;
        }
        return true;
    }

    function consultardetalle(idauto, ndoc) {
        axios.get('/cobranzas/consultardetalleventa', {
            "params": {
                "idauto": idauto
            }
        }).then(function(respuesta) {
            detalle = respuesta.data.listado;
            $("#tbldetalle tbody").empty();
            var subtotal = 0;
            var total = 0;
            detalle.forEach(function(d) {
                $("#lblmodaldetalle").text("Detalle");
                subtotal = Number(d.cant) * Number(d.prec);
                var tr = `<tr> 
                        <td>` + d.descri + `</td>
                         <td>` + d.unid + `</td>
                        <td>` + d.cant + `</td>
                        <td>` + d.prec + `</td>
                        <td>` + subtotal.toFixed(2) + `</td>
                        </tr>`;
                total = total + subtotal;
                $('#tbldetalle tbody').append(tr);
            });
            $("#txtimportemodal").val("S/ " + total)
            $("#modaldetalle").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>