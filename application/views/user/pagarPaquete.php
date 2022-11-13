<div class="x_panel"  >
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Pagar artículo</h2>
        </div>
        <form class="form-vertical" action="https://gateway2.tucompra.com.co/tc/app/inputs/compra.jsp" method="post">
            <div class="panel-body">
            <?php if ($this->session->flashdata('msgError')): ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('msgError') ?>
            </div>
            <?php endif;?>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Nombre:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo $objArticulo->nombre ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Tracking:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo $objArticulo->id_track ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Fecha:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo $objArticulo->fecha ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Descripción:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo $objArticulo->descripcion ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Valor del paquete:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo "$ " . number_format((int)$objArticulo->valor_paquete, 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Valor del recargo:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo "$ " . number_format((int)$valorRecargo, 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nombre" class="col-md-3">Total a pagar:</label>
                        <div class="col-md-4">
                            <span class="form-control"><?php echo "$ " . number_format((int)$valorTotal, 2, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                    <input type="hidden" name="usuario" value="l3c4fo1758dv7myq">
                    <input type="hidden" name="factura" value="<?php echo $objArticulo->articulo_id?>">
                    <input type="hidden" name="valor" value="<?php echo (int)$valorTotal?>">
                    <input type="hidden" name="documentoComprador" value="<?php echo $objArticulo->cliente->num_documento?>">
                    <input type="hidden" name="tipoDocumento" value="CC">
                    <input type="hidden" name="nombreComprador" value="<?php echo $objArticulo->cliente->primer_nombre."&nbsp;".$objArticulo->cliente->segundo_nombre?>">
                    <input type="hidden" name="apellidoComprador" value="<?php echo $objArticulo->cliente->apellidos?>">
                    <input type="hidden" name="correoComprador" value="<?php echo $objArticulo->cliente->email?>">
                    <input type="hidden" name="celularComprador" value="<?php echo $objArticulo->cliente->telefono?>">
                    <input type="hidden" name="direccionComprador" value="<?php echo $objArticulo->cliente->direccion?>">
                    <input type="hidden" name="ciudadComprador" value="<?php echo $objArticulo->cliente->ciudad?>">
                    <input type="hidden" name="paisComprador" value="<?php echo $objArticulo->cliente->pais?>">
                    <input type="hidden" name="descripcionFactura" value="Pago de articulos micasillero">
                    <input type="hidden" name="tokenSeguridad" value="<?php echo md5("059a2784f78647a3a91864d536b0d690".date("h:i"))?>">
                    <a href="<?php echo base_url(['user', 'pagos']) ?>" class="btn btn-default">Cancelar</a>
                    <input type="submit" class="btn btn-primary" value="Continuar">
            </div>
        </form>
    </div>
</div>
