<?php

use App\View\Components\EmpresaComponent;

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
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
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
<!-- Modal Ver Ajustes -->
<div class="modal fade" id="mddetalleajustes" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="lblndoc"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col"><input type="text" id="txtusuario" class="form-control form-control-sm" disabled></div>
                    <div class="col"><input type="text" id="txtfecha" class="form-control form-control-sm" disabled></div>
                </div>
                <div class="p-2"></div>
                <div class="table-responsive">
                    <table id="tbldetalleajuste" class="table table-bordered table-hover table-sm small">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Ingresada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $item['descri'] ?></td>
                                <td><?php echo $item['cant'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        dfechai = document.getElementById("txtfechai").value;
        dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/inventarios/listaajustes', {
            "params": {
                "txtfechai": dfechai,
                "txtfechaf": dfechaf
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            $("#search").html(respuesta.data);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error("Error al cargar el listado")
        });
    }

    function verdetalle(datos) {
        // console.log(idauto);
        $("#tbldetalleajuste tbody tr").remove();
        $("#mddetalleajustes").modal('show');
        $("#lblndoc").text("Detalle Ajuste Inventario N° " + datos.ndoc);
        $("#txtusuario").val("USUARIO: " + datos.nomb);
        $("#txtfecha").val("FECHA: " + datos.fech);

        axios.get('/inventarios/verdetalleajuste', {
            "params": {
                'idauto': datos.idauto
            }
        }).then(function(respuesta) {
            listado = respuesta.data.listado
            if (listado.length > 0) {
                for (var i = 0; i < listado.length; i++) {
                    var tr = `<tr class="fila">
                        <td>` + listado[i].descri + `</td>
                        <td>` + listado[i].cant + `</td>
                        </tr>`;
                    $('#tbldetalleajuste tbody').append(tr);
                }
            } else {
                $('#tbldetalleajuste tbody').append("<tr><td class='text-center' colspan='9'>No se encontraron resultados</td></tr>");
            }
        }).catch(function(error) {
            console.log(error)
        });
    }
</script>
<?php
$this->endSection("javascript");
?>