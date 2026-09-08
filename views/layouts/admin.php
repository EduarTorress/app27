<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">

<head>
    <base href="/">
    <link rel="icon" href="img/Sysven.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sysven</title>
    <!-- Jequery-->
    <script src="js/jquery-3.6.0.min.js"></script>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Iconos -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- para select buscador -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!--- DataTables -->
    <link rel="stylesheet" type="text/css" href="plugins/datatables-custom/DataTables-1.12.1/css/jquery.dataTables.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="plugins/datatables-custom/Buttons-2.2.3/css/buttons.dataTables.min.css" /> -->
    <link rel="stylesheet" type="text/css" href="plugins/datatables-custom/KeyTable-2.7.0/css/keyTable.dataTables.min.css" />
    <!--- Date picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/adminlte.min.css">

    <!-- Para Color de fondo de Datatable -->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>

    <link href="https://nightly.datatables.net/select/css/select.dataTables.css" rel="stylesheet" type="text/css" />
    <!-- <script src="https://nightly.datatables.net/select/js/dataTables.select.js"></script> -->
    <!-- Bootstrap table -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.4/dist/bootstrap-table.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
</head>

<body id="body" class="white-mode hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
        <input style="display: none" type="text" name="fakeusernameremembered" />
        <input style="display: none" type="password" name="fakepasswordremembered" />

        <!-- Animación -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="img/logo_Sysven.png" hrealt="Sysven" height="100" width="100">
        </div> -->

        <!-- Navbar -->
        <nav id="navbar" class="main-header navbar navbar-expand navbar-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" id="pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <!-- <li class="nav-item d-none d-sm-inline-block" id='titulo'>
                    <a href="/" class="nav-link">Actualizar Pedido 0024052506 </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link">
                        <div id="titulo">
                        </div>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo "/salir"; ?> role="button">
                        <?php echo session()->get('usuario'); ?>
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <?php
        // if ($_SESSION['tipoacceso'] == 'T') {
        // } else {
        //     $menu = cargarmenucontabilidad();
        //     $color = "#055d1f";
        // }
        $menu = cargarmenu();
        $color = "#03326a";
        ?>
        <!-- Main Sidebar Container -->
        <aside id="barralateral" class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: <?php echo $color; ?>">
            <!-- Brand Logo -->
            <a id="barralaterallogo" href="/" class="brand-link" style="background-color: <?php echo $color; ?>">
                <img src="img/logo_Sysven.png" alt="Sysven" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light" style="color:white"><b>Sysven</b></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input style="background-color:<?php echo $color; ?>; color:white;" class="form-control form-control-sidebar" type="search">
                        <div class="input-group-append" style="background-color: <?php echo $color; ?>">
                            <button class="btn btn-sidebar" style="background-color: <?php echo $color; ?>">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sidebar user panel (optional) -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <?php
                        foreach ($menu as $m) {
                        ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="<?php echo $m['class'] ?>"></i>
                                    <p class="classmenu">
                                        <?php echo $m['main']; ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php foreach ($m['elementos'] as $e) {
                                        $op = strpos($e['opt'], session()->get('tipousuario'));
                                    ?>
                                        <li class="nav-item" <?php echo ($op === false ? "style='display:none'" : "style='display:block'") ?>>
                                            <a href=<?php echo $e['ruta'] ?> class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p class="classmenu"><?php echo $e['item'] ?></p>
                                            </a>
                                        <?php  } ?>
                                        </li>
                                </ul>
                            <?php }
                            ?>
                            </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- section => contenido -->
        <?php echo $this->section('contenido') ?>
        <?php if (empty($this->section('contenido'))) { ?>
            <div class="content-wrapper">
                <div class="content">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-center align-items-center" style="height:600px;">
                            <img src="<?php echo "https://companiasysven.com/logos/" . session()->get("gene_nruc") . '/' . 'logo.jpg'; ?>" class="rounded mx-auto d-block" alt="">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Main Footer -->
        <footer class="main-footer small">
            <!-- To the right -->
            <!-- Default to the left -->
            <strong>Copyright &copy; 2020-<?php echo date('Y'); ?> <a href="">Sysven</a>.</strong> Todos los
            derechos reservados. - <?php echo session()->get("gene_empresa") . ' - '  . $_SESSION['tienda']; ?>
        </footer>
    </div>
    <!-- ./wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- REQUIRED SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>

    <script src="plugins/datatables-custom/JSZip-2.5.0/jszip.min.js"></script>
    <script src="plugins/datatables-custom/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="plugins/datatables-custom/pdfmake-0.1.36/vfs_fonts.js"></script>
    <!-- <script src="plugins/datatables-custom/DataTables-1.12.1/js/jquery.dataTables.min.js"></script> -->
    <script src="plugins/datatables-custom/Buttons-2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-custom/Buttons-2.2.3/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-custom/KeyTable-2.7.0/js/dataTables.keyTable.min.js"></script>
    <!-- Para Select buscador -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Daterangepicker -->
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js">
        ("[data-widget='sidebar-search']").SidebarSearch(options)
    </script>
    <!-- Datatables personalizado -->
    <script src="js/datatable-custom.js"></script>
    <!-- <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> -->

    <!-- Javascript Jquery Table to Excel -->
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

    <!-- Javascript Jquery Table to PDF -->
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.8.3/dist/jspdf.plugin.autotable.js"></script>

    <!-- Para Arrastrar y soltar -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> -->

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <!-- Aplicacion -->
    <script src="js/app.js"> </script>
    <script src="https://companiasysven.com/jsgeneral/index.js"></script>
    <!-- para compornentes React -->
    <script type="module" src="js/fechas.js"></script>
    <?php echo $this->section('javascript') ?>
    <script>
        pago = "<?php echo (empty($_SESSION['config']['pago']) ? 'S' : $_SESSION['config']['pago']); ?>";
        if (pago == 'N') {
            window.location.href = '/login';
        }
        if (pago == 'P') {
            mostrarvenciopago();
        }
        async function mostrarvenciopago() {
            for (let i = 0; i < 1000; i++) {
                toastr.error("No ha realizado el pago, el sistema esta pronto a cortarse.", "Realice el pago correspondiente");
                await new Promise(r => setTimeout(r, 8000));
            }
        }
    </script>
</body>
<style>
    p.classmenu {
        font-size: 14px
    }

    button {
        background-color: green;
        color: white;
    }

    #example2_filter,
    #table_filter,
    #table_info,
    #example2_info {
        color: white;
    }

    input[type=search] {
        color: whit
    }
</style>

</html>