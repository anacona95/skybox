<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foxcarga | casillero virtual</title>
    <link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>public/fontawesome-free-5.14.0-web/css/all.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>public/nprogress/nprogress.css" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="<?php echo base_url(); ?>public/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css" rel="stylesheet"
    />

    <!-- Custom Theme Style -->
    
    <!-- Data tables-->
    
    <link href="<?php echo base_url(); ?>public/datatables-net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/build/css/custom.css?v=<?= time() ?>" rel="stylesheet">
    <link href="<?= base_url();?>public/select2-4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
    
    <script src="<?php echo base_url(); ?>public/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.js"></script>
    <script src="<?= base_url();?>public/select2-4.0.13/dist/js/select2.min.js"></script>

</head>

<body class="nav-sm">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col menu_fixed">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title">
                        <a href="https://foxcarga.com/" class="site_title" target=”_blank”>
                            <img src="<?php echo base_url() ?>public/images/logo.png"></a>
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $userdata['imagen'] ?>" alt="perfil" class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <br>
                            <h2 style="color:#0C030A" class="text-capitalize">
                                Hola <?php echo $userdata['primer_nombre'] ?>
                            </h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li>
                                    <a href="<?php echo base_url() ?>user" title="Crear una prealerta nueva">
                                    <img src="/public/images/prealerta.svg" alt="Prealertar" width="35">
                                        <br><b>Prealertar</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>rastreo-paquetes" title="Consulta el estado de tus paquetes">
                                    <img src="/public/images/mis-paquetes.svg" alt="Mis paquetes" width="35">
                                        <br><b>Mis paquetes</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" title="Redime tu cupón para obetener un grandioso descuento" data-toggle="modal" data-target="#cupon">
                                    <img src="/public/images/cupon.svg" alt="Cupones" width="35">
                                        <br><b>Cupones</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>mis-ordenes" title="consulta y paga tus ordenes de compra">
                                        <img src="/public/images/cxc.svg" alt="Cupones" width="35">
                                        <br><b>Mis ordenes</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>mis-puntos" title="Asigna puntos a tus paquetes">
                                    <img src="/public/images/cup.svg" alt="Cupones" width="35">
                                        <br><b>Mis puntos</b>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://foxcarga.com/ayuda/" title="¿Necesitas ayuda?">
                                        <img src="/public/images/ayuda.svg" alt="Cupones" width="35">
                                        <br><b>¿Necesitas ayuda?</b>
                                    </a>
                                </li>
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
                        <div class="visible-xs">
                            <div class="nav toggle">
                                <a id="menu_toggle">
                                    <i class="fa fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="#" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $userdata['imagen'] ?>" alt="">
                                    <i class="fas fa-sort-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li>
                                        <a style="color:#282A8F" href="<?php echo base_url() ?>cuenta-usuario">
                                             Mi perfil
                                        </a>
                                    </li>
                                    <li class="hidden-md hidden-lg">
                                        <a style="color:#282A8F" href="#" data-toggle="modal" data-target="#myaddress">
                                            Mi dirección
                                        </a>
                                    </li>
                                    <li>
                                        <a  style="color:#282A8F" href="<?php echo base_url() ?>login/close">
                                            <i class="fa fa-sign-out pull-right"></i>
                                             Cerrar sesión
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="hidden-xs">
                                <a>
                                    <img src="/public/images/buzon.svg" alt="Dólar hoy" width="25">
                                    <span class="badge"><?php echo $userdata['id'] ?></span>

                                </a>
                            </li>
                            <li class="hidden-xs">
                                <a href="#" data-toggle="modal" data-target="#myaddress">
                                    <img src="/public/images/address.svg" alt="Mi direccion" width="25">
                                </a>
                            </li>
                            <li>
                                <a>
                                    <img src="/public/images/trm-up.svg" alt="Dólar hoy" width="30">&nbsp;<b>$<?php echo number_format($_SESSION['trm']['hoy'], 0, '', '.') ?> COP</b>
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