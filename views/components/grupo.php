<label for="" class="">Grupo:</label>
<div>
    <select class="form-control form-control-sm" id="cmbgrupo" name="cmbgrupo" aria-label=".form-select-lg example">
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <option <?php echo ($idgrup > 0) ? ($item['idgrupo'] == $idgrup ? 'selected' : '') : '' ?> value="<?php echo $item['idgrupo'] ?>"><?php echo $item['desgrupo'] ?></option>
        <?php endforeach; ?>
    </select>
</div>