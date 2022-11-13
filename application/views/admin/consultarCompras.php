<div class="x_panel">
                          <?php if($this->session->flashdata('compras')): ?>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('compras') ?>
                            </div>
                            <?php endif;?>
                        
    <div class="x_title">
        <h2><FONT FACE="helvetica">Pre-alerta compras</font> </h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
  <form class="form-horizontal" method="post" >
        <table  class="dataTable table-hover table table-striped table-bordered">
            <thead>
                <tr>
                    <th><FONT FACE="helvetica">Cliente</font></th>
                    <th><FONT FACE="helvetica">Enlace</font></th>
                    <th><FONT FACE="helvetica">Referencia</font></th>
                    <th><FONT FACE="helvetica">Talla</font></th>
                    <th><FONT FACE="helvetica">Color</font></th>
                    <th><FONT FACE="helvetica">Cantidad</font></th>
                   
                    <th><FONT FACE="helvetica">Estado</font></th>
                    <th><FONT FACE="helvetica">Aceptar</font></th>
                    <th><FONT FACE="helvetica">Rechazar</font></th>
                   
                    
                    
                </tr>
            </thead>


            <tbody>
                <?php foreach ($comprasAlerta as $key => $row) :
                $primer_nombre=$row['primer_nombre'];
                $segundo_nombre=$row['segundo_nombre'];
                $apellidos=$row['apellidos'];
		$enlace=$row['enlace'];
		$referencia=$row['referencia'];
		$talla=$row['talla'];
		$color=$row['color'];
		$cantidad=$row['cantidad'];
               
                $estado=$row['estado_compra'];
                $id=$row['id_compra'];
                
                
                 ?>
                <tr>
                    <td><FONT FACE="helvetica"><?php echo $primer_nombre; ?> <?php echo $segundo_nombre; ?> <?php echo $apellidos; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $enlace; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $referencia; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $talla; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $color; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $cantidad; ?></font></td>
                    
                    <td><FONT FACE="helvetica"><?php echo $estado; ?></font></td>
                    <td><a style="color:#0C030A" href="<?php echo base_url()?>admin/aceptarCompras?id=<?php echo $id;?>"  title="Aceptar compra">
                    <i class="fa fa-pencil-square"></i></a></td>
                    <td><a style="color:#0C030A" href="<?php echo base_url()?>admin/rechazarCompras?id=<?php echo $id;?>"  title="Rechazar compra">
                    <i class="fa fa-pencil-square"></i></a></td>
                </tr>
              <?php endforeach?>  
            </tbody>
        </table>
  </form>
 
        
        
