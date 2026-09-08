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
                                                    <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese el nombre del usuario" onkeyup="mayusculas(this)">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                                    </span>
                                                    <button data-bs-toggle="modal" data-bs-target="#modalConfirmarLogin" type="button" class="btn btn-success">Nuevo</button>
                                                </div>
                                            </div>
                                        </div>
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
<input type="hidden" id="idusua" value="0">
<?php
$cl = new \App\View\Components\ModalConfirmarLoginComponent();
echo $cl->render();
?>
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

    $(document).ready(function() {
        focustabla('#tabla_usuarios')
    });

    function buscar() {
        var abuscar = document.querySelector('#txtbuscar').value;
        if (abuscar.length == 0) {
            toastr.error("Ingrese un parametro a buscar", 'Mensaje del Sistema');
            return;
        }
        axios.get('/usuarios/buscar', {
            "params": {
                "cbuscar": abuscar
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del Sistema')
        });
    }

    function modalCrear() {
        axios.get('/usuarios/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $("#modal-mantenimiento").modal('show')
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear' + error, 'Mensaje del Sistema')
            })
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/usuarios/verificar", data)
            .then(function(respuesta) {
                $("#modalConfirmarLogin").modal("hide");
                idusua = $("#idusua").val();
                // console.log(idusua)
                if (idusua == '0') {
                    modalCrear()
                } else {
                    modalEdit(idusua)
                }
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message, 'Mensaje del sistema');
                    }
                }
            });
        $("#search").click();
    }

    function abrirmodalactualizar(id) {
        $("#modalConfirmarLogin").modal("show");
        $("#idusua").val(id)
    }

    function modalEdit(id) {
        const ruta = '/usuarios/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar' + error, 'Mensaje del sistema');
            })
    }

    $('#modal-mantenimiento').on('hidden.bs.modal', function() {
        $('#txtidusua').val("");
        $('#txtnombre').val("");
        $('#cmbtipousuario').val("Administrador");
    });

    $('#modalConfirmarLogin').on('hidden.bs.modal', function() {
        $("#txtUsuario").val("")
        $("#txtPassword").val("")
        $('#idusua').val(0);
    });

    $('#modal-mantenimiento').on('shown.bs.modal', function() {
        $('#txtnombre').focus();
    });

    //ES PARA CERRAR EL MODAL DE REGISTRO
    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    //ES PARA CERRAR EL MODAL DE LOGIN
    function cerrarModal() {
        $('#modalConfirmarLogin').modal('hide');
        $('#txtbuscar').focus();
    }

    function store(modo, id) {
        let txtidusua = document.querySelector("#txtidusua").value;
        let txtnombre = document.querySelector("#txtnombre").value;
        let txtclave = document.getElementById("txtclave").value
        let cmbtipousuario = document.getElementById("cmbtipousuario").value;
        if (txtnombre.length == 0) {
            toastr.info('Ingrese un nombre del usuario', 'Mensaje del Sistema');
            return;
        }
        if (txtclave.length == 0) {
            toastr.info('Ingrese la clave del usuario', 'Mensaje del Sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Usuario?',
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/usuarios/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            buscar();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    toastr.error('Error al registrar' + error, 'Mensaje del sistema');
                                }
                            }
                        })
                }
            })
        } else {
            const ruta = '/usuarios/update/' + id;
            Swal.fire({
                title: '¿Actualizar Usuario?',
                text: "Se Registrara con los nuevos datos",
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
                                toastr.error(error.response.data.message, 'Mensaje del sistema');
                            }
                        });
                }
            })
        }
    }
</script>
<?php
$this->endSection('javascript');
?>