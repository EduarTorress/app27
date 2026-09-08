
<table id="table" data-show-export="true" class="table table-bordered border-primary table-sm small ">
    <thead>
        <tr>
            <th colspan="3" class="text-center table-info">Información General</th>
            <th colspan="2" class="text-center table-yellow">Producto</th>
            <th colspan="2" class="text-center table-secondary">Precios</th>
            <th colspan="4" class="text-center table-success">Resultados</th>
        </tr>
        <tr>
            <th class="table-info">Documento</th>
            <th class="table-info">Fecha</th>
            <th class="table-info">Vendedor</th>
            <th class="table-yellow">Descripción</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-yellow">Cantidad Vendida</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-secondary">Precio Venta</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-secondary">Costo Unitario</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-success">Venta Total</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true"  class="text-end table-success">Costo Total</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-success">Porcentaje (%)</th>
            <th data-footer-formatter="formatTotal"  data-sortable="true" class="text-end table-success">Utilidad (Ganancia)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['Vendedor'] ?></td>
                <td><?php echo $item['Descri'] ?></td>
                <td><?php echo $item['cant'] ?></td>
                <td><?php echo $item['PrecioVenta'] ?></td>
                <td><?php echo $item['costounitario'] ?></td>
                <td><?php echo $item['ventatotal'] ?></td>
                <td><?php echo $item['costototal'] ?></td>
                <td><?php echo $item['porcentaje'] ?></td>
                <td><?php echo $item['Utilidad'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>