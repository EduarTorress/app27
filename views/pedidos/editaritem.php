<div class="modal-header">
    <h5 class="modal-title title-center">
        <?php echo $titulo; ?>
    </h5>
</div>
<form action="" id="detalleitem" autocomplete="off">
    <div class="modal-body">
        <div class="mb-3 form-group row">
            <label class="col-sm-0 col-form-label col-form-label-sm">Código:</label>
            <input type="text" disabled style="width: 50px;" class="form-control form-control-sm" id="txtcodigo" name="txtcodigo" value="<?php echo $tipo <> 'N' ? $itemcarrito['coda'] : '' ?> ">
            <input type="text" disabled id="item" style="display:none;">
            <input type="text" disabled id="costo" style="display:none;">
            <input type="text" disabled id="precio1" style="display:none;">
            <input type="text" disabled id="precio2" style="display:none;">
            <input type="text" disabled id="precio3" style="display:none;">
            <input type="hidden" id="tipoproducto" value="<?php echo $tipo <> 'N' ? $itemcarrito['tipoproducto'] : 'K' ?>" style="display:none;">
        </div>
        <div class="mb-3 form-group row">
            <label class="col-sm-0 col-form-label col-form-label-sm">Producto:</label>
            <input type="text" disabled style="width: 400px;" class="form-control form-control-sm" id="txtdescripcion" name="txtdescripcion" value="<?php echo $tipo <> 'N' ? $itemcarrito['descri'] : '' ?>">
        </div>
        <div class="mb-3 form-group row">
            <label class="col-sm-0 col-form-label col-form-label-sm">Unidad:</label>
            <input type="text" disabled style="width: 100px;" class="form-control form-control-sm" id="txtunidad" name="txtunidad" value="<?php echo $tipo <> 'N' ? $itemcarrito['unidad'] : '' ?> ">
        </div>
        <div class="form-group row">
            <label class="col-sm-0 col-form-label-sm">Stock:</label>
            <input type="number" disabled style="width: 100px;" class="form-control form-control-sm" id="txtstock" name="txtstock" value="<?php echo $tipo <> 'N' ? $itemcarrito['stock'] : 0.00 ?>">
        </div>
        <div class="form-group row">
            <label class="col-sm-0 col-form-label-sm" for="">Cantidad:</label>
            <input type="number" style="width: 100px;" class="form-control form-control-sm" name="txtcantidad" id="txtcantidad" placeholder="0.00" value="<?php echo $tipo <> 'N' ? $itemcarrito['cantidad'] : 0.00 ?>">
        </div>
        <div class="mb-3 form-group row">
            <label class="col-sm-0 col-form-label col-form-label-sm">Precios:</label>
            <select class="form-select form-select-sm mb-3" id="cmbprecios" name="cmbprecios" aria-label="">
                <option><?php echo ($tipo <> 'N' ? Round($itemcarrito['precio1'], 2) : 0) ?></option>
                <option><?php echo ($tipo <> 'N' ? Round($itemcarrito['precio2'], 2) : 0) ?></option>
                <option <?php echo 'selected' ?>><?php echo ($tipo <> 'N' ? Round($itemcarrito['precio3'], 2) : 0) ?></option>
            </select>
        </div>
        <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
        <div class="mb-3 form-group row" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
            <label class="col-sm-0 col-form-label col-form-label-sm" for="txtpreciosingv">Precio SGV:</label>
            <input type="number" style="width: 100px;" readonly class="form-control form-control-sm" name="txtpreciosgv" id="txtpreciosgv" placeholder="0.00" value="">
        </div>
        <div class="mb-3 form-group row">
            <label class="col-sm-0 col-form-label col-form-label-sm">Precio:</label>
            <input type="number" style="width: 100px;" onkeyup="calcularigv();" class="form-control form-control-sm" name="txtprecio" id="txtprecio" placeholder="0.00" value="<?php echo ($tipo <> 'N' ? Round($itemcarrito['precio'], 2) : 0.00) ?>">
        </div>
    </div>
    <div class="modal-footer">
        <!-- <?php
                if ($_SESSION['config']['combos'] == 'S') : ?>
            <button type="button" onclick="verdetalle()" class="btn btn-success">Ver detalle</button>
        <?php endif; ?> -->
        <button type="button" class="btn btn-primary" onclick="<?php echo ($tipo <> 'N') ? "actualizaritem()" : "agregarItem()" ?>">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarventana('detalleitem','#item')">Cerrar</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        // $('#detalleitem').submit(function(e) {
        //     e.preventDefault();
        // });
        calcularigv();
        $("#txtcantidad").focus();
        $("#txtcantidad").click();
    });

    function verdetalle() {
        tipoproducto = $("#tipoproducto").val();
        if (tipoproducto != 'C') {
            toastr.error("El producto no es un combo", 'Mensaje del Sistema');
        } else {
            idart = $('#txtcodigo').val();
            verdetallecombo(idart);
        }
    }

    function calcularigv() {
        igv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        preciossinigv = Number($("#txtprecio").val()) / igv;
        $("#txtpreciosgv").val(preciossinigv.toFixed(2));
    }

    var select = document.getElementById('cmbprecios');
    select.addEventListener('change',
        function() {
            var combo = document.querySelector("#cmbprecios");
            var precios = combo.options[combo.selectedIndex];
            document.getElementById("txtprecio").value = precios.value;
            calcularigv();
        });
</script>
<script>
    $('#txtcantidad').keypress(function(e) {
        if (e.keyCode == 13) {
            $("#txtprecio").focus();
            $("#txtprecio").click();
            $("#txtprecio").select();
        }
    });

    $('#txtprecio').keypress(function(e) {
        if (e.keyCode == 13) {
            <?php if ($tipo <> 'N'): ?>
                actualizaritem();
            <?php else: ?>
                agregarItem();
            <?php endif; ?>
        }
    });
</script>