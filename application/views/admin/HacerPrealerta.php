<?php foreach ($alert as $pre) {

}?>
<div class="x_panel">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                Crear prealerta
            </h2>
        </div>
        <?php if ($this->session->flashdata('cliente')): ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('cliente') ?>
            </div>
            <?php endif;?>
            <?php if ($this->session->flashdata('error-cliente')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php echo $this->session->flashdata('error-cliente') ?>
            </div>
            <?php endif;?>
        <form class="form-horizontal" method="post" action="<?php echo base_url() ?>Admin/GuardarEnvio">
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Cliente
                    </label>
                    <div class="col-sm-4">
                        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $pre->id ?>">
                        <input type="text" class="form-control" required="required" name="nombres" value="<?php echo $pre->primer_nombre ?>-<?php echo $pre->segundo_nombre ?>-<?php echo $pre->apellidos ?>"
                            disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Documento
                    </label>
                    <div class="col-sm-4">

                        <input type="text" class="form-control" required="required" name="documento" value="<?php echo $pre->num_documento ?>" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Articulo*
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="nombre" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Tracking*
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" required="required" name="id_track" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Fecha*
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control calendar" name="fecha" required="required" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Valor paquete en dolares*
                    </label>
                    <div class="col-sm-4">
                        <input type="number" class="form-control" required="required" name="valor_paquete" id="valor" placeholder="sin puntos, ni comas."
                            onclick="paquete()" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Descripción
                    </label>
                    <div class="col-sm-4">
                        <textarea class="form-control" name="descripcion" placeholder="Dato adicional del paquete"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        Desea adquirir seguro de para el envió?
                    </label>
                    <div class="col-sm-4">
                        <input type="radio" name="seguro" value="no" checked> No
                        <br>
                        <input type="radio" name="seguro" value="si"> Si
                        <br>
                    </div>
                </div>
            </div>
            <div class="panel-footer" align="right">
                <a href="<?php echo base_url() ?>informacion-clientes" class="btn btn-default">
                    <i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-bell"></i>&nbsp;Crear
                </button>
            </div>
        </form>
    </div>
</div>
