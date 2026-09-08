<div id="modaldetalle" class="modal fade " tabindex="-1" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblmodaldetalle">Detalle del comprobante</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tbldetalle">
                        <thead>
                            <tr>
                                <th scope="col">Producto</th>
                                <th scope="col">Unidad</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Sub. Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="float-right">
                    <div class="input-group mb-3">
                        <span class="input-group-text form-control-sm" id=""><b>Total:</b> </span>
                        <input type="text" id="txtimportemodal" class=" form-control" value="0.00" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnEliminar" class="btn btn-danger" onclick="cerrarModal();" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>