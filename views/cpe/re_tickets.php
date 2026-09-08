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
                                <?php
                                $empresa = new App\View\Components\EmpresaComponent('');
                                echo $empresa->render();
                                ?>
                                <label class="my-1 mr-2" for="txtfecha">Fecha:</label>
                                <input type="date" class="form-control forn-control-sm" id="txtfecha" name="txtfecha">
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
    }

    function search() {
        const empresasel = empresaseleccionada();
        if (empresasel == 'Seleccione') {
            toastr.info("Seleccione Una Empresa", 'Mensaje del Sistema');
            return;
        }
        var fecha = document.getElementById('txtfecha').value;
        axios.get('/cpe/listaticket', {
            "params": {
                "empresa": empresasel,
                "fecha": fecha
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function consultarticket(cticket, ndesde, nhasta, cserie, ctdoc, carchivo) {
        const empresasel = empresaseleccionada();
        var dfecha = document.getElementById("txtfecha").value;
        const sele = empresaseleccionada();
        axios.get('/cpe/ticket10', {
            "params": {
                "empresa": empresasel,
                "ticket": cticket,
                "fecha": dfecha,
                "desde": ndesde,
                "hasta": nhasta,
                "serie": cserie,
                "tdoc": ctdoc,
                "archivo": carchivo
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            cmensaje = respuesta.data.mensaje;
            // console.log('hola'+respuesta.data.mensaje);
            // toastr.success(cmensaje);
            if (cmensaje.substring(0, 1) == '0') {
                toastr.success(cmensaje, 'Mensaje del Sistema');
                search();
            } else {
                toastr.success(cmensaje, 'Mensaje del Sistema');
            }
        }).catch(function(error) {
            // 400, 500
            toastr.error(error, 'Mensaje del Sistema')
        });
    }

    function consultarapi(cticket, ndesde, nhasta, cserie, ctdoc) {
        const empresasel = empresaseleccionada();
        axios.get('cpe/api', {
            "params": {
                "ticket": cticket,
                "empresa": empresasel,
                "desde": ndesde,
                "hasta": nhasta,
                "serie": cserie,
                "tdoc": ctdoc,
            }
        }).then(function(respuesta) {
            cmensaje = respuesta.data;
            console.log(cmensaje)
            toastr.success(cmensaje, 'Mensaje del Sistema');
            search();
        }).catch(function(error) {
            toastr.error('Error al obtener respuesta Api-SUNAT', 'Mensaje del Sistema');
        })
    }

    function anularticket(cticket) {
        const empresasel = empresaseleccionada();
        const ruta = 'cpe/eliminarticket';
        Swal.fire({
            icon: 'error',
            title: '¿Estás seguro de eliminar?',
            text: 'Esta acción no se puede revertir',
            showCancelButton: true,
            confirmButtonText: 'Si, estoy seguro',
            cancelButtonText: 'No, cancelar'
        }).then(function(rpta) {
            if (rpta.isConfirmed) {
                axios.get(ruta, {
                        "params": {
                            "cticket": cticket,
                            "empresa": empresasel
                        }
                    })
                    .then(function(respuesta) {
                        cmensaje = respuesta.data.mensaje;
                        console.log(cmensaje)
                        toastr.success(cmensaje, 'Mensaje del Sistema');
                        search();
                    }).catch(function(error) {
                        toastr.error('error al eliminar registro', 'Mensaje del Sistema');
                        console.log(error);
                    })
            }
        })

        // axios.get('cpe/eliminarticket', {
        //     "params": {
        //         "cticket": cticket,
        //         "empresa": empresasel
        //       }
        // }).then(function(respuesta) {
        //     cmensaje = respuesta.data.mensaje;
        //     console.log(cmensaje)
        //     toastr.success(cmensaje,'Mensaje del Sistema');
        //     search();
        // }).catch(function(error) {
        //     toastr.error('error al eliminar registro','Mensaje del Sistema');
        // })
    }
</script>
<?php
$this->endSection('javascript');
?>