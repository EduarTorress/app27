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
                                <br>
                                <label class="my-1 mr-2" for="">Movimiento:</label>
                                <select name="select" class="form-control form-control-sm" id="cmbmovimiento">
                                    <option value="V">Ventas</option>
                                    <option value="C">Compras</option>
                                </select> &nbsp;
                                <?php
                                $dctos = new DocumentoComponent('');
                                echo $dctos->renderreports();
                                ?>
                                &nbsp;&nbsp;
                                <?php
                                $formrepor = new FormadepagoComponent('');
                                echo $formrepor->renderreports();
                                ?>
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Inicio:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai"> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Hasta:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf"> &nbsp;
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
        $("#cmbAlmacen").removeAttr("disabled");
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/operaciones/listamovimientosdetalladas', {
            "params": {
                "cmbalmacen": $("#cmbAlmacen").val(),
                "cmbFormaP": $("#cmbForma").val(),
                "cmbtdoc": $("#dctos").val(),
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbmovimiento": $("#cmbmovimiento").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>