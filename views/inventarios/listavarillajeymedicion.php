<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Producto</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Stock Previo</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Stock Medido</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal" class="text-end">Diferencia</th>
            <th class="text-center">Fecha / Hora</th>
            <th class="text-center">Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fecha']; ?></td>
                <td> <?php echo $item['numero_documento']; ?> </td>
                <td> <?php echo $item['producto']; ?> </td>
                <td> <?php echo $item['stock_antiguo']; ?> </td>
                <td> <?php echo $item['stock_ingresado']; ?> </td>
                <td> <?php echo $item['diferencia']; ?> </td>
                <td> <?php echo $item['fusua']; ?> </td>
                <td> <?php echo $item['usuario']; ?> </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>