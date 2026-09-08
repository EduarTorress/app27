<table id="tabla" class="table table-sm">
    <thead>
        <tr>
            <th style="display:none">ID</th>
            <th style="width: 400px;">Descripción</th>
            <th style="width: 70px;" class="text-center">Unid.</th>
            <th style="width: 70px;" class="text-center">Cant.</th>
            <th style="width: 70px;" class="text-center">Precio</th>
            <th style="width: 70px;" class="text-center">Importe</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; ?>
        <?php foreach ($detalle as $indice => $item) : ?>
            <?php if ($item['activo'] == 'A') { ?>
                <tr class="fila">
                    <td style="display:none" class="controles">
                        <input type="text" name="nreg1" style="width: 100%;" class="nreg" id="nreg1" placeholder="" value="<?php echo $item['nreg'] ?>">
                    </td>
                    <td class="controles">
                        <input type="text" name="descripcion1" style="width: 100%;" class="descripcion" id="descripcion1" placeholder="Descripcion" value="<?php echo $item['descri'] ?>">
                    </td>
                    <td class="text-center controles">
                        <input type="text" name="unidad1" style="width: 70px;" class="unidad" id="unidad1" value="<?php echo $item['unidad'] ?>">
                    </td>
                    <td class="text-center controles">
                        <input type="number" name="cantidad1" onkeyup="calcularPrecioTotal()" style="width: 70px;" class="cantidad" id="cantidad1" value="<?php echo $item['cant'] ?>">
                    </td>

                    <td class="text-center controles">
                        <input type="number" name="precio1" onkeyup="calcularPrecioTotal();" style="width:70px;" class="precio" id="precio1" value="<?php echo $item['precio'] ?>">
                    </td>
                    <td class="text-center controles">
                        <input type="number" name="subt1" onkeyup="calcularPrecioTotal()" style="width:70px;" class="subt" id="subt1" value="<?php echo $item['subt'] ?>" disabled>
                    </td>
                </tr>
            <?php } ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" align="center">
                <button id="agregar" class="btn btn-success" disabled>Adicionar</button>
            </td>
        </tr>
    </tfoot>
</table>
<div class="input-group">
    <input type="text" id="txtsubtotal" name="txtsubtotal" placeholder="SUB TOTAL" class="form-control" value="<?php echo empty($datosclientev['subtotalv']) ?  '' :  $datosclientev['subtotalv'] ?>" style="width:140px;" disabled>
    <input type="text" id="txtigv" name="txtigv" placeholder="IGV" class="form-control" value="<?php echo empty($datosclientev['igvv']) ?  '' :  $datosclientev['igvv'] ?>" disabled>
    <input type="text" id="txttotal" name="txttotal" placeholder="TOTAL" class="form-control" value="<?php echo empty($datosclientev['impov']) ?  '' :  $datosclientev['impov'] ?>" disabled>
</div>
<input type="hidden" id="txtvalordetraccion" name="txtvalordetraccion" class="form-control" value="<?php echo round(floatval($gene_detra), 0); ?>" disabled>
<input type="text" id="txtdetraccion" name="txtdetraccion" placeholder="0.00" class="form-control" disabled value="<?php echo empty($datosclientev['rcom_detr']) ?  '' :  $datosclientev['rcom_detr'] ?>">
<br>
<div class="d-flex justify-content-end">
    <button class="btn btn-success" onclick="grabarVenta();">Grabar</button>
    <button class="btn btn-warning" onclick="limpiar()">Limpiar</button>
</div>