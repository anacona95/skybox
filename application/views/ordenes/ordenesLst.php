<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Mis ordenes</h2>
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
                <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
            <table class="dataTable table-hover table table-striped table-bordered table-responsive" data-order="[[ 0, &quot;desc&quot; ]]">
                <thead>
                    <tr>
                        <th>No. Orden</th>
                        <th class="hidden-xs">Valor</th>
                        <th class="hidden-xs">Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Opciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordenes as $row): ?>
                    <tr>
                        <td>
                            <?php echo $row->factura ?>
                        </td>
                        <td class="hidden-xs">
                            <?php echo "$ " . number_format($row->valor, 0, '', '.') ?>
                        </td>
                        <td class="hidden-xs">
                            <?php echo date('d/m/Y', $row->fecha) ?>
                        </td>
                        <td>
                            <?php echo $row->estados[$row->estado] ?> &nbsp;
                            <?php if($row->descuento > 0):?>
                            <i class="fa fa-star text-info" title="
                            <?php if($row->descuento == '10000'){
                                        echo '10 puntos';
                                    }
                                    if($row->descuento == '20000'){
                                        echo '20 puntos';
                                    }
                                    if($row->descuento == '30000'){
                                        echo '30 puntos';
                                    }
                                    if($row->descuento == '40000'){
                                        echo '40 puntos';
                                    } 
                                    if($row->descuento == '50000'){
                                        echo '50 puntos';
                                    } 
                            ?>
                            "></i>
                            <?php endif;?>
                        </td>
                        <td class="text-center">
                            <?php if ($row->estado == 0 && $row->valor > 0): ?>
                            <a href="<?php echo base_url(['mis-ordenes', 'pagar-en-linea', $row->id]) ?>" title="Pagar online">
                                <i class="fa fa-credit-card-alt fa-lg" aria-hidden="true"></i>
                            </a>

                            <?php endif;?>
                            <?php if ($row->estado == 0): ?> &nbsp;
                            <a href="<?php echo base_url(['mis-ordenes', 'pagar-con-comprobante', $row->id]) ?>" title="Pagar con comprobante">
                                <i class="fa fa-paperclip fa-lg" aria-hidden="true"></i>
                            </a>
                            <?php endif;?>
                            <?php if ($row->estado == 4): ?>&nbsp;
                            <a href="<?php echo base_url(['mis-ordenes', 'reenviar-comprobante', $row->id]) ?>" title="Reenviar comprobante">
                                <i class="fa fa-undo fa-lg" aria-hidden="true"></i>
                            </a>
                            <?php endif;?>
                            <?php if ($row->estado != 5): ?>&nbsp;
                            <a href="<?php echo base_url(['mis-ordenes', 'ver', $row->id]) ?>" title="ver orden">
                                <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                            </a>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>
</div>