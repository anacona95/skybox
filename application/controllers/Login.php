<?php

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->GstClass('GstLogin');
        $this->load->GstClass('Gstuser');
        $this->load->GstClass('McConfig');
    }

    /**
     * funcion de logueo de clientes
     */
    public function index() {
        unset($_SESSION['registro']);
        $this->load->view('login/index');
    }

    /**
     * Funcion que valida los parametros de entrada para el inicio de sesion
     */
    public function process() {
        $result = $this->GstLogin->validateLogin();

        if (!$result) {
            
            redirect('login');
        }
        redirect('user');
    }

    public function close() {
        $this->session->sess_destroy();
        redirect('login');
    }

    
    public function calculadora()
    {
        $data['config'] = McConfig::find(1);
        $data['tarifas'] = $this->Gstuser->getTarifas();
        $this->load->view('user/calculadora', $data);
        
    }
    
    public function calcular()
    {
        $this->load->modelORM('McConfig');
        $objConfig = McConfig::find(1);
        $peso = ceil(round(trim($this->input->post('peso')), 1, PHP_ROUND_HALF_DOWN));
        $valor = trim($this->input->post('valor'));
        
        $seguro = trim($this->input->post('seguro'));
        $tecnologia = trim($this->input->post('tecnologia'));

        if ($peso <= 0 || !is_numeric($peso)) {
            $peso = 1;
        }
        if ($valor <= 0 || !is_numeric($valor)) {
            $valor = 1;
        }
        $valor_paquete_cop = $valor * $objConfig->trm;
        $tarifas = $this->Gstuser->getValorTarifa($peso);

        $data['valor_flete'] = round($peso * $tarifas['valor_tarifa']);
        $data['valor_seguro'] = 0;
        $data['valor_envio'] = 0;

        if($valor > 200)
        {
            $data['valor_flete'] = round($objConfig->tarifa_manejo * $objConfig->trm)*$peso;
            $tarifas['tarifa'] = $objConfig->tarifa_manejo;
        }

        if ($tecnologia == 1) {
            $data['valor_flete'] = round($objConfig->tarifa_4 * $objConfig->trm);
            $tarifas['tarifa'] = $objConfig->tarifa_4;
        }

        if ($tecnologia == 2) {
            $data['valor_flete'] = round($objConfig->tarifa_5 * $objConfig->trm);
            $tarifas['tarifa'] = $objConfig->tarifa_5;
        }

        if ($seguro == 1) {
            $data['valor_seguro'] = round(($valor_paquete_cop * $objConfig->seguro_opcional)/100);
        }

        if ($valor >= $objConfig->seguro_max+1 || $tecnologia == 3) {
            $data['valor_seguro'] = round(($valor_paquete_cop * $objConfig->seguro_obligatorio)/100);
        }

        $data['valor_total'] = $data['valor_flete'] + $data['valor_seguro'];

        $data['valor_total'] = "$ " . number_format($data['valor_total'], 0, ',', '.') . " COP";
        $data['valor_flete'] = "$ " . number_format($data['valor_flete'], 0, ',', '.') . " COP";
        $data['valor_seguro'] = "$ " . number_format($data['valor_seguro'], 0, ',', '.') . " COP";
        $data['tarifa'] = "$ " . $tarifas['tarifa'] . " USD";
        $data['libra_fraccion'] = $peso . " Lb";
        echo json_encode($data);

        exit;

    }
}
