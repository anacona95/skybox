<div class="x_panel">
    <?php if($this->session->flashdata('estado-compras')): ?>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('estado-compras') ?>
                            </div>
                            <?php endif;?>
     <?php if($this->session->flashdata('error-compras')): ?>
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('error-compras') ?>
                            </div>
                            <?php endif;?>
    <div class="x_title">
        <h2><FONT FACE="helvetica">Estado de compras</font></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
<form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/estadosCambiarCompra">
    <div class="container">
    <div  class="left" >
         
<SELECT NAME="estados" SIZE="1"> 
   <OPTION VALUE="Pago recibido">Pago recibido</OPTION> 
   <OPTION VALUE="Realizada">Realizada</OPTION>   
</SELECT> 
  <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Enviar</font></button>
  <input type="checkbox" onclick="marcar(this);" /><FONT FACE="helvetica"> Marcar/Desmarcar Todos</font>
     </div>
    </div>
        <table  class="dataTable table-hover table table-striped table-bordered">
            <thead>
                <tr>
                    <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                    <th><FONT FACE="helvetica">Cliente</font></th>
                    <th><FONT FACE="helvetica">Enlace</font></th>
                    <th><FONT FACE="helvetica">Referencia</font></th>
                    <th><FONT FACE="helvetica">Talla</font></th>
                    <th><FONT FACE="helvetica">Color</font></th>
                    <th><FONT FACE="helvetica">Cantidad</font></th>
                    
                    <th><FONT FACE="helvetica">Estado</font></th>
                    <th><FONT FACE="helvetica">Valor$</font></th>
                    <th><FONT FACE="helvetica">Dirección de entrega</font></th>
                    
                    
                    
                </tr>
            </thead>


            <tbody>
                <?php foreach ($estadosCompras as $key => $row) :
                $primer_nombre=$row['primer_nombre'];
                $segundo_nombre=$row['segundo_nombre'];
                $apellidos=$row['apellidos'];		
		$enlace=$row['enlace'];
		$referencia=$row['referencia'];
		$talla=$row['talla'];
		$color=$row['color'];
		$cantidad=$row['cantidad'];
              
                $estado=$row['estado_compra'];
                $valor=$row['valor_compra'];
                $pais=$row['pais'];
                $ciudad=$row['ciudad'];
                $direccion=$row['direccion'];
                $id=$row['id_compra'];
                
                 ?>
                <tr>
                    <td><input  type="checkbox" name="id[]" value="<?php echo $id; ?>"></td>
                    <td><FONT FACE="helvetica"><?php echo $primer_nombre; ?> <?php echo $segundo_nombre; ?> <?php echo $apellidos; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $enlace; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $referencia; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $talla; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $color; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $cantidad; ?></font></td>
                  
                    <td><FONT FACE="helvetica"><?php echo $estado; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo number_format( $valor, 0, '', '.'); ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $pais; ?>/<?php echo $ciudad; ?>/<?php echo $direccion; ?></font></td>
                    
                 
                    
                   
                </tr>
              <?php endforeach?>  
            </tbody>
        </table>
</form>