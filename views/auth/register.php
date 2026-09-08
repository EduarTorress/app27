<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse | CETI Store</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index2.html" class="h1"><b>CETI</b>Store</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Registrarme como usuario</p>

                <form action="/register" method="post">
                    <div class="input-group mb-3">
                        <input value="<?php echo $inputs["apellido_paterno"] ?? '' ?>" type="text" class="form-control <?php echo (isset($errores['apellido_paterno'])) ? 'is-invalid' : '' ?>" name="apellido_paterno" id="apellido_paterno" placeholder="Apellido paterno">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['apellido_paterno'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['apellido_paterno'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input value="<?php echo $inputs["apellido_materno"] ?? '' ?>" type="text" class="form-control <?php echo (isset($errores['apellido_materno'])) ? 'is-invalid' : '' ?>" name="apellido_materno" id="apellido_materno" placeholder="Apellido materno">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['apellido_materno'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['apellido_materno'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input value="<?php echo $inputs["nombres"] ?? '' ?>" type="text" class="form-control <?php echo (isset($errores['nombres'])) ? 'is-invalid' : '' ?>" name="nombres" id="nombres" placeholder="Nombres">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['nombres'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['nombres'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input value="<?php echo $inputs["email"] ?? '' ?>" type="email" class="form-control <?php echo (isset($errores['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Correo electrónico">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['email'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['email'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control <?php echo (isset($errores['password'])) ? 'is-invalid' : '' ?>" placeholder="Contraseña">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
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
                    <div class="input-group mb-3">
                        <input type="password" id="password_confirmacion" name="password_confirmacion" class="form-control <?php echo (isset($errores['password_confirmacion'])) ? 'is-invalid' : '' ?>" placeholder="Confirmar contraseña">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <?php
                        if (isset($errores['password_confirmacion'])) :
                        ?>
                            <div class="invalid-feedback">
                                <ul>
                                    <?php foreach ($errores['password_confirmacion'] as $valor) : ?>
                                        <li><?php echo $valor ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Registrarme</button>
                        </div>
                    </div>
                </form>
                <a href="login.html" class="text-center">Ya tengo un usuario</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>