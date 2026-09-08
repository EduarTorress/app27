<div class="card">

    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabla_clientes" class="table table-bordered table-hover" style="font-size:small;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>RUC</th>
                    <th>DNI</th>
                    <th>Agregar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista['lista']['items'] as $item) : ?>
                    <tr>
                        <td><?php echo $item['razo'] ?></td>
                        <td><?php echo $item['nruc'] ?></td>
                        <td><?php echo $item['ndni'] ?></td>
                        <td>
                            <button class="btn btn-success"  onclick="agregar_cliente('<?= $item['idclie'] ?>','<?= $item['razo'] ?>');"><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                        </td>                       
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('#tabla_clientes').DataTable({
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
