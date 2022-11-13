 <div class="panel-body ">
                              
</div>
 <div class="panel-body ">
                              
</div>
  <div class="x_content">
      <div class="x_panel">
<div class="x_title">
    <h2><FONT FACE="helvetica">Estado de compras</font></h2>

        <div class="clearfix"></div>
    </div>
  
        <table id="dataTable table-hover" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><FONT FACE="helvetica">Enlace</font></th>
                    <th><FONT FACE="helvetica">Referencia</font></th>
                    <th><FONT FACE="helvetica">Talla</font></th>
                    <th><FONT FACE="helvetica">Color</font></th>
                    <th><FONT FACE="helvetica">Cantidad</font></th>                   
                    <th><FONT FACE="helvetica">Estado</font></th>
                    <th><FONT FACE="helvetica">Valor$</font></th>
                    
                    
                </tr>
            </thead>


            <tbody>
                <?php foreach ($estadosCom as $key => $row) :
		$enlace=$row['enlace'];
		$referencia=$row['referencia'];
		$talla=$row['talla'];
		$color=$row['color'];
		$cantidad=$row['cantidad'];
                $tipo=$row['tipo'];
                $estado=$row['estado_compra'];
                $valor=$row['valor_compra'];
                
                
                 ?>
                <tr>
                    
                    <td><FONT FACE="helvetica"><?php echo $enlace; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $referencia; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $talla; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $color; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $cantidad; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $estado; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo number_format( $valor, 0, '', '.'); ?></font></td>
                   
                </tr>
              <?php endforeach?>  
            </tbody>
        </table>
</div>
</div>
