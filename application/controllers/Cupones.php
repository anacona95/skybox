<?php

class Cupones extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->validateAccess()) {
            redirect('AdmLogin/close');
        }
        if (!$this->validateRole(1)) {
            $this->session->set_flashdata('msgError', 'No tiene permiso para acceder a la funcionalidad.');
            redirect(base_url(['cuenta-administrador']));
        }
        $this->load->GstClass('GstCupones');
        

    }

    public function index()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];
        $data['cupones'] = $this->GstCupones->getAll();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('Cupones/index');
        $this->load->view('layout/footerAdmin');
    }

    public function createProcess()
    {
        $this->GstCupones->crearCupon($this->input->post('data'));
        redirect(base_url(['cupones']));
    }



   

 

}
