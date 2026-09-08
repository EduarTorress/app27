<div class="modal fade" id="modal_productos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#28a745;">
                <h4 class="modal-title" id="lblbuscarproducto">Buscar Producto</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="producto">
                <label class="radio-inline">
                    <input type="radio" name="optradiosP" value="C" id="combustible" onchange="obtenertipobusquedaProducto()" <?php echo (empty($_SESSION['config']['codigobarras']) ? 'checked' : ' ') ?>>&nbsp;Combustible&nbsp;
                </label>
                <label class="radio-inline">
                    <input type="radio" name="optradiosP" value="O" id="otros" onchange="obtenertipobusquedaProducto()" <?php echo (empty($_SESSION['config']['codigobarras']) ? ' ' : 'checked') ?>>&nbsp;Todos&nbsp;
                </label>
                <button style="float: right; position: relative; top: -5px;" class="btn btn-success" id="btncrearproductomodal" onclick="modalCrear();"><a role="button" style="color:white;">Nuevo</a></button>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="txtbuscarProducto" name="buscar" onkeypress="pulsarenterbuscarproductos(event)" onkeyup="mayusculas(this)" placeholder="Producto a Buscar" value="<?php echo session()->get('busquedaPV') ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" id="cmdbuscarP" onclick="buscarProducto()" type="button">Buscar</button>
                    </div>
                </div>
                <div id="searchP">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div id="modal-mantenimiento" data-bs-backdrop="static" data-bs-keyboard="false" class="modal fade" tabindex="-1" data-keyboard="false" aria-hidden="true">
</div>
<script>
    var txtbuscarProducto = document.getElementById("txtbuscarProducto");
    txtbuscarProducto.addEventListener("focus", function(event) {
        $("#cmdbuscarP").attr('disabled', false);
    }, true);

    function obtenertipobusquedaProducto() {
        let vdvto = 0;
        if (document.getElementsByName('optradiosP')[0].checked) {
            vdvto = 'C';
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementsByName('optradiosP')[1].checked) {
            vdvto = 'O';
            moverCursorFinalTexto("txtbuscarProducto");
        }
        return vdvto;
    }

    $(document).ready(function() {
        if (window.location.pathname === "/ventasrapidas/index") {
            $("#btncrearproductomodal").hide();
        }
    });

    function buscarProducto() {
        var abuscar = document.getElementById("txtbuscarProducto").value;
        var noption = obtenertipobusquedaProducto();
        // if (noption == 'nombre') {
        //     if (abuscar.length < 3) {
        //         toastr.error("La busqueda es muy corta, DELIMITAR BUSQUEDA", 'Mensaje del Sistema');
        //         return;
        //     }
        // }
        // $('#loading').modal('show');
        axios.get('/productos/listaModal', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            // $('#loading').modal('hide');
            const contenido_tabla = respuesta.data;
            $('#searchP').html(contenido_tabla);
            $("#cmdbuscarP").attr('disabled', true);
            $("#iniciarp").click();
            $("#txtbuscarProducto").blur();
            // if (document.getElementById('codigo').checked) {
            //     button = $("#iniciarp").find("button");
            //     $(button).click();
            // }
        }).catch(function(error) {
            // $('#loading').modal('hide');
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del Sistema')
        });
    }

    function cargarprecios(domElement, array) {
        var select = document.getElementsByName(domElement)[0];
        const $select = document.querySelector("#cmbprecios");
        for (let i = $select.options.length; i >= 0; i--) {
            $select.remove(i);
        }
        for (value in array) {
            var option = document.createElement("option");
            option.text = array[value];
            select.add(option);
        }
    }

    $('html').keyup(function(e) {
        if (e.keyCode == 46) {
            $("#tabla_productos tbody").empty();
            $("#txtbuscarProducto").select();
        }
    });

    function modalCrear() {
        axios.get('/productos/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal ' + error, 'Mensaje del sistema')
            })
    }

    function cerrarModal() {
        $("#modal-mantenimiento").modal('hide');
    }
</script>