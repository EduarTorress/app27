<div class="modal-dialog modal-lg divproducto" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"><?php echo $titulo; ?></h4>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-3">
                    <?php
                    $ccat = isset($datosProducto['idcat']) ? $datosProducto['idcat'] : '';
                    $cat = new \App\View\Components\CategoriaComponent($ccat);
                    echo $cat->render();
                    ?>
                </div>
                <div class="form-group col-2">
                    <?php
                    $ggrup = isset($datosProducto['idgrupo']) ? $datosProducto['idgrupo'] : '';
                    $grup = new \App\View\Components\GrupoComponent($ggrup);
                    echo $grup->render();
                    ?>
                </div>
                <div class="form-group col-3">
                    <?php
                    $cmar = isset($datosProducto['idmar']) ? $datosProducto['idmar'] : '';
                    $mar = new \App\View\Components\MarcaComponent($cmar);
                    echo $mar->render();
                    ?>
                </div>
                <div class="form-group col-2">
                    <?php
                    $cunid = isset($datosProducto['unid']) ? $datosProducto['unid'] : '';
                    $unid = new \App\View\Components\UnidadComponent($cunid);
                    echo $unid->render();
                    ?>
                </div>
                <div class="form-group col-2">
                    <?php
                    $ctipp = isset($datosProducto['tipop']) ? $datosProducto['tipop'] : '';
                    $tipop = new \App\View\Components\TipoProductoComponent($ctipp);
                    echo $tipop->render();
                    ?>
                </div>
                <div class="form-group col-6">
                    <input style="display:none" type="text" style="width:200%;" class="form-control form-control-sm" id="txtidart" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['idart']) ?>">
                    <label for="nombre">Descripción:</label>
                    <input type="text" onkeyup="mayusculas(this)" placeholder="Ingrese descripción del producto" name="txtdescrip" id="txtdescrip" placeholder="" class="form-control form-control-sm" value='<?php echo (empty($datosProducto) ? '' : $datosProducto['descri']) ?>' required>
                </div>
                <div class="form-group col-2">
                    <label for="codigo">Código:</label>
                    <input type="text" onclick="select()" class="form-control form-control-sm" id="txtcodigoo" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['codigo']) ?>" required>
                </div>
                <div class="form-group col-2">
                    <label for="peso">Peso:</label>
                    <input type="text" onclick="select()" class="form-control form-control-sm inputright" placeholder="KG" id="txtpeso" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['peso']) ?>" required>
                </div>
                <div class="form-group col-2">
                    <?php
                    $idflete = isset($datosProducto['idflete']) ? $datosProducto['idflete'] : '';
                    $flet = new \App\View\Components\FleteComponent($idflete);
                    echo $flet->render();
                    ?>
                </div>
                <div class="form-group col-3" style="display:none">
                    <label for="" class="">Stock Mínimo:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtStockMin" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_smin']) ?>">
                </div>
                <div class="form-group col-3" style="display:none">
                    <label for="">Stock Máximo:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtStockMax" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_smax']) ?>">
                </div>
                <div class="form-group col-4">
                    <label for="">Costo sin IGV:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcostosig" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costosigv']) ?>" required>
                </div>
                <div class="form-group col-4">
                    <label for="">Costo con IGV:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcostocig" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costocigv']) ?>" required>
                </div>
                <div class="form-group col-4" style="display: none;">
                    <label for="">Costo Transp:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" disabled class="form-control form-control-sm inputright" id="txtcostot" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['flete']) ?>">
                </div>
                <div class="form-group col-4">
                    <label for="">Costo Neto:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtcoston" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['costocigv']) ?>" required>
                </div>
                <div class="form-group col-4" style="display: none;">
                    <label for="" class="">Moneda:</label>
                    <div>
                        <?php $cmon = isset($datosProducto['tmon']) ? $datosProducto['tmon'] : ''; ?>
                        <select onchange=" convertprectodolar()" class="form-control form-control-sm" id="cmbMoneda" name="cmbMoneda">
                            <option <?php echo empty($cmon) ? 'selected ' : ($cmon == 'S' ? 'selected' : '') ?> value="S">Soles</option>
                            <option <?php echo ($cmon == 'D' ? 'selected' : '') ?> value="D">Dólares</option>¿
                        </select>
                    </div>
                </div>
                <div class="form-group col-3" style="display:none;">
                    <label for="">Comisión Efect:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtcomisione" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_come']) ?>" required>
                </div>
                <div class="form-group col-3" style="display:none;">
                    <label for="">Comisión Cred:</label>
                    <input type="text" onkeypress="return isNumber(event);" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm" id="txtcomisionc" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_comc']) ?>" required>
                </div>
                <div class="form-group col-6">
                    <label for="">% Precio Venta:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcprecma" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti1']) ?>" required>
                </div>
                <div class="form-group col-4" style="display:none">
                    <label for="">% Precio Especial:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcpreces" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti2']) ?>" required>
                </div>
                <div class="form-group col-6" style="display: none;">
                    <label for="">% Precio Menor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtporcprecem" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['prod_uti3']) ?>" required>
                </div>
                <div class="form-group col-6">
                    <label for="">Precio Venta:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtprecioma" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre1']) ?>" required>
                </div>
                <div class="form-group col-4" style="display:none">
                    <label for="">Precio Especial:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtprecioe" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre2']) ?>" required>
                </div>
                <div class="form-group col-6" style="display:none">
                    <label for="">Precio Menor:</label>
                    <input type="text" onkeypress="return isNumber(event);" onclick="select()" class="form-control form-control-sm inputright" id="txtpreciome" value="<?php echo (empty($datosProducto) ? '' : $datosProducto['pre3']) ?>" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" onclick="grabarproducto()"><i class="fas fa-save"></i> Grabar</button>
            <button type="button" class="btn btn-danger" onclick="cerrarModal()" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
    <script>
        var txtcostot = document.getElementById("txtcostot");
        txtcostot.addEventListener("blur", function(event) {
            calcularcostoneto();
        }, true);

        var txtcostocig = document.getElementById("txtcostocig");
        txtcostocig.addEventListener("blur", function(event) {
            calcularcostoneto();
            calcularcostosinigv();
        }, true);

        function calcularcostosinigv() {
            txtcostocig = parseFloat($("#txtcostocig").val());
            txtcostosig = (txtcostocig / <?php echo $_SESSION['gene_igv'] ?>);
            if (isNaN(txtcostosig)) {
                $("#txtcostosig").val("0.00");
            } else {
                $("#txtcostosig").val(txtcostosig.toFixed(4));
            }
        }

        function calcularCostoConIGV() {
            txtcostosig = parseFloat($("#txtcostosig").val());
            txtcostocig = ((txtcostosig * 0.18) + txtcostosig);
            if (isNaN(txtcostocig)) {
                $("#txtcostocig").val("0.00");
            } else {
                $("#txtcostocig").val(txtcostocig.toFixed(2));
            }
        }

        //Calcular precios por porcentaje
        function calcularPreciosPorPorcentaje(precioporc, precio) {
            txtcoston = parseFloat($("#txtcoston").val());
            txtporprecio = parseFloat($(precioporc).val());
            valorutilidad = "<?php echo ((!empty($_SESSION['config']['valorutilidad'])) ? $_SESSION['config']['valorutilidad'] : 'N'); ?>";
            if (valorutilidad != 'N') {
                preciod = ((txtporprecio / 100) + 1) * Number(valorutilidad);
                preciod = txtcoston / preciod;
            } else {
                preciod = ((txtporprecio / 100) + 1) * txtcoston;
            }
            if (isNaN(preciod)) {
                $(precio).val("0.00");
            } else {
                $(precio).val(preciod.toFixed(4));
            }
        }

        //Calcular porcentajes por precio
        function calcularPorcentajePorPrecio(precio, porcentaje) {
            txtcoston = parseFloat($("#txtcoston").val());
            txtprecio = parseFloat($(precio).val());
            diferencia = txtprecio - txtcoston;
            $porcprecio = ((diferencia * 100) / txtcoston);
            if (isNaN($porcprecio)) {
                $(porcentaje).val("0.00");
            } else {
                $(porcentaje).val($porcprecio.toFixed(4));
            }
        }

        //Evento para agregar IGV
        var txtcostosig = document.getElementById("txtcostosig");
        txtcostosig.addEventListener("blur", function(event) {
            calcularCostoConIGV();
            calcularcostoneto();
        }, true);

        //Porcentaje precio mayor
        var txtporcprecma = document.getElementById("txtporcprecma");
        txtporcprecma.addEventListener("blur", function(event) {
            calcularPreciosPorPorcentaje("#txtporcprecma", "#txtprecioma");
        }, true);

        //Porcentaje precio especial
        var txtporcpreces = document.getElementById("txtporcpreces");
        txtporcpreces.addEventListener("blur", function(event) {
            calcularPreciosPorPorcentaje("#txtporcpreces", "#txtprecioe");
        }, true);

        //Porcentaje precio menor    
        var txtporcprecem = document.getElementById("txtporcprecem");
        txtporcprecem.addEventListener("blur", function(event) {
            calcularPreciosPorPorcentaje("#txtporcprecem", "#txtpreciome");
        }, true);

        //Precio mayor
        var txtprecioma = document.getElementById("txtprecioma");
        txtprecioma.addEventListener("blur", function(event) {
            calcularPorcentajePorPrecio("#txtprecioma", "#txtporcprecma");
        }, true);

        //Precio especial
        var txtprecioe = document.getElementById("txtprecioe");
        txtprecioe.addEventListener("blur", function(event) {
            calcularPorcentajePorPrecio("#txtprecioe", "#txtporcpreces");
        }, true);

        //Precio menor
        var txtprecioe = document.getElementById("txtpreciome");
        txtpreciome.addEventListener("blur", function(event) {
            calcularPorcentajePorPrecio("#txtpreciome", "#txtporcprecem");
        }, true);
    </script>
    <script>
        function validarcamposprod() {
            let txtdescrip = document.getElementById("txtdescrip").value;
            let txtcostosig = document.getElementById("txtcostosig").value;
            let txtcostocig = document.getElementById("txtcostocig").value;
            let txtpeso = document.getElementById("txtpeso").value;
            let txtcoston = document.getElementById("txtcoston").value;
            if (txtdescrip == '') {
                toastr.error("Ingrese una descripción", 'Mensaje del Sistema');
                return false;
            }
            if (txtcostosig == '') {
                toastr.error("Ingrese un costo", 'Mensaje del Sistema');
                return false;
            }
            if (txtcostocig == '') {
                toastr.error("Ingrese un costo", 'Mensaje del Sistema');
                return false;
            }
            if (txtpeso == '') {
                toastr.error("Ingrese un peso", 'Mensaje del Sistema');
                return false;
            }
            if (txtcoston == '') {
                toastr.error("Ingrese el costo neto", 'Mensaje del Sistema');
                return false;
            }
            // let txtpreciome = document.getElementById("txtpreciome").value;
            //  if (Number(txtpreciome) < Number(txtcoston)) {
            //     toastr.error("El precio menor no puede estar debajo del costo neto", 'Mensaje del Sistema')
            //     return false;
            // }
            let txtprecioma = document.getElementById("txtprecioma").value;
            if (Number(txtprecioma) < Number(txtcoston)) {
                toastr.error("El precio no puede estar debajo del costo neto", 'Mensaje del Sistema')
                return false;
            }
            return true;
        }

        function registrarprod() {
            if (validarcamposprod() == false) {
                return;
            }
            Swal.fire({
                title: "Mensaje del Sistema",
                text: "¿Desea grabar el producto? ",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    let txtcodigo = document.getElementById("txtcodigoo").value;
                    let cmbgrupo = $("#cmbgrupo").val();
                    let cmbcategoria = document.getElementById("cmbcategoria").value;
                    let cmbmarca = document.getElementById("cmbmarca").value;
                    let txtdescrip = document.getElementById("txtdescrip").value;
                    let cmbunidad = document.getElementById("cmbunidad").value;
                    let cmbtipp = document.getElementById("cmbtipoproducto").value;
                    let cmbest = "1";
                    let txtpeso = document.getElementById("txtpeso").value;
                    let txtStockMin = document.getElementById("txtStockMin").value;
                    let txtStockMax = document.getElementById("txtStockMax").value;
                    // let txtcodprov = document.getElementById("txtStockMax").value;
                    let cmbMoneda = document.getElementById("cmbMoneda").value;
                    // let txtprecioc = document.getElementById("txtprecioc").value;
                    // let txttcprod = document.getElementById("txttcproducto").value;
                    let txtcostosig = document.getElementById("txtcostosig").value;
                    let txtcostocig = document.getElementById("txtcostocig").value;
                    cmbCostoT = document.getElementById("cmbCostoT").value;
                    cmbCostoT = cmbCostoT.split('-');
                    let txtcostot = cmbCostoT[0];
                    let txtcoston = document.getElementById("txtcoston").value;
                    let porcprecma = document.getElementById("txtporcprecma").value;
                    txtporcprecma = ((Number(porcprecma) / 100) + 1).toFixed(6);
                    let txtprecioma = document.getElementById("txtprecioma").value;
                    let porcpreces = document.getElementById("txtporcpreces").value;
                    txtporcpreces = ((Number(porcpreces) / 100) + 1).toFixed(6);
                    let txtprecioe = document.getElementById("txtprecioe").value;
                    let porcprecem = document.getElementById("txtporcprecem").value;
                    txtporcprecem = ((Number(porcprecem) / 100) + 1).toFixed(6);
                    let txtpreciome = document.getElementById("txtpreciome").value;
                    let txtcomisione = document.getElementById("txtcomisione").value;
                    let txtcomisionc = document.getElementById("txtcomisionc").value;
                    // if (cmbMoneda == 'D') {
                    //     txtcoston = Number(txtcoston / dolar).toFixed(2);
                    //     txtprecioma = Number(txtprecioma * dolar).toFixed(2);
                    //     txtprecioe = Number(txtprecioe * dolar).toFixed(2);
                    //     txtpreciome = Number(txtpreciome * dolar).toFixed(2);
                    // }
                    data = new FormData();
                    data.append("txtcodigo", txtcodigo);
                    data.append("cmbgrupo", cmbgrupo);
                    data.append("cmbcategoria", cmbcategoria);
                    data.append("cmbmarca", cmbmarca);
                    data.append("txtdescrip", txtdescrip);
                    data.append("cmbunidad", cmbunidad);
                    data.append("cmbtipp", cmbtipp);
                    data.append("cmbest", cmbest);
                    data.append("txtpeso", txtpeso);
                    data.append("txtStockMin", txtStockMin);
                    data.append("txtStockMax", txtStockMax);
                    data.append("txtcodprov", "0");
                    data.append("cmbMoneda", cmbMoneda);
                    data.append("txtprecioc", 0.00);
                    data.append("txttcprod", 0.00);
                    data.append("txtcostosig", txtcostosig);
                    data.append("txtcostocig", txtcostocig);
                    data.append("txtcostot", txtcostot);
                    data.append("txtcoston", txtcoston);
                    data.append("txtporcprecma", txtporcprecma);
                    data.append("txtprecioma", txtprecioma);
                    data.append("txtporcpreces", txtporcpreces);
                    data.append("txtprecioe", txtprecioe);
                    data.append("txtporcprecem", txtporcprecem);
                    data.append("txtpreciome", txtpreciome);
                    data.append("txtcomisione", txtcomisione);
                    data.append("txtcomisionc", txtcomisionc);
                    axios.post("/productos/registrar", data)
                        .then(function(respuesta) {
                            toastr.success(respuesta.data.message, 'Mensaje del Sistema')
                            limpiarTodoprod();
                            $("#modal-mantenimiento").modal('hide');
                            rutaactual = window.location.pathname;
                            // console.log(rutaactual)
                            if (rutaactual == '/compras/index') {
                                $("#txtbuscarProducto").val(txtdescrip)
                                buscarProducto();
                            }
                        }).catch(function(error) {
                            if (error.hasOwnProperty("response")) {
                                if (error.response.status === 422) {
                                    errors = error.response.data.errors;
                                    showtoastrerrors(errors);
                                }
                            }
                            console.log(error)
                        });
                }
            });
        }

        function convertprectodolar() {
            cmbmoneda = $("#cmbMoneda").val();
            dolar = "<?php echo session()->get('gene_dola'); ?>";
            txtprecioma = $("#txtprecioma").val();
            txtprecioe = $("#txtprecioe").val();
            txtpreciome = $("#txtpreciome").val();
            txtcoston = $("#txtcoston").val();
            // costoconigv = $("#txtcostocig").val();
            // costosingiv = $("#txtcostosig").val();
            if (cmbmoneda == 'S') {
                $("#txtcoston").val(Number(txtcoston / dolar).toFixed(2));
                // $("#txtprecioma").val(Number(txtprecioma * dolar).toFixed(2));
                // $("#txtprecioe").val(Number(txtprecioe * dolar).toFixed(2));
                // $("#txtpreciome").val(Number(txtpreciome * dolar).toFixed(2));
            } else {
                $("#txtcoston").val(Number(txtcoston * dolar).toFixed(2));
                // $("#txtprecioma").val(Number(txtprecioma / dolar).toFixed(2));
                // $("#txtprecioe").val(Number(txtprecioe / dolar).toFixed(2));
                // $("#txtpreciome").val(Number(txtpreciome / dolar).toFixed(2));
            }
            calcularPreciosPorPorcentaje("#txtporcprecma", "#txtprecioma");
            calcularPreciosPorPorcentaje("#txtporcpreces", "#txtprecioe");
            calcularPreciosPorPorcentaje("#txtporcprecem", "#txtpreciome");
            // calcularcostoneto();
        }

        function actualizarprod() {
            if (validarcamposprod() == false) {
                return;
            }
            Swal.fire({
                title: "Mensaje del Sistema",
                text: "¿Desea actualizar el producto? ",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    let txtcodigo = document.getElementById("txtcodigoo").value;
                    let cmbgrupo = $("#cmbgrupo").val();
                    let cmbcategoria = document.getElementById("cmbcategoria").value;
                    let cmbmarca = document.getElementById("cmbmarca").value;
                    let txtdescrip = document.getElementById("txtdescrip").value;
                    let cmbunidad = document.getElementById("cmbunidad").value;
                    let cmbtipp = document.getElementById("cmbtipoproducto").value;
                    let cmbest = "1";
                    let txtpeso = document.getElementById("txtpeso").value;
                    let txtStockMin = document.getElementById("txtStockMin").value;
                    let txtStockMax = document.getElementById("txtStockMax").value;
                    // let txtcodprov = document.getElementById("txtStockMax").value;
                    let cmbMoneda = document.getElementById("cmbMoneda").value;
                    // let txtprecioc = document.getElementById("txtprecioc").value;
                    // let txttcprod = document.getElementById("txttcproducto").value;
                    let txtcostosig = document.getElementById("txtcostosig").value;
                    let txtcostocig = document.getElementById("txtcostocig").value;
                    cmbCostoT = document.getElementById("cmbCostoT").value;
                    cmbCostoT = cmbCostoT.split('-');
                    let txtcostot = cmbCostoT[0];
                    let txtcoston = document.getElementById("txtcoston").value;
                    let porcprecma = document.getElementById("txtporcprecma").value;
                    txtporcprecma = ((Number(porcprecma) / 100) + 1).toFixed(6);
                    let txtprecioma = document.getElementById("txtprecioma").value;
                    let porcpreces = document.getElementById("txtporcpreces").value;
                    txtporcpreces = ((Number(porcpreces) / 100) + 1).toFixed(6);
                    let txtprecioe = document.getElementById("txtprecioe").value;
                    let porcprecem = document.getElementById("txtporcprecem").value;
                    txtporcprecem = ((Number(porcprecem) / 100) + 1).toFixed(6);
                    let txtpreciome = document.getElementById("txtpreciome").value;
                    let txtcomisione = document.getElementById("txtcomisione").value;
                    let txtcomisionc = document.getElementById("txtcomisionc").value;

                    //(Porcentaje / 100 ) + 1

                    data = new FormData();
                    data.append("idart", $("#txtidart").val());
                    data.append("txtcodigo", txtcodigo);
                    data.append("cmbgrupo", cmbgrupo);
                    data.append("cmbcategoria", cmbcategoria);
                    data.append("cmbmarca", cmbmarca);
                    data.append("txtdescrip", txtdescrip);
                    data.append("cmbunidad", cmbunidad);
                    data.append("cmbtipp", cmbtipp);
                    data.append("cmbest", cmbest);
                    data.append("txtpeso", txtpeso);
                    data.append("txtStockMin", txtStockMin);
                    data.append("txtStockMax", txtStockMax);
                    data.append("txtcodprov", "0");
                    data.append("cmbMoneda", cmbMoneda);
                    data.append("txtprecioc", 0.00);
                    data.append("txttcprod", 0.00);
                    data.append("txtcostosig", txtcostosig);
                    data.append("txtcostocig", txtcostocig);
                    data.append("txtcostot", txtcostot);
                    data.append("txtcoston", txtcoston);
                    data.append("txtporcprecma", txtporcprecma);
                    data.append("txtprecioma", txtprecioma);
                    data.append("txtporcpreces", txtporcpreces);
                    data.append("txtprecioe", txtprecioe);
                    data.append("txtporcprecem", txtporcprecem);
                    data.append("txtpreciome", txtpreciome);
                    data.append("txtcomisione", txtcomisione);
                    data.append("txtcomisionc", txtcomisionc);
                    axios.post("/productos/actualizar", data)
                        .then(function(respuesta) {
                            toastr.success(respuesta.data.message, 'Mensaje del Sistema')
                            buscar();
                            limpiarTodoprod();
                            $("#modal-mantenimiento").modal('hide');
                        }).catch(function(error) {
                            if (error.hasOwnProperty("response")) {
                                if (error.response.status === 422) {
                                    errors = error.response.data.errors;
                                    showtoastrerrors(errors);
                                }
                            }
                            console.log(error)
                        });
                }
            });
        }

        function grabarproducto() {
            idart = $("#txtidart").val();
            if (idart == '') {
                registrarprod();
            } else {
                actualizarprod();
            }
        }

        function limpiarTodoprod() {
            var elements = document.getElementsByTagName("input");
            for (var ii = 0; ii < elements.length; ii++) {
                if (elements[ii].type == "text") {
                    elements[ii].value = "";
                }
            }
        }

        function calcularcostoneto() {
            costoconigv = $("#txtcostocig").val();
            costotransporte = $("#txtcostot").val();
            $("#txtcoston").val(Number(costoconigv) + Number(costotransporte));
        }

        function obtenerFlete() {
            cmbCostoT = document.getElementById("cmbCostoT").value;
            cmbCostoT = cmbCostoT.split('-');
            // console.log(precio[0])
            // console.log(precio[1])
            $("#txtcostot").val(cmbCostoT[1]);
            costot = $("#txtcostot").val();
            txtcostocig = $("#txtcostocig").val();
            $("#txtcoston").val((Number(costot) + Number(txtcostocig)).toFixed(2));
        }

        $('#txtdescrip').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtcodigoo").focus();
                $("#txtcodigoo").click();
            }
        });

        $('#txtcodigoo').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtpeso").focus();
                $("#txtpeso").click();
            }
        });

        $('#txtpeso').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtcostosig").focus();
                $("#txtcostosig").click();
            }
        });

        $('#txtcostosig').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtcostocig").focus();
                $("#txtcostocig").click();
            }
        });

        $('#txtcostocig').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtcoston").focus();
                $("#txtcoston").click();
            }
        });

        $('#txtcoston').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtporcprecma").focus();
                $("#txtporcprecma").click();
            }
        });

        $('#txtporcprecma').keypress(function(e) {
            if (e.keyCode == 13) {
                $("#txtprecioma").focus();
                $("#txtprecioma").click();
            }
        });

        $('#txtprecioma').keypress(function(e) {
            if (e.keyCode == 13) {
                if (e.keyCode == 13) {
                    $("#modal-mantenimiento").modal('hide');
                    grabarproducto();
                }
            }
        });

        $('#cmbcategoria').on('change', function() {
            axios.get('/categorias/obtenergrupoporcat', {
                "params": {
                    "idcat": $(this).val()
                }
            }).then(function(respuesta) {
                // console.log(respuesta);
                $("#cmbgrupo").val(respuesta.data.message);
            }).catch(function(error) {
                $("#buscar").attr('disabled', false);
                // $('#loading').modal('hide');
                toastr.error('Error al cargar ' + error.response.data, 'Mensaje del Sistema');
            });
        });
    </script>