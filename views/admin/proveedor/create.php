<?php

use App\View\Components\UbigeosComponent;
?>
<div class="modal-header">
    <h4 class="modal-title">
        <?php
        echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">RUC:</label>
            <div class="col-sm-8">
                <div class="input-group-append">
                    <input type="text" name="txtRUC" id="txtRUC" class="form-control txtruc" onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" maxlength="11" value="<?php echo ($modo == 'A' ?  $lista['nruc'] : '') ?>">
                    <button class="btn btn-outline-secondary" type="button" onclick="buscaruc()" id="">Importar</button>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">DNI:</label>
            <div class="col-sm-8">
                <div class="input-group-append">
                    <input type="text" name="txtDNI" id="txtDNI" class="form-control txtdni" onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" maxlength="8" value="<?php echo ($modo == 'A' ?  $lista['ndni'] : '') ?>">
                    <button class="btn btn-outline-secondary" type="button" onclick="buscadni()" id="">Importar</button>
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
        $ubigeo = ($modo == 'A' ? $lista['ubig'] : '');
        $oubg = new UbigeosComponent($modo, $ubigeo);
        echo $oubg->render();
        ?>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" id="cmdcerrar" onclick="cerrarmodal()" data-dismiss="modal"><i class="fa fa-window-close"></i> Cerrar
            </button>
            <button id="btn-submit" type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                <?php echo ($modo == 'N') ? 'Registrar' : 'Actualizar' ?></button>
        </div>
</form>
<script>
    document.getElementById('formulario-crear').addEventListener('submit', function(evento) {
        evento.preventDefault();
        store('<?php echo $modo ?>', <?php echo $id ?>);
    })
</script>