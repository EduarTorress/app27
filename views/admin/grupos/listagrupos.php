<div class="card-body">
    <table id="tabla_grupos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categorías</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['idgrupo'] ?></td>
                    <td><?php echo $item['desgrupo'] ?></td>
                    <td><?php echo $item['totalcat'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['idgrupo'];
                        $parametro2 = $item['desgrupo'];
                        $parametro3 = $item['totalcat'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button onclick='modalEdit(<?php echo $parametro1?>)' class="btn btn-warning">Editar</button>
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