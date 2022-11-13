<?php

class OrdenesAdmin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->validateAccess()) {
            redirect('AdmLogin/close');
        }
        if (!$this->validateRole(1) && !$this->validateRole(2)) {
            $this->session->set_flashdata('msgError', 'No tiene permiso para acceder a la funcionalidad.');
            redirect(base_url(['cuenta-administrador']));
        }
        $this->load->GstClass('GstAdmin');
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('GstDashboard');
        $this->load->GstClass('ConfigEmail');
        $this->load->GstClass('GstApi');

    }

    public function ordenesLst()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['ordenes'] = $this->GstOrdenes->getOrdenesLstAdmin();
        $data['enAprobacion'] = $this->GstOrdenes->getOrdenesAprobacion();
        $data['total_pendiente'] = $this->GstDashboard->getCartera() - $this->GstDashboard->getabonado();
        $data['anuladas'] = $this->GstOrdenes->getOrdenesAnuladas();
        $data['total_pendiente'] = $this->GstDashboard->getCartera() - $this->GstDashboard->getabonado();

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/ordenesLst', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function ordenesPagadasLst($id = null)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];

        $q = null;
        if (isset($_GET['q'])) {
            $q = trim($_GET['q']);
        }

        //pagination
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url('ordenes-de-compra/pagadas'),
            'per_page' => 100,
            'total_rows' => $this->GstOrdenes->rowsOrdenesPagadas($q),
        ];

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tagl_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['first_link'] = 'Primero';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config); // model function

        $data['pagadas'] = $this->GstOrdenes->getOrdenesPagadas($config['per_page'], $this->uri->segment(3), $q);

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/ordenesPagadasLst', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function verificar($id)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);
        $data['comprobante'] = $data['orden']->comprobantes->last();
        if (!$data['comprobante']) {
            $data['comprobante'] = null;
        }
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/verificar', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function aprobarOrden()
    {
        $this->GstOrdenes->unsetAprobacion();
        $this->load->library('pdfgenerator');
        $datos['userdata'] = $this->session->userdata('admin');
        $id = $this->input->post('orden_id');
        $sin_domicilio = $this->input->post('sin_domicilio');

        if ($sin_domicilio == 1) {
            $this->GstOrdenes->delDomicilio($id);
        }

        $datos['orden'] = $this->GstOrdenes->getOrden($id);
        $cliente_id = $datos['orden']->user_id;
        if (!$this->GstOrdenes->aprobarOrden($id)) {
            $this->session->set_flashdata('msgError', 'No se pudo actualizar la orden de compra.');
            redirect(['ordenes-de-compra', 'verificar', $id]);
        }
        $data["libras"] = 0;
        $data['count'] = 0;
        $data['objFactura'] = $this->GstOrdenes->newFactura($id);

        foreach ($data['objFactura']->orden->articulos as $row) {
            $data["libras"] = $data["libras"] + $row->articulo->peso;
        }

        $html = $this->load->view('ordenes/pdfTemplateFactura', $data, true);

        $pdf_content = $this->pdfgenerator->generate($html, null, false);

        if (!file_exists("./uploads/facturas/$cliente_id/")) {
            mkdir("./uploads/facturas/$cliente_id/", 0755, true);
        }

        $nombre = $data['objFactura']->nombre;
        file_put_contents("./uploads/facturas/$cliente_id/$nombre", $pdf_content);

        $msg = $this->load->view('ordenesAdmin/emailConfirm', $datos, true);

        $this->ConfigEmail->to($datos['orden']->cliente->email);
        $this->ConfigEmail->subject('Pago recibido');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->attach("./" . $data['objFactura']->path . $data['objFactura']->nombre);
        
        if (!$this->ConfigEmail->send()) {
            $this->session->set_flashdata('msgError', 'No se pudo enviar el correo, por favor inténtalo de nuevo.');
            redirect(['ordenes-de-compra', 'verificar', $id]);
        }

        $this->session->set_flashdata('msgOk', 'La orden de compra ha sido validada con éxito.');

        if($datos['orden']->cliente->device!=null && $datos['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Pago recibido de tu orden #".$datos['orden']->factura,$datos['orden']->cliente->device);
        }
        
        redirect(['ordenes-de-compra']);
    }

    public function rechazar($id)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/rechazar', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function rechazarOrden()
    {
        $datos['userdata'] = $this->session->userdata('admin');
        $id = $this->input->post('orden_id');
        $datos['motivo'] = $this->input->post('motivo');
        $datos['orden'] = $this->GstOrdenes->getOrden($id);
        if (!$this->GstOrdenes->rechazarOrden($id)) {
            $this->session->set_flashdata('msgError', 'No se pudo actualizar la orden de compra.');
            redirect(['ordenes-de-compra', 'rechazar', $id]);
        }
        $msg = $this->load->view('ordenesAdmin/emailRechazo', $datos, true);

        $this->ConfigEmail->to($datos['orden']->cliente->email);
        $this->ConfigEmail->subject('Pago rechazado');
        $this->ConfigEmail->message($msg);
        if (!$this->ConfigEmail->send()) {
            $this->session->set_flashdata('msgError', 'No se pudo enviar el correo, por favor inténtalo de nuevo.');
            redirect(['ordenes-de-compra', 'rechazar', $id]);
        }
        $this->GstOrdenes->unsetAprobacion();
        
        if($datos['orden']->cliente->device!=null && $datos['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Pago rechazado de tu orden #".$datos['orden']->factura,$datos['orden']->cliente->device);
        }

        $this->session->set_flashdata('msgOk', 'La orden de compra ha sido rechazada con éxito.');
        redirect(['ordenes-de-compra']);
    }

    public function verOrden($id)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);
        $data['comprobante'] = $data['orden']->comprobantes->last();
        $pagosTc = $data['orden']->pagosTc;
        $data['pagosTc'] = null;
        if (!$data['comprobante']) {
            $data['comprobante'] = null;
        }
        if (count($pagosTc) > 0) {
            $data['pagosTc'] = $pagosTc;
        }
        
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/verOrden', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function agregarPaquetes()
    {
        $orden_id = $this->input->post('orden');
        $flete_nuevo = $this->input->post('flete');
        $articulos = $this->input->post('articulos');
        $objOrden = $this->GstOrdenes->getOrden($orden_id);

        $datos['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $datos['userdata']['nombre'] = $nombre[0];
        if (!$this->GstOrdenes->agregarPaquetes($orden_id, $objOrden->user_id, $flete_nuevo, $articulos)) {
            $this->session->set_flashdata('msgError', 'No se pudo agregar los paquetes a la orden.');
            redirect(['bandeja-de-salida']);
        }
        $datos['orden'] = $this->GstOrdenes->getOrden($orden_id);
        $msg = $this->load->view('ordenesAdmin/emailAgregarPaquetes', $datos, true);

        $this->ConfigEmail->to($datos['orden']->cliente->email);
        $this->ConfigEmail->subject('Hemos adicionado paquetes a tu orden');
        $this->ConfigEmail->message($msg);
        if (!$this->ConfigEmail->send()) {
            $this->session->set_flashdata('msgError', 'No se pudo enviar el correo, por favor inténtalo de nuevo.');
            redirect(['bandeja-de-salida']);
        }
        $this->session->set_flashdata('msgOk', 'Los paquetes han sido agregados a la orden con éxito.');
        
        if($datos['orden']->cliente->device!=null && $datos['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Hemos adicionado paquetes a tu orden #".$datos['orden']->factura,$datos['orden']->cliente->device);
        }
        
        redirect(['bandeja-de-salida']);
    }

    public function pagarProcess()
    {
        $id = $this->input->post('orden_id');
        $sin_domicilio = $this->input->post('sin_domicilio');
        $sin_bodegaje = $this->input->post('sin_bodegaje');
        $observaciones = trim($this->input->post('observaciones'));

        $this->load->library('pdfgenerator');
        $datos['userdata'] = $this->session->userdata('admin');
        $comprobante = $_FILES['comprobante'];
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','jfif','JFIF'];
        $tipo = explode('.', $comprobante['name']);

        $datos['orden'] = $this->GstOrdenes->getOrden($id);
        $cliente_id = $datos['orden']->user_id;

        if ($comprobante['size'] == 0 || !$comprobante) {
            $this->session->set_flashdata('msgError', 'Por favor adjunte una imagen con un tamaño menor a 2 Megabytes.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }

        if (!in_array($tipo[1], $types)) {
            $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }
        if (!$observaciones) {
            $this->session->set_flashdata('msgError', 'El campo observaciones es obligatorio.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }

        if (!$datos['orden']) {
            $this->session->set_flashdata('msgError', 'La orden no se puede pagar manualmente porque no esta habilitada para pago.');
            redirect(['ordenes-de-compra']);
        }

        /*if($datos['orden']->estado != 0 && $data['orden']->estado != 4){
        $this->session->set_flashdata('msgError', 'La orden no se puede pagar manualmente porque no esta habilitada para pago.');
        redirect(['ordenes-de-compra']);
        }*/

        $nombre_img = $this->GstOrdenes->uploadComprobante($cliente_id);
        if (!$nombre_img) {
            $this->session->set_flashdata('msgError', 'No se pudo subir el comprobante, por favor inténtalo de nuevo.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }

        if ($sin_domicilio == 1) {
            $this->GstOrdenes->delDomicilio($id);
        }

        if ($sin_bodegaje == 1) {
            $this->GstOrdenes->delBodegaje($id);
        }

        $this->GstOrdenes->createComprobante($cliente_id, $nombre_img, $tipo[1], $id);
        $this->GstOrdenes->pagoManual($id, $observaciones);
        $data["libras"] = 0;
        $data['count'] = 0;
        $data['objFactura'] = $this->GstOrdenes->newFactura($id);

        foreach ($data['objFactura']->orden->articulos as $row) {
            $data["libras"] = $data["libras"] + $row->articulo->peso;
        }

        $html = $this->load->view('ordenes/pdfTemplateFactura', $data, true);

        $pdf_content = $this->pdfgenerator->generate($html, null, false);

        if (!file_exists("./uploads/facturas/$cliente_id/")) {
            mkdir("./uploads/facturas/$cliente_id/", 0755, true);
        }

        $nombre = $data['objFactura']->nombre;
        file_put_contents("./uploads/facturas/$cliente_id/$nombre", $pdf_content);

        $msg = $this->load->view('ordenesAdmin/emailConfirm', $datos, true);

        $this->ConfigEmail->to($datos['orden']->cliente->email);
        $this->ConfigEmail->subject('Pago recibido');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->attach("./" . $data['objFactura']->path . $data['objFactura']->nombre);
        $this->ConfigEmail->send();

        $this->session->set_flashdata('msgOk', 'La orden de compra ha sido pagada manualmente con éxito.');
        
        if($datos['orden']->cliente->device!=null && $datos['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Pago recibido de tu orden #".$datos['orden']->factura,$datos['orden']->cliente->device);
        }

        redirect(['ordenes-de-compra']);
    }

    public function imprimirPrueba($id)
    {
        $this->load->library('pdfgenerator');
        $data["objOrden"] = $this->GstOrdenes->getOrden($id);

        $html = $this->load->view('ordenesAdmin/pdfTemplateOrden', $data, true);

        $this->pdfgenerator->generate($html, "prueba de entrega");
    }

    public function eliminarOrden($id)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);
        if ($data['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden no se puede anular porque no está pendiente de pago.');
            redirect(['ordenes-de-compra']);
        }
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/eliminarOrden', $data);
        $this->load->view('layout/footerAdmin', $data);

    }

    public function abandonarOrden($id)
    {
        $result = $this->GstOrdenes->abandonarOrden($id);
        if (!$result) {
            $this->session->set_flashdata('msgError', 'No se pudo abandonar la orden.');
            redirect(['ordenes-de-compra']);
        }
        $this->session->set_flashdata('msgOk', 'Se abandonó la orden con éxito.');
        redirect(['ordenes-de-compra']);
    }

    public function pagarOrden($id)
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);
        if ($data['orden']->estado != 0 && $data['orden']->estado != 4) {
            $this->session->set_flashdata('msgError', 'La orden no se puede pagar manualmente porque no esta habilitada para pago.');
            redirect(['ordenes-de-compra']);
        }
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('ordenesAdmin/pagarOrden', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function updDomicilio()
    {
        $orden_id = $this->input->post('orden_id');
        $domicilio = $this->input->post('domicilio');
        $this->GstOrdenes->setDomicilio($orden_id, $domicilio);
        $this->session->set_flashdata('msgOk', 'Se actualizó el domicilio con éxito');
        redirect(base_url(['ordenes-de-compra', 'ver', $orden_id]));

    }

    public function reenviarOrden($id)
    {
        $this->load->GstClass('GstOrdenes');

        $data['valTotal'] = 0;
        $data['userdata'] = $this->session->userdata('admin');
        $data['orden'] = $this->GstOrdenes->getOrden($id);

        if (!$data['orden']) {
            $this->session->set_flashdata('msgError', 'Error al enviar el correo, no existe la orden.');
            redirect(base_url(['ordenes-de-compra']));
        }

        $msg = $this->load->view('ordenesAdmin/emailReenviarOrden', $data, true);

        $this->ConfigEmail->to($data['orden']->cliente->email);
        $this->ConfigEmail->subject('Llegaron tus paquetes');
        $this->ConfigEmail->message($msg);
        if (!$this->ConfigEmail->send()) {
            $this->session->set_flashdata('msgError', 'No se pudo enviar el correo, por favor inténtalo de nuevo.');
            redirect(['ordenes-de-compra']);
        }

        if($data['orden']->cliente->device!=null && $data['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Llegaron tus paquetes",$data['orden']->cliente->device);
        }
        
        $this->session->set_flashdata('msgOk', 'El correo se ha enviado y se ha creado la orden de compra con éxito.');

        redirect(base_url(['ordenes-de-compra', 'ver', $id]));
    }

    public function eliminarProcess()
    {
        $observaciones = trim($this->input->post('observaciones'));
        $orden_id = $this->input->post('orden_id');

        if (!$observaciones) {
            $this->session->set_flashdata('msgError', 'El campo observaciones es obligatorio.');
            redirect(base_url(['ordenes-de-compra', 'eliminar-orden', $orden_id]));
        }

        if (!$this->GstOrdenes->eliminar($orden_id, $observaciones)) {
            $this->session->set_flashdata('msgError', 'No se pudo anular la orden.');
            redirect(base_url(['ordenes-de-compra', 'eliminar-orden', $orden_id]));
        }

        $this->session->set_flashdata('msgOk', 'La orden se anuló correctamente.');
        redirect(base_url(['ordenes-de-compra', 'ver', $orden_id]));
    }

    public function nuevoAbonoProcess()
    {
        $id = $this->input->post('orden_id');
        $search = ['$', '.', ' '];
        $valor_abono = str_replace($search, "", trim($this->input->post('valor')));
        $datos['valor'] =  trim($this->input->post('valor'));
        $datos['orden'] = $this->GstOrdenes->getOrden($id);
        $abonado = $datos['orden']->totalAbonos();
        
        if($abonado+$valor_abono >= $datos['orden']->valor)
        {
            $this->session->set_flashdata('msgError', 'No se puede crear el abono porque supera el total de la orden');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }
        

        $datos['userdata'] = $this->session->userdata('admin');
        $comprobante = $_FILES['comprobante'];
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','jfif','JFIF'];
        $tipo = explode('.', $comprobante['name']);

        if (!$datos['orden'] || $datos['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'No se puede crear el abono porque la orden no esta pendiente por pagar.');
            redirect(['ordenes-de-compra']);
        }

        $cliente_id = $datos['orden']->user_id;

        if ($comprobante['size'] == 0 || !$comprobante) {
            $this->session->set_flashdata('msgError', 'El campo adjuntar comprobante es obligatorio.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }

        if (!in_array($tipo[1], $types)) {
            $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }
        $nombre_img = $this->GstOrdenes->uploadComprobante($cliente_id);
        if (!$nombre_img) {
            $this->session->set_flashdata('msgError', 'No se pudo subir el comprobante, por favor inténtalo de nuevo.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }

        $result = $this->GstOrdenes->crearAbono($id,$cliente_id,$nombre_img.".".$tipo[1],$valor_abono);

        if (!$result) {
            $this->session->set_flashdata('msgError', 'No se pudo crear el abono, por favor inténtalo de nuevo.');
            redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
        }
        
        $datos['total_abonos'] = $datos['orden']->totalAbonos();

        $msg = $this->load->view('ordenesAdmin/emailAbono', $datos, true);

        $this->ConfigEmail->to($datos['orden']->cliente->email);
        $this->ConfigEmail->subject('Pago abonado a la orden');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->send();
        
        if($datos['orden']->cliente->device!=null && $datos['orden']->cliente->auth_app==1){
            $this->GstApi->sendMessageApp("Pago abonado a la orden #".$datos['orden']->factura,$datos['orden']->cliente->device);
        }
       
        $this->session->set_flashdata('msgOk', 'Se creó el abono con éxito');
        redirect(base_url(['ordenes-de-compra', 'pagar-orden', $id]));
    }

    public function updDescuentoProcess()
    {
        $orden_id = $this->input->post('orden_id');
        $observaciones = $this->input->post('observaciones');
        $search = ['$', '.', ' '];
        $descuento = str_replace($search, "", trim($this->input->post('descuento')));
        $this->GstOrdenes->setDescuento($descuento , $orden_id);
        $this->GstOrdenes->setObservaciones($observaciones,$orden_id);
        $this->session->set_flashdata('msgOk', 'Se actualizó el descuento con éxito');
        redirect(base_url(['ordenes-de-compra', 'ver', $orden_id]));

    }
}
