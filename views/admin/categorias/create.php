<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-sm-4 col-form-label-sm" for="nombre">Grupo:</label>
            <div class="col-sm-8">
                <select class="form-control form-control-sm" aria-label=".form-control form-control-sm example" id="cmbgrupos" name="cmbgrupos">
                    <?php
                    foreach ($grupos['lista']['items'] as $grupo) : ?>
                        <?php if ($modo == 'N') { ?>
                            <option value="<?php echo $grupo['idgrupo'] ?>"> <?php echo $grupo['desgrupo'] ?></option>
                        <?php } else { ?>
                            <option class="form-control form-control-sm" <?php echo ($lista['idgrupo'] == $grupo['idgrupo']) ? 'selected' : '' ?> value=" <?php echo $grupo['idgrupo'] ?> "> <?php echo $grupo['desgrupo'] ?></option>
                        <?php } ?>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label-sm" for="nombre">Nombre:</label>
            <div class="col-sm-8">
                <input type="text" name="txtnombre" id="txtnombre" class="form-control form-control-sm" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['dcat'] : '') ?>">
            </div>
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
    });
</script>