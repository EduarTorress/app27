<!-- Ndoc,fech,cliente,Vendedor,Importe,(SUM(Utilidad)*100)/SUM(costototal) AS porcentaje,
 SUM(Utilidad) AS Utilidad, Idauto -->
<table id="table" data-show-export="true" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th data-footer-formatter="formatTotal" class="text-end"  data-sortable="true">Importe</th>
            <th data-footer-formatter="formatTotal" class="text-end"  data-sortable="true"> Porcentaje</th>
            <th data-footer-formatter="formatTotal" class="text-end" data-sortable="true">Utilidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item[0]['Ndoc'] ?></td>
                <td><?php echo $item[0]['fech'] ?></td>
                <td><?php echo $item[0]['cliente'] ?></td>
                <td><?php echo $item[0]['Vendedor'] ?></td>
                <td class="text-end"><?php echo $item[0]['Importe'] ?></td>
                <td class="text-end">
                    <?php
                    if (floatval($item[0]['costototal']) != 0) {
                        echo round(array_sum(array_column($item, 'Utilidad')) * 100 / array_sum(array_column($item, 'costototal')), 3);
                    } else {
                        echo '0';
                    }
                    ?>
                </td>
                <td class="text-end"><?php echo array_sum(array_column($item, 'Utilidad')); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="9" style="text-align:right">Total:</th>
            <th></th>
        </tr>
    </tfoot> -->
</table>
<!-- <div class="modal fade" id="modalEliminarVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="lblEliminarVenta">Eliminar Venta: </h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="col-form-label">Usuario:</label>
                    <input type="usuario" class="form-control" name="txtUsuario" id="txtUsuario">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="txtPassword" id="txtPassword">
                </div>
                <input style="display:none" type="text" class="form-control" name="txtIdauto" id="txtIdauto">
                <div class="text-end">
                    <input type="submit" class="btn btn-warning" onclick="eliminarVenta()" value="Eliminar">
                    <input type="submit" class="btn btn-danger" value="Cancelar">
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- /.card-body -->
<!-- // $(document).ready(function() {
    //     reporteTabla('#table');
    // }); -->
<script>
    reportetablebt("#table");
</script>