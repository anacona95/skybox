<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Cambiar correo electrónico</h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url()?>User/Cambiaremail">
            <div class="panel-body">
                <?php if($this->session->flashdata('correo')): ?>
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('correo') ?>
                </div>
                <?php endif;?>
                <?php if($this->session->flashdata('error-correo')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('error-correo') ?>
                </div>
                <?php endif;?>

                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Nuevo correo*
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="email" placeholder="Correo electronico*" required="required" />
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $userdata['id'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Contraseña para confirmar*
                    </label>
                    <div class="col-sm-3">
                        <input type="password" class="form-control" name="clave" placeholder="Password" required="required" />
                    </div>
                </div>


            </div>
            <div class="panel-footer" align="right">
                <a href="<?php echo base_url()?>cuenta-usuario" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-pencil"></i>&nbsp; Actualizar
                </button>
            </div>
        </form>
    </div>
</div>