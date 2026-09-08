<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h6 class="m-0"><?php echo $titulo ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="nombre" style="width: 150px;" aria-label="Recipient's username" aria-describedby="basic-addon2" placeholder="Ingrese Nombre Del Cliente" disabled>
                                        <span class="btn btn-outline-light" role="button" id="" data-toggle="modal" data-target="#modal_clientes"><i class="fas fa-user-alt"></i>
                                        </span> &nbsp;
                                        <button role="button" data-toggle="modal" data-target="#modal_productos" id="añadirProduc" class="btn btn-success"><i class="fas fa-cart-plus"></i></button>
                                        <!-- Modal Cliente -->
                                        <div class="modal fade" id="modal_clientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Buscar Cliente</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" id="buscar" onkeypress="pulsar(event)" name="buscar" placeholder="Nombre del Cliente" aria-describedby="basic-addon2">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary" id="cmdbuscar" onclick="buscarcliente()" type="button">Buscar</button>
                                                            </div>
                                                            <div class="col-12" id="search">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            $('#myModal').on('shown.bs.modal', function() {
                                                $('#myInput').trigger('focus')
                                            })
                                        </script>
                                        <!-- Modal Productos -->
                                        <div class="modal fade" id="modal_productos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Buscar Producto</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" id="buscar1"  onkeypress="pulsar1(event)" placeholder="Nombre del Producto" aria-describedby="basic-addon2">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary" onclick="buscarproducto()" type="button">Buscar</button>
                                                            </div>
                                                            <div class="col-12" id="search1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>&nbsp;
                                    </div>
                                </div>
                                <div class="col text-right">
                                    <label for="name">
                                        Moneda:
                                        <select name="setipodoc" aria-label="Recipient's username">
                                            <option value="" selected>Soles</option>
                                            <option value="">Dolares</option>
                                            <option value="">Euros</option>
                                        </select></label>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <p class="text-right" style="font-size:h6;" aria-label="Recipient's username"><strong>Fecha:</strong></p>
                                        </div>
                                        <div class="col">
                                            <input type="date" style="width:100%;" class="form-control form-control-sm" id="txtfecha" name="txtfecha">
                                            <script>
                                                document.getElementById('txtfecha').valueAsDate = new Date();
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-success card-outline" style="width:max-content;">
                            <table class="table" id="tabla_productoC">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:50px">Quitar</th>
                                        <th scope="col">Producto</th>
                                        <th scope="col" style="width:55px">Unidad</th>
                                        <th scope="col" style="width:55px">Cantidad</th>
                                        <th scope="col" style="width:50px">Precio</th>
                                        <th scope="col" style="width:80px">Importe</th>
                                    </tr>
                                </thead>
                                <tbody id="carritopro">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline" style="width:auto;">
                            <div class="row">

                                <div class="col"><br>

                                </div>
                                <div class="col"><br>

                                </div>
                                <div class="col"><br>
                                    <button class="btn btn-danger" role="button" onclick="borrar_venta();">Cancelar</button>
                                    <button class="btn btn-success" onclick="GrabarPedido()" role="button">Grabar </button>
                                </div>
                                <div class="col"><br>
                                    <div class="input-group mb-3" style="width: 200px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id=""><strong>TOTAL S/</strong></span>
                                        </div>
                                        <input type="text" class="form-control text-right" id="total" aria-label="Small" placeholder=" 0.00" aria-describedby="inputGroup-sizing-sm" disabled>
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
        window.onload = function() {
            carrito = new Array();
            idcliente = 0
        }
        var trs = document.getElementsByClassName('inline');
        //Función para ponerle el cursor en los input
        $('#modal_clientes').on('shown.bs.modal', function() {
            $('#buscar').focus();
        })
        $('#modal_productos').on('shown.bs.modal', function() {
            $('#buscar1').focus();
        })

        //Funcion para buscar cliente
        function buscarcliente() {
            var valorbusqueda = document.getElementById("buscar").value;
            if (valorbusqueda != '') {

                consultardata(valorbusqueda);
            }
        }

        //Funcion para buscar producto
        function buscarproducto() {
            var valorbusqueda = document.getElementById("buscar1").value;
            if (valorbusqueda != '') {

                consultardata1(valorbusqueda);
            }
        }

        //Funcion para consultar cliente
        function consultardata(valorBusqueda) {
            //  console.log(window.location.href);
            axios.get('/clientes/lista', {
                    "params": {
                        "buscar": valorBusqueda
                    }
                }).then(function(respuesta) {
                    // 100, 200, 300
                    const contenido_tabla = respuesta.data;
                    // console.log(respuesta.data);
                    $('#search').html(contenido_tabla);
                    // console.log(respuesta.data.message)
                }) // representa cuando todo va bien en peticion
                .catch(function(error) {
                    // 400, 500
                    toastr.error('Error al cargar el listado')
                });
        }

        // Funcion para consultar producto
        function consultardata1(valorBusqueda) {
            axios.get('/productos/lista', {
                    "params": {
                        "buscar1": valorBusqueda
                    }
                }).then(function(respuesta) {
                    // 100, 200, 300
                    const contenido_tabla = respuesta.data;
                    $('#search1').html(contenido_tabla);
                    // console.log(respuesta.data.message)
                }) // representa cuando todo va bien en peticion
                .catch(function(error) {
                    // 400, 500
                    toastr.error('Error al cargar el listado')
                });
        }

        //Evento con enter
        function pulsar(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                var valorbusqueda = document.getElementById("buscar").value;
                if (valorbusqueda != '') {
                    consultardata(valorbusqueda);
                }
            }
        }
        function pulsar1(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                var valorbusqueda = document.getElementById("buscar1").value;
                if (valorbusqueda != '') {
                    consultardata1(valorbusqueda);
                }
            }
        }

        //Funcion Agregar Cliente
        function agregar_cliente(id, nom) {
            document.getElementById('nombre').value = nom;
            idcliente = id;
            //Cerramos la ventana modal
            $(function() {
                $('#modal_clientes').modal('toggle');
            });
        }

        //Funcion para multiplicar
        function multiplicar(a, b) {
            parseFloat(a);
            parseFloat(b);
            return a * b;
        }

        //Funcion agregar producto
        function agregar_producto(parametros) {
            //Cierro la modal
            $(function() {
                $('#modal_productos').modal('toggle');
            });
            var oparametros = JSON.parse(JSON.stringify(parametros));
            //Armamos un arraylist
            carrito.push({
                pos: 0,
                producto: oparametros.parametro1,
                codigo: oparametros.parametro2,
                unidad: oparametros.parametro3,
                cantidad: 0,
                precio: oparametros.parametro5,
                subtotal: 0.00
            });
            mostrar_carrito();
        }

        //Funcion para calcular el sub total
        function inlinePrice() {
            for (var i = 0; i < trs.length; i++) {
                // Obtenga el precio unitario de cada línea
                //console.log(trs);
                var price = Number(trs[i].children[4].innerHTML);
                //console.log(price);
                // Obtener la cantidad
                var num = Number(trs[i].children[3].children[0].value);
                //console.log(num);
                //Total parcial
                trs[i].children[5].innerHTML = (price * num).toFixed(2);

            }
        }

        //Funcion para agregar cantidad con boton "+"
        function addCount(e, fila) {
            // Obtenga el valor y la cantidad en la etiqueta de entrada
            //console.log(fila);
            var num = parseInt(e.previousElementSibling.value);
            console.log(num);
            // Número más 1
            e.previousElementSibling.value = num + 1;
            inlinePrice();
            carrito[fila].cantidad = num + 1;
            sumTotal();
            console.log(carrito);
        }

        //Funcion para disminuir cantidad con boton "-"
        function reduceCount(e, fila) {
            //Capturamos la cantidad
            var num = parseInt(e.nextElementSibling.value);
            if (num < 0) {
                alert("No se puede reducir más.");
                return;
            }
            e.nextElementSibling.value = num - 1;
            carrito[fila].cantidad = num - 1;
            inlinePrice();
            sumTotal();
            console.log(carrito);
        }

        // Funcion para agregar cantidad con input
        function enter_cant(fila) {
            // Obtenga el valor y la cantidad en la etiqueta de entrada
            let cantidad = document.getElementById("cant").value;
            if (cantidad < 0) {
                alert("No se puede reducir más.");
                return;
            }
            carrito[fila].cantidad = cantidad;
            inlinePrice();
            sumTotal();
        }

        //Borrar fila
        function borrar_fila(fila) {
            carrito.splice(fila, 1);
            mostrar_carrito();
            sumTotal();
        }

        // Calcular el total
        function sumTotal() {
            var sum = 0;
            for (var i = 0; i < carrito.length; i++) {
                sum += carrito[i].cantidad * carrito[i].precio;
            }
            document.getElementById('total').value = sum.toFixed(2);
        }

        //Funcion para mostrar tabla
        function mostrar_carrito() {
            $("#carritopro").children().remove();

            for (i = 0; i < carrito.length; i++) {
                //'<span class="add" onclick="addCount(this,' + i + ');">+</span>' + '
                //+ '<span class="reduce" onclick="reduceCount(this,' + i + ')">-</span>'
                $("#tabla_productoC").append('<tr id="valor"class="inline">' +
                    '<td align="left" style="dislay: none;">' + '<button class="btn btn-danger" onclick="borrar_fila(' + i + ');"><i style="color:white;" class="fas fa-times-circle "></i></button>' + '</td>' +
                    '<td align="left" style="dislay: none;">' + carrito[i].producto + '</td>' +
                    '<td align="left" style="dislay: none;">' + carrito[i].unidad + '</td>' +
                    '<td style="dislay: none;"><input type="number" id="cant" onkeyup="onKeyUp(event)" onblur="enter_cant(' + i + ');"></td>' +
                    '<td align="left" style="dislay: none;">' + carrito[i].precio + '</td>' +
                    '<td  id="subtotal" style="dislay: none;">' + carrito[i].subtotal + '</td>' + '</tr>');
            }
            //localStorage.setItem(0, oparametros);
        }

        $(document).on('click', '#borrar', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });

        //Funcion para cancelar la venta
        function borrar_venta() {
            carrito.length = 0;
            document.getElementById("total").value = 0.00
            mostrar_carrito();

        }

        function onKeyUp(event) {
            var keycode = event.keyCode;
            if (keycode == '13') {
                inlinePrice();
            }
        }

        function GrabarPedido() {
            var ruta = 'pedido/grabarpedido';
            var datos = new FormData();
            datos.append('idcliente', idcliente);
            datos.append('total', document.getElementById("total").value);
            datos.append('carrito', JSON.stringify(carrito));
            Swal.fire({
                icon: 'question',
                title: '¿Grabar Pedido?',
                text: 'Registrando Transacción',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No, cancelar'
            }).then(function(rpta) {
                if (rpta.isConfirmed) {
                    axios.post(ruta, datos).then(function(respuesta) {
                            // 100, 200, 300
                            // console.log(respuesta.data);
                            // console.log("hola")
                            borrar_venta();
                        }).catch(function(error) {
                            // 400, 500
                            // toastr.error(error);F
                            if (error.hasOwnProperty("response")) {
                                if (error.response.status === 422) {
                                    //mostrarErrores("formulario-agregar-presentacion", error.response.data.errors);
                                    toastr.error(error.response.data.errors);
                                }
                            } else {
                                toastr.error("Error al registrar  pedido", "Mensaje del Sistema");
                            }


                        });
                }
            })
        }
    </script>
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <?php
    $this->endSection('javascript');
    ?>