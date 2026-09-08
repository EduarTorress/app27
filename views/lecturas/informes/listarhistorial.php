<table id="table" data-show-export="true" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Producto</th>
            <th data-sortable="true" class="text-end">C. Inicial</th>
            <th data-sortable="true" class="text-end">C. Final</th>
            <th data-sortable="true" class="text-end">Cantidad</th>
            <th data-sortable="true" class="text-end">Precio S/</th>
            <th data-sortable="true" class="text-end">Venta S/</th>
            <th data-sortable="true" class="text-end">M. Inicial</th>
            <th data-sortable="true" class="text-end">M. Final</th>
            <th data-sortable="true" class="text-end">Monto S/</th>
            <th data-sortable="true" class="text-center">Mang</th>
            <th data-sortable="true" class="text-center">Surt.</th>
            <th data-sortable="true" class="text-center">Usuario</th>
            <th data-sortable="true" class="text-center">Fecha Hora Inicio</th>
            <th data-sortable="true" class="text-center">Fecha Hora Final</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['producto'] ?></td>
                <td><?php echo $item['inicial'] ?></td>
                <td><?php echo $item['final'] ?></td>
                <td><?php echo $item['cantidad'] ?></td>
                <td><?php echo $item['Precio'] ?></td>
                <td><?php echo $item['Ventas'] ?></td>
                <td><?php echo $item['montoinicial'] ?></td>
                <td><?php echo $item['montofinal'] ?></td>
                <td><?php echo $item['monto'] ?></td>
                <td><?php echo $item['manguera'] ?></td>
                <td><?php echo $item['surtidor'] ?></td>
                <td><?php echo $item['Cajero'] ?></td>
                <td><?php echo $item['InicioTurno'] ?></td>
                <td><?php echo $item['FinTurno'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>