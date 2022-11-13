
						<?php foreach ($eliminarCompra as $eliminar) {
							
						} ?>
     <form id="demo-form2" method="post" class="form-horizontal form-label-left" action="<?php echo base_url() ?>user/EstadoEliminarCompra">
                            
                
               
                           <div> 
                               
               
                                    <h1><img src="<?php echo base_url()?>public/images/logo.png"></h1>
                            </div>
                           <div class="panel-heading"><h2><FONT FACE="helvetica">Desea eliminar esta pre-alerta de compra?</font></h2></div> 
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Enlace:</font></label>
                                 <div class="col-sm-4">
                                     <textarea class="form-control" name="descripcion" disabled><?php echo $eliminar->enlace?></textarea>
                                    
                                    <input type="hidden" class="form-control" name="id" id="id"  value="<?php echo $eliminar->id_compra ?>">
                                 </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Referencia:</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track" value="<?php echo $eliminar->referencia?>" disabled/>
                                 </div>
                            </div>
                           <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Talla:</font></label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $eliminar->talla?>" disabled/> 
                                    
                                 </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Color:</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track" value="<?php echo $eliminar->color?>" disabled/>
                                 </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cantidad:</font></label>
                                 <div class="col-sm-4">
                                    <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $eliminar->cantidad?>" disabled/> 
                                    
                                 </div>
                                
                            </div>

                            <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>precompras" class="boton_personalizado2"><i class="fa fa-times"></i><FONT FACE="helvetica"> Cancelar</font></a>
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Eliminar</font></button></div> 
      
                        </form>
                