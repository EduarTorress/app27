<div class="card-body">
    <table id="tabla_grupos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Costo S/</th>
                <th>Total Prod.</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['idflete'] ?></td>
                    <td><?php echo $item['desflete'] ?></td>
                    <td><?php echo $item['prec'] ?></td>
                    <td><?php echo $item['totalproductos'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['idflete'];
                        $parametro2 = $item['desflete'];
                        $parametro3 = $item['totalproductos'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button onclick='modalEdit(<?php echo $parametro1 ?>)' class="btn btn-warning">Editar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        focustabla('#tabla_grupos')
    });
</script>