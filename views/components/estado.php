<label for="" class="">Estado:</label>
<div>
    <select class="form-control form-control-sm" id="cmbestado" name="cmbestado" aria-label=".form-select-lg example">
        <option <?php echo ($cest == 'A' ? 'selected' : '') ?> value="A">Activo</option>
        <option <?php echo ($cest == 'I' ? 'selected' : '') ?> value="I">No Activo</option>
    </select>
</div>