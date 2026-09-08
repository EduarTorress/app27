<div class="input-group mb-3">
    <input type="number" maxlength="3" name="txtdias" id="txtdias" class="form-control form-control-sm" placeholder="Días vto." onkeyup="calcularfechavto();">
    <label class="form-control form-control-sm" for="">Vto.</label>
    <input type="date" name="txtfechavto" id="txtfechavto" class="form-control form-control-sm" style="width:90px;" value="<?php echo date('Y-m-d'); ?>">
</div>