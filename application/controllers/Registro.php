<?php

class Registro extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McConfig');
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('ConfigEmail');
    }

    public function index()
    {
        $this->load->view('registro/index');
    }

    public function process()
    {
        $_SESSION['registro'] = $this->input->post('data');
        
        $gRecaptchaResponse = $this->input->post('recaptcha_response');
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $secret = "6Lek7rkUAAAAAIbI0b_DPkS1vOSpbY6g1Pia76C8";
        
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        
        $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
        
        if (!$resp->isSuccess()) {
            $this->session->set_flashdata('error', 'No se pudo crear el registro por favor intente de nuevo');
            redirect('registro');
        }

        $result = $this->GstRegistro->validarRegistro();
        
        if (!$result) {
            redirect('registro');
        }

        $datos = $this->GstRegistro->saveRegistro();
        
        if (!$datos) {
            $this->session->set_flashdata('error', 'No se pudo crear el registro por favor intente de nuevo');
            redirect('registro');
        }
        
        unset($_SESSION['registro']);
        
        $objConfig = McConfig::find(1);

        if($objConfig->api_mailerlite==NULL){

            $form = $this->input->post('data');
            $data['cliente'] = $this->GstAdmin->infoClienteEmail($datos['id']);
            $data['passwd'] = $form['password'];
    
            $msg = $this->load->view('registro/email', $data, true);
            $this->ConfigEmail->to($data['cliente']->email);
            $this->ConfigEmail->subject('Registro');
            $this->ConfigEmail->message($msg);
            $this->ConfigEmail->send();
            
        }else{
            $this->GstRegistro->apiMailer($datos);
        }

        $this->session->set_flashdata('email', 'Gracias por registrarse en nuestra plataforma, se ha enviado un email informativo a su correo electrónico. Por favor inicie sesión.');
        redirect('login');
    }

}
