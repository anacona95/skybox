<?php

class GstRegistro extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McPuntos');
        $this->load->modelORM('McConfig');
        $this->load->GstClass('GstCupones');

    }

    public function validarRegistro()
    {
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
                'field' => 'data[apellido]',
                'label' => 'apellido',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Apellidos es requerido.',
                ],
            ],
            [
                'field' => 'data[nacimiento]',
                'label' => 'nacimiento',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Cumpleaños es requerido.',
                ],
            ],
            [
                'field' => 'data[identificacion]',
                'label' => 'identificacion',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Cedula es requerido.',
                ],
            ],
            [
                'field' => 'data[telefono]',
                'label' => 'telefono',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Número celular es requerido.',
                ],
            ],
            [
                'field' => 'data[pais]',
                'label' => 'pais',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo País es requerido.',
                ],
            ],
            [
                'field' => 'data[ciudad]',
                'label' => 'ciudad',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Ciudad es requerido.',
                ],
            ],
            [
                'field' => 'data[direccion]',
                'label' => 'direccion',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Dirección es requerido.',
                ],
            ],
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
            [
                'field' => 'data[password]',
                'label' => 'password',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo Password es requerido.',
                ],
            ],
            [
                'field' => 'data[check]',
                'label' => 'check',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe aceptar termino y condiciones.',
                ],
            ],
	    [
                'field' => 'data[parent_id]',
                'label' => 'codigo de un amigo',
                'rules' => 'numeric',
                'errors' => [
                    'required' => 'El codigo de un amigo debe ser numérico.',
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
        
        $objCliente = $this->McCliente->where('email', trim(mb_strtolower($data['email'])))->get();

        if (count($objCliente) > 0) {
            $this->session->set_flashdata('error', 'El correo electrónico ya ha sido registrado.');
            return false;
        }
        $data = $this->input->post('data');

        if ($data['parent_id'] != null) {
            $objClient = $this->McPuntos->where('user_id', $data['parent_id'])->get();
            if (count($objClient) == 0) {
                $this->session->set_flashdata('error', 'el codigo ingresado no existe.');
                return false;
            }
        }

        if ($data['cupon'] != null) {
            if (!$this->GstCupones->getCuponName($data['cupon'])) {
                $this->session->set_flashdata('error', 'El cupón ingresado no es válido, por favor inténtalo nuevamente.');
                return false;
            }
        }

        return true;
    }

    public function saveRegistro()
    {
        $paren = 0;
        $data = $this->input->post('data');
        
        $data['parent_id'] = trim($data['parent_id']);
        $data['pais'] = trim($data['pais']);
        $data['email'] = trim(mb_strtolower($data['email']));
        $data['nombre'] = trim(ucwords(mb_strtolower($data['nombre'])));
        $data['ciudad'] = trim($data['ciudad']);
        $data['departamento'] = trim($data['departamento']);
        $data['nombre2'] = trim(ucwords(mb_strtolower($data['nombre2'])));
        $data['telefono'] = trim($data['telefono']);
        $data['apellido'] = trim(ucwords(mb_strtolower($data['apellido'])));
        
        $data['direccion'] = trim($data['direccion']);
        $data['nacimiento'] = trim($data['nacimiento']);
        $data['descripcion'] = trim($data['descripcion']);
        $data['identificacion'] = trim($data['identificacion']);
        $data['asesor'] = trim($data['asesor']);
        $data['password'] = $this->genPass(trim($data['password']));
        
        if ($data['parent_id'] != null) {
            $paren = $data['parent_id'];
        }

        if ($data['asesor'] != null) {
            $data['descripcion'] .= ": ".$data['asesor'];
        }

        if ($data['cupon'] != null) {
            $data['cupon'] = $this->GstCupones->getCuponName($data['cupon'],true)->id;
        }

        $McCliente = new McCliente;

        $McCliente->parent_id = $paren;
        $McCliente->estado = 1;
        $McCliente->fecha_creacion = date('Y-m-d H:i:s');
        $McCliente->pais = $data['pais'];
        $McCliente->email = $data['email'];
        $McCliente->ciudad = $data['ciudad'] . ", " . $data['departamento'];
        $McCliente->password = $data['password'];
        $McCliente->telefono = $data['telefono'];
        $McCliente->apellidos = $data['apellido'];
        $McCliente->direccion = $data['direccion'];
        $McCliente->primer_nombre = $data['nombre'];
        $McCliente->segundo_nombre = $data['nombre2'];
        $McCliente->descripcions = $data['descripcion'];
        $McCliente->fecha_nacimiento = $data['nacimiento'];
        $McCliente->num_documento = $data['identificacion'];
        $McCliente->cupon_id = $data['cupon'];
        $McCliente->imagen = 'user.png';

        if (!$McCliente->save()) {
            return false;
        }
        $datos = array(
            'id' => $McCliente->id,
            'primer_nombre' => $McCliente->primer_nombre,
            'segundo_nombre' => $McCliente->segundo_nombre,
            'apellidos' => $McCliente->apellidos,
            'email' => $McCliente->email,
        );
        return $datos;
    }

    public function genPass($pass)
    {
        $pass = strtoupper(sha1($pass));
        $random = strtoupper(md5(rand(1, 999)));
        $time = strtoupper(md5(time()));
        $pass = $time . ':' . $pass . ':' . $random;

        return $pass;
    }

    public function apiMailer($user)
    {
        //configuracion api_mailerlite
        $objConfig = McConfig::find(1);

        $groupsApi = (new MailerLiteApi\MailerLite($objConfig->api_mailerlite))->groups();

        $subscriber = [
            'email' => $user['email'],
            'fields' => [
                'name' => $user['primer_nombre'],
                'last_name' => $user['apellidos'],
            ],
        ];
// Change GROUP_ID with ID of group you want to add subscriber to
        $response = $groupsApi->addSubscriber($objConfig->group_mailerlite, $subscriber);

    }

}
