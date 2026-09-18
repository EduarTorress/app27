<?php

use App\View\Components\EmpresaComponent;

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
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <div id="fechas">
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
        $("#cmbAlmacen").removeAttr("disabled", "disabled");
    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listavarillajeymedicion', {
            "params": {
                "txtfechai": dfechai,
                "txtfechaf": dfechaf
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el listado", 'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>