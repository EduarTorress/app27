<?php
$fechaactual = date('Y-m-d');
$fechaminima = date('Y-m-d', (strtotime('-2 day', strtotime($fechaactual))));
?>
<div class="input-group mb-3">
    <label class="form-control form-control-sm" for="">Fecha:</label>
    <input type="date" name="txtfecha" min="<?php echo $fechaminima ?>" max="<?php echo $fechaactual ?>" id="txtfecha" class="form-control form-control-sm" style="width:90px;" value="<?php echo empty($datosclientev['fechv']) ? date("Y-m-d") : $datosclientev['fechv'] ?>">
</div>