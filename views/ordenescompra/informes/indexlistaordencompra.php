<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalProveedorComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prov = new ModalProveedorComponent();
echo $prov->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <div class="input-group ">
                                    <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="TODOS">
                                    <input type="hidden" id="txtidproveedor" value="0">
                                    <input type="hidden" id="txtrucproveedor" value=""><input type="hidden" id="txtptopartida" value=""><input type="hidden" id="txtUbigeoproveedor" value="">
                                    <button class="btn btn-outline-light" type="button" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <label class="my-1 mr-2" for="txtfechai">Inicio</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">
                                <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf">
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
    }

    $("#modal_proveedor").on("shown.bs.modal", function() {
        $("#txtbuscarprov").focus();
    });

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/ordenescompra/listado', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "txtidproveedor": $("#txtidproveedor").val()
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
            toastr.error('Error al cargar el listado', 'Mensaje del sistema')
        });
    }

    function descargarpdf10(nidauto, nombrepdf) {
        var params = "nidauto=" + nidauto;
        var xhr = new XMLHttpRequest();
        var cruta = '/ordenescompra/imprimir';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }
</script>
<?php
$this->endSection('javascript');
?>