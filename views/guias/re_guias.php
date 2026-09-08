<table id="tablaGuias" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro.Guia</th>
            <th>Fecha</th>
            <th>Fecha Tras.</th>
            <th>Remitente</th>
            <th>Destinatario</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecht'] ?></td>
                <td><?php echo $item['remi'] ?></td>
                <td><?php echo $item['dest'] ?></td>
                <td class="text-center">
                    <?php
                    $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
                    if ($multiempresa == 'S') {
                        $item['nombrexml'] = $item['ndoc'];
                    } ?>
                    <a class="btn btn-primary " role="button" onclick="descargarpdf('<?= $item['idauto'] ?>','<?= $item['nombrexml'] . '.pdf' ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                    <a class="btn btn-info" role="button" onclick="descargarxml('<?= $item['idauto'] ?>','<?= $item['nombrexml'] . '.xml' ?>')">
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                    <a class="btn btn-success" role="button" onclick="" href="<?php echo "/guias/buscarGuia/" . $item['idauto'] ?>">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablaGuias");
</script>