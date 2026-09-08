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
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">
                                <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf">
                                <button class="btn btn-primary my-1">Consultar</button>
                                <button class="btn btn-success my-1" type="button" role="button" onclick="$('#mdfotocompra').modal('show');">Importar Foto</button>
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
<div class="modal fade" id="mdfotocompra" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lbltitlemodal">Importar nueva Foto</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formupdateproducto">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <input type="file" accept="image/jpeg" name="txtimage" class="form-control" id="txtimage" placeholder="">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="importarfoto();" id="btnguardar">Guardar cambios</button>
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
        obtenerFechas();
        titulo('<?php echo $titulo; ?>');
    }

    $("#mdfotocompra").on("hidden.bs.modal", function() {
        input = document.getElementById("txtimage");
        input.type = "text";
        input.type = "file";
    });

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/compras/listarfotos', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function importarfoto() {
        $("#btnguardar").attr("disabled", true);
        const formulario = document.getElementById('formupdateproducto');
        const data = new FormData(formulario);
        axios.post("/compras/importarfoto", data)
            .then(function(respuesta) {
                toastr.success('Se actualizo correctamente', 'Mensaje del Sistema');
                $("#mdfotocompra").modal('hide');
                $("#btnguardar").removeAttr("disabled");
            }).catch(function(error) {
                toastr.error(error.response.data, 'Mensaje del Sistema');
                 $("#btnguardar").removeAttr("disabled");
            });
    }
</script>
<?php
$this->endSection('javascript');
?>