<?php

class ProfileAdmin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->validateAccess()) {
            redirect('AdmLogin/close');
        }
        $this->load->model('GstAdmin');

    }

    // consultar datos de cuenta del administrados
    public function cuenta()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $date['per'] = $this->GstAdmin->ConsulCuenta($id_user);
        
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/perfil', $date);
        $this->load->view('layout/footerAdmin', $data);
    }

    //modificar datos de perfil
    public function updatePerfil()
    {
        $data['userdata'] = $this->session->userdata('admin');

        $this->GstAdmin->updatePerfil();
        $data['userdata']['nombre'] = $this->input->post('nombre');
        $this->session->set_userdata('admin', $data['userdata']);
        $this->session->set_flashdata('perfil', 'El nombre de administrador se actualizo');
        redirect('cuenta-administrador');
    }

//cargar ventana para cambiar contraseña
    public function contrasena()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/CambiarPassword', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    //cambiar contraseña administrador
    public function CambiarContraseña()
    {

        $result = $this->GstAdmin->CambiarContraseña();

        if (!$result) {

            redirect('cambiar-contrasena');
        }
        $this->session->set_flashdata('clave', 'Se cambió la contraseña con éxito ');
        redirect('cambiar-contrasena');
    }

    // cargar ventana  cambiar email
    public function ventanaEmail()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/CambiarEmail', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    // cambiar email de administrador
    public function Cambiaremail()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $result = $this->GstAdmin->CambiarEmail();

        if (!$result) {
            $this->session->set_flashdata('error-email', 'no se pudo cambiar el correo');
            redirect('cambiar-correo');
        }
        $data['userdata']['email'] = $this->input->post('email');
        $this->session->set_userdata('admin', $data['userdata']);
        $this->session->set_flashdata('emailcambiado', 'El correo se actualizo');
        redirect('cambiar-correo');
    }

    // subir imgen de perfil
    public function subirImagen()
    {

        $data['userdata'] = $this->session->userdata('admin');

        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];
        $id_user = $id[0];
        $config['upload_path'] = './uploads/imagenes/';
        $config['file_name'] = "admin-" . time();
        $config['allowed_types'] = 'gif|jpg|png|jepg';
        $config['max_size'] = '2048';
        $config['max_width'] = '2024';
        $config['max_height'] = '2008';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("imagen")) {
            $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error-perfil', 'No se pudo subir la imagen por favor intente de nuevo.');
            redirect(base_url(['cuenta-administrador']));
        }

        $file_info = $this->upload->data();

        $this->crearMiniatura($file_info['file_name']);

        $imagen = $file_info['file_name'];
        $data['userdata']['imagen'] = $imagen;
        $this->session->set_userdata('admin', $data['userdata']);
        $subir = $this->GstAdmin->subirImagen($imagen, $id_user);
        $this->session->set_flashdata('perfil', 'La imagen de perfil se ha cambiado con éxito');
        redirect(base_url(['cuenta-administrador']));
    }

    public function crearMiniatura($filename)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = 'uploads/imagenes/' . $filename;
        $config['create_thumb'] = true;
        $config['maintain_ratio'] = true;
        $config['new_image'] = 'uploads/imagenes/thumbs/';
        $config['thumb_marker'] = ''; //captura_thumb.png
        $config['width'] = 150;
        $config['height'] = 150;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }

}
