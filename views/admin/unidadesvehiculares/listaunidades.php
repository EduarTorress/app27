<div class="card-body">
    <table id="tabla_unidades" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Chofer</th>
                <th>Placa 01</th>
                <th>Placa 02</th>
                <th>Brevete</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['vehi_cond'] ?></td>
                    <td><?php echo $item['vehi_plac'] ?></td>
                    <td><?php echo $item['vehi_pla2'] ?></td>
                    <td><?php echo $item['vehi_brev'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['vehi_cond'];
                        $parametro2 = $item['vehi_plac'];
                        $parametro3 = $item['vehi_pla2'];
                        $parametro4 = $item['vehi_ndni'];
                        $parametro5 = $item['vehi_brev'];
                        $parametro6 = $item['vehi_conf'];
                        $parametro7 = $item['vehi_idve'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button onclick='modalEdit("<?php echo $parametro7 ?>")' class="btn btn-warning">Editar</button>
                        <button onclick='darBaja("<?php echo $parametro7 ?>")' class="btn btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        focustabla('#tabla_unidades')
    });
</script>