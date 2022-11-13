<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Rechazar comprobante de pago</h2>
        </div>

        <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin', 'rechazarOrden']) ?>" method="POST" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="" class="label-control col-md-2">No. Orden:</label>
                    <div class="col-md-3">
                        <span class="form-control">
                            <?php echo $orden->factura ?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="label-control col-md-2">Cliente:</label>
                    <div class="col-md-3">
                        <span class="form-control">
                            <?php echo $orden->cliente->primer_nombre . " " . $orden->cliente->apellidos ?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="label-control col-md-2">Motivo del rechazo*:</label>
                    <div class="col-md-3">
                        <textarea class="form-control" name="motivo" id="motivo" cols="30" rows="5" required></textarea>
                    </div>
                </div>

            </div>
            <div class="panel-footer text-right">
                <input type="hidden" name="orden_id" value="<?php echo $orden->id ?>">
                <a href="<?php echo base_url(['ordenes-de-compra','verificar',$orden->id]) ?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i> &nbsp;Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;Enviar
                </button>
            </div>
        </form>
    </div>
</div>