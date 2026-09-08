<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th class="text-center">Mes</th>
            <th class="text-end" data-footer-formatter="formatTotal">Importe Vendido</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo getnamemonth($item['mes']) ?></td>
                <td><?php echo $item['importe'] ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><?php echo getnamemonth($siguientemes); ?></td>
            <td><?php echo $prediction;  ?></td>
        </tr>
    </tbody>
</table>
<script>
    <?php if ($prediction == 0): ?>
        alert("No se pudo generar la información porque no hay registros suficientes (Al menos seís meses) para realizar la predicción a traves de la Inteligencia Artificial")
    <?php endif; ?>
    reportetablebt("#table");
</script>