<?php

class AdminUsers extends CI_Controller
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
        $this->load->GstClass('GstAdminUsers');
    }

    public function index()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $data['users'] = $this->GstAdminUsers->getUsers();
        $data['roles'] = $this->GstAdminUsers->getRoles();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('AdminUsers/index', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    public function createProcess()
    {
        $this->GstAdminUsers->createUser($this->input->post('usuario'));
        redirect(base_url(['administrar-usuarios']));
    }

    public function updateProcess()
    {
        $this->GstAdminUsers->updateUser($this->input->post('usuario'));
        redirect(base_url(['administrar-usuarios']));
    }

    public function updatePassProcess()
    {
        $this->GstAdminUsers->updatePassword($this->input->post('usuario'));
        redirect(base_url(['administrar-usuarios']));
    }

    public function deleteProcess()
    {
        $id = $_GET['id'];
        $this->GstAdminUsers->deleteUser($id);
        redirect(base_url(['administrar-usuarios']));
    }
}
