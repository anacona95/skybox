<?php

class User extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->validateCliente()) {
            redirect('login/close');
        }
        $this->load->library('form_validation');
        $this->load->GstClass('Gstuser');
        $this->load->GstClass('GstAdmin');
        $this->load->modelORM('McUser');
        $this->load->modelORM('McConfig');
        $this->load->modelORM('McAdministrador');
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('GstCupones');
        $this->load->GstClass('GstApiAgencia');
        

    }

    public function index()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $data['tarifas'] = $this->Gstuser->getTarifas();
        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $data['config'] = McConfig::find(1);
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/index', $data);
        $this->load->view('layout/footerUser', $data);
    }

    public function Pagos()
    {
        $this->load->model('GstPagos');
        $data['objArticulos'] = $this->GstPagos->getArticulos();
        $data['objPagos'] = $this->GstPagos->getPagos();
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $this->load->view('layout/headUser', $data);
        $this->load->view('user/pagos');
        $this->load->view('layout/footerUser', $data);
    }

    // ventana de inicio para puntos
    public function puntos()
    {
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $data['ordenes'] = $this->GstOrdenes->getOrdenesActivas($id_user);
        $data['puntos'] = $this->Gstuser->ConsulPuntos($id_user);
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/consulPuntos', $data);
        $this->load->view('layout/footerUser', $data);
    }

    // ventana para asignar puntos
    public function puntosAsignar($id)
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $data['orden'] = $this->GstOrdenes->getOrden($id);

        $data['puntoss'] = $this->Gstuser->ConsulPuntos($data['userdata']['id']);
        if ($data['puntoss']->cantidad < 10) {
            $this->session->set_flashdata('msgError', 'La cantidad mínima de de puntos para asignar a una orden de compra debe ser de 10 puntos.');
            redirect(base_url(['mis-puntos']));
        }
        if ($data['orden']->descuento > 0) {
            $this->session->set_flashdata('msgError', 'No puede asignar puntos a la orden porque esta ya tiene un descuento asignado.');
            redirect(base_url(['mis-puntos']));
        }
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/asignarPuntos', $data);
        $this->load->view('layout/footerUser', $data);
    }

    // ventana para asignar puntos
    public function puntosProcess()
    {

        $config = [
            [
                'field' => 'orden',
                'label' => 'Orden',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo Orden es requerido.',
                ],
            ],
            [
                'field' => 'descuento',
                'label' => 'descuento',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo Descuento es requerido.',
                ],

            ],
            [
                'field' => 'puntos',
                'label' => 'Puntos',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo Puntos es requerido.',
                ],

            ],
        ];

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            redirect(base_url(['mis-puntos']));
        }
        $orden_id = $this->input->post('orden');
        $puntos = $this->input->post('puntos');
        $descuento = $this->input->post('descuento');

        $userdata = $this->session->userdata('user');
        $objPuntos = $this->Gstuser->ConsulPuntos($userdata['id']);

        if ($objPuntos->cantidad < $puntos) {
            $this->session->set_flashdata('msgError', 'La cantidad de puntos que intenta asignar es inferior a los puntos disponibles.');
            redirect(base_url(['mis-puntos']));
        }
        $resta = $objPuntos->cantidad - $puntos;
        $sumar = $objPuntos->utilizados + $puntos;
        $this->Gstuser->restarPuntos($resta, $sumar, $objPuntos->id_puntos);
        $this->GstOrdenes->aplicarDescuento($descuento, $orden_id);
        $this->session->set_flashdata('msgOk', 'Los puntos fueron asignados exitosamente');
        redirect(base_url(['mis-puntos']));
    }

    //cargar pre_compras
    public function CargarCompras()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $date['alertasCompras'] = $this->Gstuser->ConsulCompras($id_user);
        //die(var_dump( $date));
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/PreCompras', $date);
        $this->load->view('layout/footerUser', $data);
    }

    //cargar estados compras
    public function CargarEstadosCompra()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $date['estadosCom'] = $this->Gstuser->ConsulEstadoCompras($id_user);
        //die(var_dump( $date));
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/EstadoCompras', $date);
        $this->load->view('layout/footerUser', $data);
    }

    //ventana mofificar envios
    public function modificar_envios()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $datos['modificar_envio'] = $this->Gstuser->enviosUpdate();
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/envios_modificar', $datos);
        $this->load->view('layout/footerUser', $data);
    }

    //ventana para modificar compras
    public function modificar_compras()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $datos['modificar_compras'] = $this->Gstuser->comprasUpdate();
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/comprasModificar', $datos);
        $this->load->view('layout/footerUser', $data);
    }

    //modificar envios
    /* public function updateEnvios()
    {

        $this->Gstuser->updateEnvio();
        $this->session->set_flashdata('pre-alerta', 'La pre-alerta fue modificada.');
        redirect('user');
    } */

    //modificar datos de perfil
    public function updatePerfil()
    {

        $this->Gstuser->updatePerfil();
        $this->session->set_flashdata('perfil', 'Los datos de perfil se han actualizado. ');
        redirect('cuenta-usuario');
    }

    //modificar compras
    public function updateCompras()
    {

        $this->Gstuser->updateCompras();
        $this->session->set_flashdata('pre-alerta-compras', 'La pre-alerta compra fue modificada. ');
        redirect('precompras');
    }

    //cambiar estado a eliminado al envio
    public function EstadoEliminarEnvio()
    {

        $this->Gstuser->estadoEnvio();
        $this->session->set_flashdata('pre-alerta', 'La pre-alerta fue eliminada.');
        redirect('user');
    }

    //cambiar estado a eliminado al compra
    public function EstadoEliminarCompra()
    {

        $this->Gstuser->estadoCompra();
        $this->session->set_flashdata('pre-alerta-compras', 'La pre-alerta compra fue eliminada. ');
        redirect('precompras');
    }

    //guardar una compra
    public function compras_proces()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $enlace = $this->input->post('enlace');
        $referencia = $this->input->post('referencia');
        $talla = $this->input->post('talla');
        $color = $this->input->post('color');
        $cantidad = $this->input->post('cantidad');
        $id_user = $id[0];

        $datos = array(
            'enlace' => $enlace,
            'referencia' => $referencia,
            'talla' => $talla,
            'color' => $color,
            'cantidad' => $cantidad,
            'tipo' => 'compra',
            'estado_compra' => 'En cotizacion',
            'user_id' => $id_user,
        );
        
        if ($this->Gstuser->saveCompras($datos) == true) {
            $this->session->set_flashdata('pre-alerta-compras', 'La pre-alerta de compra fue registrada. ');
            redirect(base_url() . 'precompras');
        } else {
            $this->session->set_flashdata('error-pre-alerta-compras', 'Error al registrar la pre-alerta compra.');
            redirect(base_url() . 'precompras');
        }
    }

    //guardar datos de envio
    public function envios_process()
    {
        $objConfig = McConfig::find(1);
        $this->load->GstClass('ConfigEmail');

        $comprobante = $_FILES['comprobante'];
	    $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','pdf','PDF','jfif','JFIF'];
        $tipo = explode('.', $comprobante['name']);
        
        if (!in_array($tipo[1], $types)) {
            $this->session->set_flashdata('error-pre-alerta', 'El tipo de archivo no es válido.');
            redirect(base_url(['user']));
        }

        $file_name = $this->GstOrdenes->uploadComprobante();

        if(!$file_name){
            $this->session->set_flashdata('error-pre-alerta', 'Ha ocurrido un error al cargar la factura, por favor inténtalo de nuevo.');
             redirect(base_url(['user']));
        }

        $userdata = $this->session->userdata('user');
        $id_path = $userdata['id'];
        $factura_path = base_url()."uploads/comprobantes/$id_path/$file_name.".$tipo[1];

        $nombre = $this->input->post('nombre');
        $id_track = strtoupper($this->input->post('id_track'));
        $fecha = $this->input->post('fecha');
        $seguro = $this->input->post('seguro');
        $valorP = $this->input->post('valor_paquete');
        $trans = $this->input->post('transportadora');
        $otra = $this->input->post('otra');
        $otra_tienda = $this->input->post('otra_tienda');
        $tienda = $this->input->post('tienda');
        
        if($otra!=""){
            $trans = $otra;
        }
        
        if($otra_tienda!=""){
            $tienda = $otra_tienda;
        }

        $prealerta = [
            'nombre' => $nombre,
            'id_track' => $id_track,
            'fecha' => $fecha,
            'seguro' => "no",
            'user_id' => $userdata['id'],
            'valor_paquete' => $valorP,
            'tipo' => 'envio',
            'estadoArticulo' => 'Prealertado',
            'transportadora' => $trans,
            'tienda' => $tienda,
            'factura_path' => $factura_path
        ];

        //no aplica seguro si el articulo es menor o igual a min usd
        if ($valorP <= $objConfig->seguro_min-1) {
            $prealerta['seguro'] = "no";
        }
        /*aplica seguro opcional si selecciona si siempre y cuando el paquete
         *sea declarado entre min y max usd
         */
        if ($seguro == "si" && $valorP >= $objConfig->seguro_min-1 && $valorP <= $objConfig->seguro_max) {
            $prealerta['seguro'] = "si";
        }
        //aplica seguro obligatorio siempre y cuando el paquete sea declarado por +500 usd
        if ($valorP >= $objConfig->seguro_max+1) {
            $prealerta['seguro'] = "si";
        }
        //marca el paquete tecnologico
        /* $values = [1, 2, 3];
        if (in_array($tecnologia, $values)) {
            $prealerta['tecnologia'] = $tecnologia;
            //aplica seguro si es tipo 3 => televisor
            if ($tecnologia == 3) {
                $prealerta['seguro'] = "si";
            }

        } else {
            $prealerta['tecnologia'] = 0;
        } */
         //crea la prealerta
         $objArticulo = $this->Gstuser->saveEnvios($prealerta);

         if(!$objArticulo){
             $this->session->set_flashdata('error-pre-alerta', 'Error al crear la prealerta.');
             redirect(base_url(['user']));
         }
        //obtiene los puntos del cliente
       /*  
         //consultar cliente actualizado
        $data['userdata'] = $this->session->userdata('user');
        $dataCliente = $this->Gstuser->cliente($data['userdata']['id']);
        $cliente_id = $dataCliente->parent_id;
       $objPuntos = $this->Gstuser->puntoCliente($cliente_id);
        //sistema de puntos si tiene parent o si los puntos son inferiores a 50
        //valida si el referido esta habilitado para sumar puntos
        if ($cliente_id != 0 && $objPuntos->cantidad < 50 && $this->Gstuser->validarReferido($dataCliente->id)!=false) {
            $this->Gstuser->asignarPuntos($cliente_id, $objPuntos->cantidad + 5);
        }
 */
        $data['userdata'] = $this->session->userdata('user');
        $dataCliente = $this->Gstuser->cliente($data['userdata']['id']);
        $prealerta['nombre_cliente'] = $dataCliente->primer_nombre;
        $msg = $this->load->view('user/emailPrealerta', $prealerta, true);

        $this->ConfigEmail->to($dataCliente->email);
        $this->ConfigEmail->subject('Tu prealerta');
        $this->ConfigEmail->message($msg);

        $this->ConfigEmail->send();

         //01072020 Nupan-Metodo para enviar datos a otra plataforma 
         if($this->GstApiAgencia->getAgenciaId()!=NULL){
            $articulo_id=$this->GstApiAgencia->getArticuloId($prealerta['user_id'],$prealerta['id_track']);
            $this->GstApiAgencia->Prealerta($prealerta,$articulo_id);
          }
        
        $this->session->set_flashdata('pre-alerta', 'La prealerta se creó con éxito.');
        redirect(base_url(['user']));
    }

    //mostrar vista eliminar un pre_envio
    public function vistasEliminar()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $datos['eliminarEnvio'] = $this->Gstuser->enviosUpdate();
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/vistaEliminarEnvio', $datos);
        $this->load->view('layout/footerUser', $data);
    }

    //mostrar vista eliminar pre_compra
    public function vistasEliminarCompra()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $datos['eliminarCompra'] = $this->Gstuser->comprasUpdate();
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/vistaEliminarCompra', $datos);
        $this->load->view('layout/footerUser', $data);
    }

    //vista perfil del cliente
    public function cuenta()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];

        $id_user = $id[0];
        $date['per'] = $this->Gstuser->ConsulCuenta($id_user);
        
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/Perfil', $date);
        $this->load->view('layout/footerUser', $data);
    }

    

    // vista rastreo de paquetes
    public function paquetes()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];

        $data['config'] = McConfig::find(1);

        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];
        $data['estados'] = ['Prealertado'=>'0','Recibido y viajando'=>'1','En Cali'=>'2','Orden'=>'3'];
        $id_user = $id[0];
        $data['q'] = null;
        if (isset($_GET['q'])) {
            $data['q'] = trim($_GET['q']);
        }
        $data['paquetes'] = $this->Gstuser->ConsulPaquetes($id_user,$data['q']);
        $data['badges_class'] = [
            "badge-info",
            "badge-warning",
            "badge-success",
            "badge-error",
        ];
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/paquetesEstados', $data);
        $this->load->view('layout/footerUser', $data);
    }

//cargar ventana para cambiar contraseña
    public function contrasena()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/CambiarPassword', $data);
        $this->load->view('layout/footerUser', $data);
    }

    //update contraseña
    public function CambiarContraseña()
    {
        $result = $this->Gstuser->CambiarContraseña();
        if (!$result) {

            redirect('clave');
        }
        $this->session->set_flashdata('clave', 'La contraseña se actualizó.');
        redirect('clave');
    }

    // cargar ventana  cambiar email
    public function email()
    {

        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/CambiarEmail', $data);
        $this->load->view('layout/footerUser', $data);
    }

// cambiar email de usuario
    public function Cambiaremail()
    {
        $result = $this->Gstuser->CambiarEmail();

        if (!$result) {
            redirect('correo');
        }
        $this->session->set_flashdata('correo', 'El correo se ha actualizado.  ');
        redirect('correo');
    }

    // subir imgen de perfil
    public function subirImagen()
    {

        $data['userdata'] = $this->session->userdata('user');

        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $id = explode(' ', $data['userdata']['id']);
        $data['userdata']['id'] = $id[0];
        $id_user = $id[0];
        $config['upload_path'] = './uploads/imagenes/';
        $config['file_name'] = $data['userdata']['id'] . "-" . time();
        $config['allowed_types'] = 'gif|jpg|png|jepg';
        $config['max_size'] = '2048';
        $config['max_width'] = '2024';
        $config['max_height'] = '2008';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("imagen")) {
            $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error-perfil', 'No se pudo subir la imagen por favor intente de nuevo.');
            redirect(base_url(['cuenta-usuario']));
        }

        $file_info = $this->upload->data();

        $this->crearMiniatura($file_info['file_name']);

        $imagen = $file_info['file_name'];
        $data['userdata']['imagen'] = $imagen;
        $this->session->set_userdata('user', $data['userdata']);
        $subir = $this->Gstuser->subir($imagen, $id_user);
        $this->session->set_flashdata('perfil', 'La imagen de perfil se ha cambiado con éxito');
        redirect(base_url(['cuenta-usuario']));

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

    public function pagarPaquete($id)
    {

        date_default_timezone_set('America/Bogota');
        $this->load->model('GstPagos');
        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('user');
        $apellido = explode(' ', $data['userdata']['apellidos']);
        $data['userdata']['apellidos'] = $apellido[0];
        $datos['objArticulo'] = $this->GstPagos->getArticulo($id);

        if ((!$datos['objArticulo']) || $data['userdata']['id'] != $datos['objArticulo']->user_id || $datos['objArticulo']->estadoArticulo != "En tus manos") {
            $this->session->set_flashdata('msgError', 'No se ha podido encontrar el artículo');
            redirect('user/pagos');
        }
        $valorArticulo = $datos['objArticulo']->valor_paquete;
        $data['valorTotal'] = $valorArticulo + $valorArticulo * 0.04;
        $data['valorRecargo'] = $valorArticulo * 0.04;
        $this->load->view('layout/headUser', $data);
        $this->load->view('user/pagarPaquete', $datos);
        $this->load->view('layout/footerUser', $data);
    }

    public function updatePrealertaProcess()
    {
        $config = [
            [
                'field' => 'articulo_id',
                'label' => 'articulo',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo articulo es requerido.',
                ],
            ],
            [
                'field' => 'id_track',
                'label' => 'Tracking',
                'rules' => 'required',
                'errors' => [
                    'required' => 'El campo tracking es requerido.',
                ],

            ],
            [
                'field' => 'valor_paquete',
                'label' => 'Valor usd',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo valor usd es requerido.',
                ],

            ],
        ];

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            redirect(base_url(['rastreo-paquetes']));
        }
        $objConfig = McConfig::find(1);
        $this->load->GstClass('ConfigEmail');

        $comprobante = $_FILES['comprobante'];
        $userdata = $this->session->userdata('user');
        $id_path = $userdata['id'];
        

        $id_track = strtoupper($this->input->post('id_track'));
        $seguro = $this->input->post('seguro');
        $valorP = $this->input->post('valor_paquete');
        $articulo_id = $this->input->post('articulo_id');
        
        $prealerta = [
            'articulo_id' => $articulo_id,
            'id_track' => $id_track,
            'seguro' => "no",
            'user_id' => $userdata['id'],
            'valor_paquete' => $valorP,
            'factura_path' => null
        ];

        if($comprobante['size'] > 0){
            $types = ['png', 'PNG', 'jpg', 'JPG', 'jepg', 'JEPG', 'jpeg', 'JPEG', 'TIFF', 'tiff', 'bmp', 'BMP','pdf','PDF'];
            $tipo = explode('.', $comprobante['name']);
            
            if (!in_array($tipo[1], $types)) {
                $this->session->set_flashdata('msgError', 'El tipo de archivo no es válido.');
                redirect(base_url(['rastreo-paquetes']));
            }

            $file_name = $this->GstOrdenes->uploadComprobante();

            if(!$file_name){
                $this->session->set_flashdata('msgError', 'Ha ocurrido un error al cargar la factura, por favor inténtalo de nuevo.');
                 redirect(base_url(['rastreo-paquetes']));
            }

            $prealerta["factura_path"] = base_url()."uploads/comprobantes/$id_path/$file_name.".$tipo[1];

        }
        
        //no aplica seguro si el articulo es menor o igual a min usd
        if ($valorP <= $objConfig->seguro_min-1) {
            $prealerta['seguro'] = "no";
        }
        /*aplica seguro opcional si selecciona si siempre y cuando el paquete
         *sea declarado entre min y max usd
         */
        if ($seguro == "si" && $valorP >= $objConfig->seguro_min-1 && $valorP <= $objConfig->seguro_max) {
            $prealerta['seguro'] = "si";
        }
        //aplica seguro obligatorio siempre y cuando el paquete sea declarado por +500 usd
        if ($valorP >= $objConfig->seguro_max+1) {
            $prealerta['seguro'] = "si";
        }
      

        if(!$this->Gstuser->updPrealerta($prealerta)){
             $this->session->set_flashdata('msgError', 'Error al modificar la prealerta.');
             redirect(base_url(['rastreo-paquetes']));
        }
       
        //EMAIL PREALERTAS
        $data['userdata'] = $this->session->userdata('user');
        $dataCliente = $this->Gstuser->cliente($data['userdata']['id']);
        $prealerta['nombre_cliente'] = $dataCliente->primer_nombre;
        $msg = $this->load->view('user/emailPrealerta', $prealerta, true);

        $this->ConfigEmail->to($dataCliente->email);
        $this->ConfigEmail->subject('Tu prealerta');
        $this->ConfigEmail->message($msg);

        if (!$this->ConfigEmail->send()) {
            $this->session->set_flashdata('msgError', 'No se pudo enviar el correo.');
            redirect(['rastreo-paquetes']);
        }

         //01072020 Nupan-Metodo para enviar datos a otra plataforma 
         if($this->GstApiAgencia->getAgenciaId()!=NULL){
            $this->GstApiAgencia->updatePrealerta($prealerta);
         }
        
        $this->session->set_flashdata('msgOk', 'La prealerta se actualizó exitosamente.');
        redirect(base_url(['rastreo-paquetes']));
    }

    public function deletePrealertaProcess()
    {
        $config = [
            [
                'field' => 'articulo_id',
                'label' => 'articulo',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'El campo articulo es requerido.',
                ],
            ],
            
        ];

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('msgError', $errors);
            redirect(base_url(['rastreo-paquetes']));
        }

        //01072020 Nupan-Metodo para enviar datos a otra plataforma 
        if($this->GstApiAgencia->getAgenciaId()!=NULL){
            $objArticulo = $this->GstApiAgencia->getArticulo($this->input->post("articulo_id"));
            $this->GstApiAgencia->setArticuloEstado($objArticulo,"eliminar");
        }

        if(!$this->Gstuser->eliminarPrealerta($this->input->post("articulo_id"))){
            $this->session->set_flashdata('msgError', "No se pudo eliminar la prealerta, por favor inténtalo nuevamente");
            redirect(base_url(['rastreo-paquetes']));
        }

        $this->session->set_flashdata('msgOk', 'La prealerta se eliminó exitosamente.');
        redirect(base_url(['rastreo-paquetes']));
    }

  

    

}
