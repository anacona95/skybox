<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Ordenes pagadas</h2>
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
                <li role="presentation"><a href="<?php echo base_url(['ordenes-de-compra']) ?>">Listado</a></li>
            </ul>
            <br>
            <div class="row x_content">
                <form class="form-horizontal" action="/ordenes-de-compra/pagadas" method="GET">
                    <div class="col-md-4 col-md-offset-8">
                        <input type="text" placeholder="Buscar..." name="q" autocomplete="off" class="form-control"
                            value="<?php if (isset($_GET['q'])): echo $_GET['q'];endif;?>">
                    </div>
                </form>
            </div>
            <table class="dataTable table-hover table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No. Orden</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Fecha de registro</th>
                        <th>Fecha de pago</th>
                        <th class="text-center">Verificar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagadas as $row): ?>
                    <tr>
                        <td>
                            <?php echo $row['orden_numero'] ?>
                        </td>
                        <td>
                            <?php echo $row['primer_nombre'] . " " . $row['apellidos'] ?>
                        </td>
                        <td>
                            <?php echo "$ " . number_format($row['orden_valor'], 0, '', '.') ?>
                        </td>
                        <td>
                            <?php echo date('d/m/Y', $row['fecha_registro']) ?>
                        </td>
                        <td>
                            <?php echo date('d/m/Y', $row['fecha_pago']) ?>
                        </td>
                        <td class="text-center">
                            <a class="fa-lg" href="<?php echo base_url(['ordenes-de-compra', 'ver', $row['orden_id']]) ?>"
                                title="Más información">
                                <i class="fa fa-eye"></i>
                            </a>
                            &nbsp;
                            <a target="_blank" href="<?php echo base_url(['ordenes-de-compra', 'imprimir-prueba', $row['orden_id']]) ?>"
                                title="Imprimir prueba de entrega">
                                <i class="fa fa-print fa-lg" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <a download href="<?= base_url().$row['path'].$row['name_path']?>" title="Descargar factura">
                                <i class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></i>
                            </a>

                        </td>

                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <?=$this->pagination->create_links()?>
        </div>
    </div>
</div>