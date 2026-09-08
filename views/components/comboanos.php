<div class="form-group row">
    <label for="" class="col-sm-1 col-form-label">Periodo</label>
    <div class="col-sm-3">
        <select id="<?php echo ($canos == 'V' ? 'cmbanov' : 'cmbanoc'); ?>" class="form-control cmbanosproducto">
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
        </select>
    </div>
    <div class="col-sm">
        <button class="btn btn-success" onclick="<?php echo ($canos == 'V' ? 'consultarvtasxprod()' : 'consultarcompxprod()'); ?>">Consultar</button>
    </div>
</div>
<script>
    $(".cmbanosproducto").val("<?php echo date('Y') ?>");
</script>