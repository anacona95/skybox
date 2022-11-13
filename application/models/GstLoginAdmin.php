<?php

class GstLoginAdmin extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McAdministrador');
        $this->load->GstClass('GstRegistroAdmin');
    }

    public function validateLogin()
    {

        $config = [
            [
                'field' => 'dataAdmin[usuario]',
                'label' => 'Usuario',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Usuario es requerido.',
                ],
            ],
            [
                'field' => 'dataAdmin[password]',
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

        $data = $this->input->post('dataAdmin');
        $objAdmin = $this->McAdministrador->where('email', trim($data['usuario']))->first();

        if (count($objAdmin) == 0) {
            return false;
        }
        $result = $this->validateCredentials($data);
        return $result;
    }

    private function validateCredentials(array $data)
    {
        $email = trim($data['usuario']);
        $password = trim($data['password']);

        $pass = $this->GstRegistroAdmin->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];

        $objAdmin = $this->McAdministrador->where('email', $email)->first();
        $partsDb = explode(':', $objAdmin->password);
        $passDb = $partsDb[1];
        if ($passDb != $truePass) {
            return false;
        }
        $userdata = $objAdmin->toArray();
        unset($userdata['password']);

        $this->session->set_userdata('admin', $userdata);

        return true;
    }

}
