<label class="col-sm-4 col-form-label" for="nombre">Tipo:</label>
<div class="col-sm-8">
    <select class="form-select form-control form-control-sm cmbtipousuario" id="cmbtipousuario" name="cmbtipousuario">
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <option <?php echo ($item['tipo'] == $tipo ? 'selected' : '') ?> value="<?php echo $item['tipo'] ?>"><?php echo $item['tipo'] ?></option>
        <?php endforeach; ?>
    </select>
</div>