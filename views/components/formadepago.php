<div class="input-group mb-3">
    <label class="form-control form-control-sm" for="">Pago:</label>
    <select class="form-select-sm form-control form-control-sm" onkeypress="enterformadepago(this)" id="cmbforma" name="cmbforma" onchange="grabarCabecera();">
        <option <?php echo empty($cform) ? 'selected' : ($cform == 'E' ? 'selected' : '') ?> value="E">Efectivo</option>
        <option <?php echo ($cform == 'C' ? 'selected' : '') ?> value="C">Crédito</option>
        <option <?php echo ($cform == 'D' ? 'selected' : '') ?> value="D">Depósito</option>
        <option <?php echo ($cform == 'T' ? 'selected' : '') ?> value="T">Tarjeta</option>
        <option <?php echo ($cform == 'Y' ? 'selected' : '') ?> value="Y">YAPE / PLIN</option>
    </select>
</div>