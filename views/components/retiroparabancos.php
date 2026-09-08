<br>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Nro Documento</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="txtnumerodocumentorb">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Fecha</label>
    <div class="col-sm-3">
        <input type="date" class="form-control" id="txtfecharb" value="<?php echo date('Y-m-d') ?>">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Saldo:</label>
    <div class="col-sm-3">
        <input type="number" class="form-control" id="txtsaldorb" value="0.00" readonly placeholder="0.00">
    </div>
</div>
<div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Importe:</label>
    <div class="col-sm-3">
        <input type="number" class="form-control" id="txtimporterb" placeholder="0.00">
    </div>
    <button type="button" class="btn btn-primary btn-sm col-sm-3" data-bs-toggle="modal" data-bs-target="#modaldepositoencuenta">Importar desde Bancos</button>
</div>
<?php
$depcue = new \App\View\Components\DepositoenCuentaComponent();
echo $depcue->render();
?>
<div class="mb-3 row">
    <div class="col-sm">
        <button type="button" onclick="limpiaretiroparabancos();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
        <button type="button" onclick="registrarretiroparabancos();" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</button>
    </div>
</div>