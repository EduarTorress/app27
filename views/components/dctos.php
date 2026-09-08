<div class="input-group mb-1">
    <label class="form-control form-control-sm" id="labeldcto" for="cmdcto">Dcto:</label>
    <select onchange="grabarCabecera();" onkeypress="entertipodocumento(this)" class="form-control form-control-sm tipodocumentos" id="cmbdcto" name="cmbdcto">
        <?php foreach ($lista['lista']['items'] as $row) : ?>
            <option <?php echo ($row['tdoc'] == $tdoc ? 'selected' : '') ?> value="<?php echo $row['tdoc'] ?>"><?php echo $row['nomb'] ?></option>
        <?php endforeach; ?>
    </select>
</div>