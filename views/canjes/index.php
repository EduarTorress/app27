<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\GuiaComponent;
use App\View\Components\IGVComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalGuiaComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\ModalProductoComponent;

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
$guia = new ModalGuiaComponent();
echo $guia->render();
$impr = new ModalImprimir();
echo $impr->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <button class="btn btn-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_guias"><i class="fa fa-file-text-o" aria-hidden="true"></i></button> &nbsp;&nbsp;
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                        <input type="hidden" id="txtidcliente" value="">
                        <input type="hidden" id="txtruccliente" value="">
                        <input type="hidden" id="txtdireccion" value="">
                        <input type="hidden" id="txtdnicliente" value="">
                        <input type="hidden" id="txtclienteretencion" value="N">
                        <input type="hidden" id="txtidauto" value="">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $dctos = new DocumentoComponent('');
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $guia = new GuiaComponent();
                    echo $guia->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $tpmoneda = new TipoMonedaComponent('S');
                    echo $tpmoneda->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php
                    $formapago = new FormadepagoComponent('E');
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
                    $vendedor = new VendedorComponent(0);
                    echo $vendedor->render();
                    ?>
                </div>
                <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                <div class="col-sm-3" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                    <?php
                    $igv = new IGVComponent('');
                    echo $igv->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control form-control-sm" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="">
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
        $("#txtguia").prop("disabled", true);
        <?php session()->set('carritocanje', []);  ?>
        axios.get('/vtas/listardetallecanjeguias').then(function(respuesta) {
            $('#detalle').html(respuesta.data);
            $(".codigo").css("display", "none");
            <?php if ($multiigv != 'S') : ?>
                $(".preciosgv").css("display", "none");
            <?php endif; ?>
            $(".id").css("display", "none");
            buscarGuias();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
        $(".tipodocumentos option[value='GI']").remove();
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
    }

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    function grabarCabecera() {}

    $("#modal_productos").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscarProducto");
    });

    document.addEventListener('keyup', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            $("#cmdbuscarP").click();
        }
    });

    document.addEventListener("keydown", (e) => {
        switch (e.key) {
            case "Delete":
                $("#txtbuscarProducto").focus();
                break;
            default:
                break;
        }
    });

    function calcularIGV() {
        igv = obtenerTipoIGV();
        var total_col = 0;
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(6).text();
            total_col += parseFloat(t);
        });
        if (igv == 'I') {
            $('#griddetalle tbody tr').each(function() {
                $(this).find(".preciosgv").html("");
            });
        } else {
            $('#griddetalle tbody tr').each(function() {
                precio = $(this).find(".precio").html();
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

        let impor = $("#total").val();
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

    function grabarVenta() {
        if (!validarVenta()) {
            return;
        }
        cmensaje = '¿Registrar Venta?';
        grabar(cmensaje);
    }

    function limpiardatos() {
        document.querySelector("#idautov").value = "";
        document.querySelector("#idautog").value = "";
        $("#cmbmoneda").attr('disabled', false);
        $("#grabar").attr("disabled", false);
        document.querySelector('#txtcliente').value = "";
        document.getElementById("titulo").innerHTML = "Canjes";
        document.getElementById("grabar").innerHTML = "Grabar";
        document.querySelector("#txtidcliente").value = "0";
        document.querySelector("#txtruccliente").value = "0";
        document.querySelector('#txtdireccion').value = "";
        document.querySelector("#cmbforma").value = "E";
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector("#txtguia").value = "";
        document.querySelector("#total").value = "0.00";
        document.querySelector("#subtotal").value = "0.00";
        document.querySelector("#igv").value = "0.00";
        $("#txtreferencia").val("");
        $("#griddetalle tbody tr").remove();
    }

    function changedetaildolar() {
        $("#cmbmoneda").attr('disabled', true);
        $('#griddetalle tbody tr').each(function() {
            //cantidad=$(this).find(".ValueOne").html();
            precio = $(this).find(".precio").html();
            preciocondolar = precio / Number("<?php echo $_SESSION['gene_dola']; ?>");
            $(this).find(".precio").html(Number(preciocondolar).toFixed(2));
        });
    }

    function grabar(cmensaje) {
        const detalle = []
        $("#griddetalle tbody tr").each(function() {
            json = "";
            $(this).find("td").each(function() {
                $this = $(this);
                key = $this.attr("class")
                key = key.replace(/dtr-control/g, '')
                val = $this.text();
                val = val.replace(/"/g, '\\"');
                json += ',"' + key.trim() + '":"' + val + '"'
            })
            obj = JSON.parse('{' + json.substr(1) + '}');
            detalle.push(obj)
        });
        Swal.fire({
            title: cmensaje,
            text: "Se registrará en el sistema ",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idcliev", $("#txtidcliente").val());
                data.append("iddire", $("#txtdireccion").val())
                data.append("razov", $("#txtcliente").val());
                data.append("txtdireccion", $("#txtdireccion").val());
                data.append("txtclienteretencion", $("#txtclienteretencion").val());
                data.append("txtruccliente", $("#txtruccliente").val());
                data.append("txtdnicliente", $("#txtdnicliente").val());
                data.append("tdocv", $("#cmbdcto").val());
                data.append("ndo2v", $("#txtguia").val());
                data.append("fechv", $("#txtfecha").val());
                data.append("idautov", $("#idautov").val());
                data.append("idautog", $("#idautog").val());
                data.append("monev", $("#cmbmoneda").val());
                data.append("formv", $("#cmbforma").val());
                data.append("fechvv", $("#txtfechavto").val());
                data.append("idvenv", $("#cmbvendedor").val());
                data.append("subtotal", $("#subtotal").val());
                data.append("igv", $("#igv").val());
                data.append("total", $("#total").val());
                data.append("totalitems", $("#totalitems").val());
                data.append("detalle", JSON.stringify(detalle));
                data.append("txtdias", $("#txtdias").val())
                let tigv = obtenerTipoIGV();
                data.append("optigv", tigv);
                data.append("txtreferencia", $("#txtreferencia").val());
                axios.post("canje/registrar", data)
                    .then(function(respuesta) {
                        toastr.success('Se genero el documento: ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        limpiardatos();
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
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del sistema');
                            }
                        }
                    });
            }
        });
    }

    function cancelarVenta() {
        axios.post('/vtas/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            window.location.href = '/vtas/canjes';
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del Sistema');
        });
    }

    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $("td").removeClass("dtr-control")
        $(".id").css("display", "none");
    });
</script>
<?php
$this->endSection("javascript");
?>