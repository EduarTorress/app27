<table id="table" data-show-export="true" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Cliente</th>
            <th>Mon.</th>
            <th class="text-end" data-footer-formatter="formatTotal">Grav.</th>
            <th class="text-end" data-footer-formatter="formatTotal">Exon.</th>
            <th class="text-end" data-footer-formatter="formatTotal">Inaf.</th>
            <th class="text-end" data-footer-formatter="formatTotal">IGV</th>
            <th class="text-end" data-footer-formatter="formatTotal">Total</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Fecha Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td><?php echo $item['mone'] ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['valor']) ?></td>
                <td class="text-end"><?php echo '0.00'; ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['inafecto']); ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['igv']); ?></td>
                <td class="text-end"><?php echo evaluarvalortdoc($item['tdoc'], $item['impo']); ?></td>
                <td class="text-center"><b><?php echo $item['usuario']; ?></b></td>
                <td class="text-center"><?php echo $item['fusua']; ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        reportetablebt('#table');
    });
</script>