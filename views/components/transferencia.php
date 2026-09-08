<br>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Nro Documento</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="txtnumerodocumentot">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Fecha</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" id="txtfechat" value="<?php echo date('Y-m-d') ?>">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Forma de Pago:</label>
    <div class="col-sm-3">
        <select name="cmbformapago" id="cmbformapagot" class="form-control">
            <option selected value="D">DEPOSITO</option>
        </select>
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Saldo:</label>
    <div class="col-sm-3">
        <input type="number" class="form-control" id="txtsaldot" placeholder="0.00">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Importe:</label>
    <div class="col-sm-3">
        <input type="number" class="form-control" id="txtimportet" placeholder="0.00">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Detalle:</label>
    <div class="col">
        <input type="text" class="form-control" id="txtdetallet">
    </div>
</div>
<div class="mb-3 row">
    <div class="col-sm">
        <button type="button" onclick="limpiartransferencia();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
        <button type="button" onclick="registrartransferencia();" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</button>
    </div>
</div>