<div class="card">
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>TD</th>
                    <th>Serie</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Ticket</th>
                    <th>Gravado</th>
                    <th>Exon.</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listado as $item) : ?>
                    <tr>
                        <td><?php echo $item['resu_tdoc'] ?></td>
                        <td><?php echo $item['resu_serie'] ?></td>
                        <td><?php echo $item['resu_desd'] ?></td>
                        <td><?php echo $item['resu_hast'] ?></td>
                        <td><?php echo $item['resu_tick']  ?></td>
                        <td><?php echo number_format($item['resu_valo'], 2, '.', ',') ?></td>
                        <td><?php echo number_format($item['resu_exon'], 2, '.', ',') ?></td>
                        <td><?php echo number_format($item['resu_igv'], 2, '.', ',') ?></td>
                        <td><?php echo number_format($item['resu_impo'], 2, '.', ',') ?></td>
                        <td>
                            <button onclick="consultarticket('<?= $item['resu_tick']?>','<?= $item['resu_desd'] ?>','<?= $item['resu_hast'] ?>','<?= $item['resu_serie'] ?>','<?= $item['resu_tdoc'] ?>','<?= pathinfo($item['resu_arch'],PATHINFO_FILENAME) ?>')" class="btn btn-success"><a style="color:white;"class="fas fa-search"></a></button>
                            <button onclick="consultarapi('<?=$item['resu_tick']?>','<?=$item['resu_desd']?>','<?=$item['resu_hast']?>','<?=$item['resu_serie']?>','<?=$item['resu_tdoc']?>')" class="btn btn-success">API</button>
                            <button onclick="anularticket('<?=$item['resu_tick']?>')" class="btn btn-danger"><a class="fas fa-trash" style="color:white"></a></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
            targets: 3,
            orderable: false,
            searchable: false
        }]
    });
</script>