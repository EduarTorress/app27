<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%">Opciones</th>
                <th scope="col" style="width:3%;" class="codigo">Código</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" class="text-center" style="width:5%">Cantidad</th>
                <th scope="col" class="text-center" style="width:5%">Peso KG</th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>)" onblur="actualizarProducto(this,<?php echo $indice ?>)">
                        <?php
                        $parametro1 = $item['descripcion'];
                        $parametro12 = '';
                        $parametro13 = $item['descripcion'];
                        $parametro14 = $item['coda'];
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
                        </td>
                        <td class="codigo"><?php echo $item['coda'] ?></td>
                        <td class="descri"><?php echo $item['descripcion'] ?></td>
                        <td class="unidad"><?php echo $item['unidad'] ?></td>
                        <td class="cantidad" style="text-align: center;" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="cantidad"><?php echo round($item['cantidad'], 4) ?></td>
                        <td class="precio" style="text-align: center;" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><?php echo round($item['peso'], 5) ?></td>
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
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiarGuia()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="Guia();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
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
    $(document).ready(function() {
        $(".codigo").css("display", "none");
    });

    //No admitir letras, solo numeros con punto y coma.
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) && (charCode !== 8) && (charCode !== 46)) {
            return false;
        }
        return true;
    }

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        $('#2').attr('contenteditable', 'true');
        $('#3').attr('contenteditable', 'true');
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
            data.append("txtcantidad", _tr.find("td").eq(4).html());
            data.append("txtpeso", _tr.find("td").eq(5).html());
            // data.append("presseleccionada", cmbpresentacion[0]);
            // data.append("unidad", textpresentacion[0].trim());
            // data.append("cantequi", textpresentacion[1]);
            data.append("indice", i);
            axios.post('/guiasd/EditarUno', data)
                .then(function(respuesta) {
                    // console.log('correctamente editado')
                }).catch(function(error) {
                    if (error.hasOwnProperty("response")) {
                        if (error.response.status === 422) {
                            console.log(error)
                        }
                    }
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
                $('#2').focus().select();
            }
        });

        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });

        var scop = document.getElementById("3");
        scop.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
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

    // Evento enter 
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
        var peso = _tr.find("td").eq(5).html();
        var cant = _tr.find("td").eq(4).html();
        var subt = parseFloat(peso) * parseFloat(cant);
        // var campo = _tr.find("td").eq(6);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto",'Mensaje del Sistema')
        }
        // campo.html(subt.toFixed(2));
        // var total_col1 = 0;
        // $('table tbody').find('tr').each(function(i, el) {
        //     //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        //     total_col1 += parseFloat($(this).find('td').eq(6).text());
        // });
    }

    function verificarValores(o) {
        calcularsubtotal(o);
        calcularPesoTotal();
    }

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        $("#griddetalle tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').text());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').text());
            pesoTotal.push(peso);
            var pesot = cantidad * peso;
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#total").val(total.toFixed(2));
        } else {
            $("#total").val("0.00");
        }
    }
</script>