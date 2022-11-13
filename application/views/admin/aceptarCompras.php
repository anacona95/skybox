
						<?php foreach ($comprasAceptar as $aceptar) {
							
						} ?>

<div class="x_panel">
    <form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/aceptarComprass">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Aceptar compras</font></h2></div> 
                           <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cliente*</font></label>
                                 <div class="col-sm-4">
                                 <input type="hidden" class="form-control" name="id" id="id"  value="<?php echo $aceptar->id_compra ?>">    
                                <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->primer_nombre?>-<?php echo $aceptar->segundo_nombre?>-<?php echo $aceptar->apellidos?>" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Enlace / url*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $aceptar->enlace?>" disabled/>
                                </div>
                            </div>
                            <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Referencia*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="referencia" value="<?php echo $aceptar->referencia?>" disabled/>
                                 </div>
                            </div> 
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Talla</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="talla" value="<?php echo $aceptar->talla?>" disabled />
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Color</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control"  name="color" value="<?php echo $aceptar->color?>" disabled/>
                                 </div>
                            </div>
                                <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cantidad*</font></label>
                                 <div class="col-sm-4">
                                     <input type="text" class="form-control" required="required" name="cantidad" value="<?php echo $aceptar->cantidad?>" disabled />
                                 </div>
                            </div>
                          <div class="form-group">
                                 <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor compra</font></label>
                                 <div class="col-sm-4">
                                     <input type="number" class="form-control" required="required" name="valor"  />
                                 </div>
                            </div>
                                                    
                          <div class="panel-footer"  align="right">
                            <a href="<?php echo base_url()?>Admin/ComprasAlertas" class="btn btn-default"><i class="fa fa-times"></i><FONT FACE="helvetica"> Cancelar</font></a>
                            <button type="submit" class="btn btn-primary"><FONT FACE="helvetica"> Actualizar</font></button></div> 
                         </form>