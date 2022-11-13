<?php

class Dashboard extends CI_Controller
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
        $this->load->GstClass('GstDashboard');

    }

    public function index()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['cartera'] = $this->GstDashboard->getCartera();
        $datos['cartera'] = $datos['cartera'] -  $this->GstDashboard->getabonado();
        $datos['clientes'] = $this->GstDashboard->getCountClientes();
        $datos['clientes_hoy'] = $this->GstDashboard->getCountClientesToday();
        $datos['data'] = $this->GstDashboard->getDataGraph();
        $datos['pie'] = $this->GstDashboard->getPaquetesProcess();
        $datos['ordenes_activas'] = $this->GstDashboard->getOrdenesActivas();
        $datos['paquetes_process'] = $datos["pie"]['prealertados'] + $datos["pie"]['miami'] + $datos["pie"]['viajando'] +$datos["pie"]['cali'] + $datos["pie"]['orden'];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('Dashboard/index', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }
}
