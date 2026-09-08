<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th style="text-align: right;" class="text-end" data-footer-formatter="formatTotal">Importe</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td style="text-align: right;"><?php echo number_format($item['impo'], 2, '.', '') ?></td>
                <td class="small" style="text-align: center;">
                    <a class="btn btn-success" role="button" onclick="limpiarsesion();" href="<?php echo "/ordenescompra/buscarOrdenCompraPorId/" . $item['nidauto'] ?>">
                        <i class="fas fa-eye "></i>
                    </a>
                    <a class="btn btn-primary " role="button" onclick="descargarpdf10('<?= $item['nidauto'] ?>','<?= pathinfo($item['ndoc'], PATHINFO_FILENAME) . '.pdf' ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablacompras");
</script>