<label for="">Unidad:</label>
<div>
    <select class="form-control form-control-sm" id="cmbunidad" name="cmbunidad" aria-label="">
        <!-- <option value="UND">UND </option>
        <option value="GLN">GLN </option>
        <option value="MET">MET </option> -->
        <?php foreach ($lista as $item) : ?>
            <option <?php echo ($idunid != '') ? (trim($item) == trim($idunid) ? 'selected' : '') : '' ?> value="<?php echo $item ?>"><?php echo $item ?></option>
        <?php endforeach; ?>
    </select>
    <!-- <input type="text" id="cmbunidad" name="cmbunidad" class="form-control form-control-sm" value="<?php echo (empty($idunid) ? '' : $idunid) ?>"> -->
</div>