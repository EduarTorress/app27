<label for="" class="">Flete:</label>
<div>
    <select onchange="obtenerFlete();" class="form-control form-control-sm" id="cmbCostoT" name="cmbCostoT" aria-label="">
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <option <?php echo ($idflete > 0) ? ($item['idflete'] == $idflete ? 'selected' : '') : '' ?> value="<?php echo $item['idflete'] . "-" . $item['prec']?>"><?php echo $item['desflete']  ?></option>
        <?php endforeach; ?>
    </select>
</div>