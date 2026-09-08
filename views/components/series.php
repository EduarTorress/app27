<div class="input-group">
    <label class="form-control form-control-sm" for="">Series:</label>
    <select onchange="" class="form-select form-control form-control-sm" id="cmbSerie" name="cmbSerie">
        <option value="" selected>Seleccione</option>
        <?php
        foreach ($series as $serie) : ?>
            <option value=<?php echo $serie['idserie'] ?>><?php echo $serie['serie'] ?></option>
        <?php endforeach
        ?>
    </select>
</div>