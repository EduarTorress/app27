<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Fecha</th>
            <th>Documento</th>
            <th data-sortable="true">Proveedor</th>
            <th data-sortable="true">Guía Rem.</th>
            <th data-sortable="true">Forma</th>
            <th data-sortable="true">Moneda</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Valor Gr.</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">IGV</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Importe</th>
            <th data-sortable="true" class="text-center">Usuario</th>
            <th data-sortable="true" class="text-center">Fecha Hora Registro</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><b><?php echo $item['dcto'] ?></b></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['ndo2'] ?></td>
                <td><b><?php echo mostrarformapago($item['form']); ?></b></td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td><?php echo number_format($item['valor'], 2, '.', '') ?></td>
                <td><?php echo number_format($item['igv'], 2, '.', '') ?></td>
                <td><?php echo number_format($item['impo'], 2, '.', '') ?></td>
                <td><b><?php echo $item['usuario'] ?></b></td>
                <td><?php echo $item['fusua'] ?></td>
                <td class="small" style="text-align: center;">
                    <?php if ($item['tdoc'] != '07') : ?>
                        <?php if ($item['tcom'] == '1') : ?>
                            <a class="btn btn-success" role="button" onclick="limpiarsesion();" href="<?php echo "/compras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye "></i>
                            </a>
                        <?php else : ?>
                            <a class="btn btn-info" role="button" onclick="" href="<?php echo "/ocompras/buscarcompra/" . $item['idauto'] ?>">
                                <i class="fas fa-eye "></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // $('#tablacompras').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 3,
    //         orderable: false,
    //         searchable: false
    //     }],
    // });

    reportetablebt("#tablacompras");

    function limpiarsesion() {
        localStorage.clear();
    }
</script>