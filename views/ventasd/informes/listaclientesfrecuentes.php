<table id="tblclientesfrecuentes" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th data-sortable="true" class="">Cliente</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Valor</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">IGV</th>
            <th class="text-end" data-sortable="true" data-footer-formatter="formatTotal">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><b><?php echo $item['razo'] ?></b></td>
                <td><?php echo round($item['valor'], 3) ?></td>
                <td><?php echo round($item['igv'], 3) ?></td>
                <td><?php echo round($item['importe'], 3) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tblclientesfrecuentes");
    graficarclientesmasfrecuentes()
    graficarclientesmenosfrecuentes();

    function graficarclientesmasfrecuentes() {
        const ctx = document.getElementById('myChart');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($listagrafico1, 'razo')); ?>,
                datasets: [{
                    label: 'Total Vendido',
                    data: <?php echo json_encode(array_column($listagrafico1, 'importe')); ?>,
                    backgroundColor: <?php echo json_encode(array_column($listagrafico1, 'color')); ?>,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Los 20 clientes más frecuentes',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
        myChart.resize(500, 500);
    }

    function graficarclientesmenosfrecuentes() {
        const ctx = document.getElementById('myChart2');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($listagrafico2, 'razo')); ?>,
                datasets: [{
                    label: 'Total Vendido',
                    data: <?php echo json_encode(array_column($listagrafico2, 'importe')); ?>,
                    backgroundColor: <?php echo json_encode(array_column($listagrafico2, 'color')); ?>,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Los 20 clientes menos frecuentes',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
        myChart.resize(500, 500);
    }
</script>