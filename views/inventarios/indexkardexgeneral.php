<?php

use App\View\Components\Modaload;
use App\View\Components\ModalProductoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$omodalproductos = new ModalProductoComponent();
echo $omodalproductos->render();
$omodal = new Modaload();
echo $omodal->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <input type="hidden" id="txtcodigo" name="txtcodigo">
                                <input type="hidden" id="txtdescripcion" name="txtdescripcion">
                                <input type="hidden" id="txtunidad" name="txtunidad">
                                <!-- <button type="button" id="btnproductos" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_productos">Productos</button> -->
                                <input type="hidden" class="control control-sm" id="lblProducto" disabled>
                                <?php
                                $empresa = new \App\View\Components\EmpresaComponent("");
                                echo $empresa->render();
                                ?>
                                <div id="fechas">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 card border-success" id="search">
            </div>
            <div class="card border-primary mb-3" id="divkardexxproducto">
                <div class="card-header" id="lbltitulokardexxproducto">Kardex x Producto: </div>
                <div class="card-body text-primary">
                    <div id="progressKardex" class="progress mb-3" style="display:none; height:5px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            style="width:100%">
                        </div>
                    </div>
                    <div class="col-12" id="searchproducto">
                    </div>
                </div>
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
        searchkardex();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        obtenerFechas();
        $("#cmbAlmacen").removeAttr("disabled");
    });

    function searchkardex() {
        ntienda = $("#cmbAlmacen").val()
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        if (ntienda == '') {
            toastr.warning('Seleccione Tienda', 'Mensaje del Sistema');
            return;
        }
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listarkardexgeneral', {
            "params": {
                "dfi": dfechai,
                "dff": dfechaf,
                "ntienda": ntienda
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
            $("#searchproducto").html("")
            $("#lbltitulokardexxproducto").html("Kardex x Producto: ");
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Ocurrió un error", 'Mensaje del sistema')
        });
    }

    function searchkardexxproducto(ncoda) {
        ntienda = $("#cmbAlmacen").val()
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        if (ncoda == '') {
            toastr.warning('Seleccione un Producto', 'Mensaje del Sistema');
            return;
        }
        $("#searchproducto").html("");
        $("#progressKardex").show();
        axios.get('/inventarios/listarkardex', {
            "params": {
                "ncoda": ncoda,
                "ntienda": ntienda,
                "dfi": dfechai,
                "dff": dfechaf
            }
        }).then(function(respuesta) {
            $("#searchproducto").html(respuesta.data);
            $("#divkardexxproducto #btnExportexcel").remove();
            $("#divkardexxproducto #btnExportpdf").remove();
            $("#progressKardex").hide();
        }).catch(function(error) {
            $("#progressKardex").hide();
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Ocurrió un error", 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>