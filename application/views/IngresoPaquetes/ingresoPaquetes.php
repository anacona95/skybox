<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Ingreso de paquetes</h2>
        </div>
        <div class="panel-body">
            <legend>Búsqueda de paquete</legend>
            <?php if ($this->session->flashdata('msgOk')): ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('msgOk') ?>
            </div>
            <?php endif;?>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="nombre" class="col-md-3">Tracking:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="bg-primary input-group-addon">
                                <i class="fa fa-barcode"></i>
                            </div>
                            <input class="form-control" id="tracking" name="tracking" autofocus="true" onkeypress="return findArticle(event)">
                        </div>
                    </div>
                </div>
            </div>
            <div id="loading" class="row hide">
                <div class="form-group col-md-12 text-center">
                    <img src="<?php echo base_url() ?>public/images/loading_spinner.gif">
                </div>
            </div>
            <div id="msg-error" class="row hide">
                <div class="form-group col-md-12 text-center">
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>&nbsp;No se pudo encontrar
                        el paquete en la búsqueda, por favor intentalo de nuevo.</div>
                </div>
            </div>
            <div class="row">
                <form class="form-horizontal" method="post" action="<?php echo base_url(['IngresoPaquetes', 'ingresoProcess']) ?>" onsubmit="return validarTarifa()">
                    <fieldset id="info" class="hide">
                        <legend>Información del artículo</legend>
                        <!-- seccion izquierda -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Artículo:</label>
                                <div class="col-md-9">
                                    <span class="form-control" id="articulo"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Cliente:</label>
                                <div class="col-md-9">
                                    <span class="form-control" id="cliente"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Ciudad:</label>
                                <div class="col-md-9">
                                    <span class="form-control" id="ciudad"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Seguro:</label>
                                <div class="col-md-9">
                                    <span class="form-control" id="seguro"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Valor USD:</label>
                                <div class="col-md-9">
                                    <span class="form-control" id="valor_paquete"></span>
                                </div>
                            </div>
                        </div>
                        <!-- seccion derecha -->
                        <div id="liquidacion" class="col-md-6">
		  	                
				            
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Suite:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="bg-primary input-group-addon admin-dollar">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input class="form-control" id="user_id" name="user_id">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Peso:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="bg-primary input-group-addon">
                                            <i class="fa fa-balance-scale"></i>
                                        </div>
                                        <input class="form-control" id="peso" name="peso" onblur="reLiquidarIngreso()">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label class="col-md-3">
                                            <input checked="checked" id="regular" type="radio" name="tecnologia" value="4" onchange="setFleteIngreso(this)">
                                            <b>Regular</b>
                                        </label>
                                        <label class="col-md-3">
                                            <input id="comercial" type="radio" name="tecnologia" value="3" onchange="setFleteIngreso(this)">
                                            <b>Comercial</b>
                                        </label>
                                        <label class="col-md-3">
                                            <input id="celular" type="radio" name="tecnologia" value="1" onchange="setFleteIngreso(this)">
                                            <b>Celular</b>
                                        </label>
                                        <label class="col-md-3">
                                            <input id="pc" type="radio" name="tecnologia" value="2" onchange="setFleteIngreso(this)">
                                            <b>Laptop</b>
                                        </label>
                                    </div>
                                </div>
                                <div id="divCantidad" class="form-group hidden">
                                    <div class="checkbox">
                                        <div class="col-md-3"><b>Cantidad:</b></div>
                                        <div class="col-md-offset-1 col-md-2">
                                            <input type="number" class="form-control" name="cantidad" id="cantidad" value="1"
                                                onblur="setFleteCantidad()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Tarifa:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="bg-primary input-group-addon admin-dollar">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <input class="form-control" id="tarifa_especial" name="tarifa_especial" onblur="reLiquidarIngreso()">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombre" class="col-md-3">Valor del envío:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="bg-primary input-group-addon admin-dollar">
                                            <i class="fa fa-dollar"></i>
                                        </div>
                                        <span style="background:#EEF3E9;" class="form-control" id="flete"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="panel-footer" class="pull-rigth text-right hide">
                                <input type="hidden" name="id" id="articulo_id" value="">
                                <span id="constantes" 
                                    data-url="<?php echo base_url(['IngresoPaquetes', 'findArticle']) ?>"
                                    data-tarifa-celular="<?php echo $tarifas['tarifa_4']?>" 
                                    data-tarifa-computador="<?php echo $tarifas['tarifa_5']?>"
                                    data-tarifa="<?php echo $tarifas['tarifa']?>"
                                    data-trm="<?php echo $_SESSION['trm']['hoy']?>" 
                                    data-tarifa-minima="<?php echo $tarifas['tarifa_minima']?>"
                                    data-tarifa-manejo="<?php echo $tarifas['tarifa_manejo']?>" ></span>
                                <a href="<?php echo base_url(['admin','ingreso-paquetes']) ?>" class="btn btn-default">Cancelar</a>
                                <input type="submit" value="Registrar" class="btn btn-primary">
                             </div>
                    </fieldset>
		</form>
            </div>
        </div>
            
    </div>
</div>


<div class="modal fade" id="modalTarifa" tabindex="-1" role="dialog" aria-labelledby="modalTarifaLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalTarifaLabel">Tarifa minima</h4>
                </div>
                <div id="modal-body" class="modal-body">
                    <h5>La tarifa que ingresa es inferior a la tarifa mínima configurada: <?php echo $tarifas['tarifa_minima']?></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
        </div>
    </div>
</div>