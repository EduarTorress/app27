<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%">Opciones</th>
                <th scope="col" style="width:3%;" class="codigo">Código</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" class="text-center" style="width:5%">Cantidad</th>
                <th scope="col" class="text-center" style="width:5%">Peso</th>
                <th scope="col" class="scop" style="width:5%">SCOP</th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>);" onblur="actualizarProducto(this,<?php echo $indice ?>);">
                        <?php
                        $parametro1 = $item['descri'];
                        $parametro2 = $item['coda'];
                        $parametro3 = $item['unidad'];
                        $parametro4 = $item['stock'];
                        $parametro5 = $item['precio1'];
                        $parametro6 = $item['precio2'];
                        $parametro7 = $item['precio3'];
                        $parametro8 = $item['costo'];
                        $parametro9 = $item['cantidad'];
                        $parametro10 = 1;
                        $parametro11 = $indice;
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <td><button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                            <!-- <button class="btn btn-success" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button> -->
                        </td>
                        <td class="codigo"><?php echo $item['coda'] ?></td>
                        <td class="descri"><?php echo $item['descri'] ?></td>
                        <td class="unidad"><?php echo $item['unidad'] ?></td>
                        <td class="cantidad" style="text-align: center;" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="cantidad"><?php echo round($item['cantidad'], 4) ?></td>
                        <td class="precio" style="text-align: center;" id="precio" onkeypress="return isNumber(event);" contenteditable="true" name="precio"><?php echo round($item['peso'], 5) ?></td>
                        <td class="scop" style="text-align: center;" id="scop" contenteditable="true" name="scop"><?php echo $item['scop'] ?></td>
                        <!-- <td class="text-center" class="total"><?php echo round($item['cantidad'] * $item['peso'], 2) ?></td> -->
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
                    <!-- "/productos/index/3" para index de productos de ventas  -->
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiarTodo()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="Guia();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo  $items ?>" readonly>
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
    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $(".scop").css("display", "none");
    });

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        // $('#2').attr('contenteditable', 'true');
        $('#3').attr('contenteditable', 'true');
    });

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtpeso", _tr.find("td").eq(5).html());
            data.append("txtcantidad", _tr.find("td").eq(4).html());
            data.append("txtscop", _tr.find("td").eq(6).html());
            data.append("indice", i);
            axios.post('/guiasr/EditarUno', data)
                .then(function(respuesta) {
                    //Respuesta
                    //console.log('correctamente editado')
                }).catch(function(error) {
                    console.log(error);
                });
        });
    }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        $(o).attr('id', '1');

        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#1').removeClass('focus');
                $('#1').removeAttr('contenteditable');
                // $('#2').focus().select();
            }
        });

        // var prec = document.getElementById("2");
        // prec.addEventListener("keypress", function(event) {
        //     if (event.key === "Enter") {
        //         event.preventDefault();
        //         $('#2').removeClass('focus');
        //         $('#2').removeAttr('contenteditable');
        //         $('#body').trigger('click');
        //     }
        // });

        // var scop = document.getElementById("3");
        // scop.addEventListener("keypress", function(event) {
        //     if (event.key === "Enter") {
        //         event.preventDefault();
        //         $('#3').removeClass('focus');
        //         $('#3').removeAttr('contenteditable');
        //         $('#body').trigger('click');
        //     }
        // });
    }

    // Evento enter con el cantidad
    $("table tbody tr td:nth-child(6)").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        $(this).attr('id', '2')
        var cant = document.getElementById("2");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    // Evento enter con precio
    $("table tbody tr td:nth-child(7)").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        $(this).attr('id', '3')
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).html();
        var prec = _tr.find("td").eq(5).html();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(6);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto")
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
                total_col1 += parseFloat($(this).find('td').eq(6).text());
            });
        }
    }

    function verificarValores(o) {
        // calcularsubtotal(o);
        calcularPesoTotal();
    }

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        i = 0;
        $("#griddetalle tbody > tr").each(function(index) {
            i = i + 1;
            var cantidad = Number($(this).find('.cantidad').text());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').text());
            pesoTotal.push(peso);
            var pesot = cantidad * peso;
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#total").val(total.toFixed(2));
            $("#totalitems").val(i)
        } else {
            $("#total").val("0.00");
        }
    }
</script>