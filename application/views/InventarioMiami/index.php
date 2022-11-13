<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Inventario Miami</h2>
        </div>
        <div class="panel-body">
            <div class="form-group col-xs-12">
                <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="<?php echo base_url()?>ingreso-bodega" title="Ingrese un nuevo paquete prealertado a la bodega">
                            <i class="fa fa-cube"></i>&nbsp;Ingresar nuevo paquete</a>
                    </li>
                </ul>
            </div>
            <table class="dataTable table-hover table table-striped table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Id cliente</th>
                        <th>Cliente</th>
                        <th>Ciudad</th>
                        <th>Artículo</th>
                        <th>Tracking</th>
                        <th>Peso</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($articulos as $articulo):?>
                    <tr>
                        <td>
                            <?php echo $articulo->cliente->id?>
                        </td>
                        <td>
                            <?php echo $articulo->cliente->primer_nombre." ",$articulo->cliente->apellidos?>
                        </td>
                        <td>
                            <?php echo $articulo->cliente->ciudad?>
                        </td>
                        <td>
                            <?php echo $articulo->nombre?>
                        </td>
                        <td>
                            <?php echo $articulo->id_track?>
                        </td>
                        <td>
                            <?php echo $articulo->peso?>
                        </td>
                        <td>
                            <?php echo $articulo->estadoArticulo?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>