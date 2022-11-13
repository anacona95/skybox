<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Listado de clientes</h2>
        </div>
        <div class="panel-body">
            <?php if($this->session->flashdata('cliente')): ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('cliente') ?>
            </div>
            <?php endif;?>
            <?php if($this->session->flashdata('error-cliente')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('error-cliente') ?>
            </div>
            <?php endif;?>
            <br>
            <div class="row x_content">
                <form class="form-horizontal" action="/informacion-clientes" method="GET">
                    <div class="col-md-4 col-md-offset-8">
                        <input type="text" placeholder="Buscar..." name="q" autocomplete="off" class="form-control"
                            value="<?php if (isset($_GET['q'])): echo $_GET['q'];endif;?>">
                    </div>
                </form>
            </div>
            <table class="dataTable table-hover table table-striped table-bordered" data-page-length='100'>
                <thead>
                    <tr>
                        <th>
                            Suite
                        </th>
                        <th>
                            Cliente
                        </th>
                        <th>Ciudad</th>
                        <th>
                            Correo
                        </th>
                        <th>
                            Telefono
                        </th>

                        <th>
                            Fecha de registro
                        </th>
                        <th class="text-center">Opciones</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Clientes as $key => $row) :
                            $primer_nombre=$row['primer_nombre'];
                            $segundo_nombre=$row['segundo_nombre'];
                            $apellidos=$row['apellidos'];
                            $fecha=$row['fecha_creacion'];
                            $email=$row['email'];
                            $telefono=$row['telefono'];
                            $id=$row['id'];
                            $ciudad = $row['ciudad']
                            
                             ?>
                    <tr>
                        <td>

                            <?php echo $id; ?>

                        </td>
                        <td>

                            <?php echo $primer_nombre; ?>
                            <?php echo $segundo_nombre; ?>
                            <?php echo $apellidos; ?>

                        </td>
                        <td>
                            <?php echo $ciudad; ?>
                        </td>
                        <td>

                            <?php echo $email; ?>

                        </td>
                        <td>

                            <?php echo $telefono; ?>

                        </td>

                        <td>

                            <?php echo $fecha; ?>

                        </td>
                        <td align="center">
                            <a href="<?php echo base_url()?>admin/crearAlerta?id=<?php echo $id;?>" title="Crear prealerta">
                                <i class="fa fa-bell fa-lg"></i>
                            </a>&nbsp;
                            <a href="<?php echo base_url()?>editar-clientes?id=<?php echo $id;?>" title="Actualizar">
                                <i class="fa fa-pencil fa-lg"></i>
                            </a>&nbsp;
                            <a href="<?php echo base_url()?>imprimir-informacion?id=<?php echo $id;?>" title="Imprimir rótulo"
                                target="_blank">
                                <i class="fa fa-id-card-o fa-lg"></i>
                            </a>&nbsp;
                        </td>
                    </tr>
                    <?php endforeach?>
                </tbody>
            </table>
            <?=$this->pagination->create_links()?>
        </div>
    </div>
</div>