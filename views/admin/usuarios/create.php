<?php
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
                <input type="hidden" name="txtidusua" id="txtidusua" class="form-control txtidusua" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['idusua'] : '0') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Contraseña:</label>
            <div class="col-sm-8">
                <input type="password" name="txtclave" id="txtclave" class="form-control txtclave" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['clave'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Sueldo:</label>
            <div class="col-sm-8">
                <input type="text" name="txtsueldo" id="txtsueldo"  onkeypress="return isNumber(event);"  class="form-control txtsueldo" value="<?php echo ($modo == 'A' ?  $lista['sueldo'] : '0') ?>">
            </div>
        </div>
        <div class="form-group row">
            <?php
            $tipo = isset($lista['tipo']) ? $lista['tipo'] : '';
            $tipos = new \App\View\Components\TiposUsuarioComponent($tipo);
            echo $tipos->render();
            ?>
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