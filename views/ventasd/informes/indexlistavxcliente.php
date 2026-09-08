<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalDetalleDctoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$mcliente = new \App\View\Components\ModalClienteComponent();
echo $mcliente->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                                    <input type="hidden" id="txtidcliente" value="">
                                    <input type="hidden" id="txtruccliente" value="">
                                    <input type="hidden" id="txtdireccion" value="">
                                    <input type="hidden" id="txtdnicliente" value="">
                                    <input type="hidden" id="txtclienteretencion" value="N">
                                    <input type="hidden" id="txtdnicliente" value="">
                                    <button type="button" class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <?php
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai"> Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <button type="submit" id="btnconsultar" class="btn btn-primary my-1">Consultar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="searchvtasxcliente">
            </div>
        </div>
    </div>
</div>
<?php
$md = new ModalDetalleDctoComponent();
echo $md->render();
?>
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
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
        $(".input-group").addClass("mb-3");
    }

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
    });

    function search() {
        idclie = $("#txtidcliente").val();
        if (idclie == 0 || idclie == '') {
            toastr.error("Seleccione el cliente", 'Mensaje del Sistema');
            return;
        }
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        // $('#loading').modal('show');
        $("#btnconsultar").attr('disabled', true);
        axios.get('/vtas/listavtasxcliente', {
            "params": {
                "idclie": idclie,
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            const contenido_tabla = respuesta.data;
            $('#searchvtasxcliente').html(contenido_tabla);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function consultarDetalle(detalle) {
        // console.log(detalle)
        $("#tbldetalle tbody").empty();
        detalle.forEach(function(d) {
            $("#lblmodaldetalle").text("Consultar Detalle: " + d.ndoc);
            var tr = `<tr> 
                    <td>` + d.descri + `</td>
                    <td>` + d.cant + `</td>
                    <td>` + d.prec + `</td>
                    <td>` + d.importe + `</td>
                    </tr>`;
            $('#tbldetalle tbody').append(tr);
            $("#txtimporte").val("S/ " + d.impo)
        });
        $("#modaldetalle").modal('show');
    }

    function cerrarModal() {
        $("#modaldetalle").modal('hide');
    }
</script>
<?php
$this->endSection('javascript');
?>