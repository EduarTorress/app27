<div class="table-responsive">
    <table id="tabla" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Razón Social</th>
                <th>Nro. Documento</th>
                <th>Fecha</th>
                <th>Cantidad</th>
                <?php if (!empty($listacompras)) : ?>
                    <th>Precio Compra</th>
                <?php endif; ?>
                <th>Precio</th>
                <th>Moneda</th>
                <th>Tipo Documento</th>
                <th>Mes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['razo'] ?></td>
                    <td><?php echo $item['ndoc'] ?></td>
                    <td><?php echo $item['fech'] ?></td>
                    <td><?php echo $item['cant'] ?></td>
                    <?php if (!empty($listacompras)) : ?>
                        <td><?php echo $item['precc'] ?></td>
                    <?php endif; ?>
                    <td><?php echo $item['prec'] ?></td>
                    <td><?php echo ($item['mone'] == 'S' ? 'SOLES' : 'DÓLARES') ?></td>
                    <td><?php echo $item['tdoc'] ?></td>
                    <td> <?php echo getnamemonth($item['mes']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>