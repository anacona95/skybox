<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html;">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foxcarga | Calculadora</title>

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

</head>

<body>
<script src="<?php echo base_url(); ?>public/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.js"></script>

<div class="container-fluid">
<br>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2>
        Calculadora
      </h2>
    </div>
    <div class="panel-body">
      <div id="multiplicar" class="form-horizontal col-md-6">
        <div class="form-group">
          <label class="col-sm-3 control-label">
            Peso del artículo*
          </label>
          <div class="col-sm-9">
            <div class="input-group">
              <input id="peso" type="number" class="form-control" name="peso" placeholder="1" value="1">
              <div class="input-group-addon bg-primary">
                <b>Lb</b>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">
            Valor del artículo*
          </label>
          <div class="col-sm-9">
            <div class="input-group">
              <input id="valor" type="number" class="form-control" name="valor" placeholder="50" onblur="validarSeguro()" value="50">
              <div class="input-group-addon bg-primary">
                <b>USD</b>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-xs-12">
            <div class="checkbox">
              <label class="popovers" data-toggle="popover" data-html="true" data-trigger="hover" data-placement="right"
                    data-content="Al hacer clic en este check aceptas que tu paquete viaje asegurado por el valor total que declaraste y tiene un costo del <?=$config->seguro_opcional?>% sobre el mismo.<br><br>Los paquetes con valor superior a U$<?=$config->seguro_max+1?> deben viajar asegurados por un costo del <?=$config->seguro_obligatorio?>% con cobertura total.">
                <input id="seguro_pre" type="checkbox" name="seguro" value="1" disabled>
                <b>Asegurar paquete</b>
              </label>
            </div>
          </div>
        </div>
        <div id="tecnologia">
          <div class="form-group">
            <div class="col-md-offset-3 col-xs-12">
              <div class="checkbox">
                <label>
                  <input type="radio" name="tecnologia" value="1" onchange="marcarSeguro()">
                  <b id="celular" class="popovers" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right"
                    data-content="Liquida con una tarifa de $ <?php echo $tarifas['tarifa_4'] ?> USD por artículo y no por peso.">Celulares</b>
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-offset-3 col-xs-12">
              <div class="checkbox">
                <label>
                  <input type="radio" name="tecnologia" value="2" onchange="marcarSeguro()">
                  <b id="tv" class="popovers" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right"
                    data-content="Liquida con una tarifa de $ <?php echo $tarifas['tarifa_5'] ?> USD por artículo y no por peso.">Laptops</b>
                </label>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-2 col-md-offset-3">
          <button value="calcular" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false"
            aria-controls="collapseExample" onclick="calcular()">
            Calcular
          </button>
        </div>
      </div>
      <div id="loading" class="col-md-6 hide">
        <div class="form-group col-md-12 text-center">
          <img src="<?php echo base_url() ?>public/images/loading_spinner.gif">
        </div>
      </div>
      <div id="totales" class="col-md-6 form-horizontal hide">
        <legend>Resultado de cotización</legend>
        <div class="form-group">
          <div class="alert alert-info">
            <p style="color: white;"><b>Esta cotización no incluye domicilio, ni envío nacional, estos se liquidan por separado.</b></p>
          </div>
        </div>
        <div class="form-group">
          <span class="col-md-4 col-xs-12">
            <b>Tarifa aplicada:</b>
          </span>
          <div class="col-md-8 col-xs-12">
            <span id="tarifa" class="form-control"></span>
          </div>
        </div>
        <div class="form-group">
          <span class="col-md-4 col-xs-12">
            <b>Libra - fracción:</b>
          </span>
          <div class="col-md-8 col-xs-12">
            <span id="libra_fraccion" class="form-control"></span>
          </div>
        </div>
        <div class="form-group">
          <span class="col-md-4 col-xs-12">
            <b>Envío Miami - Cali:</b>
          </span>
          <div class="col-md-8 col-xs-12">
            <span id="valor_flete" class="form-control"></span>
          </div>
        </div>
        <div class="form-group">
          <span class="col-md-4 col-xs-12">
            <b>Valor del seguro:</b>
          </span>
          <div class="col-md-8 col-xs-12">
            <span id="valor_seguro" class="form-control"></span>
          </div>
        </div>
        
        <hr>
        <div class="form-group">
          <span class="col-md-4 col-xs-12">
            <b>Total a pagar:</b>
          </span>
          <div class="col-md-8 col-xs-12">
            <span id="valor_total" class="form-control"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<span id="constantes" data-url="<?php echo base_url(['Login', 'calcular']) ?>" data-seguro-opcional="<?=$config->seguro_opcional?>" data-seguro-obligatorio="<?=$config->seguro_obligatorio?>" data-seguro-max="<?=$config->seguro_max?>" data-seguro-min="<?=$config->seguro_min?>"></span>


<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>public/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>public/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="<?php echo base_url(); ?>public/nprogress/nprogress.js"></script>
<!-- jQuery custom content scroller -->
<script src="<?php echo base_url(); ?>public/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Custom Theme Scripts -->

<script src="<?php echo base_url() ?>public/datatables-net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-scroller/js/dataTables.scroller.min.js"></script>
<script src="<?php echo base_url() ?>public/autoNumeric/autoNumeric.min.js"></script>
<script src="<?php echo base_url(); ?>public/build/js/custom.js?v=<?= time() ?>"></script>
</body>

</html>
