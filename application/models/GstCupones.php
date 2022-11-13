<?php

class GstCupones extends CI_Model
{

    public function __construct()
    {
        $this->load->modelORM('McCupones');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McConfig');
        $this->load->modelORM('McCuponesClientes');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('GstToken');
        $this->load->GstClass('GstAdmin');
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('GstCupones');
        $this->load->library('form_validation');
        parent::__construct();

    }

    public function getAll()
    {
        return McCupones::all();
    }

   
    public function crearCupon($cupon)
    {
        
        if (!$this->validarCupon()) {
            return false;
        }
        
        $cupon['nombre'] = trim($cupon['nombre']);
        $cupon['tipo'] = trim($cupon['tipo']);
        $cupon['valor'] = trim($cupon['valor']);
        $cupon['start_date'] = trim($cupon['start_date']);
        $cupon['end_date'] = trim($cupon['end_date']);
        
        if(!isset($cupon['nuevo_cliente'])){
            $cupon["nuevo_cliente"] = 2;
        }

        if(!isset($cupon['app'])){
            $cupon["app"] = 2;
        }
      
        $objCupon = new McCupones;
        $objCupon->codigo = strtoupper($cupon['nombre']);
        $objCupon->tipo = $cupon['tipo'];
        $objCupon->valor = $cupon['valor'];
        $objCupon->estado = 1;
        $objCupon->app = $cupon['app'];
        $objCupon->start_date = strtotime(str_replace('/', '-',$cupon['start_date']));
        $objCupon->nuevo_cliente = trim($cupon['nuevo_cliente']);
        $objCupon->end_date = strtotime(str_replace('/', '-',$cupon['end_date']));
        $objCupon->created_at = time();

        if (!$objCupon->save()) {
            $this->session->set_flashdata('msgError', 'No se pudo crear el cupón, inténtalo de nuevo.');
            return false;
        }
        $this->session->set_flashdata('msgOk', 'Se creó el cupón exitosamente.');
        return true;
    }

    public function validarCupon()
    {
        $config = [
            [
                'field' => 'data[nombre]',
                'label' => 'nombre',
                'rules' => [
                    'required',
                    'is_unique[mc_cupones.codigo]'
                    
                ],
                'errors' => [
                    'required' => 'El campo nombre del cupón es requerido',
                    'is_unique' => "El nombre del cupón ya está en uso"
                    
                ],
            ],
            [
                'field' => 'data[tipo]',
                'label' => 'tipo',
                'rules' => [
                    'required',

                ],
                'errors' => [
                    'required' => 'El campo tipo es requerido',
                ],
            ],
            [
                'field' => 'data[valor]',
                'label' => 'valor',
                'rules' => [
                    'required',
                    'numeric',
                ],
                'errors' => [
                    'required' => 'El campo valor es requerido',
                    'numeric' => 'El campo valor no es correcto',
                ],
            ],
            [
                'field' => 'data[start_date]',
                'label' => 'fecha de inicio',
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'El campo fecha de inicio es requerido',
                    'numeric' => 'El campo fecha de inicio no es correcto',
                ],
            ],
            [
                'field' => 'data[end_date]',
                'label' => 'fecha de finalización',
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'El campo fecha de finalización es requerido',
                    'numeric' => 'El campo fecha de finalización no es correcto',
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

    public function getCuponName($name,$registro=false)
    {
        $today = time();
        $cupon = McCupones::where("codigo",trim(strtoupper($name)))
        ->where("estado",1)
        ->get()
        ->first();

        if(!$cupon){
            return false;
        }

        if($registro && $cupon->nuevo_cliente!=1){
            return false;
        }

        if($cupon->app==1){
            if(!$today >= $cupon->start_date && $today <= $cupon->end_date ){
                if($registro){

                    return $cupon;

                }elseif(!$this->GstToken->validateToken()){
                    return false;
                }
            }
        }

        if($today >= $cupon->start_date && $today <= $cupon->end_date ){
            return $cupon;
        }

        return false;

    }
    public function saveCuponesClientes($user_id,$cupon_id,$orden_id)
    {
        $McCuponesClientes = new McCuponesClientes;
        $McCuponesClientes->cupon_id=$cupon_id;
        $McCuponesClientes->user_id=$user_id;
        $McCuponesClientes->orden_id=$orden_id;
        $McCuponesClientes->created_at=time();


        if (!$McCuponesClientes->save()) {
            return false;
        }

        return true;

    }
    
    public function validarCuponUser($user_id,$cupon_id)
    {
        $objValidarCupon= McCuponesClientes::where("user_id",$user_id)
        ->where("cupon_id",$cupon_id)
        ->get()
        ->first();

        if($objValidarCupon){
            return false;
        }
        return true;
    }

    public function asignarCupon($nameCupon)
    {
        
        if($this->GstToken->validateToken()){
            $objCliente=$this->GstToken->getUserToken();
            $objOrden = $this->GstOrdenes->getOrdenesActivas($objCliente->id);

        }else{
            $data['userdata'] = $this->session->userdata('user');
            $objOrden = $this->GstOrdenes->getOrdenesActivas($data['userdata']['id']);
        }

        $objCupon = $this->getCuponName($nameCupon);

        if(!$objCupon){
            $this->session->set_flashdata('msgError', 'El cupón que intentas redimir no existe.');
            return false;
        }


        if(!$objOrden){
            $this->session->set_flashdata('msgError', 'No tienes una orden de compra pendiente por pagar para redimir el cupón.');
            return false;
        }

        if(!$objCupon){
            $this->session->set_flashdata('msgError', 'El cupón que intentar redimir no está disponible.');
            return false;
        }

        if(!$this->validarCuponUser($objOrden->user_id, $objCupon->id)){

            $this->session->set_flashdata('msgError', 'No puedes redimir tu cupón más de una vez.');
            return false;
        }
        
        $valor_fletes = $objOrden->getFletesCupon();


        if($valor_fletes=== 0){
            $this->session->set_flashdata('error-pre-alerta', 'No es posible aplicar el cupón a la orden.');
            return false;
        }

        if($objCupon->tipo===1){
            $descuento = ($valor_fletes*$objCupon->valor)/100;

        }
        if($objCupon->tipo===2){
            $descuento =  $objCupon->valor;
            if($objCupon->valor>= $valor_fletes){
                $descuento = $valor_fletes;
            }
        }

        $this->GstOrdenes->setDescuento($descuento,$objOrden->id);

        $this->saveCuponesClientes($objOrden->user_id, $objCupon->id, $objOrden->id);
        
        return true;
    }

   public function getDescuentoCupon($user_id)
   {
        $today = time();
        $objCliente = McCliente::find($user_id);
        $objCupon = $objCliente->cupon_registro;
        
        if(!$objCupon){
            return false;
        }

        if(!$today >= $objCupon->start_date && $today <= $objCupon->end_date ){
            return false;
        }

        if(!$this->validarCuponUser($user_id,$objCupon->id)){
            return false;
        }
        
        $fletes=$this->GstAdmin->getFletesCrearOrden($user_id);

        if($objCupon->tipo===1){
            $descuento = ($fletes*$objCupon->valor)/100;
        }

        if($objCupon->tipo===2){
            $descuento =  $objCupon->valor;
            if($objCupon->valor>= $fletes){
                $descuento = $fletes;
            }
        }
              
        return $descuento;
    }
    
    public function getCuponUser($user_id){
        $objCliente = McCliente::find($user_id);
        $objCupon = $objCliente->cupon_registro;

        return $objCupon;

    }

   
}
