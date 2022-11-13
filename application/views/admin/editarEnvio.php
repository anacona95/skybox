<?php foreach ($envioAceptar as $aceptar) {
							
						} ?>
 <?php $seguro=$aceptar->seguro ?> ;
 <div class="x_panel">
<form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/editarEnvioss">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
    <div class="panel-heading"><h2><FONT FACE="helvetica">Datos de prealerta</font></h2></div> 
                         <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cliente*</font></label>
                                 <div class="col-sm-4">
                                    
                                <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->primer_nombre?>&nbsp;<?php echo $aceptar->segundo_nombre?>&nbsp;<?php echo $aceptar->apellidos?>" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Producto*</font></label>
                                 <div class="col-sm-4">
                                    <input type="hidden" class="form-control" name="id" id="id"  value="<?php echo $aceptar->articulo_id ?>">     
                                    <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->nombre?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Tracking*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track"  value="<?php echo $aceptar->id_track?>"/>
                                 </div>
                            </div> 
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha estimada de entrega a bodega*</font></label>
                                 <div class="col-sm-4">
                                    <input type="date" class="form-control" name="fecha"  required="required" value="<?php echo $aceptar->fecha?>"  />
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor paquete*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="valor_paquete"  value="<?php echo $aceptar->valor_paquete?>"/>
                                 </div>
                            </div> 
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Descripción</font></label>
                                 <div class="col-sm-4">
                                   
                                     <textarea class="form-control" name="descripcion" ><?php echo $aceptar->descripcion?></textarea>
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Seguro</font></label>
                                 <div class="col-sm-4">
                                     <input type="radio" name="seguro" value="No" <?php if($seguro=="No"): echo "checked"; endif; ?> > No<br>
                                    <input type="radio" name="seguro" value="Si" <?php if($seguro=="Si"): echo "checked"; endif; ?>> Si<br>
                                 </div>
                            </div>
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor flete</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="valor" value="<?php echo $aceptar->valor?>" />
                                 </div>
                            </div>
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Peso</font></label>
                                 <div class="col-sm-4">
                                     <input type="number" class="form-control" name="peso" value="<?php echo $aceptar->peso?>"  />
                                 </div>
                            </div>
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha de entrega</font></label>
                                 <div class="col-sm-4">
                                    <input type="date" class="form-control" name="fecha_entrega"   value="<?php echo $aceptar->fecha_entrega?>"  />
                                 </div>
                            </div>
       
                            
                              <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>Admin/estadosEnvios" class="boton_personalizado2"><i class="fa fa-times"></i><FONT FACE="helvetica"> Cancelar</font></a>
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Actualizar</font></button></div> 
                        <div class="panel-footer"  align="right">
</form>
 </div>               
   
                
               