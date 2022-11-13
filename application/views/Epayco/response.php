<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Foxcarga | Casillero Virtual</title>
   <!-- Bootstrap -->
   <link href="<?php echo base_url(); ?>public/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>public/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url(); ?>public/animate.css/animate.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/build/css/custom.css?v=<?= time() ?>" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
</head>

<body>
<img class="center-block img-responsive" src="<?= base_url()?>public/images/logo1.png" width="300">
  <div class="container">
    <div class="row" style="margin-top:3px">
      <div class="col-xs-8 col-xs-offset-2 ">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 style="text-align:left"><img src="/public/images/transaction.svg" alt="Mi direccion" width="35">&nbsp;Transacción finalizada</h4>
          </div>
          <div class="panel-body">
            <div class="col-xs-12 text-center">
              <img class="center-block" src="<?= base_url()?>public/images/pinata.svg" width="100">
              <br>
              <h4>!Hola¡ Gracias por usar nuestro servicio de casillero virtual en USA, esperamos haberte sorprendido y que haya sido una experiencia fantástica.
                <br>
                <br>
                Una vez se valide el pago de la orden, tu factura correspondiente llegará por correo electrónico.
                <br>
                <br>
                Recuerda recomendarnos a tus contactos... Muchas gracias por preferirnos, hasta la próxima.
              </h4>
              <br>
              <a href="/user"class="btn btn-primary btn-block btn-lg">Salir</a>
              <br>
              <p style="float:left;">Pago seguro con:</p>
              <br>
              <br>
              <img class="img-responsive" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/logo_epayco_200px.png" width="100" style="float:left;">
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>