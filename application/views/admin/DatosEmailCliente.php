<div class="x_panel">
    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>Correo Cali</h2>
        </div>
        <form id="correoCali" class="form-horizontal" method="post" action="<?php echo base_url() ?>Admin/email">
            <div class="panel-body">

                <div class="container">
                    <div class="left">

                        <br>
                        <input id="checkall" type="checkbox" onclick="marcar(this);" checked />
                        <label for="checkall">Desmarcar / Marcar Todos</label>
                    </div>
                </div>
                <p></p>
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
                                Valor
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
                        <?php foreach ($paquetes as $key => $row):
                                    $valor_seguro = 0;
                                    $primer_nombre = $row['primer_nombre'];
                                    $segundo_nombre = $row['segundo_nombre'];
                                    $apellidos = $row['apellidos'];
                                    $articulo = $row['nombre'];
                                    $traking = $row['id_track'];
                                    $seguro = $row['seguro'];
                                    $id = $row['id'];
                                    $id2 = $row['articulo_id'];
                                    $valor = $row['valor'];
                                    $valorUsd = $row['valor_paquete'];
                                    $peso = $row['peso'];
                                    $valTotal = $valTotal + $valor;
                                    $total_peso = $total_peso + $peso;
                                    $valor_paquete_cop = $valorUsd * $_SESSION['trm']['hoy'];
                                    $descuento= $descuento;

                                    if ($valorUsd >= $config->seguro_max+1) {
                                        $valor_seguro = ($valor_paquete_cop * $config->seguro_obligatorio)/100;
                                    }

                                    if ($valorUsd >= $config->seguro_min && $valorUsd <= $config->seguro_max && $seguro == "si") {
                                        $valor_seguro = ($valor_paquete_cop * $config->seguro_opcional)/100;
                                    }

                                   if($row['cobrar_seguro']==1){
                                    $valor_seguro = 0;
                                   }

                                    $valor_total_seguro = round($valor_total_seguro + $valor_seguro);
                                    $valor_total_orden += round($valor_seguro + $valor);

                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" data-valor="<?php echo $valor ?>" data-libras="<?php echo $peso ?>" data-seguro="<?php echo round($valor_seguro); ?>"
                                  class="value" name="id[]" value="<?php echo $id2; ?>" checked onclick="setTotal(this)">
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
                                <?php echo "$ " . $valorUsd . " USD"; ?>
                            </td>
                            <td>

                                <?php echo $peso . " Lb"; ?>

                            </td>
                            <td>
                                <?php echo "$ " . number_format($valor, 0, '', '.') . " COP"; ?>
                            </td>
                            <td>
                                <?php echo "$ " . number_format(round($valor_seguro), 0, '', '.') . " COP"; ?>
                            </td>
                        </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
                <div class="form-group">
                    <label for="librasTotal" class="col-md-3 control-label">
                        Total de libras:
                    </label>
                    <div class="col-sm-2">
                        <input readonly id="librasTotal" type="text" class="form-control" data-libras="<?php echo $total_peso ?>" value="<?php echo $total_peso ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="valTotal" class="col-md-3 control-label">
                        Valor envío Miami - Cali:
                    </label>
                    <div class="col-sm-2">
                        <input readonly id="valTotal" type="text" class="form-control" name="valor_envio" data-valor="<?php echo $valTotal ?>"
                            value="<?php echo '$ ' . number_format($valTotal, 0, '', '.'); ?>" />
                    </div>

                </div>
                <div class="form-group">
                    <label for="impuestos" class="col-md-3 control-label">
                        Impuestos:
                    </label>
                    <div class="col-sm-2">
                        <input id="impuestos_orden" type="text" class="form-control" name="impuestos" value="0" onblur="calcularTotal()">
                    </div>

                </div>
                <div class="form-group">
                    <label for="descuento" class="col-md-3 control-label">
                        Descuento:
                    </label>
                    <div class="col-sm-2">
                        <input id="descuento_orden" type="text" class="form-control" name="descuento" value="<?php echo '$ ' . number_format($descuento, 0, '', '.'); ?>" onblur="calcularTotal()">
                    </div>

                </div>
                <div class="form-group">
                    <label for="seguro" class="col-md-3 control-label">
                        Seguro:
                    </label>
                    <div class="col-sm-2">
                        <input id="seguro" type="text" class="form-control" name="seguro" value="<?php echo $valor_total_seguro ?>"
                            data-valor="<?php echo $valor_total_seguro ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="envio_nacional" class="col-md-3 control-label">
                        Valor envío nacional y/o domicilio:
                    </label>
                    <div class="col-sm-2">
                        <input id="envio_nacional" type="text" class="form-control" name="envio_nacional" value="0"
                            onblur="calcularTotal()">
                    </div>
                    <div class="col-md-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="tipo_envio" value="0" required>
                                <b>Domicilio</b>
                            </label>&nbsp;
                            <label>
                                <input type="radio" name="tipo_envio" value="1">
                                <b>Envío nacional</b>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="envio_nacional" class="col-md-3 control-label">
                        Valor total de la orden:
                    </label>
                    <div class="col-sm-2">
                        <input id="valorTotalOrden" type="text" class="form-control" name="valor_total_orden" value="<?php echo $valor_total_orden-$descuento ?>"
                            data-valor-total-orden="<?php echo $valor_total_orden-$descuento?>">
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href="<?php echo base_url(['bandeja-de-salida']) ?>" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp; Enviar
                </button>
            </div>
        </form>
    </div>
</div>
<span id="constantes" data-val-total="<?php echo $valTotal ?>" data-trm="<?php echo $_SESSION['trm']['hoy'] ?>"
    data-total-seguro="<?php echo $valor_total_seguro ?>" data-total-libras="<?php echo $total_peso?>"
    data-valor-total-orden="<?php echo $valor_total_orden?>" data-descuento="<?php echo $descuento?>"
    data-cupon-tipo="<?php echo $cupon_tipo?>" data-cupon-valor="<?php echo $cupon_valor?>"></span>

