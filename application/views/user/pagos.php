<div class="x_panel"  >
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Mis pagos</h2>
        </div>
        <div class="panel-body">
        <?php if ($this->session->flashdata('msgError')): ?>
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('msgError') ?>
        </div>
    <?php endif;?>
        <?php if ($this->session->flashdata('msgInfo')): ?>
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('msgInfo') ?>
        </div>
    <?php endif;?>
        <?php if ($this->session->flashdata('msgSuccess')): ?>
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('msgSuccess') ?>
        </div>
    <?php endif;?>
        <?php if ($this->session->flashdata('msgWarning')): ?>
        <div class="alert alert-warning" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('msgWarning') ?>
        </div>
    <?php endif;?>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#articulos" aria-controls="prealery" role="tab" data-toggle="tab">Artículos a pagar</a></li>
                <li role="presentation"><a href="#pagos" aria-controls="all" role="tab" data-toggle="tab">Bandeja de pagos</a></li>
            </ul>
            <br>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="articulos">
                    <table  class="dataTable table-hover table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Artículo</th>
                                <th>Tracking</th>
                                <th>Estado</th>
                                <th>Puntos</th>
                                <th>Seguro</th>
                                <th>Descripción</th>
                                <th>Pagar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
foreach ($objArticulos as $row):

?>
                            <tr>
                                <th><?php echo $row->nombre ?></th>
                                <th><?php echo $row->id_track ?></th>
                                <th><?php echo $row->estadoArticulo ?></th>
                                <th><?php echo $row->puntos ?></th>
                                <th><?php echo $row->seguro ?></th>
                                <th><?php echo $row->descripcion ?></th>
                                <th class="text-center"><a href="<?php echo base_url(['user', 'pagar-paquete', $row->articulo_id]) ?>" title="Pagar"><span class="fa fa-credit-card fa-lg"></span></a></th>

                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="pagos">
                    <table id="table-payment"  class="dataTable table-hover table table-striped table-bordered" order="DESC">
                        <thead>
                            <tr>
                                <th>No. Factura</th>
                                <th>Atículo</th>
                                <th>Tracking</th>
                                <th>Valor</th>
                                <th>Estado de pago</th>
                                <th>Fecha de pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
foreach ($objPagos as $row):
?>
                            <tr>
                                <th><?php echo $row->factura ?></th>
                                <th><?php echo $row->articulo->nombre ?></th>
                                <th><?php echo $row->articulo->id_track ?></th>
                                <th><?php echo "$ " . number_format($row->valor, 2, ',', '.'); ?></th>
                                <th><?php echo $row->statusLst["$row->estado"] ?></th>
                                <th><?php echo date('d/m/Y', $row->fecha_pago) ?></th>

                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
