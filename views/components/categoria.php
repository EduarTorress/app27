<label for="" class="">Categoria:</label>
<div>
    <select class="form-control form-control-sm" id="cmbcategoria" name="cmbcategoria" aria-label=".form-select-lg example">
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <option <?php echo ($idcat > 0) ? ($item['idcat'] == $idcat ? 'selected' : '') : '' ?> value="<?php echo $item['idcat'] ?>"><?php echo $item['dcat'] ?></option>
        <?php endforeach; ?>
    </select>
</div>