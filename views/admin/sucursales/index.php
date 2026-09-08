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
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary">Consultar</button>
                                        <button type="button" onclick="modalCrear();" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Nuevo</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        focustabla('#tabla_sucursales')
    });

    function buscar() {
        axios.get('/sucursales/buscar', {
            "params": {
                "cbuscar": ''
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            console.log(error);
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema');
        });
    }

    function modalCrear() {
        axios.get('/sucursales/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $("#modal-mantenimiento").modal('show')
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear', 'Mensaje del sistema')
            })
    }

    function modalEdit(id) {
        const ruta = '/sucursales/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar', 'Mensaje del sistema')
            })
    }

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    });

    //ES PARA CERRAR EL MODAL DE REGISTRO
    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    function store(modo, id) {
        let txtidsucu = document.querySelector("#txtidsucu").value;
        let txtnombre = document.querySelector("#txtnombre").value;
        let txtdireccion = document.getElementById("txtdireccion").value
        let txtciudad = document.getElementById("txtciudad").value
        let cmbUbigeo = document.getElementById("cmbUbigeo").value;
        if (txtciudad.length == 0) {
            toastr.info('Ingrese la ciudad', 'Mensaje del Sistema');
            return;
        }
        if (txtnombre.length == 0) {
            toastr.info('Ingrese el nombre', 'Mensaje del Sistema');
            return;
        }
        if (txtdireccion.length == 0) {
            toastr.info('Ingrese la dirección', 'Mensaje del Sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Sucursal?',
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/sucursales/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            buscar();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    const respuesta_servidor = error.response.data;
                                    const errores = respuesta_servidor.errors;
                                    mostrarErrores('formulario-crear', errores);
                                }
                            }
                            toastr.error('Error al registrar', 'Mensaje del sistema');
                        })
                }
            })
        } else {
            const ruta = '/sucursales/update/' + id;
            Swal.fire({
                title: '¿Actualizar Sucursal?',
                text: "Se modificará con los nuevos datos",
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
                            toastr.success('Actualizado correctamente', 'Mensaje del Sistema');
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    mostrarErrores('formulario-crear', error.response.data.errors);
                                }
                            }
                            toastr.error('Hubo error al actualizar', 'Mensaje del sistema');
                        });
                }
            })
        }
    }
</script>
<?php
$this->endSection('javascript');
?>