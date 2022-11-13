<?php

class AdmLogin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McAdministrador');
        $this->load->GstClass('GstLoginAdmin');
    }

    /**
     * funcion de logueo de clientes
     */
    public function index()
    {
        unset($_SESSION['registro']);
        $this->load->view('loginAdmin/index');
    }

    /**
     * Funcion que valida los parametros de entrada para el inicio de sesion
     */
    public function process()
    {
        $result = $this->GstLoginAdmin->validateLogin();

        if (!$result) {
            $this->session->set_flashdata('error', 'Usuario o contraseña incorrecto');
            redirect('AdmLogin');
        }
        if ($this->validateRole(3)) {
            redirect(base_url('inventario-miami'));
        }
        if ($this->validateRole(1)) {
            redirect(base_url('reportes-y-metricas'));
        }
        redirect(base_url('tracking'));

    }

    public function close()
    {
        $this->session->sess_destroy();
        redirect('AdmLogin');
    }

}
