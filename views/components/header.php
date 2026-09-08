<div class="input-group divtienda">
    <label class="form-control form-control-sm" for="">Tienda:</label>
    <select onchange="grabarCabecera();" class="form-select-sm form-control form-control-sm" id="cmbAlmacen" name="cmbAlmacen" disabled>
        <option value="0" selected>Seleccione</option>
        <?php
        $i = 0;
        foreach ($empresas as $empresa) :
            if ($i == 0) : ?>
                <option value="<?php echo $empresa['idalma'] ?>" <?php echo empty($cempresa) ? 'selected' : ($cempresa == $empresa['idalma'] ? 'selected' : '') ?>><?php echo $empresa['nomb'] ?></option>
            <?php else : ?>
            <option value="<?php echo $empresa['idalma'] ?>" <?php echo ($cempresa == $empresa['idalma'] ? 'selected' : '') ?>><?php echo $empresa['nomb'] ?></option>
        <?php endif;
            $i++;
        endforeach
        ?>
    </select>
</div>