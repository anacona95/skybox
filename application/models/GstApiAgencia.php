<?php
use GuzzleHttp\Client;
class GstApiAgencia extends CI_Model {

    public $dominio_padre = "";

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McConfig');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McArticulos');
        $this->load->GstClass('GstIngresoPaquetes');

        $this->dominio_padre = $this->getDominioPadre()."api-agencia/";



    }
   /**
     * Metodo para agencia
     * recibe datos post para crear prealerta
     */
    public function Prealerta($prealerta,$articulo_id){

        $client = new Client();
        $res = $client->request('POST', $this->dominio_padre.'crear-prealerta', [
            'form_params' => ['nombre'=>$prealerta['nombre'],
                            'id_track'=>$prealerta['id_track'],
                            'fecha'=>$prealerta['fecha'],
                            'seguro'=>$prealerta['seguro'],
                            'articulo_id'=>$articulo_id,
                            'valor_paquete'=>$prealerta['valor_paquete'],
                            'transportadora'=>$prealerta['transportadora'],
                            'factura'=>$prealerta['factura'],
                            'tienda'=>$prealerta['tienda'],
                            'tipo'=>'envio',
                            'key'=>'byorderforskybox',//TO-DO 07092020 cdiaz@skylabs.com.co
                            'agencia_id'=>$this->getAgenciaId(),

            ]
        ]);
    
      }
   
      /**
     * Metodo para agencia
     * recibe datos post para actualizar prealerta
     */
    public function updatePrealerta($prealerta){

        $client = new Client();
        $res = $client->request('POST', $this->dominio_padre.'update-prealerta', [
            'form_params' => [
                            'id_track'=>$prealerta['id_track'],
                            'seguro'=>$prealerta['seguro'],
                            'articulo_id'=>$prealerta['articulo_id'],
                            'valor_paquete'=>$prealerta['valor_paquete'],
                            'factura'=>$prealerta['factura_path'],
                            'key'=>'byorderforskybox',//TO-DO 07092020 cdiaz@skylabs.com.co
                            'agencia_id'=>$this->getAgenciaId(),

            ]
        ]);
    
      }
      /**Retorna el articulo_id configurada */
    public function getArticuloId($user_id,$id_track){

        $objArticulo = McArticulos::where('id_track',$id_track)
        ->where('user_id',$user_id)
        ->first();
        return $objArticulo->articulo_id;
    }

      /**Retorna el articulo_id configurada */
    public function getArticuloIdByReferer($articulo_referer_id,$agencia_id){

        $objArticulo = McArticulos::where('articulo_referer_id',$articulo_referer_id)
        ->where('user_id',$agencia_id)
        ->first();
        return $objArticulo->articulo_id;
    }

    //Actualiza prealerta a Recibido y viajando
    public function setArticuloVuelo($objArticulo){

        $user_id = $objArticulo->user_id;

        $dominio_agencia = $this->getDominioAgencia($user_id);

        $client = new Client();
        $res = $client->request('POST', $dominio_agencia.'api-agencia/set-articulo-vuelo', [
            'form_params' => [
                            'nombre'=>$objArticulo->nombre,                           
                            'id_track'=>$objArticulo->id_track,
                            'articulo_referer_id'=>$objArticulo->articulo_referer_id,
                            'key'=>'byorderforskybox',
            ]
        ]);
      
    } 

    /**
     * Metodo para agencia
     * Envia el id_articulo  al padre
     */

    public function sendArticuloRefererId($objArticulo)
    {
        $client = new Client();
        $res = $client->request('POST', $this->dominio_padre.'set-articulo-referer-id', [
            'form_params' => ['articulo_referer_id'=>$objArticulo->articulo_id,
                            'id_track'=>$objArticulo->id_track,
                            'key'=>'byorderforskybox',
                            'agencia_id'=>$this->getAgenciaId(),

            ]
        ]);
    }

    /**
     * Envía datos a la agencia para puntear paquete o articulo
     */
    public function sendPunteoArticulo($peso,$tecnologia,$cantidad,$articulo_referer_id,$user_id){

        $dominio_agencia = $this->getDominioAgencia($user_id);

        $client = new Client();
        $res = $client->request('POST', $dominio_agencia.'api-agencia/punteo-articulo', [
            'form_params' => [                           
                            'peso'=>$peso,
                            'tecnologia'=>$tecnologia,
                            'cantidad'=>$cantidad,
                            'articulo_referer_id'=>$articulo_referer_id
            ]
        ]);
    }

    /**Retorna el id de la agencia configurada */
      public function getAgenciaId(){

        $objConfig = McConfig::find(1);
        return $objConfig->agencia_id;
    }

    /**
     * Retorma el correo de la agencia
     */

    public function getEmailAgencia($id){
         $objClientAgencia= McCliente::find($id);
         return $objClientAgencia->email;
    }
    
    /**
     * Metodo para la agencia
     * Almacena o actualiza un articulo al estado recibo y viajando
     */
    public function setArticulo($id_track,$articulo_referer_id,$nombre){

        $objArticulo= McArticulos::find($articulo_referer_id);

        //se valida que exista el articulo, si no crea uno nuevo Recibido y viajando
        if (!$objArticulo) {
            $objConfig = McConfig::find(1);
            $objArticulo = new McArticulos;
            $objArticulo->nombre = utf8_encode($nombre); //se convierte por msexcel
            $objArticulo->id_track = strtoupper($id_track);
            $objArticulo->user_id =$objConfig->default_client;
            $objArticulo->peso = 0;
            $objArticulo->valor = 0;
            $objArticulo->tipo = "envio";
            $objArticulo->fecha_registro = time();
            $objArticulo->fecha = date('Y-m-d');
            $objArticulo->fecha_reporte = time();
            $objArticulo->fecha_entrega = time() + (11 * 24 * 60 * 60);
            $objArticulo->descripcion = "Artículo no prealertado";
            $objArticulo->seguro = "no";
            $objArticulo->transportadora = "otra";
            $objArticulo->valor_paquete = "0";
            $objArticulo->estadoArticulo = "Recibido y viajando";
            if(!$objArticulo->save()){
                return false;
            }     
            $this->sendArticuloRefererId($objArticulo);

            return true;  
        }

         // si el archivo existe actualiza la informacion
         $objArticulo->fecha_entrega = time() + (11 * 24 * 60 * 60);
         $objArticulo->estadoArticulo = "Recibido y viajando";
         
        if(!$objArticulo->update()){
            return false;
        }
            
        return true;
    }
    
    /**
     * Metodo para agencia
     * Si un articulo es de una agencia, ese articulo va tener un articulo_referer_id que es el id_articulo de la bd 
     * de la agencia
     */
    public function setArticuloRefererId($setArticuloRefererId){

        $objArticulo = McArticulos::where('user_id',$setArticuloRefererId['agencia_id'])
                    ->where('id_track',$setArticuloRefererId['id_track'])
                    ->first();

                    $objArticulo->articulo_referer_id = $setArticuloRefererId['articulo_referer_id'];

                    if(!$objArticulo->update()){
                        return false;
                    }

                    return true;
    }

    /**
     * Valida si un usuario es agencia o no
     */

    public function isAgencia($user_id){
        $objCliente= McCliente::find($user_id);

        if($objCliente->agencia!=1){
            return false;
        }
        return true;
    } 

    /**
     * Retorna el articulo_referer_id de una articulo
     */
    
    public function getArticuloRefererId($articulo_id){

        $objArticulo= McArticulos::find($articulo_id);
        return $objArticulo->articulo_referer_id;
    }

    /**
     * Metodo para agencia
     * Metodo para puntear paquete o articulo desde el padre
     */

    public function ingresarPaquete($articulo_id, $peso, $tecnologia, $cantidad)
    {
        $config = McConfig::find(1);
        $trm = $config->trm;
        $tarifas = $this->GstIngresoPaquetes->getTarifas();
        $tarifa = $tarifas["tarifa"];
        $valor_tarifa = $tarifa * $trm;

        $objArticulo = McArticulos::find($articulo_id);
        
        if (!$objArticulo) {
            return false;
        }
        
        if( $objArticulo->valor_paquete > 200 ){
            $tarifa = $tarifas['tarifa_manejo'];
            $valor_tarifa = $tarifas['tarifa_manejo'] * $trm;
        }

        $objArticulo->trm = $trm;
        $objArticulo->peso = $peso;
        $objArticulo->valor = $valor_tarifa * $peso;
        $objArticulo->estadoArticulo = "En Cali";

        if ($tecnologia == 3 || $objArticulo->valor_paquete >= 500) {
            $objArticulo->seguro = "si";
        }
        if ($tecnologia == 1) {
            $tarifa = $tarifas["tarifa_4"];
            $objArticulo->valor = ($tarifas['tarifa_4'] * $trm) * $cantidad;
        }
        if ($tecnologia == 2) {
            $tarifa = $tarifas["tarifa_5"];
            $objArticulo->valor = ($tarifas['tarifa_5'] * $trm) * $cantidad;
        }
        
        $objArticulo->tarifa = $tarifa;
        $objArticulo->fecha_punteo = time();

        if(!$objArticulo->update()){
            return false;
        }
        return true;
    }

    /**Retorna el id de la agencia configurada */
    public function getDominioPadre(){

        $objConfig = McConfig::find(1);
        return $objConfig->dominio_padre;
    }
    /**Retorna el id de la agencia configurada */
    public function getDominioAgencia($user_id){

        $objCliente = McCliente::find($user_id);
        if($objCliente->agencia!=1){
            return false;
        }
        return $objCliente->dominio;
    }

    //actualiza el paquete a cualquier estado
    public function setArticuloEstado($objArticulo,$estado){

        if(!$objArticulo->articulo_referer_id){
            return false;
        }

        $user_id = $objArticulo->user_id;

        $dominio_agencia = $this->getDominioAgencia($user_id);

        $client = new Client();
        $res = $client->request('POST', $dominio_agencia.'api-agencia/set-articulo-estado', [
            'form_params' => [
                            'estadoArticulo'=>$estado,                           
                            'articulo_referer_id'=>$objArticulo->articulo_referer_id,
                            'key'=>'byorderforskybox',
            ]
        ]);
      
    }

    //Metodo agencia actualiza el estado de un articulo
    public function updArticuloEstado($data){

        $objArticulo= McArticulos::find($data['articulo_referer_id']);
        $objArticulo->estadoArticulo = $data['estadoArticulo'];
        $objArticulo->update();
    }

    public function getArticulo($id)
    {
        return McArticulos::find($id);
    }


   

}