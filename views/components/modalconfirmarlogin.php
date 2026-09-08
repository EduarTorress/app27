<div class="modal fade" id="modalConfirmarLogin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="lbltitle">Confirme los siguientes datos: </h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="col-form-label">Usuario:</label>
                    <!-- <input type="usuario" class="form-control" name="" id="txtUsuario"> -->
                    <select class="form-control form-control-sm" id="txtUsuario" name="txtUsuario">
                        <?php foreach ($usuarios as $row) : ?>
                            <option value="<?php echo $row['nomb'] ?>"><?php echo $row['nomb'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="txtPassword" id="txtPassword">
                    <input style="display:none;" type="text" name="txtfakeusuario" >
                </div>
                <input style="display:none" type="text" class="form-control" name="txtIdauto" id="txtIdauto">
                <input style="display:none" type="text" class="form-control" name="txttdoc" id="txttdoc">
                <div class="text-end">
                    <input type="submit" class="btn btn-warning" onclick="consultarlogin()" value="Proceder">
                    <input type="submit" class="btn btn-danger" onclick="cerrarModal()" value="Cancelar">
                </div>
            </div>
        </div>
    </div>
</div>