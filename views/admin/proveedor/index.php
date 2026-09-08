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
                                            <div class="col-7" style="display:inline-block;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese el nombre" onkeyup="mayusculas(this)">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary">Buscar</button>
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
        // console.log($(".dropdown-toggle").hasAttribute('style'))
    }

    $(document).ready(function() {
        $("#btn-submit").width('140%');
        $("#btn-submit").css('style', 'width:140%');
    });

    function buscar() {
        var abuscar = document.querySelector('#txtbuscar').value;
        if (abuscar.length = 0) {
            toastr.error("Ingrese Proveedor a buscar", 'Mensaje del Sistema');
            return;
        }
        axios.get('/proveedor/lista', {
            "params": {
                "cbuscar": abuscar
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function modalCrear() {
        axios.get('/proveedor/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data);
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear' + error, 'Mensaje del sistema')
            })
    }

    function modalEdit(id) {
        const ruta = '/proveedor/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data);
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar' + error, 'Mensaje del sistema')
            })
    }

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    });

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    function darBaja(id) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/proveedor/darBaja/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        // console.log(respuesta.data);
                        toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                        buscar();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del sistema');
                        }
                    })
            }
        })
    }

    function store(modo, id) {
        let cnombre = document.querySelector("#txtNombre").value;
        let cdireccion = document.getElementById("cmbUbigeo").value
        let cciudad = document.getElementById("txtCiudad").value;
        if (cnombre.length == 0) {
            toastr.error('Ingrese el nombre del Proveedor', 'Mensaje del Sistema');
            return;
        }
        if (document.getElementById("cmbUbigeo").value == 0) {
            toastr.error('Seleccione el Ubigeo del Proveedor', 'Mensaje del Sistema');
            return;
        }
        if (cdireccion.length == 0 || cciudad.length == 0) {
            toastr.error('Ingrese Dirección del Proveedor', 'Mensaje del Sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        // console.log(data);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Proveedor?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/proveedor/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            buscar();
                        }).catch(function(error) {
                            // if (error.hasOwnProperty('response')) {
                            //     if (error.response.status === 422) {
                            //         const respuesta_servidor = error.response.data;
                            //         const errores = respuesta_servidor.errors;
                            //         mostrarErrores('formulario-crear', errores);
                            //     }
                            // }
                            toastr.error(error.response.data.message, 'Mensaje del sistema');
                        })
                }
            })
        } else {
            const ruta = '/proveedor/update/' + id;
            Swal.fire({
                title: '¿Actualizar proveedor?',
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
                            // if (error.hasOwnProperty('response')) {
                            //     if (error.response.status === 422) {
                            //         mostrarErrores('formulario-crear', error.response.data.errors);
                            //     }
                            // }
                            toastr.error(error.response.data.message, 'Mensaje del Sistema');
                        });
                }
            })
        }
    }
</script>
<?php
$this->endSection('javascript');
?>