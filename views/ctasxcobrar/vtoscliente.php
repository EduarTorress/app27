<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection("contenido");
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-9">
                                    <div class="row">
                                        &nbsp; &nbsp; <input type="text" style="width: 400px;" class="form-control" id="txtcliente" name="txtcliente" style="width: 150px;" aria-label="Recipient's username" aria-describedby="basic-addon2" placeholder="Nombre Del Cliente" disabled>
                                        <span class="btn btn-outline-white" role="button" id="btnmodalclientes" data-toggle="modal" data-target="#modal_clientes"><i class="fas fa-user-alt"></i>
                                        </span> &nbsp;
                                        <button class="btn btn-primary" onclick="listarvtos()">Consultar</button>
                                        <!-- Modal Cliente -->
                                        <div class="modal fade" id="modal_clientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content" style="background-color:white;">
                                                    <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                                                        <h4 class="modal-title" id="exampleModalLabel">Buscar Cliente</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label class="radio-inline">
                                                            <input type="radio" name="optradios" value="nombre" onchange="obtenertipobusquedaclienteC()" checked>&nbsp;Nombre&nbsp;
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
                                                            <input type="text" class="form-control" id="txtbuscar" onkeypress="pulsarenterbuscarclientes(event)" name="buscar" onkeyup="mayusculas(this)" placeholder="Ingrese Cliente a Buscar" aria-describedby="basic-addon2">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary" id="cmdbuscar" onclick="consultarclientes()" type="button">Buscar</button>
                                                            </div>
                                                            <div class="col-12" id="search">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" id="resultados">
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
</div>
<style>
    label {
        color: black
    }

    #txtbuscar {
        background-color: white;
        color: black;
    }

    #tabla_clientes {
        color: black;
    }
</style>
<?php
$this->endSection("contenido");
?>
<?php
$this->startSection("javascript");
?>
<script>
    window.onload = function() {
        idcliente = 0
        titulo("<?php echo $titulo ?>");
    }

    $('#modal_clientes').on('shown.bs.modal', function() {
        $('#txtbuscar').focus();
    });

    function seleccionarcliente(id, nom) {
        document.getElementById('txtcliente').value = nom;
        idcliente = id;
        //Cerramos la ventana modal
        $('#modal_clientes').modal('toggle');
        listarvtos()
    }

    function listarvtos() {
        if (idcliente === 0) {
            toastr.warning("Seleccione un Cliente", 'Mensaje del Sistema')
            return;
        }
        axios.get('/ctascobrar/listaxcliente', {
            "params": {
                "idcliente": idcliente
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#resultados').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado de documentos', 'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection("javascript");
?>