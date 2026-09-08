<div class="modal fade" id="modal_choferes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title">Choferes</h4>
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
                                    <th>Chofer</th>
                                    <th>Brevete</th>
                                    <th class="text-center">Seleccionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista as $item) : ?>
                                    <tr>
                                        <td><?php echo $item['nombr'] ?></td>
                                        <td><?php echo $item['breve'] ?></td>
                                        <td class="text-center" id="iniciar">
                                            <?php
                                            $parametro1 = $item['nombr'];
                                            $parametro2 = $item['breve'];
                                            $parametro3 = $item['idtra'];
                                            $parametros = compact('parametro1', 'parametro2','parametro3');
                                            $cadena_json = json_encode($parametros);
                                            ?>
                                            <button class="btn btn-success" onclick='seleccionarChofer(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
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
    function seleccionarChofer(datos) {
        document.getElementById("txtChoferVehiculo").value = datos.parametro1;
        document.getElementById("txtbrevete").value = datos.parametro2;
        $("#modal_choferes").modal('hide');
    }
</script>