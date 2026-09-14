<div class="table-responsive">
    <table id="tabla" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Número</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Moneda</th>
                <th>Importe</th>
                <th class="text-center">Opción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['ndoc'] ?></td>
                    <td><?php echo $item['fech'] ?></td>
                    <td><?php echo $item['razo'] ?></td>
                    <td><?php echo $item['mone'] ?></td>
                    <td><?php echo number_format($item['importe'], 2, '.', ',') ?></td>
                    <td class="text-center">
                        <a class="btn btn-danger" role="button" onclick="abrirModalEliminar('<?= $item['idauto'] ?>','<?= $item['ndoc'] ?>','<?= trim($item['estadoenviado']) ?>','<?= $item['tdoc'] ?>')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <a class="btn btn-success" role="button" onclick="abrirModalBaja('<?= $item['idauto'] ?>','<?= $item['ndoc'] ?>','<?= trim($item['estadoenviado']) ?>','<?= trim($item['fech']) ?>','<?= $item['tdoc'] ?>')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$cl = new \App\View\Components\ModalConfirmarLoginComponent();
echo $cl->render();
?>
<div class="modal fade" id="modalbajaventa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="lblBajaVenta">Baja de Documento: </h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="col-form-label">Usuario:</label>
                    <input type="usuario" class="form-control" name="txtUsuariob" id="txtUsuariob">
                </div>
                <div class="mb-3">
                    <label class="col-form-label">Contraseña:</label>
                    <input type="password" class="form-control" name="txtPasswordb" id="txtPasswordb">
                </div>
                <input style="display:none" type="text" class="form-control" name="txtidautobaja" id="txtidautobaja">
                <input style="display:none" type="text" class="form-control" name="txtndocbaja" id="txtndocbaja">
                <input style="display:none" type="text" class="form-control" name="txttdoc" id="txttdoc">
                <input style="display:none" type="text" class="form-control" name="txtfecha" id="txtfecha">
                <div class="text-end">
                    <input type="submit" class="btn btn-warning" onclick="bajadocumento()" value="Dar Baja">
                    <input type="submit" class="btn btn-danger" onclick="cerrarModalBaja()" value="Cancelar">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function abrirModalEliminar(idauto, numeroDocumento, estado, tdoc) {
        if (estado == '0') {
            toastr.error("El comprobante ya fue informado a SUNAT", 'Mensaje del Sistema');
        } else {
            $("#modalConfirmarLogin").modal("show");
            $("#lbltitle").text("Eliminar Documento: " + numeroDocumento)
            $("#txtIdauto").val(idauto)
            $("#txttdoc").val(tdoc)
        }
    }

    function abrirModalBaja(idauto, numeroDocumento, estado, dfecha, tdoc) {
        if (estado != '0') {
            toastr.error("El comprobante no esta registrado en SUNAT, por lo tanto se debe anular por la opción del lado.", 'Mensaje del Sistema');
        } else {
            $("#modalbajaventa").modal("show");
            $("#lblBajaVenta").text("Dar de Baja : " + numeroDocumento)
            $("#txtidautobaja").val(idauto)
            $("#txtndocbaja").val(numeroDocumento)
            $("#txtfecha").val(dfecha)
            $("#txttdoc").val(tdoc)
        }
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        data.append("txtIdauto", document.getElementById("txtIdauto").value);
        data.append("txttdoc", document.getElementById("txttdoc").value);
        data.append("cmbTipoMovimiento", $("#cmbTipoMovimiento").val());
        axios.post("/cpe/eliminarDocumento", data)
            .then(function(respuesta) {
                toastr.success("Eliminado correctamente", 'Mensaje del Sistema');
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message, 'Mensaje del Sistema');
                    }
                }
            });
        $("#search").click();
    }

    function bajadocumento() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuariob").value);
        data.append("txtPassword", document.getElementById("txtPasswordb").value);
        data.append("txtIdauto", document.getElementById("txtidautobaja").value);
        data.append("txtndocbaja", document.getElementById("txtndocbaja").value);
        data.append("txttdoc", document.getElementById("txttdoc").value);
        data.append("txtfecha", document.getElementById("txtfecha").value);
        axios.post("/cpe/bajadocumento", data)
            .then(function(respuesta) {
                toastr.success("Eliminado correctamente", 'Mensaje del Sistema');
                $("#modalbajaventa").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        toastr.error(error.response.data.message, 'Mensaje del Sistema');
                    }
                }
            });
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function cerrarModalBaja() {
        $("#modalbajaventa").modal("hide");
    }
</script>