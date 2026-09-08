<table id="tablaGuias" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro.Guia</th>
            <th>Fecha</th>
            <th>Fecha Tras.</th>
            <th>Destinatario</th>
            <th class="text-center">Motivo</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecht'] ?></td>
                <td><?php echo $item['razo'] ?></td>
                <td class="text-center">
                    <?php
                    if (trim($item['guia_moti']) == 'V') {
                        echo "Venta";
                        $ruta = "/guiasr/buscarGuia/";
                    } else {
                        if ($item['guia_moti'] == 'C') {
                            echo "Compra";
                            $ruta = "/guiasc/buscarGuia/";
                        } else {
                            echo "Devolución";
                        }
                    }
                    ?>
                </td>
                <td class="text-center">
                    <a class="btn btn-primary " role="button" onclick="descargarpdf('<?= $item['idauto'] ?>','<?= $item['nombrexml'] . '.pdf' ?>','<?= $item['guia_moti'] ?>')">
                        <i class="fas fa-print"></i>
                    </a>
                    <a class="btn btn-info" role="button" onclick="descargarxml('<?= $item['idauto'] ?>','<?= $item['nombrexml'] . '.xml' ?>')">
                        <i class="fas fa-cloud-download-alt"></i>
                    </a>
                    <?php if ($item['guia_moti'] != 'D'): ?>
                        <a class="btn btn-success" role="button" onclick="" href="<?php echo $ruta . $item['idauto'] ?>">
                            <i class="fas fa-eye"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    // $('#tablaGuias').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": true,
    //     "info": false,
    //     "autoWidth": true,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 4,
    //         orderable: false,
    //         searchable: false
    //     }]
    // });
    reportetablebt("#tablaGuias");
</script>