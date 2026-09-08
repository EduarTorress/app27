<label for="" class="">Marca:</label>
<div>
    <select class="form-control form-control-sm" id="cmbmarca" name="cmbmarca" aria-label=".form-select-lg example">
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <option <?php echo ($idmar > 0) ? ($item['idmar'] == $idmar ? 'selected' : '') : '' ?> value="<?php echo $item['idmar'] ?>"><?php echo $item['dmar'] ?></option>
        <?php endforeach; ?>
    </select>
</div>