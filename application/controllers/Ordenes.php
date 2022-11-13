<?php

class Ordenes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->validateCliente()) {
            redirect('login/close');
        }
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('ConfigEmail');
        $this->load->modelORM('McConfig');

    }

    public function ordenesLst()
    {
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $user_id = $data['userdata']['id'];
        $data['ordenes'] = $this->GstOrdenes->getOrdenesLst($user_id);
        $oc_pendiente = $this->GstOrdenes->getOrdenPendiente($user_id);
        
        if($oc_pendiente){
            redirect("mis-ordenes/ver/$oc_pendiente->id");
        }

        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/ordenesLst');
        $this->load->view('layout/footerUser');
    }
    /**
     * Metodo para hacer el pago por medio de comprobante
     */
    public function pagarOrdenCm($id)
    {
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $user_id = $data['userdata']['id'];
        $data['orden'] = $this->GstOrdenes->getOrden($id);
        if ($data['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }
        if ($data['orden']->user_id != $user_id) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }
        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/pagarOrdenCm', $data);
        $this->load->view('layout/footerUser', $data);
    }

    public function pagarOrdenCmProcess()
    {
        $id = $this->input->post('orden_id');
        $comprobante = $_FILES['comprobante'];
	    $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP'];
        $tipo = explode('.', $comprobante['name']);

        if ($comprobante['size'] == 0 || !$comprobante) {
            $this->session->set_flashdata('msgError', 'Por favor adjunte una imagen con un tamaño menor a 2 Megabytes.');
            redirect(base_url(['mis-ordenes', 'pagar-con-comprobante', $id]));
        }

        if (!in_array($tipo[1], $types)) {
            $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
            redirect(base_url(['mis-ordenes', 'pagar-con-comprobante', $id]));
        }
        $nombre_img = $this->GstOrdenes->uploadComprobante();
        if (!$nombre_img) {
            $this->session->set_flashdata('msgError', 'No se pudo subir el comprobante, por favor inténtalo de nuevo.');
            redirect(base_url(['mis-ordenes', 'pagar-con-comprobante', $id]));
        }

        if (!$this->GstOrdenes->newPagoComprobante($id, $nombre_img, $tipo[1])) {
            $this->session->set_flashdata('msgError', 'No se pudo efectuar el pago, por favor inténtalo de nuevo.');
            redirect(base_url(['mis-ordenes', 'pagar-con-comprobante', $id]));
        }

        $this->session->set_flashdata('msgOk', 'El pago ha sido efectuado con éxito, se procede a poner la orden de compra en estado de aprobación, una vez haya terminado el proceso de aprobación será notificado por correo electrónico.');
        redirect(base_url(['mis-ordenes']));

    }

    public function reenviarOrden($id)
    {
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $user_id = $data['userdata']['id'];
        $data['orden'] = $this->GstOrdenes->getOrden($id);

        if ($data['orden']->user_id != $user_id) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }

        if ($data['orden']->estado != 4) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no está habilitada para reenvío.');
            redirect(base_url(['mis-ordenes']));
        }
        $data['comprobante'] = $data['orden']->comprobantes->last();
        if (!$data['comprobante']) {
            $data['comprobante'] = null;
        }
        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/reenviarOrden', $data);
        $this->load->view('layout/footerUser', $data);
    }

    public function reenviarOrdenProcess()
    {
        $orden_id = $this->input->post('orden_id');
        $comprobante_id = $this->input->post('comprobante_id');
        $comprobante = $_FILES['comprobante'];
	    $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP'];
        $tipo = explode('.', $comprobante['name']);

        if ($comprobante['size'] == 0 || !$comprobante) {
            $this->session->set_flashdata('msgError', 'El campo adjuntar comprobante es obligatorio.');
            redirect(base_url(['mis-ordenes', 'reenviar-comprobante', $orden_id]));
        }

        if (!in_array($tipo[1], $types)) {
            $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
            redirect(base_url(['mis-ordenes', 'reenviar-comprobante', $orden_id]));
        }
        $nombre_img = $this->GstOrdenes->uploadComprobante();
        if (!$nombre_img) {
            $this->session->set_flashdata('msgError', 'No se pudo subir el comprobante, por favor inténtalo de nuevo.');
            redirect(base_url(['mis-ordenes', 'reenviar-comprobante', $orden_id]));
        }

        if (!$this->GstOrdenes->updComprobante($orden_id, $nombre_img, $tipo[1], $comprobante_id)) {
            $this->session->set_flashdata('msgError', 'No se pudo efectuar el reenvío de comprobante, por favor inténtalo de nuevo.');
            redirect(base_url(['mis-ordenes', 'reenviar-comprobante', $orden_id]));
        }

        $this->session->set_flashdata('msgOk', 'El reenvío de comprobante ha sido efectuado con éxito, se procede a poner la orden de compra en estado de aprobación, una vez haya terminado el proceso de aprobación será notificado por correo electrónico.');
        redirect(base_url(['mis-ordenes']));

    }

    public function pagarOrdenTc($id)
    {
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $user_id = $data['userdata']['id'];
        $data['orden'] = $this->GstOrdenes->getOrden($id);

        if ($data['orden']->estado != 0) {
        $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
        redirect(base_url(['mis-ordenes']));
        }
        if ($data['orden']->valor == 0) {
        $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago en línea.');
        redirect(base_url(['mis-ordenes']));
        }
        if ($data['orden']->user_id != $user_id) {
        $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
        redirect(base_url(['mis-ordenes']));
        }
        $data['recargo'] = $data['orden']->valor * 0.042;
        $data['total_pago'] = $data['orden']->valor + $data['recargo'];
        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/pagarOrdenTc', $data);
        $this->load->view('layout/footerUser', $data);
    }

    public function verOrden($id)
    {
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
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

        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/verOrden', $data);
        $this->load->view('layout/footerUser', $data);
    }

    public function paymentMethod($id)
    {
        $config = McConfig::find(1);
        $data["pago_virtual"] = false;
        $data['userdata'] = $this->session->userdata('user');
        $nombre = explode(' ', $data['userdata']['primer_nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $user_id = $data['userdata']['id'];

        $data['orden'] = $this->GstOrdenes->getOrden($id);
        if ($data['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }
        if ($data['orden']->user_id != $user_id) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }

        //valida si tiene las llaves de pago en línea epayco
        if($config->epayco_cliente_id && $config->epayco_p_key && $config->epayco_public_key && $config->epayco_private_key )
        {
            $data["pago_virtual"] = true;
        }


        $this->load->view('layout/headUser', $data);
        $this->load->view('ordenes/paymentMethod');
        $this->load->view('layout/footerUser');
    }

}
