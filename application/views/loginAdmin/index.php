<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foxcarga | login admin</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>public/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url(); ?>public/animate.css/animate.min.css" rel="stylesheet">


    <link href="<?php echo base_url(); ?>public/build/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">
</head>

<body class="login">
    <div>
        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <div class="panel panel-default panel-login">
                        <div class="panel-body">
                            <div class="col-xs-12 text-center">
                                <img class="login-logo" src="<?php echo base_url() ?>public/images/logo1.png">
                            </div>
                            <?php if ($this->session->flashdata('email')): ?>
                                <div class="col-xs-12 alert alert-info" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $this->session->flashdata('email') ?>
                                </div>
                                <?php endif;?>
                                <?php if ($this->session->flashdata('error')): ?>
                                <div class="col-xs-12 alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $this->session->flashdata('error') ?>
                                </div>
                                <?php endif;?>
                                <div class="col-xs-12">
                                    <form method="post" action="<?php echo base_url() ?>AdmLogin/process">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="dataAdmin[usuario]" placeholder="Correo electrónico" required="required" />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="dataAdmin[password]" placeholder="Contraseña" required="required" />
                                        </div>
                                        <div class="form-group">
                                            <button id="btn-login" type="submit" class="btn btn-primary">
                                                <i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>public/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>public/build/js/custom.min.js"></script>
</body>

</html>