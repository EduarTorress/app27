<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\EmpresaComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\TipoMonedaComponent;

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
                                &nbsp;&nbsp;
                                <?php
                                $dctos = new DocumentoComponent('');
                                echo $dctos->renderreports('C');
                                ?>
                                &nbsp;&nbsp;
                                <?php
                                $formrepor = new FormadepagoComponent('');
                                echo $formrepor->renderreports();
                                ?>
                                <?php
                                $mon = new TipoMonedaComponent('');
                                echo $mon->renderreports();
                                ?>
                                <button class="btn btn-primary my-1">Consultar</button>
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
        obtenerFechas();
        titulo('<?php echo $titulo; ?>');
        // $(".tipodocumentos option[value='07']").remove();
        $(".divtienda").css("display","none");
        $("#cmbAlmacen").removeAttr("disabled");
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='20']").remove();
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/compras/listado', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbmoneda": $("#cmbmoneda").val(),
                "cmbAlmacen": $("#cmbAlmacen").val(),
                "cmbFormaP": $("#cmbForma").val(),
                "cmbdcto": $("#dctos").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
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