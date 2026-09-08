<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php

use App\View\Components\ModalDestinatarioComponent;
use App\View\Components\ModalDirecciones;
use App\View\Components\ModalImprimir;
use App\View\Components\ModalRemitentesComponent;
use App\View\Components\ModalVehiculoComponent;
?>
<?php
$prov = new ModalRemitentesComponent();
echo $prov->render();
$clie = new ModalDestinatarioComponent();
echo $clie->render();
$ve = new ModalVehiculoComponent();
echo $ve->render();
$odirecciones = new ModalDirecciones();
echo $odirecciones->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12"><br>
                <div class="card">
                    <div class="card-header">
                        Datos generales
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <div class="col">
                                <div class="form-group row">
                                    <label class="col-sm-0 col-form-label ">Fecha de emisión :</label>
                                    <input type="date" id="txtFechaEmision" class="form-control " value="<?php echo isset($_SESSION['remitente']['fechaEmision']) ?  $_SESSION['remitente']['fechaEmision'] : date("Y-m-d") ?>" style="width:40%;">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row">
                                    <label class="col-sm-0 col-form-label">Fecha de traslado:</label>
                                    <input type="date" id="txtFechaTraslado" class="form-control " value="<?php echo isset($_SESSION['remitente']['fechaTraslado']) ?  $_SESSION['remitente']['fechaTraslado'] : date("Y-m-d") ?>" style="width:45%;">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group row">
                                    <label class="col-sm-0 col-form-label">Referencia :</label>
                                    <input type="text" class="form-control" placeholder="Ingrese referencia" id="txtReferencia" value="<?php echo isset($_SESSION['remitente']['txtReferencia']) ?  $_SESSION['remitente']['txtReferencia'] : '' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Remitente
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="hidden" id="txtIdauto" value="<?php echo isset($_SESSION['remitente']['idauto']) ?  $_SESSION['remitente']['idauto'] : '' ?>">
                                    <input type="hidden" id="txtIdRemitente" value="<?php echo isset($_SESSION['remitente']['idRemitente']) ?  $_SESSION['remitente']['idRemitente'] : '' ?>">
                                    <input type="hidden" id="txtUbigeoRemitente" value="<?php echo isset($_SESSION['remitente']['ubigeoRemitente']) ?  $_SESSION['remitente']['ubigeoRemitente'] : '' ?>">
                                    <input type="hidden" id="txtrucremitente" value="<?php echo isset($_SESSION['remitente']['rucRemitente']) ?  $_SESSION['remitente']['rucRemitente'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" id="txtNombreRemitente" placeholder="Remitente" disabled value="<?php echo isset($_SESSION['remitente']['razo']) ?  $_SESSION['remitente']['razo'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_remitente"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Dirección de Partida" id="txtDireccionRemitente" value="<?php echo isset($_SESSION['remitente']['remitenteDireccion']) ?  $_SESSION['remitente']['remitenteDireccion'] : '' ?>" disabled>
                                    <button class="btn btn-outline-light" role="button" id="abrirDireccion"><i style="color:black" class="fa fa-arrow-down" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Destinatario
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                                <input type="hidden" id="txtIdDestinatario" value="<?php echo isset($_SESSION['destinatario']['idDestinatario']) ?  $_SESSION['destinatario']['idDestinatario'] : '' ?>">
                                <input type="hidden" id="txtUbigeoDestinatario" value="<?php echo isset($_SESSION['destinatario']['ubigDestinatario']) ?  $_SESSION['destinatario']['ubigDestinatario'] : '' ?>">
                                <input type="hidden" id="txtrucDestinatario" value="<?php echo isset($_SESSION['destinatario']['rucDestinatario']) ?  $_SESSION['destinatario']['rucDestinatario'] : '' ?>">
                                <input type="text" class="form-control form-control-sm" id="txtNombreDestinatario" placeholder="Destinarario" disabled value="<?php echo isset($_SESSION['destinatario']['nombre']) ?  $_SESSION['destinatario']['nombre'] : '' ?>">
                                <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_destinatario"><i style="color:black" class="fas fa-user-alt"></i></button>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Dirección de Llegada" id="txtDireccionDestinatario" disabled value="<?php echo isset($_SESSION['destinatario']['destinatarioDireccion']) ?  $_SESSION['destinatario']['destinatarioDireccion'] : '' ?>">
                                <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target=""><i style="color:black" class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Detalle</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th style="display:none">ID</th>
                                        <th style="width: 400px;">Descripcion</th>
                                        <th style="width: 70px;" class="text-center">Cant.</th>
                                        <th style="width: 70px;" class="text-center">Peso kg.</th>
                                        <th style="width: 70px;" class="text-center">Opcion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach ($detalleg as $indice => $item) : ?>
                                        <?php if ($item['activo'] == 'A') { ?>
                                            <tr class="fila">
                                                <td style="display:none" class="controles">
                                                    <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="<?php echo $item['nreg'] ?>">
                                                </td>
                                                <td class="controles">
                                                    <input type="text" name="descripcion1" style="width: 100%;" class="descripcion" id="descripcion1" placeholder="Descripcion" value="<?php echo $item['descri'] ?>">
                                                </td>
                                                <td class="text-center controles">
                                                    <input type="number" name="cantidad1" onkeyup="calcularPesoTotal()" style="width: 70px;" class="cantidad" id="cantidad1" value="<?php echo $item['cant'] ?>">
                                                </td>
                                                <td class="text-center controles">
                                                    <input type="number" name="peso1" onkeyup="calcularPesoTotal()" style="width:70px;" class="peso" id="peso1" value="<?php echo $item['peso'] ?>">
                                                </td>
                                                <td class="text-center">
                                                    <button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" align="center">
                                            <button id="agregar" class="btn btn-success">Adicionar</button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="input-group">
                                <span class="input-group-text">T.Peso Kg.</span>
                                <textarea class="form-control" id="txttpeso" name="txttpeso" aria-label="With textarea" disabled>
                                <?php echo isset($pesot) ?  $pesot : '' ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Vehículo
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="hidden" id="txtIdVehiculo" value="<?php echo isset($_SESSION['vehiculo']['txtIdVehiculo']) ?  $_SESSION['vehiculo']['txtIdVehiculo'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" id="txtChoferVehiculo" placeholder="Chofer" disabled value="<?php echo isset($_SESSION['vehiculo']['txtChoferVehiculo']) ?  $_SESSION['vehiculo']['txtChoferVehiculo'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_vehiculo"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="hidden" id="txtMarca" value="">
                                    <input type="hidden" class="form-control form-control-sm" disabled id="txtPlaca" value="<?php echo isset($_SESSION['vehiculo']['txtPlaca']) ?  $_SESSION['vehiculo']['txtPlaca'] : '' ?>">
                                    <input type="hidden" id="txtBrevete" value="<?php echo isset($_SESSION['vehiculo']['txtBrevete']) ?  $_SESSION['vehiculo']['txtBrevete'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" id="txtplaca" placeholder="Placa" disabled value="<?php echo isset($_SESSION['vehiculo']['txtPlaca']) ?  $_SESSION['vehiculo']['txtPlaca'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" disabled id="txtPlaca1" value="<?php echo isset($_SESSION['vehiculo']['txtPlaca1']) ?  $_SESSION['vehiculo']['txtPlaca1'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_placas"><i style="color:black" class="fa fa-truck"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success" onclick="registrarGuia();">Grabar</button>
                            <button class="btn btn-warning" onclick="limpiar()">Limpiar</button>
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
    td>input {
        height: 20px;
    }

    iframe {
        width: 100%;
    }

    :root {
        --dt-row-selected: 169, 165, 165;
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
        titulo("<?php echo $titulo ?>");
        buscarVehiculo();
    }

    $("#modal_vehiculo").on("hidden.bs.modal", function() {
        axios.get('/vehiculo/listarplacas', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#cargamodal').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
            });
    });

    $('#modal_destinatario').on('shown.bs.modal', function() {
        $('#txtbuscar').focus();
    });

    $('#modal_remitente').on('shown.bs.modal', function() {
        $('#txtbuscarProv').focus();
    });

    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
    });

    $("#agregar").on("click", function() {
        var numTr = $('#tabla tbody tr').length + 1;
        num = numTr - 1
        cdesc = "#descripcion" + num;
        cdesc = $(cdesc).val();
        let npeso = $("#peso" + num).val();
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
             <input type="text" name="descripcion${numTr}" style="width: 100%;" class="descripcion" id="descripcion${numTr}" onkeyup="mayusculas(this)" placeholder="Descripcion">
           </td>
           <td class="text-center controles">
             <input type="number" name="cantidad${numTr}" onkeyup="calcularPesoTotal()" style="width: 70px;" class="cantidad" id="cantidad${numTr}" value="cantidad${numTr}">
           </td>
           <td class="text-center controles">
             <input type="number" name="peso${numTr}" onkeyup="calcularPesoTotal()" style="width: 70px;" class="peso" id="peso${numTr}" value="peso${numTr}">
           </td>
           <td class="text-center">
             <button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button>
           </td>
         </tr>`);
        let cvar = `#descripcion${numTr}`
        $(cvar).focus();
    });

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        $("#tabla tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').val());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.peso').val());
            var pesot = cantidad * peso;
            pesoTotal.push(pesot);
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#txttpeso").text(total.toFixed(2));
        } else {
            $("#txttpeso").text("0.00");
        }
    }

    function agregarprimeriten() {
        $('#tabla tbody')
            .append(`<tr>
            <td style="display:none" class="controles">
                <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="0">
            </td>
           <td class="controles">
             <input type="text" name="descripcion1" style="width: 100%;" class="descripcion" id="descripcion1" onkeyup="mayusculas(this)" placeholder="Descripcion">
           </td>
           <td class="text-center controles">
             <input type="number" name="cantidad1" onkeyup="calcularPesoTotal()" style="width: 70px;" class="cantidad" id="cantidad1" >
           </td>
           <td class="text-center controles">
             <input type="number" name="peso1" onkeyup="calcularPesoTotal()" style="width: 70px;" class="peso" id="peso1">
           </td>
           <td class="text-center">
             <button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button>
           </td>
         </tr>`);
    }

    $(document).on('click', '.borrar', function(event) {
        event.preventDefault();
        $(this).closest('tr').remove();
        calcularPesoTotal();
        txtIdauto = $("#txtIdauto").val();
        if (txtIdauto != '') {
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
            // console.log(arrayEliminados)
            localStorage.setItem("arrayEliminados", arrayEliminados);
        }
    });

    function registrarGuia() {
        txtIdauto = $("#txtIdauto").val();
        if (txtIdauto == '') {
            grabarGuia();
        } else {
            modificarGuia();
        }
    }

    function modificarGuia() {
        calcularPesoTotal();
        if (validar() === false) {
            toastr.error("Faltan datos para modificar", 'Mensaje del Sistema');
            return;
        }
        Swal.fire({
            title: '¿Modificar Guia Transportista?',
            text: "Se guardará con los nuevos datos. ",
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
                var arrayEliminados = localStorage.getItem("arrayEliminados");
                data = new FormData();
                data.append("idRemitente", $("#txtIdRemitente").val());
                data.append("idDestinatario", $("#txtIdDestinatario").val());
                data.append("idVehiculo", $("#txtIdVehiculo").val());
                data.append("txtNombreRemitente", $("#txtNombreRemitente").val());
                data.append("txtNombreDestinatario", $("#txtNombreDestinatario").val());
                data.append("txtDireccionRemitente", $("#txtDireccionRemitente").val());
                data.append("txtDireccionDestinatario", $("#txtDireccionDestinatario").val());
                data.append("txtubigeor", $("#txtUbigeoRemitente").val());
                data.append("txtubigeod", $("#txtUbigeoDestinatario").val());
                data.append("txtPlaca1", $("#txtPlaca1").val());
                data.append("txtPlaca", $("#txtPlaca").val());
                data.append("txtBrevete", $("#txtBrevete").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("detalle", JSON.stringify(detalle));
                data.append("txtIdauto", $("#txtIdauto").val())
                data.append("txtFechaEmision", $("#txtFechaEmision").val());
                data.append("txtReferencia", $("#txtReferencia").val());
                data.append("txtFechaTraslado", $("#txtFechaTraslado").val());
                data.append("arrayEliminados", arrayEliminados);
                axios.post("/guias/modificar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        // var cruta = '/guias/imprimirdirecto/';
                        // var xhr = new XMLHttpRequest();
                        // xhr.open('GET', cruta, true);
                        // xhr.responseType = 'blob';
                        // xhr.onload = function(e) {
                        //     if (this.status == 200) {
                        //         var w = screen.width;
                        //         url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                        //         // console.log(w)
                        //         if (w <= 768) {
                        //             // console.log('hola movil')
                        //             var req = new XMLHttpRequest();
                        //             req.open("GET", url, true);
                        //             req.responseType = "blob";
                        //             req.onload = function(event) {
                        //                 var blob = req.response;
                        //                 // console.log(blob.size);
                        //                 var link = document.createElement('a');
                        //                 link.href = window.URL.createObjectURL(blob);
                        //                 link.download = respuesta.data.ndoc + ".pdf"
                        //                 link.click();
                        //             };
                        //             req.send();
                        //         } else {
                        //             $("#pdfguia").attr("src", url)
                        //             $("#abrirguia").click();
                        //         }
                        //     }
                        // };
                        // xhr.send();
                        // limpiar();
                        // localStorage.removeItem("arrayEliminados");
                        limpiar();
                    }).catch(function(error) {
                        console.log(error);
                        num = error['response']['data']
                        if (num.length == 1) {
                            e = error['response']['data']
                        } else {
                            e = error['response']['data']['errors']
                        }
                        result = []
                        for (var i in e) {
                            result.push([i, e[i]]);
                        }
                        result.forEach(function(numero) {
                            toastr.error(numero[1])
                        });
                    });
            }
        });
    }

    function grabarGuia() {
        calcularPesoTotal();
        if (validar() === false) {
            return;
        }
        Swal.fire({
            title: '¿Registrar Guia Transportista?',
            text: "Se guardará en la base de datos",
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
                data.append("idRemitente", $("#txtIdRemitente").val());
                data.append("idDestinatario", $("#txtIdDestinatario").val());
                data.append("idVehiculo", $("#txtIdVehiculo").val());
                data.append("txtNombreRemitente", $("#txtNombreRemitente").val());
                data.append("txtNombreDestinatario", $("#txtNombreDestinatario").val());
                data.append("txtDireccionRemitente", $("#txtDireccionRemitente").val());
                data.append("txtDireccionDestinatario", $("#txtDireccionDestinatario").val());
                data.append("txtrucremitente", $("#txtrucremitente").val());
                data.append("txtrucDestinatario", $("#txtrucDestinatario").val());
                data.append("txtubigeor", $("#txtUbigeoRemitente").val());
                data.append("txtubigeod", $("#txtUbigeoDestinatario").val());
                data.append("txtPlaca1", $("#txtPlaca1").val());
                data.append("txtPlaca", $("#txtPlaca").val());
                data.append("txtBrevete", $("#txtBrevete").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("txtMarca", $("#txtMarca").val());
                data.append("txtFechaEmision", $("#txtFechaEmision").val());
                data.append("txtFechaTraslado", $("#txtFechaTraslado").val());
                data.append("txtReferencia", $("#txtReferencia").val());
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/guias/registrar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        var cruta = '/guias/imprimirdirecto/';
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
                        limpiar();
                    }).catch(function(error) {
                        console.log(error)
                        e = error['response']['data']['errors']
                        // console.log(e)
                        result = []
                        for (var i in e) {
                            result.push([i, e[i]]);
                        }
                        result.forEach(function(numero) {
                            toastr.error(numero[1])
                        });
                    });
            }
        });
    }

    function validar() {
        idRemitente = $("#txtIdRemitente").val();
        idDestinatario = $("#txtIdDestinatario").val();
        if (idDestinatario == "") {
            toastr.info("Ingrese el Destinatario", 'Mensaje del Sistema')
            return false;
        }
        if (idRemitente == "") {
            toastr.info("Ingrese el Remitente", 'Mensaje del Sistema')
            return false;
        }
        tpeso = document.getElementById("txttpeso").value;
        if (tpeso == "0.00") {
            toastr.info("El peso es obligatorio", 'Mensaje del Sistema')
            return false;
        }
        return true
    }

    function seleccionarRemitentes(id, razo, direccion, ubig, ruc) {
        document.getElementById('txtNombreRemitente').value = razo;
        document.getElementById("txtIdRemitente").value = id;
        document.getElementById("txtDireccionRemitente").value = direccion;
        document.getElementById("txtUbigeoRemitente").value = ubig;
        document.getElementById("txtrucremitente").value = ruc;
        // console.log(ubig)
        idRemitente = id;
        axios.get('/remitente/seleccionar', {
            "params": {
                'idRemitente': idRemitente,
                'razo': razo,
                'remitenteDireccion': direccion,
                'ubigeoRemitente': ubig,
                'rucRemitente': ruc
            }
        }).then(function(respuesta) {
            $('#modal_remitente').modal('toggle');
            axios.get('/direccion/lista1', {
                "params": {
                    'idremitente': id
                }
            }).then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                // console.log(contenido_tabla);
                $('#lista').html(contenido_tabla);
                $("#modal_direcciones").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el listado de direcciones' + error, 'Mensaje del sistema')
            });
        }).catch(function(error) {
            $('#modal_remitente').modal('toggle');
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    $("#abrirDireccion").on("click", function() {
        // data-toggle="modal" data-target="#modal_direcciones"
        id = $("#txtIdRemitente").val()
        if (id == '') {
            toastr.error("Seleccione un remitente")
            return
        }
        axios.get('/direccion/lista1', {
            "params": {
                'idremitente': id
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#lista').html(contenido_tabla);
            $("#modal_direcciones").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado de direcciones' + error, 'Mensaje del sistema')
        });
    });

    function limpiar() {
        document.getElementById('txtNombreRemitente').value = "";
        document.getElementById('txtNombreDestinatario').value = "";
        document.getElementById('txtIdDestinatario').value = "";
        document.getElementById("txtIdRemitente").value = "";
        document.getElementById("txtDireccionRemitente").value = "";
        document.getElementById("txtDireccionDestinatario").value = "";
        document.getElementById("txtIdauto").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txtPlaca").value = "";
        document.getElementById("txtplaca").value = "";
        document.getElementById("txtPlaca1").value = "";
        document.getElementById("txtBrevete").value = "";
        $('#tabla tbody tr').remove();
        agregarprimeritem();
        $("#txttpeso").text("0.00");
        $("#descripcion1").focus();
        <?php
        session()->set('remitente', []);
        session()->set('destinatario', []);
        session()->set('vehiculo', []);
        ?>
    }

    function seleccionarDireccion(dire, ciud, ubig) {
        document.getElementById("txtDireccionRemitente").value = dire.trimEnd() + ' ' + ciud.trimEnd()
        document.getElementById("txtUbigeoRemitente").value = ubig;
        idRemitente = $("#txtIdRemitente").val()
        razo = $("#txtNombreRemitente").val()
        direccion = dire.trimEnd() + ' ' + ciud.trimEnd() + ' ' + ubig.trimEnd()
        ubigeo = ubig
        axios.get('/remitente/seleccionar', {
            "params": {
                'idRemitente': idRemitente,
                'razo': razo,
                'remitenteDireccion': direccion,
                'ubigeoRemitente': ubigeo,
            }
        }).then(function(respuesta) {
            $("#modal_direcciones").modal('hide');
        }).catch(function(error) {
            $("#modal_direcciones").modal('hide');
            toastr.error('Ocurrió un error' + error, 'Mensaje del sistema');
        });
    }
</script>
<?php
$this->endSection('javascript');
?>