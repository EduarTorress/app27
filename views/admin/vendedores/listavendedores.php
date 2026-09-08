<div class="card-body">
    <table id="tablavendedores" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Nombre</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista as $item) : ?>
                <tr>
                    <td><?php echo $item['nomv'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['idven'];
                        $parametro2 = $item['nomv'];
                        $parametros = compact('parametro1', 'parametro2');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button onclick='modalEdit("<?php echo $parametro1 ?>")' class="btn btn-warning">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#tablavendedores').DataTable({
            "paging": true,
            "keys": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>