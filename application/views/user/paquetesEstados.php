<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Estado de mis paquetes
            </h2>
        </div>
        <div class="panel-body">
        <?php if ($this->session->flashdata('msgOk')): ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('msgOk') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('msgError')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('msgError') ?>
                </div>
            <?php endif;?>
        <div class="form-group x_content">
                <form class="form-horizontal" action="/rastreo-paquetes/" method="GET">
                    <div class="col-md-4 col-md-offset-8">
                        <div class="input-group">
                            <input type="text" placeholder="Últimos números del tracking..." name="q" autocomplete="off" class="form-control" value="<?=$q? $q:"";?>">
                            <span class="input-group-btn">
                                <?php if($q):?>
                                    <a href="/rastreo-paquetes" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                <?php else:?>
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <?php endif;?>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                </form>
            </div>
           
            <?php if(count($paquetes)==0 && !$q):?>
                <div class="col-md-12 center">
                    <h4>No tienes paquetes registrados a tu nombre... Realiza tu compra y crea tu primer prealerta: <a href="/user"><img width="30" src="/public/images/prealerta.svg" alt="prealertar"></a></h4>
                </div>
            <?php endif;?>
            <?php if(count($paquetes)==0 && $q):?>
                <div class="col-md-12 tex-center">
                    <h4>No se encontraron paquetes en la búsqueda...</h4>
                </div>
            <?php endif;?>
            <?php foreach($paquetes as $paquete):?>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php if($paquete->estadoArticulo=="Prealertado"):?>
                            <button type="button" class="close" data-toggle="modal" data-articulo-id="<?= $paquete->articulo_id?>" data-target="#modalEliminarPrealerta" onclick="setModalDelPrealerta(this)"><img src="/public/images/borrar.svg" width="20" alt="Editar prealerta"></button>
                            <button type="button" class="close" data-toggle="modal" data-articulo-id="<?= $paquete->articulo_id?>" data-articulo-tracking="<?= $paquete->id_track?>" data-articulo-usd="<?= $paquete->valor_paquete?>" data-articulo-factura="<?= $paquete->factura?>" data-articulo-seguro="<?= $paquete->seguro?>"  onclick="setModalUpdPrealerta(this)" data-target="#upd-prealerta"><img src="/public/images/upd.svg" width="20" alt="Editar prealerta"></button>
                        <?php endif;?>
                        <h4 class="modal-title"><img src="/public/images/<?= $paquete->estadoArticulo=="Prealertado"? "prealerta":"caja"?>.svg" alt="Mi direccion" width="25">#<?= $paquete->id_track?></h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="label-control">Nombre:</label>
                            <span><?= ucfirst(strtolower(substr($paquete->nombre,0,30)."..."))?></span>
                            <br>
                            <label class="label-control">Fecha de creación:</label>
                            <span><?= date("d/m/Y", $paquete->fecha_registro)?></span>
                            <br>
                            <label class="label-control">Estado:</label>
                            <span class="badge <?= $badges_class[$estados[$paquete->estadoArticulo]]?>"><?= $paquete->estadoArticulo?></span>
                            <div class="stepper visible-md visible-lg">
                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                    <li role="presentation" <?= $estados[$paquete->estadoArticulo] >= "0"? "class='completed'":"class='disabled'";?>>
                                        <a class="persistant-disabled" href="#stepper-step-1" data-toggle="tab" aria-controls="stepper-step-1" role="tab" title="Prealertado">
                                        <span class="round-tab">1</span>
                                        </a>
                                    </li>
                                    <li role="presentation" <?= $estados[$paquete->estadoArticulo] >= "1"? "class='completed'":"class='disabled'";?> >
                                        <a class="persistant-disabled" href="#stepper-step-2" data-toggle="tab" aria-controls="stepper-step-2" role="tab" title="Recibido y viajando">
                                        <span class="round-tab">2</span>
                                        </a>
                                    </li>
                                    <li role="presentation" <?= $estados[$paquete->estadoArticulo] >= "2"? "class='completed'":"class='disabled'";?>>
                                        <a class="persistant-disabled" href="#stepper-step-3" data-toggle="tab" aria-controls="stepper-step-3" role="tab" title="En Cali">
                                        <span class="round-tab">3</span>
                                        </a>
                                    </li>
                                    <li role="presentation" <?= $estados[$paquete->estadoArticulo] == "3"? "class='completed'":"class='disabled'";?>>
                                        <a class="persistant-disabled" href="#stepper-step-4" data-toggle="tab" aria-controls="stepper-step-4" role="tab" title="Orden">
                                        <span class="round-tab">4</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <br>
                            <label class="label-control">Seguro:</label>
                            <span><?= $paquete->seguro?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<!-- modal upd prealerta -->
<div class="modal fade" id="upd-prealerta" tabindex="-1" role="dialog" aria-labelledby="cupon">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="upd-prealerta"><img src="/public/images/prealerta.svg" alt="Mi direccion" width="25"> Actualiza tu prealerta</h4>
      </div>
      <form class="form-horizontal" enctype="multipart/form-data" method="post" action="/rastreo-paquetes/update-prealerta">
          <div class="modal-body">
                <div class="alert alert-info"><p>Si deseas cambiar los otros datos de la prealerta como: (Transportadora, Tienda, Fecha de entrega)... Mejor elimina esta prealerta y crea una nueva.</p></div>
                <div class="form-group">
                    <label class="col-md-4 col-xs-12 control-label">
                        Tracking*
                    </label>
                    <div class="col-md-8 col-xs-12">
                        <input id="tracking" type="text" class="form-control" required="required" name="id_track" placeholder="Ingrese sólo el tracking" />
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
                        Descargar factura de compra
                    </label>
                    <div class="col-md-8 col-xs-12">
                        <a id="factura" href="#" download><img width="40" src="/public/images/factura.svg" alt="prealertar"></a></a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 col-xs-12 control-label">
                        Actualizar factura de compra (PDF, PNG, JPG)*
                    </label>
                    <div class="col-md-8 col-xs-12">
                        <input class="input-file" type="file" name="comprobante" id="comprobante">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-xs-12">
                        <div class="checkbox">
                            <label class="popovers"  data-toggle="popover" data-trigger="hover" data-content="Al hacer clic en este check aceptas que tu paquete viaje asegurado por el valor total que declaraste y tiene un costo del <?=$config->seguro_opcional?>% sobre el mismo. Los paquetes con valor superior a U$<?=$config->seguro_max+1?> deben viajar asegurados por un costo del <?=$config->seguro_obligatorio?>% con cobertura total.">
                                <input disabled  id="seguro_pre" type="checkbox" name="seguro" value="si">
                                <b>Asegurar paquete</b>
                            </label>
                        </div>
                    </div>
                </div>
          </div>    
          <div class="modal-footer">
            <input type="hidden" name="articulo_id" id="articuloId">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>  
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEliminarPrealerta" tabindex="-1" role="dialog" aria-labelledby="modalTarifaLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <form action="/rastreo-paquetes/eliminar-prealerta" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalTarifaLabel"><img src="/public/images/borrar.svg" width="20" alt="Editar prealerta"> ¿Estás seguro de eliminar tu prealerta?</h4>
                    </div>
                    <div class="modal-body">
                        <br>
                        <h5>Recuerda que si tus paquetes no están prealertados pueden tardar un poco más en llegar.</h5>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="articulo_id" id="articuloIdEliminar">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<span id="constantes" data-url="<?php echo base_url(['Login', 'calcular']) ?>" data-seguro-opcional="<?=$config->seguro_opcional?>" data-seguro-obligatorio="<?=$config->seguro_obligatorio?>" data-seguro-max="<?=$config->seguro_max?>" data-seguro-min="<?=$config->seguro_min?>"></span>