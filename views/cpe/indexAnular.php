<?php

use App\View\Components\DocumentoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <?php
                                $dctos = new DocumentoComponent("%%");
                                echo $dctos->listardctosanular();
                                ?><br>
                                <div class="input-group mb-1">
                                    <label class="form-control form-control-sm">Tipo M :</label>
                                    <select class="form-select-sm form-control form-control-sm" id="cmbTipoMovimiento" name="cmbTipoMovimiento">
                                        <option value="V" selected>Ventas</option>
                                        <option value="C">Compras</option>
                                        <option value="CE">Caja</option>
                                    </select>
                                </div>
                                <div class="input-group">&nbsp;&nbsp;&nbsp;
                                    <label class="col-sm-0 col-form-label col-form-label-sm">Documento :</label>
                                    <input type="text" class="form-control form-control-sm" style="width: 60%;" required id="txtNumeroDocumento" placeholder="F00100000001" value="">
                                </div>
                                <button type="submit" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        consultarDetalle();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbdcto").append('<option value="I">INGRESO</option>')
        $("#cmbdcto").append('<option value="E">EGRESO</option>')
    }

    function consultarDetalle() {
        var txtNumeroDocumento = document.getElementById("txtNumeroDocumento").value;
        var cmbdcto = document.getElementById("cmbdcto").value;
        var cmbTipoMovimiento = document.getElementById("cmbTipoMovimiento").value;
        axios.get('/cpe/listarDetalleAnular', {
            "params": {
                "txtNumeroDocumento": txtNumeroDocumento,
                "cmbdcto": cmbdcto,
                "cmbTipoMovimiento": cmbTipoMovimiento
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>