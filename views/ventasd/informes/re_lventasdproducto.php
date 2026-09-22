<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">PRODUCTO</th>
            <th data-sortable="true" class="text-center">MARCA</th>
            <th data-sortable="true" class="text-center">UNID</th>
            <th data-sortable="true" class="text-center">GRUP.</th>
            <th data-sortable="true" class="text-center">LINE.</th>
            <th data-sortable="true" class="text-end" data-footer-formatter="formatTotal" >CANTIDAD</th>
            <th data-sortable="true" class="text-end" data-footer-formatter="formatTotal" >IMPORTE</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['Descri'] ?></td>
                <td><?php echo $item['marca'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td><?php echo $item['grupo'] ?></td>
                <td><?php echo $item['linea'] ?></td>
                <td><?php echo $item['cant'] ?></td>
                <td><?php echo round($item['importe'],1) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tblVentasxProducto");
</script>