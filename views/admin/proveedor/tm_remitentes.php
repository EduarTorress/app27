<table id="tabla_proveedores" class="table table-bordered table-hover table-sm small" style='font-size: 11px;' data-page-length='15'>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>RUC</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['nruc'] ?></td>
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
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4');
                    $direccion = trim($item['dire']);
                    $ubig = $item['ubig'];
                    $cadena_json = json_encode($parametros);
                    $rem = str_replace("\"", " ", $item['razo']);
                    ?>
                    <button id="<?php echo "agregar" . $parametro1 ?>" class="btn btn-success" onclick="seleccionarRemitentes('<?= $item['idprov'] ?>','<?= $rem ?>',' <?= $direccion ?>',' <?= $ubig ?>',' <?= $item['nruc'] ?>');"><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
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