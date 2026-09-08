<!-- Modal Destinatario -->
<div class="modal fade" id="modal_destinatario" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h7 class="modal-title" id="">Destinatario</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="destinatario">
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
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscar" onkeypress="pulsarenterbuscardestinatarios(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Destinatario a buscar">
                    <br>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" id="cmdbuscar" onclick="consultarDestinatarios()" type="button">Buscar</button>
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
    function seleccionarDestinatario(id, nom, direcciond, ubigd, rucd) {
        document.getElementById('txtNombreDestinatario').value = nom;
        document.getElementById("txtIdDestinatario").value = id;
        document.getElementById("txtDireccionDestinatario").value = direcciond;
        document.getElementById("txtUbigeoDestinatario").value = ubigd;
        document.getElementById("txtrucDestinatario").value = rucd
        // console.log(direcciond)
        idDestinatario = id;
        axios.get('/destinatario/seleccionar', {
            "params": {
                'idDestinatario': idDestinatario,
                'nombre': nom,
                'destinatarioDireccion': direcciond,
                'ubigDestinatario': ubigd,
                'rudDestinatario': rucd
            }
        }).then(function(respuesta) {
            $('#modal_destinatario').modal('hide');
        }).catch(function(error) {
            $('#modal_destinatario').modal('hide');
            toastr.error(error, 'Mensaje del sistema');
        });
    }
</script>