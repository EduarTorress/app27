<?php

use App\View\Components\Modaload;
use App\View\Components\ModalProductoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$omodalproductos = new ModalProductoComponent();
echo $omodalproductos->render();
$omodal = new Modaload();
echo $omodal->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <input type="hidden" id="txtcodigo" name="txtcodigo">
                                <input type="hidden" id="txtdescripcion" name="txtdescripcion">
                                <input type="hidden" id="txtunidad" name="txtunidad">
                                <button type="button" id="btnproductos" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_productos">Productos</button>
                                &nbsp;
                                <input type="text" class="control control-sm" id="lblProducto" disabled>
                                <?php
                                $empresa = new \App\View\Components\EmpresaComponent("");
                                echo $empresa->render();
                                ?>
                                <div id="fechas">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
$this->startSection("javascript")
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        searchkardex();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {        
        $(".divtienda").css("display", "none");
        obtenerFechas();
        $("#cmbAlmacen").removeAttr("disabled");
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const coda = urlParams.get('coda')
        if (!(coda === null)) {
            const fecha = urlParams.get('fecha');
            const alma = urlParams.get('alma');
            const producto = urlParams.get('producto');
            $("#lblProducto").val(producto);
            $("#txtcodigo").val(coda);
            $("#cmbAlmacen").val(alma);
            $("#txtfechai").val('2025-01-01');
            $("#txtfechaf").val(fecha);
            searchkardex();
        }
    });

    $("#modal_productos").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscarProducto");
        $("#txtbuscarProducto").select();
    });

    function searchkardex() {
        ntienda = $("#cmbAlmacen").val()
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        ncoda = document.getElementById("txtcodigo").value;
        if (ntienda == '') {
            toastr.warning('Seleccione Tienda', 'Mensaje del Sistema');
            return;
        }
        if (ncoda == '') {
            toastr.warning('Seleccione un Producto', 'Mensaje del Sistema');
            return;
        }
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listarkardex', {
            "params": {
                "ncoda": ncoda,
                "ntienda": ntienda,
                "dfi": dfechai,
                "dff": dfechaf
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Ocurrió un error", 'Mensaje del sistema')
        });
    }

    function agregarunitemVenta(datos) {
        document.getElementById("txtcodigo").value = datos.parametro2
        document.getElementById("txtdescripcion").value = datos.parametro1
        document.getElementById("txtunidad").value = datos.parametro3
        $("#lblProducto").val(datos.parametro1)
        $('#modal_productos').modal('toggle');
    }
</script>
<?php
$this->endSection("javascript");
?>