<table id="table" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Detalle</th>
            <th>Usuario</th>
            <th class="text-center">Fecha Usuario</th>
            <th class="text-center">Opción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listado as $item) : ?>
            <tr>
                <td><?php echo $item['fech'] ?></td>
                <td><?php echo $item['ndoc'] ?></td>
                <td><?php echo $item['deta'] ?></td>
                <td><?php echo $item['nomb'] ?></td>
                <td class="text-center"><?php echo $item['fusua'] ?></td>
                <?php
                $idauto = $item['idauto'];
                $fech = $item['fech'];
                $ndoc = $item['ndoc'];
                $deta = $item['deta'];
                $nomb = $item['nomb'];
                $parametros = compact(
                    'idauto',
                    'fech',
                    'ndoc',
                    'deta',
                    'nomb'
                );
                $cadena_json = json_encode($parametros);
                ?>
                <td class="text-center">
                    <a class="btn btn-success" role="button" onclick='verdetalle(<?php echo $cadena_json ?>)'>
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
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