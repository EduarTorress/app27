<!-- Modal Vehiculo -->
<div class="modal fade" id="modal_vehiculo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h7 class="modal-title" id="lblVehiculo">Vehículo</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="vehiculo">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchVe">
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
    function buscarVehiculo() {
        axios.get('/vehiculo/listar', {}).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            // console.log(contenido_tabla);
            $('#searchVe').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function seleccionarVehiculo(parametros) {
        document.getElementById("txtIdVehiculo").value = parametros.parametro5;
        document.getElementById("txtChoferVehiculo").value = parametros.parametro3;
        document.getElementById("txtplaca").value = parametros.parametro1;
        document.getElementById("txtPlaca").value = parametros.parametro1;
        document.getElementById("txtPlaca1").value = parametros.parametro2;
        document.getElementById("txtBrevete").value = parametros.parametro4
        document.getElementById("txtMarca").value = parametros.parametro7
        idVehiculo = parametros.parametro5;
        axios.get('/vehiculo/seleccionar', {
            "params": {
                'txtIdVehiculo': parametros.parametro5,
                'txtChoferVehiculo': parametros.parametro3,
                'txtPlaca': parametros.parametro1,
                'txtplaca': parametros.parametro1,
                'txtPlaca1': parametros.parametro2,
                'txtBrevete': parametros.parametro4,
                'txtserie': parametros.parametro6
            }
        }).then(function(respuesta) {
            $('#modal_vehiculo').modal('toggle');
        }).catch(function(error) {
            $('#modal_vehiculo').modal('toggle');
            toastr.error(error, 'Mensaje del Sistema');
        });
    }
</script>