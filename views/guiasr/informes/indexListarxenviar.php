<?php

use App\View\Components\VehiculoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <label class="" for="lblPlaca">Placa</label> &nbsp;
                                <?php
                                $vehi = new VehiculoComponent("");
                                echo $vehi->render();
                                ?>&nbsp;
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
                const ruta = '/guias/eliminar/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        toastr.success('Eliminado correctamente');
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
        axios.get('/guias/enviarsunat/', {
            "params": {
                "nidauto": nidauto,
                "nombrexml": nombrexml
            }
        }).then(function(respuesta) {
            // console.log(respuesta.data.rpta);
            if (respuesta.data.hasOwnProperty('rpta')) {
                if (respuesta.data.rpta.substring(0, 1) == '0' || respuesta.data.rpta.substring(0, 2) == '99') {
                    res = respuesta.data.rpta
                    toastr.success(res.replace(/\d+/g, ''), 'Mensaje del Sistema');
                    search();
                } else {
                    toastr.info("!!!!" + respuesta.data.rpta, 'Mensaje del Sistema');
                }
            } else {
                toastr.warning('No se Pudo leer la respuesta', 'Mensaje del Sistema');
            }
            search();
        }).catch(function(error) {
            toastr.error("Error al Enviar Guia a SUNAT", 'Mensaje del Sistema');
            console.log(error);
        });
    }

    function descargarpdf(id, nombrepdf) {
        var params = "nidauto=" + id;
        var xhr = new XMLHttpRequest();
        var cruta = '/guias/imprimir/';
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
        var dplaca = document.getElementById("cmbvh").value;
        // console.log(dplaca)
        axios.get('/guias/listarGuiasxenviar', {
            "params": {
                "dplaca": dplaca
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