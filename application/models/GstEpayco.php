<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GstEpayco extends CI_Model
{
    var $epayco;
    var $config;
    public function __construct()
    {
        parent::__construct();
        $this->load->GstClass('GstOrdenes');
        $this->load->modelORM('McCards');
        $this->load->modelORM('McConfig');
        $this->load->library('form_validation');
        $this->config = McConfig::find(1);

        if(!$this->config->epayco_cliente_id || !$this->config->epayco_p_key || !$this->config->epayco_public_key || !$this->config->epayco_private_key )
        {
            redirect("mis-ordenes");
        }

       try {
            $this->epayco = new Epayco\Epayco(array(
                "apiKey" =>  $this->config->epayco_public_key,
                "privateKey" =>  $this->config->epayco_private_key,
                "lenguage" => "ES",
                "test" => false
            ));
       } catch (\Throwable $th) {
            redirect("mis-ordenes");
       }

        
    }

    public function pse($data)
    {
        $pse = $this->epayco->bank->create(array(
            "bank" => $data['bank'],
            "invoice" => $data['invoice'],
            "description" => "Pago  de orden de compra en ".$this->config->smtp_from_name,
            "value" => $data['value'],
            "tax" => "0",
            "tax_base" => "0",
            "currency" => "COP",
            "type_person" => $data['type_person'],
            "doc_type" => $data['doc_type'],
            "doc_number" => $data['doc_number'],
            "name" => $data['name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "country" => "CO",
            "cell_phone" => $data['cell_phone'],
            "ip" => $data['ip'],  // This is the client's IP, it is required
            "url_response" => base_url("epayco/response"),
            "url_confirmation" => base_url("epayco/confirmation"),
            "method_confirmation" => "GET",
            //Extra params: These params are optional and can be used by the commerce
            "extra1" => "",
            "extra2" => "",
            "extra3" => "",
            "extra4" => "",
            "extra5" => "",
            "extra6" => "",
            "extra7" => "",
        ));
        
        if(!$pse->success){
            return false;
        }

        //inicia la transacción y deja la OC pendiente por confirmación 
        if(!$this->GstOrdenes->initPayment($data['invoice'],"pse"))//$orden_id, $payment_method
        {
            return false;
        }

        return [
            
            "ref_payco"=>$pse->data->ref_payco,
            "factura"=>$pse->data->factura,
            "estado"=>$pse->data->estado,
            "urlbanco"=>$pse->data->urlbanco,
            "transactionID"=>$pse->data->transactionID,
            "ticketId"=>$pse->data->ticketId,
        ];


       
    }

    public function getBancos(){
        $response = json_decode(file_get_contents("https://secure.payco.co/restpagos/pse/bancos.json?public_key=".$this->config->epayco_public_key),true);//key db

        return $response['data'];   
    }

    public function calcularComision($objOrden)
    {
        if($objOrden->valor<=57000){

            $comision_pse_low=3000;
            $valor_total=ceil($objOrden->valor+$comision_pse_low);
            $data_response['pse']=[
              'id'=>$objOrden->id,
              'valor'=>"$".number_format($objOrden->valor,0, '', '.'),
              'comision_pago'=>"$".number_format($comision_pse_low,0, '', '.'),
              'total'=>"$".number_format($valor_total,0, '', '.'),
              'valor_total'=>$valor_total

            ];
          }else{
            $comision=($objOrden->valor*0.0314)+900;
            $iva=((($objOrden->valor+$comision)*0.03)+900)*0.19;
            $comision=$comision+$iva;
            $valor_total=ceil($objOrden->valor+$comision);

            $data_response['pse']=[
              'id'=>$objOrden->id,
              'valor'=>"$".number_format($objOrden->valor,0, '', '.'),
              'comision_pago'=>"$".number_format($comision,0, '', '.'),
              'total'=>"$".number_format($valor_total,0, '', '.'),
              'valor_total'=>$valor_total

            ];
          }

          $comision=($objOrden->valor*0.0314)+900;
          $comision_epayco=(($objOrden->valor+$comision)*0.03)+900;
          $iva=$comision_epayco*0.19;
          $rf= ($objOrden->valor+$comision)*0.01537;
          $r_ica=($objOrden->valor+$comision)*0.00237;
          $comision=$comision+$iva+$rf+$r_ica; 
          $valor_total=ceil($objOrden->valor+$comision);

          $data_response['card']=[
            'id'=>$objOrden->id,
            'valor'=>"$".number_format($objOrden->valor,0, '', '.'),
            'comision_pago'=>"$".number_format($comision,0, '', '.'),
            'total'=>"$".number_format($valor_total,0, '', '.'),
            'valor_total'=>$valor_total

          ];

          return $data_response;
    }


    /**
     * metodo que crea una transaccion con tarjeta nueva
     */
    public function card($card,$objCliente)
    {
        $token = $this->epayco->token->create(array(
            "card[number]" => $card['mask'],
            "card[exp_year]" => $card['exp_year'],
            "card[exp_month]" => $card['exp_month'],
            "card[cvc]" => $card['cvv']
        ));  
    
        $customer = $this->epayco->customer->create(array(
            "token_card" => $token->id,
            "name" => $card['nombre'],
            "last_name" => $card['nombre'], //This parameter is optional
            "email" => $card['email'],
            "default" => true,
            //Optional parameters: These parameters are important when validating the credit card transaction
            "city" => $objCliente->ciudad,
            "address" => $objCliente->direccion,
            "phone" => $objCliente->telefono,
        ));

        $response = $this->cardTransaccion($card,$token->id,$objCliente,$customer->data->customerId);
 
        if($response['response']!=1 && $response['response']!=2 && $response['response']!=3){
            $this->session->set_flashdata('msgError', 'No se pudo crear el pago, inténtalo de nuevo.');
            return false;
        }

        if($response['response']==2){
            $this->session->set_flashdata('msgError', 'Tu pago fue rechazado, inténtalo de nuevo.');
            return false;
        }

        if($card['defecto']==0){
            return true;
        }

        $saveCard = $this->getCardDefault($objCliente->id);

        $mask = substr($card['mask'],-4);

        
        $objCard = new McCards;
        $objCard->token = $token->id;
        $objCard->user_id = $objCliente->id;
        $objCard->mask = $mask;
        $objCard->franchise = $response['franchise'];
        $objCard->customer_id= $customer->data->customerId;
        $objCard->nombre = $response['nombre'];

        if($saveCard){
            $objCard->default=0;

        }else{
            $objCard->default=1;

        }

        if(!$objCard->save()){
            $this->session->set_flashdata('msgError', 'No se pudo guardar tu tarjeta, inténtalo de nuevo.');
            return false;
        }

        return true; 
        
    }


    function cardTransaccion($card,$token,$objCliente,$customer_id=null)
    {   
          //inicia la transacción y deja la OC pendiente por confirmación 
          if(!$this->GstOrdenes->initPayment($card['ordenId'],"card"))//$orden_id, $payment_method
          {
              return false;
          }

          if($customer_id==null){
            $objCard = McCards::where("user_id",$objCliente->id)->get()->first();
            $customer_id = $objCard->customer_id;
          }
          

          $pay = $this->epayco->charge->create(array(
             
            "token_card" => $token,
            "customer_id" => $customer_id,
            "doc_type" => "CC",
            "doc_number" => $objCliente->num_documento,
            "name" => $card['nombre'],
            "last_name" =>$card['nombre'],
            "email" => $objCliente->email,
            "bill" => $card['ordenId'],
            "description" => "Pago  de orden de compra en ".$this->config->smtp_from_name,
            "value" => $card['valor_total'],
            "tax" => "0",
            "tax_base" => "0",
            "currency" => "COP",
            "dues" => $card['cuotas'],
            "address" => $objCliente->direccion,
            "phone"=> $objCliente->telefono,
            "cell_phone"=> $objCliente->telefono,
            "ip" => $card['ip'],  // This is the client's IP, it is required
            "url_response" => base_url("epayco/response"),
            "url_confirmation" => base_url("epayco/confirmation"),
        
            //Extra params: These params are optional and can be used by the commerce
            "use_default_card_customer" => true,/*if the user wants to be charged with the card that the customer currently has as default = true*/
           "extras"=> array(
                "extra1" => "data 1",
                "extra2" => "data 2",
                "extra3" => "data 3",
            )
        ));  
 
        if(!$pay->success){
            return false;
        }

      

        $data = [

            "response"=>$pay->data->cod_respuesta,
            "franchise"=>$pay->data->franquicia,
            "nombre"=>$card['nombre']
        ];
    
        return $data;      
    }

    function getCards($user_id)
    {

        $cards=McCards::select('id','mask','franchise')
        ->where("user_id",$user_id)
        ->where("default",0)
        ->get();

        if(count($cards)==0){
            return false;
        }

        return $cards;

    }

    function getCardDefault($user_id)
    {

        $card=McCards::select('id','mask','franchise')
        ->where("user_id",$user_id)
        ->where("default",1)
        ->get()
        ->first();
       
        if(!$card){
            return false;
        }

        
        return $card;
    }

    function getCard($card_id)
    {
        $card=McCards::find($card_id);

        if(!$card){
            return false;
        }

        return $card;
    }

    function changeCardDefault($card_id,$user_id)
    {

        $card_default=McCards::where("user_id",$user_id)
        ->where("default",1)
        ->get()
        ->first();

        $card_default->default=0;

        if(!$card_default->save()){
            return false;
        }

        $ObjCard=McCards::find($card_id);
        $ObjCard->default=1;

        if(!$ObjCard->save()){
            return false;
        }

        return true;
    }

    
    public function validate_pse()
    {
        $config = [
            [
                'field' => 'data[value]',
                'label' => 'total',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo total es requerido.',
                ],
            ],
            [
                'field' => 'data[invoice]',
                'label' => 'orden_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo orden_id     es requerido.',
                ],
            ],
            [
                'field' => 'data[bank]',
                'label' => 'banco',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo banco es requerido.',
                ],
            ],
            [
                'field' => 'data[name]',
                'label' => 'nombre',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo nombre es requerido.',
                ],
            ],
            [
                'field' => 'data[last_name]',
                'label' => 'apellidos',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo apellidos es requerido.',
                ],
            ],
            [
                'field' => 'data[type_person]',
                'label' => 'tipo de persona',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo tipo de persona es requerido.',
                ],
            ],
            [
                'field' => 'data[doc_type]',
                'label' => 'Tipo de documento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo tipo de documento es requerido.',
                ],
            ],
            [
                'field' => 'data[doc_number]',
                'label' => 'documento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo documento es requerido.',
                ],
            ],
            [
                'field' => 'data[email]',
                'label' => 'email',
                'rules' => [
                    'required',
                    'valid_email',
                ],
                'errors' => [
                    'required' => 'El campo Correo electrónico es requerido.',
                    'valid_email' => 'El campo Correo electrónico no es un correo electrónico valido',
                ],
            ],
            [
                'field' => 'data[cell_phone]',
                'label' => 'telefono',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo teléfono es requerido.',
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }

        return true;
    }

    public function validateCardTransaction()
    {
        $config = [
            [
                'field' => 'data[valor_total]',
                'label' => 'total',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo total es requerido.',
                ],
            ],
            [
                'field' => 'data[invoice]',
                'label' => 'orden_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo orden_id es requerido.',
                ],
            ],
            [
                'field' => 'data[mask]',
                'label' => 'tarjeta de crédito',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo tarjeta de crédito es requerido.',
                ],
            ],
            [
                'field' => 'data[cvv]',
                'label' => 'cvv',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo cvv es requerido.',
                ],
            ],
            [
                'field' => 'data[exp_year]',
                'label' => 'año vencimiento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo año vencimiento es requerido.',
                ],
            ],
            [
                'field' => 'data[exp_month]',
                'label' => 'mes vencimiento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo mes vencimiento es requerido.',
                ],
            ],
            [
                'field' => 'data[cuotas]',
                'label' => 'número de cuotas',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo número de cuotas es requerido.',
                ],
            ],
            [
                'field' => 'data[nombre]',
                'label' => 'nombre de la tarjeta',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo nombre de la tarjeta es requerido.',
                ],
            ],
            [
                'field' => 'data[doc_number]',
                'label' => 'número de documento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo número de documento es requerido.',
                ],
            ],
            [
                'field' => 'data[email]',
                'label' => 'email',
                'rules' => [
                    'required',
                    'valid_email',
                ],
                'errors' => [
                    'required' => 'El campo Correo electrónico es requerido.',
                    'valid_email' => 'El campo Correo electrónico no es un correo electrónico valido',
                ],
            ],
            [
                'field' => 'data[cell_phone]',
                'label' => 'telefono',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo teléfono es requerido.',
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }

        return true;
    }


    public function validatePayCard()
    {
        $config = [
            [
                'field' => 'data[valor_total]',
                'label' => 'total',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo total es requerido.',
                ],
            ],
            [
                'field' => 'data[orden_id]',
                'label' => 'orden_id',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo orden_id es requerido.',
                ],
            ],
            [
                'field' => 'data[cuotas]',
                'label' => 'número de cuotas',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo número de cuotas es requerido.',
                ],
            ],
            [
                'field' => 'data[card_id]',
                'label' => 'tarjeta seleccionada',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo tareta seleccionada es requerido.',
                ],
            ],
            
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }

        return true;
    }

}