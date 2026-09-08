<!-- Modal Transpotista -->
<div class="modal fade" id="modal_transportista" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h7 class="modal-title" id="">Transportistas</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="transportista">
                <label class="radio-inline">
                    <input type="radio" name="optradiosTr" value="nombre" onchange="obtenertipobusquedatranspor()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosTr" value="ruc" onchange="obtenertipobusquedatranspor()">&nbsp;Placa&nbsp;
                </label>
                <button style="float: right; position: relative; top: -5px;" class="btn btn-success" onclick="creartransportista()">Nuevo</button>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarTr" name="txtbuscarTr" onkeypress="pulsarentertransportista(event)" onkeyup="mayusculas(this)" placeholder="Ingrese Transportista a Buscar" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscartra" onclick="buscarTransportista()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="searchTr">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
<script>
    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
        $("#cmdbuscartra").attr('disabled', false);
    });

    var txtbuscar = document.getElementById("txtbuscarTr");
    txtbuscar.addEventListener("click", function(event) {
        $("#cmdbuscartra").attr('disabled', false);
    }, true);

    function obtenertipobusquedatranspor() {
        let vdvto = 0;
        if (document.getElementsByName("optradiosTr")[0].checked) {
            vdvto = 1;
            document.getElementById("txtbuscarTr").focus();
        }
        if (document.getElementsByName("optradiosTr")[1].checked) {
            vdvto = 0;
            document.getElementById("txtbuscarTr").focus();
        }
        return vdvto;
    }

    function buscarTransportista() {
        var abuscar = document.querySelector('#txtbuscarTr').value;
        var noption = obtenertipobusquedatranspor();
        axios.get('/transportista/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchTr').html(contenido_tabla);
            $("#cmdbuscartra").attr('disabled', true);
            // $("#txtbuscarTr").blur();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function seleccionarTransportista(datos) {
        document.getElementById("txtIdTransportista").value = datos.parametro1;
        document.getElementById("txttransportista").value = datos.parametro2;
        document.getElementById("txttruc").value = datos.parametro5;
        document.getElementById('txtplaca').value = datos.parametro4
        document.getElementById('txtmarca').value = datos.parametro9;
        document.getElementById('txtChoferVehiculo').value = datos.parametro3;
        document.getElementById('txtbrevete').value = datos.parametro7;
        document.getElementById('txtregmtc').value = datos.parametro6;
        document.getElementById('txttipot').value = datos.parametro8;
        document.getElementById('txtPlaca1').value = datos.parametro10;
        axios.get('/transportista/seleccionar', {
            "params": {
                'datos': datos
            }
        }).then(function(respuesta) {
            $('#modal_transportista').modal('toggle');
        }).catch(function(error) {
            $('#modal_transportista').modal('toggle');
            toastr.error(error, 'Mensaje del Sistema');
        });
    }

    function creartransportista() {
        axios.get('/transportista/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento-contenido').html(respuesta.data)
                $("#modal-mantenimiento").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal de crear' + error, 'Mensaje del sistema')
            })
    }

    function cerrarmodal() {
        $('#modal-mantenimiento').modal('hide');
    }

    function store(modo, id) {
        modalmantenimiento = document.getElementById("modal-mantenimiento");
        let txtrazon = modalmantenimiento.querySelector("#txtrazon").value;
        let txtruc = modalmantenimiento.querySelector("#txtruc").value;
        let txttransportista = modalmantenimiento.querySelector("#txttransportista").value;
        let txtplaca = modalmantenimiento.querySelector("#txtplaca").value;
        let txtplaca1 = modalmantenimiento.querySelector("#txtplaca1").value;
        let txtbrevete = modalmantenimiento.querySelector("#txtbrevete").value
        let txtmarca = modalmantenimiento.querySelector("#txtmarca").value;

        if (txttransportista.length == 0) {
            toastr.error('Ingrese un nombre del transportista', 'Mensaje del Sistema');
            return;
        }
        if (txtrazon.length == 0) {
            toastr.error('Ingrese un nombre de empresa', 'Mensaje del Sistema');
            return;
        }

        cmbtipotransporte = $("#cmbtipotransporte").val();
        if (cmbtipotransporte == '02') {
            if (txtbrevete.length != 9) {
                toastr.error('Ingrese el brevete del conductor', 'Mensaje del Sistema');
                return;
            }
        }

        const formulario = document.getElementById('formulario-crear');
        const data = new FormData(formulario);
        if (modo == 'N') {
            Swal.fire({
                icon: 'question',
                title: '¿Registrar Transportista?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'SI',
                cancelButtonText: 'NO',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/transportista/store', data)
                        .then(function(respuesta) {
                            $('#modal-mantenimiento').modal('hide');
                            $('#modal-mantenimiento-contenido').html(" ")
                            toastr.success('Registrado correctamente', 'Mensaje del Sistema');
                            $("#txtbuscarTr").val(txtrazon)
                            buscarTransportista();
                        }).catch(function(error) {
                            if (error.hasOwnProperty('response')) {
                                if (error.response.status === 422) {
                                    toastr.error('Error al registrar' + error, 'Mensaje del sistema');
                                }
                            }
                        })
                }
            })
        }
    }
</script>