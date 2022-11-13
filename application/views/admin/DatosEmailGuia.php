<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Correo guía
            </h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url()?>Admin/emailGuia">
            <div class="panel-body">
                <div class="form-group">
                    <label for="empresa" class="col-sm-2 control-label">
                        Empresa de envío:
                    </label>
                    <div class="col-sm-2">
                        <select id="empresa" name="empresa" class="form-control" required>
                            <option value="Deprisa">Deprisa</option>
                            <option value="Servientrega">Servientrega</option>
                            <option value="Coordinadora">Coordinadora</option>
                            <option value="Envia">Envía</option>
                            <option value="TCC">TCC</option>
                            <option value="Interrapidisimo">Interrapidisimo</option>
                            <option value="Fedex">Fedex</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="numero_guia" class="col-sm-2 control-label">
                        Número de guía:
                    </label>
                    <div class="col-sm-2">
                        <input id="numero_guia" type="number" class="form-control" name="numero_guia" required />
                    </div>
                    <label for="fecha" class="col-sm-2 control-label">
                        Fecha de llegada:
                    </label>
                    <div class="col-sm-2">
                        <input id="fecha" type="date" class="form-control" name="fecha" required />
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <input id="checkall" type="checkbox" checked onclick="marcar(this);" />
                    <label for="checkall"> Marcar / Desmarcar Todos</label>
                </div>
                <br>
                <br>
                <table class="dataTable table-hover table table-striped table-bordered" data-page-length='100'>
                    <thead>
                        <tr>
                            <th>
                                <i class="fa fa-cog" aria-hidden="true"></i>
                            </th>
                            <th>
                                Cliente
                            </th>
                            <th>
                                Artículo
                            </th>
                            <th>
                                Tracking
                            </th>
                            <th>
                                Peso
                            </th>
                            <th>
                                Valor flete
                            </th>
                            <th>
                                Seguro
                            </th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php foreach ($paquetess as $key => $row) :
                                $primer_nombre=$row['primer_nombre'];
                                $segundo_nombre=$row['segundo_nombre'];
                                $apellidos=$row['apellidos'];
                                $articulo=$row['nombre'];
                                $traking=$row['id_track'];
                                $seguro=$row['seguro'];
                                $id=$row['id'];
                                $id2=$row['articulo_id'];
                                $valor=$row['valor'];
                                $peso=$row['peso'];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="id[]" value="<?php echo $id2; ?> " checked>
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
                                <?php echo "$ ".number_format($valor,0,'','.'); ?>
                            </td>
                            <td>
                                <?php echo $seguro; ?>
                            </td>
                        </tr>
                        <?php endforeach?>
                    </tbody>

                </table>
                <div class="form-group  col-md-5">
                    <label for="mensaje">Observaciones:</label>
                    <textarea class="form-control" name="mensaje" id="mensaje" rows="5"></textarea>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="<?php echo base_url(['bandeja-de-salida'])?>" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-envelope-o"></i>
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>
