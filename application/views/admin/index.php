<div class="x_panel">
    <?php if($this->session->flashdata('alerta')): ?>
                            <div class="alert alert-info" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('alerta') ?>
                            </div>
                            <?php endif;?>
    <?php if($this->session->flashdata('error-prealerta')): ?>
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $this->session->flashdata('error-prealerta') ?>
                            </div>
                            <?php endif;?>
                            
    <div class="x_title">
        <h2><FONT FACE="helvetica">Pre-alertas</font></h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">
<ul class="nav nav-tabs">
   <li  class="active"><a style="color:#0C030A" href="<?php echo base_url()?>Admin"><FONT FACE="helvetica">Pre-alertas</font></a></li>
  <li ><a style="color:#0C030A" href="<?php echo base_url()?>Admin/estadosEnvios"><FONT FACE="helvetica">Estados</font></a></li>
  
</ul>
        <p></p>
       
        
 <form class="form-horizontal" method="post" action ="<?php echo base_url()?>Admin/estadosCambiarPrealerta">
     <p></p>
      
    <div class="container">
    <div  class="left" >
         
<SELECT  NAME="estados" SIZE="1"> 
   <OPTION  VALUE="Prealertado">Prealertado</OPTION> 
   <OPTION VALUE="En miami">En miami</OPTION> 
   <OPTION VALUE="En Cali">En Cali</OPTION>
   <OPTION VALUE="Disponible">Disponible</OPTION>
   <OPTION VALUE="Pendiente de pago">Pendiente de pago</OPTION> 
   <OPTION VALUE="En carretera">En carretera</OPTION> 
   <OPTION VALUE="En verificacion">En verificacion</OPTION> 
   <OPTION VALUE="En tus manos">En tus manos</OPTION> 
</SELECT> 
  <button type="submit" class="boton_personalizado"><FONT FACE="helvetica"> Enviar</font></button>
  <input type="checkbox" onclick="marcar(this);" /> <FONT FACE="helvetica">Marcar/Desmarcar Todos</font>
     </div>
    </div>
      <p></p>
     <div class="x_content">
       <table id="dataTable table-hover" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                    <th><FONT FACE="helvetica">Cliente</font></th>
                    <th><FONT FACE="helvetica">Articulo</font></th>
                    <th><FONT FACE="helvetica">Tracking</font></th>
                    <th><FONT FACE="helvetica">Fecha entrega a bodega</font></th>
                    <th><FONT FACE="helvetica">Estado</font></th>
                   
                    <th><FONT FACE="helvetica">Seguro</font></th>
                    <th><FONT FACE="helvetica">Valor paquete</font></th>
                    <th><FONT FACE="helvetica">Aceptar</font></th>
                    <th><FONT FACE="helvetica">Rechazar</font></th>
                    
                    
                </tr>
            </thead>


            <tbody>
                <?php foreach ($alertas as $key => $row) :
                $primer_nombre=$row['primer_nombre'];
                $segundo_nombre=$row['segundo_nombre'];
                $apellidos=$row['apellidos'];
                $articulo=$row['nombre'];
                $traking=$row['id_track'];
                $fecha=$row['fecha'];
                $estado=$row['estadoArticulo'];
                $Valorp=$row['valor_paquete'];
                $seguro=$row['seguro'];
                $descripcion=$row['descripcion'];
                $id=$row['articulo_id'];
                
                 ?>
                <tr>
                    <td><input type="checkbox" name="id[]" value="<?php echo $id; ?>"></td>
                    <td><FONT FACE="helvetica"><?php echo $primer_nombre; ?> <?php echo $segundo_nombre; ?> <?php echo $apellidos; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $articulo; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $traking; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $fecha; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $estado; ?></font></td>
                    
                    <td><FONT FACE="helvetica"><?php echo $seguro; ?></font></td>
                    <td><FONT FACE="helvetica"><?php echo $Valorp; ?></font></td>
                    <td ALIGN=center><a href="<?php echo base_url()?>admin/aceptarEnvios?id=<?php echo $id;?>"  title="Aceptar">
                    <i style="color:#0C030A" class="fa fa-pencil-square"></i></a></td>
                     <td ALIGN=center><a href="<?php echo base_url()?>admin/rechazarEnvios?id=<?php echo $id;?>"  title="Rechazar">
                    <i style="color:#0C030A" class="fa fa-pencil-square"></i></a></td>
                    
                     
                 
                    
                   
                </tr>
              <?php endforeach?>  
            </tbody>
        </table>
          </div>
</form>
