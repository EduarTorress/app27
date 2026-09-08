<table id="tabla_direcciones" class="table table-bordered table-hover table-sm small" style='font-size: 10px;' data-page-length='15'>
    <thead>
        <tr>
            <th>Direccion</th>
            <th>Ciudad</th>
            <th>Ubigeo</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['dire_dire'] ?></td>
                <td><?php echo $item['dire_ciud'] ?></td>
                <td><?php echo $item['dire_ubig'] ?></td>
                <td class="text-center" id="iniciar">
                    <?php
                    $cdire = str_replace("\"", " ", $item['dire_dire']);
                    $cciud = str_replace("\"", " ", $item['dire_ciud']);
                    ?>
                    <button id="<?php echo "agregar" . $item['dire_iddi'] ?>" class="btn btn-success" onclick="seleccionarDireccion('<?= $cdire ?>','<?= $cciud ?>',' <?= $item['dire_ubig'] ?>');">+<i href="" style="color:white;"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustabla('#tabla_direcciones')
    });
</script>