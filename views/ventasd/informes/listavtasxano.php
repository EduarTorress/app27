<table id="table" class="table table-bordered table-hover table-sm small" style='font-size: 12px;' data-page-length='20'>
    <thead>
        <tr>
            <th>MES</th>
            <?php $sucursales = cargarsucursales(); ?>
            <?php foreach ($sucursales as $s) : ?>
                <th class="text-right" data-footer-formatter="formatTotal"><?php echo $s['nomb']; ?></th>
            <?php endforeach; ?>
            <th class="text-right" data-footer-formatter="formatTotal"> TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo getnamemonth($item['mes']); ?></td>
                <?php
                $i = 1;
                foreach ($sucursales as $s) :
                ?>
                    <th class="text-right"><?php echo Round($item["$i"], 2); ?></th>
                <?php
                    $i = $i + 1;
                endforeach;
                ?>
                <td class="text-right"><?php echo Round($item['tot'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="3" style="text-align:right">Total:</th>
            <th class="text-right"></th>
        </tr>
    </tfoot> -->
</table>
<script>
    // $(document).ready(function() {
    //     $('#table').DataTable({
    //         "paging": true,
    //         "lengthChange": false,
    //         "searching": true,
    //         "ordering": true,
    //         "info": true,
    //         "autoWidth": false,
    //         "responsive": true,
    //         "dom": 'Bfrtip',
    //         "order": [
    //             [4, 'desc']
    //         ],
    //         "buttons": [{
    //                 //Botón para Excel
    //                 extend: 'excelHtml5',
    //                 footer: true,
    //                 title: 'Reporte de ventas Resumidas por Vendedor',
    //                 filename: 'Sysven-Reporte',
    //                 //Aquí es donde generas el botón personalizado
    //                 text: '<span class="badge badge-success"><i class="fas fa-file-excel"></i></span>'
    //             },
    //             //Botón para PDF
    //             {
    //                 extend: 'pdfHtml5',
    //                 download: 'open',
    //                 title: 'Reporte de Productos Personalizados',
    //                 filename: 'Sysven-Reporte',
    //                 text: '<span class="badge  badge-danger"><i class="fas fa-file-pdf"></i></span>'
    //             },
    //             //Botón para copiar
    //             {
    //                 extend: 'copyHtml5',
    //                 footer: true,
    //                 title: 'Reporte de Productos Personalizados',
    //                 filename: 'Sysven-Reporte',
    //                 text: '<span class="badge  badge-primary"><i class="fas fa-copy"></i></span>',
    //                 exportOptions: {
    //                     columns: [0, ':visible']
    //                 }
    //             },
    //             // //Botón para print
    //             // {
    //             //     extend: 'print',
    //             //     footer: true,
    //             //     filename: 'Export_File_print',
    //             //     text: '<span class="badge badge-light"><i class="fas fa-print"></i></span>'
    //             // },
    //             //Botón para cvs
    //             {
    //                 extend: 'csvHtml5',
    //                 footer: true,
    //                 filename: 'Export_File_csv',
    //                 text: '<span class="badge  badge-success"><i class="fas fa-file-csv"></i></span>'
    //             }
    //             // {
    //             //     extend: 'colvis',
    //             //     text: '<span class="badge  badge-info"><i class="fas fa-columns"></i></span>',
    //             //     postfixButtons: ['colvisRestore']
    //             // }
    //         ],
    //         "footerCallback": function(row, data, start, end, display) {
    //             var api = this.api();
    //             // // Remove the formatting to get integer data for summation
    //             // var intVal = function(i) {
    //             //     return typeof i === 'string' ?
    //             //         i.replace(/[\$,]/g, '') * 1 :
    //             //         typeof i === 'number' ?
    //             //         i : 0;
    //             // };
    //             // // Total over all pages
    //             // total = api
    //             //     .column(4)
    //             //     .data()
    //             //     .reduce(function(a, b) {
    //             //         return intVal(a) + intVal(b);
    //             //     }, 0);
    //             // // // Total over this page
    //             // // pageTotal = api
    //             // //     .column(5, {
    //             // //         page: 'current'
    //             // //     })
    //             // //     .data()
    //             // //     .reduce(function(a, b) {
    //             // //         return intVal(a) + intVal(b);
    //             // //     }, 0);
    //             // // Update footer
    //             // $(api.column(4).footer()).html(addCommas(total.toFixed(2)));
    //         }
    //     });

    //     function addCommas(nStr) {
    //         nStr += '';
    //         x = nStr.split('.');
    //         x1 = x[0];
    //         x2 = x.length > 1 ? '.' + x[1] : '';
    //         var rgx = /(\d+)(\d{3})/;
    //         while (rgx.test(x1)) {
    //             x1 = x1.replace(rgx, '$1' + ',' + '$2');
    //         }
    //         return x1 + x2;
    //     }
    // });
    reportetablebt("#table");
</script>