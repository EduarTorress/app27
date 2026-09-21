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
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" data-bs-toggle="modal" data-bs-target="#modal_clientes" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" readonly value="<?php echo isset($datosclientev['razov']) ?  trim($datosclientev['razov']) : '' ?>">
                        <input type="hidden" id="txtidcliente" value="<?php echo isset($datosclientev['idcliev']) ?  $datosclientev['idcliev'] : '0' ?>">
                        <input type="hidden" id="txtruccliente" value="<?php echo isset($datosclientev['ruccliev']) ?  $datosclientev['ruccliev'] : '' ?> ">
                        <input type="hidden" id="txtdireccion" value="<?php echo isset($datosclientev['direcliev']) ?  $datosclientev['direcliev'] : '' ?>">
                        <input type="hidden" id="txtdnicliente" value="<?php echo isset($datosclientev['dnicliev']) ?  $datosclientev['dnicliev'] : '' ?>">
                        <input type="hidden" id="txtclienteretencion" value="<?php echo isset($datosclientev['clienteretencion']) ?  $datosclientev['clienteretencion'] : 'N' ?>">
                        <input type="hidden" id="txtcreditocliente" value="<?php echo isset($datosclientev['txtcreditocliente']) ?  $datosclientev['txtcreditocliente'] : 0 ?>">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idventa) ? $idventa : 0 ?>">
                        <button class="btn btn-outline-primary" id="btnmdclientes" role="button"><i style="color:black" class="fas fa-user-alt"></i></button>
                        <button class="btn btn-outline-success" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '03';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía R. :</label>
                        <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-00001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cempresa = isset($datosclientev['almv']) ? $datosclientev['almv'] : $_SESSION['idalmacen'];
                    $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : 'S';
                    $tpmoneda = new TipoMonedaComponent($cmon);
                    echo $tpmoneda->render();
                    ?>
                </div>
                <div class="col-sm-2">
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
                <div class="col-sm-2" style="display: none;">
                    <?php
                    $nidv = isset($datosclientev['idvenv']) ? $datosclientev['idvenv'] : 0;
                    $vendedor = new VendedorComponent($nidv);
                    echo $vendedor->render();
                    ?>
                    <div class="form-group row" style="display: none">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Vcto. :</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosclientev['fechvV']) ?  date("Y-m-d") :  $datosclientev['fechvV'] ?>" style="width:140px;" id="txtfechav" name="txtfechav">
                    </div>
                </div>
                <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                <div class="col-sm-3" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                    <?php
                    $optigv = isset($datosclientev['optigv']) ? $datosclientev['optigv'] : '';
                    $igv = new IGVComponent($optigv);
                    echo $igv->render();
                    ?>
                </div>
                <?php $ventasanticipado = (empty($_SESSION['config']['ventasanticipado']) ? 'N' : $_SESSION['config']['ventasanticipado']); ?>
                <div class="col-sm-3" style="<?php echo ($ventasanticipado == 'S' ? '' : 'display:none') ?>">
                    <div class="input-group mb-3">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_ventas" id="btnvtasxanticipo">VENTAS POR ANTICIPO</button>
                        <input type="text" id="txtndocvtaanticipo" value="<?php echo empty($ndocanticipado) ? '' : $ndocanticipado ?>" readonly class="form-control form-control-sm">
                        <input type="text" id="txttotalanticipo" style="display:none;" value="<?php echo empty($importeanticipado) ? '0' : $importeanticipado ?>" readonly class="form-control form-control-sm">
                        <input type="text" id="txtidautovtaanticipo" value="<?php echo empty($idautoanticipado) ? '0' : $idautoanticipado ?>" style="display:none;" readonly class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-sm-3">
                </div>
                <div class="col-sm-2" <?php echo ($idventa <> 0 ? ' ' : 'style="display:none;"') ?>>
                    <button class="btn btn-outline-primary btn-sm" id="btnconvertirafactura" role="button" onclick="convertirafactura(<?php echo $idventa ?>);">Convertir Boleta a Factura</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="<?php echo (isset($datosclientev['txtreferencia']) ? trim($datosclientev['txtreferencia']) : '') ?>">
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
<div class="modal fade" id="mddatosapagar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Datos a pagar:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12" id="">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm" for="">PAGO NORMAL:</label>
                        <input type="text" onclick="this.select();" onkeypress="enterpago(event);return isNumber(event)" onkeyup="" class="form-control form-control-sm" value="0.00" id="txtpago">
                    </div>
                </div>
                <div class="col-12" id="">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm" for="">PAGO EFECTIVO:</label>
                        <input type="text" onclick="this.select();" readonly onkeypress="enterefectivo(event);return isNumber(event)" onkeyup="" class="form-control form-control-sm" value="0.00" id="txtefectivo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="grabarVenta();">Guardar</button>
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

    .bloqueado-edicion {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
    }

    .bloqueado-select {
        background-color: #e9ecef !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        pointer-events: none;
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
        // idcliente = $("#txtidcliente").val();
        // buscarVentasPorCliente(idcliente.trim());
        // buscarProducto();
        $("#txtbuscarProducto").val(" ");
        $("#txtfecha").val("<?php echo (empty($datosclientev['fechv']) ? date("Y-m-d") : $datosclientev['fechv']) ?>");
        $("#txtdias").val("<?php echo (empty($datosclientev['dias']) ?  '' :  $datosclientev['dias']) ?>");
        $("#txtfechavto").val("<?php echo (empty($datosclientev['fvto']) ?  date("Y-m-d") :  $datosclientev['fvto']) ?>");
        ubicacionfocus = "cantidad";
    }

    function listardetalle() {
        axios.get('/vtas/listardetalle').then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            $(".codigo").css("display", "none");
            <?php if ($multiigv != 'S') : ?>
                $(".preciosgv").css("display", "none");
            <?php endif; ?>
            ventascondescuento = "<?php echo (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']); ?>";
            if (ventascondescuento == 'N') {
                $(".descuento").css("display", "none");
            }
            // $("#txtfecha").attr("disabled", true)
            $("#cndoc1").val("<?php echo (isset($serie) ?  $serie : '') ?>");
            calcularIGV();
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
            <?php if ($idventa <> 0) : ?>
                $("#cmbdcto").attr('disabled', true);
                bloquearTodoEdicion();
            <?php endif; ?>
            $(".tipodocumentos option[value='GI']").remove();
            $(".tipodocumentos option[value='07']").remove();
            $(".tipodocumentos option[value='08']").remove();
            var enterPressed = 0;
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    $(document).ready(function() {
        idcliente = $("#txtidcliente").val();
        if (idcliente.trim() == '0') {
            $("#txtidcliente").val("2");
            $("#txtcliente").val("VENTAS DEL DÍA (CLIENTE GENERICO)");
            $("#cmbdcto").focus();
            $("#cmbdcto").click();
            enterPressed = 1;
            // $("#modal_clientes").modal("show");
            // $("#txtbuscar").val("VENTAS DEL DIA");
        }
    });

    $('#btnmdclientes').click(function() {
        $("#modal_clientes").modal("show");
    });

    // //PROCESOS PARA VENTAS X ANTICIPO
    // function buscarVentasPorCliente(idCliente) {
    //     axios.get('/vtas/listarvtasxserviciostoanticipo', {
    //         "params": {
    //             "idCliente": idCliente,
    //         }
    //     }).then(function(respuesta) {
    //         const contenido_tabla = respuesta.data;
    //         $('#cargamodal').html(contenido_tabla);
    //     }).catch(function(error) {
    //         toastr.error('Error al cargar el listado' + error, 'Mensaje del Sistema')
    //     });
    // }

    function seleccionarVenta(datos) {
        document.getElementById("txtidautovtaanticipo").value = datos.parametro1;
        document.getElementById("txtndocvtaanticipo").value = datos.parametro4;
        $("#txtanticipado").val(datos.parametro7);
        $("#txttotalanticipo").val(datos.parametro7);
        $("#modal_ventas").modal('hide');
        calcularIGV();
        // buscarDetallePorId(datos.parametro1, datos.parametro9);
    }

    // function buscarDetallePorId(idauto, tipoventa) {
    //     axios.get('/vtas/listardetallenota', {
    //         "params": {
    //             "idauto": idauto,
    //             'tipoventa': tipoventa
    //         }
    //     }).then(function(respuesta) {
    //         const contenido_tabla = respuesta.data;
    //         $('#detalle').html(contenido_tabla);
    //     }).catch(function(error) {
    //         toastr.error('Error al cargar el listado ' + error,'Mensaje del Sistema');
    //     });
    // }

    $("#modal_clientes").on("hidden.bs.modal", function() {
        grabarCabecera();
        <?php
        $ventasanticipado = (empty($_SESSION['config']['ventasanticipado']) ? 'N' : $_SESSION['config']['ventasanticipado']);
        if ($ventasanticipado == 'S') : ?>
            // idCliente = $("#txtidcliente").val();
            // buscarVentasPorCliente(idCliente);
        <?php endif; ?>
        $("#cmbdcto").focus();
        $("#cmbdcto").click();
        enterPressed = 0
    });

    function entertipodocumento(u) {
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    $("#modal_productos").modal("show");
                }
                enterPressed++;
                return;
            }
        };
    }

    // $("input[type='search']").val("Ventas x Producto");
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

    function preregistro() {
        cmbforma = $("#cmbforma").val();
        if (cmbforma == 'T' || cmbforma == 'Y' || cmbforma == 'P' || cmbforma == 'D') {
            $("#mddatosapagar").modal('show');
        } else {
            grabarVenta();
        }
    }

    $('#mddatosapagar').on('shown.bs.modal', function() {
        $("#txtefectivo").val("0.00");
        $("#txtpago").click();
        $("#txtpago").focus();
        $("#txtpago").select();
    });

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/vtas/verutilidad", data)
            .then(function(respuesta) {
                Swal.fire({
                    title: "Ganancia calculada correctamente",
                    text: "La ganancia es: " + respuesta.data.message,
                    icon: "success"
                });
                $("#divutilidad").css("display", "");
                $("#txtutilidad").val(respuesta.data.message);
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.message, 'Mensaje del sistema');
                    }
                }
            });
    }

    function quitaritem(pos, idart) {
        btnagregar = "#agregar" + idart;
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/vtas/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $(btnagregar).removeAttr('disabled');
                // $(btnagregar).attr('disabled', false);
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error('Ocurrió un error' + error, 'Mensaje del sistema');
            });
    }

    function grabarCabecera() {
        data = new FormData();
        data.append("idcliev", $("#txtidcliente").val());
        data.append("razov", $("#txtcliente").val());
        data.append("dnicliev", $("#txtdnicliente").val());
        data.append("ruccliev", $("#txtruccliente").val());
        data.append("clienteretencion", $("#txtclienteretencion").val());
        data.append("tdocv", $("#cmbdcto").val());
        data.append("cndocv", "");
        data.append("numv", "");
        var optigv = obtenerTipoIGV();
        data.append("optigv", optigv);
        data.append("ndo2v", $("#ndo2").val());
        data.append("almv", $("#cmbAlmacen").val());
        data.append("fechv", $("#txtfecha").val());
        data.append("monev", $("#cmbmoneda").val());
        data.append("formv", $("#cmbforma").val());
        data.append("fechvv", $("#txtfechav").val());
        data.append("idvenv", $("#cmbvendedor").val());
        data.append("txtreferencia", $("#txtreferencia").val());
        axios.post("/vtas/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión" + error, "Mensaje del Sistema");
            });
    }

    // $("#modal_productos").on("shown.bs.modal", function() {
    //     moverCursorFinalTexto("txtbuscarProducto");
    // });

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
        // if (document.getElementById('codigo').checked) {
        //     moverCursorFinalTexto("txtbuscarProducto");
        //     $("#txtbuscarProducto").select();
        // }
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

    document.addEventListener('keyup', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            $("#cmdbuscarP").click();
        }
    });

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
        precioventa = 0;
        <?php $preciodefault = (empty($_SESSION['gene_precdefault']) ? '' : $_SESSION['gene_precdefault']); ?>
        <?php switch ($preciodefault):
            case 'menor': ?>
                precioventa = datos.parametro7;
            <?php break;
            case 'especial': ?>
                precioventa = datos.parametro6;
            <?php break;
            case 'mayor': ?>
                precioventa = datos.parametro5;
            <?php break;
            default: ?>
                precioventa = datos.parametro5;
        <?php endswitch; ?>
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        data.append("txtunidad", datos.parametro3);
        data.append("txtprecio", precioventa);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("costo", datos.parametro8);
        data.append("tipoproducto", datos.parametro10);
        data.append("cmbmoneda", $("#cmbmoneda").val());
        data.append("stock", parseFloat(datos.parametro4).toFixed(2));
        data.append("opt", 0)
        axios.post('/vtas/agregaritem', data)
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
        cndoc1 = $("#cndoc1").val();
        cndoc2 = $("#cndoc2").val();
        if (cndoc1 == '') {
            toastr.info("Dígite la serie", 'Mensaje del Sistema');
            return false;
        }
        if (cndoc2 == '') {
            toastr.info("Dígite el número", 'Mensaje del Sistema');
            return false;
        }
        if (idcliente == 0) {
            toastr.info("Seleccione un Cliente", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
            return false;
        }
        if (Number(total) <= 0) {
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
        txtdnicliente = $("#txtdnicliente").val();
        if (Number(total) >= 700) {
            if (txtdnicliente.length == 0 && (ctdoc == '03')) {
                toastr.error("No se puede emitir un monto mayor a 700 soles sin DNI", "Mensaje del Sistema");
                return false;
            }
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
            // console.log(id, cant, precio);
            json += ',"id":"' + id + '"'
            json += ',"cant":"' + cant + '"'
            json += ',"precio":"' + precio + '"'
            obj = JSON.parse('{' + json.substr(1) + '}');
            calcularsubtotal(_tr)
            detalle.push(obj)
        });
        return detalle;
    }

    function grabarVenta() {
        calcularIGV();
        if (!validarVenta()) {
            return;
        }
        cmbforma = $("#cmbforma").val();
        valorescarrito = verificarvalorescarrito();
        const data = new FormData();
        data.append("detalle", JSON.stringify(valorescarrito));
        axios.post('/vtas/verificarvalorescarrito', data)
            .then(function(respuesta) {
                rpta = respuesta.data.estado;
                if (rpta == '1') {
                    var cmensaje = "";
                    total = $("#total").val();
                    if (document.querySelector('#txtidauto').value == '0') {
                        cmensaje = '¿Registrar Venta? <br>' + 'El monto total es: ' + 'S/ ' + total;
                        grabar(cmensaje);
                    } else {
                        cmensaje = '¿Actualizar Venta? <br>' + 'El monto total es: ' + 'S/ ' + total;
                        actualizar(cmensaje);
                    }
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
                }
            });
    }

    function changeoptigv() {
        $(".igv").attr('disabled', true);
    }

    function limpiardatos() {
        $("#txtanticipado").val("0");
        $("#txttotalanticipo").val("0");
        $("#txtndocvtaanticipo").val("");
        $("#txtidautovtaanticipo").val("0");
        $("#cmbmoneda").attr('disabled', false);
        $("#txtcliente").val("");
        $("#txtdias").val("");
        $("#titulo").val("Registrar venta");
        $("#txtcreditocliente").val("0")
        $("#txtidcliente").val("0");
        $("#txtruccliente").val("0");
        $("#txtdnicliente").val("");
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
        $("#txtclienteretencion").val("N");
        document.getElementById("grabar").innerHTML = "Grabar";
    }

    const onFocus = () => {
        listardetalle();
    }
    window.addEventListener("focus", onFocus)

    function validarcampocantidad() {
        cantnull = false;
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            var cant = _tr.find("td").eq(4).find("input").val();
            if (cant.length == 0) {
                cantnull = true;
                return cantnull;
            }
            var precio = _tr.find("td").eq(5).find("input").val();
            if (precio.length == 0) {
                cantnull = true;
                return cantnull;
            }
        });
        return cantnull;
    }

    function grabar(cmensaje) {
        cmbforma = $("#cmbforma").val();
        if (cmbforma == 'T' || cmbforma == 'Y' || cmbforma == 'P' || cmbforma == 'D') {
            txtpago = $("#txtpago").val();
            txtefectivo = $("#txtefectivo").val();
            total = $("#total").val();
            if (Number(total) < (Number(txtpago) + Number(txtefectivo))) {
                toastr.error("No se puede cancelar más del total de venta", 'Mensaje del Sistema')
            }
        }
        if (validarcampocantidad() == true) {
            toastr.error("Los valores de los productos deben ser mayores que cero", 'Mensaje del Sistema');
            return;
        }
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
                data = new FormData();
                data.append("idcliev", $("#txtidcliente").val());
                data.append("razov", $("#txtcliente").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("txtdireccion", $("#txtdireccion").val());
                data.append("txtruccliente", $("#txtruccliente").val());
                data.append("txtdnicliente", $("#txtdnicliente").val());
                data.append("txtclienteretencion", $("#txtclienteretencion").val());
                data.append("ndo2v", $("#ndo2").val());
                data.append("almv", $("#cmbAlmacen").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                let tigv = obtenerTipoIGV();
                data.append("optigv", tigv);
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#subtotal").val());
                data.append("igv", $("#igv").val());
                data.append("total", $("#total").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txtpago", $("#txtpago").val());
                data.append("txtefectivo", $("#txtefectivo").val());
                data.append("txtcreditocliente", $("#txtcreditocliente").val())
                axios.post("/vtas/registrar", data)
                    .then(function(respuesta) {
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
                                    $("#exampleModal").modal('hide');
                                }
                            }
                        };
                        xhr.send();
                        limpiardatos();
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                        console.log(error);
                    });
            }
        });
    }

    function actualizar(cmensaje) {
        cmbforma = $("#cmbforma").val();
        if (cmbforma == 'T' || cmbforma == 'Y' || cmbforma == 'P' || cmbforma == 'D') {
            txtpago = $("#txtpago").val();
            txtefectivo = $("#txtefectivo").val();
            total = $("#total").val();
            if (Number(total) < (Number(txtpago) + Number(txtefectivo))) {
                toastr.error("No se puede cancelar más del total de venta", 'Mensaje del Sistema')
            }
        }
        Swal.fire({
            title: cmensaje,
            text: "Se actualizará en el sistema. ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idautov", $("#txtidauto").val());
                data.append("idcliev", $("#txtidcliente").val());
                data.append("razov", $("#txtcliente").val());
                data.append("txtdnicliente", $("#txtdnicliente").val());
                data.append("txtclienteretencion", $("#txtclienteretencion").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("ndo2v", $("#ndo2").val());
                data.append("almv", $("#cmbAlmacen").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                let tigv = obtenerTipoIGV();
                data.append("optigv", tigv);
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#subtotal").val());
                data.append("igv", $("#igv").val());
                data.append("total", $("#total").val());
                data.append("txtpago", $("#txtpago").val());
                data.append("txtefectivo", $("#txtefectivo").val());
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txtcreditocliente", $("#txtcreditocliente").val())
                axios.post("/vtas/actualizar", data)
                    .then(function(respuesta) {
                        toastr.success(' Se actualizo la venta satisfactoriamente ', 'Mensaje del Sistema');
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        window.location.href = '/vtas/vtasresumidas';
                        limpiardatos();
                    }).catch(function(error) {
                        mostrarerroresvalidacion(error);
                    });
            }
        });
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

    function enterefectivo(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            grabarVenta();
        }
    }

    function cancelarVenta() {
        axios.post('/vtas/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            window.location.href = '/ventasrapidas/index';
        }).catch(function(error) {
            toastr.error('Ocurró un error ' + error, 'Mensaje del Sistema');
        });
    }

    //Eventos
    var txtfecha = document.getElementById("txtfecha");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfecha").val();
        grabarCabecera();
    }, true);

    var txtfechav = document.getElementById("txtfechav");
    txtfechav.addEventListener("blur", function(event) {
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

    $('#txtpagar').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtefectivo").focus();
            $("#txtefectivo").click();
        }
    });

    $('#txtefectivo').keypress(function(e) {
        if (e.keyCode == 13) {
            grabarVenta();
        }
    });

    function bloquearTodoEdicion() {
        $(".content-header input").each(function() {
            if ($(this).attr("type") !== "hidden") {
                $(this).prop("readonly", true).addClass("bloqueado-edicion");
            }
        });
        $(".content-header select").addClass("bloqueado-select");
        $(".content-header button").prop("disabled", true).addClass("bloqueado-edicion");
        $("#griddetalle input").each(function() {
            $(this).prop("readonly", true).addClass("bloqueado-edicion");
        });
        $("#griddetalle select").addClass("bloqueado-select");
        $("#griddetalle button").prop("disabled", true).addClass("bloqueado-edicion");
        $("#btnagregarprod").prop("disabled", true).addClass("bloqueado-edicion");
        $("#cmbforma").removeClass("bloqueado-select").prop("disabled", false)
            .css({
                "background-color": "",
                "color": "",
                "cursor": ""
            });
        $("#grabar").prop("disabled", false).removeClass("bloqueado-edicion");
        $("#cancelar").prop("disabled", false).removeClass("bloqueado-edicion");
        $("#txtreferencia").prop("readonly", false).removeClass("bloqueado-edicion");
        $("#txtdias").prop("readonly", false).removeClass("bloqueado-edicion");
        $("#btnconvertirafactura").prop("disabled", false).removeClass("bloqueado-edicion");
    }

    // function convertirafactura(idventa) {
    //     cmbdcto = $("#cmbdcto").val();
    //     if (cmbdcto != '03') {
    //         toastr.error("El documento no es una boleta", 'Mensaje del Sistema');
    //         return;
    //     }
    //     Swal.fire({
    //         title: "¿Desea convertir esta boleta a factura?",
    //         text: "El numero actual la boleta pasará como anulado y se reemplazará por el correlativo de factura junto con la fecha",
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Si'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             data = new FormData();
    //             data.append("idventa", idventa);
    //             data.append("cmbforma", $("#cmbforma").val());
    //             axios.post("/vtas/convertirafactura", data)
    //                 .then(function(respuesta) {
    //                     console.log(respuesta);
    //                     // toastr.success(' Se actualizo la venta satisfactoriamente ', 'Mensaje del Sistema');
    //                     // const tabla = respuesta.data;
    //                     // $('#detalle').html(tabla);
    //                     // window.location.href = '/vtas/vtasresumidas';
    //                     // limpiardatos();
    //                 }).catch(function(error) {
    //                     mostrarerroresvalidacion(error);
    //                 });
    //         }
    //     });
    // }
</script>
<?php
$this->endSection("javascript");
?>