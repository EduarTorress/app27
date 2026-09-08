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
                            <form class="form-inline" id="form-search">
                                <label class="my-1 mr-2" for="txtfecha">Fecha:</label>
                                <input type="date" class="form-control form-control-sm" id="txtfecha" name="txtfecha">&nbsp;&nbsp;
                                <label class="my-1 mr-2">Isla:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Seleccione</option>
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                </select>&nbsp;&nbsp;
                                <label class="my-1 mr-2">Cajero:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Seleccione</option>
                                    <option>Cajero 01</option>
                                    <option>Cajero 02</option>
                                    <option>Cajero 03</option>
                                    <option>Cajero 04</option>
                                    <option>Cajero 05</option>
                                    <option>Cajero 06</option>
                                    <option>Cajero 07</option>
                                    <option>Cajero 08</option>
                                </select>&nbsp;&nbsp;
                                <label class="my-1 mr-2">Turno:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Seleccione</option>
                                    <option>Mañana</option>
                                    <option>Tarde</option>
                                    <option>Noche</option>
                                </select>&nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary my-1">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12" id="search">
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
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });
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
        // document.getElementById('txtfechaf').value = ano + "-" + mes + "-" + dia;
    }


    function search() {
        // const empresasel = empresaseleccionada();
        // if (empresasel == 'Seleccione') {
        //     toastr.info("Seleccione Una Empresa");
        //     return;
        // }
        var dfecha = document.getElementById("txtfecha").value;
        // var dfechaf = document.getElementById("txtfechaf").value;

        axios.get('/vtas/listavtasr', {
                "params": {
                    // "empresa": empresasel,
                    "dfecha": dfecha
                    // "dfechaf": dfechaf

                }
            }).then(function(respuesta) {
                // 100, 200, 300
                const contenido_tabla = respuesta.data;
                $('#search').html(contenido_tabla);
                // console.log(respuesta.data.message)
            }) // representa cuando todo va bien en peticion
            .catch(function(error) {
                // 400, 500
                toastr.error('Error al cargar el listado')
            });
    }

    function enviarboletas(dfecha, nimpo) {
        // const empresasel = empresaseleccionada();
        axios.get('/cpe/enviarboletas', {
            "params": {
                // "empresa": empresasel,
                "fecha": dfecha,
                "nimpo": nimpo

            }

        }).then(function(respuesta) {
            if (respuesta.data.mensaje) {
                toastr.info(respuesta.data.mensaje);
                cmensaje = respuesta.data.mensaje;
                // console.log('hola' + respuesta.data.mensaje);
                if (cmensaje.substring(0, 1) == '0') {
                    search();
                }
            } else {
                toastr.info("No se Obtuvo una respuesta Válida del Servidor", 'Mensaje del Sistema');
            }

        }).catch(function(error) {
            console.log(error);
            toastr.error(error, 'Mensaje del Sistema');
        })

    }
</script>
<?php
$this->endSection('javascript');
?>