<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><img src="/public/images/logo-pse.png" alt="pse" width="45"> Pago débito bancario PSE </h2>
        </div>
        <form class="form-horizontal" method="POST" action="<?php echo base_url(['Epayco', 'pseProcess']) ?>">
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
                                        <span class="form-control" readonly><?=$info_comision['pse']['valor']?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Comisión pago en línea:</label>
                                    <div class="col-md-6">
                                        <span class="form-control" readonly><?=$info_comision['pse']['comision_pago']?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Valor a pagar:</label>
                                    <div class="col-md-6">
                                        <span class="form-control" readonly><?=$info_comision['pse']['total']?></span>
                                        <input type="hidden" name="data[value]" value="<?=$info_comision['pse']['valor_total']?>">
                                        <input type="hidden" name="data[invoice]" value="<?=$orden->id?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>2) Información de la tarjeta</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Banco:</label>
                                    <div class="col-md-6">
                                        <select class="select2" name="data[bank]" style="width: 100%" required>
                                            <?php foreach($bancos as $banco):?>
                                                <option value="<?= $banco['bankCode']?>"><?= $banco['bankName']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nombre del titular:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[name]">    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Apellidos del titular:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[last_name]">
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row equal">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>3) Identificación</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Tipo de persona:</label>
                                    <div class="col-md-6">
                                        <select class="select2" name="data[type_person]" style="width: 100%" required>
                                            <option value="0">Natural</option>
                                            <option value="1">Jurídica</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Tipo de documento:</label>
                                    <div class="col-md-6">
                                        <select class="select2" name="data[doc_type]" style="width: 100%" required>
                                            <?php foreach($document_types as $key=>$value):?>
                                                <option value="<?= $key?>"><?= $value?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Número de documento:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[doc_number]">
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>4) Información de contacto</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Correo electrónico:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[email]">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Número de teléfono:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[cell_phone]">
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden-xs panel-footer text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> &nbsp;Pagar</button>
                <a href="<?php echo base_url(['mis-ordenes','seleccionar-metodo-de-pago',$orden->id])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
            </div>
            <div class="visible-xs panel-footer text-center">
                <button type="submit" class="btn btn-primary"><i class="fa fa-credit-card-alt" aria-hidden="true"></i> &nbsp;Pagar</button>
                <a href="<?php echo base_url(['mis-ordenes','seleccionar-metodo-de-pago',$orden->id])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
   