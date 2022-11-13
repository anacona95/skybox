<?php

class GstCostos extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McCostos');
        $this->load->GstClass('GstRegistro');
    }

    public function crearCosto($data)
    {
        if (!$this->validarCosto()) {
            return false;
        }

        $objCosto = new McCostos;
        $search = ['$', '.', ' '];
        $search_usd = ['$',' '];
        $objCosto->valor =  str_replace($search, "", trim($data['valor']));
        $objCosto->concepto = trim($data['concepto']);
        $objCosto->tipo = trim($data['tipo']);
        $objCosto->created_at = time();
        $objCosto->user_admin_id = trim($data['userdata']['id']);

        
        
        if(trim($data['libras'])!= ""){
            $objCosto->libras = trim($data['libras']);
        }

        if(isset($data['usd']) && trim($data['usd'])!= ""){
            $objCosto->valor_usd = str_replace($search_usd, "", trim($data['usd']));
            $objCosto->trm = trim($data['trm']);
        }
        
        if(isset($data['imagen_factura']) && trim($data['imagen_factura'])!= ""){
            $objCosto->factura = trim($data['imagen_factura']);
            $objCosto->fecha_pago = time();
        }
        
        if(isset($data['estado'])){
            $objCosto->estado = $data['estado'];
        }

        if (!$objCosto->save()) {
            $this->session->set_flashdata('msgError', 'No se pudo crear el costo, inténtalo de nuevo.');
            return false;
        }

        $this->session->set_flashdata('msgOk', 'Se creó el costo exitosamente.');
        return true;

    }

    public function validarCosto()
    {
        $config = [
            [
                'field' => 'data[concepto]',
                'label' => 'concepto',
                'rules' => [
                    'required'
                    
                ],
                'errors' => [
                    'required' => 'El campo concepto es requerido'              
                ],
            ],
            [
                'field' => 'data[tipo]',
                'label' => 'tipo',
                'rules' => [
                    'required',

                ],
                'errors' => [
                    'required' => 'El campo tipo es requerido'
                ],
            ],
            [
                'field' => 'data[valor]',
                'label' => 'valor',
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'El campo valor es requerido'
                ],
            ]
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }

        return true;
    }

    public function uploadFacturaCosto()
    {

        if (!file_exists("./uploads/facturas-costos")) {
            mkdir("./uploads/facturas-costos", 0755, true);
        }

        $config['upload_path'] = "./uploads/facturas-costos/";
        $config['allowed_types'] = 'png|jpg|jepg|jpeg|PNG|JPG|JPEG|JEPG|TIFF|tiff|bmp|BMP|JFIF|jfif|pdf|PDF';
        $config['file_name'] = time();
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("factura")) {
            return false;
        }

        return $config['file_name'];
    }


    public function getAll()
    {
        return McCostos::all();
    }

    public function getPagadas($q = null, $limit=null, $offset=null)
    {
        $this->db->select("mc_costos.concepto,mc_costos.valor,mc_costos.factura,mc_costos.tipo,mc_costos.libras,mc_costos.created_at,mc_costos.fecha_pago,mc_costos.estado,mc_costos.id, mc_costos.trm, mc_costos.valor_usd, mc_user_admin.nombre"); 
        $this->db->from('mc_costos');

        if ($q != null) {
            $this->db->where("MATCH(concepto) AGAINST('+$q*' IN BOOLEAN MODE)");
        }else{
            $this->db->order_by("mc_costos.id","DESC");
        }
        
        $this->db->join("mc_user_admin",'mc_costos.user_admin_id=mc_user_admin.id');
        $this->db->where('mc_costos.estado = 0');

        if($limit!=null && $offset!=null){
            $this->db->limit($limit, $offset);
        }

        
        $query = $this->db->get();
        
        return $query->result();
    }


    
    public function getPendientes($q = null, $limit=null, $offset=null)
    {
        $this->db->select("mc_costos.concepto,mc_costos.valor,mc_costos.tipo,mc_costos.libras,mc_costos.created_at,mc_costos.estado,mc_costos.id, mc_costos.trm, mc_costos.valor_usd, mc_user_admin.nombre"); 
        $this->db->from('mc_costos');
       
        if ($q != null) {
            $this->db->where("MATCH(concepto) AGAINST('+$q*' IN BOOLEAN MODE)");
        }else{
            $this->db->order_by("mc_costos.id","DESC");
        }
        
        $this->db->join("mc_user_admin",'mc_costos.user_admin_id=mc_user_admin.id');
        $this->db->where('mc_costos.estado = 1');//1=pendiente

        if($limit!=null && $offset!=null){
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        
        return $query->result();
    }

    public function updateEstado($id,$factura,$user_id)
    {
        
        $objCosto = McCostos::find($id);
        $objCosto->factura = $factura;
        $objCosto->estado = 0;
        $objCosto->user_admin_id = $user_id;
        $objCosto->fecha_pago = time();

        if(!$objCosto->update()){
            $this->session->set_flashdata('msgError', 'No se pudo actualizar el costo, inténtalo de nuevo.');
            return false;
        }

        $this->session->set_flashdata('msgOk', 'Se actualizó el costo exitosamente.');
        return true;

    }

    public function getTotalPendiente()
    {
        return McCostos::where('estado',1)->sum("valor");
    }

   

}
