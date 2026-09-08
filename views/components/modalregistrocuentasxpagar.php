<div class="modal fade" id="modalregistrocuentasxpagar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h4 class="modal-title" id="">Crédito por Cuotas</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">IMPORTE FINAL:</label>
                    <div class="col-sm-4">
                        <input type="text" name="txtimportefinal" id="txtimportefinal" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">TIPO DOCUMENTO:</label>
                    <div class="col-sm-4">
                        <select name="" id="cmbtipodocumentocuentasxpagar" class="form-control">
                            <option value="F">FACTURA</option>
                            <option value="L">LETRA</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">N° LETRAS:</label>
                    <div class="col-sm-4">
                        <input type="text" onkeypress="return isNumber(event);" onkeyup="crearfilas();" name="txtnumeroletras" id="txtnumeroletras" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="table-responsive">
                            <table id="tblletras" class="table table-bordered table-hover table-sm small">
                                <thead>
                                    <tr>
                                        <th class="text-center">N° Documento</th>
                                        <th class="text-center">Días Vto.</th>
                                        <th class="text-center">Fecha Vto.</th>
                                        <th class="text-center">Detalle</th>
                                        <th class="text-center">Importe Girado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $btnclick = "grabar('¿Registrar Documento?')";
            $url = $_SERVER['REQUEST_URI'];
            $url = explode("/", $url);
            $url = empty($url[2]) ? ' ' : $url[2];
            if ($url == 'buscarventa') {
                $btnclick = "actualizar('Actualizar Documento?')";
            }
            ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="<?php echo $btnclick; ?>">Registrar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>