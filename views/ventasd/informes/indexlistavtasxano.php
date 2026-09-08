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
                                <label class="my-1 mr-2" for="">Año:</label>
                                <select class="form-control form-control-sm" id="cmbano" name="cmbano">
                                    <option value="2024">2024</option>
                                    <option value="2025" selected>2025</option>
                                    <option value="2026">2026</option>
                                </select>
                                <?php
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <button id="btnconsultar" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="search">
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
        $("#cmbAlmacen").attr("disabled", false);
    }

    function search() {
        var ano = document.getElementById("cmbano").value;
        // $('#loading').modal('show');
        $("#btnconsultar").attr('disabled', true);
        axios.get('/vtas/listaventasxano', {
            "params": {
                "ano": ano,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $("#btnconsultar").attr('disabled', false);
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado','Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>