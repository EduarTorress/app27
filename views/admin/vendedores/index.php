<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper" id="container">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="" id="form-search">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optradios" value="nombre" checked>&nbsp;Nombre&nbsp;
                                                </label>
                                            </div>
                                            <div class="col-8" style="display:inline-block;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese el nombre del vendedor" onkeyup="mayusculas(this)">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary" id="btnbuscar">Buscar</button>
                                                    </span>
                                                    <button onclick="modalCrear();" type="button" class="btn btn-success">Nuevo</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-12" id="search">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-mantenimiento" role="dialog" data-keyboard="true" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-mantenimiento-contenido">
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
        buscar();
    });

    window.onload = function() {
        document.getElementById("txtbuscar").focus();
        titulo("<?php echo $titulo ?>");
    }

    function buscar() {
        var abuscar = document.querySelector('#txtbuscar').value;
        if (abuscar.length == 0) {
            toastr.error("Ingrese la búsqueda", 'Mensaje del Sistema');
            return;
        }
        $("#btnbuscar").attr('disabled', true);
        axios.get('/vendedores/lista', {
            "params": {
                "cbuscar": abuscar
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $("#btnbuscar").attr('disabled', false);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            $("#btnbuscar").attr('disabled', false);
        });
    }

    function modalCrear() {
        axios.get('/vendedores/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear' + error, 'Mensaje del sistema')
            })
    }

    function modalEdit(id) {
        const ruta = '/vendedores/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar' + error, 'Mensaje del sistema')
            });
    }

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    })

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    // function darBaja(id) {
    //     Swal.fire({
    //         icon: 'error',
    //         title: '¿Estás seguro de eliminar?',
    //         text: 'Esta acción no se puede revertir',
    //         showCancelButton: true,
    //         confirmButtonText: 'Si, estoy seguro',
    //         cancelButtonText: 'No, cancelar'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             const ruta = '/venndedores/darBaja/' + id;
    //             axios.post(ruta).then(function(respuesta) {
    //                 console.log(respuesta.data);
    //                 toastr.success('Eliminado correctamente');
    //                 buscar();
    //             }).catch(function(error) {
    //                 if (error.hasOwnProperty('response')) {
    //                     toastr.error(error.response.data.message);
    //                 }
    //             })
    //         }
    //     })
    // }

    function store(modo, id) {
        let cnombre = document.querySelector("#txtNombre").value;
        if (cnombre.length == 0) {
            toastr.error('Ingrese un nombre del vendedor', 'Mensaje del Sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Vendedor?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/vendedores/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            buscar();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    toastr.error('Hubo error al registrar' + error, 'Mensaje del sistema');
                                }
                            }
                        });
                }
            })
        } else {
            const ruta = '/vendedores/update/' + id;
            Swal.fire({
                title: '¿Actualizar Vendedor?',
                text: "Se registrará con los nuevos datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'NO'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(ruta, data)
                        .then(function() {
                            $('#modal-mantenimiento').modal('hide');
                            buscar();
                            toastr.success('Actualizado satisfactoriamente', 'Mensaje del Sistema');
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    toastr.error('Hubo error al actualizar' + error, 'Mensaje del sistema');
                                }
                            }
                        });
                }
            });
        }
    }
</script>
<?php
$this->endSection('javascript');
?>