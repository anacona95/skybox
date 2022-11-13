
						<?php foreach ($modificar_envio as $modificar) {
							
						} ?>

          <?php $seguro=$modificar->seguro ?> ;
         
<div class="x_panel">     
<form class="form-horizontal" method="post" action ="<?php echo base_url()?>user/updateEnvios">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                               
                              </div>
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Modificar Datos Envió</font></h2></div> 
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Articulo*</font></label>
                                 <div class="col-sm-4">
                                    <input type="hidden" class="form-control" name="id" id="id" placeholder="Nombre" value="<?php echo $modificar->articulo_id ?>">     
                                <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $modificar->nombre?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Tracking*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track"  value="<?php echo $modificar->id_track?>"/>
                                 </div>
                            </div> 
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha*</font></label>
                                 <div class="col-sm-4">
                                    <input type="date" class="form-control" name="fecha"  required="required" value="<?php echo $modificar->fecha?>" />
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Descripción</font></label>
                                 <div class="col-sm-4">
                                     <input class="form-control" name="descripcion" value="<?php echo $modificar->descripcion?>"/>
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Desea adquirir seguro de para el envió?</font></label>
                                 <div class="col-sm-4">
                                    <input type="radio" name="seguro" value="No" <?php if($seguro=="No"): echo "checked"; endif; ?> > No<br>
                                    <input type="radio" name="seguro" value="Si" <?php if($seguro=="Si"): echo "checked"; endif; ?>> Si<br>
                                 </div>
                            </div>
       
                            
                              <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>user" class="boton_personalizado2"><i class="fa fa-times"></i><FONT FACE="helvetica"> Cancelar</font></a>
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica">Actualizar</font></button></div> 
                        <div class="panel-footer"  align="right">
                            
                          
</form>
</div>               
   
                
                  