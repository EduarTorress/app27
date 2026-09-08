<?php
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
                            <form class="" id="form-search">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="optradios" value="nombre" checked>&nbsp;Nombre&nbsp;
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="optradios" value="codigo">&nbsp;Código&nbsp;
                                                    </label>
                                                </div>
                                                <div class="col-8" style="display:inline-block;">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese dato a buscar" onkeyup="mayusculas(this)" aria-label="Buscar">
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn btn-primary">Buscar</button>
                                                        </span>
                                                        <button onclick="modalCreate()" type="button" class="btn btn-success">Nuevo</button>
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
                        <div class="col-12" id="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-mantenimiento" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
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
        search();
    });

    window.onload = function() {
        document.getElementById("txtbuscar").focus();
        titulo("<?php echo $titulo ?>");
    }

    function search() {
        const texto_busqueda = $('#txtbuscar').val();
        axios.get('/admin/categoria/search', {
            "params": {
                "cbuscar": texto_busqueda
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            //  console.log(respuesta.data.message)
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function modalCreate() {
        axios.get('/admin/categoria/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear' + error, 'Mensaje del sistema')
            })
    }

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
        $('#txtbuscar').focus();
    }

    function store(modo, id) {
        let cnombre = document.querySelector("#txtnombre").value;
        if (cnombre.length == 0) {
            toastr.error('Ingrese un Nombre de Categoria', 'Mensaje del Sistema');
            return;
        }
        const formulario = document.getElementById('formulario-crear');
        var codgrupo = parseInt(document.getElementById("cmbgrupos").value, 10);
        const data = new FormData();
        data.append("txtnombre", cnombre);
        data.append("idgrupo", codgrupo)
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Categoria?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/admin/categoria/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            search();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    const respuesta_servidor = error.response.data;
                                    const errores = respuesta_servidor.errors;
                                    mostrarErrores('formulario-crear', errores);
                                }
                            }
                            toastr.error('Error al registrar' + error, 'Mensaje del sistema');
                        })
                }
            })
        } else {
            const ruta = '/admin/categoria/update/' + id;
            Swal.fire({
                title: '¿Actualizar Categoria?',
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
                            search();
                            toastr.success('Actualizado correctamente', 'Mensaje del Sistema');
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    mostrarErrores('formulario-crear', error.response.data.errors);
                                }
                            }
                            toastr.error('Hubo error al actualizar' + error, ' Mensaje del sistema');
                        });
                }
            })
        }
    }

    function modalEdit(id) {
        const ruta = '/admin/categoria/edit/' + id;
        axios.get(ruta)
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de editar' + error, 'Mensaje del sistema')
            })
    }

    function eliminar(id, e) {
        totalproductos = $(e).parent().parent().find(".totalproductos").html();
        // console.log(totalproductos);
        if (Number(totalproductos) > 0) {
            toastr.error("No se puede dar de baja, ya que hay productos enlazados", 'Mensaje del Sistema');
            return;
        }
        Swal.fire({
            title: 'Eliminar Categoria?',
            text: "Se dará de baja en el sistema",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                const ruta = '/admin/categoria/eliminar/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        toastr.success("Eliminado satisfactoriamente", 'Mensaje del Sistema')
                        search();
                    }).catch(function(error) {
                        toastr.error('Error al eliminar' + error, 'Mensaje del sistema')
                    })
            }
        });
    }
</script>
<?php
$this->endSection('javascript');
?>