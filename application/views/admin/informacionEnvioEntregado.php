
						<?php foreach ($infoEntregado as $aceptar) {
							
						} ?>
<div class="x_panel">

<form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/aceptarEnvioss">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Información articulo entregado</font></h2></div> 
                         <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cliente*</font></label>
                                 <div class="col-sm-4">
                                    
                                <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->primer_nombre?>-<?php echo $aceptar->segundo_nombre?>-<?php echo $aceptar->apellidos?>" disabled/>
                                </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Producto*</font></label>
                                 <div class="col-sm-4">
                                    <input type="hidden" class="form-control" name="id" id="id"  value="<?php echo $aceptar->articulo_id ?>">     
                                    <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->nombre?>" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Tracking*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="id_track"  value="<?php echo $aceptar->id_track?>"disabled />
                                 </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Peso</font></label>
                                 <div class="col-sm-4">
                                     <input type="number" class="form-control" required="required" name="peso" value="<?php echo $aceptar->peso?>" disabled />
                                 </div>
                            </div>
                           
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha de entraga*</font></label>
                                 <div class="col-sm-4">
                                    <input type="date" class="form-control" name="fecha"  required="required" value="<?php echo $aceptar->fecha_entrega?>" disabled />
                                 </div>
                                <label  class="col-sm-2 control-label"><FONT FACE="spinnaker">Seguro</font></label>
                                 <div class="col-sm-4">
                                     <input class="form-control" name="descripcion" value="<?php echo $aceptar->seguro?>" disabled/>
                                 </div> 
                            </div>
                         
                         
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor flete</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="valor" value="<?php echo $aceptar->valor?>" disabled />
                                 </div>
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor paquete</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="valor" value="<?php echo $aceptar->valor_paquete?>" disabled />
                                 </div> 
                            </div>
                          <div class="form-group">
                                 
                               <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Descripcion</font></label>
                                 <div class="col-sm-4">
                                     <textarea class="form-control" name="descripcion" disabled><?php echo $aceptar->descripcion?></textarea>
                                     
                                 </div> 
                            </div>
                         
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Direccion</font></h2></div>
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">País</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" name="pais"  required="required" value="<?php echo $aceptar->pais?>" disabled />
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Ciudad</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" name="ciudad"  required="required" value="<?php echo $aceptar->ciudad?>" disabled />
                                 </div>
                            </div>
                         <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Dirección</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" name="direccion"  required="required" value="<?php echo $aceptar->direccion?>" disabled />
                                 </div>
                            </div>
                         
       
                            
                              <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>Admin/articulosEntregados" class="boton_personalizado2"><i class="fa fa-arrow-left"></i><FONT FACE="helvetica"> Atras</font></a>

                        <div class="panel-footer"  align="right">
</form>
  </div>               
