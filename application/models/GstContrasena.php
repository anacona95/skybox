<?php

class GstContrasena extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McPuntos');
    }

    public function validarRegistro()
    {
        $config = [
            [
                'field' => 'data[email]',
                'label' => 'email',
                'rules' => [
                    'required',
                    'valid_email',
                ],
                'errors' => [
                    'required' => 'El campo Correo electrónico es requerido.',
                    'valid_email' => 'El campo Correo electrónico no es un correo electrónico valido',
                ],
            ],
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('errorclave', $errors);
            return false;
        }

        $data = $this->input->post('data');
        $objCliente = $this->McCliente->where('email', $data['email'])->get();
        if (count($objCliente) == 0) {
            $this->session->set_flashdata('errorclave', 'El correo electrónico no esta registrado.');
            return false;
        }
        return true;
    }

    public function saveRegistro($clave, $email)
    {
        $claves = $this->genPass(trim($clave));

        $datos = array(
            'password' => $claves,

        );

        $this->db->where('email', $email);
        $this->db->update('mc_clientes', $datos);

        return true;
    }

    public function genPass($pass)
    {
        $pass = strtoupper(sha1($pass));
        $random = strtoupper(md5(rand(1, 999)));
        $time = strtoupper(md5(time()));
        $pass = $time . ':' . $pass . ':' . $random;

        return $pass;
    }

    public function activate($id)
    {
        $McCliente = $this->McCliente->find($id);
        $McCliente->estado = 1;

        if (!$McCliente->save()) {
            return false;
        }
        return true;
    }

}
