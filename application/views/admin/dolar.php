
						<?php foreach ($dolar as $aceptar) {
							
						} ?>

<div class="x_panel">
                            <?php if($this->session->flashdata('dolar')): ?>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('dolar') ?>
                            </div>
                            <?php endif;?>
    <form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/CambiarvalorDolar">
                            <div class="panel-body ">
                              
                            </div>
                              <div class="form-group">
                                
                              </div>
                         <div class="panel-heading"><h2><FONT FACE="helvetica">Dólar hoy</font></h2></div> 
                          <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor actual</font></label>
                                 <div class="col-sm-2">
                                  
                                     <input type="text" class="form-control" required="required" name="mostrar"  value="<?php echo $aceptar->valor?>" disabled/>
                                </div>
                            </div>
                           <div class="form-group">
                                <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Actualizar</font></label>
                                 <div class="col-sm-2">
                                 <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $aceptar->id ?>">    
                                 <input type="number" class="form-control" required="required" name="valor"   />
                                </div>
                            </div>
                            
                                                    
                          <div class="panel-footer"  align="right">
                            
                            <button type="submit" class="boton_personalizado"><FONT FACE="helvetica">Guardar</font></button></div> 
                         </form>