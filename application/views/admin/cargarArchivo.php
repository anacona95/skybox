<div class="x_panel">
    <div class="x_content">
        <div class=" panel panel-default ">
            <div class="panel-heading">
                <h2>
                    Carga y descarga de archivos
                </h2>
            </div>
            <br>
            <div class="panel-body">
                <?php if ($this->session->flashdata('archivo')): ?>
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('archivo') ?>
                </div>
                <?php endif;?>
                <?php if ($this->session->flashdata('error-archivo')): ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $this->session->flashdata('error-archivo') ?>
                </div>
                <?php endif;?>
                <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>admin/prealertaMasiva" method="POST" enctype="multipart/form-data" class="form-vertical col-md-9">
                        <legend>Carga de archivo prealertas</legend>
                        <div class="form-group">
                            <div class="row">
                                <label for="file1" class="control-label col-md-3">Archivo:</label>
                                <div class="col-md-6">
                                    <input id="file1" type="file" name="fileImagen">
                                </div>
                            </div>
                        </div>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-3">
                                    <input class="btn btn-primary" type="submit" value="Cargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>admin/subirArchivo" method="POST" enctype="multipart/form-data" class="form-vertical col-md-9">
                        <legend>Carga de archivo Recibido y viajando</legend>
                        <div class="form-group">
                            <div class="row">
                                <label for="file1" class="control-label col-md-3">Archivo:</label>
                                <div class="col-md-6">
                                    <input id="file1" type="file" name="fileImagen">
                                </div>
                            </div>
                        </div>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-3">
                                    <input class="btn btn-primary" type="submit" value="Cargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>admin/loadAduanas" method="POST" enctype="multipart/form-data" class="form-vertical col-md-9">
                        <legend>Cargar archivo de vuelo</legend>
                        <div class="form-group">
                            <div class="row">
                                <label for="aduanas" class="control-label col-md-3">Archivo:</label>
                                <div class="col-md-6">
                                    <input id="aduanas" type="file" name="file">
                                </div>
                            </div>
                        </div>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-3">
                                    <input class="btn btn-primary" type="submit" value="Cargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br> -->
                <br>
                <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>admin/prealertasCsv" method="POST" enctype="multipart/form-data" class="form-vertical col-md-9">
                        <legend>Descargar prealertas CSV</legend>
                        <div class="form-gorup">
                            <div class="row">
                                <label class="col-md-3 control-label">Fecha inicio:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control prealertaCsv" placeholder="dd/mm/aaaa" required="required" name="fecha1" />
                                </div>
                                <label class="col-sm-3 control-label">Fecha fin:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control prealertaCsv" placeholder="dd/mm/aaaa" required="required" name="fecha2" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-9">
                                    <input class="btn btn-primary" type="submit" value="Descargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>admin/Excel" method="POST" enctype="multipart/form-data" class="form-vertical col-md-9">
                        <legend>Descargar reporte de usuarios</legend>
                        <div class="form-gorup">
                            <div class="row">
                                <label class="col-md-3 control-label">Fecha inicio:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control calendar" placeholder="dd/mm/aaaa" required="required" name="fecha1" />
                                </div>
                                <label class="col-sm-3 control-label">Fecha fin:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control calendar" placeholder="dd/mm/aaaa" required="required" name="fecha2" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-9">
                                    <input class="btn btn-primary" type="submit" value="Descargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="row">
                    <form autocomplete="off" action="<?php echo base_url(); ?>Admin/reporteFacturacion" method="POST" class="form-vertical col-md-9">
                        <legend>Descargar reporte de facturación</legend>
                        <div class="form-gorup">
                            <div class="row">
                                <label class="col-md-3 control-label">Fecha inicio:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control calendar" placeholder="dd/mm/aaaa" required="required" name="fecha1" />
                                </div>
                                <label class="col-sm-3 control-label">Fecha fin:*</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control calendar" placeholder="dd/mm/aaaa" required="required" name="fecha2" />
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-gorup">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-9">
                                    <input class="btn btn-primary" type="submit" value="Descargar">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>