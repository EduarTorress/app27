<div class="card">
     <!-- /.card-header -->
    <div class="card-body">
        <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Por Informar</th>
                    <th>Enviado</th>
                    <th>Días</th>
                    <th>Opciones</th>
                 </tr>
            </thead>
            <tbody>
          
                <?php foreach ($listado as $item) : ?>
                    <tr>
                        <td><?php echo $item['resu_fech'] ?></td>
                        <td><?php echo number_format($item['resumen'],2,'.',',')?></td>
                        <td><?php echo number_format($item['enviados'],2,'.',',') ?></td>
                        <td><?php echo $item['dias'] ?></td>
                        <td>
                            <button  onclick="enviarboletas('<?= $item['resu_fech']?>','<?= $item['resumen']?>')"  class="btn btn-success">Enviar</button>
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