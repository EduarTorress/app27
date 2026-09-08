<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalImprimir;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="input-group">
                        <label for="txtfecha" class="col-sm-0.5 col-form-label col-form-label-sm">Fecha: </label>
                        <div class="col-sm-1.5">
                            <input type="date" class="form-control form-control-sm" id="txtfecha" value="<?php echo date("Y-m-d") ?>">
                        </div>
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent($_SESSION['usuario_id']);
                        echo $lu->render();
                        ?>
                        <div class="col-sm-2">
                            <?php
                            $ec = new EmpresaComponent($_SESSION['idalmacen']);
                            echo $ec->render();
                            ?> &nbsp;
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-success btn-sm" onclick="listarcomparabancos()">Consultar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="resultado"></div>
        </div>
    </div>
</div>
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");

        const select = document.getElementById("cmbusuarios");
        const nuevaOpcion = document.createElement("option");

        nuevaOpcion.value = "0";
        nuevaOpcion.text = "SELECCIONE (TODOS)";

        // Inserta la nueva opción antes del primer elemento hijo
        select.insertBefore(nuevaOpcion, select.firstChild);

        $("#cmbusuarios").removeAttr("disabled");
        $("#cmbAlmacen").attr("disabled", true);
        if (tipousuario != 'A') {
            $("#cmbusuarios").attr("disabled", "disabled");
        }
    }

    function listarcomparabancos() {
        axios.get('/cajas/listarcomparabancos', {
            "params": {
                "txtfech": $("#txtfecha").val(),
                "cmbusuarios": $("#cmbusuarios").val(),
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultado').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>