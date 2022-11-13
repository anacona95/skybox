<!-- Modal -->
<div class="modal fade" id="myaddress" tabindex="-1" role="dialog" aria-labelledby="myaddress">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myaddress"><img src="/public/images/address.svg" alt="Mi direccion" width="25"> Mi dirección con Foxcarga</h4>
      </div>
      <div class="modal-body" style="color:#73879C;">
       <div class="container row">
        <div class="col-md-12" >
          <b><p>Esta es la dirección donde debes enviar tus compras en Estados Unidos:</p>
            <br>
            <p><?php echo $userdata['primer_nombre']." ".$userdata['apellidos']?> Fox</p>
            <p>7329 NW 56th St.</p>
            <p>Código Postal 33166</p>
            <p>Miami, Florida.</p>
            </b>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<!-- MODAL CUPON -->
<div class="modal fade" id="cupon" tabindex="-1" role="dialog" aria-labelledby="cupon">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="cupon"><img src="/public/images/cupon.svg" alt="Mi direccion" width="25"> Redime tu cupón</h4>
      </div>
      <form class="form-inline" method="post" action="/cupon-process">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-md-6">Nombre del cupón</label>
                <div class="col-md-6">
                  <input type="text" name="cupon" class="form-control"  placeholder="Ingrese el código" required="required">
                </div>
            </div>
          </div>    
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">
                        Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        Guardar</button>
                 </div>  
        </form>
    </div>
  </div>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>public/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>public/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="<?php echo base_url(); ?>public/nprogress/nprogress.js"></script>
<!-- jQuery custom content scroller -->
<script src="<?php echo base_url(); ?>public/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo base_url() ?>public/datatables-net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo base_url() ?>public/datatables-net-scroller/js/dataTables.scroller.min.js"></script>
<script src="<?php echo base_url() ?>public/autoNumeric/autoNumeric.min.js"></script>
<script src="<?php echo base_url() ?>public/jquery-validation-1.19.2/dist/jquery.validate.min.js"></script>
<script src="<?php echo base_url() ?>public/jquery-validation-1.19.2/dist/additional-methods.min.js"></script>
<script src="<?php echo base_url() ?>public/jquery-validation-1.19.2/dist/localization/messages_es.min.js"></script>
<script src="<?php echo base_url() ?>public/jquery-validation-1.19.2/dist/localization/methods_es_CL.min.js"></script>
<script src="<?php echo base_url(); ?>public/build/js/custom.js?v=<?= time() ?>"></script>

</body>

</html>