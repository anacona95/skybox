<?php

class ApiAgencia extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->GstClass('GstApiAgencia');
        $this->load->GstClass('Gstuser');
        $this->load->GstClass('GstAdmin');
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('ConfigEmail');
        $this->load->modelORM('McArticulos');

    }

   /**
    * Crea prealerta hecho en la agencia
    */
    public function CrearPrealerta(){

        $agencia_id = $this->input->post('agencia_id');
        $nombre = $this->input->post('nombre');
        $id_track = strtoupper($this->input->post('id_track'));
        $fecha = $this->input->post('fecha');
        $seguro = $this->input->post('seguro');
        $articulo_referer_id= $this->input->post('articulo_id');
        $valorP = $this->input->post('valor_paquete');
        $trans = $this->input->post('transportadora');
        $tienda = $this->input->post('tienda');
        $factura = $this->input->post('factura');

        $prealerta_agencia = [
            'nombre' => $nombre,
            'id_track' => $id_track,
            'fecha' => $fecha,
            'seguro' => $seguro,
            'articulo_referer_id' => $articulo_referer_id,
            'valor_paquete' => $valorP,
            'tipo' => 'envio',
            'estadoArticulo' => 'Prealertado',
            'transportadora' => $trans,
            'user_id'=>$agencia_id,
            'tienda'=>$tienda,
            'factura_path'=>$factura,
        ];

        $this->Gstuser->saveEnvios($prealerta_agencia);

        return;

    }

   /**
    * actualiza prealerta hecho en la agencia
    */
    public function updatePrealerta(){

        $agencia_id = $this->input->post('agencia_id');
        $id_track = strtoupper($this->input->post('id_track'));
        $seguro = $this->input->post('seguro');
        $articulo_referer_id= $this->input->post('articulo_id');
        $valorP = $this->input->post('valor_paquete');
        $factura = $this->input->post('factura');

        $prealerta_agencia = [
            'id_track' => $id_track,
            'seguro' => $seguro,
            'valor_paquete' => $valorP,
            'user_id'=>$agencia_id,
            'factura_path'=>$factura,
            'articulo_id'=>$this->GstApiAgencia->getArticuloIdByReferer($articulo_referer_id,$agencia_id),
        ];

        $this->Gstuser->updPrealerta($prealerta_agencia);

        return;

    } 
    
    /** 
     * Actualiza estado de Prelartado a Recibido y viajando
     * Metodo para agencia sirve para recibir datos post 
    */
    public function setArticuloVuelo(){

        $id_track = $this->input->post('id_track');
        $articulo_referer_id = $this->input->post('articulo_referer_id');
        $nombre = $this->input->post('nombre');

        $this->GstApiAgencia->setArticulo($id_track,$articulo_referer_id,$nombre);
        
        $this->session->set_flashdata('archivo', 'El archivo Recibido y viajando se ha procesado correctamente.');
        return;
    }

    /**
     * Metodo recibe el articulo_referer_id donde almacena al articulo_id de la agencia
     */
    public function setArticuloRefererId(){
        
        $articulo_referer_id= $this->input->post('articulo_referer_id');
        $id_track = $this->input->post('id_track');
        $agencia_id = $this->input->post('agencia_id');
        
        $setArticuloRefererId = [
            'id_track' => $id_track,
            'articulo_referer_id' => $articulo_referer_id,
            'agencia_id'=>$agencia_id
        ];

        $this->GstApiAgencia->setArticuloRefererId($setArticuloRefererId);
    }

    /**
     * Metodo para agencia, recibe datos post para el punteo del articulo
     */

    public function punteoArticulo(){

        $articulo_id = $this->input->post('articulo_referer_id');
        $peso = $this->input->post('peso');
        $tecnologia = $this->input->post('tecnologia');
        $cantidad = $this->input->post('cantidad');
    
        $this->GstApiAgencia->ingresarPaquete($articulo_id, $peso, $tecnologia, $cantidad);
    
    }

    /**
     * Metodo recibe el articulo_referer_id donde almacena al articulo_id de la agencia
     */
    public function setArticuloEstado(){
        
        $estadoArticulo = $this->input->post('estadoArticulo');
        $articulo_referer_id = $this->input->post('articulo_referer_id');
        
        $data = [
            'estadoArticulo' => $estadoArticulo,
            'articulo_referer_id' => $articulo_referer_id,
        ];

        $this->GstApiAgencia->updArticuloEstado($data);
    }
   
}    