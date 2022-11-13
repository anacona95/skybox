<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Administración de usuarios del sistema</h2>
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
            <div class="form-group col-xs-12">
                <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="#" title="Cree un nuevo usuario" data-toggle="modal" data-target="#modalCreate">
                            <i class="fa fa-plus"></i>&nbsp;Nuevo</a>
                    </li>
                </ul>
            </div>
            <div class="form-group col-xs-12">
                <table class="dataTable table-hover table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Rol</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user):?>
                        <tr>
                            <td>
                                <?php echo $user->nombre?>
                            </td>
                            <td>
                                <?php echo $user->email?>
                            </td>
                            <td>
                                <?php echo $user->roles[$user->role]?>
                            </td>
                            <td class="text-center">
                                <a id="btnUserEdit" href="#" title="Editar" data-toggle="modal" data-target="#modalEdit" data-nombre="<?php echo $user->nombre?>"
                                    data-email="<?php echo $user->email?>" data-rol="<?php echo $user->role?>" data-user-id="<?php echo $user->id?>"
                                    onclick="setFormEdit(this)">
                                    <i class="fa fa-pencil fa-lg"></i>
                                </a>&nbsp;
                                <a href="#" title="Cambiar contraseña" data-toggle="modal" data-target="#modalPassEdit" data-user-id="<?php echo $user->id?>"
                                    onclick="setIdPass(this)">
                                    <i class="fa fa-key fa-lg"></i>
                                </a>&nbsp;
                                <a href="<?php echo  base_url()?>AdminUsers/deleteProcess?id=<?php echo $user->id?>" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar el usuario?')">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Nuevo usuario</h4>
            </div>
            <form action="<?php echo base_url(['AdminUsers','createProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre" class="control-label col-xs-4">Nombre</label>
                        <div class="col-xs-4">
                            <input id="nombre" type="text" class="form-control" required name="usuario[nombre]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Correo electrónico</label>
                        <div class="col-xs-4">
                            <input id="email" type="text" class="form-control" required name="usuario[email]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Contraseña</label>
                        <div class="col-xs-4">
                            <input id="password" type="password" class="form-control" required name="usuario[password]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rol" class="control-label col-xs-4">Rol</label>
                        <div class="col-xs-4">
                            <select name="usuario[rol]" id="rol" class="form-control">
                                <?php foreach($roles as $key => $value):?>
                                <option value="<?php echo $key?>">
                                    <?php echo $value?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i>&nbsp;Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i>&nbsp;Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Modificar usuario</h4>
            </div>
            <form action="<?php echo base_url(['AdminUsers','updateProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreEdit" class="control-label col-xs-4">Nombre</label>
                        <div class="col-xs-4">
                            <input id="nombreEdit" type="text" class="form-control" required name="usuario[nombre]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="emailEdit" class="control-label col-xs-4">Correo electrónico</label>
                        <div class="col-xs-4">
                            <input id="emailEdit" type="text" class="form-control" required name="usuario[email]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rolEdit" class="control-label col-xs-4">Rol</label>
                        <div class="col-xs-4">
                            <select name="usuario[rol]" id="rolEdit" class="form-control">
                                <?php foreach($roles as $key => $value):?>
                                <option value="<?php echo $key?>">
                                    <?php echo $value?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="usuario[id]" id="userId" value="">
                    <a href="#" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i>&nbsp;Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-pencil"></i>&nbsp;Modificar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalPassEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Cambiar contraseña</h4>
            </div>
            <form action="<?php echo base_url(['AdminUsers','updatePassProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="passEdit" class="control-label col-xs-4">Nueva contraseña</label>
                        <div class="col-xs-4">
                            <input id="passEdit" type="password" class="form-control" required name="usuario[password]">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="usuario[id]" id="userIdPass" value="">
                    <a href="#" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i>&nbsp;Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-pencil"></i>&nbsp;Modificar</button>
                </div>
            </form>
        </div>
    </div>
</div>