<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Mis puntos</h2>
        </div>
        <div class="panel-body">
            <div class="alert alert-info" role="alert">
                <i class="fa fa-trophy fa-lg"></i>&nbsp;
                <span class="cutom-puntos">Puntos acumulados:&nbsp;
                    <b>
                        <?php echo $puntos->cantidad ?>
                    </b>
                </span>
                &nbsp; &nbsp;
                <span class="custom-puntos">Puntos usados:&nbsp;
                    <b>
                        <?php echo $puntos->utilizados ?>
                    </b>
                </span>
            </div>
            <?php if ($this->session->flashdata('msgError')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('msgOk')): ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgOk') ?>
            </div>
            <?php endif;?>
            <br>
            <table class="dataTable table-hover table table-striped table-bordered table-responsive" data-order="[[ 0, &quot;desc&quot; ]]">
                <thead>
                    <tr>
                        <th>No. Orden</th>
                        <th class="hidden-xs">Valor</th>
                        <th class="hidden-xs">Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Asignar puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($ordenes && $ordenes->descuento == 0):?>
                    <tr>
                        <td>
                            <?php echo $ordenes->factura ?>
                        </td>
                        <td class="hidden-xs">
                            <?php echo "$ " . number_format($ordenes->valor, 0, '', '.') ?>
                        </td>
                        <td class="hidden-xs">
                            <?php echo date('d/m/Y', $ordenes->fecha) ?>
                        </td>
                        <td>
                            <?php echo $ordenes->estados[$ordenes->estado] ?>
                        </td>
                        <td class="text-center">
                            <?php if ($puntos->cantidad >= 10): ?>
                            <a href="<?php echo base_url(['mis-puntos','asignar-puntos',$ordenes->id]) ?>" title="Asignar puntos">
                                <i class="fa fa-star fa-lg"></i>
                            </a>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>