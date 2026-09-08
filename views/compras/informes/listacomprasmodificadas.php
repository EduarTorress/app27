<table id="tablacompras" class="table table-bordered table-hover table table-sm small">
    <thead>
        <tr>
            <th class="">Documento</th>
            <th class="text-center">Fecha de Compra</th>
            <th class="">Proveedor</th>
            <th class="text-center">Forma de Pago</th>
            <th class="text-center">Moneda</th>
            <th class="text-center">Modifico Usuario</th>
            <th class="text-center">Fecha Operación</th>
            <th class="text-end" style="text-align: right;">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fecr'] ?></td>
                <td><?php echo $item['proveedor'] ?></td>
                <td><?php echo mostrarformapago($item['form']); ?></td>
                <td><?php echo $item['mone'] == 'S' ? 'SOLES' : 'DÓLARES' ?></td>
                <td><b><?php echo  $item['usuario'] ?></b></td>
                <td><b><?php echo  $item['fechaoperacion'] ?></b></td>
                <td style="text-align: right;"><?php echo number_format($item['impo'], 2, '.', '') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablacompras");
</script>