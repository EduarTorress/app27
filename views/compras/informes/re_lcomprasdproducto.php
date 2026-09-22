<table id="tblVentasxProducto" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr class="text-center">
            <th data-sortable="true">PRODUCTO</th>
            <th data-sortable="true">MARCA</th>
            <th data-sortable="true">UNID</th>
            <th data-sortable="true">GRUP.</th>
            <th data-sortable="true">LINE.</th>
            <th data-sortable="true" class="text-end">CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['PRODUCTO'] ?></td>
                <td><?php echo $item['MARCA'] ?></td>
                <td><?php echo $item['UNIDAD'] ?></td>
                <td><?php echo $item['GRUPO'] ?></td>
                <td><?php echo $item['LINEA'] ?></td>
                <td><?php echo $item['cantidad'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // reporteTablaLyG('#tblVentasxProducto');
    reportetablebt("#tblVentasxProducto");
</script>