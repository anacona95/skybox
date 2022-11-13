<?php if (isset($orden) && count($orden) > 0): ?>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th>
                <input id="checkall" type="checkbox" onclick="marcar(this);" checked/>
            </th>
            <th>Artículo</th>
            <th>Tracking</th>
            <th>Valor</th>
            <th>Peso</th>
            <th>Valor flete</th>
            <th>Seguro</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articulos as $row):
    $valor_seguro = 0;
    $valor_paquete_cop = $row->valor_paquete * $_SESSION['trm']['hoy'];

    if ($row->valor_paquete >= $config->seguro_max+1) {
        $valor_seguro = ($valor_paquete_cop * $config->seguro_obligatorio)/100;
    }

    if ($row->valor_paquete >= $config->seguro_min && $row->valor_paquete <= $config->seguro_mac && $row->seguro == "si") {
        $valor_seguro = ($valor_paquete_cop * $config->seguro_opcional)/100;
    }
    if($row->cliente->cobrar_seguro==1){
        $valor_seguro = 0;
    }
    ?>
        <tr>
            <td>
                <input type="checkbox" class="addPaquete" name="articulos[]" value="<?php echo $row->articulo_id ?>" checked>
            </td>
            <td>
                <?php echo $row->nombre ?>
            </td>
            <td>
                <?php echo $row->id_track ?>
            </td>
            <td>
                $&nbsp;
                <?php echo $row->valor_paquete ?> &nbsp;USD
            </td>
            <td>
                <?php echo $row->peso ?> &nbsp;Lb
            </td>
            <td>
                $&nbsp;
                <?php echo number_format($row->valor, 0, '', '.') ?> &nbsp;COP
            </td>
            <td>
                $&nbsp;
                <?php echo number_format(round($valor_seguro), 0, '', '.') ?>&nbsp;COP
            </td>

        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<div class="form-group">
    <label for="flete" class="col-md-6">Valor envío nacional y/o domicilio:</label>
    <div class="col-md-4">
        <input type="text" name="flete" id="flete" class="form-control" value='<?php echo "$ " . number_format($orden->flete_nacional, 0, "", ".") ?>'>
        <input type="hidden" name="orden" value="<?php echo $orden->id ?>">
    </div>
</div>

<?php else: ?>
<div class="alert alert-danger" role="alert">
    <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
    El cliente no tiene ordenes de compra activas.
</div>
<?php endif;?>