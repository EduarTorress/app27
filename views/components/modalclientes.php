<div class="modal fade" id="modal_clientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="">Clientes</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cliente">
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="nombre" onchange="obtenertipobusquedacliente()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="ruc" onchange="obtenertipobusquedacliente()">&nbsp;RUC&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="dni" onchange="obtenertipobusquedacliente()">&nbsp;DNI&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradios" value="codigo" onchange="obtenertipobusquedacliente()">&nbsp;Código&nbsp;
                </label>
                <button style="float: right; position: relative; top: -5px;" class="btn btn-primary"><a role="button" href="/cliente/index" style="color:white;">Nuevo</a></button>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" autocomplete="off" onclick="this.select();" id="txtbuscar" onkeypress="pulsarenterbuscarclientes(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Cliente a buscar">
                    <br>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" id="cmdbuscar" onclick="consultarclientes()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="search">
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
    // var txtbuscar = document.getElementById("txtbuscar");
    // txtbuscar.addEventListener("focus", function(event) {
    // }, true);

    var txtbuscar = document.getElementById("txtbuscar");
    txtbuscar.addEventListener("focus", function(event) {
        $("#cmdbuscar").attr('disabled', false);
    }, true);

    function seleccionarcliente(datos) {
        razon = datos.parametro2
        razon = razon.replace('"', '')
        document.getElementById('txtcliente').value = razon;
        document.getElementById("txtidcliente").value = datos.parametro1;
        document.getElementById("txtruccliente").value = datos.parametro3;
        document.getElementById("txtdireccion").value = datos.parametro5;
        document.getElementById("txtdnicliente").value = datos.parametro4;
        document.getElementById("txtclienteretencion").value = datos.parametro6;
        axios.get('/cliente/seleccionar', {
            "params": {
                'idclie': datos.parametro1,
                'nombre': razon,
                'ruc': datos.parametro3,
                'txtdireccion': datos.parametro5,
                'dni': datos.parametro4,
                'clienteretencion': datos.parametro6
            }
        }).then(function(respuesta) {
            $("#cmdbuscar").attr('disabled', true);
            $("#txtbuscar").blur();
            $("#search").empty();
            $('#modal_clientes').modal('hide');
            $("#cmbdcto").focus();
            $("#cmbdcto").click();
        }).catch(function(error) {
            console.log(error);
            $('#modal_clientes').modal('hide');
            toastr.error(error, 'Mensaje del Sistema');
        });
    }

    function seleccionarTipoBusquedaCliente(tipoDocumento) {
        if (tipoDocumento === '01') {
            $('input[name="optradios"][value="ruc"]').prop('checked', true);
        } else if (tipoDocumento === '03') {
            $('input[name="optradios"][value="dni"]').prop('checked', true);
        } else {
            $('input[name="optradios"][value="nombre"]').prop('checked', true);
        }
        obtenertipobusquedacliente();
        $("#txtbuscar").val("");
        $("#search").empty();
    }

    function consultarclientes() {
        var noption;
        var abuscar = document.querySelector("#txtbuscar").value;
        if (abuscar.length == 0) {
            toastr.info("Ingrese dato a buscar", 'Mensaje del Sistema');
            return;
        }
        if (abuscar.length < 3) {
            toastr.info("Busqueda muy corta", 'Mensaje del Sistema');
            return;
        }
        noption = obtenertipobusquedacliente();
        // console.log(noption);
        var cmodo = "S";
        axios.get("/cliente/buscar", {
            params: {
                cbuscar: abuscar,
                option: noption,
                modo: cmodo
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            // console.log(respuesta.data);
            $("#search").html(contenido_tabla);
            $("#cmdbuscar").attr('disabled', true);
            $("#txtbuscar").blur();
            $("#iniciar").click();
            var nombre = "N",
                ciudad = "-",
                direccion = "-",
                ubigeo = "-";
            const tblcl = $("#iniciar").val();
            if ((tblcl == null) && (noption == '1' || noption == '2')) {
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
                                        title: "Cliente no registrado en el sistema, ¿Desea agregarlo?",
                                        text: nombre,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Sí, deseo registrarlo.',
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
                                            axios.post('/cliente/store', data)
                                                .then(function(respuesta) {
                                                    axios.get("/cliente/buscar", {
                                                        params: {
                                                            cbuscar: abuscar,
                                                            option: noption,
                                                            modo: cmodo
                                                        }
                                                    }).then(function(rp) {
                                                        const contenido_tabla = rp.data;
                                                        $("#search").html(contenido_tabla);
                                                        $("#cmdbuscar").attr('disabled', true);
                                                        btnagregar = $("#iniciar").find("button");
                                                        $(btnagregar).click();
                                                    });
                                                }).catch(function(error) {
                                                    toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                                    // if (error.hasOwnProperty('response')) {
                                                    //     if (error.response.status === 422) {
                                                    //         // const respuesta_servidor = error.response.data;
                                                    //         // const errores = respuesta_servidor.errors;
                                                    //         // mostrarErrores('formulario-crear', errores);
                                                    //     }
                                                    // }
                                                })
                                        }
                                    });
                                }
                            }).catch(function(error) {
                                toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                console.log(error);
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
                                        title: "Cliente no registrado en el sistema, ¿Desea agregarlo?",
                                        text: nombre,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Sí, deseo registrarlo.',
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
                                                axios.post('/cliente/store', data)
                                                    .then(function(respuesta) {
                                                        axios.get("/cliente/buscar", {
                                                            params: {
                                                                cbuscar: abuscar,
                                                                option: noption,
                                                                modo: cmodo
                                                            }
                                                        }).then(function(rp) {
                                                            const contenido_tabla = rp.data;
                                                            $("#search").html(contenido_tabla);
                                                            $("#cmdbuscar").attr('disabled', true);
                                                            btnagregar = $("#iniciar").find("button");
                                                            $(btnagregar).click();
                                                        });
                                                    }).catch(function(error) {
                                                        toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                                        // if (error.hasOwnProperty('response')) {
                                                        //     if (error.response.status === 422) {
                                                        //         // const respuesta_servidor = error.response.data;
                                                        //         // const errores = respuesta_servidor.errors;
                                                        //         // mostrarErrores('formulario-crear', errores);
                                                        //     }
                                                        // }
                                                    })
                                            }
                                        }
                                    });
                                }
                            }).catch(function(error) {
                                toastr.error(error.response.data.message, 'Mensaje del Sistema')
                                console.log(error);
                            });
                        }
                        break;
                    default:
                        console.log("Tipo de busqueda invalido");
                }
            }
        }).catch(function(error) {
            toastr.error("Error al cargar el listado" + error, 'Mensaje del sistema');
        });
    }
</script>