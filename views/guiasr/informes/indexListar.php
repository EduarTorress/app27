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
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai">&nbsp;
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
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
        $("#cmbAlmacen").attr("disabled", true);
        $(".divtienda").css("display","none");
    }

    function confirmDelete(id) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/guiasr/eliminar/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                        search();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del Sistema');
                        } else {
                            toastr.error('Error al eliminar', 'Mensaje del Sistema');
                        }
                    })
            }
        })
    }

    function descargarxml(nidauto, nombrexml) {
        axios.get('/guiasr/descargarxml', {
            "params": {
                "nidauto": nidauto,
                "nombrexml": nombrexml
            }
        }).then(function(respuesta) {
            var fileURL = window.URL.createObjectURL(new Blob([respuesta.data]));
            var fileLink = document.createElement('a')
            fileLink.href = fileURL;
            fileLink.setAttribute('download', nombrexml);
            document.body.appendChild(fileLink);
            fileLink.click();
        }).catch(function(error) {
            toastr.error("Error al descargar XML", 'Mensaje del Sistema');
        });
    }

    function descargarpdf(id, nombrepdf, motivo) {
        var params = "nidauto=" + id + "&motivo=" + motivo;
        var xhr = new XMLHttpRequest();
        var cruta = '/guiasr/imprimir/';
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

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        axios.get('/guiasr/indexListarGuias', {
            "params": {
                "dfi": dfechai,
                "dff": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            // $('#loading').modal('hide');
            $('#search').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
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