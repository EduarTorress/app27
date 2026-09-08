<div class="table-responsive">
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <span class="badge" style="background-color:#28a745;">0-30 días</span>
        <span class="badge text-dark" style="background-color:#ffc107;">31-90 días</span>
        <span class="badge" style="background-color:#fd7e14;">91-180 días</span>
        <span class="badge" style="background-color:#dc3545;">+180 días</span>
    </div>
    <table id="table" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="text-center">Código</th>
                <th data-sortable="true">Producto</th>
                <!-- <th data-sortable="true" data-footer-formatter="formatTotal">Stock</th> -->
                <th class="text-center" data-sortable="true">Ultimo Movimiento</th>
                <th class="text-center" data-sortable="true">Dias sin Movimiento</th>
                <th class="text-center" data-sortable="true">Visualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <?php
                switch (true) {
                    case ($item['DiasSinMovimiento'] <= 30):
                        $color = '#28a745';
                        break;
                    case ($item['DiasSinMovimiento'] <= 90):
                        $color = '#ffc107;';
                        break;
                    case ($item['DiasSinMovimiento'] <= 180):
                        $color = '#fd7e14';
                        break;
                    default:
                        $color = '#dc3545;';
                        break;
                }
                ?>
                <tr style="<?php echo $color; ?>">
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['idart'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['descri'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['UltimoMovimiento'] ?></b></td>
                    <td style="color:<?php echo $color; ?>"><b><?php echo $item['DiasSinMovimiento'] ?></b></td>
                      <td> <a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['idart'] . "&producto=" . str_replace('"', "'", trim($item['descri'])) . "&alma=" . $nalma . "&fecha=" . $fecha ?>"><?php echo 'Kardex' ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        reportetablebt("#table");
    </script>
</div>