<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div id="loading" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title-center">Procesando Solicitud</h5>
            </div>
            <div class="modal-body">
                <div id="contenedor">
                    <div class="loader" id="loader">Consultando...</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="agregarproducto" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title-center">Agregar Detalle </h5>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label class="col-sm-0 col-form-label col-form-label-sm">Producto:</label>
                    <div class="col-sm-0">
                        <input type="text" disabled style="width: 150px;" class="form-control form-control-sm" id="txtdescripcion">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-0 col-form-label col-form-label-sm">Unidad:</label>
                    <div class="col-sm-0">
                        <input type="text" disabled style="width: 100px;" class="form-control form-control-sm" id="txtunidad">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-0 col-form-label col-form-label-sm">Stock:</label>
                    <div>
                        <input type="number" disabled style="width: 100px;" class="form-control form-control-sm" id="txtstock">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-0 col-form-label col-form-label-sm">Precio</label>
                    <div>
                        <input type="number" disabled style="width: 100px;" class="form-control form-control-sm" id="txtprecios" placeholder="Ingrese Precio" value="0.00">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-0 col-form-label col-form-label-sm">Cantidad :</label>
                    <div>
                        <input type="number" style="width: 100px;" class="form-control form-control-sm" id="txtcantidad" placeholder="Ingresecantidad" value="0.00">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarventana()">Close</button>
                <button type="button" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="m-0"><?php echo $titulo ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="" id="form-search">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-8 col-sm-4"> &nbsp; &nbsp;
                                                <label class="radio-inline">
                                                    <input type="radio" name="optradios" value="nombre" onchange="obtener()" checked>Nombre&nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="optradios" value="codigo" onchange="obtener()">Código&nbsp;
                                                </label>
                                            </div>
                                            <div class="col-8 col-sm-6" style="display:inline-block;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="txtbuscar" id="txtbuscar" placeholder="Ingrese nombre o código de Producto" onkeyup="mayusculas(this)" aria-label="Buscar">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-outline-secondary" onclick="buscar()">Buscar</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12" id="search">
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
        buscar();
    });


    function obtener() {
        let vdvto = 0;
        if (document.getElementsByName('optradios')[0].checked) {
            vdvto = 'nombre';
        } else {
            vdvto = 'codigo';
        }
        return vdvto;
    }

    function buscar() {
        var abuscar = document.getElementById("txtbuscar").value;
        if (abuscar.length = 0) {
            return;
        }
        var noption = obtener();
        $('#loading').modal('show');
        axios.get('/productos/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            $('#loading').modal('hide');
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $('#loading').modal('hide');
            toastr.error('Error al cargar el listado')
        });
    }

    function agregar_producto(datos) {
        // console.log("hola");
        // console.log(datos);
        $('#agregarproducto').modal('show');
    }

    function cerrarventana() {
        $('#agregarproducto').modal('hide');
    }
</script>
<?php
$this->endSection('javascript');
?>