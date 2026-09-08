<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Detalle</th>
            <th class="text-end">Ingresos</th>
            <th class="text-end">Salidas</th>
            <th class="text-end">Stock </th>
            <th class="text-center">Mon.</th>
            <th class="text-end">Precio</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Fecha/Hora</th>
            <th class="text-center">Autorizo</th>
            <th class="text-center">Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fecha'] ?></td>
                <td><?php echo $item['dcto'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <?php
                // $ingresos = $item['ingr'];
                // $color = "white";
                // if (floatval($ingresos > 0)) {
                //     $color = "#22C55E";
                // }else{
                //       $color = "#FB923C";
                // }
                // if(floatval($ingresos == 0) && floatval($item['egre']==0)){
                //      $color = "yellow";
                // }
                ?>
                <!-- style="background-color:<?php echo $color; ?>" -->
                <td><?php echo $item['ingr'] ?></td>
                <?php
                // $egresos = $item['egre'];
                // $colore = "white";
                // if (floatval($egresos > 0)) {
                //     $colore = "#b6daf4ff";
                // }
                ?>
                <!-- style="background-color:<?php echo $color; ?>" -->
                <td><?php echo $item['egre'] ?></td>
                <?php
                // $stock = $item['saldo'];
                // $colors = "#1F9D55";
                // if (floatval($stock < 0)) {
                //     $colors = "#E02424";
                // }
                // if(floatval($stock == 0)){
                //      $colors = "yellow";
                // }
                ?>
                <!-- style="background-color:<?php echo $colors; ?>" -->
                <td><?php echo $item['saldo'] ?></td>
                <td><?php echo $item['moneda'] ?></td>
                <td><?php echo $item['precio'] ?></td>
                <td><?php echo $item['usua'] ?></td>
                <td><?php echo $item['fusua'] ?></td>
                <td><?php echo $item['usua1'] ?></td>
                <td><?php echo $item['tipomvto'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // $(document).ready(function() {
    //     reporteTabla('#table');
    // });
    reportetablebt("#table");
</script>