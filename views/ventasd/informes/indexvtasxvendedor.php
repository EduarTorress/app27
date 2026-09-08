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
                                <div class="col-sm-2">
                                    <?php
                                    $idven = 0;
                                    $vendedores = new \App\View\Components\VendedorComponent($idven);
                                    echo $vendedores->render();
                                    ?>
                                </div>
                                <?php
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <button type="submit" id="btnconsultar" class="btn btn-primary my-1">Consultar</button>
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
        $(".input-group").addClass('mb-3')
    }

    function search() {
        var select = document.getElementById("cmbvendedor");
        select.addEventListener("change", function() {
            var selectedOption = this.options[select.selectedIndex];
        });
        const sele = select.options[select.selectedIndex];
        const vendedor = sele.value;
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        // $('#loading').modal('show');
        $("#btnconsultar").attr('disabled', true);
        axios.get('/vtas/listavtasxvendedor', {
            "params": {
                "nidv": vendedor,
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            // $('#loading').modal('hide');
            $("#btnconsultar").attr('disabled', false);
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>