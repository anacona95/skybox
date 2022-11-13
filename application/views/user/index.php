<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Prealertar
            </h2>
        </div>
        <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo base_url() ?>user/envios_process">
            <div class="panel panel-body">
                <?php if ($this->session->flashdata('pre-alerta')): ?>
                <div class="alert alert-success col-md-12 text" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('pre-alerta') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('error-pre-alerta')): ?>
                <div class="alert alert-danger col-md-12" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('error-pre-alerta') ?>
                </div>
                <?php endif;?>
                <div class="col-md-6 col-xs-12 form-prealert">
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label">
                            Artículo*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" required="required" name="nombre" placeholder="Breve descripción del contenido de tu paquete" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label">
                            Tracking*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input type="text" class="form-control" required="required" name="id_track" placeholder="Ingrese sólo el tracking" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label">
                            Valor del artículo en dólares*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input onblur="validarSeguro()" type="number" class="form-control" required="required" name="valor_paquete" id="valor" placeholder="Únicamente valores enteros">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label">
                            Fecha de entrega (Miami)*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input autocomplete="off" id="calendar-prealerta" type="text" class="form-control" name="fecha" required="required" placeholder="D/M/A" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label" for="tienda">
                            Tienda*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <select class="form-control select2" style="width: 100%" name="tienda" id="tienda" required>
                                <option value="AMAZON">AMAZON</option>
                                <option value="EBAY">EBAY</option>
                                <option value="NEWEGG">NEWEGG</option>
                                <option value="ALIEXPRESS">ALIEXPRESS</option>
                                <option value="ALIBABA">ALIBABA</option>
                                <option value="BESTBUY">BESTBUY</option>
                                <option value="CARTERS">CARTERS</option>
                                <option value="SEPHORA">SEPHORA</option>
                                <option value="WALMART">WALMART</option>
                                <option value="DISNEY">DISNEY</option>
                                <option value="6PM">6PM</option>
                                <option value="OTRA">OTRA</option>
                            </select>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $("#tienda").change(function(){
                                    var value = $(this).val();
                                    var compare = "OTRA";
                                    if(value == compare){
                                        $("#otraTienda").removeClass("hidden");
                                        $("#fieldOtraTienda").attr('required',"required");
                                    }else{
                                        $("#otraTienda").addClass('hidden');
                                        $("#fieldOtraTienda").removeAttr("required");
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div id="otraTienda" class="form-group hidden">
                        <label class="col-md-4 col-xs-12 control-label">
                            Otra tienda*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input id="fieldOtraTienda"  type="text" class="form-control" name="otra_tienda">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label" for="transportadora">
                            Transportadora*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <select class="form-control select2" style="width: 100%" name="transportadora" id="transportadora" required>
                                <option value="USPS">USPS</option>
                                <option value="UPS">UPS</option>
                                <option value="FEDEX">FEDEX</option>
                                <option value="AMZ LOGISTICS">AMZ LOGISTICS</option>
                                <option value="DHL">DHL</option>
                                <option value="OTRA">OTRA</option>
                            </select>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $("#transportadora").change(function(){
                                    var value = $(this).val();
                                    var compare = "OTRA";
                                    if(value == compare){
                                        $("#otra").removeClass("hidden");
                                        $("#fieldOtra").attr('required',"required");
                                    }else{
                                        $("#otra").addClass('hidden');
                                        $("#fieldOtra").removeAttr("required");
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div id="otra" class="form-group hidden">
                        <label class="col-md-4 col-xs-12 control-label">
                            Otra transportadora*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input id="fieldOtra"  type="text" class="form-control" name="otra">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-xs-12 control-label">
                            Factura de compra (PDF, PNG, JPG)*
                        </label>
                        <div class="col-md-8 col-xs-12">
                            <input class="input-file" type="file" name="comprobante" id="comprobante" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-xs-12">
                            <div class="checkbox">
                                <label class="popovers"  data-toggle="popover" data-trigger="hover" data-content="Al hacer clic en este check aceptas que tu paquete viaje asegurado por el valor total que declaraste y tiene un costo del <?=$config->seguro_opcional?>% sobre el mismo. Los paquetes con valor superior a U$<?=$config->seguro_max+1?> deben viajar asegurados por un costo del <?=$config->seguro_obligatorio?>% con cobertura total.">
                                    <input disabled  id="seguro_pre" type="checkbox" name="seguro" value="si">
                                    <b>Asegurar paquete</b>
                                </label>
                                <label>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-bell" aria-hidden="true"></i>&nbsp;Crear
                                </button>
                                </label>
                                
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div class="col-md-6 hidden-xs text-center">
                    <img class="img-thumbnail thumb-prealerta" style="padding-top: 54px; width: 273px;" src="<?php echo base_url() ?>public/images/prealerta.png" alt="prealerta">
                </div>
            </div>
        </form>
    </div>
</div>
<span id="constantes" data-url="<?php echo base_url(['Login', 'calcular']) ?>" data-seguro-opcional="<?=$config->seguro_opcional?>" data-seguro-obligatorio="<?=$config->seguro_obligatorio?>" data-seguro-max="<?=$config->seguro_max?>" data-seguro-min="<?=$config->seguro_min?>"></span>
