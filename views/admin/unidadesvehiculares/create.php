<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Chofer:</label>
            <div class="col-sm-8">
                <input type="text" name="txtChofer" id="txtChofer" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_cond'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">DNI:</label>
            <div class="col-sm-8">
                <input type="text" name="txtDNI" id="txtDNI" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_ndni'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Brevete:</label>
            <div class="col-sm-8">
                <input type="text" name="txtBrevete" id="txtBrevete" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_brev'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">T. Brevete:</label>
            <div class="col-sm-8">
                <input type="text" name="txtTipoBrevete" id="txtTipoBrevete" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_conf'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Placa 01:</label>
            <div class="col-sm-8">
                <input type="text" name="txtPlaca01" id="txtPlaca01" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_plac'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Placa 02:</label>
            <div class="col-sm-8">
                <input type="text" name="txtPlaca02" id="txtPlaca02" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_pla2'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Constancia:</label>
            <div class="col-sm-8">
                <input type="text" name="txtConstancia" id="txtConstancia" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['vehi_cons'] : '') ?>">
            </div>
        </div>
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