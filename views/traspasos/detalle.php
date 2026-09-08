<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%">Opciones</th>
                <th scope="col" class="codigo" style="width:3%">Código</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" style="width:5%">Cantidad</th>
                <!-- <th scope="col" style="width:5%">Precios</th> -->
                <th scope="col" style="width:5%">Peso</th>
                <th scope="col" style="width:5%">Importe</th>
            </tr>
        </thead>
        <tbody id="bodycompras">
            <?php $i = 0; ?>
            <?php foreach ($carritot as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="calcularsubtotal(this); actualizarProducto(this,<?php echo $indice ?>);">
                        <!-- onchange="obtenerPrecio(this,<?php echo $indice ?>);" -->
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
                        <td><?php echo $item['unidad'] ?>
                        </td>
                        <td class="text-center" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="false" name="cantidad"><input type="text" onclick="this.select(); clicksubtotal=0;" class="inputright" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
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
                <br>
                <div class="input-group">
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarTraspaso()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="grabarTraspaso();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo $items ?>" aria-describedby="inputGroup-sizing-sm" readonly>
                </div>
            </div>
            <div class="col-3 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Peso:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" readonly>
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    calcularPesoTotal();

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
        }
    });

    columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6  : 8; ?>";
    $("#griddetalle tr:last td:eq(" + columantotal + ") .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            clicksubtotal = 0;
        }
    });

    $(document).ready(function() {
        $(".codigo").css("display", "none");
        calcularPesoTotal();
    });

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            // cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            // cmbpresentacion = cmbpresentacion.split("-");
            // textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            // textpresentacion = textpresentacion.split("-");
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("unidad", _tr.find("td").eq(3).text());
            data.append("presseleccionada", 0)
            data.append("cantequi", 1);
            data.append("indice", i);
            axios.post('/traspasos/EditarUno', data)
                .then(function(respuesta) {}).catch(function(error) {
                    console.log(error);
                });
        });
    }

    function cambiarpresentacion(o, i) {
        row = $(o).parent().parent();
        $(row).each(function() {
            var _tr = $(row);
            // cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            // cmbpresentacion = cmbpresentacion.split("-");
            // textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            // textpresentacion = textpresentacion.split("-");
            // _tr.find("td").eq(5).find("input").val(Number(cmbpresentacion[1]).toFixed(2));
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
            data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
            data.append("unidad", _tr.find("td").eq(3).text());
            data.append("cantequi", 1);
            data.append("presseleccionada", 0)
            data.append("indice", i);
            axios.post('/traspasos/EditarUno', data)
                .then(function(respuesta) {
                    calcularPesoTotal();
                    calcularsubtotal(row);
                }).catch(function(error) {
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

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        columantotal = "<?php echo empty($_SESSION['config']['tipobotica']) ? 6  : 8; ?>";
        var campo = _tr.find("td").eq(columantotal).find("input");
        if (clicksubtotal == 0) {
            var subt = parseFloat(cant) * parseFloat(prec);
            if (isNaN(subt)) {
                toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
            } else {
                $(campo).val(subt.toFixed(2));
                $('#griddetalle tbody').find('tr').each(function(i, el) {
                    calcularPesoTotal();
                });
            }
        } else {
            campo = $(campo).val();
            prec = campo / cant;
            _tr.find("td").eq(5).find("input").val(Number(prec).toFixed(2));
            $('#griddetalle tbody').find('tr').each(function(i, el) {
                calcularPesoTotal();
            });
        }
    }
</script>