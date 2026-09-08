<div class="table-responsive">
    <table id="tablapedidos" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>N° Doc</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Importe</th>
                <th class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['ndoc'] ?></td>
                    <td><?php echo $item['fech'] ?></td>
                    <td><?php echo $item['razo'] ?></td>
                    <td><?php echo $item['impo'] ?></td>
                    <td class="text-center">
                        <?php
                        $idautop = $item['idautop'];
                        $ndoc = $item['ndoc'];
                        $fech = $item['fech'];
                        $impo = $item['impo'];
                        $idclie = $item['idclie'];
                        $razo = str_replace("'", " ", $item['razo']);
                        $ndni = $item['ndni'];
                        $nruc = $item['nruc'];
                        $dire = $item['dire'];
                        $tdoc = $item['tdoc'];
                        $form = $item['form'];
                        $mone = $item['mone'];
                        $idusua = $item['rped_idus'];
                        $idven = $item['idven'];
                        $usuario = $item['usuario'];
                        $parametros = compact('idautop', 'ndoc', 'fech', 'impo', 'idclie', 'razo', 'ndni', 'nruc', 'dire', 'tdoc', 'form', 'idven', 'mone', 'idusua', 'usuario');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <a class="btn btn-success" role="button" onclick='seleccionarpedido(<?php echo $cadena_json ?>)'>
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $('#tablapedidos').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
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