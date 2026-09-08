<?php

use App\View\Components\ComboAnosComponent;

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
                            <form class="form-inline" id="form-search"><br>
                                <label class="my-1 mr-2" for="">Mes</label>
                                <?php
                                $mes = new ComboAnosComponent('');
                                echo $mes->renderreportmes();
                                ?>
                                <label class="my-1 mr-2" for="">Año</label>
                                <?php
                                $ano = new ComboAnosComponent('');
                                echo $ano->renderreport();
                                ?>
                                <button type="submit" class="btn btn-primary my-1" id="btnconsultar">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="resultado">
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
        $("#titulo").html("<?php echo $titulo ?>");
    }

    function search() {
        var mes = document.getElementById("cmbmes").value;
        var ano = document.getElementById("cmbano").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/pagosplanilla/buscarreporte', {
            "params": {
                "mes": mes,
                "ano": ano,
                "descripcionmes": $("#cmbmes option:selected").text()
            }
        }).then(function(respuesta) {
            listado = (respuesta.data.listado);
            detalletabla = [
                ['Usuario', 'nomb',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Tipo', 'cargo',
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])],
                ],
                ['Año', 'ano',
                    [new Map([
                        ['class', 'text-center'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])]
                ],
                ['Periodo', 'mes',
                    [new Map([
                        ['class', 'text-center'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                    ])],
                    [new Map([
                        ['class', ''],
                        ['width', ''],
                        ['id', ''],
                        ['attr', ''],
                        ['type', 'text']
                    ])]
                ],
                ['Sueldo Laboral', 'sueldo',
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                    ])],
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                        ['type', 'number']
                    ])]
                ],
                ['Pago Procesado', 'saldopago',
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                    ])],
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                        ['type', 'number']
                    ])]
                ],
                ['Saldo Pendiente', 'saldopendiente',
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                    ])],
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                        ['type', 'number']
                    ])]
                ]
                // ['', 'buttons',
                //     [new Map([
                //         ['class', ''],
                //         ['text', ''],
                //         ['id', ''],
                //         ['attr', ''],
                //     ])],
                //     [
                //         [new Map([
                //             ['class', 'btn btn-success'],
                //             ['onclick', 'imprimir'],
                //             ['text', 'Grabar'],
                //             ['id', ''],
                //             ['attr', ''],
                //         ])],
                //         [new Map([
                //             ['class', 'btn btn-danger'],
                //             ['onclick', 'eliminar'],
                //             ['text', 'Eliminar'],
                //             ['id', ''],
                //             ['attr', ''],
                //         ])]
                //     ],
                // ]
            ]
            cargartabla(listado, "table", detalletabla);
            reportetablebt("#table");
            $("#btnconsultar").attr('disabled', false);
        }).catch(function(error) {
            console.log(error);
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>