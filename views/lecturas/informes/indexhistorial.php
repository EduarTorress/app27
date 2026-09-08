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
                                <br>
                                <label class="my-1 mr-2" for="txtfechai">Inicio:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai"> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Hasta:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf"> &nbsp;
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;

                                <button type="submit" id="btnbuscar" class="btn btn-primary my-1">Consultar</button>
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
<style>
    div.dataTables_info {
        color: black !important;
    }
</style>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
        $("#cmbAlmacen").attr("disabled", true);
        $("#cmbAlmacen").val("<?php echo $_SESSION['idalmacen'] ?>");
        tipousuario = "<?php echo $_SESSION['tipousuario'] ?>";
        if (tipousuario == 'A') {
            $("#cmbAlmacen").removeAttr("disabled");
        }
        $(".tipodocumentos option[value='GI']").remove();
    }

    function search() {
        $("#btnbuscar").attr('disabled', true);
        axios.get('/lecturas/buscarhistorial', {
            "params": {
                "dfechai": $("#txtfechai").val(),
                "dfechaf": $("#txtfechaf").val(),
                "cmbalmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $("#btnbuscar").attr('disabled', false);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
            $("#btnbuscar").attr('disabled', false);
        });
    }
</script>
<?php
$this->endSection('javascript');
?>