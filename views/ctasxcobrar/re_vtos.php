<table id="tabla" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="width: 40px;">Documento</th>
            <th style="width: 5px;">Mon.</th>
            <th style="width: 80px;">F.Emis.</th>
            <th style="width: 89px;">F.Vto.</th>
            <th style="width: 10px;">Días</th>
            <th style="width: 15px;">Tipo</th>
            <th style="width: 20x;">Referencia</th>
            <th style="width: 40px;">Vendedor</th>
            <th style="width: 5px;">Form.</th>
            <th style="width: 40px;">Saldo</th>
            <th style="width: 10px;" class="text-center">Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['mone'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fevto'] ?></td>
                <td><?php echo $item['dias'] ?></td>
                <td><?php echo $item['tipo'] ?></td>
                <td><?php echo $item['docd'] ?></td>
                <td><?php echo $item['nomv'] ?></td>
                <td><?php echo $item['form'] ?></td>
                <td><?php echo $item['importe'] ?></td>
                <td>
                    <?php
                    // $parametro1 = $item['idclie'];
                    // $parametro2 = $item['razo'];
                    // $parametro3 = $item['nruc'];
                    // $parametro4 = $item['ndni'];
                    // $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4');
                    // $cadena_json = json_encode($parametros);
                    ?>
                    <button class="btn btn-success"><i href="" style="color:white;" class="fas fa-info-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="10" style="text-align:right">Total:</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<script>
    $('#tabla').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
            targets: 10,
            orderable: false,
            searchable: false
        }],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total over all pages
            total = api
                .column(9)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(9, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(10).footer()).html(addCommas(total.toFixed(2)));
            // console.log('Hola');
            // console.log(total);
        }
    });

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>