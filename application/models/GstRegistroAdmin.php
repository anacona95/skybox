<?php

class GstRegistroAdmin extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McAdministrador');
    }

    public function validarRegistro() {
        $config = [
            [
                'field' => 'data[nombre]',
                'label' => 'Nombre',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Nombre es requerido.',
                ],
            ],

            
            [
                'field' => 'data[email]',
                'label' => 'email',
                'rules' => [
                    'required',
                    'valid_email'
                ],
                'errors' => [
                    'required' => 'El campo Correo electrónico es requerido.',
                    'valid_email' => 'El campo Correo electrónico no es un correo electrónico valido'
                ],
            ],
            [
                'field' => 'data[login]',
                'label' => 'check',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo login es requerido.',
                ],
            ],
            [
                'field' => 'data[password]',
                'label' => 'password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Password es requerido.',
                ],
            ],
           
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('error', $errors);
            return false;
        }
        $data = $this->input->post('data');
        $objAdministrador = $this->McAdministrador->where('email', $data['email'])->get();

        if (count($objAdministrador) > 0) {
            $this->session->set_flashdata('error', 'El correo electrónico ya ha sido registrado.');
            return false;
        }
        return true;
    }

    public function saveRegistro() {

        $data = $this->input->post('data');

       
        $data['email'] = trim($data['email']);
        $data['nombre'] = trim($data['nombre']);
        $data['login'] = trim($data['login']);
        $data['password'] = $this->genPass(trim($data['password']));

  

        $this->McAdministrador->email = $data['email'];
        $this->McAdministrador->password = $data['password'];
        $this->McAdministrador->primer_nombre = $data['nombre'];
        $this->McAdministrador->login = $data['login'];
        

        if (!$this->McAdministrador->save()) {
            return false;
        }
        return $this->McAdministrador->id;
    }

    function genPass($pass) {
        $pass = strtoupper(sha1($pass));
        $random = strtoupper(md5(rand(1, 999)));
        $time = strtoupper(md5(time()));
        $pass = $time . ':' . $pass . ':' . $random;

        return $pass;
    }

    function activate($id) {
        $McCliente = $this->McCliente->find($id);
        $McCliente->estado = 1;

        if (!$McCliente->save()) {
            return false;
        }
        return true;
    }

}
