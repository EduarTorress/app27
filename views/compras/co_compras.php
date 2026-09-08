<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h6 class="m-0"><?php echo $titulo ?></h6>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Dcto.:</label>
                            <div>
                                <select class="form-control form-control-sm" style="width: 150px;">
                                    <?php foreach ($lista['lista']['items'] as $row) : ?>
                                        <option value=<?php echo $row['tdoc'] ?>><?php echo $row['nomb'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">R.U.C:</label>
                            <div>
                                <div class="input-group mb-0">
                                    <input type="text" style="width:150px;" class="form-control form-control-sm" id="txtruc" placeholder="Ingrese RUC" value="" maxlength="11" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" aria-describedby="cmdruc" onkeyup="buscar1()">
                                    <button class="btn btn-outline-success btn-sm" type="button" id="cmdruc" name="cmdruc" onclick="buscaruc()">Buscar</button>

                                </div>
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Nombre:</label>
                            <div>
                                <input type="text" style="width:200%;" class="form-control form-control-sm" id="txtnombre" placeholder="Proveedor" value="" readonly>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Serie:</label>
                            <div>
                                <input type="text" style="width:150px;" class="form-control form-control-sm" onkeyup="javascript:this.value=this.value.toUpperCase();" id="txtserie" placeholder="Ingrese serie" value="">
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Número:</label>
                            <div>
                                <input type="text" style="width:150px;" class="form-control form-control-sm" id="txtnumero" placeholder="Ingrese número" value="">
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">F. Emisión:</label>
                            <div>
                                <input type="date" style="width:150px;" class="form-control form-control-sm" id="txtfecha" placeholder="Ingrese Fecha" value="">
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">F. Registro:</label>
                            <div>
                                <input type="date" style="width:150px;" class="form-control form-control-sm" id="txtfechar" placeholder="Ingrese Fecha" value="">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Moneda:</label>
                            <div>
                                <select class="form-control form-control-sm" style="width: 150px;" aria-label="Default select example">
                                    <option value="S" selected>Soles</option>
                                    <option value="D">Dólares</option>
                                </select>
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Pago:</label>
                            <div>
                                <select class="form-control form-control-sm" style="width: 150px;" aria-label="Default select example">
                                    <option value="E" selected>Efectivo</option>
                                    <option value="C">Crédito</option>
                                </select>
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">T. Cambio:</label>
                            <div>
                                <input type="text" style="width:150px;" class="form-control form-control-sm" id="txtnombre" placeholder="Ingrese Nombre" value="">
                            </div>
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Tipo:</label>
                            <div>
                                <select class="form-control form-control-sm" style="width: 150px;" aria-label="Default select example">
                                    <option value="1" selected>1. Mercaderia</option>
                                    <option value="2">2. Otros</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Base 1:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtbase1" placeholder="Ingrese Base Imponible" value="0.00" onkeyup="calculatotales()">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Base 2:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtbase2" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Base 3:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtbase3" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Base 4:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtbase4" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Exon :</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtExon" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">IGV:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtigv" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Otros:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txtotros" placeholder="Ingrese Base Imponible" value="0.00">
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Total:</label>
                            <div>
                                <input type="number" style="width: 150px;" class="form-control form-control-sm" id="txttotal" placeholder="Ingrese Monto Total" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row mb-0">
                            <div>
                                <button class="btn btn-success btn-sm">Aceptar</button>
                            </div>
                            <div>
                                <button class="btn btn-warning btn-sm">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection("contenido");
?>
<?php
$this->startsection("javascript");
?>
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        text-align: right;
    }
</style>
<script>
    window.onload = function() {
        var fecha = new Date(); //Fecha actual
        var mes = fecha.getMonth() + 1; //obteniendo mes
        var dia = fecha.getDate(); //obteniendo dia
        var ano = fecha.getFullYear(); //obteniendo año
        if (dia < 10)
            dia = '0' + dia; //agrega cero si el menor de 10
        if (mes < 10)
            mes = '0' + mes //agrega cero si el menor de 10
        document.getElementById('txtfecha').value = ano + "-" + mes + "-" + dia;
        document.getElementById('txtfechar').value = ano + "-" + mes + "-" + dia;
    }

    function buscaruc() {
        var ruc;
        ruc = document.getElementById('txtruc').value;
        if (ruc.length = 11) {
            axios.get('/proveedores/importadatos', {
                "params": {
                    "ruc": ruc
                }
            }).then(function(respuesta) {
                console.log(respuesta.data);
                console.log(ruc);
                document.getElementById("txtnombre").value = respuesta.data.nombre_o_razon_social;
            }).catch(function(error) {
                console.log(error);
            })
        }
    }

    function buscar1() {
        var ruc;
        ruc = document.getElementById("txtruc").value
        if (ruc.length === 11) {
            buscaruc();
        }
    }

    function calculatotales() {
        let valor = Number(document.querySelector("#txtbase1").value);
        let igv = valor * 0.18;
        let total = valor + igv;
        igv = igv.toFixed(2);
        total = total.toFixed(2);
        console.log(igv);
        console.log(total);
        document.getElementById("txtigv").value = igv;
        document.getElementById("txttotal").value = total;
    }
</script>
<?php
$this->endSection("javascript");
?>