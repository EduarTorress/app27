<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="width:8%;">Fecha</th>
            <th style="width:4%;">Tdoc</th>
            <th style="width:5%;">Serie</th>
            <th style="width:7%;">Documento</th>
            <th style="width:7%;">RUC/DNI</th>
            <th style="width:10%;">Cliente</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Grav.</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Exon.</th>
            <th style="width:4%;" data-footer-formatter="formatTotal">Inaf.</th>
            <th style="width:6%;" data-footer-formatter="formatTotal">IGV</th>
            <th style="width:6%;" data-footer-formatter="formatTotal">Total</th>
            <th style="width:8%;">Rpta SUNAT</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['tdoc'] ?></td>
                <td><?php echo $item['serie'] ?></td>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo ($item['tdoc'] == '03') ? $item['ndni'] : $item['nruc'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td class="text-right"><?php echo number_format($item['valor'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['exon'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['inafecto'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['igv'], 2, '.', '') ?></td>
                <td class="text-right"><?php echo number_format($item['importe'], 2, '.', '') ?></td>
                <td><?php echo $item['mensaje'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="6" style="text-align:right">Total:</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot> -->
</table>
<script>
    // $('#table').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": false,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "dom": 'Bfrtip',
    //     "keys": true,
    //     "buttons": ['excel', 'pdf'],
    //     "footerCallback": function(row, data, start, end, display) {
    //         var api = this.api();

    //         // Remove the formatting to get integer data for summation
    //         var intVal = function(i) {
    //             return typeof i === 'string' ?
    //                 i.replace(/[\$,]/g, '') * 1 :
    //                 typeof i === 'number' ?
    //                 i : 0;
    //         };

    //         // Total over all pages
    //         valor = api.column(6).data().reduce(function(a, b) {
    //             return intVal(a) + intVal(b);
    //         }, 0);
    //         exon = api.column(7).data().reduce(function(a, b) {
    //             return intVal(a) + intVal(b);
    //         }, 0);
    //         inaf = api.column(8).data().reduce(function(a, b) {
    //             return intVal(a) + intVal(b);
    //         }, 0);
    //         igv = api.column(9).data().reduce(function(a, b) {
    //             return intVal(a) + intVal(b);
    //         }, 0);
    //         total = api.column(10).data().reduce(function(a, b) {
    //             return intVal(a) + intVal(b);
    //         }, 0);

    //         // Total over this page
    //         // pageTotal = api
    //         //     .column(6, {
    //         //         page: 'current'
    //         //     })
    //         //     .data()
    //         //     .reduce(function(a, b) {
    //         //         return intVal(a) + intVal(b);
    //         //     }, 0);

    //         // Update footer
    //         $(api.column(6).footer()).html(addCommas(valor.toFixed(2)))
    //         $(api.column(7).footer()).html(addCommas(exon.toFixed(2)))
    //         $(api.column(8).footer()).html(addCommas(inaf.toFixed(2)))
    //         $(api.column(9).footer()).html(addCommas(igv.toFixed(2)))
    //         $(api.column(10).footer()).html(addCommas(total.toFixed(2)))
    //     }
    // });

    // function addCommas(nStr) {
    //     nStr += '';
    //     x = nStr.split('.');
    //     x1 = x[0];
    //     x2 = x.length > 1 ? '.' + x[1] : '';
    //     var rgx = /(\d+)(\d{3})/;
    //     while (rgx.test(x1)) {
    //         x1 = x1.replace(rgx, '$1' + ',' + '$2');
    //     }
    //     return x1 + x2;
    // }
    reportetablebt("#table");
</script>