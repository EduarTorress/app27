<?php
use App\View\Components\AlmacenComponent;
use App\View\Components\SerieComponent;
?>
<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Datos Globales</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $cempresa = '';
                    $empresa = new \App\View\Components\AlmacenComponent($cempresa);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="row">
                    <?php
                    $serie = new \App\View\Components\SerieComponent();
                    echo $serie->render();
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="obtenerDatos();" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function obtenerDatos() {
        let alm = document.getElementById("cmbAlmacen").value;
        let serie = document.getElementById("cmbSerie").value;

        data = new FormData();
        data.append("alm", alm);
        data.append("serie", serie);

        axios.post("/admin/sesion", data)
            .then(function(respuesta) {
                window.location.href = '/';
                $("#info").modal('hide');
            }).catch(function(error) {
                alert("Por favor complete todos los datos.");
            });
    }
</script>