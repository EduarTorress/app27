<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col" style="width:3%" class="codigo">Código</th>
            <th scope="col" style="width:28%">Producto</th>
            <th scope="col" style="width:5%">Cantidad</th>
            <th scope="col" style="width:5%">Devolución</th>
            <th scope="col" style="width:5%">Precio</th>
            <th scope="col" style="width:5%">Importe</th>
        </tr>
    </thead>
    <tbody id="carritoventas">
        <?php $i = 0; ?>
        <?php foreach ($listado as $indice => $item) : ?>
            <?php if ($item['activo'] == 'A') { ?>
                <tr onkeyup="verificarValores(this)">
                    <td class="codigo"><?php echo $item['idart'] ?></td>
                    <td class="descripcion"><?php echo $item['descri'] ?></td>
                    <td class="cantidad" name="cantidad"><?php echo $item['cant'] ?></td>
                    <td class="devolucion" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="devolucion"><?php echo "0.00" ?></td>
                    <td class="precio" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><?php echo round($item['prec'], 2) ?></td>
                    <td class="importe" contenteditable="false" class="total"><?php echo "0.00" ?></td>
                    <?php $i++; ?>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </tbody>
</table><br>
<div class="col-lg-12">
    <div class="card card-success card-outline" style="width:auto;">
        <div class="row">
            <div class="col-7 align-items-start">
                <br>
                <div class="input-group">
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiar()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="grabar();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%; display:none;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo '0' ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-3 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="<?php echo '0' ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-9">
            </div>
            <div class="col-3">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV&emsp;&emsp;&nbsp;&nbsp;</strong></span>
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
                    <input type="text" ondblclick="desactivarreadonly()" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  '0.00' ?>" readonly onkeyup="calcularmontoxtotal()">
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $('#griddetalle').DataTable({
    //     "paging": false,
    //     "keys": false,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": false,
    //     "info": false,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //             "responsivePriority": 1,
    //             "target": 0
    //         },
    //         {
    //             "responsivePriority": 2,
    //             "target": 2
    //         },
    //         {
    //             "responsivePriority": 3,
    //             "target": -3
    //         },
    //         {
    //             "responsivePriority": 4,
    //             "target": -2
    //         }
    //     ]
    // });

    //No admitir letras, solo numeros con punto y coma.
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode < 48 || charCode > 57) && (charCode !== 8) && (charCode !== 46)) {
            return false;
        }
        return true;
    }

    $(document).ready(function() {
        $(".codigo").css("display", "none");
        $("td").removeClass("dtr-control")
    });


    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        // $('#2').attr('contenteditable', 'true');
        // $('#3').attr('contenteditable', 'true');
    });

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

        // tr.find("td").eq(4).attr('id', '2');

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

        // var prec = document.getElementById("2");
        // prec.addEventListener("keypress", function(event) {
        //     if (event.key === "Enter") {
        //         event.preventDefault();
        //         $('#2').removeClass('focus');
        //         $('#2').removeAttr('contenteditable');
        //         $('#body').trigger('click');
        //     }
        // });
    }

    // Evento enter con el cantidad
    $("table tbody tr td:nth-child(3)").click(function() {
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

    // //Calculamos en el subtotal y total
    // function calcularsubtotal(o) {
    //     var _tr = $(o);
    //     var cant = _tr.find("td").eq(3).html();
    //     var prec = _tr.find("td").eq(4).html();
    //     var subt = parseFloat(cant) * parseFloat(prec);
    //     var campo = _tr.find("td").eq(5);
    //     if (isNaN(subt)) {
    //         toastr.info("Dígite un número correcto",'Mensaje del Sistema')
    //     } else {
    //         campo.html(subt.toFixed(2));
    //         var total_col1 = 0;
    //         $('table tbody').find('tr').each(function(i, el) {
    //             //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )
    //             total_col1 += parseFloat($(this).find('td').eq(5).text());
    //             calcularIGV();
    //         });
    //     }
    // }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
    }
</script>