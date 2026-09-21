<!-- Modal ventas -->
<div class="modal fade" id="modal_ventas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header" id="header_modal" style="background-color:#0c1c3f;">
                <h4 class="modal-title" id="exampleModalLabel">Lista de Ventas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="guias">
                <div class="input-group mb-3">
                    <div class="col-12" id="searchV">
                        <div class="table-responsive">
                            <table id="tablaVentas" class="table table-bordered table-hover table-sm small">
                                <thead>
                                    <tr>
                                        <th>Nro Nota</th>
                                        <th>Fecha</th>
                                        <th>RUC</th>
                                        <th>Cliente</th>
                                        <th>Importe total</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listado as $item) : ?>
                                        <tr>
                                            <td><?php echo $item['ndoc'] ?></td>
                                            <td><?php echo $item['fech'] ?></td>
                                            <td><?php echo $item['nruc'] ?></td>
                                            <td><?php echo $item['razo'] ?></td>
                                            <td><?php echo $item['impo'] ?></td>
                                            <?php
                                            $parametro1 = $item['idauto'];
                                            $parametro2 = $item['fech'];
                                            $parametro3 = $item['idclie'];
                                            $parametro4 = $item['ndoc'];
                                            $parametro5 = $item['nruc'];
                                            $parametro6 = $item['rcom_mens'];
                                            $parametro7 = $item['impo'];
                                            $parametro8 = $item['ndni'];
                                            $parametro9 = $item['tcom'];
                                            $parametro10 = $item['form'];
                                            $parametros = compact(
                                                'parametro1',
                                                'parametro2',
                                                'parametro3',
                                                'parametro4',
                                                'parametro5',
                                                'parametro6',
                                                'parametro7',
                                                'parametro8',
                                                'parametro9',
                                                'parametro10'
                                            );
                                            $cadena_json = json_encode($parametros);
                                            ?>
                                            <td class="text-center">
                                                <a class="btn btn-success" role="button" onclick='seleccionarVenta(<?php echo $cadena_json ?>)'>
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#tablaVentas').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [{
            targets: 5,
            orderable: false,
            searchable: false
        }]
    });
    // document.getElementById("txtidauto").value = datos.parametro1;
    // // document.getElementById("txtidcliente").value = datos.parametro4;
    // // document.getElementById("txtfecha").value = datos.parametro2;
    // // document.getElementById("txtfechav").value = datos.parametro2;
    // // document.getElementById("txtruccliente").value = datos.parametro9;
    // // document.getElementById("txtguia").value = datos.parametro12;
    // // document.getElementById("txtdireccion").value = datos.parametro7;
    // // document.getElementById("txtdnicliente").value = datos.parametro13;
    // // axios.get('/vtas/listarDetalleCanje', {
    // //     "params": {
    // //         "idguia": datos.parametro1
    // //     }
    // // }).then(function(respuesta) {
    // //     const contenido_tabla = respuesta.data;
    // //     $('#detalle').html(contenido_tabla);
    // //     calcularIGV()
    // // }).catch(function(error) {
    // //     toastr.error('Error al cargar el listado','Mensaje del Sistema')
    // // });
    // $("#modal_ventas").modal('hide');
    // // $(".codigo").css("display", "none");
</script>