<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Pagar orden manualmente</h2>
        </div>

        <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin','pagarProcess'])?>" method="POST" enctype="multipart/form-data">
            <div class="panel-body">
                <div class="row">
                    <ul class="nav nav-pills pull-right">
                        <li role="presentation"><a href="#" data-toggle="modal" data-target="#modalNewAbonos"><i class="fa fa-money fa-lg" aria-hidden="true"></i> Crear abono</a></li>
                        <li role="presentation">
                            <a target="_blank" href="<?php echo base_url(['ordenes-de-compra','imprimir-prueba', $orden->id]) ?>" title="Imprimir prueba">
                                <i class="fa fa-print fa-lg"></i>&nbsp;Imprimir prueba
                            </a>
                        </li>
                    </ul>
                </div>
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
                        <label for="comprobnte" class="control-label col-md-2">
                            <i class="fa fa-paperclip text-primary" aria-hidden="true"></i>&nbsp;Adjuntar comprobante:
                        </label>
                        <div class="col-md-4">
                            <input type="file" name="comprobante" id="comprobante" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comprobnte" class="control-label col-md-2">
                            Observaciones:
                        </label>
                        <div class="col-md-4">
                            <textarea class="form-control" name="observaciones" id="observaciones" required rows="4"></textarea>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <fieldset>
                    <div class="form-group">
                        <table class="dataTable table-hover table table-striped table-bordered">
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
                    <div class="form-group">
                        <label for="total" class="col-md-3 col-xs-12 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor envío nacional y/o domicilio:
                        </label>
                        <div class="col-md-2 col-xs-12">
                            <span id="total" class="form-control">
                                <?php echo "$ ".number_format($orden->flete_nacional,0,'','.')." COP"?>
                            </span>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="sin_domicilio" id="sin_domicilio" value="1">
                                <b>No cobrar domicilio</b>
                            </label>
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
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->valor_bodega, 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="sin_bodegaje" id="sin_bodegaje" value="1">
                                <b>No cobrar bodegaje</b>
                            </label>
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
                                <?php echo "$ ".number_format($orden->valor,0,'','.')." COP"?>
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
            </div>

            <div class="panel-footer text-right">
                <input type="hidden" name="orden_id" value="<?php echo $orden->id?>">
                <a href="<?php echo base_url(['ordenes-de-compra'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
                <button class="btn btn-primary">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp; Pagar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" role="dialog" id="modalNewAbonos">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-money fa-lg" aria-hidden="true"></i> Crear abono</h4>
      </div>
      <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin', 'nuevoAbonoProcess']) ?>" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
            <fieldset>
            <div class="form-group">
                    <label for="valorAbono" class="control-label col-md-4">Valor:</label>
                    <div class="col-md-6">
                        <input id="valorAbono" type="text" name="valor" class="form-control money" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="comprobanteAbono" class="control-label col-md-4">
                        Adjutar comprobante:
                    </label>
                    <div class="col-md-6">
                        <input type="file" name="comprobante" id="comprobanteAbono" required>
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