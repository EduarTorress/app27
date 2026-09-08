<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Código</th>
            <th data-sortable="true">Descripción</th>
            <th data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Stock</th>
            <th data-sortable="true" class="text-end">Costo Promedio</th>
            <!-- <th data-sortable="true">Importe</th> -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['idart'] ?></td>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['stock'] ?></td>
                <td><?php echo round($item['costo'],3) ?></td>
                <!-- <td><?php echo $item['importe'] ?></td> -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>