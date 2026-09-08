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
                                $empresa = new App\View\Components\EmpresaComponent();
                                echo $empresa->render();
                                ?>&nbsp;&nbsp;
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

    function search() {
        const empresasel = empresaseleccionada();
        if (empresasel == 'Seleccione') {
            toastr.info("Seleccione Una Empresa", 'Mensaje del Sistema');
            return;
        }
        axios.get('/cpe/boletasne', {
            "params": {
                "empresa": empresasel
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

    function enviarboletas(dfecha, nimpo) {
        const empresasel = empresaseleccionada();
        axios.get('/cpe/enviarboletas', {
            "params": {
                "empresa": empresasel,
                "fecha": dfecha,
                "nimpo": nimpo
            }
        }).then(function(respuesta) {
            if (respuesta.data.mensaje) {
                cmensaje = respuesta.data.mensaje;
                toastr.info(cmensaje);
                // console.log('hola' + respuesta.data.mensaje);
                if (cmensaje.substring(0, 1) == '0') {
                    search();
                }
            } else {
                toastr.info("No se obtuvo una respuesta válida del servidor", 'Mensaje del Sistema');
            }
        }).catch(function(error) {
            console.log(error);
            toastr.error(error);
        })
    }
</script>
<?php
$this->endSection('javascript');
?>