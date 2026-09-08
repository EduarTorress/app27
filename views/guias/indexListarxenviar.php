<?php
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

    function enviarsunatguiatr(nidauto, nombrexml) {
        axios.get('/guias/enviarsunatguiatr/', {
            "params": {
                "nidauto": nidauto,
                "nombrexml": nombrexml
            }
        }).then(function(respuesta) {
            console.log(respuesta.data.rpta)
            if (respuesta.status == '200') {
                // console.log(respuesta.data.rpta);
                toastr.success(respuesta.data.rpta);
                search();
                // if (respuesta.data.rpta.substring(0, 1) == '0' || respuesta.data.rpta.substring(0, 2) == '99') {
                //     res = respuesta.data.rpta.substring(0, 2);
                //     if (res == '99') {
                //         axios.get('/guias/actualizarEstadoGuiaTr', {
                //                 "params": {
                //                     "nidauto": nidauto
                //                 }
                //             }).then(function(respuesta) {
                //                 toastr.success(res.replace(/\d+/g, ''));
                //                 search();
                //             })
                //             .catch(function(error) {
                //                 // 400, 500
                //                 toastr.error('Error al realizar el proceso')
                //             });
                //     }
                // } else {
                //     toastr.info("!!!!" + respuesta.data.rpta);
                // }
            } else {
                toastr.warning(respuesta.data, 'Mensaje del Sistema');
                // console.log(respuesta);
            }
        }).catch(function(error) {
            toastr.error("Error al Enviar Guia a SUNAT", 'Mensaje del sistema');
            console.log(error);
        });
    }

    function enviarsunatguiar(nidauto, motivo, ruc) {
        axios.get('/guias/enviarsunatguiar/', {
            "params": {
                "empresa": "",
                "nidauto": nidauto,
                "motivo": motivo,
                "ruc": ruc
            }
        }).then(function(respuesta) {
            // console.log(respuesta.data.rpta);
            if (respuesta.data.hasOwnProperty('rpta')) {
                toastr.success(respuesta.data.rpta, 'Mensaje del Sistema');
                search();
                // if (respuesta.data.rpta.substring(0, 1) == '0' || respuesta.data.rpta.substring(0, 2) == '99') {
                //     res = respuesta.data.rpta.substring(0, 2);
                //     console.log(res)
                //     if (res == '99') {
                //         axios.get('/guiasr/actualizarEstadoGuiaR', {
                //                 "params": {
                //                     "nidauto": nidauto
                //                 }
                //             }).then(function(respuesta) {
                //                 toastr.success("El comprobante fue informado previamente");
                //                 search();
                //             })
                //             .catch(function(error) {
                //                 // 400, 500
                //                 toastr.error('Error al realizar el proceso')
                //             });
                //     }
                // } else {
                //     toastr.info("!!!!" + respuesta.data.rpta);
                // }
            } else {
                toastr.warning('No se pudo leer la respuesta', 'Mensaje del Sistema');
            }
            search();
        }).catch(function(error) {
            toastr.error("Error al enviar guia a SUNAT", 'Mensaje del sistema');
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
        axios.get('/guias/listarGuiasxenviar', {}).then(function(respuesta) {
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
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    var sleepES5 = function(ms) {
        var esperarHasta = new Date().getTime() + ms;
        while (new Date().getTime() < esperarHasta) continue;
    };

    function enviarTodo() {
        const tabla = document.getElementById("tablaGuias");
        const filas = document.querySelectorAll("#tablaGuias tr.fila")
        for (let i = 0; i < filas.length; i++) {
            const row = filas[i];
            button = row.querySelector('#enviar .btn')
            button.click();
            sleepES5(5000);
        }
    }
</script>
<?php
$this->endSection('javascript');
?>