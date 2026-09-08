<div class="modal fade" id="modal_proveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h5 class="modal-title" id="">Proveedores</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="proveedor">
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="nombre" onchange="obtenertipobusquedaproveedor()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="ruc" onchange="obtenertipobusquedaproveedor()">&nbsp;RUC&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="dni" onchange="obtenertipobusquedaproveedor()">&nbsp;DNI&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="codigo" onchange="obtenertipobusquedaproveedor()">&nbsp;Código&nbsp;
                </label>
                <button style="float: right; position: relative; top: -5px;" class="btn btn-success"><a role="button" href="/proveedor/index" style="color:white;">Nuevo</a></button>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarprov" onkeypress="pulsarenterbuscarproveedor(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Ingrese parametro de Proveedor a Buscar" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscar" onclick="buscarProveedor()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="searchprov">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#modal_proveedor').on('shown.bs.modal', function() {
        $('#txtbuscarprov').focus();
    });

    function obtenertipobusquedaproveedor() {
        let vdvto = 0;
        if (document.getElementsByName("optradios")[0].checked) {
            $("#txtbuscarprov").focus();
            vdvto = 0;
        }
        if (document.getElementsByName("optradios")[1].checked) {
            $("#txtbuscarprov").focus();
            vdvto = 1;
        }
        if (document.getElementsByName("optradios")[2].checked) {
            $("#txtbuscarprov").focus();
            vdvto = 2;
        }
        if (document.getElementsByName("optradios")[3].checked) {
            $("#txtbuscarprov").focus();
            vdvto = 3;
        }
        return vdvto;
    }

    function buscarProveedor() {
        var abuscar = document.querySelector('#txtbuscarprov').value;
        var noption = obtenertipobusquedaproveedor();
        var cmodo = 'S';
        axios.get('/proveedor/buscar', {
            "params": {
                "cbuscar": abuscar,
                "option": noption,
                "modo": cmodo
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchprov').html(contenido_tabla);
            var nombre = "N",
                ciudad = "-",
                direccion = "-",
                ubigeo = "-";
            const tblprov = $("#iniciar").val();
            if ((tblprov == null) && (noption == '1' || noption == '2')) {
                switch (noption) {
                    case 1:
                        if (abuscar.length == 11) {
                            axios.get('/empresa/importarucydni', {
                                "params": {
                                    "ruc": abuscar
                                }
                            }).then(function(respuesta) {
                                nombre = respuesta.data.nombre_o_razon_social;
                                if (abuscar.substring(0, 1) == '2') {
                                    direccion = respuesta.data.direccion;
                                    ciudad = respuesta.data.distrito.trimEnd() + ' ' + respuesta.data.provincia.trimEnd() + ' ' + respuesta.data.departamento.trimEnd();
                                    ubigeo = respuesta.data.ubigeo.trimEnd();
                                }
                                if (nombre !== undefined) {
                                    Swal.fire({
                                        title: "Proveedor no registrado en el sistema, presione sí para registrarlo",
                                        text: nombre,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Si, deseo registrarlo.',
                                        cancelButtonText: 'No, volver atras.'
                                    }).then(function(respuesta) {
                                        if (respuesta.isConfirmed) {
                                            const data = new FormData();
                                            data.append("txtRUC", abuscar);
                                            data.append("txtDNI", "");
                                            data.append("txtNombre", nombre);
                                            data.append("txtDireccion", direccion);
                                            data.append("txtCiudad", ciudad);
                                            data.append("cmbUbigeo", ubigeo);
                                            axios.post('/proveedor/store', data)
                                                .then(function(respuesta) {
                                                    axios.get("/proveedor/buscar", {
                                                        params: {
                                                            cbuscar: abuscar,
                                                            option: noption,
                                                            modo: cmodo,
                                                        }
                                                    }).then(function(rp) {
                                                        const contenido_tabla = rp.data;
                                                        $("#searchprov").html(contenido_tabla);
                                                        $("#cmdbuscar").attr('disabled', true);
                                                        btnagregar = $("#iniciar").find("button");
                                                        $(btnagregar).click();
                                                    });
                                                }).catch(function(error) {
                                                    toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                                    // if (error.hasOwnProperty('response')) {
                                                    //     if (error.response.status === 422) {
                                                    //         const respuesta_servidor = error.response.data;
                                                    //         const errores = respuesta_servidor.errors;
                                                    //         mostrarErrores('formulario-crear', errores);
                                                    //     }
                                                    // }
                                                })
                                        }
                                    });
                                }
                            }).catch(function(error) {
                                toastr.error(error.response.data.message, 'Mensaje del Sistema')
                            });
                        }
                        break;
                    case 2:
                        if (abuscar.length == 8) {
                            axios.get('/empresa/importarucydni', {
                                "params": {
                                    "ruc": abuscar
                                }
                            }).then(function(respuesta) {
                                nombre = respuesta.data.nombre;
                                if (nombre !== undefined) {
                                    Swal.fire({
                                        title: "Proveedor no registrado en el sistema, presione sí para registrarlo",
                                        text: nombre,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Si, deseo registrarlo.',
                                        cancelButtonText: 'No, volver atras.'
                                    }).then(function(respuesta) {
                                        if (respuesta.isConfirmed) {
                                            if (respuesta.isConfirmed) {
                                                const data = new FormData();
                                                data.append("txtRUC", "");
                                                data.append("txtDNI", abuscar);
                                                data.append("txtNombre", nombre);
                                                data.append("txtDireccion", direccion);
                                                data.append("txtCiudad", ciudad);
                                                data.append("cmbUbigeo", ubigeo);
                                                axios.post('/proveedor/store', data)
                                                    .then(function(respuesta) {
                                                        axios.get("/proveedor/buscar", {
                                                            params: {
                                                                cbuscar: abuscar,
                                                                option: noption,
                                                                modo: cmodo,
                                                            }
                                                        }).then(function(rp) {
                                                            const contenido_tabla = rp.data;
                                                            $("#searchprov").html(contenido_tabla);
                                                            $("#cmdbuscar").attr('disabled', true);
                                                            btnagregar = $("#iniciar").find("button");
                                                            $(btnagregar).click();
                                                        });
                                                    }).catch(function(error) {
                                                        toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                                        // if (error.hasOwnProperty('response')) {
                                                        //     if (error.response.status === 422) {
                                                        //         const respuesta_servidor = error.response.data;
                                                        //         const errores = respuesta_servidor.errors;
                                                        //         mostrarErrores('formulario-crear', errores);
                                                        //     }
                                                        // }
                                                    })
                                            }
                                        }
                                    });
                                }
                            }).catch(function(error) {
                                // toastr.error(error, 'Mensaje del Sistema')
                                toastr.error(error.response.data.message, 'Mensaje del Sistema')
                            });
                        }
                        break;
                    default:
                        console.log("Tipo de busqueda invalido");
                }
            }
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function seleccionarproveedor(datos) {
        document.getElementById("txtidproveedor").value = datos.parametro1;
        document.getElementById("txtproveedor").value = datos.parametro2;
        document.getElementById('txtrucproveedor').value = datos.parametro3;
        document.getElementById('txtptopartida').value = datos.parametro5 + ' ' + datos.parametro6;
        document.getElementById('txtUbigeoproveedor').value = datos.parametro9;
        // document.getElementById('cmbUbigeo').value = datos.parametro9;
        // console.log(datos.parametro9);
        axios.get('/proveedor/seleccionar', {
            "params": {
                'datos': datos
            }
        }).then(function(respuesta) {
            $('#modal_proveedor').modal('toggle');
        }).catch(function(error) {
            $('#modal_proveedor').modal('toggle');
            toastr.error(error, 'Mensaje del Sistema');
        });
    }
</script>