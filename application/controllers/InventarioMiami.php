<?php

class InventarioMiami extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->validateAccess()) {
            redirect('login/close');
        }
        if (!$this->validateRole(1) && !$this->validateRole(3)) {
            $this->session->set_flashdata('msgError', 'No tiene permiso para acceder a la funcionalidad.');
            redirect(base_url(['cuenta-administrador']));
        }
        $this->load->GstClass('GstInventarioMiami');

    }

    public function index()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $data['articulos'] = $this->GstInventarioMiami->getArticulos();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('InventarioMiami/index', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function ingresoPaquetes()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('InventarioMiami/ingresoPaquetes');
        $this->load->view('layout/footerAdmin', $data);
    }

    public function findArticle()
    {
        $tracking = trim($_GET['tracking']);
        $result = $this->GstInventarioMiami->getArticle($tracking);
        echo json_encode($result);
        exit;
    }

    public function ingresoProcess()
    {
        $id = $this->input->post('id');
        $peso = $this->input->post('peso');
        $flete = $this->input->post('flete');
        $user_id = $this->input->post('user_id');
        $this->GstInventarioMiami->ingresarPaquete($id, $peso, $flete, $user_id);
        redirect(base_url(['ingreso-bodega']));
    }

    public function nuevoProcess()
    {
        $this->GstInventarioMiami->crearPaquete();
        $this->session->set_flashdata('msgOk', 'El paquete ha sido ingresado a la bodega con éxito.');
        redirect(base_url(['ingreso-bodega']));
    }
}
