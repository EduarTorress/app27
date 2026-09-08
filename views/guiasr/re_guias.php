<table id="tablaGuias" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Nro.Guia</th>
            <th>Fecha</th>
            <th>Fecha Tras.</th>
            <th>Destinatario</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['fecht'] ?></td>
                <td><?php echo $item['dest'] ?></td>
                <td class="text-center">
                    <?php
                    $parametro1 = $item['idauto'];
                    $parametro2 = $item['fech'];
                    $parametro3 = $item['fecht'];
                    $parametro4 = $item['idclie'];
                    $parametro5 = $item['dest'];
                    $parametro6 = $item['dirrem'];
                    $parametro7 = $item['dirdes'];
                    $parametro8 = $item['ndoc'];
                    $parametro9 = $item['nruc'];
                    $parametro10 = $item['guia_mens'];
                    $parametro11 = $item['nombrexml'];
                    $parametro12 = $item['ndoc'];
                    $parametro13 = $item['ndni'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'parametro11', 'parametro12', 'parametro13');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <a class="btn btn-success" role="button" onclick='seleccionarGuia(<?php echo $cadena_json ?>)'>
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $('#tablaGuias').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": true,
        "columnDefs": [{
            targets: 4,
            orderable: false,
            searchable: false
        }]
    });
</script>