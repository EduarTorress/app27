<div class="form-group row">
    <label class="col-sm-4 col-form-label" for="">Ubigeo:</label>
    <div class="col-sm-8">
        <select name="cmbUbigeo" id="cmbUbigeo"  data-width="100%" class="selectpicker" data-live-search="true">
            <option value="0">Seleccione</option>
            <?php foreach ($ubigeos as $row) : ?>
                <option value=<?php echo $row['ubigeo'] ?> <?php echo ($modo == 'A' ? ($row['ubigeo'] == $ubigeo ? 'selected' : '') : '') ?>><?php echo  trim($row['distrito']) . '-' . trim($row['provincia']) . '-' . trim($row['departamento']) . trim($row['ubigeo']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<script>
    $('#cmbUbigeo').selectpicker();
</script>