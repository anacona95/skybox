<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Bandeja de salida</h2>
        </div>
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
            <ul class="nav nav-tabs">
                <li role="presentation" class="active">
                    <a href="#clientesCali" aria-controls="prealery" role="tab" data-toggle="tab">
                        Correos
                    </a>
                </li>
                <li role="presentation">
                    <a href="#clientesDisponible" aria-controls="prealery" role="tab" data-toggle="tab">
                        Envío de guía
                    </a>
                </li>
            </ul>
            <br>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="clientesCali">
                    <table class="dataTable table-hover table table-striped table-bordered" data-page-length='100'>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Ciudad</th>
                                <th>Correo electrónico</th>
                                <th class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $key => $row) :
                                     $primer_nombre=$row->primer_nombre;
                                     $segundo_nombre=$row->segundo_nombre;
                                     $apellidos=$row->apellidos;
                                     $telefono=$row->telefono;
                                     $email=$row->email;
                                     $id=$row->id;
                                     $ciudad = $row->ciudad;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $primer_nombre; ?>
                                    <?php echo $segundo_nombre; ?>
                                    <?php echo $apellidos; ?>
                                </td>
                                <td>
                                    <?php echo $telefono;?>
                                </td>
                                <td>
                                    <?php echo $ciudad?>
                                </td>
                                <td>
                                    <?php echo $email;?>
                                </td>
                                <td class="text-center">
                                <?php if($row->validoNuevaOrden()):?>
                                    <a href="<?php echo base_url(['bandeja-de-salida','agrupar-paquetes',$id])?>" title="Crear orden">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </a>
                                    <?php else:?>
                                    <a id="modal-ordenes" href="#" title="Agregar a una orden" onclick="getOrdenes(<?php echo $id?>)">
                                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i>
                                    </a>
                                <?php endif;?>
                                </td>
                            </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="clientesDisponible">
                    <table class="dataTable table-hover table table-striped table-bordered" data-page-length='100'>
                        <thead>
                            <tr>
                                <th>
                                    Nombre
                                </th>
                                <th>
                                    Teléfono
                                </th>
                                <th>
                                    Ciudad
                                </th>
                                <th>
                                    Correo
                                </th>
                                <th class="text-center">
                                    Seleccionar paquetes
                                </th>
                            </tr>
                        </thead>


                        <tbody>
                            <?php foreach ($clientesEnvio as $key => $row) :
                            
                                    $primer_nombre=$row['primer_nombre'];
                                    $segundo_nombre=$row['segundo_nombre'];
                                    $apellidos=$row['apellidos'];
                                    $telefono=$row['telefono'];
                                    $email=$row['email'];
                                    $id=$row['id'];
                                    $ciudad = $row['ciudad'];
                            ?>
                            <tr>
                                <td>
                                    <?php echo $primer_nombre; ?>
                                    <?php echo $segundo_nombre; ?>
                                    <?php echo $apellidos; ?>
                                </td>
                                <td>
                                    <?php echo $telefono; ?>
                                </td>
                                <td>
                                    <?php echo $ciudad;?>
                                </td>
                                <td>
                                    <?php echo $email; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo base_url(['bandeja-de-salida','envio-de-guia',$id])?>" title="Seleccionar paquetes">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="agregarPaquetes" class="form-horizontal" action="<?php echo base_url(['OrdenesAdmin','agregarPaquetes'])?>" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Agregar paquetes a la orden</h4>
                    </div>
                    <div id="modal-body" class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button id="btn-modal" type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="constantes" data-url="<?php echo base_url(['Admin','getOrdenes'])?>"></span>
</div>