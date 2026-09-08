<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:3%" class="codigo">Código</th>
                <th scope="col" style="width:3%" class="eliminar">Eliminar</th>
                <th scope="col" style="width:28%">Producto</th>
                <th scope="col" style="width:5%">U.M.</th>
                <th scope="col" style="width:5%">Cantidad</th>
                <th scope="col" style="width:5%">Precio</th>
                <th scope="col" style="width:5%" class="preciosgv">Valor Unitario</th>
                <th scope="col" style="width:5%">Importe</th>
                <th scope="col" style="width:3%" class="tipoproducto"></th>
                <th scope="col" style="width:3%" class="costo"></th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <tr onkeyup="verificarValores(this)" onkeypress="actualizarProducto(this,<?php echo $indice ?>)">
                    <td class="coda"><?php echo $item['coda'] ?></td>
                    <td class="eliminar">
                        <button class="btn btn-warning" onclick="quitaritem(this)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                    </td>
                    <td class="descripcion"><?php echo $item['descripcion'] ?></td>
                    <td class="unidad"><?php echo $item['unidad'] ?></td>
                    <td class="cantidad" style="text-align: center;" contenteditable="false" name="cantidad"><?php echo round($item['cantidad'], 2) ?></td>
                    <td class="precio" style="text-align: center;" id="precio" contenteditable="false" name="precio"><?php echo round($item['precio'], 3) ?></td>
                    <td class="preciosgv" style="text-align: center;"></td>
                    <td class="total" style="text-align: center;" contenteditable="false"><?php echo round($item['subtotal'], 3) ?></td>
                    <td class="tipoproducto" style="text-align: center;"><?php echo $item['tipoproducto'] ?></td>
                    <td class="costo" style="text-align: center;"><?php echo $item['costo'] ?></td>
                    <?php $i++; ?>
                </tr>
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
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $("td").removeClass("dtr-control");
        $(".coda").css("display", "none");
        $(".eliminar").css("display", "none");
        $(".tipoproducto").css("display", "none");
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

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtprecio", _tr.find("td").eq(4).html());
            data.append("txtcantidad", _tr.find("td").eq(3).html());
            // console.log(_tr.find("td").eq(4).html())
            data.append("indice", i);
            axios.post('/vtas/EditarUno', data)
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
        //Obtenemos la celda cant y le asignamos un id
        $(o).attr('id', '1');
        //Obtenemos la celda precios y precio, a ambos le asignamos un id
        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');

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

    function calcularsubtotal(o) {
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).html();
        var prec = _tr.find("td").eq(5).html();

        var subt = parseFloat(cant) * parseFloat(prec);
        console.log(subt);
        var campo = _tr.find("td").eq(7);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
                total_col1 += parseFloat($(this).find('td').eq(7).text());
                calcularIGV();
            });
        }
    }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
    }
</script>