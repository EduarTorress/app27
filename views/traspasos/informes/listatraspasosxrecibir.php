<table id="tablatraspasos" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro. Guia</th>
            <th>Sucursal Proveniente</th>
            <th>Motivo</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['nomb'] ?></td>
                <td>Traspaso </td>
                <td class="text-center">
                    <a class="btn btn-primary " role="button" onclick="consultardetalle('<?= $item['idauto'] ?>')">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#tablatraspasos");
</script>