<?php

class GstAdmin extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation', 'session');
        $this->load->modelORM('McUser');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McAdministrador');
        $this->load->modelORM('McTransactions');
        $this->load->GstClass('GstRegistro');
        $this->load->GstClass('GstApiAgencia');
        $this->load->GstClass('GstApi');
    }

    public function countPrealertas(){
        $objPrealertados = $this->McArticulos->where('estadoArticulo', 'Prealertado')->get();
        return count($objPrealertados);
    }

    public function countMiami(){
        $objMiami = $this->McArticulos->where('estadoArticulo', 'Recibido y viajando')->get();
        return count($objMiami);
    }

    public function countCali(){
        $objCali = $this->McArticulos->where('estadoArticulo', 'En Cali')->get();
        return count($objCali);
    }
    
    public function countOrden(){
        $objOrden = $this->McArticulos->where('estadoArticulo', 'Orden')->get();
        return count($objOrden);
    }


//consultar alertas de envios
    public function ConsulAlertas()
    {

        $sql = "select *
                              from mc_articulos,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_articulos.user_id
                              AND mc_articulos.estadoArticulo='Prealertado'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar clientes con envios en cali
    public function ConsulClientesCorreo()
    {

        return McCliente::select(
            'mc_clientes.id',
            'mc_clientes.primer_nombre',
            'mc_clientes.segundo_nombre',
            'mc_clientes.apellidos',
            'mc_clientes.telefono',
            'mc_clientes.email',
            'mc_clientes.ciudad'
        )->join('mc_articulos','mc_clientes.id','=','mc_articulos.user_id')
        ->where('mc_articulos.estadoArticulo','En Cali')
        ->distinct()
        ->get();
    }

    //consultar clientes con paquetes por despachar de cali  a casa
    public function ConsulClienDespacho()
    {

        $sql = "SELECT DISTINCT mc_clientes.id, mc_clientes.primer_nombre, mc_clientes.segundo_nombre,
                            mc_clientes.apellidos,mc_clientes.telefono,mc_clientes.email,mc_clientes.ciudad
                            FROM mc_clientes,mc_articulos
                            WHERE mc_clientes.id=mc_articulos.user_id
                            AND mc_articulos.estadoArticulo='Disponible'
			      ";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar clientes
    public function ConsulClientes($limit, $offset, $q = null)
    {

        $this->db->select('*');
        $this->db->from('mc_clientes');
        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
            if (count($words) >= 2) {
                $this->db->or_where_in('primer_nombre', $words);
                $this->db->or_where_in('apellidos', $words);
            } else {
                $this->db->or_like('primer_nombre', ucwords(strtolower($q)));
                $this->db->or_like('apellidos', ucwords(strtolower($q)));
                $this->db->or_like('email', strtolower($q));
                $this->db->or_like('num_documento', strtolower($q));
                $this->db->or_like('id', strtolower($q));
                $this->db->or_like('telefono', strtolower($q));
            }
            $this->db->group_end();

        }
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();

    }

    public function rows_ConsulClientes($q)
    {
        $this->db->select('*');
        $this->db->from('mc_clientes');
        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
            if (count($words) >= 2) {
                $this->db->or_where_in('primer_nombre', $words);
                $this->db->or_where_in('apellidos', $words);
            } else {
                $this->db->or_like('primer_nombre', ucwords(strtolower($q)));
                $this->db->or_like('apellidos', ucwords(strtolower($q)));
                $this->db->or_like('email', strtolower($q));
                $this->db->or_like('num_documento', strtolower($q));
                $this->db->or_like('id', strtolower($q));
                $this->db->or_like('telefono', strtolower($q));
            }
            $this->db->group_end();

        }
        $this->db->order_by('id', 'desc');

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function consulClienPrealerta()
    {
       return McCliente::find($_GET['id']);
       
    }

//consultar alertas de envios
    public function ConsulAlertasCompras()
    {

        $sql = "select *
                              from mc_compras,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_compras.user_id
                              AND mc_compras.estado_compra='En cotizacion'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar envios entregados
    public function ConsulEnviosEntre($limit, $offset, $q = null)
    {

        $this->db->select('*');
        $this->db->from('mc_articulos');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_articulos.user_id');
        $this->db->where('estadoArticulo', 'En tus manos');

        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
            if (count($words) >= 2) {
                $this->db->or_where_in('mc_clientes.primer_nombre', $words);
                $this->db->or_where_in('mc_clientes.apellidos', $words);
            } else {
                $this->db->or_like('mc_clientes.primer_nombre', ucwords(strtolower($q)));
                $this->db->or_like('mc_clientes.apellidos', ucwords(strtolower($q)));
            }

            $this->db->or_like('mc_clientes.id', $q);
            $this->db->or_like('nombre', $q);
            $this->db->or_like('id_track', strtoupper($q));
            $this->db->group_end();

        }

        $this->db->order_by('mc_articulos.articulo_id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        /* die(var_dump($this->db->last_query())); */
        return $query->result_array();
    }

    public function rows_ConsulEnviosEntre($q = null)
    {

        $this->db->select('*');
        $this->db->from('mc_articulos');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_articulos.user_id');
        $this->db->where('estadoArticulo', 'En tus manos');

        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
            if (count($words) >= 2) {
                $this->db->or_where_in('mc_clientes.primer_nombre', $words);
                $this->db->or_where_in('mc_clientes.apellidos', $words);
            } else {
                $this->db->or_like('mc_clientes.primer_nombre', ucwords(strtolower($q)));
                $this->db->or_like('mc_clientes.apellidos', ucwords(strtolower($q)));
            }

            $this->db->or_like('mc_clientes.id', $q);
            $this->db->or_like('nombre', $q);
            $this->db->or_like('id_track', strtoupper($q));
            $this->db->group_end();

        }

        $this->db->order_by('mc_articulos.articulo_id', 'desc');
        $query = $this->db->get();
        /* die(var_dump($this->db->last_query())); */
        return $query->num_rows();
    }

    //consultar rastreo de envios
    public function Consulestados($limit, $offset, $q = null,$s=null)
    {

        $estados = ['Prealertado', 'Recibido y viajando', 'En Cali', 'Disponible', 'Orden'];

        $this->db->select('*');
        $this->db->from('mc_articulos');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_articulos.user_id');
        if ($s != null && in_array($s, $estados)) {

            $this->db->where('estadoArticulo', $s);

        } else {
            $this->db->where_not_in('estadoArticulo', ['eliminar', 'En tus manos']);
        }
        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
        
            foreach($words as $word){
                $this->db->or_like('mc_clientes.primer_nombre', $word);
                $this->db->or_like('mc_clientes.apellidos', $word);
                $this->db->or_like('mc_clientes.primer_nombre', strtoupper($word));
                $this->db->or_like('mc_clientes.apellidos', strtoupper($word));
            }
            
            $this->db->or_like('mc_clientes.id', $q);
            $this->db->or_like('id_track', strtoupper($q));
            $this->db->group_end();

        }

        $this->db->order_by('mc_articulos.articulo_id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    //cuenta las filas
    public function rows_inventario($q = null, $s=null)
    {
        $estados = ['Prealertado', 'Recibido y viajando', 'En Cali', 'Disponible', 'Orden'];

        $this->db->select('*');
        $this->db->from('mc_articulos');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_articulos.user_id');
        if ($s != null && in_array($s, $estados)) {

            $this->db->where('estadoArticulo', $s);
            $this->db->order_by('mc_articulos.articulo_id', 'desc');

        } else {
            $this->db->where_not_in('estadoArticulo', ['eliminar', 'En tus manos']);
        }
        if ($q != null) {
            $words = explode(' ', ucwords(strtolower($q)));

            $this->db->group_start();
        
            foreach($words as $word){
                $this->db->or_like('mc_clientes.primer_nombre', $word);
                $this->db->or_like('mc_clientes.apellidos', $word);
                $this->db->or_like('mc_clientes.primer_nombre', strtoupper($word));
                $this->db->or_like('mc_clientes.apellidos', strtoupper($word));
            }
            
            $this->db->or_like('mc_clientes.id', $q);
            $this->db->or_like('id_track', strtoupper($q));
            $this->db->group_end();

        }

        $this->db->order_by('mc_articulos.articulo_id', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

//consultar rastreo de envios
    public function ConsulestadosCompras()
    {

        $sql = "select *
                              from mc_compras,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_compras.user_id
                              AND mc_compras.estado_compra!='En cotizacion'
                              AND mc_compras.estado_compra!='eliminar'
                              "
        ;
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//cambiar estados
    public function cambiarEstados()
    {

        $post = $this->input->post('id');
        if ($post == null) {
            $this->session->set_flashdata('error-estados', 'Seleccionar uno de los campos');
            return false;
        }
        $estado = $this->input->post('estados');
        $transaction = new McTransactions;
        $transaction->beginTransaction();
        try {
            
            foreach ($post as $articulo_id) {

                $objArticulo = McArticulos::find($articulo_id);
                $objArticulo->estadoArticulo = $estado;
                
                if(!$objArticulo->update()){
                    throw new Exception(false);
                }

                //GSTAPIAGENCIA
                if($this->GstApiAgencia->isAgencia($objArticulo->user_id)){
                    $this->GstApiAgencia->setArticuloEstado($objArticulo,$estado);

                }

                if($objArticulo->cliente->device!=null && $objArticulo->cliente->auth_app==1){
                    $this->GstApi->sendMessageApp("Hemos actualizado el estado de tu paquete ...".substr($objArticulo->id_track,-6)." a: $estado.",$objArticulo->cliente->device);
                }
              
            }
            $transaction->commit();
        } catch (Exception $exc) {
            $transaction->rollBack();
            $this->session->set_flashdata('error-estados', 'Ha ocurrido un error inesperado.');
            return false;
        }
        
        return true;
    }

    //cambiar estados prealerta
    public function cambiarEstadosPrealerta()
    {

        $post = $_POST['id'];
        if ($post == null) {
            $this->session->set_flashdata('error-prealerta', 'Seleccionar uno de los campos');
            return false;
        }
        $estado = $this->input->post('estados');
        $masDias = date('Y-m-d', strtotime('+ 6 day'));
        //die(var_dump( $post,$estado));
        foreach ($post as $value) {

            $this->db->set('estadoArticulo', $estado);
            $this->db->set('fecha_entrega', $masDias);
            $this->db->where('articulo_id', $value);
            $this->db->update('mc_articulos');
        }
        return true;
    }

//cambiar estados de ls compras
    public function cambiarEstadosCompras()
    {
        $post = $_POST['id'];
        $estado = $this->input->post('estados');
        if ($post == null) {
            $this->session->set_flashdata('error-compras', 'Seleccionar uno de los campos');
            return false;
        }
        foreach ($post as $value) {
            $this->db->set('estado_compra', $estado);
            $this->db->where('id_compra', $value);
            $this->db->update('mc_compras');
        }
        return true;
    }

// cambiar estados de la ventana entregados
    public function cambiarEstadosEntregados()
    {

        $post = $_POST['id'];
        $estado = $this->input->post('estados');
        if ($post == null) {
            $this->session->set_flashdata('error-entregados', 'Seleccionar uno de los campos');
            return false;
        }
        foreach ($post as $value) {

            $this->db->set('estadoArticulo', $estado);
            $this->db->where('articulo_id', $value);
            $this->db->update('mc_articulos');
        }
        return true;
    }

//mostrar datos para aceptar envio
    public function enviosMostrarDatos($id)
    {

        $sql = "select * from mc_articulos,mc_clientes"
            . " where  mc_clientes.id=mc_articulos.user_id and  mc_articulos.articulo_id=?";
        $sql = $this->db->query($sql, $id);
        return $sql->result();
    }

//mostrar datos para aceptar envio
    public function comprasMostrarDatos()
    {

        $sql = "select * from mc_compras,mc_clientes"
            . " where  mc_clientes.id=mc_compras.user_id and  mc_compras.id_compra=?";
        $sql = $this->db->query($sql, $_GET['id']);
        return $sql->result();
    }

    //guardar datos de aceptar envio
    public function aceptarEnvio()
    {

        $datos = array(
            'nombre' => $this->input->post('nombre'),
            'id_track' => $this->input->post('id_track'),
            'fecha' => $this->input->post('fecha'),
            'descripcion' => $this->input->post('descripcion'),
            'valor' => $this->input->post('valor'),
            'seguro' => $this->input->post('seguro'),
            'peso' => $this->input->post('peso'),
            'fecha_entrega' => $this->input->post('fecha_entrega'),
            'estadoArticulo' => 'Recibido y viajando',
        );

        $this->db->where('articulo_id', $this->input->post('id'));
        $this->db->update('mc_articulos', $datos);
    }

//guardar datos de aceptar envio
    public function editarEnvio()
    {

        $datos = array(
            'nombre' => $this->input->post('nombre'),
            'id_track' => $this->input->post('id_track'),
            'fecha' => $this->input->post('fecha'),
            'descripcion' => $this->input->post('descripcion'),
            'valor' => $this->input->post('valor'),
            'valor_paquete' => $this->input->post('valor_paquete'),
            'seguro' => $this->input->post('seguro'),
            'peso' => $this->input->post('peso'),
            'fecha_entrega' => $this->input->post('fecha_entrega'),
        );

        $this->db->where('articulo_id', $this->input->post('id'));
        $this->db->update('mc_articulos', $datos);
    }

//guardar datos de rechazar envio
    public function rechazarEnvio()
    {

        $datos = array(
            'estadoArticulo' => 'eliminar',
        );

        $this->db->where('articulo_id', $_GET['id']);
        $this->db->update('mc_articulos', $datos);
    }

//guardar datos de aceptar compra
    public function aceptarCompra()
    {

        $datos = array(
            'valor_compra' => $this->input->post('valor'),
            'estado_compra' => 'Aceptada',
        );

        $this->db->where('id_compra', $this->input->post('id'));
        $this->db->update('mc_compras', $datos);
    }

// guardar rechazar una compra
    public function RechazarCompra()
    {

        $datos = array(
            'estado_compra' => 'Rechazada',
        );

        $this->db->where('id_compra', $this->input->post('id'));
        $this->db->update('mc_compras', $datos);
    }

//mostrar datos para aceptar envio
    public function calculadora()
    {
        $id = 1;
        $sql = "select * from calculadora"
            . " where  ?";
        $sql = $this->db->query($sql, $id);
        return $sql->result();
    }

    //guardar datos de aceptar envio
    public function guardarDolar()
    {

        $datos = array(
            'valor' => $this->input->post('valor'),
        );
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('calculadora', $datos);
    }

/// registrar un nuevo cliente por parte del administrados

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
        ];

        $this->form_validation->set_rules($config);
        if (!$this->form_validation->run()) {
            $errors = validation_errors();
            $this->session->set_flashdata('er', $errors);
            return false;
        }
        $data = $this->input->post('data');
        $objCliente = $this->McCliente->where('email', $data['email'])->get();

        if (count($objCliente) > 0) {
            $this->session->set_flashdata('er', 'El correo electrónico ya ha sido registrado.');
            return false;
        }
        return true;
    }

    public function saveRegistro()
    {

        $data = $this->input->post('data');

        $data['pais'] = trim($data['pais']);
        $data['email'] = trim($data['email']);
        $data['nombre'] = trim($data['nombre']);
        $data['ciudad'] = trim($data['ciudad']);
        $data['nombre2'] = trim($data['nombre2']);
        $data['telefono'] = trim($data['telefono']);
        $data['apellido'] = trim($data['apellido']);
        $data['direccion'] = trim($data['direccion']);
        $data['nacimiento'] = trim($data['nacimiento']);
        $data['identificacion'] = trim($data['identificacion']);
        $data['password'] = $this->genPass(trim($data['password']));

        $this->McCliente->estado = 0;
        $this->McCliente->parent_id = 0;
        $this->McCliente->fecha_creacion = date('Y-m-d H:i:s');
        $this->McCliente->pais = $data['pais'];
        $this->McCliente->email = $data['email'];
        $this->McCliente->ciudad = $data['ciudad'];
        $this->McCliente->password = $data['password'];
        $this->McCliente->telefono = $data['telefono'];
        $this->McCliente->apellidos = $data['apellido'];
        $this->McCliente->direccion = $data['direccion'];
        $this->McCliente->primer_nombre = $data['nombre'];
        $this->McCliente->segundo_nombre = $data['nombre2'];
        $this->McCliente->fecha_nacimiento = $data['nacimiento'];
        $this->McCliente->num_documento = $data['identificacion'];

        if (!$this->McCliente->save()) {
            return false;
        }
        return $this->McCliente->id;
    }

    //---------------- termina registrar cliente
    //mostrar datos para aceptar envio
    public function datosPaqueteClienteCorreo($id)
    {

        $sql = "select * from mc_articulos,mc_clientes"
            . " where  mc_clientes.id=mc_articulos.user_id and mc_articulos.estadoArticulo='En Cali'"
            . " and mc_clientes.id=?";
        $sql = $this->db->query($sql, $id);
        return $sql->result_array();
    }

    public function getFletesCrearOrden($user_id)
    {
        $fletes = McArticulos::where('user_id',$user_id)
        ->where('estadoArticulo','En Cali')
        ->get()
        ->sum("valor");
        
        return $fletes;
    }

    //mostrar datos para aceptar envio
    public function datosPaqueClienCorreoGuia($id)
    {

        $sql = "select * from mc_articulos,mc_clientes"
            . " where  mc_clientes.id=mc_articulos.user_id and mc_articulos.estadoArticulo='Disponible'"
            . " and mc_clientes.id=?";
        $sql = $this->db->query($sql, $id);
        return $sql->result_array();
    }

//mandar email
    public function cargarEmail()
    {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $post = $_POST['id'];
            $datos = [];

            foreach ($post as $value) {

                $datos[] = $this->McArticulos->where('articulo_id', $value)->first();
            }
            return $datos;
        }
        return false;
    }

//cambiar estado correo a Disponible
    public function cambiarEstadosEmail()
    {
        $post = $_POST['id'];
        $estado = 'Orden';
        foreach ($post as $value) {
            $this->db->set('estadoArticulo', $estado);
            $this->db->where('articulo_id', $value);
            $this->db->update('mc_articulos');
        }
    }

    //cambiar estado correo a disponible
    public function estadoCorreoGuia()
    {
        $post = $_POST['id'];
        $estado = 'En tus manos';
        foreach ($post as $value) {
            $this->db->set('estadoArticulo', $estado);
            $this->db->where('articulo_id', $value);
            $this->db->update('mc_articulos');
        }
    }

//consultar datos de cuenta
    public function ConsulCuenta($id_user)
    {
        return McAdministrador::find($id_user);
    }

//consultar datos de cuenta
    public function infoClienteEmail($id_user)
    {
        return $objCliente = McCliente::find($id_user);
    }

//modifivcar datos de perfil
    public function updatePerfil()
    {

        $datos = array(
            'nombre' => $this->input->post('nombre'),
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mc_user_admin', $datos);
    }

    //update para la contraseña

    public function CambiarContraseña()
    {

        $id = $this->input->post('id');
        $password = $this->input->post('clave');
        $password2 = $this->input->post('claves');
        $password3 = $this->input->post('claves2');

        $pass = $this->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];
        //----------------------------------------------------
        $ObjCliente = $this->McAdministrador->where('id', $id)->first();
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];

        if ($passDb != $truePass) {
            $this->session->set_flashdata('error-clave', ' contraseña incorrecta.');
            return false;
        }
        if ($password2 != $password3) {
            $this->session->set_flashdata('error-clave', 'La contraseña de confirmación no coincide con la nueva. ');
            return false;
        }
        $data = $this->genPass(trim($password2));
        //die(var_dump( $data));
        $datos = array(
            'password' => $data,
        );
        $this->db->where('id', $id);
        $this->db->update('mc_user_admin', $datos);
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

    //cambiar email usuario
    public function CambiarEmail()
    {

        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $password = $this->input->post('clave');
        //die(var_dump( $email));
        $objClientes = $this->McAdministrador->where('email', $email)->get();
        if (count($objClientes) > 0) {
            $this->session->set_flashdata('error-email', 'correo ya esta siendo utilizado.');
            return false;
        }
        //die(var_dump( $email));
        $pass = $this->GstRegistro->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];
        $ObjCliente = $this->McAdministrador->where('id', $id)->first();
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];
        //die(var_dump( $passDb));
        if ($passDb != $truePass) {
            $this->session->set_flashdata('error-email', 'clave erronea.');
            return false;
        }
        $datos = array(
            'email' => $email,
        );

        $this->db->where('id', $id);
        $this->db->update('mc_user_admin', $datos);
        return true;
    }

// subir imagen de perfil
    public function subirImagen($imagen, $id_user)
    {

        $data = array(
            'imagen' => $imagen,
        );
        $this->db->where('id', $id_user);
        $this->db->update('mc_user_admin', $data);
    }

    //modifivcar datos de perfil del cliente
    public function updatePerfilCliente()
    {

        $datos = array(
            'primer_nombre' => $this->input->post('primer_nombre'),
            'segundo_nombre' => $this->input->post('segundo_nombre'),
            'apellidos' => $this->input->post('apellidos'),
            'num_documento' => $this->input->post('num_documento'),
            'telefono' => $this->input->post('telefono'),
            'pais' => $this->input->post('pais'),
            'ciudad' => $this->input->post('ciudad'),
            'direccion' => $this->input->post('direccion'),
            'email' => $this->input->post('email'),
            'fecha_nacimiento' => $this->input->post('fecha_nacimiento'),
            'parent_id' => $this->input->post('parent_id'),
            'estado' => $this->input->post('estado'),
            'descripcions' => $this->input->post('descripcions'),
            'tarifa' => $this->input->post('tarifa'),
            'tarifa_comercial' => $this->input->post('comercial'),
        );

        if($this->input->post('cobrar_seguro')=="1"){
            $datos['cobrar_seguro'] = 1;
        }else{
            $datos['cobrar_seguro'] = 0;
        }

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mc_clientes', $datos);
    }

// realizar un reporte en excel
    public function excel()
    {
        $fecha1 = $this->input->post('fecha1');
        $fecha2 = $this->input->post('fecha2');

        $sql = "select mc_clientes.primer_nombre,mc_clientes.segundo_nombre,mc_clientes.apellidos,mc_clientes.telefono,"
            . "mc_clientes.email,mc_clientes.ciudad,mc_clientes.direccion,mc_articulos.nombre,mc_articulos.id_track,"
            . "mc_articulos.estadoArticulo,mc_articulos.valor,mc_articulos.valor_paquete,mc_articulos.fecha "
            . " from mc_articulos,mc_clientes "
            . "WHERE mc_clientes.id=mc_articulos.user_id"
            . " AND mc_articulos.fecha BETWEEN '$fecha1' AND '$fecha2'";
        $query = $this->db->query($sql);
        $fields = $query->field_data();
        return array("fields" => $fields, "query" => $query);
    }

    public function emailCliente($id)
    {
        $datos = $this->McCliente->where('id', $id)->first();
        return $datos;
    }

    public function updateStateNews()
    {
        $sql = "update mc_news_prealert set estado = '0'";
        $this->db->query($sql);
    }

    public function Prealertados()
    {
        return $objPrealertados = $this->McArticulos->where('estadoArticulo', 'Prealertado')->get();
    }

    public function delMultiple()
    {
        $post = $_POST['id'];
        if ($post == null) {
            $this->session->set_flashdata('error-estados', 'Seleccionar uno de los campos');
            return false;
        }

        foreach ($post as $value) {

            $this->db->set('estadoArticulo', "eliminar");
            $this->db->where('articulo_id', $value);
            $this->db->update('mc_articulos');
        }
        return true;
    }

    public function sortArray($datos)
    {
        $new_array = [];
        $index = 0;
        foreach ($datos as $clave => $fila) {
            $suite[$clave] = $fila['suite'];
        }
        array_multisort($suite, SORT_DESC, $datos);
        foreach ($datos as $key => $val) {

            if (isset($datos[$key + 1]) && ($datos[$key]['suite'] == $datos[$key + 1]['suite'])) {
                $new_array[$index][] = $datos[$key];
                continue;
            }

            $new_array[$index][] = $datos[$key];
            $index++;
        }
        return $new_array;
    }

    public function updateAduanas($data)
    {
        $transaction = new McTransactions;
        $transaction->beginTransaction();

        $libras = 0;
        $tarifas = $this->getTarifas();
        $masDias = date('Y-m-d', strtotime('+ 1 day'));
        try {
            foreach ($data as $key => $row) {

                $libras = $this->librasPorVuelo($data[$key]);
                $valor_tarifa = $this->getValorTarifa($libras);

                foreach ($data[$key] as $article) {

                    $tracking = $article['track'];

                    $objArticulo = McArticulos::where('user_id', $article['suite'])
                        ->where('estadoArticulo', '=', 'Recibido y viajando')
                        ->where('id_track', "LIKE", "%$tracking")
                        ->first();

                    if (!isset($objArticulo) || count($objArticulo) == 0) {
                        continue;
                    }

                    $objArticulo->estadoArticulo = "Viajando";
                    $objArticulo->fecha_entrega = $masDias;
                    $objArticulo->peso = $article['peso'];
                    $objArticulo->valor = $valor_tarifa * $article['peso'];
                    $objArticulo->valor_tarifa = $valor_tarifa;

                    if ($objArticulo->tecnologia == 1) {
                        $objArticulo->valor = $tarifas['tarifa_4'] * $_SESSION['trm']['hoy'];
                    }

                    if ($objArticulo->tecnologia == 2) {
                        $objArticulo->valor = $tarifas['tarifa_5'] * $_SESSION['trm']['hoy'];
                    }

                    if (!$objArticulo->update()) {
                        throw new Exception('error al guardar un dato');
                    }
                }

            }

            $transaction->commit();
            $this->session->set_flashdata('archivo', 'El archivo de vuelo se ha procesado correctamente.');
            return true;

        } catch (Exception $exc) {

            $transaction->rollBack();
            $this->session->set_flashdata('error-archivo', 'Error al procesar el archivon de vuelo proporcionado');
            return false;
        }

    }

    public function librasPorVuelo($data)
    {
        $this->load->modelORM('McConfig');
        $objConfig = McConfig::find(1);
        $libras = 0;

        if ($data[0]['suite'] == $objConfig->default_client) {
            return $libras = 1;
        }

        foreach ($data as $row) {
            $libras += $row['peso'];
        }

        if ($libras == 0) {
            $libras = 1;
        }
        return $libras;
    }

    public function getValorTarifa($libras)
    {
        $this->load->modelORM('McConfig');

        $objConfig = McConfig::find(1);
        $valor_tarifa = 0;
        $trm = $_SESSION['trm']['hoy'];

        if ($libras >= 0 && $libras <= 19) {
            $valor_tarifa = $objConfig->tarifa * $trm;
        }
        if ($libras >= 20 && $libras <= 49) {
            $valor_tarifa = $objConfig->tarifa_2 * $trm;
        }
        if ($libras >= 50) {
            $valor_tarifa = $objConfig->tarifa_3 * $trm;
        }

        return round($valor_tarifa);
    }

    public function getTarifas()
    {
        $this->load->modelORM('McConfig');
        $objConfig = McConfig::find(1);
        $tarifas = [
            'tarifa' => $objConfig->tarifa,
            'tarifa_2' => $objConfig->tarifa_2,
            'tarifa_3' => $objConfig->tarifa_3,
            'tarifa_4' => $objConfig->tarifa_4,
            'tarifa_5' => $objConfig->tarifa_5,
            'comercial' => $objConfig->tarifa_manejo,
        ];
        return $tarifas;
    }

    public function getPrealertasFecha($fecha_inicio,$fecha_fin){
        $objArticulos = McArticulos::where('estadoArticulo','Prealertado')
            ->whereBetween('fecha_registro',[$fecha_inicio,$fecha_fin])
            ->get();

        return $objArticulos;
    }

}
