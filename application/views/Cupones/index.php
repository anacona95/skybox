<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-ticket" aria-hidden="true"></i> Administración de cupones</h2>
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
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
            <div class="form-group col-xs-12">
                <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="#" title="Cree un nuevo cupón" data-toggle="modal" data-target="#modalCreate">
                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i>&nbsp;Nuevo</a>
                    </li>
                </ul>
            </div>
            <div class="form-group col-xs-12">
                <table class="dataTable table-hover table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Vigencia</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cupones as $cupon):?>
                        <tr>
                            <td>
                                <?= $cupon->codigo?>
                            </td>
                            <td>
                                <?=($cupon->tipo==2 )? "$ ".number_format($cupon->valor, 0, '', '.') : $cupon->valor." %"; ?>
                            </td>
                            <td class="text-center">
                                <?=( $cupon->estado==1)? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-success'>Inactivo</span>"?>
                            </td>
                            <td>
                                <?= date("d/m/Y h:i:s A",$cupon->start_date). " --> ". date("d/m/Y h:i:s A",$cupon->end_date)?>
                            </td>
                            <td class="text-center">
                                <a class="hidden" href="#" title="Eliminar" title="Modificar cupón" data-toggle="modal" data-target="#modalUpdate">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#"
                                    title="Eliminar">
                                    <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-ticket" aria-hidden="true"></i> Nuevo cupón</h4>
            </div>
            <form autocomplete="off" action="<?php echo base_url(['Cupones','createProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre" class="control-label col-xs-4">Nombre o código del cupón</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" required name="data[nombre]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Tipo</label>
                        <div class="col-xs-4">
                            <select class="form-control" name="data[tipo]">
                                <option value="1">% Porcentaje</option>
                                <option value="2">$ Valor</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label  class="col-xs-4 col-xs-offset-4">
                                <input type="checkbox" name="data[nuevo_cliente]" value="1"> <b>Válido para nuevos clientes</b>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label  class="col-xs-4 col-xs-offset-4">
                                <input type="checkbox" name="data[app]" value="1"> <b>Redimible sólo en la app</b>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Valor</label>
                        <div class="col-xs-4">
                            <input type="numeric" class="form-control" required name="data[valor]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Fecha de inicio</label>
                        <div class="col-xs-4">
                            <input type="text" numeric class="form-control prealertaCsv" required name="data[start_date]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Fecha de finalización</label>
                        <div class="col-xs-4">
                            <input type="text" numeric class="form-control prealertaCsv" required name="data[end_date]">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-ticket" aria-hidden="true"></i> Editar cupón</h4>
            </div>
            <form autocomplete="off" action="<?php echo base_url(['Cupones','createProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre" class="control-label col-xs-4">Nombre o código del cupón</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" required name="data[nombre]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-xs-4">Tipo</label>
                        <div class="col-xs-4">
                            <select class="form-control" name="data[tipo]">
                                <option value="1">% Porcentaje</option>
                                <option value="2">$ Valor</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label  class="col-xs-4 col-xs-offset-4">
                                <input type="checkbox" name="data[nuevo_cliente]" value="1"> <b>Válido para nuevos clientes</b>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label  class="col-xs-4 col-xs-offset-4">
                                <input type="checkbox" name="data[app]" value="1"> <b>Redimible sólo en la app</b>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Valor</label>
                        <div class="col-xs-4">
                            <input type="numeric" class="form-control" required name="data[valor]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Fecha de inicio</label>
                        <div class="col-xs-4">
                            <input type="text" numeric class="form-control prealertaCsv" required name="data[start_date]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-xs-4">Fecha de finalización</label>
                        <div class="col-xs-4">
                            <input type="text" numeric class="form-control prealertaCsv" required name="data[end_date]">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>