<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Verificar comprobante de pago</h2>
        </div>

        <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin', 'aprobarOrden']) ?>" method="POST" enctype="multipart/form-data">
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
                <div class="form-group">
                    <label for="" class="label-control col-md-2">No. Orden:</label>
                    <div class="col-md-4">
                        <span class="form-control">
                            <?php echo $orden->factura ?>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="label-control col-md-2">Cliente:</label>
                    <div class="col-md-4">
                        <span class="form-control">
                            <?php echo $orden->cliente->primer_nombre . " " . $orden->cliente->apellidos ?>
                        </span>
                    </div>
                </div>
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
                    <div class="form-group">
                        <label for="total" class="col-md-3 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor envío nacional y/o domicilio:
                        </label>
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->flete_nacional, 0, '', '.') . " COP" ?>
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
                                <?php echo "$ " . number_format($orden->seguro, 0, '', '.') . " COP" ?>
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
                    </div>

                    <div class="form-group">
                        <label for="total" class="col-md-3 control-label">
                            <i class="fa fa-usd text-primary" aria-hidden="true"></i>&nbsp; Valor total a pagar:
                        </label>
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->valor, 0, '', '.') . " COP" ?>
                            </span>
                        </div>
                    </div>
                </fieldset>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <img class="img-thumbnail img-comprobante" src="<?php echo base_url() . $comprobante->path . $comprobante->imagen ?>" alt="comprobante de pago">
                    </div>
                </div>
            </div>

            <div class="panel-footer text-right">
                <input type="hidden" name="orden_id" value="<?php echo $orden->id ?>">
                <a href="<?php echo base_url(['ordenes-de-compra']) ?>" class="btn btn-default">
                    <i class="fa fa-undo" aria-hidden="true"></i> &nbsp;Cancelar
                </a>
                <a href="<?php echo base_url(['ordenes-de-compra', 'rechazar', $orden->id]) ?>" class="btn btn-danger">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp;Rechazar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-check" aria-hidden="true"></i>&nbsp;Aprobar
                </button>
            </div>
        </form>
    </div>
</div>