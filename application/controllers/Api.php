<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Api extends RestController 
{
  


  function __construct() 
  {
    parent::__construct();

    header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization");
    header("Access-Control-Allow-Origin: *");
    
    
    $this->load->GstClass('GstApi');
    $this->load->GstClass('GstToken');
    $this->load->GstClass('Gstuser');
    $this->load->GstClass('GstRegistro');
    $this->load->GstClass('GstOrdenes');
    $this->load->GstClass('ConfigEmail');
    $this->load->GstClass('GstCupones');
    $this->load->GstClass('GstEpayco');
    $this->load->GstClass('GstApiAgencia');

    
    
}

  public function login_options(){return;}
  public function login_post()
  {
    $data = $this->post();
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || trim($data['email']) =="") {
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar un correo electrónico válido.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if($data['password'] == ""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar contraseña.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    if($data['device'] == ""){
      $data_response['success'] = false;
      $data_response['message'] = 'Device vacio';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstApi->getUserEmail($data['email']);
    $result = $this->GstApi->validateLogin($data['email'],$data['password']);
    
    if(!$objUser || !$result){
      $data_response['success'] = false;
      $data_response['message'] = 'Usuario o contraseña incorrecto.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

  if (!$this->GstApi->setDeviceAuthApp($objUser->id,$data['device'])) {
      $data_response['success'] = false;
      $data_response['message'] = 'no se pudo actualizar device';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    //proceso de token y respuesta
    $data_token = [
        'user_id' => $objUser->id,
        'email' =>$objUser->email,
        'passwd' => $objUser->password
    ];
    
      
    $token = $this->GstToken->createToken($data_token);
    

    $data_response['message'] = 'Usuario logueado.';
    $data_response['data'] = [
        'token' => $token,
        'suite' => $objUser->id,
        'primer_nombre' =>$objUser->primer_nombre,
        'segundo_nombre' => $objUser->segundo_nombre,
        'apellidos' => $objUser->apellidos,
        'email' => $objUser->email,
        'avatar' => base_url().'uploads/imagenes/thumbs/'.$objUser->imagen,
        'trm' => $this->GstApi->getTrm()];
    $this->response($data_response,RestController::HTTP_OK);
    return;
    
  }

  public function registro_options(){return;}
  public function registro_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    $data['parent_id'] = 0;
    $patern = "/^[a-z A-Z ñÑ áéíóúÁÉÍÓÚ]+$/";
    $patern_date ="/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
    $data = $this->post();
   
    if(!isset($data['email']) || trim($data['email']) =="" || !filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar un email válido.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if($this->GstApi->getUserEmail($data['email'])){
      $data_response['success'] = false;
      $data_response['message'] = 'El correo electrónico ya ha sido registrado.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['nombre']) || trim($data['nombre']) =="" || !preg_match($patern, $data["nombre"])){
      $data_response['success'] = false;
      $data_response['message'] = 'En el campo nombre debe ir únicamente letras.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(isset($data['nombre2']) && trim($data['nombre2'])!=""){
      if(!preg_match($patern, $data["nombre2"])){
        $data_response['success'] = false;
        $data_response['message'] = 'En el campo segundo nombre debe ir únicamente letras.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
    }else{
      $data['nombre2']= "";
    }

    if(!isset($data['pais']) || trim($data['pais'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo país es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['ciudad']) || trim($data['ciudad'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo ciudad es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['departamento']) || trim($data['departamento'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo departamento es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['telefono']) || trim($data['telefono'])=="" || !is_numeric($data['telefono'])){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo teléfono es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['apellidos']) || trim($data['apellidos'])=="" || !preg_match($patern, $data["apellidos"])){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo apellidos es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['direccion']) || trim($data['direccion'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo dirección es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['nacimiento']) || trim($data['nacimiento'])=="" || !preg_match($patern_date, $data["nacimiento"])){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo cumpleaños es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    if(!isset($data['descripcion']) || trim($data['descripcion'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo ¿Cómo te enteraste? es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    if(!isset($data['identificacion']) || trim($data['identificacion'])=="" || !is_numeric($data['identificacion'])){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo identificación es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['password']) || trim($data['password'])=="" ){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo contraseña es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if (isset($data['asesor']) && trim($data['asesor']) == "") {
      $data_response['success'] = false;
      $data_response['message'] = 'El campo asesor es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if (isset($data['codigo']) && $data['codigo'] != null && is_numeric($data['codigo'])) {
      if(!$this->GstApi->getUserById(trim($data['codigo']))){
          $data_response['success'] = false;
          $data_response['message'] = 'El código ingresado no existe.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
      }
      $data['parent_id'] = $data['codigo'];
    }else{
      $data['parent_id'] = 0;
    }

    if (isset($data['asesor']) && trim($data['asesor']) != "") {
       $data['descripcion'] .= ": ".trim($data['asesor']);
    }
    
    if (isset($data['asesor']) && $data['asesor'] != null && trim($data['asesor'])!="") {
        $data['descripcion'] .= ": ".$data['asesor'];
    }
    
    if (isset($data['cupon']) && $data['cupon'] != null && trim($data['cupon'])!="") {
      if(!$this->GstCupones->getCuponName($data['cupon'],true)){
        $data_response['success'] = false;
        $data_response['message'] = 'El cupón ingresado no es válido, por favor inténtalo nuevamente.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      $data['cupon'] = $this->GstCupones->getCuponName($data['cupon'],true)->id;
    }
    $data['email'] = trim($data['email']);
    $data['nombre'] = trim(ucwords(mb_strtolower($data['nombre'])));
    $data['pais'] = trim($data['pais']);
    $data['ciudad'] = trim($data['ciudad']);
    $data['departamento'] = trim($data['departamento']);
    $data['nombre2'] = trim(ucwords(mb_strtolower($data['nombre2'])));
    $data['telefono'] = trim($data['telefono']);
    $data['apellidos'] = trim(ucwords(mb_strtolower($data['apellidos'])));
    $data['direccion'] = trim($data['direccion']);
    $data['nacimiento'] = trim($data['nacimiento']);
    $data['descripcion'] = trim($data['descripcion']);
    $data['identificacion'] = trim($data['identificacion']);
    $data['cupon'] = trim($data['cupon']);
    $data['password'] = $this->GstRegistro->genPass(trim($data['password']));
   
    if(!$this->GstApi->saveRegistro($data)){
      $data_response['success'] = false;
      $data_response['message'] = 'No se pudo crear el registro, por favor intente nuevamente.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $data_response['message'] = 'Gracias por registrarse en nuestra plataforma, se ha enviado un email informativo a su correo electrónico. Por favor inicie sesión.';
    $this->response($data_response,RestController::HTTP_OK);
    return;
    
  }

  public function resetpasswd_options(){return;}
  public function resetpasswd_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    $data = $this->post();

    if(!isset($data['email']) || trim($data['email']) =="" || !filter_var($data['email'],FILTER_VALIDATE_EMAIL)){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar un email válido.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $objCliente = $this->GstApi->getUserEmail(trim($data['email']));

    if(!$objCliente){
      $data_response['success'] = false;
      $data_response['message'] = 'Correo equivocado.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $clave = $clave = rand(1000, 99999);

    if(!$this->GstApi->updatePasswd(trim($data['email']),$clave)){
      $data_response['success'] = false;
      $data_response['message'] = 'No se pudo actualizar la contraseña, por favor intente de nuevo.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $param_view =['clave' => $clave];

    $config = $this->GstApi->getConfig();
    $msg = $this->load->view('ReContrasena/email', $param_view, true);
    $this->ConfigEmail->to(trim($data['email']));
    $this->ConfigEmail->subject('Restablecer contraseña de su casillero '.$config->smtp_from_name);
    $this->ConfigEmail->message($msg);
    $this->ConfigEmail->send();

    $data_response['message'] = 'Se ha enviado una contraseña provisional al correo electrónico proporcionado.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function prealerta_options(){return;}
  public function prealerta_post()
  {
    $objConfig = $this->GstApi->getConfig();
    $data_response['success'] = true;
    $data_response['message'] = "";

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $data = $this->post();

    if(!isset($data['articulo']) || trim($data['articulo'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo artículo es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['tracking']) || trim($data['tracking'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo tracking es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['valor_usd']) || trim($data['valor_usd'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo valor del artículo es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['fecha_entrega']) || trim($data['fecha_entrega'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo fecha de entrega es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['transportadora']) || trim($data['transportadora'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo transportadora es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['tienda']) || trim($data['tienda'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo tienda es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['factura']) || trim($data['factura'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo factura es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['seguro']) || trim($data['seguro'])==""){
      $data['seguro'] = "no";
    }

    $objCliente = $this->GstToken->getUserToken();
    
    $prealerta['nombre'] = trim($data['articulo']);
    $prealerta['id_track'] = trim(strtoupper($data['tracking']));
    $prealerta['valor_paquete'] = trim($data['valor_usd']);
    $prealerta['fecha'] = trim($data['fecha_entrega']);
    $prealerta['transportadora'] = trim($data['transportadora']);
    $prealerta['seguro'] = trim($data['seguro']);
    $prealerta['tienda'] = trim($data['tienda']);
    $prealerta['factura'] = $data['factura'];
    $prealerta['user_id'] = $objCliente->id;
    $prealerta['tipo'] = "envio";
    $prealerta['estadoArticulo'] ="Prealertado";

    //no aplica seguro si el articulo es menor o igual a min usd
    if ($prealerta['valor_paquete'] <= $objConfig->seguro_min-1) {
        $prealerta['seguro'] = "no";
    }
    /*aplica seguro opcional si selecciona si siempre y cuando el paquete
    *sea declarado entre min y max usd
    */
    if ($prealerta['seguro'] == "si" && $prealerta['valor_paquete'] >= $objConfig->seguro_min-1 && $prealerta['valor_paquete'] <= $objConfig->seguro_max) {
        $prealerta['seguro'] = "si";
    }
    //aplica seguro obligatorio siempre y cuando el paquete sea declarado por +500 usd
    if ($prealerta['valor_paquete'] >= $objConfig->seguro_max+1) {
        $prealerta['seguro'] = "si";
    }

    $name_factura = $this->GstApi->uploadComprobante($objCliente->id,$prealerta['factura']);

    if(!$name_factura){
      $data_response['success'] = false;
      $data_response['message'] = $this->session->flashdata('msgError');
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $prealerta['factura_path'] = base_url()."uploads/comprobantes/$objCliente->id/$name_factura";
    
    //crea la prealerta
    if (!$this->Gstuser->saveEnvios($prealerta)) {
        $data_response['success'] = false;
        $data_response['message'] = 'Error al crear la prealerta.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
    }
    //obtiene los puntos del cliente
    $objPuntos = $this->Gstuser->puntoCliente($objCliente->parent_id);
    //sistema de puntos si tiene parent o si los puntos son inferiores a 50
    //valida si el referido esta habilitado para sumar puntos
    /* if ($objCliente->parent_id != 0 && $objPuntos->cantidad < 50 && $this->Gstuser->validarReferido($objCliente->id)!=false) {
        $this->Gstuser->asignarPuntos($cliente_id, $objPuntos->cantidad + 5);
    } */

    $prealerta['nombre_cliente'] = $objCliente->primer_nombre;
    $msg = $this->load->view('user/emailPrealerta', $prealerta, true);

    $this->ConfigEmail->to($objCliente->email);
    $this->ConfigEmail->subject('Tu prealerta');
    $this->ConfigEmail->message($msg);
    $this->ConfigEmail->send();

    //01072020 Nupan-Metodo para enviar datos a otra plataforma 
    if($this->GstApiAgencia->getAgenciaId()!=NULL){
      $articulo_id=$this->GstApiAgencia->getArticuloId($prealerta['user_id'],$prealerta['id_track']);
      $this->GstApiAgencia->Prealerta($prealerta,$articulo_id);
    }

    $data_response['message'] = 'Prealerta creada exitosamente.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function paquetes_options(){return;}
  public function paquetes_get($page = 1)
  {
    $estados = ['Prealertado'=>'0.20','Recibido y viajando'=>'0.5','En Cali'=>'0.75','Disponible'=>'0.9','Orden'=>'0.9','En tus manos'=>'1'];
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    $data_response['total_rows'] = 0;
    $data_response['current_page'] = $page;
    $data_response['next_page'] = $page + 1;

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objCliente = $this->GstToken->getUserToken();
    $objPaquetes = $this->GstApi->getPaquetesPaginate($objCliente->id,$page);
    $data_response['total_rows'] = $this->GstApi->getCountPaquetes($objCliente->id);
    
    if($data_response['total_rows']==0){
        $data_response['success'] = true;
        $data_response['message'] = 'No tienes paquetes registrados.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
    }

    foreach ($objPaquetes as $paquete) {
      $data_response['data'][] = [
        "articulo_id"=>$paquete->articulo_id,
        "nombre" => $paquete->nombre,
        "tracking" => $paquete->id_track,
        "fecha_creacion" => date('m/d/Y',$paquete->fecha_registro),
        "estado" => $paquete->estadoArticulo,
        "porcentaje" => $estados[$paquete->estadoArticulo],
        "seguro" => $paquete->seguro,
      ]; 
    }
    
    $data_response['message'] = 'Paquetes cargados exitosamente.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function ordenes_options(){return;}
  public function ordenes_get($page = 1)
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    $data_response['total_rows'] = 0;
    $data_response['current_page'] = $page;
    $data_response['next_page'] = $page + 1;

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objCliente = $this->GstToken->getUserToken();
    $objOrdenes = $this->GstApi->getOrdenesPaginate($objCliente->id,$page);
    $data_response['total_rows'] = $this->GstApi->getCountOrdenes($objCliente->id);
    
    if($data_response['total_rows']==0){
        $data_response['success'] = true;
        $data_response['message'] = 'No tienes ordenes registradas.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
    }

    foreach ($objOrdenes as $orden) {
      $data_response['data'][] = [
        "id" => $orden->id,
        "numero_orden" => $orden->factura,
        "valor" => "$ ".number_format($orden->valor, 0, '', '.'),
        "abonado" => "$ ". number_format($orden->totalAbonos(), 0, '', '.'),
        "fecha_creacion" => date('m/d/Y',$orden->fecha),
        "estado" => $orden->estados[$orden->estado],
        
      ]; 
    }
    
    $data_response['message'] = 'Ordenes cargadas exitosamente.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function orden_options(){return;}
  public function orden_get($orden_id = null)
  {
    
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if($orden_id == null || !is_numeric($orden_id)){
      $data_response['success'] = false;
      $data_response['message'] = 'Orden inválida.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objCliente = $this->GstToken->getUserToken();
    $objOrden = $this->GstApi->getOrden($orden_id);
    
    $objPaquetes = $objOrden->articulos;
    $libras = $objOrden->getLibras();
    $abonos = $objOrden->totalAbonos();

    $data_response['data']['orden'] = [
      'numero' => $objOrden->factura,
      'libras' => $libras,
      'envio' => "$ ".number_format($objOrden->flete_nacional, 0, '', '.'),
      'seguro' => "$ ".number_format($objOrden->seguro, 0, '', '.'),
      'descuento' => "$ ".number_format($objOrden->descuento, 0, '', '.'),
      'valor_total' => "$ ".number_format($objOrden->valor, 0, '', '.')
    ];

    foreach ($objPaquetes as $paquete) {
      $data_response['data']['paquetes'][] = [
        "nombre" => $paquete->articulo->nombre,
        "tracking" => $paquete->articulo->id_track,
        "peso" => $paquete->articulo->peso,
        "valor" => "$ ". number_format($paquete->articulo->valor, 0, '', '.')
      ]; 
    }
    
    $data_response['message'] = 'Orden cargada exitosamente.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function pagarorden_options(){return;}
  public function pagarorden_post()
  {
      $data_response['success'] = true;
      $data_response['message'] = "";
      $data_response['data'] = [];

      if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      $data = $this->post();

      if(!isset($data['orden_id']) || trim($data['orden_id']) =="" || !is_numeric($data["orden_id"])){
        $data_response['success'] = false;
        $data_response['message'] = 'Orden incorrecta.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      $objOrden = $this->GstOrdenes->getOrden($data['orden_id']);

      if(!$objOrden && ($objOrden->estado != 0 || $objOrden->estado !=4)){
        $data_response['success'] = false;
        $data_response['message'] = 'La orden de compra no está habilitada para pago.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      $objUser = $this->GstToken->getUserToken();
      $nombre_img = $this->GstApi->uploadComprobante($objUser->id,$data['comprobante']);

      if($nombre_img === false){
        $data_response['success'] = false;
        $data_response['message'] = 'No se pudo subir el comprobante.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if (!$this->GstApi->newPagoComprobante($objUser->id, $data['orden_id'], $nombre_img)) {
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo pagar la orden.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
      }

      $data_response['message'] = 'El pago ha sido efectuado con éxito, se procede a poner la orden de compra en estado de aprobación, una vez haya terminado el proceso de aprobación será notificado por correo electrónico.';
      $this->response($data_response,RestController::HTTP_OK);
      return;

  }

  public function perfil_options(){return;}
  public function perfil_get()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstToken->getUserToken();
    
    $data_response['data'] = [
      'suite' => $objUser->id,
      'primer_nombre' =>$objUser->primer_nombre,
      'segundo_nombre' => $objUser->segundo_nombre,
      'apellidos' => $objUser->apellidos,
      'identificacion' => $objUser->num_documento,
      'email' => $objUser->email,
      'celular' => $objUser->telefono,
      'pais' => $objUser->pais,
      'ciudad' => $objUser->ciudad,
      'direccion' => $objUser->direccion,
      'correo' => $objUser->email,
      'avatar' => base_url().'uploads/imagenes/thumbs/'.$objUser->imagen,
      'trm' => "$ ".number_format($this->GstApi->getTrm(), 0, '', '.')
    ];
    $data_response['message'] = "Perfil cargado con éxito.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function direccion_options(){return;}
  public function direccion_get()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstToken->getUserToken();

    $data_response['data'] = [
      'nombre' =>$objUser->primer_nombre.' '.$objUser->apellidos.' fox',
      'direccion' => "7590 NW 186 St. Suite 110",
      'codigo_postal' => '33015',
      'ciudad_estado' =>'Hialeah, Florida.',
      'telefono' => '(+1) 352 275 5507',
      'nota' => 'Es importante que envíes tus paquetes con tu primer nombre y primer apellido seguido de la palabra "fox", ya que, esto nos permitirá clasificarlos mejor.'
    ];

    $data_response['message'] = "Dirección cargada con éxito.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }


  public function cambiarcontrasena_options(){return;}
  public function cambiarcontrasena_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $data = $this->post();

    if(!isset($data['new_password']) || trim($data['new_password']) ==""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar una contraseña nueva.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['password']) || trim($data['password']) ==""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar la contraseña actual.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['conf_passwd']) || trim($data['conf_passwd']) ==""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe confirmar la contraseña.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(trim($data['new_password']) != trim($data['conf_passwd'])){
      $data_response['success'] = false;
      $data_response['message'] = 'Las contraseñas no coinciden.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }


    $objUser = $this->GstToken->getUserToken();
    
    if(!$this->GstApi->validarPasswdById($objUser->id,$data['password'])){
      $data_response['success'] = false;
      $data_response['message'] = 'Contraseña incorrecta.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!$this->GstApi->cambiarPasswd($objUser->id,$data['new_password'])){
      $data_response['success'] = false;
      $data_response['message'] = 'No se pudo actualizar la contraseña.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $data_response['message'] = "La contraseña se ha cambiado con éxito.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function cambiaremail_options(){return;}
  public function cambiaremail_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $data = $this->post();

    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || trim($data['email']) ==""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar un correo electrónico válido.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!isset($data['password']) || trim($data['password']) ==""){
      $data_response['success'] = false;
      $data_response['message'] = 'Debe ingresar la contraseña actual.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstToken->getUserToken();
    
    if(!$this->GstApi->validarPasswdById($objUser->id,trim($data['password']))){
      $data_response['success'] = false;
      $data_response['message'] = 'Contraseña incorrecta.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!$this->GstApi->cambiarEmail($objUser->id,trim($data['email']))){
      $data_response['success'] = false;
      $data_response['message'] = 'No se pudo actualizar el correo electrónico.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $data_response['message'] = "El correo electrónico se ha cambiado con éxito.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }

  public function cambiaravatar_options(){return;}
  public function cambiaravatar_post()
  {
      $data_response['success'] = true;
      $data_response['message'] = "";
      $data_response['data'] = [];

      if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      $data = $this->post();
      
      $objUser = $this->GstToken->getUserToken();
      $nombre_img = $this->GstApi->uploadAvatar($objUser->id,$data['avatar']);

      if($nombre_img === false){
        $data_response['success'] = false;
        $data_response['message'] = 'No se pudo actualizar la imagen de perfil.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if (!$this->GstApi->setAvatar($objUser->id, $nombre_img)) {
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo actualizat la foto de perfil.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
      }

      $data_response['message'] = 'La foto de perfil se ha actualizado con éxito.';
      $this->response($data_response,RestController::HTTP_OK);
      return;

  }
  
  public function updperfil_options(){return;}
  public function updperfil_post()
  {
      $data_response['success'] = true;
      $data_response['message'] = "";
      $data_response['data'] = [];

      if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      $data = $this->post();
      
      if(!isset($data['pais']) || trim($data['pais'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo país es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
  
      if(!isset($data['ciudad']) || trim($data['ciudad'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo ciudad es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!isset($data['direccion']) || trim($data['direccion'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo dirección es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
  
      if(!isset($data['telefono']) || trim($data['telefono'])=="" || !is_numeric($data['telefono'])){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo teléfono es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      $data['telefono'] = trim($data['telefono']);
      $data['ciudad'] = trim($data['ciudad']);
      $data['pais'] = trim($data['pais']);
      $data['direccion'] = trim($data['direccion']);

      $objUser = $this->GstToken->getUserToken();
      
      if (!$this->GstApi->setPerfil($objUser->id, $data)) {
        $data_response['success'] = false;
        $data_response['message'] = 'No se pudo actualizar el perfil.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      $data_response['message'] = 'Tu perfil se ha actualizado con éxito.';
      $this->response($data_response,RestController::HTTP_OK);
      return;

  }

  public function calculadora_options(){return;}
  public function calculadora_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'][''];

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $data = $this->post();
    
    if(!isset($data['peso']) || trim($data['peso'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo peso es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if ($data["peso"] <= 0 || !is_numeric(trim($data["peso"]))) {
        $data["peso"] = 1;
    }

    if(!isset($data['valor']) || trim($data['valor'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo valor es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if ($data["valor"] <= 0 || !is_numeric(trim($data["valor"]))) {
        $data["valor"] = 50;
    }

    $data['peso'] = trim($data['peso']);
    $data['valor'] = trim($data['valor']);
    $data['seguro'] = trim($data['seguro']);
    $data['tecnologia'] = trim($data['tecnologia']);

    $valor_paquete_cop = $data['valor'] * $this->GstApi->getTrm();
    $tarifas = $this->Gstuser->getValorTarifa($data['peso']);

    $valor_flete = round($data['peso'] * $tarifas['valor_tarifa']);
    $valor_seguro = 0;
    
    $objConfig = $this->GstApi->getConfig();

    if ($data['valor'] > 200) {
        $valor_flete = round($objConfig->tarifa_manejo * $objConfig->trm)*$data['peso'];
        $tarifas['tarifa'] = $objConfig->tarifa_manejo;
    }
    
    if ($data['tecnologia'] == 1) {
        $valor_flete = round($objConfig->tarifa_4 * $objConfig->trm);
        $tarifas['tarifa'] = $objConfig->tarifa_4;
    }

    if ($data['tecnologia'] == 2) {
        $valor_flete = round($objConfig->tarifa_5 * $objConfig->trm);
        $tarifas['tarifa'] = $objConfig->tarifa_5;
    }

    if ($data['seguro'] == 1 && $data['valor'] > $objConfig->seguro_min-1) {
        $valor_seguro = round($valor_paquete_cop * $objConfig->seguro_opcional)/100;
    }

    if ($data['valor'] >= $objConfig->seguro_max+1 || $data['tecnologia'] == 3) {
        $valor_seguro = round($valor_paquete_cop * $objConfig->seguro_obligatorio)/100;
    }

    $valor_total = $valor_flete + $valor_seguro;

    $data_response['data']['valor_total'] = "$ " . number_format($valor_total, 0, ',', '.') . " COP";
    $data_response['data']['valor_flete'] = "$ " . number_format($valor_flete, 0, ',', '.') . " COP";
    $data_response['data']['valor_seguro'] = "$ " . number_format($valor_seguro, 0, ',', '.') . " COP";
    $data_response['data']['tarifa'] = "$ " . $tarifas['tarifa'] . " USD";
    $data_response['data']['libra_fraccion'] = $data['peso'] . " Lb";


    $data_response['message'] = 'Esta cotización no incluye domicilio, ni envío nacional, estos se liquidan por separado.';
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }
  public function cerrarsesion_options(){return;}
  public function cerrarsesion_get()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstToken->getUserToken();

    if(!$this->GstApi->setAuthAppClose($objUser->id)){
      $data_response['success'] = false;
      $data_response['message'] = 'Un error al cerrar la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    

    $data_response['message'] = "Se cerró sesión.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }
  public function checksession_options(){return;}
  public function checksession_post()
  {
    $data = $this->post();
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'] = [];
    
    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if($data['device'] == ""){
      $data_response['success'] = false;
      $data_response['message'] = 'Device vacio';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    $objUser = $this->GstToken->getUserToken();

    if(!$this->GstApi->setDeviceAuthApp($objUser->id,$data['device'])){
      $data_response['success'] = false;
      $data_response['message'] = 'No se pudo verificar la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    

    $data_response['message'] = "Se cerró sesión.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

  }
  public function cupon_options(){return;}
  public function cupon_post()
  {
    $data_response['success'] = true;
    $data_response['message'] = "";
    $data_response['data'][''];

    if(!$this->GstToken->validateToken()){
      $data_response['success'] = false;
      $data_response['message'] = 'Por favor cierra la sesión.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }
    
    $data = $this->post();

    if(!isset($data['cupon']) || trim($data['cupon'])==""){
      $data_response['success'] = false;
      $data_response['message'] = 'El campo cupón es obligatorio.';
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }

    if(!$this->GstCupones->asignarCupon($data['cupon'])){
      $data_response['success'] = false;
      $data_response['message'] = $this->session->flashdata('error-pre-alerta');
      $this->response($data_response,RestController::HTTP_OK);
      return;
    }


    $data_response['message'] = "Se asignó el cupon a tu orden.";
    $this->response($data_response,RestController::HTTP_OK);
    return;

    }

    public function getBanks_options(){return;}
    public function getBanks_get()
    {
      $data_response['success'] = true;
      $data_response['message'] = "";
      $data_response['data'] = [];

      if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!$this->GstEpayco->getBancos()){
        $data_response['success'] = false;
        $data_response['message'] = 'Error con la carga de bancos.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

    $data_response['message'] = "Se cargó los bancos con éxito";
    $data_response['data']=$this->GstEpayco->getBancos();
    $this->response($data_response,RestController::HTTP_OK);
    return;

    }

    public function pse_transaction_options(){return;}
    public function pse_transaction_post()
    {
      $data_response['success'] = true;
      $data_response['message'] = "";
  
      if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      } 
      
      $data = $this->post();

      if(!$data|| $data==""){
        $data_response['success'] = false;
        $data_response['message'] = 'Complete el formulario.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
  
      if(!isset($data['bank']) || trim($data['bank'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo banco es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!isset($data['invoice']) || trim($data['invoice'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo factura es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!isset($data['value']) || trim($data['value'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo valor es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!isset($data['type_person']) || trim($data['type_person'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo tipo de persona es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      if(!isset($data['doc_type']) || trim($data['doc_type'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo tipo de documento es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if(!isset($data['doc_number']) || trim($data['doc_number'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo documento es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if(!isset($data['name']) || trim($data['name'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo nombre es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
     
      if(!isset($data['last_name']) || trim($data['last_name'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo nombre es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if(!isset($data['email']) || trim($data['email'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo correo electrónico es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if(!isset($data['cell_phone']) || trim($data['cell_phone'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo correo electrónico es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      if(!isset($data['ip']) || trim($data['ip'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo ip es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      if(!isset($data['orden_id']) || trim($data['orden_id'])==""){
        $data_response['success'] = false;
        $data_response['message'] = 'El campo orden id es obligatorio.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
      
      $data_response['pse'] = $this->GstEpayco->pse($data);
      
      if(!$data_response['pse']){
        $data_response['success'] = false;
        $data_response['message'] = "Hay un error en la transacción, por favor inténtalo nuevamente.";
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }
  
  
      $data_response['message'] = "Redireccionando al banco...";
      $this->response($data_response,RestController::HTTP_OK);
      return;
  
      }

      public function getOrden_options(){return;}
      public function getOrden_post()
      {
         $data_response['success'] = true;
         $data_response['message'] = "";
    
         if(!$this->GstToken->validateToken()){
            $data_response['success'] = false;
            $data_response['message'] = 'Por favor cierra la sesión.';
            $this->response($data_response,RestController::HTTP_OK);
            return;
          }

          $data = $this->post();

          if(!isset($data['id']) || trim($data['id'])==""){
            $data_response['success'] = false;
            $data_response['message'] = 'El id de la orden es obligatorio.';
            $data_response[''];
            $this->response($data_response,RestController::HTTP_OK);
            return;
          }
            $objOrden=$this->GstOrdenes->getOrden($data['id']); 
          if(!$objOrden){
            $data_response['success'] = false;
            $data_response['message'] = 'Hay un error en la información.';
            $this->response($data_response,RestController::HTTP_OK);
            return;
          }

          $data_response['data'] = $this->GstEpayco->calcularComision($objOrden);
          $data_response['message'] = "Se cargaron los datos de la orden con éxito";
          $this->response($data_response,RestController::HTTP_OK);
          return;
        

      }

      public function card_options(){return;}
      public function card_post()
      {
        $data_response['success'] = true;
        $data_response['message'] = "";
        $data_response['url_response']="";

        if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $objCliente=$this->GstToken->getUserToken(); 
        $data = $this->post();


        if(!isset($data['email']) || trim($data['email'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El email es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['mask']) || trim($data['mask'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El mask es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['exp_year']) || trim($data['exp_year'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El year_exp es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        } 

        if(!isset($data['exp_month']) || trim($data['exp_month'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El month_exp es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }
        
        if(!isset($data['cvv']) || trim($data['cvv'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El cvv es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['defecto']) || trim($data['defecto'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El defecto es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['ip']) || trim($data['ip'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El ip es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }
        
        if(!isset($data['cuotas']) || trim($data['cuotas'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El cuotas es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['valor_total']) || trim($data['valor_total'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El valor_total es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }


        if(!isset($data['nombre']) || trim($data['nombre'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El nombre es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['ordenId']) || trim($data['ordenId'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El ordenId es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $card=[
          'email'=>$data['email'],
          'mask'=>$data['mask'],
          'exp_year'=>$data['exp_year'],
          'exp_month'=>$data['exp_month'],
          'cvv'=>$data['cvv'],
          'defecto'=>$data['defecto'],
          'ip'=>$data['ip'],
          'cuotas'=>$data['cuotas'],
          'valor_total'=>$data['valor_total'],
          'nombre'=>$data['nombre'],
          'ordenId'=>$data['ordenId']
        ]; 

         if(!$this->GstEpayco->card($card,$objCliente)){
          $data_response['success'] = true;
          $data_response['message'] = $this->session->flashdata('msgError');
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }
        
        $data_response['success'] = true;
        $data_response['message'] = "Pago realizado con éxito";
        $data_response['url_response']=base_url("epayco/response");
        $this->response($data_response,RestController::HTTP_OK);
        return;
        
      }

      public function checkCardDefault_options(){return;}
      public function checkCardDefault_get()
      {
        $data_response['success'] = true;
        $data_response['message'] = "";
        $data_response['data'] = "";
        
      if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $objCliente=$this->GstToken->getUserToken();  

       
         $user_id=$objCliente->id;  
 
         $cards = $this->GstEpayco->getCards($user_id);
         $card_default = $this->GstEpayco->getCardDefault($user_id);
 
        if(!$card_default){
          $data_response['success'] = false;
          $data_response['message'] = 'No tiene tarjetas almacenadas.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $data_response['success'] = true;
        $data_response['message'] = 'Tiene tarjeta almacenadas';
        $data_response['data'] = $cards;
        $data_response['default'] = $card_default;
        
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      public function payWithStoredCard_options(){return;}
      public function payWithStoredCard_post()
      {
        $data_response['success'] = true;
        $data_response['message'] = "";
        $data_response['url_response']="";
        
      if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $objCliente=$this->GstToken->getUserToken(); 
 
         $data = $this->post();

         if(!isset($data['card_id']) || trim($data['card_id'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El card_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['ordenId']) || trim($data['ordenId'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El orden_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['cuota']) || trim($data['cuota'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El cuota es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['valor_total']) || trim($data['valor_total'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El valor_total es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        } 

        $info_card=$this->GstEpayco->getCard($data['card_id']);

        $token=$info_card->token;

        $card=[
          "nombre"=>$info_card->nombre,
          "ordenId"=>$data['ordenId'],
          "cuotas"=>$data['cuota'],
          "valor_total"=>$data['valor_total'],
        ];

        $response=$this->GstEpayco->cardTransaccion($card,$token,$objCliente);

        if(!$response['response']!=1){
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo realizar el pago, inténtalo de nuevo.';
          $this->response($data_response,RestController::HTTP_OK);
        }

        $data_response['success'] = true;
        $data_response['message'] = 'Pago se realizó con exito';
        $data_response['url_response']=base_url("epayco/response");
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }

      public function changeDefaultCard_options(){return;}
      public function changeDefaultCard_post()
      {
        $data_response['success'] = true;
        $data_response['message'] = "";
        
         if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        } 

        $objCliente=$this->GstToken->getUserToken(); 

         $data = $this->post();

         if(!isset($data['card_id']) || trim($data['card_id'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El card_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

      

        if(!$this->GstEpayco->changeCardDefault($data['card_id'],$objCliente->id)){
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo efectuar el cambio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $data_response['success'] = true;
        $data_response['message'] = 'Se realizó el cambio con éxito';
        $this->response($data_response,RestController::HTTP_OK);
        return;
      }


      public function getTransportadorasYTiendas_options(){return;}
      public function getTransportadorasYTiendas_get(){

        $data_response['success'] = true;
        $data_response['message'] = "";
        $data_response['data']="";
        
          if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        } 

        $datos=$this->GstApi->getTransportesYTiendas();

        if(!$datos){
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo obtener la información.';
          $this->response($data_response,RestController::HTTP_OK);
        }


        $data_response['success'] = true;
        $data_response['message'] = 'Se realizó el cambio con éxito';
        $data_response['data']=$datos;

        $this->response($data_response,RestController::HTTP_OK);
        return;

      }

      public function getPrealerta_options(){return;}
      public function getPrealerta_post()
      {

        $data_response['success'] = true;
        $data_response['message'] = "";
        $data_response['data']="";
        
        if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }  
 
        $data = $this->post();

        if(!isset($data['articulo_id']) || trim($data['articulo_id'])==""){
         $data_response['success'] = false;
         $data_response['message'] = 'El articulo_id es obligatorio.';
         $this->response($data_response,RestController::HTTP_OK);
         return;
       }

       $objPaquete = $this->GstApi->getPrealerta($data['articulo_id']);

       if(!$objPaquete){
        $data_response['success'] = false;
        $data_response['message'] = 'No se pudo obtener la información.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
       }

       $objPrealerta['tracking']=$objPaquete->id_track;
       $objPrealerta['valor']=$objPaquete->valor_paquete;
       $objPrealerta['seguro']=$objPaquete->seguro;
       $objPrealerta['factura']=$objPaquete->factura;

        if($objPrealerta['factura']==""){
          $data_response['success'] = true;
          $data_response['message'] = 'Se realizó la consulta con éxito';
          $data_response['data']=$objPrealerta;
          $data_response['data']['base64']="";
          $data_response['data']['base64_imagen']=""; 
          $this->response($data_response,RestController::HTTP_OK);
          return;
      }

      $path = $objPaquete->factura;
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $base64_imagen = 'data:image/' . $type . ';base64,' . base64_encode($data);
      $base64=base64_encode($data);
      
      $data_response['success'] = true;
      $data_response['message'] = 'Se realizó la consulta con éxito';
      $data_response['data']=$objPrealerta;
      $data_response['data']['base64']=$base64;
      $data_response['data']['base64_imagen']=$base64_imagen; 

      $this->response($data_response,RestController::HTTP_OK);
      return;

      }

      public function deletePrealerta_options(){return;}
      public function deletePrealerta_post()
      {

        $data_response['success'] = true;
        $data_response['message'] = "";
        
          if(!$this->GstToken->validateToken()){
          $data_response['success'] = false;
          $data_response['message'] = 'Por favor cierra la sesión.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        } 
  
        $data = $this->post();

        if(!isset($data['articulo_id']) || trim($data['articulo_id'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El articulo_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }


        if(!$this->Gstuser->eliminarPrealerta($data['articulo_id'])){
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo eliminar la prealerta.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $data_response['success'] = true;
        $data_response['message'] = 'Se elimino la prealerta con éxito';
        $this->response($data_response,RestController::HTTP_OK);
        return;

      }

      public function updatePrealerta_options(){return;}
      public function updatePrealerta_post(){

        $data_response['success'] = true;
        $data_response['message'] = "";
        $objConfig = $this->GstApi->getConfig();
        
        if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
        } 

        $objCliente = $this->GstToken->getUserToken(); 
  
        $data = $this->post();

        if(!isset($data['articulo_id']) || trim($data['articulo_id'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El articulo_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['id_track']) || trim($data['id_track'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El id_track es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['seguro']) || trim($data['seguro'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El seguro es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!isset($data['valor_paquete']) || trim($data['valor_paquete'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El valor_paquete es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        //no aplica seguro si el articulo es menor o igual a min usd
        if ($data['valor_paquete'] <= $objConfig->seguro_min-1) {
            $data['seguro'] = "no";
        }

        /*aplica seguro opcional si selecciona si siempre y cuando el paquete
        *sea declarado entre min y max usd
        */
        if ($data['seguro'] == "si" && $data['valor_paquete'] >= $objConfig->seguro_min-1 && $data['valor_paquete'] <= $objConfig->seguro_max) {
            $data['seguro'] = "si";
        }

        //aplica seguro obligatorio siempre y cuando el paquete sea declarado por +500 usd
        if ($data['valor_paquete'] >= $objConfig->seguro_max+1) {
            $data['seguro'] = "si";
        }
       
       
        $prealerta['articulo_id']= trim($data['articulo_id']);
        $prealerta['id_track']= trim($data['id_track']);
        $prealerta['seguro']= trim($data['seguro']);
        $prealerta['valor_paquete']= trim($data['valor_paquete']);
        $prealerta['factura']= trim($data['factura']);

        if($prealerta['factura']!=null){
          $name_factura = $this->GstApi->uploadComprobante($objCliente->id,$prealerta['factura']);
          

          if(!$name_factura){
            $data_response['success'] = false;
            $data_response['message'] = $this->session->flashdata('msgError');
            $this->response($data_response,RestController::HTTP_OK);
            return;

          }        
          $prealerta['factura_path'] = base_url()."uploads/comprobantes/$objCliente->id/$name_factura";  
        }

        if(!$this->Gstuser->updPrealerta($prealerta)){
          $data_response['success'] = false;
          $data_response['message'] = 'Error al modificar la prealerta.';
          $this->response($data_response,RestController::HTTP_OK);
        }
       
        $prealerta['nombre_cliente'] = $objCliente->primer_nombre;
        $msg = $this->load->view('user/emailPrealerta', $prealerta, true);

        $this->ConfigEmail->to($objCliente->email);
        $this->ConfigEmail->subject('Tu prealerta');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->send();

      //01072020 Nupan-Metodo para enviar datos a otra plataforma 
      if($this->GstApiAgencia->getAgenciaId()!=NULL){
        $this->GstApiAgencia->updatePrealerta($prealerta);
      }

      $data_response['success'] = true;
      $data_response['message'] = 'La prealerta se actualizó exitosamente.';
      $this->response($data_response,RestController::HTTP_OK);
      return;

      }


      public function deleteCreditCard_options(){return;}
      public function deleteCreditCard_post(){

        $data_response['success'] = true;
        $data_response['message'] = "";
        
         if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
        } 

        $data = $this->post();

        if(!isset($data['card_id']) || trim($data['card_id'])==""){
          $data_response['success'] = false;
          $data_response['message'] = 'El card_id es obligatorio.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        if(!$this->GstApi->deleteCard($data['card_id'])){
          $data_response['success'] = false;
          $data_response['message'] = 'No se pudo eliminar la tarjeta de crédito.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        $data_response['success'] = true;
        $data_response['message'] = 'Se eliminó la tarjeta de crédito exitosamente.';
        $this->response($data_response,RestController::HTTP_OK);
        return;

      }
      
      public function buscarPaquete_options(){return;}
      public function buscarPaquete_post(){

        $estados = ['Prealertado'=>'0.20','Recibido y viajando'=>'0.5','En Cali'=>'0.75','Disponible'=>'0.9','Orden'=>'0.9','En tus manos'=>'1'];
        $data_response['success'] = true;
        $data_response['message'] = "";
        
        if(!$this->GstToken->validateToken()){
        $data_response['success'] = false;
        $data_response['message'] = 'Por favor cierra la sesión.';
        $this->response($data_response,RestController::HTTP_OK);
        return;
        }  

         $objCliente = $this->GstToken->getUserToken(); 
         $data = $this->post();

        $result=$this->Gstuser->ConsulPaquetes($objCliente->id,$data['tracking']);

        if(count($result)==0){
          $data_response['success'] = false;
          $data_response['message'] = 'No se obtuvo resultado del tracking.';
          $this->response($data_response,RestController::HTTP_OK);
          return;
        }

        foreach ($result as $paquete) {
          $data_response['data'][] = [
            "articulo_id"=>$paquete->articulo_id,
            "nombre" => $paquete->nombre,
            "id_track" => $paquete->id_track,
            "fecha" => date('m/d/Y',$paquete->fecha_registro),
            "estadoArticulo" => $paquete->estadoArticulo,
            "porcentaje" => $estados[$paquete->estadoArticulo],
            "seguro" => $paquete->seguro,
          ]; 
        }
        $data_response['success'] = true;
        $data_response['message'] = 'Se encontró la información del tracking';
        $this->response($data_response,RestController::HTTP_OK);
        return;

      }



}