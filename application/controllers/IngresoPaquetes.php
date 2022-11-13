<?php

class IngresoPaquetes extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->GstClass('GstApiAgencia');

        if (!$this->validateAccess()) {
            redirect('login/close');
        }
        if (!$this->validateRole(1) && !$this->validateRole(2)) {
            $this->session->set_flashdata('msgError', 'No tiene permiso para acceder a la funcionalidad.');
            redirect(base_url(['cuenta-administrador']));
        }
        $this->load->model('GstIngresoPaquetes');

    }

    public function ingresoPaquetes()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $data['tarifas'] = $this->GstIngresoPaquetes->getTarifas();
        $data['valor_envio'] = $data['tarifas']['tarifa']*$_SESSION['trm']['hoy'];

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('IngresoPaquetes/ingresoPaquetes', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function findArticle()
    {
        $tracking = trim($_GET['tracking']);
        $result = $this->GstIngresoPaquetes->getArticle($tracking);
        echo json_encode($result);
        exit;
    }

    public function ingresoProcess()
    {
        $id = trim($this->input->post('id'));
        $peso = trim($this->input->post('peso'));
        $user_id = trim($this->input->post('user_id'));
        $tecnologia = trim($this->input->post('tecnologia'));        
        $tarifa_especial = trim($this->input->post("tarifa_especial"));
        $cantidad = trim($this->input->post("cantidad"));



        $this->GstIngresoPaquetes->ingresarPaquete($id, $peso, $user_id, $tecnologia,$tarifa_especial, $cantidad);

        if($this->GstApiAgencia->isAgencia($user_id)){

            $articulo_referer_id= $this->GstApiAgencia->getArticuloRefererId($id);
            $this->GstApiAgencia->sendPunteoArticulo($peso, $tecnologia, $cantidad,$articulo_referer_id,$user_id);
        }

        $this->session->set_flashdata("msgOk", "El paquete ha sido ingresado a la bodega con éxito.");

        redirect(base_url(['admin', 'ingreso-paquetes']));
    }
}
