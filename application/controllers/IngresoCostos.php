<?php

class IngresoCostos extends CI_Controller
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
         //pagination
        $this->load->library('pagination'); 
        $this->load->GstClass('GstCostos');
        $this->load->model('McCostos');
        

    }

    public function index()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        
        $q = null;
        
        if (isset($_GET['q'])) {
            $q = trim($_GET['q']);
        }
       
        $config = [
            'base_url' => base_url('ingreso-costos'),
            'per_page' => 50,
            'total_rows' =>count($this->GstCostos->getPendientes($q)),
        ];

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tagl_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['first_link'] = 'Primero';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $data['costos'] = $this->GstCostos->getPendientes($q, $config['per_page'], $this->uri->segment(2));
        $data['pendiente_x_pagar'] = $this->GstCostos->getTotalPendiente();
      
        $data['costo_model'] = new McCostos;
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('IngresoCostos/index');
        $this->load->view('layout/footerAdmin');
    }

    public function costoProcess()
    {

        $factura = $_FILES['factura'];
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','PDF','pdf'];
        $tipo = explode('.', $factura['name']);
        $data = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');

        if($factura['name']!= ""){
            if ($factura['size'] == 0) {
                $this->session->set_flashdata('msgError', 'La factura es requerida.');
                redirect(base_url(['ingreso-costos']));
            }

            if (!in_array($tipo[1], $types)) {
                $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
                redirect(base_url(['ingreso-costos']));
            }

            $nombre_img = $this->GstCostos->uploadFacturaCosto();
    
            if (!$nombre_img) {
                $this->session->set_flashdata('msgError', 'No se pudo subir la factura, por favor inténtalo de nuevo.');
                redirect(base_url(['ingreso-costos']));
            }
            
            $data['imagen_factura'] = "/uploads/facturas-costos/".$nombre_img.".".$tipo[1];
    
        } 
       
          
        $this->GstCostos->crearCosto($data);

        if($data['estado']==NULL){
            redirect(base_url(['ingreso-costos']));

        }

        redirect(base_url(['ingreso-costos/pagadas']));

    }

    public function facturaProcess()
    {

        $costo_id = $this->input->post('costo_id');
        $data['userdata'] = $this->session->userdata('admin');
        $user_id = $data['userdata']['id'];

        $factura = $_FILES['factura'];
        $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','PDF','pdf'];
        $tipo = explode('.', $factura['name']);

            if ($factura['size'] == 0 && $factura['name']!= "") {
                $this->session->set_flashdata('msgError', 'La factura es requerida.');
                redirect(base_url(['ingreso-costos']));
            }

            if (!in_array($tipo[1], $types)) {
                $this->session->set_flashdata('msgError', 'El tipo de imagen adjunta no es válido.');
                redirect(base_url(['ingreso-costos']));
            }

            $nombre_img = $this->GstCostos->uploadFacturaCosto();
    
            if (!$nombre_img) {
                $this->session->set_flashdata('msgError', 'No se pudo subir la factura, por favor inténtalo de nuevo.');
                redirect(base_url(['ingreso-costos']));
            }

            $factura = "/uploads/facturas-costos/".$nombre_img.".".$tipo[1];


            $this->GstCostos->updateEstado($costo_id,$factura,$user_id);

            redirect(base_url(['ingreso-costos/pagadas']));

    }

    public function pagadas()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        
        $q = null;
        
        if (isset($_GET['q'])) {
            $q = trim($_GET['q']);
        }
       
        $config = [
            'base_url' => base_url('ingreso-costos/pagadas'),
            'per_page' => 50,
            'total_rows' =>count($this->GstCostos->getPagadas($q)),
        ];

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tagl_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tagl_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page-item disabled">';
        $config['first_tagl_close'] = '</li>';
        $config['last_link'] = 'Último';
        $config['first_link'] = 'Primero';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tagl_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $data['costos'] = $this->GstCostos->getPagadas($q, $config['per_page'], $this->uri->segment(3));
        $data['costo_model'] = new McCostos;
        $data['badges_class'] = [
            "badge-success",
            "badge-info",
            "badge-warning",
            "badge-danger",
        ];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('IngresoCostos/pagadas');
        $this->load->view('layout/footerAdmin');

    }



   

 

}
