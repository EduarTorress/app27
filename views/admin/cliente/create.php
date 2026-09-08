<?php

use App\View\Components\UbigeosComponent;
?>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">RUC:</label>
            <div class="col-sm-8">
                <div class="input-group-append">
                    <input type="text" name="txtRUC" id="txtRUC" class="form-control txtruc" maxlength="11" onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" value="<?php echo ($modo == 'A' ?  $lista['nruc'] : '') ?>">
                    <button class="btn btn-outline-secondary" onclick="buscaruc()" type="button">Importar</button>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">DNI:</label>
            <div class="col-sm-8">
                <div class="input-group-append">
                    <input type="text" name="txtDNI" id="txtDNI" class="form-control txtdni" maxlength="8" onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" value="<?php echo ($modo == 'A' ?  $lista['ndni'] : '') ?>">
                    <button class="btn btn-outline-secondary" onclick="buscadni()" type="button">Importar</button>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Nombre:</label>
            <div class="col-sm-8">
                <input type="text" name="txtNombre" id="txtNombre" class="form-control txtnombre" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['razo'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Dirección:</label>
            <div class="col-sm-8">
                <input type="text" name="txtDireccion" id="txtDireccion" class="form-control txtdireccion" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['dire'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Ciudad:</label>
            <div class="col-sm-8">
                <input type="text" name="txtCiudad" id="txtCiudad" class="form-control txtciudad" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['ciud'] : '') ?>">
            </div>
        </div>
        <?php
        $oubg = new UbigeosComponent($modo, ($modo == 'N' ? '' : $lista['ubig']));
        echo $oubg->render();
        ?>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Cliente Retención:</label>
            <div class="col-sm-8">
                <select class="form-select-sm form-control form-control-sm cmbretencion" id="cmbretencion" name="cmbretencion">
                    <!-- <?php echo ($cmon == 'D' ? 'selected' : '') ?>  -->
                    <!-- <?php echo $modo == 'A' ? 'selected ' : ($cmon == 'S' ? 'selected' : '') ?>  -->
                    <option  <?php echo ($modo != 'A' ?  'selected' : ($lista['clie_rete'] == 'S' ? 'selected' : '')) ?> value="S">Si Aplica</option>
                    <option <?php echo ($modo != 'A' ?  'selected' : ($lista['clie_rete'] == 'N' ? 'selected' : '')) ?> value="N">No Aplica</option>
                </select>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" id="cmdcerrar" onclick="cerrarmodal()" data-dismiss="modal"><i class="fas fa-close"></i> Cerrar
            </button>
            <button id="btn-submit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                <?php echo ($modo == 'N') ? 'Registrar' : 'Actualizar' ?></button>
        </div>
    </div>
</form>
<script>
    document.getElementById('formulario-crear').addEventListener('submit', function(evento) {
        evento.preventDefault();
        store('<?php echo $modo ?>', <?php echo $id ?>);
    })
</script>