<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col">Detalle</th>
            <th scope="col">N° Documento</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Efectivo</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Crédito</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Depósito</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Tarjeta</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">YAPE / PLIN</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Contra Ent.</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Egresos</th>
            <th scope="col" class="text-center">Usuario</th>
            <th scope="col" class="text-center">Moneda</th>
            <th scope="col" class="text-center">Fecha Hora</th>
        </tr>
    </thead>
    <tbody id="">
        <?php foreach ($lista as $l) : ?>
            <tr>
                <td><?php echo $l['deta']; ?></td>
                <td><?php echo $l['ndoc']; ?></td>
                <td><?php echo $l['efectivo']; ?></td>
                <td><?php echo $l['credito']; ?></td>
                <td><?php echo $l['deposito']; ?></td>
                <td><?php echo $l['tarjeta']; ?></td>
                <td><?php echo $l['yape']; ?></td>
                <td><?php echo $l['Centrega']; ?></td>
                <td><?php echo $l['egresos']; ?></td>
                <td><?php echo $l['usua']; ?></td>
                <td><?php echo $l['mone']; ?></td>
                <td><?php echo $l['fechao']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="card ">
    <div class="card-body">
        <div class="mb-3 input-group float-right ">
            <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Total de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txttotalvtas" value="<?php echo ($totalv); ?>">
            </div>
            <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Total Efectivo: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="" value="<?php echo number_format($efectivo, 2, ',', ''); ?>">
            </div>
            <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Total Caja: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="" value="<?php echo number_format($totalc, 2, ',', ''); ?>">
            </div>
            <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Total Egresos: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="" value="<?php echo number_format($egresos, 2, ',', ''); ?>">
            </div>
            <label for="" class="col-sm-0.5 col-form-label col-form-label-sm">Ganancia Total: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="" value="<?php echo number_format(floatval($totalv) - floatval($egresos), 2, ',', ''); ?>">
            </div>
            <div class="col-sm-2">
                <input type="text" placeholder="Ingrese una glosa" id="txtreferencia" class="form-control form-control-sm">
            </div>
            <div class="col">
                <button class="btn btn-primary btn-sm" onclick='generarticketcaja(<?php echo json_encode($lista) ?>)'>Imprimir Ticket</button>
            </div>

            <!--  <label for="txtpagosact" class="col-sm-0.5 col-form-label col-form-label-sm">Pagos Act: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtpagosact" value="">
             </div>
             <label for="txtingsincaja" class="col-sm-0.5 col-form-label col-form-label-sm">Ing sin Caj: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtingsincaja" value="">
             </div>
             <label for="txtegresos" class="col-sm-0.5 col-form-label col-form-label-sm">Egresos: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtegresos" value="">
             </div>
             <label for="txtliquidacion" class="col-sm-0.5 col-form-label col-form-label-sm">Liqui: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtliquidacion" value="">
             </div> -->
        </div>
    </div>
</div>
<script>
    $("#txtingresos").val('<?php echo $saldo['ingresoss']; ?>');
    $("#txtegresos").val('<?php echo $saldo['egresoss']; ?>');
    $("#txtsaldoanterior").val('<?php echo $saldo['saldoanterior']; ?>');
    reportetablebt("#griddetalle");
    graficar();

    function graficar() {
        const ctx = document.getElementById('myChart');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                title: 'Gráfico de Liquidación de Caja',
                labels: ['Efectivo', 'Crédito', 'Depósito', 'Tarjeta', 'YAPE', 'PLIN', 'Contra Entrega', 'Egresos'],
                datasets: [{
                    label: 'Monto',
                    data: [<?php echo array_sum(array_column($lista, 'efectivo'))  ?>,
                        <?php echo array_sum(array_column($lista, 'credito'))  ?>,
                        <?php echo array_sum(array_column($lista, 'deposito'))  ?>,
                        <?php echo array_sum(array_column($lista, 'tarjeta'))  ?>,
                        <?php echo array_sum(array_column($lista, 'yape'))  ?>,
                        <?php echo array_sum(array_column($lista, 'plin'))  ?>,
                        <?php echo array_sum(array_column($lista, 'Centrega'))  ?>,
                        <?php echo array_sum(array_column($lista, 'egresos'))  ?>
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafico de Liquidación de Caja',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    }
</script>