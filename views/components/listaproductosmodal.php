<table id="tabla_productos" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="font-size:10px;" id="headersysven">Producto</th>
            <th style="font-size:10px;" id="headersysven">Und.</th>
            <th style="font-size:10px;" id="headersysven">Precio</th>
            <th class="text-center" id="headersysven">Elegir</th>
        </tr>
    </thead>
    <tbody>
        <?php $tds = cargarsucursalestbody(); ?>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr style="font-size: 10px;">
                <td><?php echo $item['descri'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <td class="text-end"><?php echo number_format($item['pre1'], 2, ',', '.') ?></td>
                <td class="text-center" id="iniciarp" style="font-size:10px;">
                    <?php
                    // $parametro1 = $item['descri'];
                    $parametro1 = str_replace("'", '"', $item['descri'] . ' - ' . $item['marca']);
                    $parametro2 = $item['idart'];
                    $parametro3 = $item['unid'];
                    $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
                    if ($multiempresa == 'S') {
                        switch ($_SESSION['idalmacen']) {
                            case '1':
                                $parametro4 = floatval($item['uno']);
                                break;
                            case '2':
                                $parametro4 = floatval($item['dos']);
                                break;
                            case '3':
                                $parametro4 = floatval($item['tre']);
                                break;
                            case '4':
                                $parametro4 = floatval($item['cua']);
                                break;
                        }
                    } else {
                        $parametro4 = $item['uno'] + $item['dos'] + $item['tre'] + $item['cua'];
                    }
                    // if ($item['tipro'] == 'K') {
                    $parametro5 = $item['pre1'];
                    // } else {
                    //     $parametro5 = $item['costo'];
                    // }
                    $parametro6 = $item['pre2'];
                    if (empty($_SESSION['config']['precioespecial'])) {
                        $parametro7 = $item['prec'];
                    } else {
                        $parametro7 = $item['pre3'];
                    }
                    $parametro8 = $item['costo'];
                    $parametro9 = $item['peso'];
                    $parametro10 = $item['tipro'];
                    $stockuno = $item['uno'];
                    $stockdos = $item['dos'];
                    $stocktre = $item['tre'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'stockuno', 'stockdos', 'stocktre');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button class="btn <?php echo ((intval($parametro4) < 0) ?  'btn-danger' : 'btn-success') ?> btn-sm" data-target="#agregar_cantidad" id="<?php echo 'agregar' . $parametro2 ?>" onclick='agregarunitemVenta(<?php echo $cadena_json ?>)' style="font-size: 5px;"><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustablaproducto('#tabla_productos')
    });
</script>