<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Información de la orden de compra</h2>
        </div>
        <div class="form-horizontal">
            <?php if ($orden->estado == 0): ?>
            <div class="col-md-12">
                <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="<?php echo base_url(['reenviar-orden', $orden->id]) ?>" title="Envíe nuevamente el correo de la orden de compra">
                            <i class="fa fa-retweet"></i>&nbsp;Reenviar correo
                        </a>
                    </li>
                    <li role="presentation">
                        <a target="_blank" href="<?php echo base_url(['ordenes-de-compra','imprimir-prueba', $orden->id]) ?>" title="Imprimir prueba">
                            <i class="fa fa-print fa-lg"></i>&nbsp;Imprimir prueba
                        </a>
                    </li>
                    <?php if($orden->estado == 0):?>
                    <li role="presentation">
                        <a href="<?php echo base_url(['ordenes-de-compra','pagar-orden', $orden->id]) ?>" title="Imprimir prueba">
                            <i class="fa fa-check fa-lg"></i>&nbsp;Pagar
                        </a>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('msgOk')): ?>
            <div class="col-md-12 alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgOk') ?>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('msgError')): ?>
            <div class="col-md-12 alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
        </div>
        <div class="form-horizontal">
            <div class="panel-body">

                <div class="form-group">
                    <label for="" class="col-md-2">No. Orden:</label>
                    <div class="col-md-4">
                        <span class="form-control">
                            <?php echo $orden->factura ?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-2">Cliente:</label>
                    <div class="col-md-4">
                        <span class="form-control">
                            <?php echo $orden->cliente->primer_nombre . " " . $orden->cliente->apellidos ?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-md-2">Observaciones:</label>
                    <div class="col-md-4">
                        <span class="form-control" style="height: 115px;">
                            <?php echo $orden->observaciones ?>
                        </span>
                    </div>
                </div>
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
                                <?php foreach ($orden->articulos as $row): ?>
                                <tr>
                                    <td>
                                        <?php echo $row->articulo->nombre ?>
                                    </td>
                                    <td>
                                        <?php echo $row->articulo->id_track ?>
                                    </td>
                                    <td class="hidden-xs">
                                        <?php echo "$ " . number_format($row->articulo->valor_paquete, 0, '', '.') . " USD" ?>
                                    </td>
                                    <td class="hidden-xs">
                                        <?php echo $row->articulo->peso . " lb" ?>
                                    </td>
                                    <td>
                                        <?php echo "$ " . number_format($row->articulo->valor, 0, '', '.') . " COP" ?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
                <fieldset>
                <div class="form-group">
                        <label for="totallibras" class="col-md-3 col-xs-12 control-label">
                            Total de libras:
                        </label>
                        <div class="col-md-2 col-xs-12">
                            <span id="totallibras" class="form-control" readonly>
                                <?php echo $orden->getLibras() ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($orden->valor_recargo != 0): ?>
                    <div class="form-group">
                        <label for="recargo" class="col-md-3">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp;Valor del recargo pago en línea:</label>
                        <div class="col-md-2">
                            <span id="recargo" class="form-control">
                                <?php echo "$ " . number_format($orden->valor_recargo, 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="form-group">
                        <label for="domicilio" class="col-md-3 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor envío nacional y/o domicilio:
                        </label>

                        <form action="<?php echo base_url(['OrdenesAdmin', 'updDomicilio']) ?>" method="POST">
                            <div class="col-md-2">
                                <?php if ($orden->estado == 0): ?>
                                <div class="input-group">
                                    <input id="domicilio" type="text" class="money form-control" value="<?php echo $orden->flete_nacional ?>" name="domicilio">
                                    <span class="input-group-btn">
                                        <input type="hidden" name="orden_id" value="<?php echo $orden->id ?>">
                                        <button type="submit" class="btn btn-primary" title="Actualizar">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </span>
                                </div>
                                <?php else: ?>
                                <span id="total" class="form-control">
                                    <?php echo "$ " . number_format($orden->flete_nacional, 0, '', '.') . " COP" ?>
                                </span>
                                <?php endif;?>
                            </div>
                        </form>
                    </div>
                    <div class="form-group">
                        <label for="total" class="col-md-3 col-xs-12 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Seguro:
                        </label>
                        <div class="col-md-2 col-xs-12">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->seguro, 0, '', '.') . " COP" ?>
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
                        <label for="total" class="col-md-3 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Descuento:
                        </label>
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->descuento, 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                        <?php if($userdata['role']==1 && $orden->estado == 0):?>
                        <div class="col-md-1 col-xs-12">
                            <a href="#" title="Modificar descuento" data-toggle="modal" data-target="#modalDescuento">
                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                            </a>
                        </div>
                        <?php endif;?>
                    </div>
                    <div class="form-group">
                        <label for="total" class="col-md-3 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor total a pagar:
                        </label>
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->valor + $orden->valor_recargo, 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="total" class="col-md-3 col-xs-12 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Abonado:
                        </label>
                        <div class="col-md-2 col-xs-12">
                            <span id="total" class="form-control" readonly>
                                <?php echo "$ " . number_format($orden->totalAbonos(), 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <a href="#" title="Ver abonos" data-toggle="modal" data-target="#modalAbonos">
                                <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="total" class="col-md-3 col-xs-12 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Pendiente por pagar:
                        </label>
                        <div class="col-md-2 col-xs-12">
                            <span id="total" class="form-control" readonly>
                                <?php echo "$ " . number_format($orden->valor-$orden->totalAbonos(), 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                    </div>
                </fieldset>
                <br>
                <?php if ($comprobante != null): ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img class="img-thumbnail img-comprobante" src="<?php echo base_url() . $comprobante->path . $comprobante->imagen ?>" alt="comprobante de pago">
                    </div>
                </div>
                <?php endif;?>
                <?php if ($pagosTc != null): ?>
                <div class="form-group">
                    <hr>
                    <table class="dataTable table-hover tablestriped table-condensed table-bordered table">
                        <caption>Listado de transacciones en tu compra</caption>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Valor</th>
                                <th>Ip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagosTc as $row): ?>
                            <tr>
                                <td>
                                    <?php echo date('d/m/Y H:i', $row->fecha) ?>
                                </td>
                                <td>
                                    <?php echo $row->statusLst[$row->estado] ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($row->valor, 0, '', '.') . " COP" ?>
                                </td>
                                <td>
                                    <?php echo $row->ip ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <?php endif;?>
            </div>

            <div class="panel-footer text-right">
                <a href="<?php echo base_url(['ordenes-de-compra']) ?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i> &nbsp;Cancelar
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="agregarPaquetes" class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin', 'agregarPaquetes']) ?>" method="post">
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
                            <?php foreach ($orden->bodegajes as $bodegaje): ?>
                            <tr>
                                <td>COBRO POR BODEGAJE</td>
                                <td>
                                    <?php echo date('d/m/Y H:i', $bodegaje->fecha) ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($bodegaje->valor, 0, '.', ',') . " COP" ?>
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
<div class="modal fade" id="modalAbonos" tabindex="-1" role="dialog" aria-labelledby="modalAbonosLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalAbonosLabel">Listado de abonos de la orden</h4>
                </div>
                <div id="modal-body" class="modal-body">
                    <table class="dataTable table-hover table table-striped table-bordered table-condensed col-md-12">
                        <thead>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Valor</th>
                            <th>Comprobante</th>
                        </thead>
                        <tbody>
                            <?php foreach ($orden->abonos as $abono): ?>
                            <tr>
                                <td>ABONO DE ORDEN DE COMPRA</td>
                                <td>
                                    <?php echo date('d/m/Y H:i', $abono->fecha) ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($abono->valor, 0, '.', ',') . " COP" ?>
                                </td>
                                <td class="text-center">
                                    <a target="_blank" href="<?php echo $abono->comprobante?>" title="Ver comprobante"><i class="fa fa-file-image-o fa-lg" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
        </div>
    </div>
</div>


<div class="modal fade" role="dialog" id="modalDescuento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-money fa-lg" aria-hidden="true"></i> Modificar descuento</h4>
      </div>
      <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin', 'updDescuentoProcess']) ?>" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <fieldset>
            <div class="form-group">
                    <label class="control-label col-md-4">Valor:</label>
                    <div class="col-md-6">
                        <input type="text" name="descuento" class="form-control money" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="obsrvdesc" class="col-md-4 control-label">Observaciones:</label>
                    <div class="col-md-6">
                        <textarea class="form-control" name="observaciones" id="obsrvdesc" cols="30" rows="5"><?php echo $orden->observaciones;?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="orden_id" value="<?php echo $orden->id ?>">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->