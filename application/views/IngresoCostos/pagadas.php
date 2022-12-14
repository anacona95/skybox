<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><i class="fa fa-check-square-o" aria-hidden="true"></i> Pagadas</h2>
        </div>

        <div class="panel-body">
            <?php if ($this->session->flashdata('msgOk')): ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-check-square fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgOk') ?>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('msgError')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>
          
            <ul class="nav nav-pills pull-right">
                <li role="presentation"><a href="<?php echo base_url(['ingreso-costos']) ?>"><i class="fa fa-usd fa-lg" aria-hidden="true"></i> Pendientes por pagar</a></li>
            </ul>
            <ul class="nav nav-pills pull-right">
                    <li role="presentation">
                        <a href="#" title="Cree un nuevo costo" data-toggle="modal" data-target="#modalCreate">
                        <i class="fa fa-plus fa-lg" aria-hidden="true"></i>&nbsp;Nuevo</a>
                    </li>
            </ul>
            <br>
            <div class="form-group x_content">
                <form class="form-horizontal" action="/ingreso-costos/pagadas" method="GET">
                    <div class="col-md-4 col-md-offset-8">
                        <div class="input-group">
                            <input type="text" placeholder="Buscar..." name="q" autocomplete="off" class="form-control" value="<?php if (isset($_GET['q'])): echo $_GET['q'];endif;?>">
                            <span class="input-group-btn">
                                <?php if(isset($_GET['q'])):?>
                                    <a href="/ingreso-costos/pagadas" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                                <?php else:?>
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <?php endif;?>

                            </span>
                        </div><!-- /input-group -->
                    </div>
                </form>
            </div>
            <div class="form-group col-xs-12">
                <table class="dataTable table-hover table table-striped table-bordered table-condensed"
                    data-page-length='100'>
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>USD</th>
                            <th>TRM</th>
                            <th>Libras</th>
                            <th>Val. unit.</th>
                            <th>Fecha de creaci??n</th>
                            <th>Fecha pago</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Factura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($costos as $costo): ?>
                        <tr>
                            <td>
                                <?= $costo->concepto?>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $badges_class[$costo->tipo]?>"><?= $costo_model->tipos[$costo->tipo]?></span>
                            </td>
                            <td>
                                <?= "$ " . number_format($costo->valor,0, '', '.')?>
                            </td>
                            <td>
                                <?= $costo->valor_usd? "$ ".$costo->valor_usd : "<span class='badge badge-info'>No</span>"?>
                            </td>
                            <td>
                            <?= $costo->trm? "$ ". number_format($costo->trm,0, '', '.') : "<span class='badge badge-info'>No</span>"?>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $costo->libras == NULL? "badge-info":"badge-success" ?>"><?= $costo->libras == NULL? "No": $costo->libras; ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($costo->libras == NULL):?>
                                    <span class="badge badge-info">No</span>
                                <?php else:?>
                                    <?= "$ " . number_format($costo->valor/$costo->libras,0, '', '.')?>
                                <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?= date('d/m/Y',$costo->created_at)?>
                            </td>
                            <td class="text-center">
                                <?= date('d/m/Y',$costo->fecha_pago);?>
                            </td>
                            <td class="text-center">
                                <?= $costo->nombre?>
                            </td>
                            <td class="text-center">
                                 <?= $costo->estado == 0? "<span class='badge badge-success'>".$costo_model->estados[$costo->estado]."</span>":"<span class='badge badge-info'>".$costo_model->estados[$costo->estado]."</span> <a  href='#' data-id='$costo->id' onclick='showModalFacturaCostos(this)'><i class='fa fa-upload fa-lg' aria-hidden='true'></i></a>"?>
                            </td>
                            
                           
                            <td class="text-center">
                                <?=$costo->factura == NULL? '<span class="badge badge-info">No</span>':"<a  href='$costo->factura' download><i class='fa fa-download fa-lg' aria-hidden='true'></i></a>";?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <?=$this->pagination->create_links()?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-usd" aria-hidden="true"></i> Nuevo costo</h4>
            </div>
            <form autocomplete="off" enctype="multipart/form-data" action="<?php echo base_url(['IngresoCostos','costoProcess'])?>" method="POST" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre" class="control-label col-xs-4">Concepto</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" required name="data[concepto]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="control-label col-xs-4">Tipo</label>
                        <div class="col-xs-4">
                            <select class="form-control" name="data[tipo]" id="tipo" required>
                                <option value="" disabled selected>Seleccione tipo</option>
                                <option value="0">Flete</option>
                                <option value="2">Domicilio</option>
                                <option value="3">Env??o nacional</option>
                                <option value="1">Otro</option>
                            </select>
                                    <script>
                                        $(document).ready(function(){
                                            $("#tipo").change(function(){
                                                var value = $(this).val();
                                                var compare = "0";
                                                if(value == compare){
                                                    $("#libras-group").removeClass('hidden');
                                                    $("#libras").attr("required");
                                                    $("#usd-group").removeClass('hidden');
                                                    $("#trm-group").removeClass('hidden');
                                                }else{
                                                    $("#libras-group").addClass('hidden');
                                                    $("#libras").removeAttr("required");
                                                    $("#usd-group").addClass('hidden');
                                                    $("#trm-group").addClass('hidden');
                                                }
                                            });
                                        });
                                    </script>
                        </div>
                    </div>
                    <div id="libras-group"  class="form-group hidden">
                        <label id="label" class="control-label col-xs-4 ">Libras</label>
                        <div class="col-xs-4">
                            <input type="numeric" id="libras" class="form-control" name="data[libras]">
                        </div>
                    </div>
                    <div id="usd-group" class="form-group hidden">
                        <label class="control-label col-xs-4 ">USD</label>
                        <div class="col-xs-4">
                            <input type="text" id="usd" class="form-control usd" name="data[usd]" onblur="calcularUSD()">
                        </div>
                    </div>
                    <div id="trm-group" class="form-group hidden">
                        <label class="control-label col-xs-4 ">TRM</label>
                        <div class="col-xs-4">
                            <input type="text" id="trm" class="form-control" name="data[trm]" value="<?= $_SESSION['trm']['hoy'] ?>" onblur="calcularUSD()">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text" class="control-label col-xs-4">Valor</label>
                        <div class="col-xs-4">
                            <input type="numeric" id="valor-costo" class="form-control money" required name="data[valor]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label  class="col-xs-4 col-xs-offset-4">
                                <input id="estadoCosto" type="checkbox" name="data[estado]" value="0" checked> <b>Pagada</b>
                            </label>
                            <script>
                                        $(document).ready(function(){
                                            $("#estadoCosto").change(function(){
                                                if($("#estadoCosto").is(':checked')){
                                                    $("#file").removeClass('hidden');
                                                    $("#fileFactura").attr("required");
                                                }else{
                                                    $("#file").addClass('hidden');
                                                    $("#fileFactura").removeAttr("required");

                                                }
                                            });
                                        });
                                    </script>
                        </div>
                    </div>
                    <div class="form-group" id="file">
                        <label  class="control-label col-xs-4">Adjunte factura</label>
                        <div class="col-xs-4">
                            <input id="fileFactura" type="file" name="factura" required>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
