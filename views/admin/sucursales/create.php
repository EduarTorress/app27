<?php
use App\View\Components\UbigeosComponent;
?>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Nombre:</label>
            <div class="col-sm-8">
                <input type="text" name="txtnombre" id="txtnombre" class="form-control txtnombre" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['nomb'] : '') ?>">
                <input type="hidden" name="txtidsucu" id="txtidsucu" class="form-control txtidsucu" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['idalma'] : '0') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Dirección:</label>
            <div class="col-sm-8">
                <input type="text" name="txtdireccion" id="txtdireccion" class="form-control txtdireccion" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['dire'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Ciudad:</label>
            <div class="col-sm-8">
                <input type="text" name="txtciudad" id="txtciudad" class="form-control txtciudad" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['ciud'] : '') ?>">
            </div>
        </div>
        <?php
        $oubg = new UbigeosComponent($modo, ($modo == 'N' ? '' : $lista['ubigeo']));
        echo $oubg->render();
        ?>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" id="cmdcerrar" onclick="cerrarmodal()" data-dismiss="modal"><i class="fas fa-close"></i> Cerrar
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