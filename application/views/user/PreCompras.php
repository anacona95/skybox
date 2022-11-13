<div class="panel-body ">
                              
                            </div>
                              

 
<div class="tab-content">
     
  
    <div class="x_panel">
        <?php if($this->session->flashdata('pre-alerta-compras')): ?>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('pre-alerta-compras') ?>
                            </div>
                            <?php endif;?> 
<?php if($this->session->flashdata('error-pre-alerta-compras')): ?>
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('error-pre-alerta-compras') ?>
                            </div>
                            <?php endif;?>
    <form class="form-horizontal" method="post" action ="<?php echo base_url()?>user/compras_proces">
                            
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Mis cotizaciones</font></h2></div> 
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Enlace / url*</font></label>
                                 <div class="col-sm-4">
                                     <textarea  class="form-control" required="required" name="enlace"></textarea>  
                                </div>
                            </div>
                            <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Referencia*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="referencia"  />
                                 </div>
                            </div> 
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Talla</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="talla"  />
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Color</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="color"  />
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cantidad*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="cantidad"  />
                                 </div>
                            </div>
                                                    
                          <div class="panel-footer"  align="right">
                            
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Enviar</font></button></div> 
                         </form>
  </div>
     
  
 <div class="x_panel">
 <div class="x_title">
        <h2><FONT FACE="helvetica">Mis compras</font></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table id="dataTable table-hover" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><FONT FACE="helvetica">Enlace</font></th>
                    <th><FONT FACE="helvetica">Referencia</font></th>
                    <th><FONT FACE="helvetica">Talla</font></th>
                    <th><FONT FACE="helvetica">Color</font></th>
                    <th><FONT FACE="helvetica">Cantidad</font></th>                  
                    <th><FONT FACE="helvetica">Estado</font></th>
                    <th><FONT FACE="helvetica">Modificar </font></th>
                    <th><FONT FACE="helvetica">Eliminar</font></th>
                    
                </tr>
            </thead>


            <tbody>
                <?php foreach ($alertasCompras as $key => $row) :
		$enlace=$row['enlace'];
		$referencia=$row['referencia'];
		$talla=$row['talla'];
		$color=$row['color'];
		$cantidad=$row['cantidad'];
                $tipo=$row['tipo'];
                $estado=$row['estado_compra'];
                $id=$row['id_compra'];
                
                 ?>
                <tr>
                    
                    <td><FONT FACE="helvetica"><?php echo $enlace; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $referencia; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $talla; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $color; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $cantidad; ?></font></td>                   
                    <td><FONT FACE="helvetica"><?php echo $estado; ?></font></td>
                    <td><a style="color:#0C030A" href="<?php echo base_url()?>modificar_compras?id=<?php echo $id;?>"  title="Modificar">
                    <i class="fa fa-pencil-square"></i></a></td>
                     <td><a style="color:#0C030A" href="<?php echo base_url()?>user/vistasEliminarCompra?id=<?php echo $id;?>"  title="Eliminar">
                    <i class="fa fa-pencil-square"></i></a></td>
                </tr>
              <?php endforeach?>  
            </tbody>
        </table>
  </div>
</div>


