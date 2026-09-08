<!-- Modal ventas -->
<div class="modal fade" id="modalpagos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="">Lista de Pagos</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <div class="input-group mb-3">
                    <div class="col-12" id="search">
                        <div class="table-responsive">
                            <table id="tabla" class="table table-bordered table-hover table-sm small">
                                <thead>
                                    <tr>
                                        <th class="text-center">Año</th>
                                        <th class="text-center">Mes</th>
                                        <th class="text-center">Fecha de Operacion</th>
                                        <th class="text-center">Registrado Por</th>
                                        <th class="text-end">Sueldo</th>
                                        <th class="text-end">Acta de Pago</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listado as $item) : ?>
                                        <tr>
                                            <td class="text-center"><?php echo date('Y', strtotime($item['fech'])) ?></td>
                                            <td class="text-center"><?php echo $mes ?></td>
                                            <td class="text-center"><?php echo $item['fusua'] ?></td>
                                            <td class="text-center"><?php echo $item['nomb'] ?></td>
                                            <td class="text-end"><?php echo $item['sueldo'] ?></td>
                                            <td class="text-end"><?php echo $item['actas'] ?></td>
                                            <?php
                                            $parametro1 = $item['idpagos'];
                                            $parametros = compact('parametro1');
                                            $cadena_json = json_encode($parametros);
                                            ?>
                                            <td class="text-center">
                                                <a class="btn btn-danger btn-sm" role="button" onclick='darbaja(<?php echo $cadena_json ?>,this)'>
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#tabla').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false,
    });

    $('#modalpagos').on('hidden.bs.modal', function() {
        var cantfilas = $('#tabla >tbody >tr').length;
        if (cantfilas != 0) {
            listarsaldoxusuario();
        } else {
            $("#btnregistrar").attr('disabled', 'disabled');
            $("#txtapagar").attr('disabled', 'disabled');
        }
    });
</script>