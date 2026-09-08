<?php
?>
<div class="modal-header">
    <h4 class="modal-title"><?php echo $titulo ?></h4>
</div>
<form action="" id="formulario-crear" autocomplete="off">
    <div class="modal-body">
        <!-- razon,ructr,nombr,placa,placa1,breve,marca, -->
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Razon Social:</label>
            <div class="col-sm-8">
                <input type="text" name="txtrazon" id="txtrazon" placeholder="" class="form-control" onkeyup="mayusculas(this)" required value="<?php echo ($modo == 'A' ?  $lista['razon'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">RUC:</label>
            <div class="col-sm-8">
                <input type="text" name="txtruc" maxlength="11" id="txtruc" placeholder="" class="form-control" required onkeyup="mayusculas(this)" onkeypress="return validarNumeros(event);" value="<?php echo ($modo == 'A' ?  $lista['ructr'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Transportista:</label>
            <div class="col-sm-8">
                <input type="text" name="txttransportista" id="txttransportista" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['nombr'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="nombre">Placa 01:</label>
            <div class="col-sm-8">
                <input type="text" name="txtplaca" maxlength="7" id="txtplaca" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['placa'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Placa 02:</label>
            <div class="col-sm-8">
                <input type="text" name="txtplaca1" maxlength="7" id="txtplaca1" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['placa1'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Brevete:</label>
            <div class="col-sm-8">
                <input type="text" name="txtbrevete" maxlength="9" id="txtbrevete" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['breve'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Marca:</label>
            <div class="col-sm-8">
                <input type="text" name="txtmarca" id="txtmarca" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['marca'] : '') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Contancia / MTC:</label>
            <div class="col-sm-8">
                <input type="text" name="txtconstancia" id="txtconstancia" class="form-control" onkeyup="mayusculas(this)" value="<?php echo ($modo == 'A' ?  $lista['constancia'] : 'TRAMITE') ?>">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label" for="">Tipo de Transporte:</label>
            <div class="col-sm-8">
                <?php $cmbtipotransporte = ($modo == 'A' ?  $lista['tipot'] : '') ?>
                <select class="form-select form-control" id="cmbtipotransporte" name="cmbtipotransporte">
                    <option <?php echo ($cmbtipotransporte == '02' ? 'selected' : '') ?> value="02">Privado</option>
                    <option <?php echo ($cmbtipotransporte == '01' ? 'selected' : '') ?> value="01">Público</option>
                </select>
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
    $("#cmbtipotransporte").on("change", function() {
        // Check input( $( this ).val() ) for validity here
        if ($(this).val() == '01') {
            $("#txtrazon").css("border", "3px solid blue");
            $("#txtruc").css("border", "3px solid blue");
            $("#txtconstancia").css("border", "3px solid blue")
        } else {
            $("#txtrazon").css("border", "");
            $("#txtruc").css("border", "");
            $("#txtconstancia").css("border", "")
        }
    });

    document.getElementById('formulario-crear').addEventListener('submit', function(evento) {
        evento.preventDefault();
        store('<?php echo $modo ?>', <?php echo $id ?>);
    })
</script>