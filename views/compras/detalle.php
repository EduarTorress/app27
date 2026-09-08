<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%" id="headersysven">Opciones</th>
                <th scope="col" style="width:3%" id="headersysven">Código</th>
                <th scope="col" style="width:28%" id="headersysven">Producto</th>
                <th scope="col" style="width:5%" id="headersysven">U.M.</th>
                <th scope="col" style="width:5%; text-align: center;" id="headersysven">Cantidad</th>
                <!-- <th scope="col" style="width:5%">Precios</th> -->
                <th scope="col" style="width:5%;  text-align: center;" id="headersysven">Precio</th>
                <th scope="col" style="width:5%;  text-align: center;" id="headersysven">Importe</th>
                <?php
                $tipocompraexon = (empty($_SESSION['config']['tipocompraexon']) ? 'N' : $_SESSION['config']['tipocompraexon']);
                if ($tipocompraexon == 'S') : ?>
                    <th scope="col" style="width:5%" class="text-center" id="headersysven">No Afecto</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="bodycompras">
            <?php $i = 0; ?>
            <?php foreach ($carritoc as $indice => $item) : ?>
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
                        <td><?php echo $item['coda'] ?></td>
                        <td onclick="seleccionarproducto(<?php echo $indice . ',' . $item['coda'] ?>)">
                            <?php echo $item['descri'] ?>
                        </td>
                        <td><?php echo $item['unidad'] ?></td>
                        <td class="text-center" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="false" name="cantidad"><input onclick="clicksubtotal=0;" type="text" class="inputright" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                        <td class="precio text-center" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><input onclick="clicksubtotal=0;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format($item['precio'], 2, '.', '') ?>"></td>
                        <td class="text-center" class="total"><input onclick="this.select(); clicksubtotal=1;" onkeypress="return isNumber(event);" type="text" class="inputright" value="<?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?>"></td>
                        <?php if ($tipocompraexon == 'S') : ?>
                            <td class="text-center" class="afecto">
                                <?php
                                $checkafecto = "";
                                if (trim($item['checkafecto']) == "true") {
                                    $checkafecto = "checked";
                                }
                                ?>
                                <input type="checkbox" <?php echo $checkafecto; ?> class="" id="checkmarcado<?php echo $indice; ?>" name="checkafecto" onclick="cambiarcheckafecto(this,<?php echo $indice ?>)">
                            </td>
                        <?php endif; ?>
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
        <?php if ($tipocompraexon == 'N') { ?>
            <div class="row">
                <div class="col-7">
                    <div class="form-check" id="inputpercepcion">
                        <input class="form-check-input" type="checkbox" class="" value="S" id="cbpercepcion">
                        <label class="form-check-label" for="">
                            Aplica Percepción
                        </label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="input-group mb-3" style="width: 85%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>Perce.</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="txtpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
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
                <div class="col-2">
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos"><a style="color:white;">Agregar</a></button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarCompra()">Limpiar</button>
                    <?php if (!empty($_SESSION['fusua'])): ?>
                        <span title="No se puede actualizar la compra, porque ya paso tres días de su fecha de registro">
                        <?php endif; ?>
                        <button class="btn btn-success btn-sm" id="grabar" <?php echo (empty($_SESSION['fusua']) ? '' : 'disabled') ?> role="button" onclick="vermodalactualizarprecios();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                        <?php if (!empty($_SESSION['fusua'])): ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-5">
                    <label for="" id="lblimportarxml">Importar desde XML:</label>
                    <input type="file" class="btn btn-secondary btn-sm" onchange="cargararchivoxml(event);" id="btnimportar" accept="text/xml">
                </div>
                <div class="col-2">
                    <div class="input-group mb-3" style="width: 85%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>Pagar</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="txttotalpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>
                <div class="col-3 align-items-start">
                    <div class="input-group mb-3" style="width: 90%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>TOTAL &emsp;&ensp;</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-7">
                    <div class="form-check" id="inputpercepcion">
                        <input class="form-check-input" type="checkbox" class="" value="S" id="cbpercepcion">
                        <label class="form-check-label" for="">
                            Aplica Percepción
                        </label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="input-group mb-3" style="width: 85%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>Perce.</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="txtpercepcion" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>
                <div class="col-3">
                    <div class="input-group " style="width: 90%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>EXON. &emsp;</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="exonerado" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-7">
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos"><a style="color:white;">Agregar</a></button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarCompra()">Limpiar</button>
                    <?php if (!empty($_SESSION['fusua'])): ?>
                        <span title="No se puede actualizar la compra, porque ya paso tres días de su fecha de registro">
                        <?php endif; ?>
                        <button class="btn btn-success btn-sm" id="grabar" <?php echo (empty($_SESSION['fusua']) ? '' : 'disabled') ?> role="button" onclick="vermodalactualizarprecios();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                        <?php if (!empty($_SESSION['fusua'])): ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-2">
                    <div class="input-group mb-3" style="width: 85%;">
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
                <div class="col-2">
                    <label for="" id="lblimportarxml">Importar desde XML:</label>
                    <input type="file" class="btn btn-secondary btn-sm" onchange="cargararchivoxml(event);" id="btnimportar" accept="text/xml">
                </div>
                <div class="col-7"></div>
                <div class="col-3">
                    <div class="input-group mb-3" style="width: 90%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>TOTAL &emsp;</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script>
    calcularIGV();

    function seleccionarproducto(indice, codigoproducto) {
        if (codigoproducto == 0) {
            ie = indice;
            $("#modal_productos").modal("show");
            $("#txtbuscarProducto").click();
            $("#txtbuscarProducto").select();
        }
    }

    $('#cbpercepcion').change(function() {
        calcularpercepcion();
    });

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

    function cambiarcheckafecto(element, i) {
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
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(6).find("input");
        // if (isNaN(subt)) {
        //     toastr.info("Dígite un número correcto",'Mensaje del Sistema')
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
</script>