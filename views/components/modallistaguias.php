<!-- Modal Guias -->
<div class="modal fade" id="modal_guias" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="exampleModalLabel">Guias Remitente P/Canje</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="guias">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchG">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function buscarGuias() {
        axios.get('/guiasr/listarGuiasparacanje').then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchG').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function seleccionarGuia(datos) {
        document.getElementById("txtcliente").value = datos.parametro5;
        document.getElementById("txtidcliente").value = datos.parametro4;
        document.getElementById("txtfecha").value = datos.parametro2;
        document.getElementById("txtfechavto").value = datos.parametro2;
        document.getElementById("txtruccliente").value = datos.parametro9;
        document.getElementById("txtguia").value = datos.parametro12.substr(0, 4) + '-' + datos.parametro12.substr(4);
        document.getElementById("txtdireccion").value = datos.parametro7;
        document.getElementById("txtdnicliente").value = datos.parametro13;
        axios.get('/vtas/listarDetalleCanje', {
            "params": {
                "idguia": datos.parametro1
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            // console.log(contenido_tabla);
            $('#detalle').html(contenido_tabla);
            calcularIGV();
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
        $("#modal_guias").modal('hide');
        $(".codigo").css("display", "none");
    }
</script>