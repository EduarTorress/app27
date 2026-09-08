<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th data-sortable="true">Producto</th>
            <th data-sortable="true">Categoria</th>
            <th class="text-end" data-sortable="true">Costo</th>
            <th class="text-end" data-sortable="true">Cantidad Existente</th>
            <th class="text-center">Ver</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $fecha = date('Y-m-d');
        //$nalma = $_SESSION['idalmacen'];
        ?>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['categoria'] ?></td>
                <td><?php echo $item['prec'] ?></td>
                <td><?php echo $item['alma'] ?></td>
                <td><a target="_blank" rel="noopener noreferrer" href="<?php echo "/inventarios/kardex?coda=" . $item['idart'] . "&producto=" . str_replace('"', "'", trim($item['descri'])) . "&alma=" . $nalma . "&fecha=" . $fecha ?>"><?php echo 'Kardex' ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#table");
</script>