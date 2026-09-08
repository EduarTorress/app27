<div class="input-group">
    <label class="form-control form-control-sm" for="">Tienda:</label>
    <select class="form-select form-control form-control-sm" id="cmbAlmacen" name="cmbAlmacen">
        <?php
        foreach ($empresas as $empresa) : ?>
            <option value=<?php echo $empresa['idalma'] ?> <?php echo ($cempresa == $empresa['idalma'] ? 'selected' : '') ?>  ><?php echo $empresa['nomb'] ?></option>
        <?php endforeach
        ?>
    </select>
</div>