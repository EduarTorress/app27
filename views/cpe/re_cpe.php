<?php
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
                                <button type="submit" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="resultado">
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
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
    }

    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    function search() {
        axios.get('/cpe/lista', {}).then(function(respuesta) {
            // var table = new DataTable('#tblvtasxsend');
            // table.destroy()
            // console.log(respuesta.data.listado)
            // $("#tblvtasxsend tbody tr").remove();
            // $("#btnExportpdf").remove();
            // $("#btnExportexcel").remove();
            listado = respuesta.data.listado
            // if (listado !== undefined) {
            //     if (listado.length > 0) {
            //         for (var i = 0; i < listado.length; i++) {
            //             var tr = `<tr class="fila">
            //                 <td>` + listado[i].ndoc + `</td>
            //                 <td>` + listado[i].fech + `</td>
            //                 <td>` + listado[i].razo + `</td>
            //                 <td>` + listado[i].mone + `</td>
            //                 <td>` + Number(listado[i].valor).toFixed(2) + `</td>
            //                 <td> 0.00 </td>
            //                 <td>` + Number(listado[i].igv).toFixed(2) + `</td>
            //                 <td>` + Number(listado[i].impo).toFixed(2) + `</td>`;
            //             $('#tblvtasxsend tbody').append(tr);
            //         }
            //     } else {
            //         $('#tblvtasxsend tbody').append("<tr><td class='text-center' colspan='9'>No se encontraron resultados</td></tr>");
            //     }
            // }
            // $('input[type=search]').css('color', 'black');
            // $('.dataTables_filter').css('color', 'black');
            // $('.paginate_button').css('background-color', '#006CA7');
            // $('.previous').removeClass('disabled');
            detalletabla = [
                ['Numero', 'ndoc',
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
                ['Fecha', 'fech',
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
                        ['type', 'fech']
                    ])],
                ],
                ['Razon Social', 'razo',
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
                ['Moneda', 'mone',
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
                ['Grav.', 'valor',
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"']
                    ])],
                    [new Map([
                        ['class', 'text-end'],
                        ['width', ''],
                        ['id', ''],
                        ['attr', 'data-footer-formatter="formatTotal"'],
                        ['type', 'number']
                    ])],
                ],
                ['IGV', 'igv',
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
                    ])],
                ],
                ['Importe', 'impo',
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
                    ])],
                ]
            ]
            cargartabla(listado, "table", detalletabla);
            reportetablebt("#table")
        }).catch(function(error) {
            toastr.error(error,'Mensaje del Sistema')
        });
    }
</script>
<?php
$this->endSection('javascript');
?>