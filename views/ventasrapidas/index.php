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
<div class="modal fade" id="mdpreregistro" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Pre Registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '';
                        $dctos = new DocumentoComponent($ctdoc);
                        echo $dctos->render();
                        ?> <br>
                    </div>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="VENTAS DEL DIA">
                            <input type="hidden" id="txtidcliente" value="2">
                            <input type="hidden" id="txtruccliente" value="">
                            <input type="hidden" id="txtdireccion" value="">
                            <input type="hidden" id="txtdnicliente" value="">
                            <input type="hidden" id="txtidauto" value="">
                            <input type="hidden" id="txtclienteretencion" value="">
                            <input type="hidden" id="txtcreditocliente" value="<?php echo isset($datosclientev['txtcreditocliente']) ?  $datosclientev['txtcreditocliente'] : 0 ?>">
                            <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                            <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                        </div><br>
                    </div>
                    <div class="col-sm-12">
                        <?php
                        $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : '';
                        $formapago = new FormadepagoComponent($cforma);
                        echo $formapago->render();
                        ?>
                    </div>
                    <div class="col-sm-12">
                        <input type="text" oninput="this.value = this.value.toUpperCase();" class="form-control form-control-sm" onkeypress="enterreferencia(event);" name="txtreferencia" placeholder="INGRESE SU PLACA (CAMPO EXCLUSIVO PARA REFERENCIA)" id="txtreferencia" value="<?php echo (isset($datosclientev['txtreferencia']) ? $datosclientev['txtreferencia'] : '') ?>">
                    </div><br><br>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <label class="form-control form-control-sm" for="">TOTAL:</label>
                            <input type="text" class="form-control form-control-sm" id="txttotal" value="0.00" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6" style="display:none;">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">VUELTO:</label>
                            <input type="text" class="form-control form-control-sm" id="txtvuelto" value="0.00" readonly>
                        </div>
                    </div><br><br>
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">PAGO:</label>
                            <input type="text" onclick="this.select(); disabledbtngrabar();" onkeypress="enterpago(event); return isNumber(event)" onkeyup="calcularvuelto();" value="0.00" class="form-control form-control-sm" id="txtpago">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="form-control form-control-sm" for="">EFECTIVO:</label>
                            <input type="text" onclick="this.select();" readonly onkeypress="enterEfectivo(event); return isNumber(event)" onkeyup="calcularvuelto();" class="form-control form-control-sm" value="0.00" id="txtefectivo">
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <div class="input-group">
                            <label class="col-sm-0 col-form-label col-form-label-sm">Guía R. :</label>
                            <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-00001">
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <?php
                        $cempresa = isset($datosclientev['almv']) ? $datosclientev['almv'] : $_SESSION['idalmacen'];
                        $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                        echo $empresa->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display:none;">
                        <?php
                        $fecha = new FechaComponent();
                        echo $fecha->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display:none">
                        <?php
                        $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : '';
                        $tpmoneda = new TipoMonedaComponent($cmon);
                        echo $tpmoneda->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display: none" ;>
                        <?php
                        $dfechavto = new FechavtoComponent();
                        echo $dfechavto->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display: none;">
                        <?php
                        $nidv = isset($datosclientev['idvenv']) ? $datosclientev['idvenv'] : 0;
                        $vendedor = new VendedorComponent($nidv);
                        echo $vendedor->render();
                        ?>
                    </div>
                    <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                    <div class="col-sm-6" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                        <?php
                        $optigv = isset($datosclientev['optigv']) ? $datosclientev['optigv'] : '';
                        $igv = new IGVComponent($optigv);
                        echo $igv->render();
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white" id="btngrabar" onclick="registrarvta();">Guardar</button>
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
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
        listardetalle();
        $("#txtfecha").val("<?php echo date("Y-m-d"); ?>")
        $("#txtdias").val("0")
        $("#modal_productos").modal('show');
        $("#txtbuscarProducto").val(" ");
        $("#cmdbuscarP").click()
        $("#cmbdcto").val("03");
        provieneopcion = "carrito";
        ubicacionfocus = "cantidad";
    }

    function obtenertipobusquedaProducto() {
        vdvto = 'X';
        return vdvto;
    }

    function listardetalle() {
        axios.get('/ventasrapidas/listardetalle').then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            $(".codigo").css("display", "none");
            calcularIGV();
            $(".tipodocumentos option[value='07']").remove();
            $(".tipodocumentos option[value='08']").remove();
            $(".tipodocumentos option[value='GI']").remove();
            $('input[name="optradiosP"]').css('display', 'none');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function entertipodocumento(u) {
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressedtdoc == 0) {} else if (enterPressedtdoc >= 1) {
                    e.preventDefault();
                    tipodocumento = $(u).val();
                    txtidcliente = $("#txtidcliente").val();
                    // if ((tipodocumento == '20') && (txtidcliente == '2')) {
                    //     registrarvta();
                    // } else {
                    // }
                    $("#mdpreregistro").modal('hide');
                    seleccionarTipoBusquedaCliente(tipodocumento);
                    $("#modal_clientes").modal('show');
                }
                enterPressedtdoc++;
                return;
            }
        };
    }

    function enterformadepago(u) {
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressedforma == 0) {} else if (enterPressedforma >= 1) {
                    e.preventDefault();
                    $("#txtreferencia").click();
                    $("#txtreferencia").select();
                }
                enterPressedforma++;
                return;
            }
        };
    }

    // var code = e.keyCode || e.which;
    // if (code == 46) {
    //     $("#searchP").empty();
    //     $("#txtbuscarProducto").select();
    // }

    // $("input[type='search']").val("Ventas x Producto");

    $('#modal_clientes').on('hidden.bs.modal', function() {
        grabarCabecera();
        $("#mdpreregistro").modal('show');
        provieneopcion = 'clientes';
    });

    $('#mdpreregistro').on('shown.bs.modal', function() {
        let formaPago = $("#cmbforma").val();
        if (formaPago === "E") {
            $("#txttxtefectivo").attr("readonly");
        }
        total = $("#total").val();
        $("#txttotal").val(total);
        if (provieneopcion == 'carrito') {
            $("#cmbdcto").focus();
            $("#cmbdcto").click();
            enterPressedtdoc = 1;
        } else {
            enterPressedforma = 1;
            $("#cmbforma").focus();
            $("#cmbforma").click();
            // $("#txtreferencia").focus();
            // $("#txtreferencia").click();
        }
    });

    function entergrabar(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            registrarvta();
        }
    }

    function enterreferencia(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            $("#txtpago").focus();
            $("#txtpago").select();
        }
    }

    function enterpago(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            let formaPago = $("#cmbforma").val();
            if (formaPago === "E") {
                registrarvta();
            } else {
                $("#txtefectivo").removeAttr("readonly");
                $("#txtefectivo").focus();
                $("#txtefectivo").select();
            }
        }
    }

    // $('body').on('keyup', function(e) {
    //     if (e.shiftKey) {
    //         $("#modal_productos").modal('show');
    //     }
    // });

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    function verutilidad() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    // function consultarlogin() {
    //     data = new FormData();
    //     data.append("txtUsuario", document.getElementById("txtUsuario").value);
    //     data.append("txtPassword", document.getElementById("txtPassword").value);
    //     axios.post("/vtas/verutilidad", data)
    //         .then(function(respuesta) {
    //             Swal.fire({
    //                 title: "La ganancia calculada correctamente",
    //                 text: "La ganancia es: " + respuesta.data.message,
    //                 icon: "success"
    //             });
    //             $("#divutilidad").css("display", "");
    //             $("#txtutilidad").val(respuesta.data.message);
    //             $("#modalConfirmarLogin").modal("hide");
    //         }).catch(function(error) {
    //             if (error.hasOwnProperty("response")) {
    //                 if (error.response.status == 422) {
    //                     toastr.error(error.response.data.message, 'Mensaje del sistema');
    //                 }
    //             } else {
    //                 toastr.error("Ocurrió un error" + error, 'Mensaje del sistema')
    //             }
    //         });
    // }

    $(document).on('keydown', function(event) {
        if (event.key == "Escape") {
            $("#modal_productos").modal('hide');
        }
    });

    function quitaritem(pos, idart) {
        btnagregar = "#agregar" + idart;
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/ventasrapidas/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $(btnagregar).removeAttr('disabled');
                // $(btnagregar).attr('disabled', false);
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function grabarCabecera() {}

    $("#modal_productos").on("shown.bs.modal", function() {
        // moverCursorFinalTexto("txtbuscarProducto");
        filastbl = document.getElementById("griddetalle tbody").rows.length;
        console.log(filastbl)
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
    });

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
    });

    document.addEventListener('keyup', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            $("#cmdbuscarP").click();
        }
    });

    function enterEfectivo(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            registrarvta();
        }
    }

    // document.addEventListener("keydown", (e) => {
    //     switch (e.key) {
    //         case "Delete":
    //             $("#txtbuscarProducto").focus();
    //             break;
    //         default:
    //             break;
    //     }
    // });

    function agregarunitemVenta(datos) {
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
        data.append("tipoproducto", datos.parametro10);
        data.append("cmbmoneda", $("#cmbmoneda").val());
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/ventasrapidas/agregaritem', data)
            .then(function(respuesta) {
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                idart = "#agregar" + datos.parametro2;
                $(idart).attr('disabled', 'disabled');
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
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
                    }
                }
            });
    }

    $('#modal_productos').on('hidden.bs.modal', function() {
        var a = $("#griddetalle tr:last td:eq(8)");
        $(a).find("input").click();
        $(a).find("input").focus();
        $(a).find("input").select();
        ubicacionfocus = "subtotal";
    });

    function calcularIGV() {
        columnatotal = ($("#griddetalle").find("thead tr:first th").length) - 1;
        igv = obtenerTipoIGV();
        var total_col = 0;
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(columnatotal).find("input").val();
            total_col += parseFloat(t);
        });
        if (igv == 'I') {
            $('#griddetalle tbody tr').each(function() {
                $(this).find(".preciosgv").html("");
            });
        } else {
            $('#griddetalle tbody tr').each(function() {
                precio = $(this).find(".precio input").val();
                preciosgv = precio / Number(valorigv);
                $(this).find(".preciosgv").html(Number(preciosgv).toFixed(2));
            });
        }
        let impo = (Number(total_col)).toFixed(2);
        let valor = (impo / valorigv).toFixed(2);
        let nigv = (impo - valor).toFixed(2);
        $("#igv").val(nigv);
        $("#subtotal").val(valor);
        $("#total").val(impo);
        if (isNaN(impo)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
    }

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

    function verificarvalorescarrito() {
        ubicacionfocus = 'subtotal';
        const detalle = []
        json = "";
        $('#griddetalle tbody tr').each(function() {
            var _tr = $(this);
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();
            json += ',"id":"' + id + '"'
            json += ',"cant":"' + cant + '"'
            json += ',"precio":"' + precio + '"'
            obj = JSON.parse('{' + json.substr(1) + '}');
            calcularsubtotal(_tr)
            detalle.push(obj)
        });
        return detalle;
    }

    function preregistro() {
        importe = $("#total").val();
        if (importe == "0.00") {
            toastr.info("Agregue productos a la venta", 'Mensaje del Sistema');
            return;
        }
        $("#mdpreregistro").modal('show');
    }

    function registrarvta() {
        $("#btngrabar").attr("disabled", true);
        if (!validarVenta()) {
            $("#btngrabar").removeAttr("disabled");
            return;
        }
        calcularIGV();
        valorescarrito = verificarvalorescarrito();
        const data = new FormData();
        data.append("detalle", JSON.stringify(valorescarrito));
        axios.post('/vtas/verificarvalorescarrito', data)
            .then(function(respuesta) {
                rpta = respuesta.data.estado;
                if (rpta == '1') {
                    total = $("#total").val();
                    cmensaje = '¿Registrar Venta? <br>' + 'El monto total es: ' + 'S/ ' + total;
                    grabar(cmensaje);
                } else {
                    toastr.warning("Ocurrió un error de conexión a internet. Vuelva a intentarlo", 'Mensaje del Sistema')
                }
            }).catch(function(error) {
                toastr.warning("Ocurrió un error de conexión a internet. Vuelva a intentarlo", 'Mensaje del Sistema')
                console.log(error);
            });
    }

    function changedetaildolar() {
        moneda = $("#cmbmoneda").val();
        const data = new FormData();
        data.append('moneda', moneda);
        axios.post('/detail/changedolar', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $("#cmbmoneda").attr('disabled', true);
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                } else {
                    toastr.error("Ocurrió un error" + error, 'Mensaje del sistema')
                }
            });
    }

    function changeoptigv() {
        $(".igv").attr('disabled', true);
    }

    function limpiardatos() {
        $("#cmbmoneda").attr('disabled', false);
        $("#txtcliente").val("VENTAS DEL DÍA");
        $("#txtdias").val("0");
        $("#titulo").val("Venta Rápida");
        $("#txtidcliente").val("2");
        $("#txtefectivo").attr("readonly");
        $("#txtruccliente").val("");
        $("#txtcreditocliente").val("0")
        $("#ndo2").val("");
        $("#cmbforma").val("E");
        // $("#cmbAlmacen").val("1");
        $("#cmbmoneda").val("S");
        $("#optigv").val("I");
        // $("#cmbvendedor").val("1");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        $("#txtreferencia").val("");
        $("#searchP").empty();
        $("#btngrabar").removeAttr("disabled");
        provieneopcion = "carrito";
        document.getElementById("grabar").innerHTML = "Grabar";
    }

    function grabar(cmensaje) {
        $("#btngrabar").attr("disabled", "disabled");
        txtpago = $("#txtpago").val();
        txtefectivo = $("#txtefectivo").val();
        total = $("#total").val();
        if (Number(total) < (Number(txtpago) + Number(txtefectivo))) {
            toastr.error("No se puede cancelar más del total de venta", 'Mensaje del Sistema')
        }
        data = new FormData();
        data.append("idcliev", $("#txtidcliente").val());
        data.append("razov", $("#txtcliente").val());
        data.append("tdocv", $("#cmbdcto").val());
        data.append("txtdireccion", $("#txtdireccion").val());
        data.append("txtruccliente", $("#txtruccliente").val());
        data.append("txtdnicliente", $("#txtdnicliente").val());
        data.append("ndo2v", $("#ndo2").val());
        data.append("almv", $("#cmbAlmacen").val());
        data.append("fechv", $("#txtfecha").val());
        data.append("monev", $("#cmbmoneda").val());
        data.append("formv", $("#cmbforma").val());
        let [anio, mes, dia] = $("#txtfechavto").val().split('-').map(Number);
        let fecha = new Date(anio, mes - 1, dia);
        fecha.setDate(fecha.getDate() + 1);
        let nuevaFechaString = fecha.toISOString().split('T')[0];
        // console.log(nuevaFechaString);
        data.append("fechvv", nuevaFechaString);
        let tigv = obtenerTipoIGV();
        data.append("optigv", tigv);
        data.append("idvenv", $("#cmbvendedor").val());
        data.append("subtotal", $("#subtotal").val());
        data.append("igv", $("#igv").val());
        data.append("total", total);
        data.append("txtpago", txtpago);
        data.append("txtefectivo", txtefectivo);
        data.append("txtreferencia", $("#txtreferencia").val());
        data.append("txtcreditocliente", $("#txtcreditocliente").val())
        axios.post("/vtas/registrar", data)
            .then(function(respuesta) {
                $("#mdpreregistro").modal('hide');
                $("#btngrabar").removeAttr("disabled");
                toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                $('#griddetalle tbody tr').remove();
                var cruta = '/vtas/imprimirdirecto/';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', cruta, true);
                xhr.responseType = 'blob';
                xhr.onload = function(e) {
                    if (this.status == 200) {
                        var w = screen.width;
                        url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                        if (w <= 800) {
                            var req = new XMLHttpRequest();
                            req.open("GET", url, true);
                            req.responseType = "blob";
                            req.onload = function(event) {
                                var blob = req.response;
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
                limpiardatos();
            }).catch(function(error) {
                mostrarerroresvalidacion(error);
                console.log(error);
                $("#btngrabar").removeAttr("disabled");
            });
    }

    const onFocus = () => {
        $("#exampleModal").modal('hide');
        $("#mdpreregistro").modal('hide');
        listardetalle();
        $('#modal_productos').modal('show');
        if ($("#tabla_productos tbody tr").length === 0) {
            $("#txtbuscarProducto").val(" ");
            $("#cmdbuscarP").click();
        }
    }
    window.addEventListener("focus", onFocus)

    function calcularvuelto() {
        let total = parseFloat($("#txttotal").val()) || 0;
        let montoPago = 0;
        let formaPago = $("#cmbforma").val();
        if (formaPago === "E") {
            montoPago = parseFloat($("#txtpago").val()) || 0;
        } else {
            montoPago = parseFloat($("#txtefectivo").val()) || 0;
        }
        let vuelto = montoPago - total;
        if (vuelto < 0) {
            vuelto = 0;
        }
        $("#txtvuelto").val(vuelto.toFixed(2));
    }

    function cancelarVenta() {
        axios.post('/ventasrapidas/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            window.location.href = '/ventasrapidas/index';
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
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

    var txtreferencia = document.getElementById("txtreferencia");
    txtreferencia.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    $("#cmbvendedor").on("change", function() {
        grabarCabecera();
    });
</script>
<?php
$this->endSection("javascript");
?>