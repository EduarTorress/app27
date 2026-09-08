<table id="tabla_vehiculos" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Placa 01</th>
            <th>Chofer</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['vehi_plac'] ?></td>
                <td><?php echo $item['vehi_cond'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['vehi_plac'];
                    $parametro2 = $item['vehi_pla2'];
                    $parametro3 = $item['vehi_cond'];
                    $parametro4 = $item['vehi_brev'];
                    $parametro5 = $item['vehi_idve'];
                    $parametro6 = $item['vehi_seri'];
                    $parametro7 = $item['vehi_marc'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6','parametro7');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button id="<?php echo "agregar" . $parametro1 ?>" class="btn btn-success" onclick='seleccionarVehiculo(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustabla('#tabla_vehiculos')
    });
</script>
<!-- <style>
    :root {
        --dt-row-selected: 205,192,192;
    }
</style> -->