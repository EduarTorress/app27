<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php

use App\View\Components\ModalDestinatarioComponent;
use App\View\Components\ModalDirecciones;
use App\View\Components\ModalImprimir;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalProveedorComponent;
use App\View\Components\ModalTransportistaComponent;

$clie = new ModalDestinatarioComponent();
echo $clie->render();
$ve = new ModalTransportistaComponent();
echo $ve->render();
$odirecciones = new ModalDirecciones();
echo $odirecciones->render();
$prod = new ModalProductoComponent();
echo $prod->render();
$oimp = new ModalImprimir();
echo $oimp->render();
$oprov = new ModalProveedorComponent();
echo $oprov->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label ">Fecha de Emisión:</label>
                                <input type="date" id="txtFechaEmision" class="form-control " value="<?php echo date("Y-m-d") ?>" style="width:40%;">
                            </div>
                            <input type="hidden" name="idautoc" id="idautod" value="0">
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Fecha de Traslado:</label>
                                <input type="date" id="txtFechaTraslado" class="form-control " value="<?php echo  date("Y-m-d") ?>" style="width:45%;">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-0 col-form-label">Partida:</label>
                                <input type="text" class="form-control " id="txtptopartida" style="width:80%" value="<?php echo $_SESSION['gene_ptop']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Proveedor
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="input-group">
                                    <input type="hidden" id="txtIdauto" value="<?php echo "" ?>">
                                    <input type="hidden" id="txtidproveedor" value="<?php "" ?>">
                                    <input type="hidden" id="txtUbigeoproveedor" value="<?php echo isset($_SESSION['proveedor']['ubigprov']) ?  $_SESSION['proveedor']['ubigprov'] : '' ?>">
                                    <input type="hidden" id="txtrucproveedor" value="<?php echo isset($_SESSION['proveedor']['rucprov']) ?  $_SESSION['proveedor']['rucprov'] : '' ?>">
                                    <input type="text" class="form-control form-control-sm" id="txtproveedor" placeholder="Nombre Proveedor" disabled value="<?php echo isset($_SESSION['proveedor']['razoprov']) ?  $_SESSION['proveedor']['razoprov'] : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Dirección de Punto de Llegada" id="txtptollegada" disabled value="<?php echo isset($_SESSION['proveedor']['direprov']) ?  trim($_SESSION['proveedor']['direprov']) . '' . trim($_SESSION['proveedor']['ciudprov']) : '' ?>">
                                    <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_direcciones"><i style="color:black" class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
                                </div>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Referencia" id="txtreferencia" maxlength="60" onkeyup="mayusculas(this);" value="<?php echo isset($_SESSION['proveedor']['referencia']) ?  trim($_SESSION['proveedor']['referencia'])  : '' ?>">
                                    <button class="btn btn-outline-light" role="button"><i style="color:black" class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Transportista
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group">
                                        <input type="hidden" id="txtIdTransportista" value="<?php echo isset($_SESSION['transportista']['txtIdTransportista']) ?  $_SESSION['transportista']['txtIdTransportista'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttransportista" placeholder="Transportista" disabled value="<?php echo isset($_SESSION['transportista']['txttransportista']) ?  $_SESSION['transportista']['txttransportista'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttruc" placeholder="Ruc" disabled value="<?php echo isset($_SESSION['transportista']['txtruc']) ?  $_SESSION['transportista']['txtruc'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_transportista"><i style="color:black" class="fas fa-user-alt"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtplaca" placeholder="Placa 01" disabled value="<?php echo isset($_SESSION['transportista']['txtPlaca']) ?  $_SESSION['transportista']['txtPlaca'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" placeholder="Placa 02" disabled id="txtPlaca1" value="<?php echo isset($_SESSION['transportista']['txtPlaca1']) ?  $_SESSION['transportista']['txtPlaca1'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txtmarca" placeholder="Marca" disabled value="<?php echo isset($_SESSION['transportista']['txtmarca']) ?  $_SESSION['transportista']['txtmarca'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target=""><i style="color:black" class="fa fa-truck"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtChoferVehiculo" placeholder="Chofer" disabled value="<?php echo isset($_SESSION['transportista']['txtChoferVehiculo']) ?  $_SESSION['transportista']['txtChoferVehiculo'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txtbrevete" placeholder="Brevete" disabled value="<?php echo isset($_SESSION['transportista']['txtbrevete']) ?  $_SESSION['transportista']['txtbrevete'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_choferes"><i style="color:black" class="fa fa-id-card-o" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="txtregmtc" placeholder="Registro MTC" disabled value="<?php echo isset($_SESSION['transportista']['txtregmtc']) ?  $_SESSION['transportista']['txtregmtc'] : '' ?>">
                                        <input type="text" class="form-control form-control-sm" id="txttipot" placeholder="Tipo de Transporte" disabled value="<?php echo isset($_SESSION['transportista']['txttipot']) ?  $_SESSION['transportista']['txttipot'] : '' ?>">
                                        <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target=""><i style="color:black" class="fa fa-truck"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-success card-outline" style="width:max-content; width:auto;">
                                        <div class="col-12" id="detalle">
                                            <div class="table-responsive">
                                                <table class="table table-sm small table table-hover" id="griddetalle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width:2%">Opciones</th>
                                                            <th scope="col" style="width:3%;" class="codigo">Código</th>
                                                            <th scope="col" style="width:28%">Producto</th>
                                                            <th scope="col" style="width:5%">U.M.</th>
                                                            <th scope="col" class="text-center" style="width:5%">Cantidad</th>
                                                            <th scope="col" class="text-center" style="width:5%">Peso KG</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="carritoventas">
                                                        <?php $i = 0; ?>
                                                        <?php foreach ($carritov as $indice => $item) : ?>
                                                            <?php if ($item['activo'] == 'A') { ?>
                                                                <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>);" onblur="actualizarProducto(this,<?php echo $indice ?>);">
                                                                    <?php
                                                                    $parametro1 = $item['descripcion'];
                                                                    $parametro2 = $item['coda'];
                                                                    $parametro3 = $item['unidad'];
                                                                    $parametro4 = $item['stock'];
                                                                    $parametro5 = $item['precio1'];
                                                                    $parametro6 = $item['precio2'];
                                                                    $parametro7 = $item['precio3'];
                                                                    $parametro8 = $item['costo'];
                                                                    $parametro9 = $item['cantidad'];
                                                                    $parametro10 = 1;
                                                                    $parametro11 = $indice;
                                                                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                                                                    $cadena_json = json_encode($parametros);
                                                                    ?>
                                                                    <td><button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                                                                        <!-- <button class="btn btn-success" onclick='editaritem(<?php echo $cadena_json ?>);'><a style="color:white" class="fas fa-edit"></a></button> -->
                                                                    </td>
                                                                    <td class="codigo"><?php echo $item['coda'] ?></td>
                                                                    <td class="descri"><?php echo $item['descripcion'] ?></td>
                                                                    <td class="unidad"><?php echo $item['unidad'] ?></td>
                                                                    <td class="cantidad" style="text-align: center;" onclick="funcionEnterCant(this,<?php echo $indice ?>)" onkeypress="return isNumber(event);" contenteditable="true" name="cantidad"><?php echo round($item['cantidad'], 4) ?></td>
                                                                    <td class="precio" style="text-align: center;" id="precio" onkeypress="return isNumber(event);" contenteditable="false" name="precio"><?php echo round($item['peso'], 5) ?></td>
                                                                    <!-- <td class="text-center" class="total" contenteditable="true"></td> -->
                                                                    <?php $i++; ?>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div><br>
                                            <div class="col-lg-12">
                                                <div class="card card-success card-outline" style="width:auto;">
                                                    <div class="row">
                                                        <div class="col-7 align-items-start">
                                                            <br>
                                                            <div class="input-group">
                                                                <!-- "/productos/index/3" para index de productos de ventas  -->
                                                                <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                                                                <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="limpiarGuia()">Limpiar</button>
                                                                <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="Guia();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 align-items-start">
                                                            <div class="input-group mb-3" style="width: 85%;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                                                                </div>
                                                                <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo $items; ?>" aria-describedby="inputGroup-sizing-sm" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 align-items-start">
                                                            <div class="input-group" style="width: 90%;">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text text-sm" id=""><strong>Peso:</strong></span>
                                                                </div>
                                                                <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo  $total ?>" readonly>
                                                                <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cargamodal">
</div>
<div id="cargamodalcompras"></div>
<style>
    td>input {
        height: 20px;
    }

    iframe {
        width: 100%;
    }
</style>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        calcularPesoTotal();
        $(".codigo").css("display", "none");
        // buscarProducto();
        // buscarTransportista();
        // buscarProveedor();
    }

    function agregarunitemVenta(datos) {
        // presentaciones = JSON.parse(datos.parametro11);
        // precio = presentaciones[0]['epta_prec'];
        // unidad = presentaciones[0]['pres_desc']
        // cantequi = presentaciones[0]['epta_cant'];
        // eptaidep = presentaciones[0]['epta_idep'];

        stock = parseFloat(datos.parametro4.toFixed(2))
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        data.append("txtunidad", datos.parametro3);
        data.append("txtpeso", datos.parametro9);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("presentaciones", datos.parametro11);
        data.append("presseleccionada", 0);
        data.append("cantequi", 1);
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        axios.post('/guiasd/agregaritem', data)
            .then(function(respuesta) {
                //window.location.href = '/vtas/index';
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                //$("#griddetalle tr:last").focus()
                var a = $("#griddetalle tr:last td:eq(4)").each(function() {
                    // console.log($(this).text());
                    $(this).focus();
                    $(this).click();
                });
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        errors = error.response.data.message;
                        toastr.error(errors, "Mensaje del Sistema")
                    }
                }
            });
    }

    function quitaritem(pos) {
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/guiasd/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del Sistema');
            });
    }

    ////
    $('#modal_proveedor').on('shown.bs.modal', function() {
        $('#txtbuscarprov').focus();
    });

    $('#modal_transportista').on('shown.bs.modal', function() {
        $('#txtbuscarTr').focus();
    });

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        i = 0;
        $("#griddetalle tbody > tr").each(function(index) {
            i = i + 1;
            var cantidad = Number($(this).find('.cantidad').text());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').text());
            pesoTotal.push(peso);
            var pesot = cantidad * peso;
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#total").val(total.toFixed(2));
            $("#totalitems").val(i)
        } else {
            $("#total").val("0.00");
        }
    }

    function Guia() {
        grabarGuia();
    }

    function grabarGuia() {
        if (validar() === false) {
            return;
        }
        Swal.fire({
            title: '¿Registrar Guia?',
            text: "Se guardará en la base de datos",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                data = new FormData();
                data.append("idProveedor", $("#txtidproveedor").val());
                data.append("proveedor", $("#txtproveedor").val());
                data.append("rucproveedor", $("#txtrucproveedor").val());
                data.append("txtptopartida", $("#txtptopartida").val());
                data.append("txtptollegada", $("#txtptollegada").val());
                data.append("txtUbigeoproveedor", $("#txtUbigeoproveedor").val());
                data.append("txtPlaca1", "");
                data.append("txtreferencia", $("#txtreferencia").val());
                data.append("txtFechaEmision", $("#txtFechaEmision").val())
                data.append("txtFechaTraslado", $("#txtFechaTraslado").val())
                data.append("txtPlaca", $("#txtplaca").val());
                data.append("txtmarca", $("#txtmarca").val());
                data.append("txtBrevete", $("#txtbrevete").val());
                data.append("txtructransportista", $("#txttruc").val());
                data.append("txttransportista", $("#txttransportista").val());
                data.append("txtIdTransportista", $("#txtIdTransportista").val());
                data.append("txtChoferVehiculo", $("#txtChoferVehiculo").val());
                data.append("txtregmtc", $("#txtregmtc").val());
                data.append("txttipot", $("#txttipot").val())
                axios.post("/guiasd/registrar", data)
                    .then(function(respuesta) {
                        toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                        var cruta = '/guiasd/imprimirdirecto/';
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', cruta, true);
                        xhr.responseType = 'blob';
                        xhr.onload = function(e) {
                            if (this.status == 200) {
                                var w = screen.width;
                                url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                                if (w <= 768) {
                                    var req = new XMLHttpRequest();
                                    req.open("GET", url, true);
                                    req.responseType = "blob";
                                    req.onload = function(event) {
                                        var blob = req.response;
                                        // console.log(blob.size);
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = respuesta.data.ndoc + ".pdf"
                                        link.click();
                                    };
                                    req.send();
                                } else {
                                    $("#pdfguia").attr("src", url)
                                    $("#abrirguia").click();
                                }
                            }
                        };
                        xhr.send();
                        nuevo();
                    }).catch(function(error) {
                        e = error['response']['data']
                        result = []
                        for (var i in e) {
                            result.push([i, e[i]]);
                        }
                        result.forEach(function(numero) {
                            toastr.error(numero[1], 'Mensaje del sistema')
                        });
                    });
            }
        });
    }

    function validar() {
        idDestinatario = $("#txtidproveedor").val();
        if (idDestinatario == "") {
            toastr.warning("Ingrese el Remitente", 'Mensaje del Sistema')
            return false;
        }
        idtr = $("#txtIdTransportista").val();
        if (idtr == "") {
            toastr.warning("Ingrese el Transportista", 'Mensaje del Sistema')
            return false;
        }
        idtotal = $("#total").val();
        if (idtotal == "" || idtotal == '0.00') {
            toastr.warning("Ingrese el Peso de Los Productos ", 'Mensaje del Sistema')
            return false;
        }
        return true
    }

    // $("#abrirDireccion").on("click", function() {
    //     id = $("#txtIdRemitente").val()
    //     if (id == '') {
    //         toastr.error("Seleccione un remitente", 'Mensaje del sistema')
    //         return
    //     }
    //     axios.get('/direccion/lista1', {
    //         "params": {
    //             'idremitente': id
    //         }
    //     }).then(function(respuesta) {
    //         const contenido_tabla = respuesta.data;
    //         $('#lista').html(contenido_tabla);
    //         $("#modal_direcciones").modal('show');
    //     }).catch(function(error) {
    //         toastr.error('Error al cargar el listado de Direcciones' + error, 'Mensaje del sistema')
    //     });
    // });

    function seleccionarDireccion(dire, ciud, ubig) {
        document.getElementById("txtptollegada").value = dire.trimEnd() + ' ' + ciud.trimEnd()
        document.getElementById("txtUbigeoproveedor").value = ubig;
        // idRemitente = $("#txtidproveedor").val()
        // razo = $("#txtproveedor").val()
        // direccion = dire.trimEnd() + ' ' + ciud.trimEnd()
        // ubigeo = ubig
        $("#modal_direcciones").modal('hide');
    }

    function limpiarGuia() {
        limpiardetallegc();
        document.getElementById('txtidproveedor').value = "";
        document.getElementById('txtUbigeoproveedor').value = "";
        document.getElementById('txtrucproveedor').value = "";
        document.getElementById("txtplaca").value = "";
        document.getElementById("txtPlaca1").value = "";
        document.getElementById("txtbrevete").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txtIdauto").value = "";
        document.getElementById("txttransportista").value = "";
        document.getElementById("txttruc").value = "";
        document.getElementById("txtmarca").value = "";
        document.getElementById("txtregmtc").value = "";
        document.getElementById("txttipot").value = "";
        document.getElementById("totalitems").value = "00";
        document.getElementById("total").value = "0.00";
        $("#txtptollegada").val("");
        $("#griddetalle tbody tr").remove();
        <?php
        session()->remove('proveedor');
        session()->remove('transportista');
        session()->remove('guiad');
        session()->set('carritogc', []);
        ?>
        window.location.href = '/guiasd/index';
    }

    function limpiardetallegc() {
        axios.get('/guiasd/limpiar', {})
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                // window.location.href = '/guiasr/index';
            }).catch(function(error) {
                toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            });
    }

    $('#modal_productos').on('shown.bs.modal', function() {
        $('#txtbuscarProducto').focus();
    });

    function nuevo() {
        document.getElementById('txtidproveedor').value = "";
        document.getElementById('txtproveedor').value = "";
        document.getElementById('txtUbigeoproveedor').value = "";
        document.getElementById('txtrucproveedor').value = "";
        document.getElementById("txtplaca").value = "";
        document.getElementById("txtbrevete").value = "";
        document.getElementById("txtChoferVehiculo").value = "";
        document.getElementById("txtIdauto").value = "";
        document.getElementById("txttransportista").value = "";
        document.getElementById("txttruc").value = "";
        document.getElementById("txtmarca").value = "";
        document.getElementById("txtregmtc").value = "";
        document.getElementById("txttipot").value = "";
        document.getElementById("totalitems").value = "00";
        document.getElementById("total").value = "0.00";
        $("#txtptollegada").val("");
        $("#griddetalle tbody tr").remove();
    }

    function seleccionarproveedor(datos) {
        document.getElementById("txtidproveedor").value = datos.parametro1;
        document.getElementById("txtproveedor").value = datos.parametro2;
        document.getElementById('txtrucproveedor').value = datos.parametro3;
        document.getElementById('txtptollegada').value = datos.parametro5 + ' ' + datos.parametro6;
        document.getElementById('txtUbigeoproveedor').value = datos.parametro9;
        axios.get('/proveedores/seleccionar', {
            "params": {
                'datos': datos
            }
        }).then(function(respuesta) {
            $('#modal_proveedor').modal('toggle');
            axios.get('/direccion/lista1', {
                "params": {
                    'idremitente': datos.parametro1
                }
            }).then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#lista').html(contenido_tabla);
                //$("#modal_direcciones").modal('show');
            }).catch(function(error) {
                toastr.error('Error al cargar el listado de direcciones' + error, 'Mensaje del sistema')
            });
        }).catch(function(error) {
            $('#modal_proveedor').modal('toggle');
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    $(document).ready(function() {
        $(".codigo").css("display", "none");
    });

    //Poner editable luego de quitar el focus a los campos.
    $("#body").on('click', function() {
        $('#1').attr('contenteditable', 'true');
        $('#2').attr('contenteditable', 'true');
        $('#3').attr('contenteditable', 'true');
    });

    function actualizarProducto(o, i) {
        $(o).each(function() {
            var _tr = $(o);
            // cmbpresentacion = _tr.find("td").eq(3).find("select").val();
            // cmbpresentacion = cmbpresentacion.split("-");
            // textpresentacion = _tr.find("td").eq(3).find("select option:selected").text();
            // textpresentacion = textpresentacion.split("-");
            const data = new FormData();
            var id = _tr.find("td").eq(1).html();
            data.append("txtcantidad", _tr.find("td").eq(4).html());
            data.append("txtpeso", _tr.find("td").eq(5).html());
            // data.append("presseleccionada", cmbpresentacion[0]);
            // data.append("unidad", textpresentacion[0].trim());
            // data.append("cantequi", textpresentacion[1]);
            data.append("indice", i);
            axios.post('/guiasd/EditarUno', data)
                .then(function(respuesta) {
                    //console.log('correctamente editado')
                }).catch(function(error) {
                    console.log(error)
                });
        });
    }

    function funcionEnterCant(o, i) {
        //Eliminamos los id anteriores
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        $(o).attr('id', '1');

        var tr = $(o).parent();
        tr.find("td").eq(5).attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#1').removeClass('focus');
                $('#1').removeAttr('contenteditable');
                $('#2').focus().select();
            }
        });

        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });

        var scop = document.getElementById("3");
        scop.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    }

    // Evento enter con el cantidad
    $("table tbody tr td:nth-child(6)").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        $(this).attr('id', '2')
        var cant = document.getElementById("2");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#2').removeClass('focus');
                $('#2').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    // Evento enter con precio
    $("table tbody tr td:nth-child(7)").click(function() {
        var id = document.getElementById("3");
        $(id).removeAttr('id', '3')
        $(this).attr('id', '3')
        var prec = document.getElementById("3");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                $('#3').removeClass('focus');
                $('#3').removeAttr('contenteditable');
                $('#body').trigger('click');
            }
        });
    });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        var _tr = $(o);
        var peso = _tr.find("td").eq(5).html();
        var cant = _tr.find("td").eq(4).html();
        var subt = parseFloat(peso) * parseFloat(cant);
        // var campo = _tr.find("td").eq(6);
        if (isNaN(subt)) {
            toastr.info("Dígite un número correcto", 'Mensaje del Sistema')
        }
        // campo.html(subt.toFixed(2));
        // var total_col1 = 0;
        // $('table tbody').find('tr').each(function(i, el) {
        //     //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        //     total_col1 += parseFloat($(this).find('td').eq(6).text());
        // });
    }

    function verificarValores(o) {
        calcularsubtotal(o);
        calcularPesoTotal();
    }

    function calcularPesoTotal() {
        var cantidades = [];
        var pesos = [];
        var pesoTotal = [];
        var total = 0;
        $("#griddetalle tbody > tr").each(function(index) {
            var cantidad = Number($(this).find('.cantidad').text());
            cantidades.push(cantidad);
            var peso = Number($(this).find('.precio').text());
            pesoTotal.push(peso);
            var pesot = cantidad * peso;
            // console.log(pesot)
            total += pesot;
        });
        if (!isNaN(total)) {
            $("#total").val(total.toFixed(2));
        } else {
            $("#total").val("0.00");
        }
    }
</script>
<?php
$this->endSection('javascript');
?>