<?php

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
                                <?php
                                $ec = new EmpresaComponent('');
                                echo $ec->render();
                                ?> &nbsp;
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
        $("#cmbAlmacen").attr("disabled", true);
        $("#cmbAlmacen").val("<?php echo $_SESSION['idalmacen'] ?>");
        tipousuario = "<?php echo $_SESSION['tipousuario'] ?>";
        if (tipousuario == 'A') {
            $("#cmbAlmacen").removeAttr("disabled");
        }
        $("#cmbAlmacen option[value='0']").css('display', 'none');
        $(".tipodocumentos option[value='GI']").remove();
    }

    function search() {
        $("#btnbuscar").attr('disabled', true);
        axios.get('/lecturas/listaregistro', {
            "params": {
                "cmbalmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            const contenido_tabla = respuesta.data;
            $('#search').html(contenido_tabla);
            $("#btnbuscar").attr('disabled', false);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
            $("#btnbuscar").attr('disabled', false);
        });
    }

    function registrarlecturas() {
        Swal.fire({
            title: "Ingresar Lecturas",
            text: "¿Seguro de registrar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, estoy seguro',
            cancelButtontext: "No"
        }).then(function(respuesta) {
            if (respuesta.isConfirmed) {
                const detalle = []
                $("#table tbody tr").each(function() {
                    json = "";
                    $(this).find("td").each(function() {
                        $this = $(this);
                        if (($this.attr("class") == 'monto') || ($this.attr("class") == 'cantidad') || ($this.attr("class") == 'precio')) {
                            val = $this.find("input").val();
                            val = val.replace(/"/g, '\\"');
                            json += ',"' + $this.attr("class") + '":"' + val + '"'
                        } else {
                            val = $this.text();
                            val = val.replace(/"/g, '\\"');
                            json += ',"' + $this.attr("class") + '":"' + val + '"'
                        }
                    })
                    obj = JSON.parse('{' + json.substr(1) + '}');
                    detalle.push(obj)
                });
                data = new FormData();
                data.append("cmbalmacen", $("#cmbAlmacen").val());
                data.append("detalle", JSON.stringify(detalle));
                axios.post("/lecturas/registrar", data)
                    .then(function(respuesta) {
                        Swal.fire({
                            title: "Mensaje del Sistema",
                            text: "Se registraron las lecturas correctamente.",
                            icon: "success"
                        });
                        search();
                    }).catch(function(error) {
                        toastr.error(error.response.data, 'Mensaje del sistema')
                    });
            }
        });
    }
</script>
<?php
$this->endSection('javascript');
?>