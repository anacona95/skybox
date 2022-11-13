<?php

class GstLogin extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McCliente');
        $this->load->GstClass('GstRegistro');
    }

    public function validateLogin()
    {

        $config = [
            [
                'field' => 'data[usuario]',
                'label' => 'Usuario',
                'rules' => ['required','valid_email'],
                'errors' => [
                    'required' => 'El campo Usuario es requerido.',
                    'valid_email' => 'Usuario o contraseña incorrecto'
                ],
            ],
            [
                'field' => 'data[password]',
                'label' => 'Password',
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
        $objCliente = McCliente::where('email', strtolower($data['usuario']))
        ->where('estado', 1)
        ->first();

        if (!$objCliente) {
            $this->session->set_flashdata('error', 'Usuario o contraseña incorrecto');
            return false;
        }

        if(!$this->validateCredentials($data))
        {
            return false;
        }

        return true;
    }

    private function validateCredentials(array $data)
    {
        $email = strtolower($data['usuario']);
        $password = trim($data['password']);

        $pass = $this->GstRegistro->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];

        $ObjCliente = $this->McCliente->where('email', $email)->first();
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];
        if ($passDb != $truePass) {
            $this->session->set_flashdata('error', 'Usuario o contraseña incorrecto');
            return false;
        }
        $userdata = $ObjCliente->toArray();
        unset($userdata['password']);

        $this->session->set_userdata('user', $userdata);

        $objCliente = $this->McPuntos->where('user_id', trim($userdata['id']))->first();

        if (count($objCliente) > 0) {
            return true;
        }
        $datos = array(
            'cantidad' => '0',
            'utilizados' => '0',
            'user_id' => $userdata['id'],
        );

        $this->db->insert('mc_puntos_user', $datos);

        return true;
    }

}
