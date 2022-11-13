
<?php foreach ($envioRechazar as $rechazar) {
    
}
?>
<?php $seguro = $rechazar->seguro ?> ;

<form class="form-horizontal" method="post" action ="<?php echo base_url() ?>Admin/rechazarEnvioss">
    <div class="panel-body ">

    </div>
    <div class="form-group">

    </div>
    <div class="panel-heading"><h2><FONT FACE="helvetica">Rechazar prealerta flete</font></h2></div> 
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Cliente*</font></label>
        <div class="col-sm-4">

            <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $rechazar->primer_nombre ?>-<?php echo $rechazar->segundo_nombre ?>-<?php echo $rechazar->apellidos ?>" disabled/>
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Producto*</font></label>
        <div class="col-sm-4">
            <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $rechazar->articulo_id ?>">     
            <input type="text" class="form-control" required="required" name="nombre"  value="<?php echo $rechazar->nombre ?>" disabled />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Tracking*</font></label>
        <div class="col-sm-4">
            <input type="text" class="form-control" required="required" name="id_track"  value="<?php echo $rechazar->id_track ?>" disabled/>
        </div>
    </div> 
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Fecha estimada de entrega a bodega*</font></label>
        <div class="col-sm-4">
            <input type="date" class="form-control" name="fecha"  required="required" value="<?php echo $rechazar->fecha ?>" disabled />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Valor paquete*</font></label>
        <div class="col-sm-4">
            <input type="text" class="form-control" required="required" name="valor_paquete"  value="<?php echo $rechazar->valor_paquete ?>" disabled/>
        </div>
    </div> 
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Descripción</font></label>
        <div class="col-sm-4">
            <input class="form-control" name="descripcion" value="<?php echo $rechazar->descripcion ?>" disabled />
        </div>
    </div>
    <div class="form-group">
        <label  class="col-sm-2 control-label"><FONT FACE="helvetica">Seguro</font></label>
        <div class="col-sm-4">
            <input type="radio" name="seguro" value="No" <?php if ($seguro == "No"): echo "checked";
endif; ?> disabled> No<br>
            <input type="radio" name="seguro" value="Si" <?php if ($seguro == "Si"): echo "checked";
endif; ?> disabled> Si<br>
        </div>
    </div>
    <div class="panel-footer"  align="right">
        <a href="<?php echo base_url() ?>Admin" class="boton_personalizado2"><FONT FACE="helvetica"> Cancelar</font></a>
        <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Rechazar</font></button></div> 
    <div class="panel-footer"  align="right">
</form>
