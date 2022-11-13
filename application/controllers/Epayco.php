<?php

class Epayco extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    
        $this->load->GstClass('GstEpayco');
        $this->load->GstClass('Gstuser');
        $this->load->GstClass('ConfigEmail');
        $this->load->GstClass('GstOrdenes');
        $this->load->modelORM('McConfig');
        $this->load->library('pdfgenerator');


    }

    public function response()
    {
        $this->load->view('Epayco/response');
        
    }

    public function confirmation()
    {
        $config = McConfig::find(1);
        $data = [];
        $p_cust_id_cliente = $config->epayco_cliente_id;
        $p_key = $config->epayco_p_key;
        $x_ref_payco = $_REQUEST['x_ref_payco'];
        $x_transaction_id = $_REQUEST['x_transaction_id'];
        $x_amount = $_REQUEST['x_amount'];
        $x_currency_code = $_REQUEST['x_currency_code'];
        $x_signature = $_REQUEST['x_signature'];
        $signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);
        $x_response = $_REQUEST['x_response'];
        $x_motivo = $_REQUEST['x_response_reason_text'];
        $x_id_invoice = $_REQUEST['x_id_invoice'];
        $x_autorizacion = $_REQUEST['x_approval_code'];
        //Validamos la firma
        if ($x_signature == $signature) {
            /*Si la firma esta bien podemos verificar los estado de la transacción*/
            $x_cod_response = $_REQUEST['x_cod_response'];
            $objOrden = $this->GstOrdenes->getOrden($x_id_invoice);
            
            if(!$objOrden || $objOrden->estado!=6){
                return;
            }

            if($x_cod_response==3){
                //estado pendiente de epayco
                return;
            }
            
            if($x_cod_response!=1){
                $this->GstOrdenes->resetPayment($objOrden->id);
                return;
            }

            if(!$this->GstOrdenes->aprobarOrden($objOrden->id)){
                return;
            }

            $data["libras"] = 0;
            $data['objFactura'] = $this->GstOrdenes->newFactura($objOrden->id);
            
            if(!$data['objFactura']){
                return;
            }

            $id_path = $data['objFactura']->orden->user_id;

            foreach ($data['objFactura']->orden->articulos as $row) {
                $data["libras"] = $data["libras"] + $row->articulo->peso;
            }
            $data['count'] = 0;
            $html = $this->load->view('ordenes/pdfTemplateFactura', $data, true);

            $pdf_content = $this->pdfgenerator->generate($html, null, false);

            if (!file_exists("./uploads/facturas/$id_path/")) {
                mkdir("./uploads/facturas/$id_path/", 0755, true);
            }

            $nombre = $data['objFactura']->nombre;
            
            file_put_contents("./uploads/facturas/$id_path/$nombre", $pdf_content);

            $msg = $this->load->view('ordenes/emailPagos', $data, true);

            $this->ConfigEmail->to($data['objFactura']->orden->cliente->email);
            $this->ConfigEmail->subject('Pago recibido');
            $this->ConfigEmail->message($msg);
            $this->ConfigEmail->attach("./" . $data['objFactura']->path . $data['objFactura']->nombre);
            $this->ConfigEmail->send();
            return;
            
        }
    }

    public function pse($id)
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

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

        $data['bancos'] = $this->GstEpayco->getBancos();
        $data['document_types']=["CC"=>"Cédula de ciudadanía","CE"=>"Cédula de extranjería","PPN"=>"Pasaporte","NIT"=>"Número de identificación tributaria"];
        $data['info_comision'] = $this->GstEpayco->calcularComision($data['orden']);

        $this->load->view('layout/headUser', $data);
        $this->load->view('Epayco/pse');
        $this->load->view('layout/footerUser');
    }

    public function pseProcess()
    {
        $data = $this->input->post("data");
        $result = $this->GstEpayco->validate_pse();

        if(!$result){
            redirect(base_url(['pago-en-linea','pse',$data['orden_id']]));
        }

        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        
        $result = $this->GstEpayco->pse($data);
        redirect($result['urlbanco']);

        
    }

    public function cards($id)
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

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

        $data['card_default'] = $this->GstEpayco->getCardDefault($user_id);

        if(!$data['card_default']){
            redirect(base_url(['pago-en-linea','card-transaction',$id]));
        }

        $data['cards'] = $this->GstEpayco->getCards($user_id);

        $this->load->view('layout/headUser', $data);
        $this->load->view('Epayco/cards');
        $this->load->view('layout/footerUser');
    }

    public function cardTransaction($id)
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

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
        
        $data['info_comision'] = $this->GstEpayco->calcularComision($data['orden']);
        $data['document_types']=["CC"=>"Cédula de ciudadanía","CE"=>"Cédula de extranjería","PPN"=>"Pasaporte","NIT"=>"Número de identificación tributaria"];
        $this->load->view('layout/headUser', $data);
        $this->load->view('Epayco/cardTransaction');
        $this->load->view('layout/footerUser');
    }

    public function cardProcess()
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

        $user_data = $this->session->userdata('user');
        $objCliente = $this->Gstuser->cliente($user_data["id"]);
        $data =  $this->input->post("data");
        //TO-DO
        $data['ordenId'] = $data['orden_id'];//13102020 @gnupan tuve que hacer esto porque no se implementa el estandar de codificacion
        $data['ip'] = $_SERVER['REMOTE_ADDR'];

        
        $objOrden = $this->GstOrdenes->getOrden($data['orden_id']);

        if ($objOrden->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }
        if ($objOrden->user_id != $user_data["id"]) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }
        
        if(!$this->GstEpayco->validateCardTransaction()){
            redirect(base_url(['pago-en-linea','card-transaction',$data['orden_id']]));
        }

        if(!$this->GstEpayco->card($data,$objCliente)){
            $this->session->set_flashdata('msgError', 'Ha fallado la transaccion por favor inténtalo nuevamente.');
            redirect(base_url(['mis-ordenes']));
        }

        redirect(base_url(['epayco','response']));
        
    }

    public function payCard($orden_id,$card_id)
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

        $user_data = $this->session->userdata('user');
        $objCliente = $this->Gstuser->cliente($user_data["id"]);
        
        $data['orden'] = $this->GstOrdenes->getOrden($orden_id);

        if ($data['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }
        if ($data['orden']->user_id != $objCliente->id) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }
        $data['selected_card'] = $this->GstEpayco->getCard($card_id);
        $data['info_comision'] = $this->GstEpayco->calcularComision($data['orden']);

        $this->load->view('layout/headUser', $data);
        $this->load->view('Epayco/payCard');
        $this->load->view('layout/footerUser');

    }

    public function payCardProcess()
    {
        if (!$this->validateCliente()) {
            redirect('login/close');
        }

        $data = $this->input->post("data");
        $user_data = $this->session->userdata('user');
        $objCliente = $this->Gstuser->cliente($user_data["id"]);
        
        $data['orden'] = $this->GstOrdenes->getOrden($data['orden_id']);

        if(!$data['orden']){
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }
        


        if ($data['orden']->estado != 0) {
            $this->session->set_flashdata('msgError', 'La orden de compra no está habilitada para pago.');
            redirect(base_url(['mis-ordenes']));
        }

        
        if ($data['orden']->user_id != $objCliente->id) {
            $this->session->set_flashdata('msgError', 'Ha seleccionado una orden que no se le ha asignado.');
            redirect(base_url(['mis-ordenes']));
        }

        if(!$this->GstEpayco->validatePayCard()){
            redirect(base_url(['pago-en-linea','pay-card',$data['orden_id'],$data['card_id']]));
        }
        

        $info_card=$this->GstEpayco->getCard($data['card_id']);

        $token=$info_card->token;

        $card=[
          "nombre"=>$info_card->nombre,
          "ordenId"=>$data['orden_id'],
          "cuotas"=>$data['cuotas'],
          "valor_total"=>$data['valor_total'],
        ];

        $response = $this->GstEpayco->cardTransaccion($card,$token,$objCliente);

        if(!$response){
            $this->session->set_flashdata('msgError', 'No se pudo realizar el pago, por favor inténtalo nuevamente.');
            redirect(base_url(['pago-en-linea','pay-card',$data['orden_id']]));
        }

        redirect(base_url(['epayco','response']));

    }


}
