<div class="modal fade" id="modal_pedidos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="exampleModalLabel">Canje de Cotizaciones (Pre-Ventas)</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pedidos">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchpedidos">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function buscarpedidos() {
        axios.get('/pedidos/listarpedidosparacanje').then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchpedidos').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function seleccionarpedido(datos) {
        document.getElementById("txtcliente").value = datos.razo;
        document.getElementById("txtidcliente").value = datos.idclie;
        // document.getElementById("txtfecha").value = datos.fech;
        // document.getElementById("txtfechavto").value = datos.fech;
        document.getElementById("txtruccliente").value = datos.nruc;
        document.getElementById("txtdnicliente").value = datos.ndni;
        document.getElementById("txtdireccion").value = datos.dire;
        $("#idusua").val(datos.idusua);
        $("#usuario").val(datos.usuario);
        $("#cmbdcto").val(datos.tdoc);
        $("#cmbforma").val(datos.form);
        $("#cmbvendedor").val(datos.idven);
        $("#txtidautop").val(datos.idautop);
        $("#cmbmoneda").attr('disabled', true);
        $("#cmbmoneda").val(datos.mone);
        $("#titulo").text("Canjear Pedido " + datos.ndoc);
        axios.get('/pedidos/listardetallepedidoxid', {
            "params": {
                "idautop": datos.idautop
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            calcularIGV()
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
        $("#modal_pedidos").modal('hide');
        $(".codigo").css("display", "none");
        $(".tipoproducto").css("display", "none");
    }
</script>