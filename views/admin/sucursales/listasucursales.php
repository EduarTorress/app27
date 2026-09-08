<div class="card-body">
    <table id="tabla_sucursales" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Ubigeo</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista as $item) : ?>
                <tr>
                    <td><?php echo $item['nomb'] ?></td>
                    <td><?php echo $item['dire'] ?></td>
                    <td><?php echo $item['ciud'] ?></td>
                    <td><?php echo $item['ubigeo'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['idalma'];
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
        focustabla('#tabla_sucursales')
    });
</script>