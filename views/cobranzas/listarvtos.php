<div class="table-responsive">
    <table id="tabla" class="table table-bordered table-hover table-sm small" data-page-size="500">
        <thead>
            <tr>
                <th class="d-lg-none">Ncontrol</th>
                <th class="d-lg-none">rcre_idrc</th>
                <th class="d-lg-none">razo</th>
                <th style="width: 40px;">Documento</th>
                <th style="width: 5px;">Mon.</th>
                <th style="width: 80px;">F.Emis.</th>
                <th style="width: 89px;">F.Vto.</th>
                <th style="width: 10px;">Días</th>
                <th style="width: 15px;">Tipo</th>
                <th style="width: 20x;">Referencia</th>
                <th style="width: 40px;">Vendedor</th>
                <th style="width: 5px;">Forma</th>
                <th style="width: 35px;" class="text-end" style="text-align:right;" data-footer-formatter="formatTotal">Saldo</th>
                <th style="width: 10px;" style="text-align:right;">Cancelar</th>
                <th class="d-lg-none">idclie</th>
                <th class="d-lg-none">tdoc</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td class="ncontrol"><?php echo $item['ncontrol'] ?></td>
                    <td class="rcre_idrc"><?php echo $item['rcre_idrc'] ?></td>
                    <td class="razo"><?php echo $item['razo'] ?></td>
                    <td class="ndoc"><?php echo $item['ndoc'] ?></td>
                    <td class="mon"><?php echo $item['mone'] ?></td>
                    <td class="fech"><?php echo $item['fech'] ?></td>
                    <td class="fechvto"><?php echo $item['fevto'] ?></td>
                    <td class="dias"><?php echo $item['dias'] ?></td>
                    <td class="tipo"><?php echo $item['tipo'] ?></td>
                    <td class="docd"><?php echo $item['docd'] ?></td>
                    <td class="nomv"><?php echo $item['nomv'] ?></td>
                    <td class="form">
                        <?php switch ($item['form']) {
                            case 'E':
                                echo 'EFECTIVO';
                                break;
                            case 'C':
                                echo 'CRÉDITO';
                                break;
                            case 'D':
                                echo 'DEPÓSITO';
                                break;
                            case 'T':
                                echo 'TARJETA';
                                break;
                            case 'Y':
                                echo 'YAPE';
                                break;
                            case 'P':
                                echo 'PLIN';
                                break;
                        } ?>
                    </td>
                    <td class="importe" data-footer-formatter="formatTotal" style="text-align:end;"><?php echo $item['importe'] ?></td>
                    <td class="cancelar" style="width: 10px;"><input type="text" value="0" onclick="this.select()" onkeyup="calculartotal(this);" onblur="verificarvalor()" onkeypress="return isNumber(event);" class="form-control form-control-sm text-end" name="" id=""></td>
                    <td class="idclie"><?php echo $item['idclie'] ?></td>
                    <td class="tdoc"><?php echo $item['tdoc'] ?></td>
                    <td style="text-align: CENTER;">
                        <button class="btn btn-success" onclick="consultardetalle(<?php echo $item['idauto'] ?> , '<?php echo $item['ndoc'] ?>')"><i href="" style="color:white;" class="fas fa-info-circle"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <!-- <tfoot>
        <tr>
            <th colspan="10" style="text-align:right">Total:</th>
            <th></th>
        </tr>
    </tfoot> -->
    </table>
</div>
<div class="row">
    <div class="col-6">
    </div>
    <div class="col-6 text-end">
        <button class="btn btn-danger" onclick="limpiartodo()">Limpiar</button>
        <button class="btn btn-primary" onclick="openmodal();">Grabar Transacciones</button>
    </div>
</div>
<script>
    reportetablebt("#tabla");
    $(".ncontrol").css("display", "none");
    $(".rcre_idrc").css("display", "none");
    $(".razo").css("display", "none");
    $(".idclie").css("display", "none");
    $(".tdoc").css("display", "none");
</script>