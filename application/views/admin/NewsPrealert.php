<?php if (count($articulos) == 0): ?>
    <p>No hay pre-alertas nuevas</p>
<?php endif;?>

<?php foreach ($articulos as $row): ?>
    <P class="list-group-item-heading text-capitalize"><b>Cliente:</b>&nbsp;<?php echo $row->cliente ?></P>
    <P class="list-group-item-heading"><b>Nombre:</b>&nbsp;<?php echo $row->nombre ?></P>
    <p class="list-group-item-text" style="word-wrap: break-word;"><b>Tracking:</b>&nbsp;<?php echo $row->tracking ?></p>
    <hr>
<?php endforeach;?>
