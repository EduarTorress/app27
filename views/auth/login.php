<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/Sysven.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | SYSVEN</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page" style="background-image: linear-gradient(to left top, #9abbeb, #b1c6f0, #c6d2f5, #d9dffa, #eaecff);">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary shadow-lg">
            <div class="card-header text-left">
                <a class="h1" style="color:#007bff">
                    <img src="/img/Sysven.png" alt="Logo Sysven" style="width: 40px; ">
                    <b>Sysven</b>
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Iniciar sesión</p>
                <form action="/login" method="post">
                    <div class="input-group mb-3">
                        <input value="<?php echo $inputs["usuario"] ?? '' ?>" type="text" class="form-control <?php echo (isset($errores['usuario'])) ? 'is-invalid' : '' ?>" id="usuario" name="usuario" placeholder="Nombre de Usuario" onkeyup="mayusculas(this)" autocomplete="on">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['usuario'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['usuario'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control <?php echo (isset($errores['password'])) ? 'is-invalid' : '' ?>" placeholder="Contraseña" autocomplete="on">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock" type="button" onclick="mostrarContrasena();"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['password'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['password'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="row" style="display:none;">
                        <div class="col-12">
                            <?php
                            $cempresa = '';
                            $empresa = new \App\View\Components\AlmacenComponent($cempresa);
                            echo $empresa->render();
                            ?>
                        </div>
                    </div>
                    <div class="p-2" style="<?php echo (count(cargarmenucontabilidad()) < 1 ? 'display:none;' : '') ?>"></div>
                    <div class="row" style="<?php echo (count(cargarmenucontabilidad()) < 1 ? 'display:none;' : '') ?>">
                        <div class="col-12">
                            <div class="input-group">
                                <label class="form-control form-control-sm" for="">Tipo de Acceso:</label>
                                <select class="form-select form-control form-control-sm" id="cmbtipoacceso" name="cmbtipoacceso">
                                    <option value="T">ADMINISTRATIVA</option>
                                    <option value="C">CONTABILIDAD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <br>
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Aplicacion -->
    <script src="js/app.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>
    $("#usuario").focus();

    function mostrarContrasena() {
        var tipo = document.getElementById("password");
        if (tipo.type == "password") {
            tipo.type = "text";
        } else {
            tipo.type = "password";
        }
    }

    $('#usuario').keypress(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            $("#password").focus();
            $("#password").click();
        }
    });
</script>

</html>