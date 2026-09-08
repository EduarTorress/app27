<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:3%" class="codigo">Código</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" style="width:5%">Cantidad</th>
                <th scope="col" style="width:5%">Precio</th>
                <th scope="col" style="width:5%" class="preciosgv">Valor Unitario</th>
                <th scope="col" style="width:5%">Importe</th>
                <th scope="col" style="width:3%" class="tipoproducto"></th>
                <th scope="col" style="width:5%" class="valorpreciomenor"></th>
                <th scope="col" style="width:5%" class="costo"></th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this)" onkeypress="actualizarProducto(this,<?php echo $indice ?>)">
                        <?php
                        $parametro1 = $item['descripcion'];
                        $parametro2 = $item['coda'];
                        $parametro3 = $item['unidad'];
                        $parametro4 = $item['stock'];
                        $parametro5 = $item['precio1'];
                        $parametro6 = $item['precio2'];
                        $parametro7 = $item['precio3'];
                        $parametro8 = $item['costo'];
                        $parametro9 = $item['cantidad'];
                        $parametro10 = $item['precio'];
                        $parametro11 = $indice;
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <td class="id"><?php echo $item['coda'] ?></td>
                        <td class="descripcion"><?php echo $item['descripcion'] ?></td>
                        <td class="unidad"><?php echo $item['unidad'] ?></td>
                        <td class="cantidad" style="text-align: center;" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="false" name="cantidad"><?php echo round($item['cantidad'], 2) ?></td>
                        <td class="precio" style="text-align: center;" id="precio" onkeypress="return isNumber(event);" contenteditable="true" name="precio"><?php echo $item['precio3'] ?></td>
                        <td class="preciosgv" style="text-align: center;"></td>
                        <td class="total" style="text-align: center;" contenteditable="false"><?php echo Round($item['precio3'] * $item['cantidad'], 2) ?></td>
                        <td class="tipoproducto" style="text-align: center;"><?php echo $item['tipoproducto'] ?></td>
                        <td class="valorpreciomenor" style="text-align: center;"><?php echo $item['precio3'] ?></td>
                        <td class="costo" style="text-align: center;"><?php echo $item['costo'] ?></td>
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
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiardatos()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="grabarVenta();">Grabar</button>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%; display:none;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value=<?php echo  $items ?> aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-3 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV &emsp;&emsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="<?php ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3 align-items-start">
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL&emsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value=<?php echo  $total ?> disabled>
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
        <input type="hidden" id="idautov" value="<?php echo (isset($idautov) ? $idautov : ''); ?>">
        <input type="hidden" id="idautog" value="<?php echo (isset($idautog) ? $idautog : ''); ?>">
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $(".tipoproducto").css("display", "none");
        $(".valorpreciomenor").css("display", "none");
        $("td").removeClass("dtr-control")
        $(".id").css("display", "none");
        $(".costo").css("display", "none");
        <?php
        $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv'];
        if ($multiigv != 'S') : ?>
            $(".preciosgv").css("display", "none");
        <?php endif; ?>
    });

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'false');
        $('#2').attr('contenteditable', 'true');
        // $('#3').attr('contenteditable', 'false');
    });

    function actualizarProducto(o, i) {}

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        //Obtenemos la celda cant y le asignamos un id
        $(o).attr('id', '1');
        //Obtenemos la celda precios y precio, a ambos le asignamos un id
        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');

        //Buscamos lo que hay dentro de la celda precios
        // var p = document.getElementById('precios_' + i);

        //Obtenemos la celda cantidad con función enter
        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#1').removeClass('focus');
                $('#1').removeAttr('contenteditable');
                $('#2').focus().select();
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
        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    }

    // Evento enter con el cantidad
    $("table tbody tr td:nth-child(5)").click(function() {
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
    // $("table tbody tr td:nth-child(7)").click(function() {
    //     var id = document.getElementById("3");
    //     $(id).removeAttr('id', '3')
    //     $(this).attr('id', '3')
    //     var prec = document.getElementById("3");
    //     prec.addEventListener("keypress", function(event) {
    //         if (event.key === "Enter") {
    //             event.preventDefault();
    //             $('#3').removeClass('focus');
    //             $('#3').removeAttr('contenteditable');
    //             $('#body').trigger('click');
    //         }
    //     });
    // });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(3).html();
        var prec = _tr.find("td").eq(4).html();
        // console.log(cant)
        // console.log(prec);
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(6);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la columna ( .eq(0) representa la columna 1 )     
                total_col1 += parseFloat($(this).find('td').eq(6).text());
                calcularIGV();
            });
        }
    }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
        $(o).each(function() {
            var _tr = $(o);
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(3).html();
            var precio = _tr.find("td").eq(4).html();
            var valorpreciomenor = _tr.find("td").eq(8).html();
            cmbmoneda = $("#cmbmoneda").val();

            if (cmbmoneda == 'D') {
                preciomenor = (valorpreciomenor) / Number("<?php echo strval($_SESSION["gene_dola"]) ?>");
            } else {
                preciomenor = valorpreciomenor;
            }

            preciomenor = Number(valorpreciomenor).toFixed(2);
            if (Number(precio) < preciomenor) {
                _tr.find("td").eq(4).css("backgroundColor", "#F67979");
                $("#grabar").attr("disabled", true);
            } else {
                _tr.find("td").eq(4).css("backgroundColor", "");
                $("#grabar").attr("disabled", false);
            }
        });
    }
</script>