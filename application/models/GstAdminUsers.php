<?php

class GstAdminUsers extends CI_Model
{

    public function __construct()
    {
        $this->load->modelORM('McAdministrador');
        $this->load->GstClass('GstRegistro');
        $this->load->library('form_validation');
        parent::__construct();

    }

    public function getUsers()
    {
        return McAdministrador::all();
    }

    public function getRoles()
    {
        $objAdministrador = new McAdministrador;
        return $objAdministrador->roles;
    }

    public function createUser($user)
    {
        if (!$this->validateUser()) {
            return false;
        }
        if (!$this->validatePassword()) {
            return false;
        }

        $user['email'] = trim($user['email']);
        $user['nombre'] = trim($user['nombre']);
        $user['rol'] = trim($user['rol']);

        if (!$this->validateEmail($user['email'])) {
            return false;
        }
        $objAdministrador = new McAdministrador;
        $objAdministrador->nombre = ucwords(strtolower($user['nombre']));
        $objAdministrador->email = $user['email'];
        $objAdministrador->password = $this->GstRegistro->genPass($user['password']);
        $objAdministrador->imagen = "user.png";
        $objAdministrador->role = $user['rol'];

        if (!$objAdministrador->save()) {
            $this->session->set_flashdata('msgError', 'No se pudo crear el usuario, inténtalo de nuevo.');
            return false;
        }
        $this->session->set_flashdata('msgOk', 'Se creó el usuario exitosamente.');
        return true;
    }

    public function validateUser()
    {
        $config = [
            [
                'field' => 'usuario[email]',
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
                'field' => 'usuario[nombre]',
                'label' => 'nombre',
                'rules' => [
                    'required',

                ],
                'errors' => [
                    'required' => 'El campo Nombre es requerido',
                ],
            ],
            [
                'field' => 'usuario[rol]',
                'label' => 'contraseña',
                'rules' => [
                    'required',
                    'numeric',

                ],
                'errors' => [
                    'required' => 'El campo Contraseña es requerido',
                    'numeric' => 'El campo Rol no es correcto',
                ],
            ],
        ];
        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }

        return true;
    }

    public function validateEmail($email, $id = null)
    {

        $objAdministradores = McAdministrador::where('email', $email)->get();

        if ($id != null) {
            $objAdministrador = McAdministrador::find($id);
            foreach ($objAdministradores as $administrador) {
                if ($administrador->id != $objAdministrador->id) {
                    return false;
                }
            }
            return true;
        }
        if (count($objAdministradores) > 0) {
            $this->session->set_flashdata('msgError', 'El correo electrónico ya ha sido registrado.');
            return false;
        }
        return true;
    }

    public function updateUser($user)
    {
        if (!$this->validateUser()) {
            return false;
        }

        if (!array_key_exists('id', $user) && $user['id'] == "") {
            $this->session->set_flashdata('msgError', 'No se puede modificar el usuario, inténtalo de nuevo.');
            return false;
        }

        $user['email'] = trim($user['email']);
        $user['nombre'] = trim($user['nombre']);
        $user['rol'] = trim($user['rol']);
        $objAdministrador = McAdministrador::find($user['id']);
        if (!$this->validateEmail($user['email'], $user['id'])) {
            $this->session->set_flashdata('msgError', 'El correo electrónico ya ha sido registrado.');
            return false;
        }
        if ($use['rol'] != 1 && $this->getCountAdmins() == 1 && $objAdministrador->role == 1) {
            $this->session->set_flashdata('msgError', 'No se pudo modificar el usuario ya que debe existir mínimo un usuario con rol Administrador');
            return false;
        }
        $objAdministrador = McAdministrador::find($user['id']);
        $objAdministrador->nombre = ucwords(strtolower($user['nombre']));
        $objAdministrador->email = $user['email'];
        $objAdministrador->role = $user['rol'];

        if (!$objAdministrador->save()) {
            $this->session->set_flashdata('msgError', 'No se pudo modificar el usuario, inténtalo de nuevo.');
            return false;
        }
        $this->session->set_flashdata('msgOk', 'Se modificó el usuario exitosamente.');
        return true;

    }

    public function validatePassword()
    {
        $config = [
            [
                'field' => 'usuario[password]',
                'label' => 'contraseña',
                'rules' => [
                    'required',

                ],
                'errors' => [
                    'required' => 'El campo Contraseña es requerido',
                ],
            ],
        ];
        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            return false;
        }
        return true;
    }

    public function updatePassword($user)
    {
        if (!$this->validatePassword()) {
            return false;
        }
        $objAdministrador = McAdministrador::find($user['id']);
        $objAdministrador->password = $this->GstRegistro->genPass(trim($user['password']));

        if (!$objAdministrador->save()) {
            $this->session->set_flashdata('msgError', 'No se pudo modificar la contraseña del usuario, inténtalo de nuevo.');
            return false;
        }
        $this->session->set_flashdata('msgOk', 'Se modificó la contraseña exitosamente.');
        return true;
    }

    public function deleteUser($id)
    {

        $objAdministrador = McAdministrador::find($id);
        if (!$objAdministrador->delete()) {
            $this->session->set_flashdata('msgError', 'No se pudo eliminar el usuario, inténtalo de nuevo.');
            return false;
        }
        $this->session->set_flashdata('msgOk', 'Se eliminó el usuario exitosamente.');
        return true;

    }

    public function getCountAdmins()
    {
        return count(McAdministrador::where('role', '1')->get());
    }
}
