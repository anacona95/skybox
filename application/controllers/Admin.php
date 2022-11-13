<?php

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();


        if (!$this->validateAccess()) {
            redirect('AdmLogin/close');
        }
        if (!$this->validateRole(1) && !$this->validateRole(2)) {
            $this->session->set_flashdata('msgError', 'No tiene permiso para acceder a la funcionalidad.');
            redirect(base_url(['cuenta-administrador']));
        }
        $this->load->library('form_validation', 'session');
        $this->load->model('GstAdmin');
        $this->load->modelORM('McUser');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McConfig');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McAdministrador');
        $this->load->modelORM('McNewsPrealert');
        $this->load->model('Gstuser');
        $this->load->GstClass('ConfigEmail');
        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('GstApi');
        $this->load->GstClass('GstApiAgencia');
        $this->load->GstClass('GstCupones');
        $this->load->helper('mysql_to_excel_helper');
    }

//mostrar pre alertas y rastreo de estados
    public function index($id = null)
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $data['q'] = null;
        if (isset($_GET['q'])) {
            $data['q'] = trim($_GET['q']);
        }
        $data['s'] = null;
        if (isset($_GET['s'])) {
            $data['s'] = trim($_GET['s']);
        }
        //pagination
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url('tracking'),
            'per_page' => 100,
            'total_rows' => $this->GstAdmin->rows_inventario($data['q'],$data['s']),
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
        $this->pagination->initialize($config); // model function

        $data['count_prealertas'] = $this->GstAdmin->countPrealertas();
        $data['count_miami'] = $this->GstAdmin->countMiami();
        $data['count_cali'] = $this->GstAdmin->countCali();
        $data['count_orden'] = $this->GstAdmin->countOrden();


        $date['estados'] = $this->GstAdmin->Consulestados($config['per_page'], $this->uri->segment(2), $data['q'],$data['s']);

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/estados', $date);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar ventana hacer prealerta
    public function crearAlerta()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['pre'] = $this->GstAdmin->consulClienPrealerta();

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/HacerPrealerta', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    // consultar info de clientes
    public function clientesInfo($id = null)
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $q = null;
        if (isset($_GET['q'])) {
            $q = trim($_GET['q']);
        }
        //pagination
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url('informacion-clientes'),
            'per_page' => 100,
            'total_rows' => $this->GstAdmin->rows_ConsulClientes($q),
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
        $this->pagination->initialize($config); // model function

        $date['Clientes'] = $this->GstAdmin->ConsulClientes($config['per_page'], $this->uri->segment(2), $q);

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/clientes', $date);
        $this->load->view('layout/footerAdmin', $data);
    }

    //ventana de editar cliente
    public function editarClientes()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['cliente'] = $this->GstAdmin->consulClienPrealerta();
        $date['tarifas'] = $this->GstAdmin->getTarifas();

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/editarCliente', $date);
        $this->load->view('layout/footerAdmin', $data);
    }

    //ventana de informacion del cliente para imprimir
    public function informacionCliente()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['per'] = $this->GstAdmin->consulClienPrealerta();

        $this->load->view('admin/imprimirInfoCliente', $date);
    }

//cambiar estados de rastreo
    public function estadosCambiar()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['post'] = $this->GstAdmin->cambiarEstados();

        if (!$date['post']) {
            redirect('tracking');
        } else {
            $this->session->set_flashdata('estados', 'El estado ha sido cambiado ');
            redirect(base_url('tracking'));
        }
    }

    //cambiar estados de  prealerta
    public function estadosCambiarPrealerta()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['post'] = $this->GstAdmin->cambiarEstadosPrealerta();

        if (!$date['post']) {
            redirect(base_url('tracking'));
        } else {
            $this->session->set_flashdata('alerta', 'El estado ha sido cambiado ');
            redirect(base_url('tracking'));
        }
    }

    // cambiar estados de compras
    public function estadosCambiarCompra()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['post'] = $this->GstAdmin->cambiarEstadosCompras();
        if (!$date['post']) {
            redirect('Admin/estadosCompras');
        } else {
            $this->session->set_flashdata('estado-compras', 'El estado ha sido cambiado ');
            redirect('Admin/estadosCompras');
        }
    }

    //cambiar estados de articulos entregados
    public function estadosCambiarEntregados()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $date['post'] = $this->GstAdmin->cambiarEstadosEntregados();
        if (!$date['post']) {
            redirect('Admin/articulosEntregados');
        } else {
            $this->session->set_flashdata('envios-entregados', 'El estado ha sido cambiado ');
            redirect('Admin/articulosEntregados');
        }
    }

    //mostrar articulos entregados
    public function articulosEntregados()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $q = null;
        if (isset($_GET['q'])) {
            $q = trim($_GET['q']);
        }

        //pagination
        $this->load->library('pagination');
        $config = [
            'base_url' => base_url('Admin/articulosEntregados'),
            'per_page' => 100,
            'total_rows' => $this->GstAdmin->rows_ConsulEnviosEntre($q),
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
        $this->pagination->initialize($config); // model function

        $date['estados'] = $this->GstAdmin->ConsulEnviosEntre($config['per_page'], $this->uri->segment(3), $q);

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/entregadosEnvios', $date);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar ventana para aceptar envio
    public function aceptarEnvios()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['envioAceptar'] = $this->GstAdmin->enviosMostrarDatos();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/aceptarEnvio', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar editar envio por administrador
    public function editarEnvio()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['envioAceptar'] = $this->GstAdmin->enviosMostrarDatos();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/editarEnvio', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar ventana para rechazar envio
    public function rechazarEnvios()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['envioRechazar'] = $this->GstAdmin->enviosMostrarDatos();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/rechazarEnvio', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar ventana para mostrar mas informacion de envio
    public function mostrar_mas_info($id)
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['info'] = $this->GstAdmin->enviosMostrarDatos($id);
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/InformacionEnvio', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //mostrar ventana para mostrar mas informacion de envio
    public function mostrar_mas_info_entregados()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['infoEntregado'] = $this->GstAdmin->enviosMostrarDatos();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/informacionEnvioEntregado', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //guardar datos de aceptar envio
    public function aceptarEnvioss()
    {

        $this->GstAdmin->aceptarEnvio();
        $this->session->set_flashdata('alerta', 'Pre-alerta aceptada');
        redirect(base_url('tracking'));
    }

    //guardar datos de aceptar envio
    public function editarEnvioss()
    {

        $this->GstAdmin->editarEnvio();
        $this->session->set_flashdata('estados', 'La pre-alerta se ha editado');
        redirect('Admin/estadosEnvios');
    }

    //guardar datos de rechazar envio
    public function rechazarEnvioss()
    {

        $this->GstAdmin->rechazarEnvio();
        $this->session->set_flashdata('estados', 'Artículo eliminado satisfactoriamente');
        redirect(base_url('tracking'));
    }

    // guardar una prealerta
    public function GuardarEnvio()
    {

        $nombre = $this->input->post('nombre');
        $id_track = $this->input->post('id_track');
        $fecha = $this->input->post('fecha');
        $descripcion = $this->input->post('descripcion');
        $seguro = $this->input->post('seguro');
        $id_user = $this->input->post('id');
        $valor = $this->input->post('valor_paquete');
        if ($valor > 500) {
            $datos = array(
                'nombre' => $nombre,
                'id_track' => strtoupper($id_track),
                'fecha' => $fecha,
                'descripcion' => $descripcion,
                'seguro' => 'si',
                'user_id' => $id_user,
                'valor_paquete' => $valor,
                'tipo' => 'envio',
                'estadoArticulo' => 'Prealertado',
                'tecnologia' => 0,
                'transportadora' => ''
            );
            if ($this->Gstuser->saveEnvios($datos) == true) {
                $this->session->set_flashdata('cliente', 'La pre-alerta de envió fue creada ');
                redirect(base_url() . 'Admin/crearAlerta?id=' . $id_user);
            } else {
                $this->session->set_flashdata('error-cliente', 'La pre-alerta no se creo ');
                redirect(base_url() . 'Admin/crearAlerta?id=' . $id_user);
            }
        } else {
            $datos = array(
                'nombre' => $nombre,
                'id_track' => strtoupper($id_track),
                'fecha' => $fecha,
                'descripcion' => $descripcion,
                'seguro' => $seguro,
                'user_id' => $id_user,
                'valor_paquete' => $valor,
                'tipo' => 'envio',
                'estadoArticulo' => 'Prealertado',
                'tecnologia' => 0,
                'transportadora' => ''
            );
            if ($this->Gstuser->saveEnvios($datos) == true) {
                $this->session->set_flashdata('cliente', 'La pre-alerta de envió fue creada ');
                redirect(base_url() . 'Admin/crearAlerta?id=' . $id_user);
            } else {
                $this->session->set_flashdata('error-cliente', 'La pre-alerta no se creo ');
                redirect(base_url() . 'Admin/crearAlerta?id=' . $id_user);
            }
        }
    }

    //mostra ventana de cambiar el dolar
    public function valorDolar()
    {

        $_SESSION['envios'] = $this->input->post('data');
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['dolar'] = $this->GstAdmin->calculadora();
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/dolar', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }

    //cambiar valor dolar
    public function CambiarvalorDolar()
    {

        $this->GstAdmin->guardarDolar();
        $this->session->set_flashdata('dolar', 'Se ha actualizado el valor del dólar');
        redirect(base_url() . 'Admin/valorDolar');
    }

    /**
     * Metodo que despliega la vista del listado de usuarios con paquetes en estado
     * En cali y disponible por medio de tabs
     * 2018/05/08
     * cdiaz@skylabs.com.co
     */
    public function bandejaSalida()
    {

        $data['userdata'] = $this->session->userdata('admin');
        $nombre = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $nombre[0];

        $data['clientes'] = $this->GstAdmin->ConsulClientesCorreo();
        $data['clientesEnvio'] = $this->GstAdmin->ConsulClienDespacho();

        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/bandejaSalida', $data);
        $this->load->view('layout/footerAdmin', $data);
    }

    /**
     * Metodo que despliega vista con el listado de articulos para agrupar y evniar correo
     * Parametro int $id
     * 2018/05/08
     * cdiaz@skylabs.com.co
     */
    public function enviarCorreoClientesCali($id)
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $data['paquetes'] = $this->GstAdmin->datosPaqueteClienteCorreo($id);
        $data['valTotal'] = 0;
        $data['valor_paquete_cop'] = 0;
        $data['valor_seguro'] = 0;
        $data['valor_total_seguro'] = 0;
        $data['valor_total_orden'] = 0;
        $data['total_peso']=0;
        $data['cupon_valor']=0;
        $data['cupon_tipo']=0;
        //que tengo que bhacer
        $data['descuento']=$this->GstCupones->getDescuentoCupon($id);
        $objCupon=$this->GstCupones->getCuponUser($id);

        if($objCupon){
            $data['cupon_valor']= $objCupon->valor;
            $data['cupon_tipo']= $objCupon->tipo;
        }
       
        $data['config'] = McConfig::find(1);
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/DatosEmailCliente');
        $this->load->view('layout/footerAdmin');
    }

    /**
     * Metodo para enviar correo de guia para articulos con estado Disponible
     * 2018/05/08
     * cdiaz@skylabs.com.co
     */
    public function enviarCorreoClientesGuia($id)
    {

        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $datos['paquetess'] = $this->GstAdmin->datosPaqueClienCorreoGuia($id);
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/DatosEmailGuia', $datos);
        $this->load->view('layout/footerAdmin', $data);
    }
    /**
     * Metodo que envia el correo para la orden de compra y cambia el estado del articulo
     * a disponible
     * 2018/05/09
     * cdiaz@skylabs.com.co
     */
    public function email()
    {

        $this->load->GstClass('GstOrdenes');

        $datos['userdata'] = $this->session->userdata('admin');
        $datos['articulos'] = $this->GstAdmin->cargarEmail();

        if (!$datos['articulos']) {
            $this->session->set_flashdata('msgError', 'Error al enviar el correo, debe seleccionar al menos un artículo.');
            redirect(base_url(['bandeja-de-salida']));
        }
        $cliente_id = $datos['articulos'][0]->cliente->id;
        if (count($this->GstOrdenes->getOrdenesActivas($cliente_id)) > 0) {
            $this->session->set_flashdata('msgError', 'No se puede crear la orden de compra porque el cliente ya tiene una pendiente de pago, por favor adiciona los paquetes a la orden activa.');
            redirect(base_url(['bandeja-de-salida']));
        }
        $datos['cliente'] = $this->GstAdmin->infoClienteEmail($cliente_id);
        $search = ['$', '.', ' '];
        $datos['flete'] = str_replace($search, "", trim($this->input->post('valor_envio')));
        $datos['seguro'] = str_replace($search, "", trim($this->input->post('seguro')));
        $datos['impuestos'] = str_replace($search, "", trim($this->input->post('impuestos')));
        $datos['descuento'] = str_replace($search, "", trim($this->input->post('descuento')));
        $datos['tipo_envio'] = $this->input->post('tipo_envio');
        $datos['envio_nacional'] = str_replace($search, "", trim($this->input->post('envio_nacional')));
        $datos['valor_total_orden'] = str_replace($search, "", trim($this->input->post('valor_total_orden')));
        $this->GstAdmin->cambiarEstadosEmail();

        $datos['orden'] = $this->GstOrdenes->crearOrden(
            $cliente_id,
            $datos['valor_total_orden'],
            $datos['envio_nacional'],
            $datos['articulos'],
            $datos['seguro'],
            $datos['tipo_envio'],
            $datos['descuento'],
            $datos['impuestos']
        );

        if($datos['cliente']->cupon_id!=NULL && $this->GstCupones->validarCuponUser($datos['cliente']->id,$datos['cliente']->cupon_id)){
         if($datos['valor_total_orden']!=0){
            $this->GstCupones->saveCuponesClientes($datos['cliente']->id,$datos['cliente']->cupon_id,$datos['orden']->id);
            }
        }
       
        $msg = $this->load->view('admin/correoNotificacion', $datos, true);

        $this->ConfigEmail->to($datos['cliente']->email);
        $this->ConfigEmail->subject('Llegaron tus paquetes');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->send();
        $this->session->set_flashdata('msgOk', 'El correo se ha enviado y se ha creado la orden de compra con éxito.');
        
        if($datos['cliente']->device!=null && $datos['cliente']->auth_app==1){
            $this->GstApi->sendMessageApp("Llegaron tus paquetes",$datos['cliente']->device);
        }
        
        
        redirect(base_url(['bandeja-de-salida']));

    }

    //mandar email de guia
    public function emailGuia()
    {
        $datos['userdata'] = $this->session->userdata('admin');
        $datos['articulos'] = $this->GstAdmin->cargarEmail();
        if (!$datos['articulos']) {
            $this->session->set_flashdata('msgError', 'Error al enviar el correo, debe seleccionar al menos un artículo.');
            redirect(base_url(['bandeja-de-salida']));
        }
        $cliente_id = $datos['articulos'][0]->cliente['id'];
        $datos['cliente'] = $this->GstAdmin->infoClienteEmail($cliente_id);

        $empresa = $this->input->post('empresa');
        $guia = $this->input->post('numero_guia');
        $fecha = $this->input->post('fecha');
        $mensaje = $this->input->post('mensaje');

        if ($empresa == 'Coordinadora') {
            $enlace = 'http://www.coordinadora.com/portafolio-de-servicios/servicios-en-linea/rastrear-guias/';
        } elseif ($empresa == 'Envia') {
            $enlace = "http://enviacolvanes.com.co/Contenido.aspx?rastreo=$guia";
        } elseif ($empresa == 'Deprisa') {
            $enlace = "https://www.deprisa.com//Tracking/index/?track=$guia";
        } elseif ($empresa == 'Interrapidisimo') {
            $enlace = "https://www.interrapidisimo.com/sigue-tu-envio/?guia=$guia";
        } elseif ($empresa == 'Fedex') {
            $enlace = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$guia";
        } elseif ($empresa == 'TCC') {
            $enlace = "https://www.tcc.com.co/logistica/servicios-on-line/rastrear-envios/";
        }

        $datos['guias'] = [
            'empresa' => $empresa,
            'enlace' => $enlace,
            'guia' => $guia,
            'fecha' => $fecha,
            'mensaje' => $mensaje,
        ];
        $email = $datos['articulos'][0]->cliente['email'];
        $msg = $this->load->view('admin/correoGuia', $datos, true);

        $this->ConfigEmail->to($email);
        $this->ConfigEmail->subject('Notificación de envío nacional');
        $this->ConfigEmail->message($msg);
        $this->ConfigEmail->send();
        $this->GstAdmin->estadoCorreoGuia();
        $this->session->set_flashdata('msgOk', 'El correo se ha enviado con éxito.');
        redirect(base_url(['bandeja-de-salida']));

    }

    // cargar vista de subir archivos
    public function cargarArchivo()
    {
        $data['userdata'] = $this->session->userdata('admin');
        $apellido = explode(' ', $data['userdata']['nombre']);
        $data['userdata']['nombre'] = $apellido[0];
        $this->load->view('layout/headAdmin', $data);
        $this->load->view('admin/cargarArchivo');
        $this->load->view('layout/footerAdmin', $data);
    }

// subir archivo para cambiar las prealertas a Recibido y viajando
    public function subirArchivo()
    {
        $suites = [];
        $config['file_name'] = time() . ".csv";
        $config['upload_path'] = './uploads/archivos/';
        $config['allowed_types'] = 'csv';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("fileImagen")) {
            $this->session->set_flashdata('error-archivo', 'Error al procesar el archivo de aduanas.');
            redirect('Admin/cargarArchivo');
        }
        $file_info = $this->upload->data();

        $file = "./uploads/archivos/" . $config['file_name'];
        //La primera row del CSV viene con 3 caracteres invisibles, esto los elimina
        /* file_put_contents($file, str_replace("\xEF\xBB\xBF", "", file_get_contents($file))); */

        if (($gestor = fopen("./uploads/archivos/" . $config['file_name'], "r")) !== false) {

            while (($datos = fgetcsv($gestor, 1000, ";")) !== false) {
                $datos[0] = str_replace("\xEF\xBB\xBF", "", $datos[0]);
                if (!is_numeric($datos[0])) {
                    continue;
                }
                //se busca el articulo por suite y por tracking
                $tracking = substr(strtoupper($datos[1]),-8);
                $objArticulo = McArticulos::where('user_id', "$datos[0]")
                    ->where('estadoArticulo', 'Prealertado')
                    ->where('id_track', "LIKE", "%$tracking")
                    ->first();
                    
                $objArticuloRecibido = McArticulos::where('user_id', "$datos[0]")
                    ->where('estadoArticulo', 'Recibido y viajando')
                    ->where('id_track', "LIKE", "%$tracking")
                    ->first();

                if($objArticuloRecibido){
                    continue;
                }
                
                //se valida que exista el articulo, si no crea uno nuevo Recibido y viajando
                if (!$objArticulo && !$objArticuloRecibido) {
                    $objArticulo = new McArticulos;
                    $objArticulo->nombre = utf8_encode($datos[2]); //se convierte por msexcel
                    $objArticulo->id_track = strtoupper($datos[1]);
                    $objArticulo->user_id = $datos[0];
                    $objArticulo->peso = 0;
                    $objArticulo->valor = 0;
                    $objArticulo->tipo = "envio";
                    $objArticulo->fecha_registro = time();
                    $objArticulo->fecha = date('Y-m-d');
                    $objArticulo->fecha_reporte = time();
                    $objArticulo->fecha_entrega = time() + (11 * 24 * 60 * 60);
                    $objArticulo->descripcion = "Artículo no prealertado";
                    $objArticulo->seguro = "no";
                    $objArticulo->transportadora = "otra";
                    $objArticulo->valor_paquete = "0";
                    $objArticulo->estadoArticulo = "Recibido y viajando";
                    $objArticulo->save();

                    //GSTAPIAGENCIA
                    if($this->GstApiAgencia->isAgencia($objArticulo->user_id)){
                        $this->GstApiAgencia->setArticuloVuelo($objArticulo);

                    }
                    
                    //continua con el siguiente registro del archivo
                    if(in_array($datos[0],$suites)){
                        continue;
                    }
    
                    $suites[] = $datos[0];
                    
                    if($objArticulo->cliente->device!=null && $objArticulo->cliente->auth_app==1){
                        $this->GstApi->sendMessageApp("Hemos recibido paquetes a tu nombre en Miami.",$objArticulo->cliente->device);
                    }
                    
                    continue;
                }
                // si el archivo existe actualiza la informacion
                $objArticulo->fecha_entrega = time() + (11 * 24 * 60 * 60);
                $objArticulo->estadoArticulo = "Recibido y viajando";
                $objArticulo->update();
                
                //GSTAPIAGENCIA
                if($this->GstApiAgencia->isAgencia($objArticulo->user_id)){
                    $this->GstApiAgencia->setArticuloVuelo($objArticulo);
                }

                if(in_array($datos[0],$suites)){
                    continue;
                }

                $suites[] = $datos[0];
                
                if($objArticulo->cliente->device!=null && $objArticulo->cliente->auth_app==1){
                    $this->GstApi->sendMessageApp("Hemos recibido paquetes a tu nombre en Miami.",$objArticulo->cliente->device);
                }

            }
            fclose($gestor);
        }
        //borrar el archivo
        unlink("./uploads/archivos/" . $config['file_name']);
        $this->session->set_flashdata('archivo', 'El archivo Recibido y viajando se ha procesado correctamente.');
        redirect('Admin/cargarArchivo');
    }

//crear reporte en excel
    public function Excel()
    {
        $result = $this->GstAdmin->excel();
        to_excel($result, "reporte");
    }

    public function reporteFacturacion()
    {

        $data['ordenes'] = $this->GstOrdenes->getPagosReport();
        $data['total'] = 0;
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=reporte-facturacion.xls");
        $this->load->view('admin/templateReport', $data);
    }

    public function prealertasCsv(){
        
        $fecha1 = strtotime(str_replace('/', '-', $this->input->post('fecha1')));
        $fecha2 = strtotime(str_replace('/', '-', $this->input->post('fecha2')));
        $objArticulos = $this->GstAdmin->getPrealertasFecha($fecha1,$fecha2);

        header("Content-disposition: attachment; filename=prealertas.csv; charset=utf-8");
        header("Content-Type: text/csv");
        $fp = fopen("php://output", 'w');
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        
        foreach ($objArticulos as $row) {
            $vals = ["142",$row->id_track,trim($row->nombre),$row->valor_paquete];
            fputcsv($fp, $vals,";");
        }

        fclose($fp);

        exit();
    }

    public function updArticle()
    {

        $objArticulo = $this->McArticulos->find($this->input->post('articulo_id'));
        //para el nombre
        if ($this->input->post('type') == "nombre") {

            $objArticulo->nombre = $this->input->post('valor');
        }
        if ($this->input->post('type') == "traking") {

            $objArticulo->id_track = $this->input->post('valor');
        }
        if ($this->input->post('type') == "peso") {

            $objArticulo->peso = $this->input->post('valor');
        }
        if ($this->input->post('type') == "valor") {

            $objArticulo->valor = $this->input->post('valor');
        }
        if ($this->input->post('type') == "valor_paquete") {

            $objArticulo->valor_paquete = $this->input->post('valor');
        }
        if ($this->input->post('type') == "user_id") {
            $objArticulo->user_id = $this->input->post('valor');
        }
        $objArticulo->save();
    }

    public function newsPrealert()
    {
        $objArticulo = $this->McNewsPrealert->where('estado', 1)->get();
        $data['articulos'] = $objArticulo;
        $this->GstAdmin->updateStateNews();
        $objArticulo = $this->McNewsPrealert->where('estado', 1)->get();
        $_SESSION['news']['notifzise'] = count($objArticulo);
        $this->load->view('admin/NewsPrealert', $data);
    }

    public function getOrdenes()
    {
        $data['config'] = McConfig::find(1);
        $this->load->GstClass('GstOrdenes');
        $id = $_GET['id'];
        $data['orden'] = $this->GstOrdenes->getOrdenesActivas($id);

        if (isset($data['orden']->user_id)) {
            $data['articulos'] = $this->GstOrdenes->getArticulosCali($data['orden']->user_id);
        }

        $this->load->view('admin/getOrdenes', $data);
    }

    public function delMultiple()
    {
        $date['post'] = $this->GstAdmin->delMultiple();

        if (!$date['post']) {
            redirect('tracking');
        } else {
            $this->session->set_flashdata('estados', 'Se han eliminado los artículos con éxito.');
            redirect(base_url('tracking'));
        }
    }
    //modificar datos de perfil del cliente
    public function updatePerfilCliente()
    {

        $this->GstAdmin->updatePerfilCliente();
        $this->session->set_flashdata('cliente', 'Se han modificado los datos personales del cliente');
        redirect("editar-clientes?id=".$this->input->post('id'));
    }

    //funcion para cambiar de miami a aduanas
    /* public function loadAduanas()
    {
        $config['file_name'] = time() . ".csv";
        $config['upload_path'] = './uploads/archivos/';
        $config['allowed_types'] = 'csv';

        $data = [];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("file")) {

            $this->session->set_flashdata('error-archivo', 'Error al procesar el archivo de vuelo.');
            redirect('Admin/cargarArchivo');
        }
        $file_info = $this->upload->data();

        $file = "./uploads/archivos/" . $config['file_name'];
        //La primera row del CSV viene con 3 caracteres invisibles, esto los elimina
        //file_put_contents($file, str_replace("\xEF\xBB\xBF", "", file_get_contents($file)));

        if (($gestor = fopen("./uploads/archivos/" . $config['file_name'], "r")) !== false) {

            while (($datos = fgetcsv($gestor, 1000, ";")) !== false) {
                $datos[0] = str_replace("\xEF\xBB\xBF", "", $datos[0]);
                if (!is_numeric($datos[0])) {
                    continue;
                }

                $data[] = [
                    'suite' => trim($datos[0]),
                    'track' => trim(strtoupper($datos[1])),
                    'peso' => trim($datos[2]),
                ];

            }
            fclose($gestor);
        }
        //borrar el archivo
        unlink("./uploads/archivos/" . $config['file_name']);
        $result_array = $this->GstAdmin->sortArray($data);

        $this->GstAdmin->updateAduanas($result_array);

        redirect('Admin/cargarArchivo');
    } */

    //suite,tracking, desc, valorUSD
    public function prealertaMasiva()
    {
        $config['file_name'] = time() . ".csv";
        $config['upload_path'] = './uploads/archivos/';
        $config['allowed_types'] = 'csv';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("fileImagen")) {
            $this->session->set_flashdata('error-archivo', 'Error al procesar el archivo de prealertas.');
            redirect('Admin/cargarArchivo');
        }
        $file_info = $this->upload->data();
        
        $file = "./uploads/archivos/" . $config['file_name'];
        //La primera row del CSV viene con 3 caracteres invisibles, esto los elimina
        /* file_put_contents($file, str_replace("\xEF\xBB\xBF", "", file_get_contents($file))); */
        
        if (($gestor = fopen("./uploads/archivos/" . $config['file_name'], "r")) !== false) {
            while (($datos = fgetcsv($gestor, 1000, ";")) !== false) {
                $datos[0] = str_replace("\xEF\xBB\xBF", "", $datos[0]);
                if (!is_numeric($datos[0])) {
                    continue;
                }
                
                //se busca el articulo por suite y por tracking
                $tracking = substr(strtoupper($datos[1]),-8);
                $objArticulo = McArticulos::where('user_id', "$datos[0]")
                    ->where('estadoArticulo', 'Prealertado')
                    ->where('id_track', "LIKE", "%$tracking")
                    ->first();
                    
                $objArticuloRecibido = McArticulos::where('user_id', "$datos[0]")
                    ->where('estadoArticulo', 'Recibido y viajando')
                    ->where('id_track', "LIKE", "%$tracking")
                    ->first();

                 
                //se valida que exista el articulo, si no crea uno nuevo Recibido y viajando
                if (!$objArticulo && !$objArticuloRecibido) {
                
                    $objArticulo = new McArticulos;
                    $objArticulo->nombre = $datos[2]; //se convierte por msexcel
                    $objArticulo->id_track = $tracking;
                    $objArticulo->user_id = trim($datos[0]);
                    $objArticulo->peso = 0;
                    $objArticulo->valor = "0";
                    $objArticulo->tipo = "envio";
                    $objArticulo->fecha_registro = time();
                    $objArticulo->fecha = date('Y-m-d');
                    $objArticulo->fecha_reporte = date("Y-m-d");
                    $objArticulo->fecha_entrega = date('Y-m-d', strtotime('+ 10 day'));
                    $objArticulo->descripcion = "Artículo carga masiva";
                    $objArticulo->seguro = "no";
                    $objArticulo->valor_paquete = trim($datos[3]);
                    $objArticulo->estadoArticulo = "Prealertado";
                    $objArticulo->save();
                    
                    continue;
                }
                   
            }
            fclose($gestor);
        }
        //borrar el archivo
        unlink("./uploads/archivos/" . $config['file_name']);
        $this->session->set_flashdata('archivo', 'El archivo de prealertas se ha procesado correctamente.');
        redirect('Admin/cargarArchivo');
    }

}
