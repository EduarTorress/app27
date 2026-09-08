<table id="tablaGuias" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro.Guia</th>
            <th>Fecha</th>
            <th>Fecha Tras.</th>
            <th>Destinatario</th>
            <th class="text-center">Motivo</th>
            <th class="text-center">Enviar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr class="fila">
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecht'] ?></td>
                <td><?php echo $item['dest'] ?></td>
                <td class="text-center"><?php echo $item['motivo'] ?></td>
                <td class="text-center" id="enviar">
                    <?php if ($item['tipoguia'] == 'T') : ?>
                        <a class="btn btn-info" role="button" onclick="enviarsunatguiatr('<?= $item['idauto'] ?>','<?= $item['nombrexml'] . '.xml' ?>')">
                            <i class="fas fa-cloud-download-alt"></i>
                        </a>
                    <?php else : ?>
                        <a class="btn btn-info" role="button" onclick="enviarsunatguiar('<?= $item['idauto'] ?>','<?= $item['motivo'] ?>','<?= $item['nruc'] ?>')">
                            <i class="fas fa-cloud-download-alt"></i>
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<button class="btn btn-success my-1" onclick="enviarTodo();">Enviar todo</button>
<script>
    // $('#tablaGuias').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": true,
    //     "info": false,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //         targets: 5,
    //         orderable: false,
    //         searchable: false
    //     }]
    // });
    reportetablebt("#tablaGuias");
</script>