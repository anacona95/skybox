<!DOCTYPE html>
<html lang="es">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">

	<title>Foxcarga | Recuperar contraseña</title>

	<!-- Bootstrap -->
	<link href="<?php echo base_url(); ?>public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="<?php echo base_url(); ?>public/nprogress/nprogress.css" rel="stylesheet">
	<!-- Animate.css -->
	<link href="<?php echo base_url(); ?>public/animate.css/animate.min.css" rel="stylesheet">


	<link href="<?php echo base_url(); ?>public/build/css/custom.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>public/images/logo.ico" rel="icon">

</head>

<body class="login">
	<div>
		<div class=" login_wrapper ">
			<div class="animate form registration_form ">
				<section class="login_content ">
					<div class="panel panel-default panel-login">
						<div class="panel-heading">
							<h4>Recuperar contraseña</h4>
						</div>
						<div class="panel-body">
							<form id="form-reset-pass" method="post" action="<?php echo base_url() ?>RecuperarContrasena/process">
								<div class="alert alert-info text-left" role="alert">
									Escriba su correo para enviar una contraseña provisional.
								</div>
								<?php if ($this->session->flashdata('errorclave')): ?>
								<div class="alert alert-danger" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<?php echo $this->session->flashdata('errorclave') ?>
								</div>
								<?php endif;?>
								<?php if ($this->session->flashdata('email')): ?>
								<div class="alert alert-success" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<?php echo $this->session->flashdata('email') ?>
								</div>
								<?php endif;?>
								<div>
									<input type="email" class="form-control" name="data[email]" placeholder="Correo electrónico*" required="required" <?php if
									 (isset($_SESSION[ 'registro'])): ?> value="
									<?php echo $_SESSION['registro']['email'] ?>"
									<?php endif;?> />
								</div>

								<br>
								<a href="<?php echo base_url() ?>Login" class="btn btn-default">
									<i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar
								</a>
								<button class="btn btn-primary" type="submit">
									<i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Enviar</button>
							</form>
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