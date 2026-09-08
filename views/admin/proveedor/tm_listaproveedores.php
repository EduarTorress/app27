<table id="tabla_proveedores" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="width:80Ppx;">Nombre</th>
            <th class="text-center" style="width:10px;">RUC</th>
            <th class="text-center" style="width: 10px;">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo trim($item['razo']) ?></td>
                <td class="text-center"><?php echo trim($item['nruc']) ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $parametro1 = $item['idprov'];
                    $parametro2 = $item['razo'];
                    $parametro3 = $item['nruc'];
                    $parametro4 = $item['fono'];
                    $parametro5 = $item['dire'];
                    $parametro6 = $item['ciud'];
                    $parametro7 = $item['email'];
                    $parametro8 = $item['celu'];
                    $parametro9 = $item['ubig'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro9');
                    $cadena_json = json_encode($parametros)
                    ?>
                    <button id="<?php echo "agregar" . $parametro1 ?>" class="btn btn-success" onclick='seleccionarproveedor(<?php echo $cadena_json ?>);'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustabla('#tabla_proveedores')
    });
</script>