<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Pagar orden con pago en línea</h2>
        </div>
        <div class="panel-body">
            <?php if($this->session->flashdata('msgOk')): ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgOk') ?>
            </div>
            <?php endif;?>
            <?php if($this->session->flashdata('msgError')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
            <fieldset>
                <div class="form-group">
                    <table class="dataTable table-hover table table-striped table-bordered">
                        <caption>Listado de artículos</caption>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tracking</th>
                                <th class="hidden-xs">Valor</th>
                                <th class="hidden-xs">Peso</th>
                                <th>Valor fletes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orden->articulos as $row):?>
                            <tr>
                                <td>
                                    <?php echo $row->articulo->nombre?>
                                </td>
                                <td>
                                    <?php echo $row->articulo->id_track?>
                                </td>
                                <td class="hidden-xs">
                                    <?php echo "$ ".number_format($row->articulo->valor_paquete,0,'','.')." USD"?>
                                </td>
                                <td class="hidden-xs">
                                    <?php echo $row->articulo->peso." lb"?>
                                </td>
                                <td>
                                    <?php echo "$ ".number_format($row->articulo->valor,0,'','.')." COP"?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <fieldset class="form-horizontal">
                <div class="form-group">
                    <label for="recargo" class="col-md-3 col-xs-12 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp;Valor del recargo pago en línea:</label>
                    <div class="col-md-2 col-xs-12">
                        <span id="recargo" class="form-control">
                            <?php echo "$ ".number_format($recargo,0,'','.')." COP"?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="total" class="col-md-3 col-xs-12 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor envío nacional y/o domicilio:
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <span id="total" class="form-control">
                            <?php echo "$ ".number_format($orden->flete_nacional,0,'','.')." COP"?>
                        </span>
                    </div>

                </div>
                <div class="form-group">
                    <label for="total" class="col-md-3 col-xs-12 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Seguro:
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <span id="total" class="form-control">
                            <?php echo "$ ".number_format($orden->seguro,0,'','.')." COP"?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="total" class="col-md-3 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Bodegaje:
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <span id="total" class="form-control">
                            <?php echo "$ " . number_format($orden->valor_bodega, 0, '', '.') . " COP" ?>
                        </span>
                    </div>
                    <div class="col-md-1 col-xs-12">
                        <a href="#" title="Ver detalle" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-calendar fa-lg" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="total" class="col-md-3 col-xs-12 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Descuento:
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <span id="total" class="form-control">
                            <?php echo "$ ".number_format($orden->descuento,0,'','.')." COP"?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="total" class="col-md-3 control-label">
                        <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor total a pagar:
                    </label>
                    <div class="col-md-2 col-xs-12">
                        <span id="total" class="form-control">
                            <?php echo "$ ".number_format($total_pago,0,'','.')." COP"?>
                        </span>
                    </div>
                </div>

            </fieldset>
        </div>
        <div class="panel-footer text-right hidden-xs">

            <form>
                <a href="<?php echo base_url(['mis-ordenes'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
                <script src="https://checkout.epayco.co/checkout.js" class="epayco-button" data-epayco-key="ad64d2b76affa79ba57bc4905e80e12f"
                    data-epayco-amount="<?php echo round($total_pago)?>" data-epayco-name="Orden de compra fletes" data-epayco-email-billing="<?php echo $orden->cliente->email?>"
                    data-epayco-name-billing="<?php echo $orden->cliente->primer_nombre.' '.$orden->cliente->apellidos?>" data-epayco-address-billing="<?php echo $orden->cliente->direccion?>"
                    data-epayco-invoice="<?php echo $orden->id?>" data-epayco-type-doc-billing="CC" data-epayco-mobilephone-billing="<?php echo $orden->cliente->telefono?>"
                    data-epayco-number-doc-billing="<?php echo $orden->cliente->num_documento?>" data-epayco-description="Pago de orden de compra Foxcarga"
                    data-epayco-currency="cop" data-epayco-country="co" data-epayco-external="false"
                    data-epayco-response="<?php echo base_url()?>Epayco/response" data-epayco-confirmation="<?php echo base_url()?>Epayco/comfirmation">
                    </script>
            </form>
        </div>
        <div class="panel-footer text-center visible-xs">

            <form>
                <a href="<?php echo base_url(['mis-ordenes'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
                <script src="https://checkout.epayco.co/checkout.js" class="epayco-button" data-epayco-key="ad64d2b76affa79ba57bc4905e80e12f"
                    data-epayco-amount="<?php echo round($total_pago)?>" data-epayco-name="Orden de compra fletes" data-epayco-email-billing="<?php echo $orden->cliente->email?>"
                    data-epayco-name-billing="<?php echo $orden->cliente->primer_nombre.' '.$orden->cliente->apellidos?>" data-epayco-address-billing="<?php echo $orden->cliente->direccion?>"
                    data-epayco-invoice="<?php echo $orden->id?>" data-epayco-type-doc-billing="CC" data-epayco-mobilephone-billing="<?php echo $orden->cliente->telefono?>"
                    data-epayco-number-doc-billing="<?php echo $orden->cliente->num_documento?>" data-epayco-description="Pago de orden de compra Foxcarga"
                    data-epayco-currency="cop" data-epayco-country="co" data-epayco-external="false"
                    data-epayco-response="<?php echo base_url()?>Epayco/response" data-epayco-confirmation="<?php echo base_url()?>Epayco/comfirmation">
                    </script>
            </form>
        </div>

    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="agregarPaquetes" class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin','agregarPaquetes'])?>" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Detalle de valores por bodegaje</h4>
                    </div>
                    <div id="modal-body" class="modal-body">
                        <h5>Los valores se aplican con respecto a la TRM del día</h5>
                        <hr>
                        <table class="dataTable table-hover table table-striped table-bordered table-condensed">
                            <thead>
                                <th>Concepto</th>
                                <th>Fecha</th>
                                <th>Valor</th>
                            </thead>
                            <tbody>
                                <?php foreach($orden->bodegajes as $bodegaje):?>
                                <tr>
                                    <td>COBRO POR BODEGAJE</td>
                                    <td>
                                        <?php echo date('d/m/Y',$bodegaje->fecha)?>
                                    </td>
                                    <td>
                                        <?php echo "$ ".number_format($bodegaje->valor,0,'.',',')." COP"?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>