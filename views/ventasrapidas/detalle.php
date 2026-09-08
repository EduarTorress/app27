<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%" id="headersysven">Opciones</th>
                <th scope="col" style="width:3%" class="codigo" id="headersysven">Código</th>
                <th scope="col" style="width:28%" id="headersysven">Producto</th>
                <th scope="col" style="width:5%" id="headersysven">U.M.</th>
                <th scope="col" style="width:5%" class="text-center" id="headersysven">Cantidad</th>
                <th scope="col" style="width:5%" class="text-center" id="headersysven">Precio</th>
                <th scope="col" style="width:5%" class="text-center preciosgv" id="headersysven"></th>
                <th scope="col" style="width:5%" class="text-center descuento" id="headersysven"></th>
                <th scope="col" style="width:5%" class="text-center" id="headersysven">Importe</th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>,event);" onblur="actualizarProducto(this,<?php echo $indice ?>,event);">
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
                        <td>
                            <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>,<?php echo $item['coda']; ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                        </td>
                        <td class="codigo"><?php echo $item['coda'] ?></td>
                        <td><?php echo $item['descripcion'] ?></td>
                        <td><?php echo $item['unidad'] ?></td>
                        <td class="text-center cantidad" onclick="funcionEnterCant(this,<?php echo $indice ?>)" contenteditable="false" name="cantidad"><input type="text" class="inputright" onkeyup="abrirmodalvtas(event)" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                        <td class="precio text-center" id="precio" contenteditable="false" name="precio"><input onkeypress="return isNumber(event);" readonly type="text" onkeyup="abrirmodalvtas(event)" class="inputright" value="<?php echo number_format($item['precio'], 2, '.', '') ?>"></td>
                        <td class="preciosgv text-center"></td>
                        <td class="descuento text-end"></td>
                        <td class="text-center" class="total"><input onclick="ubicacionfocus='subtotal';" type="text" class="inputright" onkeyup="abrirmodalvtas(event)" onkeypress="ubicacionfocus='subtotal'; return isNumber(event);" value="<?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?>"></td>
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
            <div class="col-6 align-items-start">
                <br>
                <div class="input-group">
                    <!-- "/productos/index/3" para index de productos de ventas  -->
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarVenta()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="preregistro();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                </div>
            </div>
            <div class="col-2 align-items-start">
               
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo  $items ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV&emsp;&emsp;&nbsp;&nbsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL&emsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo $total ?>" disabled>
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $(".preciosgv").css("display", "none");
        $(".descuento").css("display", "none");
        validarvaloresporgrupo();
        // obtenerdescuento();
    });

    // function editardescuentoxproducto(idart, posicion, descuento) {
    //     const data = new FormData();
    //     data.append("txtcoda", idart);
    //     data.append("descuento", $(descuento).val());
    //     data.append("indice", posicion);
    //     axios.post('/vtas/editardescuentoxproducto', data)
    //         .then(function(respuesta) {
    //             tr = $(descuento).parent().parent();
    //             $(tr).find(".precio input").val(Number(respuesta.data.preciofinal).toFixed(3));
    //             calcularsubtotal($(tr));
    //             $("#descuentogeneral").val("0.00");
    //             obtenerdescuento();
    //         }).catch(function(error) {
    //             console.log(error);
    //             if (trim(error) == 'Network Error') {
    //                 const datanew = new FormData();
    //                 datanew.append("txtcoda", idart);
    //                 datanew.append("descuento", $(descuento).val());
    //                 datanew.append("indice", posicion);
    //                 axios.post('/vtas/editardescuentoxproducto', datanew)
    //                     .then(function(respuesta) {
    //                         tr = $(descuento).parent().parent();
    //                         $(tr).find(".precio input").val(respuesta.data.preciofinal);
    //                         calcularsubtotal($(tr));
    //                         $("#descuentogeneral").val("0.00");
    //                         obtenerdescuento();
    //                     });
    //             }
    //         });
    // }

    // function aplicardescuentogeneral(descuentogeneral) {
    //     $('#griddetalle tbody tr').each(function() {
    //         _tr = $(this);
    //         var descuento = _tr.find("td").eq(7).find("input");
    //         $(descuento).val(descuentogeneral);
    //         $(descuento).blur();
    //     });
    // }

    // function obtenerdescuento() {
    //     var preciosindescuento = 0;
    //     var descuentoold = $('#griddetalle tbody tr').find('td').eq(7).find("input").val();
    //     var todosmismodescuento = true;
    //     $('#griddetalle tbody tr').each(function() {
    //         _tr = $(this);
    //         var cant = _tr.find("td").eq(4).find("input").val();
    //         var precio = _tr.find("td").eq(5).find("input").val();
    //         var descuento = _tr.find("td").eq(7).find("input").val();
    //         if (descuento != descuentoold) {
    //             todosmismodescuento = false;
    //         }
    //         if (Number(descuento) > 0) {
    //             preciosindescuento += (Number(precio) / (1 - (Number(descuento) / 100))) * Number(cant);
    //         } else {
    //             preciosindescuento += Number(precio) * Number(cant);
    //         }
    //         descuentoold = descuento;
    //     });
    //     if (todosmismodescuento == true) {
    //         $("#descuentogeneral").val(descuentoold);
    //     }
    //     $("#totalsindescuento").val(preciosindescuento.toFixed(2));
    // }

    function actualizarProducto(o, i) {
        if (event && event.target.closest(".descuento")) {
            return;
        }
        // $(o).each(function() {
        var _tr = $(o);
        const data = new FormData();
        var id = _tr.find("td").eq(1).html();
        data.append("txtdescri", _tr.find("td").eq(2).html());
        data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
        data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
        data.append("cmbmoneda", $("#cmbmoneda").val());
        // console.log(_tr.find("td").eq(4).html())
        data.append("indice", i);
        axios.post('/ventasrapidas/EditarUno', data)
            .then(function(respuesta) {
                calcularIGV();
                //console.log('correctamente editado')
            }).catch(function(error) {
                console.log(error);
            });
        // });
    }

    function funcionEnterCant(o, i) {
        ubicacionfocus = "cantidad";
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        cant = $(o).find("input");
        $(cant).attr('id', '1');
        $("#1").select();

        var tr = $(o).parent();
        tr.find("td").eq(5).find("input").attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(4).removeClass('focus');
                $("#2").select();
                // $('#1').removeAttr('contenteditable');
                // $('#2').focus().select();
            }
        });

        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#2').removeClass('focus');
                // $('#2').removeAttr('contenteditable');
                tr.find("td").eq(5).removeClass('focus');
                // $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    }

    // Evento enter con el precio
    $("table tbody tr td:nth-child(6) input").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        tr = $(this).closest('tr');
        $(this).attr('id', '2')
        $(this).select()
        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(5).removeClass('focus');
                $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
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
        columnatotal = ($("#griddetalle").find("thead tr:first th").length) - 1;
        var _tr = $(o);
        if (ubicacionfocus == 'cantidad') {
            var cant = _tr.find("td").eq(4).find("input").val();
            var prec = _tr.find("td").eq(5).find("input").val();
            var subt = parseFloat(cant) * parseFloat(prec);
            var campo = _tr.find("td").eq(columnatotal).find("input");
            if (!isNaN(subt)) {
                campo.val(subt.toFixed(3));
                calcularIGV();
            }
        } else {
            var subt = parseFloat(_tr.find("td").eq(columnatotal).find("input").val());
            var prec = parseFloat(_tr.find("td").eq(5).find("input").val());
            var campoCantidad = _tr.find("td").eq(4).find("input");
            if (!isNaN(subt) && !isNaN(prec) && prec != 0) {
                var cant = subt / prec;
                campoCantidad.val(cant.toFixed(3));
                calcularIGV();
            }
        }
    }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
        $(o).each(function() {
            var _tr = $(o);
            let premiun = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();

            if (cmbmoneda == 'D') {
                preciomenor = (resultado.precio3) / Number("<?php echo $_SESSION["gene_dola"] ?>");
            } else {
                preciomenor = resultado.precio3;
            }
            <?php $precioedit = (empty($_SESSION['gene_precioedit']) ? 'N' : $_SESSION['gene_precioedit']); ?>
            <?php if ($precioedit == 'N'): ?>
                preciomenor = Number(preciomenor).toFixed(2);
                if (Number(precio) < preciomenor) {
                    _tr.find("td").eq(5).css("backgroundColor", "#F67979");
                    $("#grabar").attr("disabled", true);
                    toastr.warning("Precio no permitido", 'Mensaje del Sistema');
                } else {
                    _tr.find("td").eq(5).css("backgroundColor", "");
                    $("#grabar").attr("disabled", false);
                }
            <?php endif; ?>
        });
        validarvaloresporgrupo();
    }

     $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
              $("#griddetalle tr:last td:eq(8) .inputright").click();
              $("#griddetalle tr:last td:eq(8) .inputright").select();
              $("#griddetalle tr:last td:eq(8) .inputright").focus();
        }
    });

    $("#griddetalle tr:last td:eq(8) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            // $("#modal_productos").modal('show');
            preregistro();
        }
    });

    function abrirmodalvtas(e) {
        if (e.keyCode == 27) {
            preregistro();
        }
    }

    function validarvaloresporgrupo() {
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            let premiun = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();

            if (cmbmoneda == 'D') {
                preciomenor = (resultado.precio3) / Number("<?php echo $_SESSION["gene_dola"] ?>");
            } else {
                preciomenor = resultado.precio3;
            }

            preciomenor = Number(preciomenor).toFixed(2);
            <?php $precioedit = (empty($_SESSION['gene_precioedit']) ? 'N' : $_SESSION['gene_precioedit']); ?>
            <?php if ($precioedit == 'N'): ?>
                if (Number(precio) < preciomenor) {
                    _tr.find("td").eq(5).css("backgroundColor", "#F67979");
                    $("#grabar").attr("disabled", true);
                } else {
                    _tr.find("td").eq(5).css("backgroundColor", "");
                }
            <?php endif; ?>
        })
    }
</script>