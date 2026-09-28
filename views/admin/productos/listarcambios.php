<br>
<div class="table-responsive">
    <table id="tablalistarcambios" class="table table-bordered border-dark table-sm small">
        <thead>
            <tr>
                <th class="text-center">Código</th>
                <th data-sortable="true">Producto (Actual)</th>
                <th class="text-center" data-sortable="true">Cambio (Detallado)</th>
                <th class="text-center" data-sortable="true">Fecha / Hora</th>
                <th class="text-center" data-sortable="true">Usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['prod_idar'] ?></td>
                    <td><b><?php echo $item['producto'] ?></b></td>
                    <td><?php echo $item['prod_deta'] ?></td>
                    <td><b><?php echo $item['prod_fope'] ?></b></td>
                    <td><b><?php echo $item['usuario'] ?></b></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tablalistarcambios");
</script>