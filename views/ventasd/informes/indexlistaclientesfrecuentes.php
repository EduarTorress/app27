<?php

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
                                <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai" value="<?php echo date('Y-m-d') ?>">
                                <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf" value="<?php echo date('Y-m-d') ?>">
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <?php
                                $formrepor = new FormadepagoComponent('');
                                echo $formrepor->renderreports();
                                ?>
                                &nbsp;
                                <button id="btnconsultar" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive" id="search">
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div id="canvas" class="card border-light">
                    </div>
                </div>
                <div class="col-6">
                    <div id="canvas2" class="card border-light">
                    </div>
                </div>
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
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").removeAttr("disabled");
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        // $('#loading').modal('show');
        $("#btnconsultar").attr('disabled', true);
        axios.get('/vtas/listaclientesfrecuentes', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbalmacen": $("#cmbAlmacen").val(),
                "cmbForma": $("#cmbForma").val(),
            }
        }).then(function(respuesta) {
            $("#canvas").empty();
            $("#canvas").append(' <canvas id="myChart" width="400" height="100"></canvas>');
            $("#canvas2").empty();
            $("#canvas2").append(' <canvas id="myChart2" width="400" height="100"></canvas>');
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            // $('#loading').modal('hide');
            $("#btnconsultar").attr('disabled', false);
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            console.log(error)
            toastr.error('Error al cargar el listado', "Mensaje del Sistema")
        });
    }
</script>
<?php
$this->endSection('javascript');
?>