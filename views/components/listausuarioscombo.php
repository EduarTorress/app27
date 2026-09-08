<label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Usuarios:</label>
<div class="col-sm-2">
    <select name="cmbusuarios" class="form-control form-control-sm" id="cmbusuarios">
        <?php foreach ($lista['lista']['items'] as $item) : ?>          
            <option <?php echo ($item['idusua'] == trim($idusua) ? 'selected' : '')?> value="<?php echo $item['idusua'] ?>"><?php echo $item['nomb'] ?></option>
        <?php endforeach; ?>
    </select>
</div>