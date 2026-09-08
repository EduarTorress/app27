<div class="modal-header">
    <h4 class="modal-title">
        <?php
        use App\View\Components\UbigeosComponent;
        echo $titulo
        ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <input type="text" style="display:none" name="txtIdRemitente" id="txtIdRemitente" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['txtIdRemitente'] : '') ?>">
            <label class="col-sm-4 col-form-label" for="nombre">Nombre:</label>
            <div class="col-sm-8">
                <input type="text" name="txtNombre" id="txtNombre" disabled class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['razo'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Dirección:</label>
            <div class="col-sm-8">
                <input type="text" name="txtDireccion" id="txtDireccion" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['txtDireccion'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Ciudad:</label>
            <div class="col-sm-8">
                <input type="text" name="txtCiudad" id="txtCiudad" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['txtCiudad'] : '') ?>">
            </div>
        </div>
        <?php
        // echo ($cmodo = 'N' ? '' : $lista['cmbUbigeo']);
        // echo $modo;
        $oubg = new UbigeosComponent($modo, ($modo == 'N' ? '' : $lista['cmbUbigeo']));
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