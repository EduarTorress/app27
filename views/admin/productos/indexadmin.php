<?php

use App\View\Components\ModalGestionStockComponent;
use App\View\Components\Modaload;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prod = new Modaload();
echo $prod->render();
$mdGs = new ModalGestionStockComponent();
echo $mdGs->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divlista" role="tab" aria-selected="true">Lista</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#divvtasxprod" role="tab" aria-selected="false">Ventas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divcompxprod" role="tab" aria-selected="false">Compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#divstocksminimos" role="tab" aria-selected="false">Stock x Minimos</a>
                        </li>
                        <?php if ($_SESSION['tipousuario'] == 'A') : ?>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divcalcularstock" role="tab" aria-selected="false">Calcular Stock</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divlista" role="tabpanel"><br>
                            <div class="col-lg-12">
                                <div class="card card-primary card-outline">
                                    <div class="card-body">
                                        <form class="" id="form-search">
                                            <div class="container">
                                                <div class="col-sm-9">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="C" onchange="obtener()" checked>Combustible&nbsp;
                                                            </label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="O" onchange="obtener()">Todos&nbsp;
                                                            </label>
                                                        </div>
                                                        <!-- <div class="col-sm-2">
                                                            <label class="radio-inline">
                                                                <input type="radio" name="optradios" value="codigo1" onchange="obtener()">Código Fab.&nbsp;
                                                            </label>
                                                        </div> -->
                                                        <div class="col-8" style="display:inline-block;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" style="width:40%;" name="txtbuscar" id="txtbuscar" placeholder="Ingrese nombre o código de Producto" onkeyup="mayusculas(this)" aria-label="Buscar" value="<?php echo session()->get('busqueda') ?>">
                                                                <span class="input-group-btn">
                                                                    <button type="submit" id="buscar" class="btn btn-outline-secondary">Buscar</button>
                                                                </span>&nbsp;
                                                                <?php $opt = session()->get('tiposel', '0');
                                                                if ($opt == 4) : ?>
                                                                    <button type="button" class="btn btn-success" onclick="modalCrear();" style="position:relative;"> Registrar Producto</button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12" id="search">
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divvtasxprod" role="tabpanel"><br>
                            <?php
                            $ca = new \App\View\Components\ComboAnosComponent('V');
                            echo $ca->render();
                            ?>
                            <div class="card">
                                <div class="card-body" id="resultadovtas">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divcompxprod" role="tabpanel"><br>
                            <?php
                            $ca = new \App\View\Components\ComboAnosComponent('C');
                            echo $ca->render();
                            ?>
                            <div class="card">
                                <div class="card-body" id="resultadocompras">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divstocksminimos" role="tabpanel"><br>
                            <button class="btn btn-warning" onclick="consultarstockminimos();">Consultar Stock por Mínimos</button>
                            <br>
                            <div class="card">
                                <div class="card-body" id="resultadosminimos">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="divcalcularstock" role="tabpanel"><br>
                            <button class="btn btn-primary" onclick="calcularstock();">Calcular Stock General</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="txtidart">
<div id="item" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-contenido">
        </div>
    </div>
</div>
<div id="modal-mantenimiento" class="modal fade" tabindex="-1" data-keyboard="false" aria-hidden="true">
</div>
<div id="detallecombo"></div>
<div id="divpresentaciones"></div>
<?php
$this->endSection('contenido');
?>

<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        moverCursorFinalTexto("txtbuscar");
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        $('#tabla_productos').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [{
                targets: 3,
                orderable: false,
                searchable: false
            }]
        });
    });

    function calcularstock() {
        axios.get('/inventarios/calcularstock')
            .then(function(respuesta) {
                rpta = respuesta.data.mensaje.trimEnd();
                Swal.fire({
                    title: "Se ejecutó correctamente",
                    text: rpta,
                    icon: "success"
                });
            }).catch(function(error) {
                toastr.error('Ocurrio un error' + error, 'Mensaje del sistema')
            })
    }

    function consultarstockminimos() {
        axios.get('/productos/consultarstockxminimos', {}).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadosminimos').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        buscar();
    });

    function modalCrear() {
        axios.get('/productos/create')
            .then(function(respuesta) {
                $('#modal-mantenimiento').html(respuesta.data)
                $('#modal-mantenimiento').modal('show');
                $("#txtidart").val("");
            }).catch(function(error) {
                toastr.error('Error al cargar el modal' + error, 'Mensaje del Sistema')
            })
    }

    function buscarProductoxId(producto) {
        datos = new FormData();
        datos.append("idart", producto.parametro2);
        datos.append("idcat", producto.idcat);
        datos.append("idmar", producto.idmarca);
        datos.append("unid", producto.parametro3);
        datos.append("idgrupo", producto.idgrupo);
        datos.append("descri", producto.parametro1);
        datos.append('codigo', producto.prod_cod1);
        datos.append('peso', producto.peso);
        datos.append('idflete', producto.idflete);
        datos.append('prod_smin', producto.prod_smin);
        datos.append('prod_smax', producto.prod_smax);
        datos.append('costocigv', producto.costocigv);
        datos.append('costosigv', producto.costosigv);
        datos.append('flete', producto.flete);
        datos.append("prod_uti1", producto.prod_uti1);
        datos.append("prod_uti2", producto.prod_uti2);
        datos.append("prod_uti3", producto.prod_uti3);
        datos.append("tmon", producto.tmon);
        datos.append("pre1", producto.parametro5);
        datos.append("pre2", producto.parametro6);
        datos.append("pre3", producto.parametro7);
        datos.append("tipop", producto.tipro);
        // datos.append("uldc", producto.uldc);
        // console.log(Object.fromEntries(datos));
        axios.post('/productos/consultarProductoPorID/', datos)
            .then(function(respuesta) {
                $('#modal-mantenimiento').html(respuesta.data);
                $("#modal-mantenimiento").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el modal' + error, 'Mensaje del Sistema');
            });
    }

    function cerrarModal() {
        $("#modal-mantenimiento").modal('hide');
    }

    function buscar() {
        var abuscar = document.getElementById("txtbuscar").value;
        if (abuscar.length == 0) {
            toastr.info("Ingrese nombre de producto a buscar", 'Mensaje del Sistema')
            return;
        }
        var noption = obtener();
        $("#buscar").attr('disabled', true);
        $("#search").stop(true, true).fadeTo(150, 0.3);
        axios.get('/productos/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            $("#search").fadeTo(200, 1);
            $("#buscar").attr('disabled', false);
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $("#search").fadeTo(200, 1);
            $("#buscar").attr('disabled', false);
            // $('#loading').modal('hide');
            toastr.error('Error al cargar el listado' + error, 'Mensaje del Sistema')
        });
    }

    function obtener() {
        let vdvto = 0;
        if (document.getElementsByName('optradios')[0].checked) {
            vdvto = 'C';
        }
        if (document.getElementsByName('optradios')[1].checked) {
            vdvto = 'O';
        }
        return vdvto;
    }

    function obteneridart(idart) {
        $("#txtidart").val(idart);
    }

    function consultarvtasxprod() {
        var txtidart = $("#txtidart").val();
        if (txtidart == '') {
            toastr.error("Haga clic en un producto para consultar", 'Mensaje del Sistema')
            return;
        }
        var cmbano = $("#cmbanov").val();
        axios.get('/productos/consultarvtasxprod', {
            "params": {
                "txtidart": txtidart,
                "cmbano": cmbano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadovtas').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema');
        });
    }

    function consultarcompxprod() {
        var txtidart = $("#txtidart").val();
        if (txtidart == '') {
            toastr.error("Haga clic en un producto para consultar", 'Mensaje del Sistema')
            return;
        }
        var cmbano = $("#cmbanoc").val();
        axios.get('/productos/consultarcompxprod', {
            "params": {
                "txtidart": txtidart,
                "cmbano": cmbano
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultadocompras').html(contenido_tabla);
            $('input[type=search]').css('color', 'black');
            $('.dataTables_filter').css('color', 'black');
            $('.paginate_button').css('background-color', '#006CA7');
            $('.previous').removeClass('disabled');
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function openmodalpresent() {
        axios.get('/productos/listarmodalpres', {}).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#divpresentaciones').html(contenido_tabla);
            $("#modal_presentaciones").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function anularproducto(idart) {
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/productos/darBaja/' + idart;
                axios.post(ruta)
                    .then(function(respuesta) {
                        // console.log(respuesta.data);
                        toastr.success(respuesta.data.message, 'Mensaje del Sistema');
                        buscar();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del Sistema');
                        }
                    })
            }
        })
    }
</script>
<?php
$this->endSection('javascript');
?>