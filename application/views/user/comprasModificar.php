<?php foreach ($modificar_compras as $modificar) {
							
						} ?>

<form class="form-horizontal" method="post" action ="<?php echo base_url()?>user/updateCompras">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
                         <div class="panel-heading"><h3><FONT FACE="helvetica">Compras modificar</font></h3></div> 
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Enlace / url*</font></label>
                                 <div class="col-sm-4">
                                    
                                     <input type="hidden" class="form-control" name="id" id="id"  value="<?php echo $modificar->id_compra ?>">     
                                     <input type="text" class="form-control" required="required" name="enlace"  value="<?php echo $modificar->enlace?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Referencia*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="referencia"  value="<?php echo $modificar->referencia?>"/>
                                 </div>
                            </div> 
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Talla*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="talla"  value="<?php echo $modificar->talla?>"/>
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Color*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="color"  value="<?php echo $modificar->color?>"/>
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cantidad*</font></label>
                                 <div class="col-sm-4">
                                      <input type="text" class="form-control" required="required" name="cantidad"  value="<?php echo $modificar->cantidad?>"/>
                                 </div>
                            </div>
                                                    
                          <div class="panel-footer"  align="right">
                            
                             <a href="<?php echo base_url()?>precompras" class="boton_personalizado2"><i class="fa fa-times"></i> <FONT FACE="helvetica">Cancelar</font></a>
                            <button type="submit" class="boton_personalizado"> <FONT FACE="helvetica">Actualizar</font></button></div> 
                         </form>
                
   
                
                  