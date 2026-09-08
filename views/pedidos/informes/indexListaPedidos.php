<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <?php
                                $empresa = new \App\View\Components\EmpresaComponent($_SESSION['idalmacen']);
                                echo $empresa->render();
                                ?>
                                <div class="input-group">
                                    <label for="cmbtipopedidos" class="form-control form-control-sm">Tipo</label>
                                    <select name="cmbtipotpedidos" id="cmbtipopedidos" class="form-select form-select-sm form-control form-control-sm">
                                        <option value="0">Todos</option>
                                        <!-- <option value="E">Escritorio</option> -->
                                        <!-- <option value="web">Web</option> -->
                                    </select>
                                </div>
                                <div id="fechas">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="search">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        $('#titulo').html("<?php echo $titulo ?>")
        // $("#cmbAlmacen").attr("disabled", false);
        // document.getElementById('txtfechai').focus();
    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function confirmDelete(id) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/pedidos/eliminar/' + id;
                axios.post(ruta).then(function(respuesta) {
                    // console.log(respuesta.data);
                    toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                    search();
                }).catch(function(error) {
                    if (error.hasOwnProperty('response')) {
                        toastr.error(error.response.data.message, 'Mensaje del Sistema');
                    } else {
                        toastr.error('Error al eliminar', 'Mensaje del Sistema');
                    }
                })
            }
        })
    }

    function imprimirpedido(id, nombrepdf) {
        var params = "nidauto=" + id;
        var xhr = new XMLHttpRequest();
        var cruta = '/pedidos/imprimir/';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf + '.pdf';
                link.click();
            }
        };
        xhr.send();
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        var ctipopedidos = $("#cmbtipopedidos").val();
        axios.get('/pedidos/listartpedidos', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "ctipopedidos": ctipopedidos,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema');
        });
    }
</script>
<?php
$this->endSection('javascript');
?>