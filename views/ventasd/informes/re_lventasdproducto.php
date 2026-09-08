<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr class="text-center">
            <th data-sortable="true">CÓDIGO</th>
            <th data-sortable="true">PRODUCTO</th>
            <th data-sortable="true">MARCA</th>
            <th data-sortable="true">UNID</th>
            <th data-sortable="true">GRUP.</th>
            <th data-sortable="true">LINE.</th>
            <th data-sortable="true" class="text-end" data-footer-formatter="formatTotal" >CANTIDAD</th>
            <th data-sortable="true" class="text-end" data-footer-formatter="formatTotal" >IMPORTE</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['prod_cod1'] ?></td>
                <td><?php echo $item['Descri'] ?></td>
                <td><?php echo $item['marca'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td><?php echo $item['grupo'] ?></td>
                <td><?php echo $item['linea'] ?></td>
                <td><?php echo $item['cant'] ?></td>
                <td><?php echo round($item['importe'],3) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tblVentasxProducto");
</script>