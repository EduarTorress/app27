<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Tipo D.</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Guía</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Fecha R.</th>
            <th class="text-center">Proveedor</th>
            <th class="text-center">Forma</th>
            <th class="text-center">Moneda</th>
            <th class="text-end" style="text-align: right;" data-footer-formatter="formatTotal">Valor</th>
            <th class="text-end" style="text-align: right;" data-footer-formatter="formatTotal">Exon</th>
            <th class="text-end" style="text-align: right;" data-footer-formatter="formatTotal">IGV</th>
            <th class="text-end" style="text-align: right;" data-footer-formatter="formatTotal">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['auto'] ?></td>
                <td><?php echo $item['tdoc'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['ndo2'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecr'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo mostrarformapago($item['form']); ?></td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td style="text-align: right;"><?php echo $item['valor'] ?></td>
                <td style="text-align: right;"><?php echo $item['exon'] ?></td>
                <td style="text-align: right;"><?php echo $item['igv'] ?></td>
                <td style="text-align: right;"><?php echo number_format($item['impo'], 2, '.', '') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="5" style="text-align:right">Total:</th>
            <th class="text-right"></th>
        </tr>
    </tfoot> -->
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