<!-- Modal Guias -->
<div class="modal fade" id="modal_placas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title">Placas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="guias">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchCh">
                        <table id="tabla_choferes" class="table table-bordered table-hover table-sm small">
                            <thead>
                                <tr>
                                    <th>Placa 01</th>
                                    <th>Placa 02</th>
                                    <th class="text-center">Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista as $item) : ?>
                                    <tr>
                                        <td><?php echo $item['vehi_plac'] ?></td>
                                        <td><?php echo $item['vehi_pla2'] ?></td>
                                        <td class="text-center" id="iniciar">
                                            <?php
                                            $parametro1 = $item['vehi_plac'];
                                            $parametro2 = $item['vehi_pla2'];
                                            $parametros = compact('parametro1', 'parametro2');
                                            $cadena_json = json_encode($parametros);
                                            ?>
                                            <button class="btn btn-success" onclick='seleccionarPlaca(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
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
<script>
    function seleccionarPlaca(datos) {
        document.getElementById("txtplaca").value = datos.parametro1;
        document.getElementById("txtPlaca1").value = datos.parametro2;
        $("#modal_placas").modal('hide');
    }
</script>