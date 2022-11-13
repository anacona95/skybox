<div class="x_panel">
    <div class="panel panel-default">

        <?php if ($this->session->flashdata('envios-entregados')): ?>
        <div class="alert alert-info" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $this->session->flashdata('envios-entregados') ?>
        </div>
        <?php endif;?>
        <?php if ($this->session->flashdata('error-entregados')): ?>
        <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo $this->session->flashdata('error-entregados') ?>
        </div>
        <?php endif;?>
        <div class="panel-heading">
            <h2>Paquetes entregados</h2>
        </div>
        <div id="qSearch" class="row x_content">
            <form class="form-horizontal" action="/Admin/articulosEntregados/" method="GET">
                <div class="col-md-4 col-md-offset-8">
                    <input type="text" placeholder="Buscar..." name="q" autocomplete="off" class="form-control" value="<?php if (isset($_GET['q'])): echo $_GET['q'];endif;?>">
                </div>
            </form>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url() ?>Admin/estadosCambiarEntregados">
            <div class="panel-body">
                <div class="container">
                    <br>
                    <br>

                    <div class="col-md-4">
                        <select class="form-control " name="estados">
                            <option value="Prealertado">Prealertado</option>
                            <option value="En Cali">En Cali</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i>
                            Actualizar
                        </button>
                    </div>
                </div>
                <br>
                <br><br><br>

                <table class="dataTable table-hover table table-striped table-bordered" data-page-length='100'>
                    <thead>
                        <tr>
                            <th class="text-center" style="padding-left: 19px;">
                                <input id="checkall2" type="checkbox" onclick="markAll(this);">
                            </th>
                            <th>Cliente</th>
                            <th>Articulo</th>
                            <th>Tracking</th>
                            <th>Peso</th>
                            <th>Valor envío</th>
                            <th>Fecha entrega a cliente</th>
                            <th>Estado</th>
                            <th>Puntos</th>
                            <th>Seguro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estados as $key => $row):
    $primer_nombre = $row['primer_nombre'];
    $segundo_nombre = $row['segundo_nombre'];
    $apellidos = $row['apellidos'];
    $articulo = $row['nombre'];
    $traking = $row['id_track'];
    $fecha = $row['fecha_entrega'];
    $estado = $row['estadoArticulo'];
    $puntos = $row['puntos'];
    $seguro = $row['seguro'];
    $descripcion = $row['descripcion'];
    $id = $row['articulo_id'];
    $valor = $row['valor'];
    $peso = $row['peso'];
    $pais = $row['pais'];
    $ciudad = $row['ciudad'];
    $direccion = $row['direccion'];

    ?>
	                        <tr>
	                            <td>
	                                <input class="all" type="checkbox" name="id[]" value="<?php echo $id; ?>">
	                            </td>
	                            <td>
	                                <?php echo $primer_nombre; ?>
	                                <?php echo $segundo_nombre; ?>
	                                <?php echo $apellidos; ?>
	                            </td>
	                            <td>
	                                <?php echo $articulo; ?>
	                            </td>
	                            <td>
	                                <?php echo $traking; ?>
	                            </td>
	                            <td>
	                                <?php echo $peso; ?>
	                            </td>
	                            <td>
	                                <?php echo $valor; ?>
	                            </td>
	                            <td>
	                                <?php echo $fecha; ?>
	                            </td>
	                            <td>
	                                <?php echo $estado; ?>
	                            </td>
	                            <td>
	                                <?php echo $puntos; ?>
	                            </td>
	                            <td>
	                                <?php echo $seguro; ?>
	                            </td>

	                        </tr>
	                        <?php endforeach?>
                    </tbody>
                </table>
                <?=$this->pagination->create_links()?>
            </div>
        </form>
    </div>
</div>