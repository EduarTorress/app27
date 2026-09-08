<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\ModalProveedorComponent;
use App\View\Components\MotivoNotasCredComponent;
use App\View\Components\MotivoNotasDebiComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<div style="display:none">
    <?php
    $motivonc = new MotivoNotasCredComponent('');
    echo $motivonc->render();
    ?>
    <?php
    $motivond = new MotivoNotasDebiComponent('');
    echo $motivond->render();
    ?>
</div>
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
                        <input type="text" class="form-control form-control-sm" id="txtproveedor" placeholder="Proveedor" disabled value="">
                        <input type="hidden" id="txtidproveedor" value="0">
                        <input type="hidden" id="txtrucproveedor" value="">
                        <input type="hidden" id="txtptopartida" value="">
                        <input type="hidden" id="txtUbigeoproveedor" value="">
                        <input type="hidden" id="txtidauto" value="0">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                        <button class="btn btn-outline-info" role="button" data-bs-toggle="modal" data-bs-target="#modal_compras"><i style="color:black" class="fa fa-file-text-o"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : '';
                    $formapago = new FormadepagoComponent($cforma);
                    echo $formapago->render();
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
            <div class="row">
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm" for="">Nro documento:</label>
                        <input type="text" class="form-control form-control-sm" id="txtndoc" placeholder="" readonly>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group mb-3">
                        <label class="form-control form-control-sm" for="">Documento:</label>
                        <input type="text" class="form-control form-control-sm" onkeyup="mayusculas(this);" id="txtndocnotacredito" maxlength="12" placeholder="FN0100001234">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div id="motivo" class="input-group mb-3">
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
        $(".tipodocumentos option[value='03']").remove();
        $(".tipodocumentos option[value='01']").remove();
        $(".tipodocumentos option[value='22']").remove();
        $(".tipodocumentos option[value='20']").remove();
        $(".tipodocumentos option[value='08']").remove();
        // jQuery('#motivo').replaceWith(jQuery('#cmbMotivoNotaC'));
        mostrarMotivos("07");
        $("#cmbMotivo option[value='01']").remove();
        $("#cmbMotivo option[value='02']").remove();
        $("#cmbMotivo option[value='03']").remove();
        $("#cmbMotivo option[value='06']").remove();
        $("#cmbMotivo option[value='07']").remove();
        $("#cmbMotivo option[value='08']").remove();
        $("#cmbMotivo option[value='09']").remove();
        $("#cmbMotivo option[value='10']").remove();
        $("#cmbMotivo option[value='11']").remove();
        $("#cmbMotivo option[value='12']").remove();
        $("#cmbMotivo option[value='13']").remove();
        $("#motivo").find("#cmbMotivo").val("05")
    }

    $('#modal_proveedor').on('shown.bs.modal', function() {
        $('#txtbuscarprov').focus();
        $('#txtbuscarprov').select();
    });

    $('#modal_proveedor').on('hidden.bs.modal', function(e) {
        txtbuscarprov = $("#txtidproveedor").val();
        buscarcompraxproveedor(txtbuscarprov);
    });

    $("#cmbdcto").on("change", function(event) {
        mostrarMotivos(this.value);
    });

    function mostrarMotivos(tipodoc) {
        var divMotivo = document.getElementById('motivo');
        if (tipodoc == '07') {
            var cmbMotivoNotaC = document.getElementById('cmbMotivoNotaC');
            divMotivo.innerHTML = cmbMotivoNotaC.innerHTML;
        } else {
            var cmbMotivoNotaD = document.getElementById('cmbMotivoNotaD');
            divMotivo.innerHTML = cmbMotivoNotaD.innerHTML;
        }
    }

    function buscarcompraxproveedor(idproveedor) {
        axios.get('/compras/listarcomprasnota', {
            "params": {
                "idproveedor": idproveedor,
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#cargamodal').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function seleccionarcompra(datos) {
        document.getElementById("txtidauto").value = datos.parametro1;
        document.getElementById("txtndoc").value = datos.parametro4;
        $("#modal_compras").modal('hide');
        buscarDetallePorId(datos.parametro1, datos.parametro9);
        $(".codigo").css("display", "none");
    }

    function buscarDetallePorId(idauto, tipoventa) {
        axios.get('/compras/listardetallenota', {
            "params": {
                "idauto": idauto,
                'tipoventa': 'K'
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
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
        if (igv === 'I') {
            //Si el IGV está incluido
            valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
            let impo = (Number(total_col)).toFixed(2);
            let valor = (impo / valorigv).toFixed(2);
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

    function calculartotalporglobal() {
        total = $("#total").val();
        let impo = (Number(total)).toFixed(2);
        let valor = (impo / 1.18).toFixed(2);
        let nigv = (impo - valor).toFixed(2);
        $("#igv").val(nigv);
        $("#subtotal").val(valor);
        // $("#total").val(impo);
    }

    function descuentoglobal() {
        $("#total").prop('readonly', false);
    }

    function validarcompra() {
        txtidproveedor = document.querySelector('#txtidproveedor').value;
        total = document.querySelector('#total').value;
        if (txtidproveedor == 0) {
            toastr.info("Seleccione un Proveedor", 'Mensaje del Sistema');
            return false;
        }
        cmbMotivo = $("#cmbMotivo").val();
        if (cmbMotivo == '05') {
            if (total == 0) {
                toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
                return false;
            }
        }
        return true;
    }

    function grabar() {
        if (!validarcompra()) {
            return;
        }
        cmensaje = '¿Registrar nota de crédito?';
        registrar(cmensaje);
    }

    function limpiardatos() {
        $("#txtproveedor").val("");
        $("#titulo").val("Registrar Nota Credito x compra");
        $("#txtidproveedor").val("0");
        $("#txtrucproveedor").val("0");
        $("#txtndoc").val("");
        $("#cmbforma").val("E");
        // $("#cmbvendedor").val("1");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        document.getElementById("grabar").innerHTML = "Grabar";
        $('#griddetalle tbody tr').remove();
    }

    function validarregistro() {
        txtndocnotacredito = $("#txtndocnotacredito").val();
        if (txtndocnotacredito.length > 12) {
            toastr.error("El numero de documento tiene que tener 12 caracteres", 'Mensaje del Sistema');
            return false;
        }
        if ((txtndocnotacredito.substr(0, 1) != 'F') && (txtndocnotacredito.substr(0, 1) != 'B')) {
            toastr.error("El numero de documento debe comenzar con B o F", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function registrar(cmensaje) {
        if (validarregistro() == true) {
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
                    const detalle = []
                    $("#griddetalle tbody tr").each(function() {
                        json = "";
                        $(this).find("td").each(function() {
                            $this = $(this);
                            val = $this.text();
                            val = val.replace(/"/g, '\\"');
                            json += ',"' + $this.attr("class") + '":"' + val + '"'
                        })
                        obj = JSON.parse('{' + json.substr(1) + '}');
                        detalle.push(obj)
                    });
                    data = new FormData();
                    data.append("txtndocnotacredito", $("#txtndocnotacredito").val());
                    data.append("idprov", $("#txtidproveedor").val());
                    data.append("idauto", $("#txtidauto").val());
                    data.append("razo", $("#txtproveedor").val());
                    data.append("tdoc", $("#cmbdcto").val());
                    // data.append("txtdireccion", $("#txtdireccion").val());
                    // data.append("txtruccliente", $("#txtruccliente").val());
                    // data.append("txtdnicliente", $("#txtdnicliente").val());
                    // data.append("ndo2v", $("#txtndoc").val());
                    // data.append("almv", $("#cmbAlmacen").val());
                    data.append("fech", $("#txtfecha").val());
                    // data.append("monev", $("#cmbmoneda").val());
                    data.append("form", $("#cmbforma").val());
                    // data.append("fechvv", $("#txtfechavto").val());
                    data.append("cmbMotivo", $("#motivo").find("#cmbMotivo option:selected").text());
                    data.append("idvenv", $("#cmbvendedor").val());
                    data.append("subtotal", $("#subtotal").val());
                    data.append("igv", $("#igv").val());
                    data.append("total", $("#total").val());
                    data.append("detalle", JSON.stringify(detalle));
                    axios.post("/compras/registrarnotacredito", data)
                        .then(function(respuesta) {
                            rpta = respuesta.data.message.trimEnd() + ' ' + respuesta.data.ndoc;
                            Swal.fire({
                                title: "Se registro correctamente",
                                text: rpta,
                                icon: "success"
                            });
                            // toastr.success(rpta);
                            limpiardatos();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Numero de documento no valido", 'Mensaje del Sistema');
        }
    }

    function limpiar() {
        window.location.href = "/compras/indexnotascredito";
    }
</script>
<?php
$this->endSection("javascript");
?>