<div class="input-group mb-3">
    <label class="form-control form-control-sm" for="">Guia:</label>
    <input type="text" name="txtguia" id="txtguia" class="form-control form-control-sm"  style="width:105px;" value="<?php echo empty($datosclientev['ndo2v']) ? "" : substr($datosclientev['ndo2v'],0,3).'-'.substr($datosclientev['ndo2v'],-8) ?>">
</div>