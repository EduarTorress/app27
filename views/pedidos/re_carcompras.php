<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\IGVComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalConfirmarLoginComponent;

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
            <div class="row mb-2">
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <input type="text" id="numeropedido" style="display:none;" value="<?php echo isset($nropedido) ? $nropedido : '' ?> ">
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value=" <?php echo isset($datoscliente['nombre']) ?  $datoscliente['nombre'] : ' ' ?>">
                        <input type="hidden" id="txtidcliente" value="<?php echo isset($datoscliente['idcliente']) ?  $datoscliente['idcliente'] : '0' ?> ">
                        <input type="hidden" id="txtruccliente" value="<?php echo isset($datoscliente['txtruccliente']) ?  $datoscliente['txtruccliente'] : '0' ?>">
                        <input type="hidden" id="txtdireccion" value="<?php echo isset($datoscliente['txtdireccion']) ?  $datoscliente['txtdireccion'] : '0' ?>">
                        <input type="hidden" id="txtdnicliente" value="<?php echo isset($datoscliente['txtdnicliente']) ?  $datoscliente['txtdnicliente'] : '0' ?>">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idautop) ? $idautop : 0 ?>">
                        <input type="hidden" id="txtclienteretencion" value="<?php echo isset($datoscliente['clienteretencion']) ?  $datoscliente['clienteretencion'] : 'N' ?>">
                        <button class="btn btn-outline-light" style="color:black" role="button" id="" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i class="fas fa-user-alt"></i></button>
                        <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $nidv = isset($datoscliente['idven']) ? $datoscliente['idven'] : 0;
                    $vendedor = new VendedorComponent($nidv);
                    echo $vendedor->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datoscliente['tdoc']) ? $datoscliente['tdoc'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cforma = isset($datoscliente['form']) ? $datoscliente['form'] : 'E';
                    $formapago = new FormadepagoComponent($cforma);
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datoscliente['mone']) ? $datoscliente['mone'] : '';
                    $tpmoneda = new TipoMonedaComponent($cmon);
                    echo $tpmoneda->render();
                    ?>
                </div>
                <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                <div class="col-sm-1" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                    <?php
                    $optigvp = isset($datoscliente['optigvp']) ? $datoscliente['optigvp'] : '';
                    $igv = new IGVComponent($optigvp);
                    echo $igv->render();
                    ?>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline" style="width:max-content; width:auto;">
                            <div class="col-12" id="pedido">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="item" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-contenido">
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
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='GI']").remove();
        facturardolares = "<?php echo (!empty($_SESSION['config']['facturardolares']) ? $_SESSION['config']['facturardolares'] : 'N'); ?>";
        if (facturardolares == 'N') {
            $(".cmbmoneda option[value='D']").remove();
        }
        calcularIGV();
        // var url = "http://130.10.1:8087/pruebas/downloadFile/archivo.txt";
        const arr = window.location.href.split('/')
        if (arr[arr.length - 2] === 'buscarpedido') {
            //Si hay numero=false
            //si hay letras=true
            // console.log(isNaN(arr[arr.length - 1]))
            if (isNaN(arr[arr.length - 1]) == true) {
                // $("#cancelar").trigger("click");
                cancelarpedido();
                window.location.href = '/pedidos/listarpedido';
            }
        }
        idcliente = 0;
        titulo("<?php echo $titulo ?>");
        axios.get('/pedidos/listarcarrito').then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#pedido').html(contenido_tabla);
            $('#totalpedido').html(document.querySelector("#total").value);
            <?php if ($_SESSION['monedap'] == 'SI') : ?>
                $("#cmbmoneda").attr('disabled', true);
            <?php endif; ?>
            <?php if ($multiigv == 'N') : ?>
                $(".preciosgv").css("display", "none");
            <?php endif; ?>
            <?php if ($_SESSION['igvsololectura'] == 'SI') : ?>
                $(".igv").attr('disabled', true);
            <?php endif; ?>
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function cambiaritem(datos) {
        $("#modal_productos").modal('show');
        $("#txtindice").val(datos.parametro11)
    }

    function mostrardatoscliente() {
        txtruccliente = $("#txtruccliente").val();
        txtdireccion = $("#txtdireccion").val();
        txtdnicliente = $("#txtdnicliente").val();
        Swal.fire("RUC: " + txtruccliente + ". <br> DNI: " + txtdnicliente + ". <br>DIRECCIÓN: " + txtdireccion + ".");
    }

    $('#item').on('shown.bs.modal', function() {
        $('#txtcantidad').focus();
        $('#txtcantidad').select();
    });

    $('#item').on('hidden.bs.modal', function() {
        $("#modal_productos").modal("show");
    });

    $('#modal_clientes').on('shown.bs.modal', function() {
        $('#txtbuscar').focus();
        $('#txtbuscar').select();
    });

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("gridpedidos").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementById('codigo').checked) {
            moverCursorFinalTexto("txtbuscarProducto");
            $("#txtbuscarProducto").select();
        }
    });

    // $("input[type='search']").val("Registrar Cotizacion");

    function verutilidad() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/pedido/verutilidad", data)
            .then(function(respuesta) {
                Swal.fire({
                    title: "El costo calculado correctamente",
                    text: "El costo es: " + respuesta.data.message,
                    icon: "success"
                });
                $("#divutilidad").css("display", "");
                $("#txtutilidad").val(respuesta.data.message);
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message, 'Mensaje del Sistema');
                    }
                }
            });
    }

    function changedetaildolar() {
        $("#cmbmoneda").attr('disabled', true);
        moneda = $("#cmbmoneda").val();
        const data = new FormData();
        data.append('monedap', moneda);
        axios.post('/detail/changedolarp', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#pedido').html(contenido_tabla);
                calcularIGV();
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                }
            });
    }

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/pedidos/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#pedido').html(contenido_tabla);
                $('#totalpedido').html(document.querySelector("#total").value);
                toastr.success("Eliminado satisfactoriamente", 'Mensaje del Sistema');
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function cancelarpedido() {
        <?php session()->set('monedap', 'NO'); ?>
        axios.post('/pedidos/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#pedido').html(tabla);
            $('#totalpedido').html("");
            limpiardatos();
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    function grabarpedido() {
        if (!validarpedido()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidauto').value == '0') {
            cmensaje = '¿Registrar Pedido?';
            guardarpedido(cmensaje);
        } else {
            cmensaje = '¿Actualizar  Pedido? ';
            actualizarpedido(cmensaje);
        }
    }

    function editaritem(datos) {
        var item = datos.parametro11;
        const url = '/pedidos/itemdetalle/' + item;
        axios.get(url)
            .then(function(respuesta) {
                $("#modal-contenido").html(respuesta.data);
                document.querySelector("#item").value = item;
                $("#item").modal('show');
            }).catch(function(error) {
                toastr.error('Error al Cargar Detalle de Item' + error, 'Mensaje del sistema');
            })
    }

    function actualizaritem() {
        var precio = document.querySelector("#txtprecio").value;
        var cantidad = document.querySelector("#txtcantidad").value;
        const data = new FormData();
        data.append("txtprecio", document.querySelector("#txtprecio").value);
        data.append("txtcantidad", document.querySelector("#txtcantidad").value);
        data.append("indice", document.querySelector("#item").value);
        data.append("precio1", document.querySelector("#precio1").value);
        // data.append("precio2", document.querySelector("#precio2").value);
        data.append("precio3", document.querySelector("#precio3").value);
        data.append("cmbmoneda", $("#cmbmoneda").val());
        data.append("stock", document.querySelector("#txtstock").value);
        axios.post('/pedidos/editaritem', data)
            .then(function(respuesta) {
                cerrarventana('detalleitem', '#item')
                const contenido_tabla = respuesta.data;
                $('#pedido').html(contenido_tabla);
                $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        mostrarErrores("detalleitem", error.response.data.errors);
                    }
                }
            });
    }

    function calcularIGV() {
        igv = obtenerTipoIGV();
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        if (igv === 'I') {
            $('#gridpedidos tbody tr').each(function() {
                $(this).find(".preciosgv").html("");
            });
        } else {
            $('#gridpedidos tbody tr').each(function() {
                precio = $(this).find(".precio").html();
                preciosgv = precio / Number(valorigv);
                $(this).find(".preciosgv").html(Number(preciosgv).toFixed(2));
            });
        }
    }

    function changeoptigv() {
        $(".igv").attr('disabled', true);
    }

    function cargarprecios(domElement, array) {
        var select = document.getElementsByName(domElement)[0];
        const $select = document.querySelector("#cmbprecios");
        for (let i = $select.options.length; i >= 0; i--) {
            $select.remove(i);
        }
        for (value in array) {
            var option = document.createElement("option");
            option.text = array[value];
            select.add(option);
        }
    }

    function validarpedido() {
        idcliente = document.querySelector('#txtidcliente').value;
        total = document.querySelector('#total').value;
        ctdoc = document.querySelector('#cmbdcto').value;
        cnruc = document.querySelector('#txtruccliente').value
        if (idcliente == 0) {
            toastr.info("Seleccione un Cliente", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && cnruc.length == 0) {
            toastr.info("El Cliente No tiene RUC", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function guardarpedido(cmensaje) {
        Swal.fire({
            title: cmensaje,
            text: "Se grabará en el sistema. ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let total = document.querySelector("#total").value;
                let cdetalle = document.querySelector("#txtdetalle").value;
                let codv = parseInt(document.getElementById("cmbvendedor").value, 10);
                let cforma = document.getElementById("cmbforma").value;
                let ctdoc = document.getElementById("cmbdcto").value;
                data = new FormData();
                data.append("idcliente", $("#txtidcliente").val());
                data.append("total", total);
                data.append("detalle", cdetalle);
                data.append("codv", codv);
                data.append("forma", cforma)
                data.append("mone", $("#cmbmoneda").val());
                data.append("ctdoc", ctdoc);
                let tigv = obtenerTipoIGV();
                data.append("optigvp", tigv);
                data.append("txtdetallepago", $("#txtdetallepago").val());
                data.append("txtvalidezoferta", $("#txtvalidezoferta").val());
                data.append("txtplazoentrega", $("#txtplazoentrega").val());
                data.append("txtlugarentrega", $("#txtlugarentrega").val());
                axios.post("/pedido/grabarpedido", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#pedido').html(tabla);
                        $('#totalpedido').html(document.querySelector("#total").value);
                        nropedido = document.querySelector("#nropedido").value;
                        limpiardatos();
                        Swal.fire(' Se Genero la Cotización con Número :' + nropedido + ' correctamente ');
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.message, 'Mensaje del sistema');
                            }
                        } else {
                            toastr.error("Error al registrar" + error, "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function agregarunitemVenta(datos) {
        console.log(datos);
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        data.append("txtunidad", datos.parametro3);
        data.append("txtprecio", datos.parametro5);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro6);
        data.append("precio2", datos.parametro7);
        data.append("precio3", datos.parametro5);
        data.append("costo", datos.parametro8);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        // data.append("indice", $("#txtindice").val());
        axios.post('/pedidos/agregaritemrapido', data)
            .then(function(respuesta) {
                //window.location.href = '/vtas/index';
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#pedido').html(contenido_tabla);
                datos.parametro11 = $("#txtindice").val();
                editaritem(datos);
            }).catch(function(error) {
                if (error.response.data.errors) {
                    e = error['response']['data']['errors']
                    result = []
                    for (var i in e) {
                        result.push([i, e[i]]);
                    }
                    result.forEach(function(numero) {
                        toastr.error(numero[1], 'Mensaje del sistema')
                    });
                } else {
                    errors = error.response.data.message;
                    toastr.error(errors, "Mensaje del Sistema")
                }
            });
    }

    function actualizarpedido(cmensaje) {
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
                let total = document.querySelector("#total").value;
                let idauto = document.querySelector("#txtidauto").value;
                let cndoc = document.querySelector("#numeropedido").value;
                let codv = parseInt(document.getElementById("cmbvendedor").value, 10);
                let cforma = document.getElementById("cmbforma").value;
                let ctdoc = document.getElementById("cmbdcto").value;
                let cdetalle = document.querySelector("#txtdetalle").value;
                data = new FormData();
                data.append("idcliente", $("#txtidcliente").val());
                data.append("total", total);
                data.append("idauto", idauto);
                data.append("ndoc", cndoc);
                data.append("codv", codv);
                data.append("forma", cforma);
                data.append("detalle", cdetalle);
                let tigv = obtenerTipoIGV();
                data.append("optigvp", tigv);
                data.append("ctdoc", ctdoc);
                data.append("txtdetallepago", $("#txtdetallepago").val());
                data.append("txtvalidezoferta", $("#txtvalidezoferta").val());
                data.append("txtplazoentrega", $("#txtplazoentrega").val());
                data.append("txtlugarentrega", $("#txtlugarentrega").val());
                axios.post("/pedido/actualizar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#pedido').html(tabla);
                        $('#totalpedido').html(document.querySelector("#total").value);
                        limpiardatos();
                        Swal.fire(' Se actualizo la Cotización correctamente. ');
                        window.location.href = '/pedidos/listarpedido';
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                toastr.error(error.response.data.errors, 'Mensaje del Sistema');
                            }
                        } else {
                            toastr.error("Error al actualizar" + error, "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function limpiardatos() {
        <?php $_SESSION['igvsololectura'] = 'NO'; ?>
        <?php $_SESSION['monedap'] = 'NO'; ?>
        $("#cmbmoneda").attr('disabled', false);
        $(".igv").attr('disabled', false);
        $("#cmbmoneda").val("S");
        document.querySelector('#txtcliente').value = "";
        document.getElementById("titulo").innerHTML = "Registrar Cotización";
        document.querySelector("#txtidcliente").value = "0";
        document.querySelector("#txtidauto").value = "0";
    }

    $(document).keydown(function(event) {
        if (event.keyCode == 88) {
            cerrarventana('detalleitem', '#item')
        }
    });

    function grabarCabecera() {
        data = new FormData();
        data.append("cmbvendedor", $("#cmbvendedor").val());
        data.append("cmbdcto", $("#cmbdcto").val());
        data.append("cmbforma", $("#cmbforma").val());
        data.append("cmbmoneda", $("#cmbmoneda").val());
        let tigv = obtenerTipoIGV();
        data.append("optigvp", tigv);
        axios.post("/pedido/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión", "Mensaje del Sistema");
            });
    }
</script>
<?php
$this->endSection('javascript');
?>