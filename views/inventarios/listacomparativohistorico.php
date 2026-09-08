<div class="table-responsive">
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <span class="badge" style="background-color:#28a745;">
            Alta Demanda (≥ 100)
        </span>
        <span class="badge text-dark" style="background-color:#ffc107;">
            Demanda Media (50 - 99)
        </span>
        <span class="badge" style="background-color:#fd7e14;">
            Demanda Baja (10 - 49)
        </span>
        <span class="badge" style="background-color:#dc3545;">
            Sin Demanda (< 10)
        </span>
    </div>
    <table id="table" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="text-center">Código</th>
                <th data-sortable="true">Producto</th>
                <th class="text-center" data-sortable="true">Stock Actual</th>
                <th class="text-center" data-sortable="true">Consumo hace <?php echo $dias ?> días </th>
                <th class="text-center" data-sortable="true">Diferencia</th>
                <th class="text-center" data-sortable="true">Estado</th>
                <th class="text-center" data-sortable="true">Visualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <?php
                switch (true) {
                    case ($item['consumo'] >= 100):
                        $color = '#28a745';
                        $estado = 'ALTA ROTACIÓN';
                        break;
                    case ($item['consumo'] >= 50):
                        $color = '#ffc107;';
                        $estado = 'DEMANDA MEDIA';
                        break;
                    case ($item['consumo'] >= 10):
                        $color = '#fd7e14';
                        $estado = 'DEMANDA BAJA';
                        break;
                    default:
                        $color = '#dc3545;';
                        $estado = 'SIN DEMANDA';
                        break;
                }
                switch ($nalma) {
                    case 1:
                        $valor = $item['uno'];
                        break;

                    case 2:
                        $valor = $item['dos'];
                        break;

                    case 3:
                        $valor = $item['tres'];
                        break;

                    case 4:
                        $valor = $item['cua'];
                        break;

                    case 5:
                        $valor = $item['cin'];
                        break;

                    case 6:
                        $valor = $item['sei'];
                        break;

                    default:
                        $valor = 0;
                        break;
                }
                ?>
                <tr style="<?php echo $color; ?>">
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['idart'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['descri'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['uno'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['consumo'] ?></b></td>
                    <td style=" color:<?php echo $color; ?>"><b><?php echo max(0, $valor) - $item['consumo']?></b></td>
                    <td style=" color:<?php echo $color; ?>"><b><?php echo $estado ?></b></td>
                    <td> <a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['idart'] . "&producto=" . str_replace('"', "'", trim($item['descri'])) . "&alma=" . $nalma . "&fecha=" . $fecha ?>"><?php echo 'Kardex' ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#table");
</script>