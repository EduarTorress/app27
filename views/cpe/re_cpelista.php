<div class="card">
    <div class="card-body">
        <table id="tablavtasxenviar" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Moneda</th>
                    <th class="text-center">Gravado</th>
                    <th class="text-center">Exonerado</th>
                    <th class="text-center">IGV</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Opción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listado as $item) : ?>
                    <tr>
                        <td><?php echo $item['ndoc'] ?></td>
                        <td><?php echo $item['fech'] ?></td>
                        <td><?php echo $item['razo'] ?></td>
                        <td><?php echo ($item['mone'] == 'S' ? 'PEN' : 'DÓLARES'); ?></td>
                        <td class="text-right"><?php echo number_format($item['valor'], 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo '0.00' ?></td>
                        <td class="text-right"><?php echo number_format($item['igv'], 2, '.', ',') ?></td>
                        <td class="text-right"><?php echo number_format($item['impo'], 2, '.', ',') ?></td>
                        <td class="text-center">
                            <button class="btn btn-warning">Enviar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    // $('#example2').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": false,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 8,
    //         orderable: false,
    //         searchable: false
    //     }]
    // });
    reportetablebt("#tablavtasxenviar");
</script>