<div class="modal fade" id="modaldepositoencuenta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Deposito en Cuenta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="" class="col-sm-4 col-form-label">Banco:</label>
                    <div class="col-sm-8">
                        <select name="cmbnumeroscuenta" id="cmbnumeroscuenta" class="form-control">
                            <?php foreach ($numeroscuenta as $nc): ?>
                                <option value="<?php echo $nc['ctas_idct'] ?>"><?php echo $nc['ctas_ctas'] . ' - ' . $nc['banc_nomb'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label for="" class="col-sm-4 col-form-label">Fecha:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="txtfechade" value="<?php echo date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="row">
                    <label for="" class="col-sm-4 col-form-label">Tipo de Cambio:</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control text-right" id="txttipocambiode" value="<?php echo $_SESSION['gene_dola']; ?>">
                    </div>
                </div>
                <div class="row">
                    <label for="" class="col-sm-4 col-form-label">Operación:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="txtoperacionde" placeholder="Numero de Operación (referencial)">
                    </div>
                </div>
                <div class="row">
                    <label for="" class="col-sm-4 col-form-label">Valor:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control text-right" id="txtimportede" placeholder="0.00">
                    </div>
                </div>
                <div class=" row">
                    <label for="" class="col-sm-4 col-form-label">Forma de Pago:</label>
                    <div class="col-sm-8">
                        <select name="cmbformapagode" id="cmbformapagode" class="form-control">
                            <option selected value="D">DEPOSITO</option>
                        </select>
                    </div>
                </div>
                <div class=" row">
                    <label for="" class="col-sm-4 col-form-label">Detalle:</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="txtdetallede">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  data-bs-dismiss="modal">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>