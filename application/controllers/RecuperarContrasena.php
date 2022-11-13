<?php

class RecuperarContrasena extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('GstContrasena');
        $this->load->GstClass('ConfigEmail');
    }

    public function index()
    {
        $this->load->view('ReContrasena/index');
    }

    public function process()
    {
        $result = $this->GstContrasena->validarRegistro();
        if (!$result) {
            redirect('RecuperarContrasena/index');
        }
        $data = $this->input->post('data');
        $email = $data['email'];
        $clave = rand(1000, 99999);
        $response = $this->GstContrasena->saveRegistro($clave, $email);
        if (!$response) {
            $this->session->set_flashdata('errorclave', 'No se pudo crear el registro por favor intente de nuevo');
            redirect('RecuperarContrasena/index');
        }
        $datos = array(
            'clave' => $clave,

        );
        $this->load->library('encryption');
        $msg = $this->load->view('ReContrasena/email', $datos, true);
        $this->ConfigEmail->to($email);
        $this->ConfigEmail->subject('Restablecer contraseña Foxcarga');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->send();
        unset($_SESSION['RecuperarContrasena/index']);
        $this->session->set_flashdata('email', 'Se ha enviado una contraseña provisional al correo electrónico proporcionado.');
        redirect('RecuperarContrasena/index');

    }

}
