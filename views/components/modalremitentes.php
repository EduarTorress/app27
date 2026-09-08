<!-- Modal Remitente -->
<div class="modal fade" id="modal_remitente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h7 class="modal-title" id="exampleModalLabel">Remitente</h7>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="remitente">
                <label class="radio-inline">
                    <input type="radio" name="optradiosR" value="nombre" onchange="obtenertipobusquedaproveedor()" checked>&nbsp;Nombre&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosR" value="ruc" onchange="obtenertipobusquedaproveedor()">&nbsp;RUC&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosR" value="dni" onchange="obtenertipobusquedaproveedor()">&nbsp;DNI&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosR" value="codigo" onchange="obtenertipobusquedaproveedor()">&nbsp;Código&nbsp;
                </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarProv" onkeypress="pulsarenterbuscarremitentes(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Ingrese Remitente a Buscar" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscar" onclick="buscarRemitente()" type="button">Buscar</button>
                    </div>
                    <div class="col-12" id="searchRem">
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
    function obtenertipobusquedaproveedor() {
        let vdvto = 0;
        if (document.getElementsByName("optradiosR")[0].checked) {
            vdvto = 0;
            document.getElementById("txtbuscarProv").focus();
        }
        if (document.getElementsByName("optradiosR")[1].checked) {
            vdvto = 1;
            document.getElementById("txtbuscarProv").focus();
        }
        if (document.getElementsByName("optradiosR")[2].checked) {
            vdvto = 2;
            document.getElementById("txtbuscarProv").focus();
        }
        if (document.getElementsByName("optradiosR")[3].checked) {
            vdvto = 3;
            document.getElementById("txtbuscarProv").focus();
        }
        return vdvto;
    }

    function buscarRemitente() {
        var abuscar = document.querySelector('#txtbuscarProv').value;
        if (abuscar.length = 0) {
            return;
        }
        var noption = obtenertipobusquedaproveedor();
        var cmodo = 'S';
        axios.get('/remitente/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption,
                "modo": cmodo
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#searchRem').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }
</script>