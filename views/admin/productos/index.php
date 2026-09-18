<?php

use App\View\Components\ModalGestionStockComponent;
use App\View\Components\Modaload;
?>
<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$prod = new Modaload();
echo $prod->render();
$mdGs = new ModalGestionStockComponent();
echo $mdGs->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="" id="form-search">
                            <div class="container">
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="optradios" value="C" onchange="obtener()" checked>Combustible&nbsp;
                                            </label>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="optradios" value="O" onchange="obtener()">Todos&nbsp;
                                            </label>
                                        </div>
                                        <!-- <div class="col-sm-2">
                                            <label class="radio-inline">
                                                <input type="radio" name="optradios" value="codigo1" onchange="obtener()">Código Fab.&nbsp;
                                            </label>
                                        </div> -->
                                        <div class="col-8" style="display:inline-block;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" style="width:40%;" name="txtbuscar" id="txtbuscar" placeholder="Ingrese nombre o código de Producto" onkeyup="mayusculas(this)" aria-label="Buscar" value="<?php echo session()->get('busqueda') ?>">
                                                <span class="input-group-btn">
                                                    <button type="submit" id="buscar" class="btn btn-outline-secondary">Buscar</button>
                                                </span>&nbsp;
                                                <!-- <?php
                                                        $opt = session()->get('tiposel', '0');
                                                        if ($opt == '1') : ?>
                                                    <a href="/pedidos/listarpedido" class="btn btn-success">Ver Carrito</a>
                                                <?php endif; ?> -->
                                                <?php if ($opt == '5') : ?>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdStockProducto">
                                                        Ver Gestión Stock
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($opt == '6') : ?>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdStockProducto">
                                                        Ingresar Diferencia
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12" id="search">
                <?php
                $lista = session()->get('lista', []);
                if (isset($lista['lista']['items'])) {
                    $lista = new \App\View\Components\Listaproductos();
                    echo $lista->render();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div id="item" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-contenido">
        </div>
    </div>
</div>
<div id="modal-mantenimiento" class="modal fade" tabindex="-1" data-keyboard="false" aria-hidden="true">
</div>
<div id="modal-detalle" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="modal-contenidod">
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
        moverCursorFinalTexto("txtbuscar");
        titulo("<?php echo $titulo ?>");
    }

    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        buscar();
    });

    function buscar() {
        var abuscar = document.getElementById("txtbuscar").value;
        if (abuscar.length == 0) {
            toastr.info("Ingrese Nombre de Producto a Buscar", 'Mensaje del Sistema')
            return;
        }
        var noption = obtener();
        $("#buscar").attr('disabled', true);
        // $('#loading').modal('show');
        axios.get('/productos/lista', {
            "params": {
                "cbuscar": abuscar,
                "option": noption
            }
        }).then(function(respuesta) {
            $("#buscar").attr('disabled', false);
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
        }).catch(function(error) {
            $("#buscar").attr('disabled', false);
            // $('#loading').modal('hide');
            console.log(error);
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function limpiarTodo() {
        var elements = document.getElementsByTagName("input");
        for (var ii = 0; ii < elements.length; ii++) {
            if (elements[ii].type == "text") {
                elements[ii].value = "";
            }
        }
    }

    function agregar_producto(datos) {
        var id = 'z';
        const url = '/pedidos/itemdetalle/' + id;
        axios.get(url)
            .then(function(respuesta) {
                $("#modal-contenido").html(respuesta.data);
                $('#txtcodigo').val(datos.parametro2)
                $('#txtdescripcion').val(datos.parametro1);
                $('#txtunidad').val(datos.parametro3);
                $('#txtstock').val(parseFloat(datos.parametro4.toFixed(2)));
                $('#txtprecio').val(datos.parametro7);
                $('#precio1').val(datos.parametro5);
                $('#precio2').val(datos.parametro6);
                $('#precio3').val(datos.parametro7);
                $("#costo").val(datos.costo);
                $('#txtcantidad').val("");
                $("#tipoproducto").val(datos.tipro);
                <?php if (!empty($_SESSION['config']['precioespecial'])) : ?>
                    var array = [datos.parametro5, datos.parametro6, datos.parametro7];
                <?php else : ?>
                    var array = [datos.parametro5, datos.parametro7];
                <?php endif; ?>
                cargarprecios("cmbprecios", array);
                $('#txtprecio').val($("#cmbprecios option:selected").text());
                $('#item').modal('show');
            }).catch(function(error) {
                toastr.error('Error al mostrar modal', 'Mensaje del Sistema');
            });
    }

    function agregarItem() {
        var precio = document.querySelector("#txtprecio").value;
        var cantidad = document.querySelector("#txtcantidad").value;
        var combo = document.querySelector("#cmbprecios");
        var cantidad = document.querySelector("#txtcantidad").value;
        var stock = document.querySelector("#txtstock").value;
        var precios = combo.options[combo.selectedIndex];
        const data = new FormData();
        data.append('txtcodigo', document.getElementById("txtcodigo").value);
        data.append("txtdescripcion", document.querySelector("#txtdescripcion").value);
        data.append("txtunidad", document.querySelector("#txtunidad").value);
        data.append("txtprecio", document.querySelector("#txtprecio").value);
        data.append("txtcantidad", document.querySelector("#txtcantidad").value);
        data.append("precio1", document.querySelector("#precio1").value);
        data.append("precio2", document.querySelector("#precio2").value);
        data.append("precio3", document.querySelector("#precio3").value);
        data.append("stock", document.querySelector("#txtstock").value);
        data.append("tipoproducto", document.querySelector("#tipoproducto").value);
        data.append("costo", $("#costo").val());
        data.append("opt", 1)
        axios.post('/pedidos/agregaritem', data)
            .then(function(respuesta) {
                cerrarventana('detalleitem', '#item')
                $('#totalpedido').html(respuesta.data.total);
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status === 422) {
                        // toastr.error(error.response.data.message, "Error del sistema");
                        // console.log(error.response.data.message)
                        if (error.response.data.hasOwnProperty('errors')) {
                            mostrarErrores("detalleitem", error.response.data.errors);
                        } else {
                            toastr.error(error.response.data.message, "Error");
                        }
                    }
                }
                document.querySelector("#txtcantidad").focus();
            });
    }

    function cargarprecios(domElement, array) {
        var select = document.getElementsByName(domElement)[0];
        const $select = document.querySelector("#cmbprecios");
        for (let i = $select.options.length; i >= 0; i--) {
            $select.remove(i);
        }
        for (value in array) {
            var option = document.createElement("option");
            option.text = array[value];
            select.add(option);
        }
    }

    function obtener() {
        let vdvto = 0;
        if (document.getElementsByName('optradios')[0].checked) {
            vdvto = 'C';
        }
        if (document.getElementsByName('optradios')[1].checked) {
            vdvto = 'O';
        }
        return vdvto;
    }

    $('#item').on('shown.bs.modal', function() {
        $('#txtcantidad').focus();
        $('#txtcantidad').select();
    });

    function eliminarProducto(datos) {
        id = datos.parametro2;
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const ruta = '/productos/darBaja/' + id;
                axios.post(ruta)
                    .then(function(respuesta) {
                        // console.log(respuesta.data);
                        toastr.success('Eliminado correctamente', 'Mensaje del Sistema');
                        buscar();
                    }).catch(function(error) {
                        if (error.hasOwnProperty('response')) {
                            toastr.error(error.response.data.message, 'Mensaje del sistema');
                        }
                    })
            }
        })
    }

    function obteneridart(idart) {}

    //ARMAR COMBOS
    // function armarcombo(datos) {
    //     axios.get('/combos/modalcreatedetalle', {
    //         "params": {
    //             "txtidproducto": datos.parametro2
    //         }
    //     }).then(function(respuesta) {
    //         $('#modal-contenidod').html(respuesta.data)
    //         $("#lbltitulodetalle").text("Armar combo: " + datos.parametro1);
    //         $("#txtidproducto").val(datos.parametro2);
    //         $("#modal-detalle").modal('show');
    //     }).catch(function() {
    //         toastr.error('Error al cargar el modal de crear', 'Mensaje del Sistema')
    //     });
    // }

    function closemodaldetalle() {
        $("#modal-detalle").modal('hide');
    }

    // function buscarProductoModal() {
    //     var abuscar = document.getElementById("txtbuscarProducto").value;
    //     if (abuscar.length == 0) {
    //         toastr.info("Ingrese Nombre de Producto a Buscar", 'Mensaje del Sistema')
    //         return;
    //     }
    //     var noption = 'nombre';
    //     axios.get('/productos/buscarproductoparacombo', {
    //         "params": {
    //             "cbuscar": abuscar,
    //             "option": noption
    //         }
    //     }).then(function(respuesta) {
    //         const contenido_tabla = respuesta.data;
    //         $('#searchP').html(contenido_tabla);
    //     }).catch(function(error) {
    //         toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
    //     });
    // }

    // //PARA COMBOS DE PRODUCTOS
    // function agregarunitemVenta(datos) {
    //     var tr = `<tr class="fila"> 
    //                 <td><input type="text" name="idart1" style="width: 100%;" class="idart" id="idart1"  value='` + datos.parametro2 + `' readonly></td>
    //                 <td><input type="text" name="nombre1" style="width: 100%;" class="nombre" id="nombre1"  value='` + datos.parametro1 + `' readonly></td>
    //                 <td><input type="text" name="costo1" style="width: 100%;" class="costo" id="costo1"  value='` + datos.parametro8 + `' readonly></td>
    //                 <td> <button class="borrar" style="height:25px; background-color:#FF3838; border-color: #FF3838;">Eliminar</button></td>
    //                 </tr>`;
    //     $('#detallecombo tbody').append(tr);
    //     calcularcostototal();
    // }

    function calcularcostototal() {
        var costos = [];
        var total = 0;
        $("#detallecombo tbody > tr").each(function(index) {
            var costo = Number($(this).find('.costo').val());
            total += costo;
        });
        $("#txtcostototal").val(Number(total).toFixed(2))
    }

    // $(document).on('click', '.borrar', function(event) {
    //     event.preventDefault();
    //     $(this).closest('tr').remove();
    //     calcularcostototal();
    // });

    // function registrarcombo() {
    //     if ($('#detallecombo tbody tr').length != 0) {
    //         Swal.fire({
    //             title: "¿Desea registrar el combo?",
    //             text: "Los productos se uniran al combo",
    //             icon: "warning",
    //             showCancelButton: true,
    //             confirmButtonColor: "#3085d6",
    //             cancelButtonColor: "#d33",
    //             confirmButtonText: "Si, armar combo",
    //             cancelButtonText: "No, cancelar"
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 const detalle = []
    //                 $("#detallecombo tbody tr").each(function() {
    //                     json = "";
    //                     $(this).find("td input").each(function() {
    //                         $this = $(this);
    //                         json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
    //                     })
    //                     obj = JSON.parse('{' + json.substr(1) + '}');
    //                     detalle.push(obj)
    //                 });
    //                 data = new FormData();
    //                 data.append("txtidproducto", $("#txtidproducto").val());
    //                 data.append("detalle", JSON.stringify(detalle));
    //                 axios.post("/combos/registrarcombo", data)
    //                     .then(function(respuesta) {
    //                         Swal.fire({
    //                             title: "Se ejecuto satisfactoriamente",
    //                             text: respuesta.data.mensaje.trimEnd(),
    //                             icon: "success"
    //                         });
    //                         $("#modal-detalle").modal('hide');
    //                     }).catch(function(error) {
    //                         e = error['response']['data']['errors']
    //                         result = []
    //                         for (var i in e) {
    //                             result.push([i, e[i]]);
    //                         }
    //                         result.forEach(function(numero) {
    //                             toastr.error(numero[1], 'Mensaje del Sistema')
    //                         });
    //                     });
    //             }
    //         });
    //     } else {
    //         toastr.info("Agregue productos al combo", 'Mensaje del Sistema');
    //     }
    // }

    // $('#modal-detalle').on('hidden.bs.modal', function() {
    //     $('#detallecombo tbody tr').remove();
    // });
</script>
<?php
$this->endSection('javascript');
?>