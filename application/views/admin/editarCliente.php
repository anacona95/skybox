<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Datos personales del cliente
            </h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url()?>admin/updatePerfilCliente">
            <div class="panel-body">
            <?php if($this->session->flashdata('cliente')): ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('cliente') ?>
            </div>
            <?php endif;?>
            <?php if($this->session->flashdata('error-cliente')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('error-cliente') ?>
            </div>
            <?php endif;?>
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <a href="" class="image" data-toggle="modal" data-target="#modal-profile">
                            <img src="<?php echo base_url() ?>uploads/imagenes/thumbs/<?php echo $cliente->imagen ?>" alt="avatar" class="img-responsive">
                        </a>
                    </div>
                </div>
                <br>
                <legend>Configuración</legend>
                <div class="form-group">
                    <div class="checkbox">
                        <label  class="col-xs-4 col-xs-offset-4">
                            <input id="estadoCosto" type="checkbox" name="cobrar_seguro" value="1" <?= $cliente->cobrar_seguro==1? "checked": ""?>> <b>No cobrar seguro</b>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Tarifa regular
                    </label>
                    <div class="col-sm-2">
                        <input required type="text" class="form-control" required="required" name="tarifa" value="<?= !$cliente->tarifa? $tarifas['tarifa'] : $cliente->tarifa?>"/>
                    </div>
                    <label class="col-sm-2 control-label">
                        Tarifa comercial
                    </label>
                    <div class="col-sm-2">
                        <input required type="text" class="form-control" name="comercial" value="<?= !$cliente->tarifa_comercial? $tarifas['comercial'] : $cliente->tarifa_comercial?>" />
                    </div>
                </div>
                <legend>Datos personales</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Primer nombre
                    </label>
                    <div class="col-sm-2">
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $cliente->id ?>">
                        <input type="text" class="form-control" required="required" name="primer_nombre" value="<?php echo $cliente->primer_nombre?>"
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Segundo nombre
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" name="segundo_nombre" value="<?php echo $cliente->segundo_nombre?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Apellidos
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="apellidos" value="<?php echo $cliente->apellidos?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Documento de identidad
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="num_documento" value="<?php echo $cliente->num_documento?>"
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Celular
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="telefono" value="<?php echo $cliente->telefono?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Fecha de nacimiento
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="fecha_nacimiento" value="<?php echo $cliente->fecha_nacimiento?>"
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Código de un amigo
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="parent_id" value="<?php echo $cliente->parent_id?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Fecha de creación
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="fecha_creacion" value="<?php echo $cliente->fecha_creacion?>"
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Estado de cuenta
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="estado" value="<?php echo $cliente->estado?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Como te enteraste?
                    </label>
                    <div class="col-sm-2">
                        <textarea class="form-control" name="descripcions"><?php echo $cliente->descripcions?></textarea>
                    </div>
                </div>
                <div></div>
                <h2> Lugar de residencia</h2>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        País
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="pais" value="<?php echo $cliente->pais?>" />
                    </div>
                    <label class="col-sm-2 control-label">
                        Ciudad
                    </label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" required="required" name="ciudad" value="<?php echo $cliente->ciudad?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Dirección
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" required="required" name="direccion" value="<?php echo $cliente->direccion?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Correo
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" required="required" name="email" value="<?php echo $cliente->email?>" />
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="<?php echo base_url()?>informacion-clientes" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-pencil"></i>&nbsp;Actualizar
                </button>
            </div>
        </form>
    </div>
</div>