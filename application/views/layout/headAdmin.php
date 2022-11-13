<!DOCTYPE html>
<html lang="es">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foxcarga</title>
    <link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>public/nprogress/nprogress.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="<?php echo base_url(); ?>public/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css" rel="stylesheet"
    />

    <!-- Data tables-->
    <link href="<?php echo base_url(); ?>public/datatables-net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url(); ?>public/build/css/custom.css?v=<?= time() ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
    <script src="<?php echo base_url() ?>public/chartjs/Chart.js"></script>
    <script src="<?php echo base_url(); ?>public/jquery/dist/jquery.min.js"></script>

</head>

<body class="nav-sm">
    <div class="container body">
        <div class="main_container" style="color:#0C030A">
            <div class="col-md-3 left_col menu_fixed">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title">
                        <a href="<?php echo base_url() ?>dashboard" class="site_title">
                            <img src="<?php echo base_url() ?>public/images/logo.png">
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $userdata['imagen'] ?>" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <br>
                            <h2 class="text-capitalize" style="color:#0C030A">Hola
                                <?php echo $userdata['nombre'] ?>
                            </h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                            <?php if ($userdata['role'] == 1): ?>
                                <li>
                                    <a href="<?php echo base_url(['reportes-y-metricas']) ?>" title="Reportes generales">
                                        <i class="fa fa-line-chart"></i> Dashboard</a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                                <li>
                                    <a href="<?php echo base_url() ?>tracking" title="Inventario de artículos">
                                        <i class="fa fa-map-marker"></i> Tracking</a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                                <li>
                                    <a href="<?php echo base_url() ?>informacion-clientes" title="Consultar la información de clientes registrados">
                                        <i class="fa fa-user"></i> Clientes</a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                                <li>
                                    <a href="<?php echo base_url() ?>bandeja-de-salida" title="Listado de clientes con paquetes en bodega Cali">
                                        <i class="fa fa-envelope"></i> Bandeja de salida</a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                                <li>
                                    <a href="<?php echo base_url() ?>ordenes-de-compra" title="Listado de ordenes de compra">
                                        <i class="fa fa-money"></i> Ordenes
                                        <span class="badge">
                                            <?php echo $_SESSION['pay']['aprobacion']; ?>
                                        </span> 
                                        <span class="badge bg-red">
                                            <?php echo $_SESSION['pay']['pays']; ?>
                                        </span>
                                    </a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                                <li>
                                    <a href="<?php echo base_url() ?>Admin/articulosEntregados" title="Consultar paquetes entregados">
                                        <i class="fa fa-archive"></i> Entregados</a>
                                </li>
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1 || $userdata['role'] == 3): ?>
                                <!-- <li>
                                    <a href="<?php echo base_url() ?>inventario-miami" title="Listado de artículos en bodega Miami">
                                        <i class="fa fa-cubes"></i> Inventario Miami</a>
                                </li> -->
                                <?php endif;?>
                                <?php if ($userdata['role'] == 1): ?>
                                <li>
                                    <a href="<?php echo base_url(['administrar-usuarios']) ?>" title="Cree y configure los usuarios de la plataforma">
                                        <i class="fa fa-users"></i> Administrar usuarios</a>
                                </li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav navbar-fixed-top">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="#" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $userdata['imagen'] ?>" alt="">
                                    <span class="text-capitalize">
                                        <?php echo $userdata['nombre'] ?>
                                    </span>
                                    <span class=" fa fa-angle-down"></span>

                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li>
                                        <a style="color:#282A8F" href="<?php echo base_url() ?>cuenta-administrador"> Cuenta</a>
                                    </li>
                                    <li>
                                        <a style="color:#282A8F" href="<?php echo base_url() ?>AdmLogin/close">
                                            <i class="fa fa-sign-out pull-right"></i> Cerrar sesión</a>
                                    </li>
                                </ul>
                            </li>
                            <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                            <li>
                                <a href="<?php echo base_url() ?>Admin/cargarArchivo" title="Carga de archivos">
                                    <i class="fa fa-cloud-upload fa-lg"></i>
                                </a>
                            </li>
                            <?php endif;?>
                            <?php if ($userdata['role'] == 1 || $userdata['role'] == 2): ?>
                            <li>
                                <a href="<?php echo base_url(['admin', 'ingreso-paquetes']) ?>" title="Ingreso de paquetes">
                                    <i class="fa fa-cubes fa-lg"></i>
                                </a>
                            </li>
                            <?php endif;?>
                            <?php if ($userdata['role'] == 1): ?>
                            <li>
                                <a href="<?php echo base_url("ingreso-costos") ?>" title="Ingreso de costos">
                                    <i class="fa fa-usd fa-lg"></i>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url("cupones") ?>" title="Administración de cupones">
                                    <i class="fa fa-ticket fa-lg"></i>
                                </a>
                            </li>
                           
                            <?php endif;?>
                            <li>
                                <a>
                                    <b>TRM: $
                                        <?php echo number_format($_SESSION['trm']['hoy'], 0, '', '.') ?> COP
                                    </b>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
            <div class="right_col" role="main">
                <div class="content">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">