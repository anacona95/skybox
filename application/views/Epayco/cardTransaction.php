<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><img src="/public/images/credit-card.svg" alt="card" width="45"> Pago en línea con tarjeta de crédito</h2>
        </div>
        <form id="frmCardTransaction" class="form-horizontal" method="POST" action="<?php echo base_url(['Epayco', 'cardProcess']) ?>">
            <input type="hidden" name="data[orden_id]" value="<?= $orden->id?>">
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
                                    <label class="col-md-5 control-label"><img src="/public/images/credit-card-number.svg" alt="card" width="35"> Número de la tarjeta:</label>
                                    <div class="col-md-7">
                                        <input type="number" required pattern="(\d{4}[-. ]?){4}|\d{4}[-. ]?\d{6}[-. ]?\d{5}" data-msg="Ingresa un número de tarjeta de crédito válido." class="form-control" name="data[mask]">    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label"><img src="/public/images/cvv.svg" alt="card" width="35"> CVV:</label>
                                    <div class="col-md-3 ">
                                        <input type="number" pattern="\d{3}$" data-msg="Ingresa 3 dígitos." required class="form-control" name="data[cvv]">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Fecha de vencimiento:</label>
                                    <div class="col-md-3">
                                        <input type="number" placeholder="Año (AAAA)" pattern="\d{4}$" min="<?= date("Y")?>" data-msg="Ingresa el año." required class="form-control" name="data[exp_year]">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" placeholder="Mes (MM)" pattern="\d{1,2}$" min="1" max="12" data-msg="Ingresa el mes." required class="form-control" name="data[exp_month]">
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
                </div>
                <div class="row equal">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>3) Tarjeta habitante</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Nombre completo de la tarjeta:</label>
                                    <div class="col-md-6">
                                    <input type="text" required class="form-control" name="data[nombre]">
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
                                        <input type="number" required class="form-control" name="data[doc_number]">
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
                                        <input type="email" required class="form-control" name="data[email]">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Número de teléfono:</label>
                                    <div class="col-md-6">
                                        <input type="text" required class="form-control" name="data[cell_phone]">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Guardar tarjeta para futuros pagos:</label>
                                    <div class="col-md-6">
                                        <div class="material-switch">
                                            <input id="someSwitchOptionPrimary" name="data[defecto]" type="checkbox" value="1"/>
                                            <label for="someSwitchOptionPrimary" class="label-primary"></label>
                                        </div>
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
   