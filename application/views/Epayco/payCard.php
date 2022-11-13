<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><img src="/public/images/credit-card.svg" alt="card" width="45"> Pago en línea con tarjeta de crédito</h2>
        </div>
        <form id="frmCardTransaction" class="form-horizontal" method="POST" action="<?php echo base_url(['Epayco', 'payCardProcess']) ?>">
            <input type="hidden" name="data[orden_id]" value="<?= $orden->id?>">
            <input type="hidden" name="data[card_id]" value="<?= $selected_card->id?>">
            <div class="panel-body">
                <?php if ($this->session->flashdata('msgOk')): ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                    <?php echo $this->session->flashdata('msgOk') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('msgError')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                    <?php echo $this->session->flashdata('msgError') ?>
                </div>
                <?php endif;?>
                <div class="row equal">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>1) Datos de transacción</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Valor factura:</label>
                                    <div class="col-md-6">
                                        <span class="form-control" readonly><?=$info_comision['card']['valor']?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Comisión pago en línea:</label>
                                    <div class="col-md-6">
                                        <span class="form-control" readonly><?=$info_comision['card']['comision_pago']?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Valor a pagar:</label>
                                    <div class="col-md-6">
                                        <span class="form-control" readonly><?=$info_comision['card']['total']?></span>
                                        <input type="hidden" name="data[valor_total]" value="<?=$info_comision['card']['valor_total']?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Número de cuotas:</label>
                                    <div class="col-md-3 ">
                                        <input type="number" min="1" max="36" required class="form-control" name="data[cuotas]">
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>2) Tarjeta seleccionada</h4>
                            </div>
                            <div class="panel-body">
                                <div class="cursor-pointer col-md-offset-3  col-md-5 col-xs-10 col-sm-10">
                                    <div class="panel panel-default panel-card-1">
                                        <div class="panel-body card-defaul">
                                        <h4 class="pull-right text-card"><b>***<?= $selected_card->mask?></b></h4>
                                    </div>  
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden-xs panel-footer text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> &nbsp;Pagar</button>
                <a href="<?php echo base_url(['pago-en-linea','cards',$orden->id])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
            </div>
            <div class="visible-xs panel-footer text-center">
                <button type="submit" class="btn btn-primary"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> &nbsp;Pagar</button>
                <a href="<?php echo base_url(['pago-en-linea','cards',$orden->id])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
   