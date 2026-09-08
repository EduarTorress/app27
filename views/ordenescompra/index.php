<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\IGVComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ValorDolarComponent;
use App\View\Components\ModalProveedorComponent;
use App\View\Components\ModalProductoComponent;
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
            <div class="row">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="<?php echo isset($datosproveedoroc['razooc']) ?  trim($datosproveedoroc['razooc']) : '' ?>">
                        <input type="hidden" id="txtidproveedor" value="<?php echo isset($datosproveedoroc['idprovoc']) ?  $datosproveedoroc['idprovoc'] : '' ?> ">
                        <input type="hidden" id="txtrucproveedor" value=""><input type="hidden" id="txtptopartida" value=""><input type="hidden" id="txtUbigeoproveedor" value="">
                        <input type="hidden" id="txtidauto" value="<?php echo isset($idordencompra) ? $idordencompra : 0 ?>">
                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $ctdoc = isset($datosproveedoroc['tdococ']) ? $datosproveedoroc['tdococ'] : '';
                    $dctos = new DocumentoComponent($ctdoc);
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-3" style="display:none">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="col-sm-0 col-form-label col-form-label-sm">Número: </label>
                        <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm col-3" maxlength="4" id="cndoc1" value="<?php echo (isset($serieoc) ?  trim($serieoc) : '') ?>" placeholder="F001">
                        <input type="text" onkeypress="return isNumberNdoc(event);" onblur="rellenaNumero()" class="form-control form-control-sm" maxlength="8" id="cndoc2" value="<?php echo (isset($numoc) ?  $numoc : '') ?>" placeholder="00000001" pattern="^[0-9]" />
                    </div>
                </div>
                <div class="col-sm-2" style="display:none">
                    <div class="input-group">&nbsp;&nbsp;&nbsp;
                        <label class="form-control form-control-sm">Guía:</label>
                        <input type="text" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosproveedoroc['ndo2oc']) ?  $datosproveedoroc['ndo2oc'] : '' ?>" placeholder="T00100000001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $empresa = isset($datosproveedoroc['almoc']) ? $datosproveedoroc['almoc'] : $_SESSION['idalmacen'];
                    $empresa = new \App\View\Components\EmpresaComponent($empresa);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2 mb-1">
                    <div class="input-group">
                        <label class="form-control form-control-sm">Fecha:</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosproveedoroc['fechoc']) ?  date("Y-m-d") :  $datosproveedoroc['fechoc'] ?>" id="txtfechai" name="txtfechai">
                    </div>
                </div>
                <div class="col-sm-2" style="display:none">
                    <?php
                    $forma = isset($datosproveedoroc['formoc']) ? $datosproveedoroc['formoc'] : '';
                    $formapago = new FormadepagoComponent($forma);
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $mon = isset($datosproveedoroc['moneoc']) ? $datosproveedoroc['moneoc'] : '';
                    $tpmoneda = new TipoMonedaComponent($mon);
                    echo $tpmoneda->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 mb-1">
                    <div class="input-group">
                        <label class="form-control form-control-sm">Forma de Pago:</label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo empty($datosproveedoroc['obsoc']) ?  '' :  $datosproveedoroc['obsoc'] ?>" id="txtobservacion" name="txtobservacion">
                    </div>
                </div>
                <div class="col-sm-4 mb-1">
                    <div class="input-group">
                        <label class="form-control form-control-sm">Despachado:</label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo empty($datosproveedoroc['despoc']) ?  '' :  $datosproveedoroc['despoc'] ?>" id="txtdespacho" name="txtdespacho">
                    </div>
                </div>
                <div class="col-sm-4 mb-1">
                    <div class="input-group">
                        <label class="form-control form-control-sm">Atención:</label>
                        <input type="text" class="form-control form-control-sm" value="<?php echo empty($datosproveedoroc['ateoc']) ?  '' :  $datosproveedoroc['ateoc'] ?>" id="txtatencion" name="txtatencion">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 mb-1" style="display:none">
                    <div class="input-group">
                        <label class="form-control form-control-sm">Fecha Reg. :</label>
                        <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosproveedoroc['fecroc']) ?  '' :  $datosproveedoroc['fecroc']; ?>" id="txtfechaf" name="txtfechaf">
                    </div>
                </div>
                <div class="col-sm-4" style="display:none">
                    <?php
                    $optigv = isset($datosproveedoroc['optigvoc']) ? $datosproveedoroc['optigvoc'] : 'I';
                    $igv = new IGVComponent($optigv);
                    echo $igv->render();
                    ?>
                </div>
                <div class="col-sm-2" id="divdolar" style="display:none">
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
                            <?php if ($v == 'M') : ?>
                                <div class="table-responsive">
                                    <table class="table table-sm small table table-hover" id="griddetalle">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:2%">Opciones</th>
                                                <th scope="col" style="width:3%" class="codigo">Código</th>
                                                <th scope="col" style="width:28%">Producto</th>
                                                <th scope="col" style="width:5%">U.M.</th>
                                                <th scope="col" style="width:5%">Cantidad</th>
                                                <th scope="col" style="width:5%">Precio</th>
                                                <th scope="col" style="width:5%">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodycompras">
                                            <?php $i = 0; ?>
                                            <?php foreach ($carritococ as $indice => $item) : ?>
                                                <?php if ($item['activo'] == 'A') { ?>
                                                    <tr onkeyup="calcularsubtotal(this); actualizarProducto(this,<?php echo $indice ?>); " onchange="obtenerPrecio(this,<?php echo $indice ?>);">
                                                        <?php
                                                        $parametro1 = $item['descri'];
                                                        $parametro2 = $item['coda'];
                                                        $parametro3 = $item['unidad'];
                                                        $parametro4 = $item['cantidad'];
                                                        $parametro5 = $item['precio'];
                                                        $parametro6 = $indice;
                                                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                                                        $cadena_json = json_encode($parametros);
                                                        ?>
                                                        <td>
                                                            <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                                                        </td>
                                                        <td class="codigo"><?php echo $item['coda'] ?></td>
                                                        <td><?php echo $item['descri'] ?></td>
                                                        <!-- <td><?php
                                                                    $presentaciones = json_decode($item['presentaciones'], true); ?>
                                                            <select onchange="cambiarpresentacion(this,<?php echo $indice ?>)" class="form-control form-control-sm" name="cmbpresentaciones" id="cmbpresentaciones">
                                                                <?php foreach ($presentaciones as $p) : ?>
                                                                    <option value="<?php echo $p['epta_idep'] . '-' . $p['epta_prec'] ?>" <?php echo (($p['epta_idep'] == $item['presseleccionada']) ? 'selected' : '') ?>>
                                                                        <?php echo trim($p['pres_desc']) . '-' . $p['epta_cant']; ?>
                                                                    </option>
                                                                <?php endforeach;
                                                                ?>
                                                            </select>
                                                        </td> -->
                                                        <td><?php echo $item['unidad'] ?></td>
                                                        <td class="text-center" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="false" name="cantidad"><input onclick="this.select(); clicksubtotal=0;" type="text" class="inputright" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                                                        <td class="precio text-center" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><input onclick="this.select(); clicksubtotal=0;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format($item['precio'], 2, '.', '') ?>"></td>
                                                        <td class="text-center" class="total"><input onclick="this.select(); clicksubtotal=1;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?>"></td>
                                                        <?php $i++; ?>
                                                    </tr>
                                                <?php } ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div><br>
                                <div class="col-lg-12">
                                    <div class="card card-success card-outline" style="width:auto;">
                                        <div class="row">
                                            <div class="col-7 align-items-start">
                                                <div class="input-group">
                                                    <label class="col-form-label form-control-sm">Observaciones:</label>
                                                    <div>
                                                        <textarea class="form-control form-control-sm" id="txtdetalle" name="txtdetalle" style="width:150%; height:65%;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2 align-items-start">
                                                <div class="input-group mb-3" style="width: 85%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value=<?php echo  $items ?> aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <div class="col-3 align-items-start">
                                                <div class="input-group" style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm"><strong>Sub Total</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <div class="form-check" id="inputpercepcion" style="display:none;">
                                                    <input class="form-check-input" type="checkbox" class="" value="S" id="cbpercepcion">
                                                    <label class="form-check-label" for="">
                                                        Aplica Percepción
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group mb-3" style="width: 85%; display:none;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Perce.</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="txtpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <!-- <div class="col-3">
                                                <div class="input-group " style="width: 90%; display:none;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>EXON. &emsp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="exonerado" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div> -->
                                        </div>
                                        <div class="row">
                                            <div class="col-7">
                                                <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos"><a style="color:white;">Agregar</a></button>
                                                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarCompra()">Limpiar</button>
                                                <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="proceder();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                                            </div>
                                            <div class="col-2">
                                                <div class="input-group mb-3" style="width: 85%; display:none">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>Pagar</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="txttotalpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="input-group " style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>IGV &emsp;&emsp;&ensp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-9"></div>
                                            <div class="col-3 align-items-start">
                                                <div class="input-group mb-3" style="width: 90%;">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text text-sm" id=""><strong>TOTAL &emsp;</strong></span>
                                                    </div>
                                                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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
        clicksubtotal = 0;
        titulo("<?php echo $titulo ?>");
        valor = "<?php echo $v ?>";
        if (valor == 'R') {
            axios.get('/ordenescompra/listardetalle').then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
        }
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='22']").remove();
        $(".codigo").css("display", "none");
        obtenerDolar($("#txtfechai").val());
        calcularIGV();
        $("#cndoc1").val("<?php echo isset($serieoc) ?  $serieoc : '' ?>");
        $("#txtidproveedor").val("<?php echo isset($datosproveedoroc['idprovoc']) ?  $datosproveedoroc['idprovoc'] : '' ?>");
        $("#txtproveedor").val("<?php echo isset($datosproveedoroc['razooc']) ?  $datosproveedoroc['razooc'] : '' ?>");
    }

    $(".tipodocumentos").on("change", function() {
        isFormatSerie();
    });

    $("#modal_productos").on("shown.bs.modal", function() {
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
    });

    $("#modal_proveedor").on("shown.bs.modal", function() {
        $("#txtbuscarprov").focus();
    });

    $("#modal_proveedor").on("hidden.bs.modal", function() {
        grabarCabecera();
    });

    // function calcularpercepcion() {
    //     subtotal = $("#total").val();
    //     nper = <?php echo round($_SESSION['gene_nper']) / 100; ?>;
    //     percepcion = Number(subtotal) * Number(nper);
    //     total = Number(subtotal) + Number(percepcion);
    //     if ($("#cbpercepcion").is(':checked')) {
    //         $("#txtpercepcion").val(percepcion.toFixed(2));
    //         $("#txttotalpercepcion").val(total.toFixed(2));
    //     };
    //     if ($("#cbpercepcion").is(':checked') == false) {
    //         $("#txtpercepcion").val("0.00");
    //         subtotal = $("#total").val();
    //         $("#txttotalpercepcion").val(subtotal);
    //         // igv = obtenerTipoIGV();
    //         // var total_col = 0;
    //         // $('#griddetalle tbody').find('tr').each(function(i, el) {
    //         //     total_col += parseFloat($(this).find('td').eq(6).text());
    //         // });
    //         // if (igv == 'I') {
    //         //     //Si el IGV está incluido
    //         //     let impo = (Number(total_col)).toFixed(2);
    //         //     let valor = (impo / 1.18).toFixed(2);
    //         //     let nigv = (impo - valor).toFixed(2);
    //         //     $("#igv").val(nigv);
    //         //     $("#subtotal").val(valor);
    //         //     $("#total").val(impo);
    //         // } else {
    //         //     impo = Number(total_col);
    //         //     $("#subtotal").val(impo.toFixed(2));
    //         //     $("#igv").val("18");
    //         //     imponoigv = ((impo * 0.18) + impo);
    //         //     $("#total").val(imponoigv.toFixed(2));
    //         // }
    //         // let impor = $("#total").val();
    //         // if (isNaN(impor)) {
    //         //     $("#subtotal").val("0.00");
    //         //     $("#igv").val("0.00");
    //         //     $("#total").val("0.00");
    //         // }
    //     };
    // }

    // function cambiarcheckafecto(element, i) {
    //     marcado = false;
    //     if (element.checked == true) {
    //         marcado = true;
    //     }
    //     const data = new FormData();
    //     data.append("indice", i);
    //     data.append("marcado", marcado);
    //     axios.post('/compras/checkafecto', data)
    //         .then(function(respuesta) {
    //             calcularIGV();
    //         }).catch(function(error) {
    //             if (error.hasOwnProperty("response")) {
    //                 console.log(error);
    //             }
    //         });
    // }

    // function calcularafecto() {
    //     totalexon = 0;
    //     $('#griddetalle tbody tr').each(function() {
    //         _tr = $(this);
    //         td = _tr.find("td").eq(7).find("input");
    //         var subtotal = _tr.find("td").eq(6).text();
    //         var isChecked = $(td).is(":checked");
    //         if (isChecked) {
    //             totalexon = totalexon + Number(subtotal);
    //         }
    //     });
    //     if (totalexon > 0) {
    //         subtotal = $("#subtotal").val();
    //         igv = $("#igv").val();
    //         subtotalafecto = Number(subtotal) / <?php echo $_SESSION['gene_igv'] ?>;
    //         igvafecto = (Number(subtotalafecto) * 0.18);
    //         $("#exonerado").val(Number(totalexon).toFixed(2))
    //         // $("#subtotal").val(subtotalafecto.toFixed(2));
    //         // $("#igv").val(igvafecto.toFixed(2));
    //         $("#subtotal").val("0.00");
    //         $("#igv").val("0.00");
    //     } else {
    //         $("#exonerado").val("0.00")
    //         igv = obtenerTipoIGV();
    //         var total_col = 0;
    //         // var total_noexon = 0;
    //         $('#griddetalle tbody').find('tr').each(function(i, el) {
    //             total_col += parseFloat($(this).find('td').eq(6).text());
    //             // total_noexon += parseFloat($(this).find('td').eq(6).find("input").val());
    //         });
    //         if (igv == 'I') {
    //             //Si el IGV está incluido
    //             let impo = (Number(total_col)).toFixed(2);
    //             let valor = (impo / 1.18).toFixed(2);
    //             let nigv = (impo - valor).toFixed(2);
    //             $("#igv").val(nigv);
    //             $("#subtotal").val(valor);
    //             $("#total").val(impo);
    //         } else {
    //             impo = Number(total_col);
    //             $("#subtotal").val(impo.toFixed(2));
    //             $("#igv").val("18");
    //             imponoigv = ((impo * 0.18) + impo);
    //             $("#total").val(imponoigv.toFixed(2));
    //         }
    //         calcularpercepcion();
    //         let impor = $("#total").val();
    //         if (isNaN(impor)) {
    //             $("#subtotal").val("0.00");
    //             $("#igv").val("0.00");
    //             $("#total").val("0.00");
    //         }
    //     }
    // }

    function agregarunitemVenta(datos) {
        // presentaciones = JSON.parse(datos.parametro11);
        // precio = presentaciones[0]['epta_prec'];
        // unidad = presentaciones[0]['pres_desc']
        // cantequi = presentaciones[0]['epta_cant'];
        // eptaidep = presentaciones[0]['epta_idep'];
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        // data.append("txtunidad", datos.parametro3);
        // data.append("txtprecio", datos.parametro5);
        data.append("txtunidad", datos.parametro3);
        data.append("txtprecio", Number(datos.parametro5).toFixed(2));
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("costo", datos.parametro8);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", 0);
        data.append("cantequi", 1);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/ordenescompra/agregaritem', data)
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
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message, "Mensaje del Sistema");
                    }
                }
            });
    }

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
        }
    });

    $("#griddetalle tr:last td:eq(6) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            clicksubtotal = 0;
        }
    });

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/ordenescompra/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function cancelarCompra() {
        axios.post('/ordenescompra/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            limpiardatos();
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
            console.log(error);
        });
    }

    function limpiardatos() {
        document.querySelector('#txtproveedor').value = "";
        document.getElementById("titulo").innerHTML = "Regs. Orden Compra";
        document.getElementById("grabar").innerHTML = "Grabar";
        document.querySelector("#txtidproveedor").value = "0";
        document.querySelector("#cndoc1").value = "";
        document.querySelector("#cndoc2").value = "";
        document.querySelector("#ndo2").value = "";
        document.querySelector("#cmbforma").value = "E";
        // document.querySelector("#cmbAlmacen").value = "1";
        document.querySelector("#cmbmoneda").value = "S";
        document.querySelector('#txtdolar').value = "";
        window.location.href = '/ordenescompra/index';
    }

    function validarCompra() {
        idProv = document.querySelector('#txtidproveedor').value;
        total = document.querySelector('#total').value;
        if (idProv == 0) {
            toastr.info("Seleccione un proveedor", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese importes válidos", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function proceder() {
        if (!validarCompra()) {
            return;
        }
        grabarCompra();
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
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function grabarCompra() {
        if (!validarCompra()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidauto').value == '0') {
            cmensaje = '¿Registrar Orden de compra?';
            grabar(cmensaje);
        } else {
            cmensaje = '¿Actualizar Orden de compra?';
            actualizar(cmensaje);
        }
    }

    function grabar(cmensaje) {
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
                data.append("txtobservacion", $("#txtobservacion").val())
                data.append("txtdespacho", $("#txtdespacho").val())
                data.append("txtatencion", $("#txtatencion").val())
                axios.post("/ordenescompra/registrar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire({
                            title: "Orden de Compra registrada",
                            text: "Se generó la orden correctamente",
                            icon: "success"
                        });
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                toastr.error(error.response.data.errors, 'Mensaje del Sistema');
                            }
                        } else {
                            toastr.error("Error al registrar la orden" + error, "Mensaje del Sistema");
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
        data.append("txtobservacion", $("#txtobservacion").val())
        data.append("txtdespacho", $("#txtdespacho").val())
        data.append("txtatencion", $("#txtatencion").val())
        axios.post("/ordenescompra/sesion", data)
            .then(function(respuesta) {
                // console.log("Se registro la cabecera en la sesión")
            }).catch(function(error) {
                toastr.error("Error al guardar sesión" + error, "Mensaje del Sistema");
            });
    }

    function calcularIGV() {
        igv = obtenerTipoIGV();
        var total_col = 0;
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            total_col += parseFloat($(this).find('td').eq(6).find("input").val());
        });
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        if (igv == 'I') {
            //Si el IGV está incluido
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
        // calcularpercepcion();
        let impor = $("#total").val();
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
        // calcularafecto();
    }

    function actualizar(cmensaje, actualizarprecios) {
        Swal.fire({
            title: cmensaje,
            text: "Se actualizará en el sistema ",
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
                // data.append("exonerado", $("#exonerado").val())
                data.append("txtobservacion", $("#txtobservacion").val())
                data.append("txtdespacho", $("#txtdespacho").val())
                data.append("txtatencion", $("#txtatencion").val())
                data.append("txtidauto", $("#txtidauto").val())
                axios.post("/ordenescompra/actualizar", data)
                    .then(function(respuesta) {
                        const tabla = respuesta.data;
                        $('#detalle').html(tabla);
                        cancelarCompra();
                        limpiardatos();
                        Swal.fire(' Se actualizó la Orden de Compra correctamente ');
                    }).catch(function(error) {
                        if (error.hasOwnProperty("response")) {
                            if (error.response.status === 422) {
                                toastr.error(error.response.data.errors, 'Mensaje del Sistema');
                            }
                        } else {
                            toastr.error("Error al actualizar orden compra" + error, "Mensaje del Sistema");
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
        obtenerDolar(fech);
        grabarCabecera();
    }, true);

    var txtfechaf = document.getElementById("txtfechaf");
    txtfechaf.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var cndoc1 = document.getElementById("cndoc1");
    cndoc1.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var txtobservacion = document.getElementById("txtobservacion");
    txtobservacion.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var txtdespacho = document.getElementById("txtdespacho");
    txtdespacho.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var txtatencion = document.getElementById("txtatencion");
    txtatencion.addEventListener("blur", function(event) {
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

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("unidad", (textpresentacion[0]).trim());
            data.append("presseleccionada", cmbpresentacion[0])
            data.append("cantequi", textpresentacion[1]);
            data.append("indice", i);
            axios.post('/ordenescompra/EditarUno', data)
                .then(function(respuesta) {}).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            console.log(error);
                        }
                    }
                });
        });
    }

    function cambiarpresentacion(o, i) {
        row = $(o).parent().parent()
        $(row).each(function() {
            var _tr = $(row);
            cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            cmbpresentacion = cmbpresentacion.split("-");
            textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            textpresentacion = textpresentacion.split("-");
            _tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("unidad", (textpresentacion[0]).trim());
            data.append("cantequi", textpresentacion[1]);
            data.append("presseleccionada", cmbpresentacion[0])
            data.append("indice", i);
            axios.post('/ordenescompra/EditarUno', data)
                .then(function(respuesta) {
                    calcularIGV();
                    calcularsubtotal(row);
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status == 422) {
                            console.log(error);
                        }
                    }
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

    //Calculamos en el subtotal y total
    // function calcularsubtotal(o) {
    //     var _tr = $(o);
    //     var cant = _tr.find("td").eq(4).find("input").val();
    //     var prec = _tr.find("td").eq(5).find("input").val();
    //     var subt = parseFloat(cant) * parseFloat(prec);
    //     var campo = _tr.find("td").eq(6);
    //     if (isNaN(subt)) {
    //         toastr.info("Dígite un número correcto")
    //     } else {
    //         campo.html(subt.toFixed(2));
    //         var total_col1 = 0;
    //         $('#griddetalle tbody').find('tr').each(function(i, el) {
    //             //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
    //             total_col1 += parseFloat($(this).find('td').eq(6).text());
    //             calcularIGV();
    //             // $('#totalpedido').text(total_col1.toFixed(2));
    //         });
    //     }
    // }
    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        var campo = _tr.find("td").eq(6).find("input");
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
</script>
<?php
$this->endSection("javascript");
?>