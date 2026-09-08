<table id="tabla_destinatarios" class="table table-bordered table-hover table-sm small" style='font-size: 10px;' data-page-length='15'>
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
                <td class="text-center" id="iniciard">
                    <?php
                    $parametro1 = $item['idclie'];
                    $parametro2 = $item['razo'];
                    $parametro3 = (empty(trim($item['nruc'])) ? $item['ndni'] : $item['nruc']);
                    $parametro4 = $item['ndni'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4');
                    $cadena_json = json_encode($parametros);
                    $razon = str_replace("\"", " ", $item['razo']);
                    $direcciond = trim($item['dire']);
                    $ubigd = $item['ubig'];
                    ?>
                    <button id="<?php echo "agregar" . $parametro1 ?>" style="background-color:green" onclick="seleccionarDestinatario('<?= $item['idclie'] ?>','<?= $razon ?>',' <?= $direcciond ?>',' <?= $ubigd ?>',' <?= $parametro3 ?>');">+<i href="" style="color:white;"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustabladestinatarios('#tabla_destinatarios')
    });
</script>