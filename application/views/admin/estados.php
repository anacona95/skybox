<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Inventario de artículos
            </h2>
        </div>
        <div class="panel-body">
            <?php if ($this->session->flashdata('estados')): ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check fa-lg"></i>&nbsp;
                <?php echo $this->session->flashdata('estados') ?>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('error-estados')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('error-estados') ?>
            </div>
            <?php endif;?>
            
            <div class="form-group x_content">
                <form class="form-horizontal" action="/tracking/" method="GET">
                    <div class="col-md-4 col-md-offset-8">
                        <div class="input-group">
                            <input type="text" placeholder="Buscar..." name="q" autocomplete="off" class="form-control" value="<?=$q? $q:"";?>">
                            <?php if($s):?>
                            <input type="hidden" name="s" value="<?= $s;?>">
                            <?php endif;?>
                            <span class="input-group-btn">
                                <?php if($q):?>
                                    <a href="/tracking" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                <?php else:?>
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <?php endif;?>

                            </span>
                        </div><!-- /input-group -->
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <ul class="nav nav-pills">
                            <li role="presentation" <?= $s=="Prealertado"?"class='active'":""?>>
                                <a href="/tracking?<?= $q? "q=".$q:"";?>&s=Prealertado">
                                    <b>Pre:</b>&nbsp;<span class="badge" style="background: #007099 !important; color: #fff;"><?php echo $count_prealertas;?></span>
                                </a>   
                            </li>
                            <li role="presentation" <?= $s=="Recibido y viajando"?"class='active'":""?>>
                                <a href="/tracking?<?= $q? "q=".$q:"";?>&s=Recibido+y+viajando">
                                    <b>Rec:</b>&nbsp;<span class="badge" style="background: #F2922B !important; color: #fff;"><?php echo $count_miami;?></span>
                                </a>
                            </li>
                            <li role="presentation" <?= $s=="En Cali"?"class='active'":""?>>
                                <a href="/tracking?<?= $q? "q=".$q:"";?>&s=En+Cali">
                                    <b>Cal:</b>&nbsp;<span class="badge" style="background: #2B9348 !important; color: #fff;"><?php echo $count_cali;?></span>
                                </a>
                            </li>
                            <li role="presentation" <?= $s=="Orden"?"class='active'":""?>>
                                <a href="/tracking?<?= $q? "q=".$q:"";?>&s=Orden">
                                    <b>Ord:</b>&nbsp;<span class="badge" style="background: #ac2925 !important; color: #fff;"><?php echo $count_orden;?></span>
                                </a>
                            </li>
                            <?php if($s):?>
                            <li role="presentation">
                                <a href="/tracking?<?= $q? "q=".$q:"";?>"><i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i></a>
                            </li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </div>
            <form class="form-horizontal" method="post" id="form-all">
                <div class="x_content">
                    <div class="col-md-4">
                        <select class="form-control " name="estados" size="1">
                            <option value="Prealertado">Prealertado</option>
                            <option value="Recibido y viajando">Recibido y viajando</option>
                            <option value="En Cali">En Cali</option>
                            <option value="Disponible">Disponible</option>
                            <option value="En tus manos">En tus manos</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-sm" onclick=" return updArticulosAll()">
                            <i class="fa fa-pencil"></i>
                            Actualizar
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 88px;" onclick="return dellArticulosAll()">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </button>
                    </div>
                </div>
                <table class="dataTable table-hover table table-striped table-hover table-bordered table-condensed table-responsive">
                    <caption>Listado de artículos</caption>
                    <thead>
                        <tr>
                            <th class="text-center" style="padding-left: 19px;">
                                <input id="checkall2" type="checkbox" onclick="markAll(this);">
                            </th>
                            <th class="text-center">Suite</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Ciudad</th>
                            <th class="text-center">Artículo</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Tracking</th>
                            <th class="text-center">USD</th>
                            <th class="text-center">Peso</th>
                            <th class="text-center">Envío</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Seguro</th>
                            <th class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$estados):?>
                            <tr>
                                <td colspan="14" class="text-center"><p>No se encontraron resultados.</p></td>
                            </tr>
                        <?php endif;?>
                        <?php
                            foreach ($estados as $key => $row):
                                $primer_nombre = $row['primer_nombre'];
                                $segundo_nombre = $row['segundo_nombre'];
                                $apellidos = $row['apellidos'];
                                $articulo = $row['nombre'];
                                $traking = $row['id_track'];
                                $estado = $row['estadoArticulo'];
                                $seguro = $row['seguro'];
                                $user_id = $row['user_id'];
                                $id = $row['articulo_id'];
                                $valor = $row['valor'];
                                $peso = $row['peso'];
                                $ciudad = $row['ciudad'];
                                $factura = $row['factura'];
                                $fecha = date("d/m/Y",$row['fecha_registro']);
                        ?>
                        <tr>
                            <td class="text-center">
                                <input class="all" type="checkbox" name="id[]" value="<?php echo $id; ?>">
                            </td>
                            <td ondblclick="dspInput(this)">
                                <p>
                                    <?php echo $row['user_id'] ?>
                                </p>
                                <input autofocus="true" data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="user_id" hidden="true" name="user_id" value="<?php echo $user_id; ?>">
                            </td>
                            <td>
                                <?php echo $primer_nombre; ?>
                                <?php echo $segundo_nombre; ?>
                                <?php echo $apellidos; ?>
                            </td>
                            <td>
                                <?php echo $ciudad; ?>
                            </td>
                            <td ondblclick="dspInput(this)">
                                <p>
                                    <?php echo $articulo; ?>
                                </p>
                                <input autofocus="true" data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="nombre" hidden="true" name="nombre" value="<?php echo $articulo; ?>">
                            </td>
                            <td>
                                <?php echo $fecha; ?>
                            </td>
                            <td ondblclick="dspInput(this)" class="text-center">
                                <p>
                                    <?php echo "...".substr($traking,-6); ?>
                                </p>
                                <input autofocus="true" data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="traking" hidden="true" type="text" name="traking" value="<?php echo $traking; ?>">
                            </td>
                            <td ondblclick="dspInput(this)" class="text-center">
                                <p>
                                    <?php echo $row['valor_paquete']; ?>
                                </p>
                                <input autofocus=" true " data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="valor_paquete" hidden="true" type="text" name="valor_paquete" value="<?php echo $row['valor_paquete']; ?>">
                            </td>
                            <td ondblclick="dspInput(this)" class="text-center">
                                <p>
                                    <?php echo $peso; ?>
                                </p>
                                <input autofocus="true" data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="peso" hidden="true" type="text" name="peso" value="<?php echo $peso; ?>">
                            </td>

                            <td ondblclick="dspInput(this)" class="text-center">
                                <p>
                                    <?php echo number_format($valor, 0, '', '.'); ?>
                                </p>
                                <input autofocus="true" data-row-id="<?php echo $id; ?>" onblur="sendInput(this)"
                                    data-type="valor" hidden="true" type="text" name="valor" value="<?php echo $valor; ?>">
                            </td>

                            <td class="text-center">
                                <?php echo $estado; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $seguro; ?>
                            </td>
                            <td class="text-center">
                                <a class="fa-lg" href="<?php echo base_url(['tracking', 'ver', $id]) ?>"
                                    title="Más información">
                                    <i class="fa fa-search "></i>
                                </a>
                               
                                <?php if($factura):?>
                                    &nbsp;
                                    <a download="factura-<?=$traking?>" class="fa-lg" href="<?= $factura?>"
                                    title="Descargar factura">
                                    <i class="fa fa-download "></i>
                                    </a>    
                                <?php endif;?>
                                 &nbsp;
                                <a class="fa-lg" href="<?php echo base_url() ?>Admin/rechazarEnvioss?id=<?php echo $id; ?>"
                                    title="Eliminar artículo" onclick="return confirm('¿Está seguro de eliminar el artículo?')">
                                    <i class="fa fa-trash "></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </form>
            <?=$this->pagination->create_links()?>
        </div>
        <span id="constantes" data-url="<?php echo base_url() ?>Admin/updArticle" data-url-form="<?php echo base_url() ?>"></span>
    </div>
</div>