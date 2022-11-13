<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Selecciona el método de pago</h2>
        </div>
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
                <div class="row text-center">
                    <?php if($pago_virtual):?>
                    <div class="col-program col-md-4 col-sm-4">
                        <a href="/pago-en-linea/cards/<?= $orden->id?>">
                            <img src="/public/images/credit-card.svg" alt="pse" width="150">
                        </a>
                        <h4>Pago en línea con tarjeta de crédito</h4>
                    </div>
                    <div class="col-program col-md-4 col-sm-4">
                        <a href="/pago-en-linea/pse/<?= $orden->id?>">
                            <img src="/public/images/logo-pse.png" alt="pse" width="150">
                        </a>
                        <h4>Transferencia bancaria virtual</h4>
                    </div>
                    <?php endif;?>
                    <div class="col-program col-md-4 col-sm-4">
                        <a href="/mis-ordenes/pagar-con-comprobante/<?= $orden->id?>">
                            <img src="/public/images/upload.svg" alt="pse" width="150">
                        </a>
                        <h4>Subir comprobante de pago</h4>
                    </div>
                </div>
            </div>
            <div class="hidden-xs panel-footer text-right">
                <a href="/mis-ordenes" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i> &nbsp;Cancelar
                </a>
            </div>
        </div>
    </div>
</div>