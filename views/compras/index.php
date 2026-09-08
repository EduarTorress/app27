<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\IGVComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ValorDolarComponent;
use App\View\Components\ModalProveedorComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalRegistroCuentasxPagarComponent;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<?php
$prod = new ModalProductoComponent();
echo $prod->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="<?php echo isset($datosproveedor['razo']) ?  trim($datosproveedor['razo']) : '' ?>">
                        <input type="hidden" id="txtidproveedor" value="<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?>">
                        <input type="hidden" id="txtrucproveedor" value="<?php echo isset($datosproveedor['rucc']) ?  $datosproveedor['rucc'] : '' ?>">
                        <input type="hidden" id="txtptopartida" value="">
                        <input type="hidden" id="txtUbigeoproveedor" value="">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idcompra) ? $idcompra : 0 ?>">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosproveedor['tdoc']) ? $datosproveedor['tdoc'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render('C');
                    ?>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Número: </label>
                        <input type="text" onkeyup="mayusculas(this); isFormatSerie()" class="form-control form-control-sm" maxlength="4" id="cndoc1" value="<?php echo isset($serie) ?  $serie : '' ?>" style="width: 20%;" placeholder="F001">
                        <input type="text" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="<?php echo isset($num) ?  $num : '' ?>" style="width: 30%;" placeholder="00001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosproveedor['ndo2']) ?  $datosproveedor['ndo2'] : '' ?>" placeholder="00001">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php
                    $cempresa = isset($datosproveedor['alm']) ? $datosproveedor['alm'] : $_SESSION['idalmacen'];
                    $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cforma = isset($datosproveedor['form']) ? $datosproveedor['form'] : '';
                    $formapago = new FormadepagoComponent($cforma);
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $cmon = isset($datosproveedor['mone']) ? $datosproveedor['mone'] : '';
                    $tpmoneda = new TipoMonedaComponent($cmon);
                    echo $tpmoneda->render();
                    ?>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Doc. :</label>
                        <?php
                        $diasregistro = (empty($_SESSION['gene_diasregistro']) ? '15' : $_SESSION['gene_diasregistro']);
                        $fechaactual = date('Y-m-d');
                        $fechaminima = date('Y-m-d', (strtotime('-' . $diasregistro . ' day', strtotime($fechaactual))));
                        ?>
                        <input type="date" class="form-control form-control-sm" onkeydown="return false;" min="<?php echo $fechaminima ?>" max="<?php echo $fechaactual; ?>" value="<?php echo empty($datosproveedor['fech']) ?  date("Y-m-d") :  $datosproveedor['fech'] ?>" style="width:140px;" id="txtfechai" name="txtfechai">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Reg. :</label>
                        <input type="date" class="form-control form-control-sm" onkeydown="return false;" min="<?php echo $fechaminima ?>" max="<?php echo $fechaactual; ?>" value="<?php echo empty($datosproveedor['fecr']) ?  date("Y-m-d") :  $datosproveedor['fecr']; ?>" style="width:140px;" id="txtfechaf" name="txtfechaf">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <?php
                    $optigv = isset($datosproveedor['optigv']) ? $datosproveedor['optigv'] : 'I';
                    $igv = new IGVComponent($optigv);
                    echo $igv->render();
                    ?>
                </div>
                <div class="col-sm-2" id="divdolar">
                    <?php
                    $dolar = new ValorDolarComponent();
                    echo $dolar->render();
                    ?>
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
<div class="modal fade" id="mdactualizarprecios" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">¿Actualizar Precios?</h5>
            </div>
            <div class="modal-body">
                <select onchange="" class="form-control form-control-sm" id="actualizarprecios" name="actualizarprecios">
                    <option value="N">NO</option>
                    <option value="S">SI</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="grabaropcion();">Grabar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
$mdrcxp = new ModalRegistroCuentasxPagarComponent();
echo $mdrcxp->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        clicksubtotal = 0;
        ie = -1;
        titulo("<?php echo $titulo ?>");
        valor = "<?php echo $v ?>";
        listardetalle();
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='22']").remove();
        $(".tipodocumentos option[value='20']").remove();
        $("#cmbAlmacen").css("display", "none");
        fechai = document.getElementById('txtfechai').value;
        obtenerDolar(fechai);
        calcularIGV();
        $("#cndoc1").val("<?php echo isset($serie) ?  $serie : '' ?>");
        $("#txtidproveedor").val("<?php echo isset($datosproveedor['idprov']) ?  $datosproveedor['idprov'] : '' ?>");
        $("#txtproveedor").val("<?php echo isset($datosproveedor['razo']) ?  $datosproveedor['razo'] : '' ?>");
    }

    const onFocus = () => {
        listardetalle();
    }
    window.addEventListener("focus", onFocus)

    function listardetalle() {
        axios.get('/compras/listardetalle').then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del Sistema')
        });
    }

    $("#modal_proveedor").on("shown.bs.modal", function() {
        $("#txtbuscarprov").focus();
    });

    $("#modal_proveedor").on("hidden.bs.modal", function() {
        grabarCabecera();
    });

    async function cargararchivoxml(event) {
        ie = -1;
        const file = event.target.files.item(0)
        const text = await file.text();
        // console.log(text)
        const data = new FormData();
        data.append("archivo", text);
        axios.post('/compras/importarcompraxarchivo', data)
            .then(function(respuesta) {
                rpta = respuesta.data.message;
                $("#txtproveedor").val(rpta.proveedor);
                $("#txtproveedor").addClass("border border-success border-5");
                $("#txtrucproveedor").val(rpta.ruc);
                $("#txtfechai").val(rpta.fecha)
                $("#txtfechai").addClass("border border-success border-3");
                dcto = rpta.documento;
                ndoc = dcto.split('-');
                $("#cndoc1").val(ndoc[0]);
                $("#cndoc1").addClass("border border-success border-3");
                $("#cndoc2").val(ndoc[1]);
                $("#cndoc2").addClass("border border-success border-3");
                switch (ndoc[0].substr(0, 1)) {
                    case 'F':
                        $("#cmbdcto").val("01");
                        break;
                    case 'E':
                        $("#cmbdcto").val("01");
                        break;
                    case 'B':
                        $("#cmbdcto").val("03");
                        break;
                    default:
                        $("#cmbdcto").val("GI");
                }
                $("#cmbdcto").addClass("border border-success border-3");

                switch (rpta.moneda) {
                    case 'USD':
                        $("#cmbmoneda").val("D");
                        break;
                    default:
                        $("#cmbmoneda").val("S");
                }
                $("#cmbmoneda").addClass("border border-success border-3");

                axios.get('/compras/listardetalle').then(function(respuesta) {
                    const contenido_tabla = respuesta.data;
                    $('#detalle').html(contenido_tabla);

                    $("#griddetalle").addClass("border border-success border-5");
                }).catch(function(error) {
                    toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
                });
                grabarCabecera();
                Swal.fire({
                    title: "Compra Importada satisfactoriamente",
                    text: "Se cargaron los datos de acuerdo al archivo subido.",
                    icon: "success"
                });
                consultarproveedorximportacion();
            }).catch(function(error) {
                console.log(error);
            });
    }

    function consultarproveedorximportacion() {
        var abuscar = document.querySelector('#txtrucproveedor').value;
        var noption = 1
        var cmodo = 'S';
        axios.get('/proveedor/buscar', {
            "params": {
                "cbuscar": abuscar,
                "option": noption,
                "modo": cmodo
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchprov').html(contenido_tabla);
            var nombre = "N",
                ciudad = "-",
                direccion = "-",
                ubigeo = "-";
            const tblcl = $("#iniciar").val();
            if ((tblcl == null) && (noption == '1' || noption == '2')) {
                if (abuscar.length == 11) {
                    axios.get('/empresa/importarucydni', {
                        "params": {
                            "ruc": abuscar
                        }
                    }).then(function(respuesta) {
                        nombre = respuesta.data.nombre_o_razon_social;
                        if (abuscar.substring(0, 1) == '2') {
                            direccion = respuesta.data.direccion;
                            ciudad = respuesta.data.distrito.trimEnd() + ' ' + respuesta.data.provincia.trimEnd() + ' ' + respuesta.data.departamento.trimEnd();
                            ubigeo = respuesta.data.ubigeo.trimEnd();
                        }
                        if (nombre !== undefined) {
                            Swal.fire({
                                title: "Proveedor no registrado en el sistema, presione sí para registrarlo",
                                text: nombre,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Si, deseo registrarlo.',
                                cancelButtonText: 'No, volver atras.'
                            }).then(function(respuesta) {
                                if (respuesta.isConfirmed) {
                                    const data = new FormData();
                                    data.append("txtRUC", abuscar);
                                    data.append("txtDNI", "");
                                    data.append("txtNombre", nombre);
                                    data.append("txtDireccion", direccion);
                                    data.append("txtCiudad", ciudad);
                                    data.append("cmbUbigeo", ubigeo);
                                    axios.post('/proveedor/store', data)
                                        .then(function(respuesta) {
                                            axios.get("/proveedor/buscar", {
                                                params: {
                                                    cbuscar: abuscar,
                                                    option: noption,
                                                    modo: cmodo,
                                                }
                                            }).then(function(rp) {
                                                const contenido_tabla = rp.data;
                                                $("#searchprov").html(contenido_tabla);
                                                $("#cmdbuscar").attr('disabled', true);
                                                btnagregar = $("#iniciar").find("button");
                                                $(btnagregar).click();
                                            });
                                        }).catch(function(error) {
                                            if (error.hasOwnProperty('response')) {
                                                if (error.response.status === 422) {
                                                    const respuesta_servidor = error.response.data;
                                                    const errores = respuesta_servidor.errors;
                                                    mostrarErrores('formulario-crear', errores);
                                                }
                                            }
                                        })
                                }
                            });
                        }
                    }).catch(function(error) {
                        toastr.error(error, 'Mensaje del Sistema')
                    });
                }
            }
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementById('codigo').checked) {
            moverCursorFinalTexto("txtbuscarProducto");
            $("#txtbuscarProducto").select();
        }
    });

    function agregarunitemVenta(datos) {
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        data.append("txtunidad", datos.parametro3);
        data.append("txtprecio", datos.parametro5);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        if (ie < 0) {
            axios.post('/compras/agregaritem', data)
                .then(function(respuesta) {
                    //window.location.href = '/vtas/index';
                    $('#modal_productos').modal('hide')
                    const contenido_tabla = respuesta.data;
                    $('#detalle').html(contenido_tabla);
                    calcularIGV();
                    //$("#griddetalle tr:last").focus()
                    var a = $("#griddetalle tr:last td:eq(4)").each(function() {
                        $(this).focus();
                        $(this).click();
                    });
                    idart = "#agregar" + datos.parametro2;
                    // console.log(idart);
                    $(idart).attr('disabled', 'disabled');
                    ie = -1;
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
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
        } else {
            data.append("indice", ie);
            axios.post('/compras/agregaritemxposicion', data)
                .then(function(respuesta) {
                    //window.location.href = '/vtas/index';
                    $('#modal_productos').modal('hide')
                    const contenido_tabla = respuesta.data;
                    $('#detalle').html(contenido_tabla);
                    calcularIGV();
                    idart = "#agregar" + datos.parametro2;
                    $(idart).attr('disabled', 'disabled');
                    ie = -1;
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            toastr.error(error.response.data.errors, "Mensaje del Sistema");
                        }
                    }
                });
        }
    }

    function cambiarcheckafecto(element, i) {
        // tr = $(element).parent().parent().parent();
        // console.log($(tr).html());
        // console.log(i)
        // console.log(element.checked)
        marcado = false;
        if (element.checked == true) {
            marcado = true;
        }
        const data = new FormData();
        data.append("indice", i);
        data.append("marcado", marcado);
        axios.post('/compras/checkafecto', data)
            .then(function(respuesta) {
                // calcularafecto();
                calcularIGV()
            }).catch(function(error) {
                console.log(error);
            });
    }

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/compras/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                console.log(error);
            });
    }

    function cancelarCompra() {
        axios.post('/compras/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            limpiardatos();
        }).catch(function(error) {
            console.log(error);
        });
    }

    function limpiardatos() {
        document.querySelector('#txtproveedor').value = "";
        document.getElementById("titulo").innerHTML = "Regs. Compra";
        document.getElementById("grabar").innerHTML = "Grabar";
        document.querySelector("#txtidproveedor").value = "0";
        document.querySelector("#cndoc1").value = "";
        document.querySelector("#cndoc2").value = "";
        document.querySelector("#ndo2").value = "";
        document.querySelector("#cmbforma").value = "E";
        // document.querySelector("#cmbAlmacen").value = "1";
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector('#txtdolar').value = "";
        window.location.href = '/compras/index';
    }

    function validarCompra() {
        idProv = document.querySelector('#txtidproveedor').value;
        total = document.querySelector('#total').value;
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
        // ctdoc = document.querySelector('#cmbdcto').value;
        if (idProv == 0) {
            toastr.info("Seleccione un proveedor", 'Mensaje del Sistema');
            return false;
        }
        // if (total == 0) {
        //     toastr.info("Ingrese importes válidos", 'Mensaje del Sistema');
        //     return false;
        // }
        return true;
    }

    function vermodalactualizarprecios() {
        if (!validarCompra()) {
            return;
        }
        $("#mdactualizarprecios").modal('show');
    }

    function grabaropcion() {
        $("#mdactualizarprecios").modal('hide');
        grabarCompra();
    }

    $('#modalregistrocuentasxpagar').on('shown.bs.modal', function() {
        $("#txtnumeroletras").select();
    });

    function crearfilas() {
        let num = document.querySelector("#cndoc2").value
        let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
        cantidadletras = $("#txtnumeroletras").val();
        $("#tblletras tbody").empty();
        for (var i = 0; i < Number(cantidadletras); i++) {
            var fila = '<tr>' +
                '<td><input type="text" class="ndoc" style="font-size:10px;" value="' + cndoc + '" readonly></td>' +
                '<td><input type="number" oninput="this.value = Math.round(this.value);" class="txtdiasvto" style="font-size:10px;" onfocus="this.select();" onkeyup="calcularfechaxdias(this)" ></td>' +
                '<td><input type="date" class="txtfechavto" style="font-size:10px;" value="<?php echo date('Y-m-d'); ?>"></td>' +
                '<td><input type="text" class="txtreferenciacxpagar" style="font-size:10px;"></td>' +
                '<td><input type="text" class="txtimporte" style="font-size:10px;" onkeypress="isNumber(event)" onfocus="this.select();"></td>' +
                '</tr>';
            $('#tblletras tbody').append(fila);
        }
    }

    function calcularfechaxdias(t) {
        txtdias = $(t).val();
        txtfecha = $("#txtfechai").val();
        txtfechavto = $(t).parent().next().find("input");
        calcularfechavto(txtfecha, txtdias, txtfechavto);
    }

    function calcularfechavto(txtfecha, txtdias, txtfechavto) {
        axios.get('/calcularfechavto', {
            "params": {
                "txtfecha": txtfecha,
                'txtdias': txtdias
            }
        }).then(function(respuesta) {
            $(txtfechavto).val(respuesta.data);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error)
        });
    }

    function grabarCompra() {
        if (!validarCompra()) {
            return;
        }
        registrocuentasxpagar = "<?php $_SESSION['config'] ?>"
        <?php if (!empty($_SESSION['config']['nocuentasxpagar'])) : ?>
            var cmensaje = "";
            if (document.querySelector('#txtidauto').value == '0') {
                cmbformapago = $("#cmbforma").val();
                <?php if (!empty($_SESSION['config']['depositocomocredito'])) : ?>
                    if ((cmbformapago == 'C') || (cmbformapago == 'D')) {
                        total = $("#total").val();
                        total = Number(total).toFixed(2);
                        $("#txtimportefinal").val(total);
                        $("#modalregistrocuentasxpagar").modal('show');
                    } else {
                        cmensaje = '¿Registrar Compra?';
                        grabar(cmensaje);
                    }
                <?php else : ?>
                    if ((cmbformapago == 'C')) {
                        total = $("#total").val();
                        total = Number(total).toFixed(2);
                        $("#txtimportefinal").val(total);
                        $("#modalregistrocuentasxpagar").modal('show');
                    } else {
                        cmensaje = '¿Registrar Compra?';
                        grabar(cmensaje);
                    }
                <?php endif; ?>
            } else {
                cmensaje = '¿Actualizar Compra?';
                actualizar(cmensaje);
            }
        <?php else : ?>
            if (document.querySelector('#txtidauto').value == '0') {
                cmensaje = '¿Registrar Compra?';
                grabar(cmensaje);
            } else {
                cmensaje = '¿Actualizar Compra?';
                actualizar(cmensaje);
            }
        <?php endif; ?>
    }

    function grabar(cmensaje) {
        const detalle = []
        e = 0;
        totalsuma = 0;
        cmbtipodocumentocuentasxpagar = '';
        let form = document.getElementById("cmbforma").value;
        <?php if (!empty($_SESSION['config']['depositocomocredito'])) : ?>
            if ((form == 'C') || (form == 'D')) {
                $("#tblletras tbody tr").each(function() {
                    json = "";
                    $(this).find("td input").each(function() {
                        $this = $(this);
                        json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                        valor = $this.val();
                        if ($this.attr("class") == 'txtimporte') {
                            if (Number(valor) == 0 || valor == "0" || valor == " ") {
                                e = 1;
                            }
                        }
                        if ($this.attr("class") == 'txtdiasvto') {
                            if (Number(valor) == 0 || valor == "0" || valor == " ") {
                                e = 1;
                            }
                        }
                        if ($this.attr("class") == 'txtimporte') {
                            totalsuma += Number(valor);
                        }
                    });
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                <?php if (!empty($_SESSION['config']['nocuentasxpagar'])) : ?>
                    if (e == 1) {
                        toastr.error("Complete los datos correctamente", 'Mensaje del sistema');
                        return;
                    }
                    importetotal = $("#total").val();
                    if (Number(totalsuma) > Number(importetotal)) {
                        toastr.error("El monto sumado no debe ser mayor al total", 'Mensaje del sistema');
                        return;
                    }
                    txtnumeroletras = $("#txtnumeroletras").val();
                    if (txtnumeroletras.length == 0 || txtnumeroletras == '' || Number(txtnumeroletras) == 0) {
                        toastr.error("Ingrese el número de letras", 'Mensaje del sistema');
                        return;
                    }
                <?php endif; ?>
            }
        <?php else : ?>
            if ((form == 'C')) {
                $("#tblletras tbody tr").each(function() {
                    json = "";
                    $(this).find("td input").each(function() {
                        $this = $(this);
                        json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
                        valor = $this.val();
                        if ($this.attr("class") == 'txtimporte') {
                            if (Number(valor) == 0 || valor == "0" || valor == " ") {
                                e = 1;
                            }
                        }
                        if ($this.attr("class") == 'txtdiasvto') {
                            if (Number(valor) == 0 || valor == "0" || valor == " ") {
                                e = 1;
                            }
                        }
                        if ($this.attr("class") == 'txtimporte') {
                            totalsuma += Number(valor);
                        }
                    });
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                <?php if (!empty($_SESSION['config']['nocuentasxpagar'])) : ?>
                    if (e == 1) {
                        toastr.error("Complete los datos correctamente", 'Mensaje del sistema');
                        return;
                    }
                    importetotal = $("#total").val();
                    if (Number(totalsuma) > Number(importetotal)) {
                        toastr.error("El monto sumado no debe ser mayor al total", 'Mensaje del sistema');
                        return;
                    }
                    txtnumeroletras = $("#txtnumeroletras").val();
                    if (txtnumeroletras.length == 0 || txtnumeroletras == '' || Number(txtnumeroletras) == 0) {
                        toastr.error("Ingrese el número de letras", 'Mensaje del sistema');
                        return;
                    }
                <?php endif; ?>
            }
        <?php endif; ?>
        Swal.fire({
            title: cmensaje,
            text: "Por Favor verificar los datos de la compra, pasado tres (3) días no se podrá modificar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let tdoc = document.getElementById("cmbdcto").value;
                let num = document.querySelector("#cndoc2").value
                if (num.length < 8) {
                    while (num.length < 8)
                        num = '0' + num;
                }
                let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
                let form = document.getElementById("cmbforma").value;
                let deta = document.querySelector("#txtdetalle").value;
                let impo = document.querySelector("#total").value;
                let ndo2 = document.querySelector("#ndo2").value;
                let mon = document.getElementById("cmbmoneda").value;
                let fechi = document.getElementById("txtfechai").value;
                let fechf = document.getElementById("txtfechaf").value;
                let dolar = document.getElementById("txtdolar").value;
                let idprov = document.getElementById("txtidproveedor").value;
                let alm = document.getElementById("cmbAlmacen").value;
                let valor = document.querySelector("#subtotal").value;
                let nigv = document.querySelector("#igv").value;
                let igv = obtenerTipoIGV();
                // "valor" => $request->get("valor"),
                // "nigv" => $request->get("nigv"),
                // "impo" => $request->get("impo"),
                data = new FormData();
                data.append("tdoc", tdoc);
                data.append("cndoc", cndoc);
                data.append("form", form);
                data.append("fechi", fechi);
                data.append("fechf", fechf);
                data.append("deta", deta);
                data.append("valor", valor);
                data.append("nigv", nigv);
                data.append("impo", impo);
                data.append("ndo2", ndo2);
                data.append("mon", mon);
                data.append("dolar", dolar);
                data.append("idprov", idprov);
                data.append("txtproveedor", $("#txtproveedor").val());
                data.append("txtrucproveedor", $("#txtrucproveedor").val());
                data.append("alm", alm);
                data.append("igv", igv);
                data.append("pimpo", $("#txtpercepcion").val());
                data.append("actualizarprecios", $("#actualizarprecios").val());
                data.append("cmbtipodocumentocuentasxpagar", $("#cmbtipodocumentocuentasxpagar").val());
                data.append("cuentasxpagar", JSON.stringify(detalle));
                <?php
                $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
                if ($tipocompraexon == 'S') { ?>
                    data.append("exonerado", $("#exonerado").val())
                <?php   } ?>
                axios.post("/compras/registrar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        // console.log(data);
                        $('#detalle').html(tabla);
                        // $('#totalpedido').html(document.querySelector("#total").value);
                        // nropedido = document.querySelector("#nropedido").value;
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire({
                            title: "Compra registrada",
                            text: "Se generó la compra correctamente",
                            icon: "success"
                        });
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del Sistema');
                            }
                        } else {
                            toastr.error("Error al registrar compra " + error, "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    function obtenerDolar(fech) {
        const data = new FormData();
        axios.get('/dolar/obtenerdolar', {
            "params": {
                "fech": fech
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#divdolar').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    function grabarCabecera() {
        let idprov = document.getElementById("txtidproveedor").value;
        let razo = document.getElementById("txtproveedor").value;
        let ruc = $("#txtrucproveedor").val();
        let tdoc = document.getElementById("cmbdcto").value;
        let cndoc = document.querySelector("#cndoc1").value;
        let num = document.querySelector("#cndoc2").value;
        let ndo2 = document.querySelector("#ndo2").value;
        let form = document.getElementById("cmbforma").value;
        let deta = document.querySelector("#txtdetalle").value;
        let mone = document.getElementById("cmbmoneda").value;
        let fechi = document.getElementById("txtfechai").value;
        let fechf = document.getElementById("txtfechaf").value;
        let dolar = document.getElementById("txtdolar").value;
        let alm = document.getElementById("cmbAlmacen").value;
        var optigv = obtenerTipoIGV();
        data = new FormData();
        data.append("idprov", idprov);
        data.append("razo", razo);
        data.append("ruc", ruc);
        data.append("tdoc", tdoc);
        data.append("cndoc", cndoc);
        data.append("num", num);
        data.append("ndo2", ndo2);
        data.append("alm", alm);
        data.append("form", form);
        data.append("mone", mone);
        data.append("fechi", fechi);
        data.append("fechf", fechf);
        data.append("dolar", dolar);
        data.append("deta", deta);
        data.append("optigv", optigv);
        axios.post("/compras/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión " + error, "Mensaje del Sistema");
            });
    }

    $(".tipodocumentos").on("change", function() {
        isFormatSerie();
    });

    function calcularIGV() {
        igv = obtenerTipoIGV();
        var total_col = 0;
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            total_col += parseFloat($(this).find('td').eq(6).find("input").val());
        });
        <?php
        $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
        if ($tipocompraexon == 'S') : ?>
            totalexon = 0;
            $('#griddetalle tbody tr').each(function() {
                _tr = $(this);
                td = _tr.find("td").eq(7).find("input");
                var subtotal = _tr.find("td").eq(6).find("input").val();
                var isChecked = $(td).is(":checked");
                if (isChecked) {
                    totalexon = totalexon + Number(subtotal);
                }
            });
            $("#exonerado").val(Number(totalexon).toFixed(2))
            if (totalexon > 0) {
                total_col = total_col - totalexon;
            }
        <?php endif; ?>
        if (igv == 'I') {
            //Si el IGV está incluido
            let impo = (Number(total_col)).toFixed(2);
            let valor = (impo / 1.18).toFixed(2);
            let nigv = (impo - valor).toFixed(2);
            $("#igv").val(nigv);
            $("#subtotal").val(valor);
            $("#total").val(impo);
        } else {
            impo = Number(total_col);
            $("#subtotal").val(impo.toFixed(2));
            valorigv = (impo * 0.18).toFixed(2);
            $("#igv").val(valorigv);
            // $("#igv").val("18");
            imponoigv = ((impo * 0.18) + impo);
            $("#total").val(imponoigv.toFixed(2));
        }
        <?php
        $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
        if ($tipocompraexon == 'S') : ?>
            if (totalexon > 0) {
                impo = total_col + totalexon;
                $("#total").val(impo.toFixed(2));
            }
        <?php endif; ?>
        calcularpercepcion();
        let impor = $("#total").val();
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
    }

    function calcularpercepcion() {
        subtotal = $("#total").val();
        nper = <?php echo round($_SESSION['gene_nper']) / 100; ?>;
        percepcion = Number(subtotal) * Number(nper);
        total = Number(subtotal) + Number(percepcion);
        if ($("#cbpercepcion").is(':checked')) {
            $("#txtpercepcion").val(percepcion.toFixed(2));
            $("#txttotalpercepcion").val(total.toFixed(2));
        };
        if ($("#cbpercepcion").is(':checked') == false) {
            $("#txtpercepcion").val("0.00");
            subtotal = $("#total").val();
            $("#txttotalpercepcion").val(subtotal);
            // igv = obtenerTipoIGV();
            // var total_col = 0;
            // $('#griddetalle tbody').find('tr').each(function(i, el) {
            //     total_col += parseFloat($(this).find('td').eq(6).text());
            // });
            // if (igv == 'I') {
            //     //Si el IGV está incluido
            //     let impo = (Number(total_col)).toFixed(2);
            //     let valor = (impo / 1.18).toFixed(2);
            //     let nigv = (impo - valor).toFixed(2);
            //     $("#igv").val(nigv);
            //     $("#subtotal").val(valor);
            //     $("#total").val(impo);
            // } else {
            //     impo = Number(total_col);
            //     $("#subtotal").val(impo.toFixed(2));
            //     $("#igv").val("18");
            //     imponoigv = ((impo * 0.18) + impo);
            //     $("#total").val(imponoigv.toFixed(2));
            // }
            // let impor = $("#total").val();
            // if (isNaN(impor)) {
            //     $("#subtotal").val("0.00");
            //     $("#igv").val("0.00");
            //     $("#total").val("0.00");
            // }
        };
    }

    function actualizar(cmensaje, actualizarprecios) {
        Swal.fire({
            title: cmensaje,
            text: "Por Favor verificar los datos de la compra, pasado tres (3) días no se podrá modificar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                let tdoc = document.getElementById("cmbdcto").value;
                let num = document.querySelector("#cndoc2").value
                if (num.length < 8) {
                    while (num.length < 8)
                        num = '0' + num;
                }
                let cndoc = (document.querySelector("#cndoc1").value + num).toUpperCase();
                let form = document.getElementById("cmbforma").value;
                let deta = document.querySelector("#txtdetalle").value;
                let impo = document.querySelector("#total").value;
                let ndo2 = document.querySelector("#ndo2").value;
                let mon = document.getElementById("cmbmoneda").value;
                let fechi = document.getElementById("txtfechai").value;
                let fechf = document.getElementById("txtfechaf").value;
                let dolar = document.getElementById("txtdolar").value;
                let idprov = document.getElementById("txtidproveedor").value;
                let alm = document.getElementById("cmbAlmacen").value;
                let valor = document.querySelector("#subtotal").value;
                let nigv = document.querySelector("#igv").value;
                let igv = obtenerTipoIGV();
                data = new FormData();
                data.append("tdoc", tdoc);
                data.append("cndoc", cndoc);
                data.append("form", form);
                data.append("fechi", fechi);
                data.append("fechf", fechf);
                data.append("deta", deta);
                data.append("valor", valor);
                data.append("nigv", nigv);
                data.append("impo", impo);
                data.append("ndo2", ndo2);
                data.append("mon", mon);
                data.append("dolar", dolar);
                data.append("idprov", idprov);
                data.append("txtproveedor", $("#txtproveedor").val());
                data.append("alm", alm);
                data.append("igv", igv);
                data.append("pimpo", $("#txtpercepcion").val());
                data.append("actualizarprecios", $("#actualizarprecios").val());
                <?php
                $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
                if ($tipocompraexon == 'S') { ?>
                    data.append("exonerado", $("#exonerado").val())
                <?php } ?>
                axios.post("/compras/actualizar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        // console.log(data);
                        $('#detalle').html(tabla);
                        // $('#totalpedido').html(document.querySelector("#total").value);
                        // nropedido = document.querySelector("#nropedido").value;
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire(' Se Actualizó la Compra correctamente ');
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del sistema');
                            }
                        } else {
                            toastr.error("Error al actualizar compra" + error, "Mensaje del Sistema");
                        }
                    });
            }
        });
    }

    //Eventos
    var input = document.getElementById('cndoc2');
    input.addEventListener('input', function() {
        if (this.value.length > 8)
            this.value = this.value.slice(0, 8);
    })

    const hiddenInput = document.querySelector('#txtfechaf');
    document.querySelector('#txtfechai').addEventListener('change', (event) => {
        hiddenInput.value = event.target.value;
        $("#txtfechaf").val(hiddenInput.value);
    });

    var txtfecha = document.getElementById("txtfechai");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfechai").val();
        grabarCabecera();
        obtenerDolar(fech)
    }, true);

    var txtfechaf = document.getElementById("txtfechaf");
    txtfechaf.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var cndoc1 = document.getElementById("cndoc1");
    cndoc1.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var cndoc2 = document.getElementById("cndoc2");
    cndoc2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var ndo2 = document.getElementById("ndo2");
    ndo2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    // var deta = document.getElementById("detalle");
    // deta.addEventListener("blur", function(event) {
    //     grabarCabecera();
    // }, true);

    $("#griddetalle tr:last td:eq(6) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            clicksubtotal = 0;
        }
    });

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
        }
    });

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("indice", i);
            axios.post('/compras/EditarUno', data)
                .then(function(respuesta) {}).catch(function(error) {
                    console.log(error);
                });
        });
    }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        // var id2 = document.getElementById("2");
        // $(id2).removeAttr('id', '2');
        var id3 = document.getElementById("3");
        $(id3).removeAttr('id', '3');

        //Obtenemos la celda cant y le asignamos un id
        cant = $(o).find("input");
        $(cant).attr('id', '1');
        $("#1").select();

        //Obtenemos la celda precios y precio, a ambos le asignamos un id
        var tr = $(o).parent();
        // tr.find("td").eq(5).attr('id', '2');
        tr.find("td").eq(5).find("input").attr('id', '3');
        //Buscamos lo que hay dentro de la celda precios
        // var p = document.getElementById('precios_' + i);
        //Obtenemos la celda cantidad con función enter
        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#1').removeClass('focus');
                // $('#1').removeAttr('contenteditable');
                // $('#3').focus();
                tr.find("td").eq(4).removeClass('focus');
                $("#3").select();
            }
        });
        // var preci = document.getElementById("precios_" + i);
        // // console.log(preci)
        // $('body').on('keydown', preci, function(e) {
        //     if (e.which == 9) {
        //         e.preventDefault();
        //         $('#precios_' + i).blur();
        //         $('#3').addClass('focus');
        //         $('#3').focus();
        //     }
        // });
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#3').removeClass('focus');
                // $('#3').removeAttr('contenteditable');
                // $('#body').trigger('click');
                tr.find("td").eq(5).removeClass('focus');
                $("#3").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    }

    // Evento enter con precio
    $("table tbody tr td:nth-child(6) input").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        tr = $(this).closest('tr');
        $(this).attr('id', '3')
        $(this).select()
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#3').removeClass('focus');
                // $('#3').removeAttr('contenteditable');
                // $('#body').trigger('click');
                tr.find("td").eq(5).removeClass('focus');
                $("#3").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    });

    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(6).find("input");
        // if (isNaN(subt)) {
        //     toastr.info("Dígite un número correcto")
        // } else {
        //     campo.html(subt.toFixed(2));
        //     var total_col1 = 0;
        //     $('#griddetalle tbody').find('tr').each(function(i, el) {
        //         //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        //         total_col1 += parseFloat($(this).find('td').eq(6).text());
        //         calcularIGV();
        //         // $('#totalpedido').text(total_col1.toFixed(2));
        //     });
        // }
        if (clicksubtotal == 0) {
            var subt = parseFloat(cant) * parseFloat(prec);
            if (isNaN(subt)) {
                toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
            } else {
                $(campo).val(subt.toFixed(2));
                $('#griddetalle tbody').find('tr').each(function(i, el) {
                    calcularIGV();
                });
            }
        } else {
            campo = $(campo).val();
            prec = campo / cant;
            _tr.find("td").eq(5).find("input").val(Number(prec).toFixed(2));
            $('#griddetalle tbody').find('tr').each(function(i, el) {
                calcularIGV();
            });
        }
    }

    //Funcionamiento del combobox
    function obtenerPrecio(o, i) {
        $(o).each(function() {
            var precios = $(this).find("#precios_" + i).val();
            $(this).find(".precio").text(precios);
        });
        calcularsubtotal(o);
        actualizarProducto(o, i);
    }

    $('#cbpercepcion').change(function() {
        calcularpercepcion()
    });

    $("#modal-mantenimiento").on("shown.bs.modal", function() {
        $("#txtdescrip").click();
        $("#txtdescrip").select();
    });
</script>
<?php
$this->endSection("javascript");
?>