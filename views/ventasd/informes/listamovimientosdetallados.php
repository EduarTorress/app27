<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Fecha</th>
            <th data-sortable="true">Documento</th>
            <th data-sortable="true">Cliente</th>
            <th data-sortable="true">Producto</th>
            <th data-sortable="true">Unidad</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Cantidad</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Precio</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Sub. Total</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">IGV</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['cliente'] ?></td>
                <td><?php echo $item['producto'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td><?php echo number_format($item['cant'], 2, '.', ',') ?></td>
                <td><?php echo number_format($item['prec'], 2, '.', ',') ?></td>
                <td><?php echo number_format($item['valor'], 2, '.', ',') ?></td>
                <td><?php echo number_format($item['igv'], 2, '.', ',') ?></td>
                <td><?php echo number_format($item['impo'], 2, '.', ',') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>