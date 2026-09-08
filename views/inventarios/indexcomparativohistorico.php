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
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="" class="col-form-label col-form-label-sm">Fecha</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="date" id="txtfecha" value="<?php echo date('Y-m-d'); ?>" class="form-control form-control-sm" aria-describedby="">
                                    </div>
                                    <?php
                                    $ec = new EmpresaComponent('');
                                    echo $ec->render();
                                    ?> &nbsp;
                                    <div class="col-auto">
                                        <label for="" class="col-form-label col-form-label-sm">Días</label>
                                    </div>
                                    <div class="col-auto">
                                        <select name="select" class="form-control form-control-sm" id="txtdias">
                                            <option value="90" selected>90 días</option>
                                            <option value="180" >180 días</option>
                                            <option value="270" >270 días</option>
                                            <option value="360" >360 días</option>
                                        </select>
                                    </div>&nbsp;
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
        $("#cmbAlmacen option[value='0']").remove();
        $("#cmbAlmacen").removeAttr("disabled");
    }

    function search() {
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listacomparativohistorico', {
            "params": {
                'cmbalmacen': $("#cmbAlmacen").val(),
                "txtfecha": $("#txtfecha").val(),
                "txtdias": $("#txtdias").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el reporte", "Mensaje del sistema")
        });
    }
</script>
<?php
$this->endSection("javascript");
?>