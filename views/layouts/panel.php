<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<div class="content-wrapper" id="container">
    <div class="content">
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card mb-3" style="color: #03326a;">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Ventas <?php  echo ' (' . date('d/m/Y') . ')' ?></h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalventas; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Monto Ventas S/ <?php  echo ' (' . date('d/m/Y') . ')' ?></h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $montoventassoles; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Monto Ventas $ <?php  echo ' (' . date('d/m/Y') . ')' ?></h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $montoventasdolares; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Cotiza. <?php  echo ' (' . date('d/m/Y') . ')' ?></h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalpedidos; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Clientes Registrados</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalclientes; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3" style="color: #03326a;">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h4 class="text-sm mb-0 text-capitalize font-weight-bold">Total Productos Registrados</h4>
                                        <h5 class="font-weight-bolder mb-0">
                                            <?php echo $totalproductos; ?>
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-header">VENTAS POR MES</div>
                                <div class="card-body text-primary">
                                    <canvas id="circularventaxmes" style="width:100%;max-width:600px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-header">COMPRAS POR MES</div>
                                <div class="card-body text-primary">
                                    <canvas id="circularcomprasxmes" style="width:100%;max-width:600px"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-header">COTIZACIONES POR MES</div>
                                <div class="card-body text-primary">
                                    <canvas id="circularpedidos" style="width:100%;max-width:600px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-md-3">
                            <div class="card border-primary ">
                                <div class="card-header">MONTO TOTAL DE VENTAS POR AÑO</div>
                                <div class="card-body text-primary">
                                    <canvas id="barraventasxano" style="width:100%;max-width:600px;height:380px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <div class="card border-primary ">
                                <div class="card-header">MONTO TOTAL DE COMPRAS POR AÑO</div>
                                <div class="card-body text-primary">
                                    <canvas id="barracomprasxano" style="width:100%;max-width:600px;height:380px;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <div class="card border-primary ">
                                <div class="card-header">MONTO TOTAL DE COTIZA. POR AÑO</div>
                                <div class="card-body text-primary">
                                    <canvas id="barrapedidossxano" style="width:100%;max-width:600px;height:380px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    titulo("Dashboard");

    graficoventasxmes();
    graficoventasxano();

    graficocomprasxmes();
    graficocomprasxano();

    graficocircularpedidos();
    graficopedidosxano();


    function graficoventasxmes() {
        var xValues = [<?php echo ("'" . (empty($totalventaspormes[0]['mes']) ? '' : $totalventaspormes[0]['mes']) . "'" . "," . "'" . (empty($totalventaspormes[1]['mes']) ? '' : $totalventaspormes[1]['mes']) . "'" . "," .
                            "'" . (empty($totalventaspormes[2]['mes']) ? '' : $totalventaspormes[2]['mes']) . "'" . "," .  "'" . (empty($totalventaspormes[3]['mes']) ? '' : $totalventaspormes[3]['mes']) . "'"  . "," .
                            "'" . (empty($totalventaspormes[4]['mes']) ? '' : $totalventaspormes[4]['mes']) . "'" . "," .  "'" . (empty($totalventaspormes[5]['mes']) ? '' : $totalventaspormes[5]['mes']) . "'" . "," .
                            "'" . (empty($totalventaspormes[6]['mes']) ? '' : $totalventaspormes[6]['mes']) .  "'" . "," .
                            "'" . (empty($totalventaspormes[7]['mes']) ? '' : $totalventaspormes[7]['mes']) . "'" . "," . "'" . (empty($totalventaspormes[8]['mes']) ? '' : $totalventaspormes[8]['mes']) . "'" . "," .
                            "'" . (empty($totalventaspormes[9]['mes']) ? '' : $totalventaspormes[9]['mes']) . "'" . "," . "'" . (empty($totalventaspormes[10]['mes']) ? '' : $totalventaspormes[10]['mes']) . "'" . "," .
                            "'" . (empty($totalventaspormes[11]['mes']) ? '' : $totalventaspormes[11]['mes']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalventaspormes[0]['total']) ? '0' : $totalventaspormes[0]['total']) . "'" . "," . "'" . (empty($totalventaspormes[1]['total']) ? '0' : $totalventaspormes[1]['total']) . "'" . "," .
                            "'" . (empty($totalventaspormes[2]['total']) ? '0' : $totalventaspormes[2]['total']) . "'" . "," .  "'" . (empty($totalventaspormes[3]['total']) ? '0' : $totalventaspormes[3]['total']) . "'"  . "," .
                            "'" . (empty($totalventaspormes[4]['total']) ? '0' : $totalventaspormes[4]['total']) . "'" . "," .  "'" . (empty($totalventaspormes[5]['total']) ? '0' : $totalventaspormes[5]['total']) . "'" . "," .
                            "'" . (empty($totalventaspormes[6]['total']) ? '0' : $totalventaspormes[6]['total']) .   "'" . "," .
                            "'" . (empty($totalventaspormes[7]['total']) ? '0' : $totalventaspormes[7]['total']) . "'" . "," . "'" . (empty($totalventaspormes[8]['total']) ? '0' : $totalventaspormes[8]['total']) . "'" . "," .
                            "'" . (empty($totalventaspormes[9]['total']) ? '0' : $totalventaspormes[9]['total']) . "'" . "," . "'" . (empty($totalventaspormes[10]['total']) ? '0' : $totalventaspormes[10]['total']) . "'" . "," .
                            "'" . (empty($totalventaspormes[11]['total']) ? '0' : $totalventaspormes[11]['total']) . "'")
                        ?>];
        var barColors = [
            "#b91d47",
            "#00aba9",
            "#2b5797",
            "#e8c3b9",
            "#1e7145",
            "#00c71b",
            "#0045c7",
            "#ea960d",
            "#f4611d",
            "#3621a2",
            "#ae2488",
            "#54c16f"
        ];

        new Chart("circularventaxmes", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de ventas"
                },
                legend: {
                    position: 'top'
                }
            }
        });
    }

    function graficoventasxano() {
        var xValues = [<?php echo ("'" . (empty($totalmontoventas[0]['ano']) ? '' : $totalmontoventas[0]['ano']) . "'" . "," . "'" . (empty($totalmontoventas[1]['ano']) ? '' : $totalmontoventas[1]['ano']) . "'" . "," .
                            "'" . (empty($totalmontoventas[2]['ano']) ? '' : $totalmontoventas[2]['ano']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalmontoventas[0]['total']) ? '0' : $totalmontoventas[0]['total']) . "'" . "," . "'" . (empty($totalmontoventas[1]['total']) ? '0' : $totalmontoventas[1]['total']) . "'" . "," .
                            "'" . (empty($totalmontoventas[2]['total']) ? '0' : $totalmontoventas[2]['total']) . "'")
                        ?>];
        var barColors = ["#4e79a7", "#f28e2b", "#59a14f"];

        new Chart("barraventasxano", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                    label: "Ventas por año"
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de ventas"
                },
                legend: {
                    position: 'top'
                }
            }
        });
    }

    function graficocomprasxmes() {
        var xValues = [<?php echo ("'" . (empty($totalcompraspormes[0]['mes']) ? '' : $totalcompraspormes[0]['mes']) . "'" . "," . "'" . (empty($totalcompraspormes[1]['mes']) ? '' : $totalcompraspormes[1]['mes']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[2]['mes']) ? '' : $totalcompraspormes[2]['mes']) . "'" . "," .  "'" . (empty($totalcompraspormes[3]['mes']) ? '' : $totalcompraspormes[3]['mes']) . "'"  . "," .
                            "'" . (empty($totalcompraspormes[4]['mes']) ? '' : $totalcompraspormes[4]['mes']) . "'" . "," .  "'" . (empty($totalcompraspormes[5]['mes']) ? '' : $totalcompraspormes[5]['mes']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[6]['mes']) ? '' : $totalcompraspormes[6]['mes']) .  "'" . "," .
                            "'" . (empty($totalcompraspormes[7]['mes']) ? '' : $totalcompraspormes[7]['mes']) . "'" . "," . "'" . (empty($totalcompraspormes[8]['mes']) ? '' : $totalcompraspormes[8]['mes']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[9]['mes']) ? '' : $totalcompraspormes[9]['mes']) . "'" . "," . "'" . (empty($totalcompraspormes[10]['mes']) ? '' : $totalcompraspormes[10]['mes']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[11]['mes']) ? '' : $totalcompraspormes[11]['mes']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalcompraspormes[0]['total']) ? '0' : $totalcompraspormes[0]['total']) . "'" . "," . "'" . (empty($totalcompraspormes[1]['total']) ? '0' : $totalcompraspormes[1]['total']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[2]['total']) ? '0' : $totalcompraspormes[2]['total']) . "'" . "," .  "'" . (empty($totalcompraspormes[3]['total']) ? '0' : $totalcompraspormes[3]['total']) . "'"  . "," .
                            "'" . (empty($totalcompraspormes[4]['total']) ? '0' : $totalcompraspormes[4]['total']) . "'" . "," .  "'" . (empty($totalcompraspormes[5]['total']) ? '0' : $totalcompraspormes[5]['total']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[6]['total']) ? '0' : $totalcompraspormes[6]['total']) .   "'" . "," .
                            "'" . (empty($totalcompraspormes[7]['total']) ? '0' : $totalcompraspormes[7]['total']) . "'" . "," . "'" . (empty($totalcompraspormes[8]['total']) ? '0' : $totalcompraspormes[8]['total']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[9]['total']) ? '0' : $totalcompraspormes[9]['total']) . "'" . "," . "'" . (empty($totalcompraspormes[10]['total']) ? '0' : $totalcompraspormes[10]['total']) . "'" . "," .
                            "'" . (empty($totalcompraspormes[11]['total']) ? '0' : $totalcompraspormes[11]['total']) . "'")
                        ?>];
        var barColors = [
            "#4e79a7", // azul
            "#f28e2b", // naranja
            "#e15759", // rojo suave
            "#76b7b2", // turquesa
            "#59a14f", // verde
            "#edc948", // amarillo
            "#b07aa1", // morado
            "#ff9da7", // rosado
            "#9c755f", // marrón
            "#bab0ab", // gris
            "#2f4b7c", // azul oscuro
            "#d45087" // magenta
        ];

        new Chart("circularcomprasxmes", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de compras"
                },
                legend: {
                    position: 'top'
                }
            }
        });
    }



    function graficocomprasxano() {
        var xValues = [<?php echo ("'" . (empty($totalmontocompras[0]['ano']) ? '' : $totalmontocompras[0]['ano']) . "'" . "," . "'" . (empty($totalmontocompras[1]['ano']) ? '' : $totalmontocompras[1]['ano']) . "'" . "," .
                            "'" . (empty($totalmontocompras[2]['ano']) ? '' : $totalmontocompras[2]['ano']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalmontocompras[0]['total']) ? '0' : $totalmontocompras[0]['total']) . "'" . "," . "'" . (empty($totalmontocompras[1]['total']) ? '0' : $totalmontocompras[1]['total']) . "'" . "," .
                            "'" . (empty($totalmontocompras[2]['total']) ? '0' : $totalmontocompras[2]['total']) . "'")
                        ?>];
        var barColors = ["#ff595e", "#ffca3a", "#1982c4"];

        new Chart("barracomprasxano", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                    label: "Compras por año"
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de compras"
                },
                legend: {
                    position: 'top'
                }
            }
        });
    }


    function graficocircularpedidos() {
        var xValues = [<?php echo ("'" . (empty($totalpedidospormes[0]['mes']) ? '' : $totalpedidospormes[0]['mes']) . "'" . "," . "'" . (empty($totalpedidospormes[1]['mes']) ? '' : $totalpedidospormes[1]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[2]['mes']) ? '' : $totalpedidospormes[2]['mes']) . "'" . "," .  "'" . (empty($totalpedidospormes[3]['mes']) ? '' : $totalpedidospormes[3]['mes']) . "'"  . "," .
                            "'" . (empty($totalpedidospormes[4]['mes']) ? '' : $totalpedidospormes[4]['mes']) . "'" . "," .  "'" . (empty($totalpedidospormes[5]['mes']) ? '' : $totalpedidospormes[5]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[6]['mes']) ? '' : $totalpedidospormes[6]['mes']) .  "'" . "," .
                            "'" . (empty($totalpedidospormes[7]['mes']) ? '' : $totalpedidospormes[7]['mes']) . "'" . "," . "'" . (empty($totalpedidospormes[8]['mes']) ? '' : $totalpedidospormes[8]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[9]['mes']) ? '' : $totalpedidospormes[9]['mes']) . "'" . "," . "'" . (empty($totalpedidospormes[10]['mes']) ? '' : $totalpedidospormes[10]['mes']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[11]['mes']) ? '' : $totalpedidospormes[11]['mes']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalpedidospormes[0]['total']) ? '0' : $totalpedidospormes[0]['total']) . "'" . "," . "'" . (empty($totalpedidospormes[1]['total']) ? '0' : $totalpedidospormes[1]['total']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[2]['total']) ? '0' : $totalpedidospormes[2]['total']) . "'" . "," .  "'" . (empty($totalpedidospormes[3]['total']) ? '0' : $totalpedidospormes[3]['total']) . "'"  . "," .
                            "'" . (empty($totalpedidospormes[4]['total']) ? '0' : $totalpedidospormes[4]['total']) . "'" . "," .  "'" . (empty($totalpedidospormes[5]['total']) ? '0' : $totalpedidospormes[5]['total']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[6]['total']) ? '0' : $totalpedidospormes[6]['total']) .   "'" . "," .
                            "'" . (empty($totalpedidospormes[7]['total']) ? '0' : $totalpedidospormes[7]['total']) . "'" . "," . "'" . (empty($totalpedidospormes[8]['total']) ? '0' : $totalpedidospormes[8]['total']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[9]['total']) ? '0' : $totalpedidospormes[9]['total']) . "'" . "," . "'" . (empty($totalpedidospormes[10]['total']) ? '0' : $totalpedidospormes[10]['total']) . "'" . "," .
                            "'" . (empty($totalpedidospormes[11]['total']) ? '0' : $totalpedidospormes[11]['total']) . "'")
                        ?>];
        var barColors = [
            "#ff595e",
            "#ffca3a",
            "#8ac926",
            "#1982c4",
            "#6a4c93",
            "#ff924c",
            "#00bbf9",
            "#cdb4db",
            "#2ec4b6",
            "#e71d36",
            "#ff9f1c",
            "#8338ec"
        ];

        new Chart("circularpedidos", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de pedidos"
                }
            }
        });
    }

    function graficopedidosxano() {
        var xValues = [<?php echo ("'" . (empty($totalmontopedidos[0]['ano']) ? '' : $totalmontopedidos[0]['ano']) . "'" . "," . "'" . (empty($totalmontopedidos[1]['ano']) ? '' : $totalmontopedidos[1]['ano']) . "'" . "," .
                            "'" . (empty($totalmontopedidos[2]['ano']) ? '' : $totalmontopedidos[2]['ano']) . "'")
                        ?>];
        var yValues = [<?php echo ("'" . (empty($totalmontopedidos[0]['total']) ? '0' : $totalmontopedidos[0]['total']) . "'" . "," . "'" . (empty($totalmontopedidos[1]['total']) ? '0' : $totalmontopedidos[1]['total']) . "'" . "," .
                            "'" . (empty($totalmontopedidos[2]['total']) ? '0' : $totalmontopedidos[2]['total']) . "'")
                        ?>];
        var barColors = ["#8ecae6", "#219ebc", "#023047"];

        new Chart("barrapedidossxano", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                    label: "Cotizaciones por año"
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Cantidad total de cotizaciones"
                },
                legend: {
                    position: 'top'
                }
            }
        });
    }
</script>
<?php
$this->endSection('javascript');
?>