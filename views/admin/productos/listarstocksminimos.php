<br>
<div class="table-responsive">
    <table id="tablastockminimos" class="table table-bordered border-dark table-sm small">
        <thead>
            <tr>
                <th class="text-center">Código</th>
                <th data-sortable="true">Producto</th>
                <?php $sucursales = cargarsucursales(); ?>
                <?php foreach ($sucursales as $s) : ?>
                    <th id="<?php echo $s['idalma']; ?>"><?php echo $s['nomb']; ?></th>
                <?php endforeach; ?>
                <th class="text-end" data-sortable="true">Stock Total</th>
                <th class="text-end" data-sortable="true">Stock Minimo</th>
                <th class="text-end" data-sortable="true">Estado Actual</th>
            </tr>
        </thead>
        <tbody>
            <?php $tds = cargarsucursalestbody(); ?>
            <?php foreach ($listado as $item) : ?>
                <?php
                $estado = "";
                $color = "";
                $stocktotal = $item['uno'] + $item['dos'] + $item['tre'] + $item['cua'] + $item['cin'];
                $diferencia = $stocktotal - $item['prod_smin'];
                if ($diferencia >= 0) {
                    $estado = '+' . $diferencia;
                    $color = "#9eeb47";
                } else {
                    $estado = $diferencia;
                    $color = "#f59794";
                }
                ?>
                <tr>
                    <td style="background-color: <?php echo $color; ?>;"><?php echo $item['idart'] ?></td>
                    <td style="background-color: <?php echo $color; ?>;"><?php echo $item['descri'] ?></td>
                    <?php foreach ($tds as $t) : ?>
                        <td style="background-color: <?php echo $color; ?>;" class=" text-end" id="<?php echo $t; ?>"><?php echo $item["$t"]; ?></td>
                    <?php endforeach; ?>
                    <td style="background-color: <?php echo $color; ?>;"><?php echo $stocktotal; ?> </td>
                    <td style="background-color: <?php echo $color; ?>;"><?php echo $item['prod_smin'] ?></td>
                    <td style="background-color: <?php echo $color; ?>;"> <?php echo $estado;  ?> </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tablastockminimos");
</script>