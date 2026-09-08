<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="gridpedidos">
        <thead>
            <tr>
                <th scope="col" style="width:7%;" id="headersysven">Opciones</th>
                <th scope="col" style="width:5%" class="coda" id="headersysven">Código</th>
                <th scope="col" style="width:25%" id="headersysven">Producto</th>
                <th scope="col" style="width:5%" id="headersysven">UM</th>
                <th scope="col" style="width:5%; text-align:right;" id="headersysven">Cantidad</th>
                <th scope="col" style="width:5%; text-align:right;" id="headersysven">Precio</th>
                <th scope="col" class="preciosgv" style="width:5%" id="headersysven">Valor Unitario</th>
                <th scope="col" style="width:5%; text-align:right;" id="headersysven">Importe</th>
            </tr>
        </thead>
        <tbody id="carritocompras">
            <?php $i = 0; ?>
            <?php foreach ($carrito as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr>
                        <?php
                        $parametro1 = $item['descri'];
                        $parametro2 = $item['coda'];
                        $parametro3 = $item['unidad'];
                        $parametro4 = $item['stock'];
                        $parametro5 = $item['precio1'];
                        $parametro6 = $item['precio2'];
                        $parametro7 = $item['precio3'];
                        $parametro8 = $item['costo'];
                        $parametro9 = $item['cantidad'];
                        $parametro10 = $item['precio'];
                        $parametro11 = $indice;
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                            <button class="btn btn-success  btn-sm" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button>
                            <!-- <?php if ($_SESSION['config']['cambiarproductoxposicion'] == 'S') : ?>
                            <button class="btn btn-secondary" onclick='cambiaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fa fa-exchange"></a></button>
                            <?php endif; ?> -->
                        </td>
                        <td class="coda"><?php echo $item['coda'] ?></td>
                        <td><?php echo $item['descri'] ?></td>
                        <td><?php echo $item['unidad'] ?></td>
                        <td class="text-end"><?php echo number_format($item['cantidad'], 2, '.', ',') ?></td>
                        <td class="precio text-end"><?php echo number_format($item['precio'], 3, '.', ',') ?></td>
                        <td class="preciosgv"></td>
                        <td class="text-end"><?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', ',') ?></td>
                        <?php $i++; ?>
                    </tr>
                <?php } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div><br>
<div class="col-lg-12">
    <div class="card card-primary card-outline" style="width:auto;">
        <div class="row">
            <div class="col-sm-3">
                <div class="input-group mb-3">
                    <label class="form-control form-control-sm" for="">Detalle de Pago:</label>
                    <input class="form-control form-control-sm" type="text" name="txtdetallepago" id="txtdetallepago" value="<?php echo (empty($_SESSION['cliente']['forma']) ? ' ' : $_SESSION['cliente']['forma']) ?>">
                </div>
            </div>
            <div class=" col-sm-3">
                <div class="input-group mb-3">
                    <label class="form-control form-control-sm" for="">Validez de Oferta:</label>
                    <input class="form-control form-control-sm" type="text" name="txtvalidezoferta" id="txtvalidezoferta" value="<?php echo (empty($_SESSION['cliente']['validez']) ? ' ' : $_SESSION['cliente']['validez']) ?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group mb-3">
                    <label class="form-control form-control-sm" for="">Plazo de Entrega:</label>
                    <input class="form-control form-control-sm" type="text" name="txtplazoentrega" id="txtplazoentrega" value="<?php echo (empty($_SESSION['cliente']['plazo']) ? ' ' : $_SESSION['cliente']['plazo']) ?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="input-group mb-3">
                    <label class="form-control form-control-sm" for="">Lugar de Entrega:</label>
                    <input class="form-control form-control-sm" type="text" name="txtlugarentrega" id="txtlugarentrega" value="<?php echo (empty($_SESSION['cliente']['entrega']) ? ' ' : $_SESSION['cliente']['entrega']) ?>">
                </div>
            </div>
        </div>
        <div class="input-group" id="divobservaciones">
            <label for="" class="col-form-label form-control-sm ">Observaciones:</label>
            <div>
                <textarea class="form-control form-control-sm" placeholder="" id="txtdetalle" name="txtdetalle" style="width:200%; height:65%"><?php echo (empty($_SESSION['cliente']['detalle']) ? ' ' : $_SESSION['cliente']['detalle']) ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-6"><br>
                <button class="btn btn-primary btn-sm" id="btnagregarprod" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarpedido()">Limpiar</button>
                <button class="btn btn-success btn-sm" role="button" onclick="grabarpedido()">Grabar </button>
                <?php if ($_SESSION['tipousuario'] == 'A') : ?>
                    <button class="btn btn-warning btn-sm" onclick="verutilidad();">Ver Utilidad</button>
                <?php endif; ?>
                <!-- <button class="btn btn-secondary btn-sm" role="button"><a style="color:white;" href="<?php echo "/productos/index/1" ?>">Lista de Productos</a></button> -->
                <!-- <?php if (!empty($_SESSION['idpedido'])) : ?> -->
                <!-- <?php if ($_SESSION['config']['guardarpedidocomonuevo'] == 'S') : ?>
                        <button class="btn btn-success btn-sm" role="button" onclick="guardarpedido('Registrar Pedido como nuevo')">Grabar Nuevo </button>
                    <?php endif; ?> -->
                <!-- <?php endif; ?> -->
            </div>
            <div class="col-2 align-items-end"><br>
                <div class="input-group mb-3" style="width: 85%; display:none" id="divutilidad">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Costo:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="txtutilidad" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-end"><br>
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value=<?php echo  $items ?> aria-describedby="inputGroup-sizing-sm" disabled>
                    <input type="text" name="nropedido" id="nropedido" hidden value=" <?php echo isset($nropedido) ? $nropedido : '' ?> ">
                </div>
            </div>
            <div class="col-2 align-items-start"><br>
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value=<?php echo  $total ?> aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="txtindice" value="<?php echo $indice; ?>">
<script>
    $(".coda").css("display", "none");
    <?php
    $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv'];
    if ($multiigv == 'N') : ?>
        $(".preciosgv").css("display", "none");
    <?php endif; ?>
</script>