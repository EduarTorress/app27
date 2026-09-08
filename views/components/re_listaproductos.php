<div class="card-body">
    <div class="table-responsive">
        <table id="tabla_productos" class="table table-bordered table-hover table-sm small">
            <thead>
                <tr>
                    <th style="width:2%;" class="text-center" id="headersysven">ID</th>
                    <th style="width:60%;" id="headersysven">Producto</th>
                    <th style="width:5%;" class="text-center" id="headersysven">U.M.</th>
                    <?php if (!empty($_SESSION['config']['columnacategoria'])) : ?>
                        <th style="width:8%;" class="text-center" id="headersysven">CAT.</th>
                    <?php endif; ?>
                    <!-- <?php $sucursales = cargarsucursales(); ?> -->
                    <?php if (count($sucursales) > 1) : ?>
                        <th style="width:8%;" class="text-right" id="headersysven">Stock Total</th>
                    <?php endif; ?>
                    <!-- <?php foreach ($sucursales as $s) : ?>
                        <th style="width:8%;" class="text-right" id="headersysven"><?php echo ucfirst(strtolower(substr($s['nomb'], 0, 8))) . "."; ?></th>
                    <?php endforeach; ?> -->
                    <th style="width:8%;" class="text-right" id="headersysven">P.Venta</th>
                    <th style="width:5%;" class="text-center" id="headersysven">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista['lista']['items'] as $item) : ?>
                    <tr onclick="obteneridart('<?php echo $item['idart']; ?>');">
                        <?php
                        $parametro2 = $item['idart'];
                        ?>
                        <td id="<?php echo 'hola' . $parametro2 ?>"><?php echo $item['idart'] ?></td>
                        <td><?php echo $item['descri'] . ' - ' . $item['marca'] ?></td>
                        <td><?php echo $item['unid'] ?></td>
                        <?php if (!empty($_SESSION['config']['columnacategoria'])) : ?>
                            <td><?php echo $item['dcat'] ?></td>
                        <?php endif; ?>
                        <?php if (count($sucursales) > 1) : ?>
                            <td class="text-right"><?php echo ($item['uno'] + $item['dos'] + $item['tre']) ?></td>
                        <?php endif; ?>
                        <?php
                        $sucu = array($item['uno'], $item['dos'], $item['tre']);
                        $i = 0;
                        ?>
                        <!-- <?php foreach ($sucursales as $s) : ?>
                            <td class="text-right"><?php echo $sucu[$i]; ?></td>
                        <?php $i++;
                                endforeach;
                        ?>-->
                        <td class="text-right"><?php echo evaluarvalortdoc('01', $item['pre1']) ?></td>
                        <td class="text-center" id="iniciar">
                            <?php
                            $parametro1 = str_replace("'", '"', $item['descri']);
                            $parametro2 = $item['idart'];
                            $parametro3 = $item['unid'];
                            $parametro4 = $item['uno'] + $item['dos'] + $item['tre'];
                            $parametro5 = $item['pre1'];
                            $parametro6 = $item['pre2'];
                            $parametro7 = $item['pre3'];
                            $parametro9 = $item['uno'];
                            $parametro10 = $item['dos'];
                            $parametro11 = $item['tre'];
                            $tipro = $item['tipro'];
                            $idmarca = $item['idmarca'];
                            $idgrupo = $item['idgrupo'];
                            $idcat = $item['idcat'];
                            $prod_cod1 = $item['prod_cod1'];
                            $peso = $item['peso'];
                            $idflete = $item['idflete'];
                            $prod_smin = $item['prod_smin'];
                            $prod_smax = $item['prod_smax'];
                            $costocigv = $item['costocigv'];
                            $costosigv = $item['prec'];
                            $costo = $item['costo'];
                            $flete = $item['flete'];
                            $tmon = $item['tmon'];
                            $prod_come = $item['prod_come'];
                            $prod_comc = $item['prod_comc'];
                            $prod_uti1 = $item['prod_uti1'];
                            $prod_uti2 = $item['prod_uti2'];
                            $prod_uti3 = $item['prod_uti3'];
                            $uldc = $item['uldc'];
                            $parametros = compact(
                                'parametro1',
                                'parametro2',
                                'parametro3',
                                'parametro4',
                                'parametro5',
                                'parametro6',
                                'parametro7',
                                'idmarca',
                                'idgrupo',
                                'tipro',
                                'idcat',
                                'prod_cod1',
                                'peso',
                                'idflete',
                                'prod_smin',
                                'prod_smax',
                                'costosigv',
                                'costocigv',
                                'flete',
                                'tmon',
                                'prod_come',
                                'prod_comc',
                                'prod_uti1',
                                'prod_uti2',
                                'prod_uti3',
                                'parametro9',
                                'parametro10',
                                'parametro11',
                                'costo',
                                'uldc'
                            );
                            $cadena_json = json_encode($parametros);
                            $opt = session()->get('tiposel', '0');
                            switch ($opt) {
                                case 0: ?>
                                    <button class="btn btn-success btn-sm" id="<?php echo "agregar" . $parametro2 ?>" onclick='agregarunitemcarrito(<?php echo $cadena_json ?>)'><a style="color:white;" class="a fas fa-plus-circle"></a></button>
                                <?php break;
                                case 1: ?>
                                    <button class="btn btn-warning btn-sm" id="<?php echo "agregar" . $parametro2 ?>" disabled data-target="#agregar_cantidad" onclick='agregar_producto(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                                <?php break;
                                case 3: ?>
                                    <?php if ($tipro == 'C') : ?>
                                        <button class="btn btn-warning btn-sm" id="<?php echo "agregar" . $parametro2 ?>" onclick='armarcombo(<?php echo $cadena_json ?>)'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                                    <?php endif; ?>
                                <?php break;
                                case 4: ?>
                                    <?php $tipousuario = $_SESSION['tipousuario'];
                                    if ($tipousuario == 'A' || $tipousuario == 'L') { ?>
                                        <a class="btn btn-info btn-sm" role="button" onclick='buscarProductoxId(<?php echo $cadena_json ?>)'>
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm" role="button" onclick="anularproducto(<?php echo $item['idart'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php  } ?>
                                <?php break;
                                case 5: ?>
                                    <button class="btn btn-info" id="<?php echo "agregar" . $parametro2 ?>" onclick='getDataArtStock(<?php echo $cadena_json ?>)'><a class="a fas fa-plus-circle" style="color:white;"></a></button>
                            <?php break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    nro = document.getElementById('tabla_productos').rows[0].cells.length
    // console.log(nro);
    $('#tabla_productos').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": false,
        "dom": 'Bfrtip',
        "keys": true,
        "buttons": [{
                //Botón para Excel
                extend: 'excelHtml5',
                footer: true,
                title: 'Reporte de Sysven',
                filename: 'Sysven-Reporte',
                //Aquí es donde generas el botón personalizado
                text: '<span class="badge badge-success"><i class="fas fa-file-excel"></i></span>'
            },
            //Botón para PDF
            {
                extend: 'pdfHtml5',
                download: 'open',
                title: 'Reporte de Sysven',
                filename: 'Sysven-Reporte',
                text: '<span class="badge badge-danger"><i class="fas fa-file-pdf"></i></span>'
            },
            //Botón para copiar
            {
                extend: 'copyHtml5',
                footer: true,
                title: 'Reporte de Sysven',
                filename: 'Sysven-Reporte',
                text: '<span class="badge badge-primary"><i class="fas fa-copy"></i></span>',
                exportOptions: {
                    columns: [0, ':visible']
                }
            },
            //Botón para cvs
            {
                extend: 'csvHtml5',
                footer: true,
                filename: 'Export_File_csv',
                text: '<span class="badge badge-success"><i class="fas fa-file-csv"></i></span>'
            }
        ],
        "columnDefs": [{
            targets: nro - 1,
            orderable: false,
            searchable: false
        }]
    });
</script>