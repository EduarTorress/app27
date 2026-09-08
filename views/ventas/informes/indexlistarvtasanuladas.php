<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\EmpresaComponent;

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
                                <br>
                                <label class="my-1 mr-2" for="txtfechai">Inicio:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai"> &nbsp;
                                <label class="my-1 mr-2" for="txtfechai">Hasta:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf"> &nbsp;
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
                                <?php
                                $dctos = new DocumentoComponent('');
                                echo $dctos->renderreports();
                                ?>
                                <button type="submit" id="btnbuscar" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
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
<style>
    div.dataTables_info {
        color: black !important;
    }
</style>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        obtenerFechas();
        $("#cmbAlmacen").removeAttr("disabled");
        $("#cmbAlmacen").val("0");
    }

    function search() {
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        cmbtdoc = $("#dctos").val();
        cmbAlmacen = $("#cmbAlmacen").val();
        $("#btnbuscar").attr('disabled', true);
        axios.get('/vtas/listarvtasanuladas', {
            "params": {
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbtdoc": cmbtdoc,
                "cmbAlmacen": cmbAlmacen
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $("#btnbuscar").attr('disabled', false);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
            $("#btnbuscar").attr('disabled', false);
        });
    }

    function descargarxml(nidauto, nombrexml) {
        axios.get('/cpe/descargarxml', {
            "params": {
                // "empresa": empresasel,
                "nidauto": nidauto
            }
        }).then(function(respuesta) {
            var fileURL = window.URL.createObjectURL(new Blob([respuesta.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', nombrexml);
            document.body.appendChild(fileLink);
            fileLink.click();
        }).catch(function(error) {
            toastr.error("Error al descargar XML " + error, "Mensaje del sistema");
        });
    }

    function descargarpdf10(nidauto, tipo, nombrepdf, tdoc) {
        var params = "nidauto=" + nidauto + '&tipo=' + tipo + '&nombrepdf=' + nombrepdf + '&tdoc=' + tdoc;
        var xhr = new XMLHttpRequest();
        var cruta = '/cpe/descargarpdf';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

    function descargarpdfticket(nidauto, tipo, nombrepdf, tdoc) {
        var params = "nidauto=" + nidauto + '&tipo=' + tipo + '&nombrepdf=' + nombrepdf + '&tdoc=' + tdoc;
        var xhr = new XMLHttpRequest();
        var cruta = '/cpe/descargarpdfticket';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

    // function descargarpdf(nidauto, tipo, nombrepdf) {
    //     axios.get('/cpe/descargarpdf', {
    //         "params": {
    //             "empresa": empresasel,
    //             "nidauto": nidauto,
    //             "tipo": tipo
    //         },
    //         contentType: 'application/pdf',
    //     }).then(function(respuesta) {
    //         var fileURL = window.URL.createObjectURL(new Blob([respuesta.data], {
    //             type: 'application/octet-stream'
    //         }));
    //         var fileLink = document.createElement('a');
    //         fileLink.href = fileURL;
    //         fileLink.setAttribute('download', nombrepdf);
    //         document.body.appendChild(fileLink);
    //         fileLink.click();
    //     }).catch(function(error) {
    //         toastr.error("Error al descargar PDF"+error,'Mensaje del Sistema');
    //     });
    // }
</script>
<?php
$this->endSection('javascript');
?>