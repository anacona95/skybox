<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Ordenes de compra</h2>
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
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="<?php echo base_url(['ordenes-de-compra', 'pagadas']) ?>">Pagadas</a></li>
                <!-- <li role="presentation"><a href="/ordenes-de-compra/anuladas">Anuladas</a></li> -->
            </ul>
            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#all" aria-controls="prealery" role="tab" data-toggle="tab">Todas</a>
                </li>
                <li role="presentation">
                    <a href="#aprobacion" aria-controls="all" role="tab" data-toggle="tab">En aprobación</a>
                </li>
                <li role="presentation">
                    <a href="#anuladas" aria-controls="anuladas" role="tab" data-toggle="tab">Anuladas</a>
                </li>
            </ul>
            <br>
            <div class="col-md-12">
                <b>Cartera:  <?php echo "$ " . number_format($total_pendiente, 0, '', '.') ?></b>
            </div>
            <br><br>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="all">
                    <table class="data-table dataTable table-hover table table-striped table-bordered" data-order="[[ 0, &quot;desc&quot; ]]"
                        data-page-length='50'>
                        <thead>
                            <tr>
                                <th>No. Orden</th>
                                <th>Cliente</th>
                                <th>Ciudad</th>
                                <th>Valor</th>
                                <th>Fecha</th>
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
                                <td>
                                    <?php echo $row->cliente->primer_nombre . " " . $row->cliente->apellidos ?>
                                </td>
                                <td>
                                    <?php echo $row->cliente->ciudad ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($row->valor - $row->totalAbonos(), 0, '', '.') ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', $row->fecha) ?>
                                </td>
                                <td>
                                    <?php echo $row->estados[$row->estado] ?> &nbsp;
                                    <?php if ($row->descuento > 0): ?>
                                    <i class="fa fa-star  title="
                                    <?php if ($row->descuento == '10000') {
                                                echo '10 puntos';
                                            }
                                            if ($row->descuento == '20000') {
                                                echo '20 puntos';
                                            }
                                            if ($row->descuento == '30000') {
                                                echo '30 puntos';
                                            }
                                            if ($row->descuento == '40000') {
                                                echo '40 puntos';
                                            }
                                            if ($row->descuento == '50000') {
                                                echo '50 puntos';
                                            }
                                            ?>
                                    "></i>
                                    <?php endif;?>
                                </td>

                                <td class="text-center">
                                    <?php if ($row->estado == 0 || $row->estado==4): ?>
                                    <a href="<?php echo base_url(['ordenes-de-compra', 'pagar-orden', $row->id]) ?>"
                                        title="Pagar manualmente">
                                        <i class="fa fa-check fa-lg"></i>
                                    </a>
                                    <?php endif;?> &nbsp;
                                    <a class="fa-lg" href="<?php echo base_url(['ordenes-de-compra', 'ver', $row->id]) ?>"
                                        title="Más información">
                                        <i class="fa fa-eye"></i>
                                    </a>&nbsp;
                                    <a class="fa-lg" target="_blank" href="<?php echo base_url()?>imprimir-informacion?id=<?php echo $row->cliente->id;?>"
                                        title="Imprimir rótulo">
                                        <i class="fa fa-id-card-o"></i>
                                    </a>
                                    <?php if ($row->estado != 2): ?> &nbsp;
                                    <a target="_blank" href="<?php echo base_url(['ordenes-de-compra', 'imprimir-prueba', $row->id]) ?>"
                                        title="Imprimir prueba de entrega">
                                        <i class="fa fa-print fa-lg" aria-hidden="true"></i>
                                    </a>
                                    <?php endif;?>
                                    <?php if ($row->estado != 2): ?> &nbsp;
                                    <a href="<?php echo base_url(['ordenes-de-compra', 'abandonar-orden', $row->id]) ?>"
                                        title="Abandonar orden" onclick="return confirm('¿Está seguro de abandonar la orden?')">
                                        <i class="fa fa-user-times fa-lg" aria-hidden="true"></i>
                                    </a>
                                    <?php endif;?>
                                    <?php if ($row->estado != 2): ?> &nbsp;
                                    <a href="<?php echo base_url(['ordenes-de-compra', 'eliminar-orden', $row->id]) ?>"
                                        title="Eliminar">
                                        <i class="fa fa-trash fa-lg" aria-hidden="true"></i>
                                    </a>
                                    <?php endif;?>

                                </td>

                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="aprobacion">
                    <table class="data-table dataTable table-hover table table-striped table-bordered" data-order="[[ 0, &quot;desc&quot; ]]"
                        data-page-length='50'>
                        <thead>
                            <tr>
                                <th>No. Orden</th>
                                <th>Cliente</th>
                                <th>Ciudad</th>
                                <th>Valor</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-center">Verificar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enAprobacion as $row): ?>
                            <tr>
                                <td>
                                    <?php echo $row->factura ?>
                                </td>
                                <td>
                                    <?php echo $row->cliente->primer_nombre . " " . $row->cliente->apellidos ?>
                                </td>
                                <td>
                                    <?php echo $row->cliente->ciudad ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($row->valor, 0, '', '.') ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', $row->fecha) ?>
                                </td>
                                <td>
                                    <?php echo $row->estados[$row->estado] ?> &nbsp;
                                    <?php if ($row->descuento > 0): ?>
                                    <i class="fa fa-star  title="
                                    <?php if ($row->descuento == '10000') {
                                                echo '10 puntos';
                                            }
                                            if ($row->descuento == '20000') {
                                                echo '20 puntos';
                                            }
                                            if ($row->descuento == '30000') {
                                                echo '30 puntos';
                                            }
                                            if ($row->descuento == '40000') {
                                                echo '40 puntos';
                                            }
                                            if ($row->descuento == '50000') {
                                                echo '50 puntos';
                                            }
                                            ?>
                                    "></i>
                                    <?php endif;?>
                                </td>
                                <td class="text-center">

                                    <a href="<?php echo base_url(['ordenes-de-compra', 'verificar', $row->id]) ?>"
                                        title="Verificar">
                                        <i class="fa fa-search fa-lg"></i>
                                    </a>


                                </td>

                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="anuladas">
                    <table class="data-table dataTable table-hover table table-striped table-bordered" data-order="[[ 0, &quot;desc&quot; ]]" data-page-length='50'>
                        <thead>
                            <tr>
                                <th>No. Orden</th>
                                <th>Cliente</th>
                                <th>Valor</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($anuladas as $row): ?>
                            <tr>
                                <td>
                                    <?php echo $row->factura ?>
                                </td>
                                <td>
                                    <?php echo $row->cliente->primer_nombre . " " . $row->cliente->apellidos ?>
                                </td>
                                <td>
                                    <?php echo "$ " . number_format($row->valor, 0, '', '.') ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', $row->fecha) ?>
                                </td>
                                <td>
                                    <?php echo $row->estados[$row->estado] ?> &nbsp;
                                    <?php if ($row->descuento > 0): ?>
                                    <i class="fa fa-star text-info" title="
                                    <?php if ($row->descuento == '10000') {
                                            echo '10 puntos';
                                        }
                                        if ($row->descuento == '20000') {
                                            echo '20 puntos';
                                        }
                                        if ($row->descuento == '30000') {
                                            echo '30 puntos';
                                        }
                                        if ($row->descuento == '40000') {
                                            echo '40 puntos';
                                        }
                                        if ($row->descuento == '50000') {
                                            echo '50 puntos';
                                        }
                                        ?>
                                    "></i>
                                    <?php endif;?>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>