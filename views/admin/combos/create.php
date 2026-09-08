<div class="modal-header">
    <h4 id="lbltitulodetalle"><?php echo $titulo; ?></h4>
</div>
<div class="modal-body">
    <div class="form-group row">
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="txtbuscarProducto" name="txtbuscarProducto">
            <input type="hidden" class="form-control" id="txtidproducto" name="txtidproducto">
            <button class="btn btn-outline-primary" type="button" onclick="buscarProductoModal()">Consultar</button>
        </div>
    </div>
    <div class="form-group row">
        <div class="col">
            <h5 class="text-center">Productos</h5>
            <div id="searchP"></div>
        </div>
        <div class="col">
            <h5 class="text-center">Detalle</h5>
            <br>
            <table id="detallecombo" class="table table-bordered table-hover table-sm small">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Costo</th>
                        <th scope="col">Opción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combo as $c) : ?>
                        <tr class="fila">
                            <td scope="col"><input type="text" name="" style="width: 100%;" class="idart" id="" value="<?php echo $c['com_idart']; ?>" readonly></th>
                            <td scope="col"><input type="text" name="" style="width: 100%;" class="nombre" id="" value="<?php echo $c['descri']; ?>" readonly></th>
                            <td scope="col"><input type="text" name="" style="width: 100%;" class="costo" id="" value="<?php echo $c['com_costo']; ?>" readonly></th>
                            <td scope="col"><button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button></th>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="input-group mb-3">
                <span class="input-group-text" id="lbltotalcosto">Costo Total:</span>
                <input type="text" class="form-control" id="txtcostototal" readonly>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-success" onclick="registrarcombo()">Registrar</button>
    <button class="btn btn-danger" onclick="closemodaldetalle()">Cerrar</button>
</div>
<script>
    calcularcostototal()
</script>