<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Anular orden</h2>
        </div>

        <form class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin','eliminarProcess'])?>" method="POST" enctype="multipart/form-data">
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
                        <div class="col-md-2">
                            <span id="total" class="form-control">
                                <?php echo "$ " . number_format($orden->valor_bodega, 0, '', '.') . " COP" ?>
                            </span>
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
                </fieldset>
            </div>

            <div class="panel-footer text-right">
                <input type="hidden" name="orden_id" value="<?php echo $orden->id?>">
                <a href="<?php echo base_url(['ordenes-de-compra'])?>" class="btn btn-default">
                    <i class="fa fa-times" aria-hidden="true"></i>&nbsp; Cancelar
                </a>
                <button class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar la orden?')">
                    <i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Anular
                </button>
            </div>
        </form>
    </div>
</div>