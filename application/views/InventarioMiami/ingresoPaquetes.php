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
                            <input class="form-control" id="tracking" name="tracking" autofocus="true" onkeypress="return findArticleMiami(event)">
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
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>&nbsp;No se pudo encontrar el paquete en la búsqueda, por favor ingresa la información del paquete.
                    </div>
                </div>
            </div>
            <form class="form-horizontal" method="post" action="<?php echo base_url(['InventarioMiami', 'ingresoProcess']) ?>">
                <fieldset id="info" class="hide">
                    <legend>Información del paquete</legend>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Artículo:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="articulo"></span>
                            </div>
                            <label for="nombre" class="col-md-3">Suite:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="bg-primary input-group-addon admin-dollar">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input class="form-control" id="user_id" name="user_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Cliente:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="cliente"></span>
                            </div>
                            <label for="nombre" class="col-md-3">Peso:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="bg-primary input-group-addon">
                                        <i class="fa fa-balance-scale"></i>
                                    </div>
                                    <input class="form-control" id="peso" name="peso">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Fecha de entrega estimada:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="fecha_entrega"></span>
                            </div>
                            <label for="nombre" class="col-md-3">Valor del flete:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="bg-primary input-group-addon admin-dollar">
                                        <i class="fa fa-dollar"></i>
                                    </div>
                                    <input class="form-control" id="flete" name="flete">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Seguro:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="seguro"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Descripción:</label>
                            <div class="col-md-3">
                                <span class="form-control desc-ingreso-paquete" id="descripcion"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">País:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="pais"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Ciudad:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="ciudad"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="nombre" class="col-md-3">Dirección:</label>
                            <div class="col-md-3">
                                <span class="form-control" id="direccion"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>
        </div>
        <div id="panel-footer" class="panel-footer text-right hide">
            <input type="hidden" name="id" id="articulo_id" value="">
            <span id="constantes" data-url="<?php echo base_url(['InventarioMiami', 'findArticle']) ?>"></span>
            <a href="<?php echo base_url(['Admin']) ?>" class="btn btn-default">Cancelar</a>
            <input type="submit" value="Registrar" class="btn btn-primary">

        </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="nuevoPaquete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" action="<?php echo base_url(['InventarioMiami', 'nuevoProcess']) ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Registre un nuevo paquete en la bodega</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 text-center">
                        <div class="alert alert-info" role="alert">
                            <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>&nbsp;No se pudo encontrar el paquete en la búsqueda, por favor ingresa la información del
                            paquete.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-4 control-label">Traking</label>
                        <div class="col-md-4">
                            <input type="text" name="traking" id="track" class="form-control" disabled>
                            <input type="hidden" name="n_tracking" id="hideTrack" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-4 control-label">Nombre</label>
                        <div class="col-md-4">
                            <input type="text" name="n_nombre" id="n_nombre" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-4 control-label">Peso</label>
                        <div class="col-md-4">
                            <input type="text" name="n_peso" id="n_peso" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-md-4 control-label">Valor del flete</label>
                        <div class="col-md-4">
                            <input type="text" name="n_flete" id="n_flete" class="form-control money" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>