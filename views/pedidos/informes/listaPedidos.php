<table id="tblpedidos" class="table table-bordered table-hover table table-sm small responsive" style='font-size: 12px;' data-page-length='20'>
    <thead>
        <tr class="text-center">
            <th>Fecha</th>
            <th>Documento</th>
            <th>Cliente</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Fecha / Hora</th>
            <th class="text-end" data-footer-formatter="formatTotal">Total</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td><b><?php echo $item['usuario'] ?></b></td>
                <td><b><?php echo $item['fecho'] ?></b></td>
                <td style="text-align: right;"><?php echo number_format($item['impo'], 2, '.', '') ?></td>
                <td class="small" style="text-align: center;">
                    <a class="btn btn-success" role="button" href=<?php echo "/pedidos/buscarpedido/" . $item['idautop'] ?>>
                        <i class="fas fa-eye "></i>
                    </a>
                    <a class="btn btn-primary" role="button" onclick="imprimirpedido(<?php echo $item['idautop'] ?>,<?php echo $item['ndoc'] ?>);">
                        <i class="fas fa-print"></i>
                    </a>
                    <a class="btn btn-warning" role="button" onclick="confirmDelete(<?php echo $item['idautop'] ?>);">
                        <i class="fas fa-trash-alt "></i>
                    </a>
                </td>
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
    reportetablebt("#tblpedidos");
    // $('#tblpedidos').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": true,
    //     "info": true,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 5,
    //         orderable: false,
    //         searchable: false
    //     }],

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
    //         total = api
    //             .column(4)
    //             .data()
    //             .reduce(function(a, b) {
    //                 return intVal(a) + intVal(b);
    //             }, 0);

    //         // Total over this page
    //         // pageTotal = api
    //         //     .column(5, {
    //         //         page: 'current'
    //         //     })
    //         //     .data()
    //         //     .reduce(function(a, b) {
    //         //         return intVal(a) + intVal(b);
    //         //     }, 0);

    //         // Update footer
    //         $(api.column(5).footer()).html(addCommas(total.toFixed(2)));
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
</script>