<div class="input-group ">
    <label class="form-control form-control-sm" for="">Vende.</label>
    <select onchange="grabarCabecera();" class="form-select-sm form-control form-control-sm" id="cmbvendedor" name="cmbvendedor" onchange="vendedorseleccionado(this)">
        <option selected value="0"><?php echo 'TODOS' ?></option>
       <?php foreach ($vendedores as $vendedor) { ?>
            <option <?php echo ($idven > 0) ? ($vendedor['idven'] == $idven ? 'selected' : '') : '' ?> value="<?php echo $vendedor['idven'] ?>"><?php echo $vendedor['nomv'] ?></option>
        <?php } ?>
    </select>
</div>