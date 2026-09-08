<div class="card-body">
    <table id="tabla_direcciones" class="table table-bordered table-hover table-sm small">
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
            <!-- SELECT dire_iddi,dire_dire,dire_ciud,dire_ubig,dire_acti,dire_idre,dire_idde FROM fe_direcciones d INNER JOIN fe_clie c ON (d.dire_idre=c.idclie) WHERE razo LIKE :abuscar and clie_acti='A'" -->
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['razo'] ?></td>
                    <td><?php echo $item['dire_dire'] ?></td>
                    <td><?php echo $item['dire_ciud'] ?></td>
                    <td><?php echo $item['dire_ubig'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['razo'];
                        $parametro2 = $item['dire_dire'];
                        $parametro3 = $item['dire_ciud'];
                        $parametro4 = $item['dire_ubig'];
                        $parametro5 = $item['dire_idre'];
                        $parametro6 = $item['dire_idde'];
                        $parametro7 = $item['dire_iddi'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <!-- <button onclick='seleccionar("<?php echo $parametro1 ?>"," <?php echo $parametro5 ?> ")' class="btn btn-success">Seleccionar</button> -->
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
        focustabla('#tabla_direcciones')
    });
</script>