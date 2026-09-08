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
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <button class="btn btn-primary my-1">Consultar</button>
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
<div id="modaldetalle" class="modal fade " tabindex="-1" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblmodaldetalle">Detalle del comprobante</h5>
                <input type="text" style="display:none" id="txtidauto">
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="tbldetalle">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Unidad</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Peso</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="float-right">
                    <div class="input-group mb-3">
                        <span class="input-group-text form-control-sm" id=""><b>Total:</b> </span>
                        <input type="text" id="txtimportemodal" class=" form-control" value="0.00" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnEliminar" class="btn btn-danger" onclick="cerrarModal();" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnrecibir" class="btn btn-success" onclick="recibirtraspaso();">Recibir Traspaso</button>
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
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
    }

    function search() {
        axios.get('/traspasos/listarxrecibir', {
            "params": {
                "cmbalmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function consultardetalle(idauto) {
        axios.get('/traspasos/verdetalletraspaso', {
            "params": {
                "idauto": idauto
            }
        }).then(function(respuesta) {
            detalle = respuesta.data.listado;
            $("#txtidauto").val(idauto);
            $("#tbldetalle tbody").empty();
            var subtotal = 0;
            var total = 0;
            detalle.forEach(function(d) {
                $("#lblmodaldetalle").text("Detalle: ");
                subtotal = Number(d.cant) * Number(d.prec);
                var tr = `<tr> 
                        <td class="text-sm">` + d.descri + `</td>
                         <td>` + d.unid + `</td>
                        <td>` + d.cant + `</td>
                        <td>` + d.prec + `</td>
                        <td>` + subtotal.toFixed(2) + `</td>
                        </tr>`;
                total = total + subtotal;
                $('#tbldetalle tbody').append(tr);
            });
            $("#txtimportemodal").val("" + total.toFixed(3))
            $("#modaldetalle").modal('show');
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function recibirtraspaso() {
        $("#btnrecibir").attr("disabled", "disabled");
        axios.get('/traspasos/aceptartraspaso', {
            "params": {
                "idauto": $("#txtidauto").val()
            }
        }).then(function(respuesta) {
            $("#btnrecibir").removeAttr("disabled");
            console.log(respuesta);
            est = respuesta.data.estado;
            if (est == '1') {
                $("#modaldetalle").modal('hide');
                search();
                toastr.success("El traspaso fue aceptado satisfactoriamente", 'Mensaje del Sistema')
            }
        }).catch(function(error) {
            $("#btnrecibir").removeAttr("disabled");
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>