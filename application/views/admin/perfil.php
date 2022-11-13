<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Mi perfil</h2>
        </div>
        <form class="form-horizontal" action="<?php echo base_url(['ProfileAdmin', 'updatePerfil']) ?>" method="post">
            <div class="panel-body">
            <?php if ($this->session->flashdata('perfil')): ?>
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('perfil') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('error-perfil')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('error-perfil') ?>
                </div>
                <?php endif;?>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <a href="#" class="image" data-toggle="modal" data-target="#modal-profile">
                            <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $per->imagen ?>" alt="avatar" class="img-responsive">
                            <span class="case">
                                <i class="fa fa-camera fa-lg" aria-hidden="true"></i>
                            </span>
                        </a>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Nombre
                            </label>
                            <div class="col-sm-6">
                                <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $per->id ?>">
                                <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $per->nombre ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Correo
                            </label>
                            <div class="col-sm-6">
                                <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $per->id ?>">
                                <input type="text" class="form-control" required="required" name="enlace" value="<?php echo $per->email ?>" disabled/>
                            </div>
                            <a href="<?php echo base_url() ?>cambiar-correo" class="boton_personalizado">
                                Cambiar correo
                            </a>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                Contraseña
                            </label>
                            <div class="col-sm-6">
                                <input type="password" class="form-control" required="required" name="enlace" placeholder="******************" disabled/>
                            </div>
                            <a href="<?php echo base_url() ?>cambiar-contrasena" class="boton_personalizado">
                                Cambiar contraseña
                            </a>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-pencil"></i>&nbsp;Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Cambiar foto de perfil</h4>
            </div>
            <form class="form-horizontal" action="<?php echo base_url(); ?>ProfileAdmin/subirImagen" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <br>
                    <br>
                    <div class="form-group">
                        <label for="imagen" class="control-label col-md-4 col-xs-12">Seleccionar imagen:</label>
                        <div class="col-md-4 col-xs-12">
                            <input type="file" name="imagen" id="imagen">
                        </div>
                    </div>
                    <br>
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i>&nbsp;Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-pencil"></i>&nbsp;Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>