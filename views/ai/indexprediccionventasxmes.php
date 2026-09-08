<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\EmpresaComponent;
use App\View\Components\FormadepagoComponent;

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
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <?php
                                $dctos = new DocumentoComponent('');
                                echo $dctos->renderreports();
                                ?>
                                &nbsp;&nbsp;
                                <?php
                                $formrepor = new FormadepagoComponent('');
                                echo $formrepor->renderreports();
                                ?>
                                &nbsp;&nbsp;
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <button class="btn btn-success btn-sm">Consultar</button>
                                    </div>
                                </div>
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
$this->startSection("javascript")
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
    }

    function search() {
        $("#btnconsultar").attr('disabled', true);
        axios.get('/ai/listaprediccionventasxmes', {
            "params": {
                "cmbFormaP": $("#cmbForma").val(),
                "cmbtdoc": $("#dctos").val(),
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el reporte" + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>