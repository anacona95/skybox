<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Información del artículo
            </h2>
        </div>
        <form class="form-horizontal" method="post" action="<?php echo base_url() ?>Admin/info[0]Envioss">
            <div class="panel-body ">
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Cliente:
                    </label>
                    <div class="col-sm-4">

                        <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $info[0]->primer_nombre ?>-<?php echo $info[0]->segundo_nombre ?>-<?php echo $info[0]->apellidos ?>"
                            disabled/>
                    </div>
                    <label class="col-sm-2 control-label">
                        Producto:
                    </label>
                    <div class="col-sm-4">
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $info[0]->articulo_id ?>">
                        <input type="text" class="form-control" required="required" name="nombre" value="<?php echo $info[0]->nombre ?>" disabled/>
                    </div>
                </div>
                <div class="form-group">

                    <label class="col-sm-2 control-label">
                        Tracking:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="id_track" value="<?php echo $info[0]->id_track ?>" disabled
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Peso:
                    </label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" required="required" name="peso" value="<?php echo $info[0]->peso ?>" disabled />
                    </div>
                </div>

                <div class="form-group">
                
                <label class="col-sm-2 control-label">
                        Transportadora:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" value="<?php echo $info[0]->transportadora ?>" disabled
                        />
                    </div>
                    <label class="col-sm-2 control-label">
                        Seguro:
                    </label>
                    <div class="col-sm-4">
                        <input class="form-control" name="descripcion" value="<?php echo $info[0]->seguro ?>" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Valor flete:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="valor" value="<?php echo $info[0]->valor ?>" disabled />
                    </div>
                    <label class="col-sm-2 control-label">
                        Valor paquete:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="valor" value="<?php echo $info[0]->valor_paquete ?>" disabled
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Fecha llegada:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="valor" value="<?php echo $info[0]->fecha ?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Descripcion:
                    </label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="descripcion" disabled><?php echo $info[0]->descripcion ?></textarea>
                    </div>
                </div>
                <div class="panel-heading">
                    <h2>
                        Direccion:
                    </h2>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        País:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="pais" required="required" value="<?php echo $info[0]->pais ?>" disabled />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Ciudad:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="ciudad" required="required" value="<?php echo $info[0]->ciudad ?>" disabled
                        />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Dirección:
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="direccion" required="required" value="<?php echo $info[0]->direccion ?>" disabled
                        />
                    </div>
                </div>
            </div>
            <div class="panel-footer" align="right">
                <a href="<?php echo base_url(['tracking']) ?>" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>