<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Asignar puntos</h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url() ?>user/puntosProcess">
            <div class="panel-body">
                <?php if ($this->session->flashdata('msgError')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                    <?php echo $this->session->flashdata('msgError') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('msgOk')): ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                    <?php echo $this->session->flashdata('msgOk') ?>
                </div>
                <?php endif;?>

                <div class="form-group col-md-12 hidden-xs">
                    <label class="col-sm-2 control-label">Puntos acumulados:</label>
                    <div class="col-sm-1">
                        <span class="form-control">
                            <?php echo $puntoss->cantidad ?>
                        </span>
                    </div>
                    <label class="col-sm-4 control-label">Puntos para asignar:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="puntos" onchange="puntosDescuento(this)">
                            <?php if($puntoss->cantidad >= 10):?>
                            <option value="10">10</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 20):?>
                            <option value="20">20</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 30):?>
                            <option value="30">30</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 40):?>
                            <option value="40">40</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 50):?>
                            <option value="50">50</option>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-12 visible-xs">
                    <label class="col-sm-2 control-label">Puntos acumulados:</label>
                    <div class="col-sm-1">
                        <span class="form-control">
                            <?php echo $puntoss->cantidad ?>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-12 visible-xs">
                    <label class="col-sm-4 control-label">Puntos para asignar:</label>
                    <div class="col-sm-1">
                        <select class="form-control" name="puntos" onchange="puntosDescuento(this)">
                            <?php if($puntoss->cantidad >= 10):?>
                            <option value="10">10</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 20):?>
                            <option value="20">20</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 30):?>
                            <option value="30">30</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 40):?>
                            <option value="40">40</option>
                            <?php endif;?>
                            <?php if($puntoss->cantidad >= 50):?>
                            <option value="50">50</option>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-xs-12 col-md-2 control-label">No. orden:</label>
                    <div class="col-md-3 col-xs-12">
                        <span class="form-control">
                            <?php echo $orden->factura?>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-2 col-xs-12 control-label">Valor:</label>
                    <div class="col-md-3 col-xs-12">
                        <span class="form-control">
                            <?php echo "$ ".number_format($orden->valor,0,'','.')?>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-md-2 col-xs-12 control-label">Descuento:</label>
                    <div class="col-md-3 col-xs-12">
                        <span class="form-control" id="descuento-span">
                            $ 10.000
                        </span>
                    </div>
                </div>
            </div>
            <div class="hidden-xs panel-footer text-right">
                <a href="<?php echo base_url(['mis-puntos'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-star" aria-hidden="true"></i>&nbsp;Asignar
                </button>
            </div>
            <div class="visible-xs panel-footer text-center">
                <a href="<?php echo base_url(['mis-puntos'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-star" aria-hidden="true"></i>&nbsp;Asignar
                </button>
            </div>
            <input type="hidden" class="form-control" id="descuento" name="descuento" value="10000" />
            <input type="hidden" class="form-control" id="puntos_select" name="puntos_select" value="10" />
            <input type="hidden" class="form-control" name="orden" value="<?php echo $orden->id?>" />
        </form>
    </div>
</div>