<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html;">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foxcarga | Registro en línea</title>

    <link href="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.css" rel="stylesheet">
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
    <script src="https://www.google.com/recaptcha/api.js?render=6Lek7rkUAAAAAN020D_xNDYfFzD8tmY2_JzYGGUb"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Lek7rkUAAAAAN020D_xNDYfFzD8tmY2_JzYGGUb', {action: 'registro/process'}).then(function(token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
</head>

<body class="login">
<script src="<?php echo base_url(); ?>public/jquery/dist/jquery.min.js"></script>   

    <div>
        <div class="register_wrapper">
            <div class="animate form registration_form">
                <section class="login_content">
                    <div class="panel panel-default panel-login shadow">
						<div class="panel-body">
                            <div class="col-xs-12 text-center">
								<img class="login-logo" src="<?php echo base_url() ?>public/images/logo1.png">
							</div>
                            <?php if (isset($_SESSION['registro'])): ?>
                                <div class="col-xs-12 alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $this->session->flashdata('error') ?>
                                </div>
                                <?php endif;?>
                                <?php if ($this->session->flashdata('email')): ?>
                                <div class="col-xs-12 alert alert-success" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $this->session->flashdata('email') ?>
                                </div>
                                <?php endif;?>
                            <div class="col-xs-12">
                            <form method="POST" action="<?php echo base_url() ?>registro/process">
                                <legend>Los campos marcados con (*) son obligatorios</legend>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[nombre]" placeholder="Primer nombre*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['nombre'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[nombre2]" placeholder="Segundo nombre" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['nombre2'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[apellido]" placeholder="Apellidos*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['apellido'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <input type="text" class="form-control" name="data[identificacion]" placeholder="Cédula*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['identificacion'] ?>"
                                    <?php endif;?>/>
                                </div>

                                <div class="form-group col-md-6 col-xs-12">
                                    <input id="cumple" autocomplete="off" type="text" class="form-control" placeholder="Cumpleaños* yyyy-mm-dd" required="required" name="data[nacimiento]">
                                </div>
                                
                                <div class="form-group col-md-6 col-xs-12">
                                    <input type="text" class="form-control" name="data[telefono]" placeholder="Número celular*" required="required" <?php if(isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['telefono'] ?>"
                                    <?php endif;?> pattern="[(]?\d{3}[)]?\s?-?\s?\d{3}\s?-?\s?\d{4}" oninvalid="setCustomValidity('Ingrese un número de teléfono valido')" onchange="try { setCustomValidity('') } catch (e) { }"/>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <input type="text" class="form-control popovers" name="data[direccion]" placeholder="Dirección*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['direccion'] ?>"
                                    <?php endif;?> data-toggle="popover" data-trigger="focus" data-placement="top" data-content="Recuerda utilizar el signo # para evitar errores en el despacho de tus paquetes."/>
                                </div>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[pais]" placeholder="País*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['pais'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[departamento]" placeholder="Departamento*" required="required" <?php if(isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['departamento'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-4 col-xs-12">
                                    <input type="text" class="form-control" name="data[ciudad]" placeholder="Ciudad*" required="required" <?php if (isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['ciudad'] ?>"
                                    <?php endif;?>/>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <input type="email" class="form-control" name="data[email]" placeholder="Correo electrónico*" required="required" <?php if(isset($_SESSION['registro'])): ?> value="<?php echo $_SESSION['registro']['email'] ?>"
                                    <?php endif;?> />
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <input type="password" class="form-control" name="data[password]" placeholder="Contraseña*" required="required" />
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    
                                    <select required class="form-control" name="data[descripcion]" id="descripcion">
                                        <option value="" disabled selected>¿Cómo te enteraste?</option>
                                        <optgroup label="Internet">
                                            <option value="Página web">Página web</option>
                                            <option value="Redes sociales">Redes sociales</option>
                                            <option value="Reddit">Reddit</option>
                                            <option value="Laneros">Laneros</option>
                                            <option value="YouTube">YouTube</option>
                                            <option value="Enter">Enter</option>
                                        </optgroup>
                                        <optgroup label="Códigos promocionales">
                                            <option value="Cupon">Ingresa un cupón</option>
                                        </optgroup>
                                        <optgroup label="Referido">
                                            <option value="Un amigo me dijo">Un amigo me dijo</option>
                                            <option value="Un asesor me dijo">Un asesor me dijo</option>
                                        </optgroup>
                                        <optgroup label="Publicidad">
                                            <option value="Volantes">Volantes</option>
                                            <option value="Tarjetas">Tarjetas</option>
                                            <option value="Google ads">Google ads</option>
                                        </optgroup>
                                    </select>
                                    <script>
                                        $(document).ready(function(){
                                            $("#descripcion").change(function(){
                                                var value = $(this).val();
                                                var compare = "Un amigo me dijo";
                                                var compare2 = "Un asesor me dijo";
                                                var compare3 = "Cupon";
                                                if(value == compare){
                                                    $("#asesor").addClass('hidden');
                                                    $("#asesor").removeAttr("required");
                                                    $("#cupon").addClass('hidden');
                                                    $("#cupon").removeAttr("required");

                                                    $("#parent").removeClass("hidden");
                                                    $("#parent").attr('required',"required");
                                                }else if(value==compare2){
                                                    $("#parent").addClass('hidden');
                                                    $("#parent").removeAttr("required");
                                                    $("#cupon").addClass('hidden');
                                                    $("#cupon").removeAttr("required");

                                                    $("#asesor").removeClass("hidden");
                                                    $("#asesor").attr('required',"required");
                                                }else if(value==compare3){
                                                    $("#parent").addClass('hidden');
                                                    $("#parent").removeAttr("required");
                                                    $("#asesor").addClass('hidden');
                                                    $("#asesor").removeAttr("required");

                                                    $("#cupon").removeClass("hidden");
                                                    $("#cupon").attr('required',"required");
                                                }else{
                                                    $("#parent").addClass('hidden');
                                                    $("#parent").removeAttr("required");
                                                    $("#asesor").addClass('hidden');
                                                    $("#asesor").removeAttr("required");
                                                    $("#cupon").addClass('hidden');
                                                    $("#cupon").removeAttr("required");
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="form-group col-md-6 col-xs-12">
                                    <input id="parent" type="number" class="form-control hidden" name="data[parent_id]" placeholder="Código de un amigo*"/>
                                    <input id="asesor" type="text" class="form-control hidden" name="data[asesor]" placeholder="Nombre del asesor*"/>
                                    <input id="cupon" type="text" class="form-control hidden" name="data[cupon]" placeholder="Ingresa el código del cupón*"/>
                                </div>
                                <br>
                                <div class="form-group col-md-12 checkbox">
                                    <label>
                                        <input type="checkbox" required="required" name="data[check]" value="1" <?php if (isset($_SESSION['registro']['check'])): echo "checked='checked'";endif;?>>
                                        <span style="color:#0C030A"> Acepto los <a href="https://foxcarga.com/terminos-y-condiciones" target="_blank">términos y condiciones de uso.</a> <br>Acepto recibir información acerca de Foxcarga.</span>
                                    </label>
                                </div>
                                <br>
                                
                                <div class="form-group col-md-12 col-xs-12">
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                                    <button id="btn-login" class="btn btn-primary" type="submit">
                                        <i class="fa fa-share-square-o" aria-hidden="true"></i>&nbsp;Continuar</button>
                                </div>
                                <div class="clearfix"></div>
                                <div class="separator">
                                    <p class="change_link" style="color:#0C030A">Ya estas registrado?
                                        <a href="<?php echo base_url() ?>login" class="to_register">
                                            <b>Inicia sesión</b>
                                        </a>
                                    </p>
                                    <div class="clearfix"></div>
                                    <br>
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
    <script src="<?php echo base_url(); ?>public/jquery-ui/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>public/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/build/js/custom.js"></script>
</body>

</html>
