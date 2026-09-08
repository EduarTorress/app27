<div class="table-responsive">
    <table id="tablaestadocuenta" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Fecha Emi.</th>
                <th>Fecha Vto.</th>
                <th>Documento</th>
                <!-- <th>Cargos</th> -->
                <th>Pagos</th>
                <th>Saldo</th>
                <th>Moneda</th>
                <th>Tip. Cambio</th>
                <th>Tip. Dcto.</th>
                <th>Detalle</th>
                <th>Referencia</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista as $item) : ?>
                <tr>
                    <td class=""><?php echo $item['fepd'] ?></td>
                    <td class=""><?php echo $item['fevd'] ?></td>
                    <td class=""><?php echo $item['ndoc'] ?></td>
                    <!-- <td class=""><?php echo $item['impc'] ?></td> -->
                    <td class=""><?php echo $item['actd'] ?></td>
                    <td class=""><?php echo $item['impd'] ?></td>
                    <td class=""><?php echo $item['mond'] ?></td>
                    <td class=""><?php echo $item['dolar'] ?></td>
                    <td class=""><?php echo substr($item['docd'], 0, 1) ?></td>
                    <td class=""></td>
                    <td class=""><?php echo $item['docd'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    reportetablebt("#tablaestadocuenta");
</script>