<div class="input-group mb-3">
    <label class="form-control form-control-sm" for="">Moneda:</label>
    <select onchange="grabarCabecera(); changedetaildolar();" class="form-select-sm form-control form-control-sm cmbmoneda" id="cmbmoneda" name="cmbmoneda">
        <option <?php echo empty($cmon) ? 'selected ' : ($cmon == 'S' ? 'selected' : '') ?> value="S">Soles</option>
        <option <?php echo ($cmon == 'D' ? 'selected' : '') ?> value="D">Dólares</option>
    </select>
</div>