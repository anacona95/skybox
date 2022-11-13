<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GstApi extends CI_Model
{
   
    public function __construct()
    {
        parent::__construct();
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('GstOrdenes');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McCards');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->modelORM('McAbonosOrdenes');
        $this->load->modelORM('McArticulosOrdenes');
        $this->load->modelORM('McPagosOrdenesComprobantes');

        
        
    }


    public function getUserEmail($email){
        return  McCliente::where('email', trim($email))->first();
    }

    public function getUserById($id){
        return  McCliente::find($id);
    }

    public function validateLogin($user,$pass){
        
        $email = trim($user);
        $password = trim($pass);

        $pass = $this->GstRegistro->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];

        $ObjCliente = $this->McCliente->where('email', $email)->first();
        if(!$ObjCliente){
            return false;
        }
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];
        if ($passDb != $truePass) {
            return false;
        }

        $objCliente = $this->McPuntos->where('user_id', $ObjCliente->id)->first();

        if (count($objCliente) > 0) {
            return true;
        }
        $datos = array(
            'cantidad' => '0',
            'utilizados' => '0',
            'user_id' => $ObjCliente->id,
        );

        $this->db->insert('mc_puntos_user', $datos);

        return true;
    }

    public function getTrm(){
        $this->load->modelORM('McConfig');
        $objMcConfig = McConfig::find(1);
        return $objMcConfig->trm;
    }

    public function getConfig(){
        $this->load->modelORM('McConfig');
        return McConfig::find(1);
    }

    public function saveRegistro($data){
        
        $McCliente = new McCliente;

        $McCliente->parent_id = $data['parent_id'];
        $McCliente->estado = 1;
        $McCliente->fecha_creacion = date('Y-m-d H:i:s');
        $McCliente->pais = $data['pais'];
        $McCliente->email = $data['email'];
        $McCliente->ciudad = $data['ciudad'] . ", " . $data['departamento'];
        $McCliente->password = $data['password'];
        $McCliente->telefono = $data['telefono'];
        $McCliente->apellidos = $data['apellidos'];
        $McCliente->direccion = $data['direccion'];
        $McCliente->primer_nombre = $data['nombre'];
        $McCliente->segundo_nombre = $data['nombre2'];
        $McCliente->descripcions = $data['descripcion'];
        $McCliente->fecha_nacimiento = $data['nacimiento'];
        $McCliente->num_documento = $data['identificacion'];
        $McCliente->cupon_id = $data['cupon'];
        $McCliente->imagen = 'user.png';

        if (!$McCliente->save()) {
            return false;
        }

        $datos = array(
            'id' => $McCliente->id,
            'primer_nombre' => $McCliente->primer_nombre,
            'segundo_nombre' => $McCliente->segundo_nombre,
            'apellidos' => $McCliente->apellidos,
            'email' => $McCliente->email,
        );

        $this->GstRegistro->apiMailer($datos);

        return true;


    }

    public function updatePasswd($email,$passwd)
    {
        $pass = $this->GstRegistro->genPass($passwd);
        $objCliente = $this->getUserEmail($email);

        $objCliente->password = $pass;

        if(!$objCliente->save()){
            return false;
        }

        return true;
    }

    public function getPaquetesPaginate($user_id, $page=1)
    {
        $estados = ['eliminar','En tus manos','Disponible','Abandonado'];

        $objArticulos = McArticulos::where('user_id', $user_id)
                        ->whereNotIn("estadoArticulo",$estados)
                        ->where("estadoArticulo","!=","eliminar")
                        ->orderBy('articulo_id','DESC')
                        ->paginate(5,['*'],'page',$page);

        if(count($objArticulos)==0){
            return false;
        }

        return $objArticulos;
        
    }

    public function getCountPaquetes($user_id)
    {
        $count = 0;

        $objArticulos = McArticulos::where('user_id', $user_id)
                        ->where("estadoArticulo","!=","eliminar")
                        ->get();

        if(count($objArticulos)==0){
            return $count;
        }

        return count($objArticulos);
    }

    public function getOrdenesPaginate($user_id, $page=1)
    {
        $objOrdenes = McOrdenesCompras::where('user_id', $user_id)
                        ->orderBy('id','DESC')
                        ->paginate(5,['*'],'page',$page);

        if(count($objOrdenes)==0){
            return false;
        }

        return $objOrdenes;
        
    }

    public function getCountOrdenes($user_id)
    {
        $count = 0;

        $objOrdenes = McOrdenesCompras::where('user_id', $user_id)
                        ->get();

        if(count($objOrdenes)==0){
            return $count;
        }

        return count($objOrdenes);
    }

    public function getOrden($orden_id)
    {
        return  McOrdenesCompras::find($orden_id);
    }

    public function uploadComprobante($user_id,$img64)
    {
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','pdf','PDF','jfif','JFIF'];
        
        if (!file_exists("./uploads/comprobantes/$user_id/")) {
            mkdir("./uploads/comprobantes/$user_id/", 0755, true);
        }
        
        $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
        file_put_contents($temp_file_path, base64_decode($img64));
        $buffer = file_get_contents($temp_file_path);

        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $mime = $finfo->buffer($buffer);
        $format = preg_replace('!\w+/!', '', $mime);

        if (filesize($temp_file_path) == 0) {
            $this->session->set_flashdata("msgError","archivo sin contenido");
            return false;
        }
        
        if (!in_array($format, $types)) {
            $this->session->set_flashdata("msgError","Formato del archivo incorrecto...($mime)");
            return false;
        }

        $name = time().'.'.$format;
        $file_path = "./uploads/comprobantes/$user_id/$name";
        file_put_contents($file_path, base64_decode($img64));
        
        return $name;
        
    }

    public function newPagoComprobante($user_id,$orden_id,$img)
    {
        $objOrdenComprobante = new McPagosOrdenesComprobantes;

        $objOrdenComprobante->imagen = $img;
        $objOrdenComprobante->path = "/uploads/comprobantes/$user_id/";
        $objOrdenComprobante->fecha = time();
        $objOrdenComprobante->orden_id = $orden_id;
        if (!$objOrdenComprobante->save()) {
            return false;
        }

        $objOrden = McOrdenesCompras::find($orden_id);
        $objOrden->estado = "3";
        if (!$objOrden->update()) {
            return false;
        }
        $this->GstOrdenes->setContadorAprobacion();
        return true;

    }

    public function validarPasswdById($user_id,$passwd)
    {
        $objCliente = $this->getUserById($user_id);

        if(!$objCliente){
            return false;
        }

        $pass = $this->GstRegistro->genPass($passwd);
        $parts = explode(':', $pass);
        $truePass = $parts[1];

        $partsDb = explode(':', $objCliente->password);
        $passDb = $partsDb[1];
        if ($passDb != $truePass) {
            return false;
        }

        return true;
    }

    public function cambiarPasswd($user_id,$new_passwd)
    {
        $objCliente = $this->getUserById($user_id);
        
        if(!$objCliente){
            return false;
        }

        $pass = $this->GstRegistro->genPass($new_passwd);
        $objCliente->password = $pass;

        if(!$objCliente->save()){
            return false;
        }

        return true;
        
    }

    public function cambiarEmail($user_id,$email)
    {
        $objCliente = $this->getUserById($user_id);
        $userEmail = $this->getUserEmail($email);
        
        if(!$objCliente){
            return false;
        }

        if($userEmail && ($userEmail->id == $objCliente->id)){
            return true;
        }
        
        $objCliente->email = $email;

        if(!$objCliente->save()){
            return false;
        }

        return true;
        
    }

    public function uploadAvatar($user_id,$img64)
    {
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP'];
        
        $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage');
        file_put_contents($temp_file_path, base64_decode($img64));
        $image_info = getimagesize($temp_file_path);
       
        if (filesize($temp_file_path) == 0) {
            return false;
        }
        
        $format = preg_replace('!\w+/!', '', $image_info['mime']);
        
        if (!in_array($format, $types)) {
            return false;
        }

        $name = $user_id."-".time().'.'.$format;
        $file_path = "./uploads/imagenes/$name";
        file_put_contents($file_path, base64_decode($img64));

        $config['image_library'] = 'gd2';
        $config['source_image'] = 'uploads/imagenes/' . $name;
        $config['create_thumb'] = true;
        $config['maintain_ratio'] = true;
        $config['new_image'] = 'uploads/imagenes/thumbs/';
        $config['thumb_marker'] = ''; //captura_thumb.png
        $config['width'] = 150;
        $config['height'] = 150;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        
        return $name;
        
    }

    public function setAvatar($user_id,$avatar)
    {
        $objCliente = McCliente::find($user_id);
        
        if(!$objCliente){
            return false;
        }

        $objCliente->imagen=$avatar;

        if(!$objCliente->update()){
            return false;
        }

        return true;

    }

    public function setPerfil($user_id,$data)
    {
        $objCliente = $this->getUserById($user_id);

        if(!$objCliente){
            return false;
        }

        $objCliente->pais = $data['pais'];
        $objCliente->ciudad = $data['ciudad'];
        $objCliente->direccion = $data['direccion'];
        $objCliente->telefono = $data['telefono'];

        if(!$objCliente->update()){
            return false;
        }

        return true;
    }
    
    public function setDeviceAuthApp($user_id,$device)
    {
        $objCliente = $this->getUserById($user_id);

        if(!$objCliente){
            return false;
        }

        $objCliente->device=$device;
        $objCliente->auth_app=1;

        if(!$objCliente->update()){
            return false;
        }

        return true;

    }
    public function setAuthAppClose($user_id)
    {
        $objCliente = $this->getUserById($user_id);

        if(!$objCliente){
            return false;
        }

        $objCliente->auth_app=0;

        if(!$objCliente->update()){
            return false;
        }

        return true;

    }

    public function sendMessageApp($msg,$device)
    {
        
        if($device==NULL){
            return false;
        }

        $content = array(
            "en" => $msg
            );
        
        $fields = array(
            'app_id' => "90bbf6f1-a937-48df-b9a9-f6520485b8b4",
            'include_player_ids' => array($device),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
        
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic MDc3YzU3ZjYtM2ZhYy00ZTRjLTgyMjAtMmE2NDRjNzAyODA3'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function getTransportesYTiendas(){

        $data=[
            "transportadoras"=>[
              'USPS',
              'UPS',
              'FEDEX',
              'AMZ LOGISTICS',
              'DHL',
              'OTRA'
            ],
            "tiendas"=>[
                'AMAZON',
                'EBAY',
                'NEWEGG',
                'ALIEXPRESS',
                'ALIBABA',
                'BESTBUY',
                'CARTERS',
                'SEPHORA',
                'WALMART',
                'DISNEY',
                '6PM',
                'OTRA'
            ]
        ];

        return $data;
     
    }

    public function getPrealerta($id)
    {
        return $objPaquete=McArticulos::find($id);
    }

    public function deleteCard($id)
    {
        $objCard = McCards::find($id);

        if(!$objCard->delete()){
            return false;
        }

        return true;
    }

}
