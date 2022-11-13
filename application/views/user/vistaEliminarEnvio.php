
						<?php foreach ($eliminarEnvio as $eliminar) {
							
						} ?>
         
    <div class="x_panel">
     <form class="form-horizontal" method="post" action ="<?php echo base_url()?>user/EstadoEliminarEnvio">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Eliminar pre-alerta de flete</font></h2></div> 
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Articulo*</font></label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $eliminar->nombre?>" disabled/> 
                                </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Traking*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track" value="<?php echo $eliminar->id_track?>" disabled/>
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha*</font></label>
                                 <div class="col-sm-4">
                                    <input type="date" class="form-control" name="fecha"   value="<?php echo $eliminar->fecha?>" disabled/>
                                 </div>
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor paquete*</font></label>
                                 <div class="col-sm-2">
                                     <input type="number" class="form-control"  name="valor_paquete" value="<?php echo $eliminar->valor_paquete?>" disabled/>
                                 </div>  
                            </div>
                          <div class="form-group">
                              
                            <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Descripcion</font></label>
                                 <div class="col-sm-4">
                                     <textarea class="form-control" name="descripcion" disabled><?php echo $eliminar->descripcion?></textarea>
                                 </div>   
                            </div>
                             <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Seguro</font></label>
                                 <div class="col-sm-2">
                                     <input type="text" class="form-control" required="required"  value="<?php echo $eliminar->seguro?>" disabled/>
                                 </div>
                            </div>
                               
                                
                                                    
                           <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>user" class="boton_personalizado2"><i class="fa fa-times"></i><FONT FACE="helvetica"> Cancelar</font></a>
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Eliminar</font></button></div> 
                         </form>
  </div>
                
                  