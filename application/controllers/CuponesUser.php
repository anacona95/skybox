<?php

class CuponesUser extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->validateCliente()) {
            redirect('login/close');
        }
        $this->load->GstClass('GstCupones');
    }
  

    public function cuponProcess(){

        $nameCupon = trim($this->input->post('cupon'));
        
        if(!$this->GstCupones->asignarCupon($nameCupon)){
            redirect('mis-ordenes');
        }

        $this->session->set_flashdata('msgOk', 'Se aplicó el descuento a la orden activa');
        redirect('mis-ordenes');

    }


}