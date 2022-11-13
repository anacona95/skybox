<?php

class Gstuser extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation', 'session');
        $this->load->modelORM('McUser');
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McNewsPrealert');
        $this->load->modelORM('McPuntos');
        $this->load->modelORM('McTransactions');
        $this->load->GstClass('GstRegistro');
    }

//guardar envios
    public function saveEnvios($prealerta)
    {
        $transaction = new McTransactions;
        $transaction->beginTransaction();

        $objArticulo = new McArticulos;
        $objArticulo->nombre = $prealerta['nombre'];
        $objArticulo->id_track = $prealerta['id_track'];
        $objArticulo->fecha = $prealerta['fecha'];
        $objArticulo->seguro = $prealerta['seguro'];
        $objArticulo->user_id = $prealerta['user_id'];
        $objArticulo->valor_paquete = $prealerta['valor_paquete'];
        $objArticulo->tipo = $prealerta['tipo'];
        $objArticulo->estadoArticulo = $prealerta['estadoArticulo'];
        $objArticulo->peso = 0;
        $objArticulo->fecha_entrega = null;
        $objArticulo->fecha_reporte = time();
        $objArticulo->descripcion = "Artículo prealertado";
        $objArticulo->valor = 0;
        $objArticulo->fecha_registro = time();
        $objArticulo->transportadora = $prealerta['transportadora'];
        if(isset($prealerta['articulo_referer_id'])){
            $objArticulo->articulo_referer_id = $prealerta['articulo_referer_id'];
        }
        $objArticulo->tienda = $prealerta['tienda'];
        $objArticulo->factura = $prealerta['factura_path'];

        if (!$objArticulo->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return $objArticulo;
    }

//guardar datos de compra
    public function saveCompras($datos)
    {

        $this->db->insert('mc_compras', $datos);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

//consultar datos de cuenta
    public function ConsulCuenta($id_user)
    {
       return McUser::find($id_user);
    }

//consultar rastreo de paquetes
    public function ConsulPaquetes($id_user,$q=null)
    {
        $estados = ['eliminar','En tus manos','Disponible','Abandonado'];
        
        if(!$q)
        {
            return McArticulos::where('user_id',$id_user)->whereNotIn("estadoArticulo",$estados)->orderBy('articulo_id','desc')->get();
        }
        
        return McArticulos::where('user_id',$id_user)
        ->whereNotIn("estadoArticulo",$estados)
        ->where("id_track",'like',"%".strtoupper($q))
        ->orderBy('articulo_id','desc')
        ->get();
    }

//consultar alertas de envios
    public function ConsulAlertas($id_user)
    {

        $sql = "select mc_articulos.articulo_id,mc_articulos.id_track,mc_articulos.estadoArticulo,mc_articulos.tipo,mc_articulos.fecha,
                                     mc_articulos.nombre,mc_articulos.seguro,mc_articulos.descripcion,mc_articulos.valor_paquete
                              from mc_articulos,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_articulos.user_id
                              AND mc_articulos.estadoArticulo='Prealertado'
      			      AND mc_clientes.id='" . $id_user . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar paquetes para puntos
    public function ConsulPaquetesPuntos($id_user)
    {

        $sql = "select mc_articulos.articulo_id,mc_articulos.id_track,mc_articulos.estadoArticulo,mc_articulos.tipo,mc_articulos.fecha,
                                     mc_articulos.nombre,mc_articulos.seguro,mc_articulos.descripcion,mc_articulos.valor_paquete

                              from mc_articulos,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_articulos.user_iD
                              AND mc_articulos.puntos=0
                               AND mc_articulos.estadoArticulo!='En carretera'
                              AND mc_articulos.estadoArticulo!='eliminar'
                              AND mc_articulos.estadoArticulo!='En verificacion'
                              AND mc_articulos.estadoArticulo!='En tus manos'
      			      AND mc_clientes.id='" . $id_user . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar paquete para asignar punto
    public function ConsulPaquetesPuntosPuntos()
    {
        if (!is_numeric($_GET['id'])) {
            return false;
        }
        return $objPuntos = $this->McArticulos->where('articulo_id', $_GET['id'])->first();
    }

//consultar puntos del cliente
    public function ConsulPuntos($id_user)
    {
        return $objPuntos = $this->McPuntos->where('user_id', $id_user)->first();
    }

//puntos de cliente amigo
    public function puntoCliente($id_user)
    {

        $datos = $this->McPuntos->where('user_id', $id_user)->first();
        return $datos;
    }

//puntos de cliente amigo
    public function cliente($id_user)
    {
        return McCliente::find($id_user);
    }

    //consultar puntos del cliente
    public function asignarPuntos($d, $suma)
    {
        $datos = array(
            'cantidad' => $suma,
        );
        $this->db->where('user_id', $d);
        $this->db->update('mc_puntos_user', $datos);
    }

    //update al parent id de cliente a (0)
    public function cambiarParent($id_user)
    {
        $datos = array(
            'parent_id' => 0,
        );
        $this->db->where('id', $id_user);
        $this->db->update('mc_clientes', $datos);
    }

    //consultar puntos del cliente
    public function puntosPaquete($puntos, $id)
    {
        $datos = array(
            'puntos' => $puntos,
        );
        $this->db->where('articulo_id', $id);
        $this->db->update('mc_articulos', $datos);
    }

//actualixar puntos al utilizarlos
    public function restarPuntos($resta, $sumar, $id_puntos)
    {
        $datos = array(
            'cantidad' => $resta,
            'utilizados' => $sumar,
        );
        $this->db->where('id_puntos', $id_puntos);
        $this->db->update('mc_puntos_user', $datos);
    }

//consultar alertas d compras
    public function ConsulCompras($id_user)
    {

        $sql = "select mc_compras.id_compra,mc_compras.enlace,mc_compras.referencia,mc_compras.talla,mc_compras.color,
                                     mc_compras.cantidad,mc_compras.estado_compra,mc_compras.tipo
                              from mc_compras,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_compras.user_id
                              AND mc_compras.estado_compra='En cotizacion'
      			      AND mc_clientes.id='" . $id_user . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

//consultar estados de compras
    public function ConsulEstadoCompras($id_user)
    {

        $sql = "select *
                              from mc_compras,mc_clientes
			      WHERE
      			      mc_clientes.id=mc_compras.user_id
                              AND mc_compras.estado_compra!='En cotizacion'
                              AND mc_compras.estado_compra!='eliminar'
      			      AND mc_clientes.id='" . $id_user . "'";
        $result = $this->db->query($sql);
        return $result->result_array();
    }

    //cargar datos para modificar envios
    public function enviosUpdate()
    {

        $sql = "select * from mc_articulos where  articulo_id=?";
        $sql = $this->db->query($sql, $_GET['id']);
        return $sql->result();
    }

//cargar datos para modificar compras
    public function comprasUpdate()
    {

        $sql = "select * from mc_compras where  id_compra=?";
        $sql = $this->db->query($sql, $_GET['id']);
        return $sql->result();
    }

//modifivcar datos de perfil
    public function updatePerfil()
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
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('mc_clientes', $datos);
    }

//modificar envios
    public function updateEnvio()
    {

        $datos = array(
            'nombre' => $this->input->post('nombre'),
            'id_track' => $this->input->post('id_track'),
            'fecha' => $this->input->post('fecha'),
            'descripcion' => $this->input->post('descripcion'),
            'seguro' => $this->input->post('seguro'),
        );

        $this->db->where('articulo_id', $this->input->post('id'));
        $this->db->update('mc_articulos', $datos);
    }

//modificar datos de compras
    public function updateCompras()
    {

        $datos = array(
            'enlace' => $this->input->post('enlace'),
            'referencia' => $this->input->post('referencia'),
            'talla' => $this->input->post('talla'),
            'color' => $this->input->post('color'),
            'cantidad' => $this->input->post('cantidad'),
        );

        $this->db->where('id_compra', $this->input->post('id'));
        $this->db->update('mc_compras', $datos);
    }

//cambiar estado eliminar a envio
    public function estadoEnvio()
    {

        $datos = array(
            'estadoArticulo' => 'eliminar',
        );
        $this->db->where('articulo_id', $this->input->post('id'));
        $this->db->update('mc_articulos', $datos);
    }

//cambiar estado eliminar compra
    public function estadoCompra()
    {

        $datos = array(
            'estado_compra' => 'eliminar',
        );
        $this->db->where('id_compra', $this->input->post('id'));
        $this->db->update('mc_compras', $datos);
    }

    //update para la contraseña

    public function CambiarContraseña()
    {

        $id = $this->input->post('id');
        $password = $this->input->post('clave');
        $password2 = $this->input->post('claves');
        $password3 = $this->input->post('claves2');
        $pass = $this->GstRegistro->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];
        //----------------------------------------------------
        $ObjCliente = $this->McCliente->where('id', $id)->first();
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];

        if ($passDb != $truePass) {
            $this->session->set_flashdata('error-clave', ' Contraseña anterior incorrecta.');
            return false;
        }
        if ($password2 != $password3) {
            $this->session->set_flashdata('error-clave', 'Nueva contraseña y confirmación de contraseña no coinciden.');
            return false;
        }
        $data = $this->genPass(trim($password2));
        $datos = array(
            'password' => $data,
        );
        $this->db->where('id', $id);
        $this->db->update('mc_clientes', $datos);
        return true;
    }

//cambiar email usuario
    public function CambiarEmail()
    {

        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $password = $this->input->post('clave');

        $objClientes = $this->McCliente->where('email', $email)->get();
        if (count($objClientes) > 0) {
            $this->session->set_flashdata('error-correo', 'correo ya esta siendo utilizado.');
            return false;
        }
        $pass = $this->GstRegistro->genPass($password);
        $parts = explode(':', $pass);
        $truePass = $parts[1];
        $ObjCliente = $this->McCliente->where('id', $id)->first();
        $partsDb = explode(':', $ObjCliente->password);
        $passDb = $partsDb[1];
        if ($passDb != $truePass) {
            $this->session->set_flashdata('error-correo', 'Clave incorrecta.');
            return false;
        }
        $datos = array(
            'email' => $email,
        );

        $this->db->where('id', $id);
        $this->db->update('mc_clientes', $datos);
        return true;
    }

//encriptar contraseña
    public function genPass($pass)
    {
        $pass = strtoupper(sha1($pass));
        $random = strtoupper(md5(rand(1, 999)));
        $time = strtoupper(md5(time()));
        $pass = $time . ':' . $pass . ':' . $random;

        return $pass;
    }

//SUBIR IMAGEN DE PERFIL
    public function subir($imagen, $id_user)
    {

        $data = array(
            'imagen' => $imagen,
        );
        $this->db->where('id', $id_user);
        $this->db->update('mc_clientes', $data);
    }

    public function validarReferido($user_id)
    {
        $objArticulos = McArticulos::where('user_id', $user_id)->get();
        if (count($objArticulos) > 0) {
            return false;
        }
        return true;
    }

    public function getValorTarifa($libras)
    {
        $this->load->modelORM('McConfig');
        $result = [];
        $objConfig = McConfig::find(1);
        $valor_tarifa = 0;
        $tarifa = $objConfig->tarifa;
        $trm = $objConfig->trm;

        if ($libras >= 0 && $libras <= 19) {
            $valor_tarifa = $objConfig->tarifa * $trm;
        }
        if ($libras >= 20 && $libras <= 49) {
            $valor_tarifa = $objConfig->tarifa_2 * $trm;
            $tarifa = $objConfig->tarifa_2;
        }
        if ($libras >= 50) {
            $valor_tarifa = $objConfig->tarifa_3 * $trm;
            $tarifa = $objConfig->tarifa_3;
        }

        $result['valor_tarifa'] = $valor_tarifa;
        $result['tarifa'] = $tarifa;
        return $result;
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
        ];
        return $tarifas;
    }

    public function updPrealerta($prealerta)
    {
        $objArticulo = McArticulos::find($prealerta['articulo_id']);
        if(!$objArticulo){
            return false;
        }

        $objArticulo->id_track = $prealerta['id_track'];
        $objArticulo->seguro = $prealerta['seguro'];
        $objArticulo->valor_paquete = $prealerta['valor_paquete'];
        if($prealerta['factura_path']){
            $objArticulo->factura = $prealerta['factura_path'];
        }

        if(!$objArticulo->update()){
            return false;
        }

        return true;
    }

    public function eliminarPrealerta($articulo_id)
    {
        $objArticulo = McArticulos::find($articulo_id);
        
        if(!$objArticulo){
            return false;
        }
        
        $objArticulo->estadoArticulo = "eliminar";
        
        if(!$objArticulo->update()){
            return false;
        }
        
        return true;
    }
}
