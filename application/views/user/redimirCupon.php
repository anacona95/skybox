<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Prealertar
            </h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url() ?>user/envios_process">
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
                            <input type="text" class="form-control" required="required" name="nombre" maxlength="20" placeholder="Breve descripción del contenido de tu paquete" />
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6 hidden-xs text-center">
                    <img class="img-thumbnail thumb-prealerta" style="width: 400px;" src="<?php echo base_url() ?>public/images/prealerta.png" alt="prealerta">
                </div>
            </div>
        </form>
    </div>
</div>
