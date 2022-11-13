<?php
class GstOrdenes extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McConfig');
        $this->load->modelORM('McArticulosOrdenes');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->modelORM('McPagosOrdenesTc');
        $this->load->modelORM('McPagosOrdenesComprobantes');
        $this->load->modelORM('McFacturas');
        $this->load->GstClass('GstAdmin');
        $this->load->modelORM('McBodegajes');
        $this->load->modelORM('McAbonosOrdenes');

    }

    /**
     * Metodo que crea la orden de compra
     * 2018/05/10
     * cdiaz@magical.com.co
     */
    public function crearOrden($cliente_id, $valor, $envio_nacional, $articulos, $seguro, $tipo_envio, $descuento, $impuestos)
    {
        $objOrden = new McOrdenesCompras;
        $objOrden->user_id = $cliente_id;
        $objOrden->valor = $valor;
        $objOrden->flete_nacional = $envio_nacional;
        $objOrden->factura = time();
        $objOrden->fecha = time();
        $objOrden->estado = 0;
        $objOrden->seguro = $seguro;
        $objOrden->tipo_envio = $tipo_envio;
        $objOrden->descuento = $descuento;
        $objOrden->impuestos = $impuestos;
        $objOrden->save();

        //relaciona los articulos con la orden de compra
        foreach ($articulos as $row) {
            $objArticuloOrden = new McArticulosOrdenes;
            $objArticulo = McArticulos::find($row->articulo_id);

            $objArticuloOrden->orden_id = $objOrden->id;
            $objArticuloOrden->articulo_id = $row->articulo_id;
            $objArticuloOrden->save();

            $objArticulo->valor_seguro = $this->calcularSeguro($objArticulo);
            $objArticulo->update();
        }

        return $objOrden;

    }
    /**
     * Metodo que retorna el listado de ordenes de compras activas
     */
    public function getOrdenesLst($user_id)
    {
        return McOrdenesCompras::where('user_id', $user_id)
        ->where("estado","!=","5")
        ->orderBy("id","DESC")
        ->get();
        
    }

    public function getOrdenPendiente($user_id)
    {
        return McOrdenesCompras::where('user_id', $user_id)
            ->where("estado",0)
            ->get()
            ->first();
    }

    public function getOrden($id)
    {
        return McOrdenesCompras::find($id);
    }

    public function uploadComprobante($user_id = null)
    {

        $userdata = $this->session->userdata('user');
        $id_path = $userdata['id'];

        if ($user_id !== null) {
            $id_path = $user_id;
        }
        if (!file_exists("./uploads/comprobantes/$id_path/")) {
            mkdir("./uploads/comprobantes/$id_path/", 0755, true);
        }
        $config['upload_path'] = "./uploads/comprobantes/$id_path/";
        $config['allowed_types'] = 'png|jpg|jepg|jpeg|PNG|JPG|JPEG|JEPG|TIFF|tiff|bmp|BMP|JFIF|jfif|pdf|PDF';
        $config['file_name'] = time();
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("comprobante")) {
            return false;
        }

        return $config['file_name'];
    }

    public function newPagoComprobante($id, $nombre_img, $extension)
    {
        $userdata = $this->session->userdata('user');
        $id_path = $userdata['id'];
        $objOrdenComprobante = new McPagosOrdenesComprobantes;

        $objOrdenComprobante->imagen = $nombre_img . ".$extension";
        $objOrdenComprobante->path = "/uploads/comprobantes/$id_path/";
        $objOrdenComprobante->fecha = time();
        $objOrdenComprobante->orden_id = $id;
        if (!$objOrdenComprobante->save()) {
            return false;
        }

        $objOrden = McOrdenesCompras::find($id);
        $objOrden->estado = "3";
        if (!$objOrden->update()) {
            return false;
        }
        $this->setContadorAprobacion();
        return true;

    }

    public function getOrdenesLstAdmin()
    {
        return $objOrdenes = McOrdenesCompras::whereNotIn('estado', [3, 1, 5, 2])->get();
    }

    public function getOrdenesPagadas($limit, $offset, $q = null)
    {
        $this->db->select('mc_ordenes_compras.id as orden_id, mc_ordenes_compras.factura as orden_numero ,mc_ordenes_compras.id as orden_id, mc_clientes.primer_nombre , mc_clientes.apellidos, mc_ordenes_compras.valor as orden_valor, mc_ordenes_compras.fecha as fecha_registro, mc_facturas.fecha as fecha_pago, mc_facturas.path as path, mc_facturas.nombre as name_path');
        $this->db->from('mc_ordenes_compras');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_ordenes_compras.user_id');
        $this->db->join('mc_facturas', 'mc_facturas.orden_id = mc_ordenes_compras.id');
        $this->db->where('mc_ordenes_compras.estado', 1);
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
            $this->db->group_end();

        }
        $this->db->order_by('mc_facturas.id', 'desc');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();

    }

    public function rowsOrdenesPagadas($q)
    {
        $this->db->select('*');
        $this->db->from('mc_ordenes_compras');
        $this->db->join('mc_clientes', 'mc_clientes.id = mc_ordenes_compras.user_id');
        $this->db->join('mc_facturas', 'mc_facturas.orden_id = mc_ordenes_compras.id');
        $this->db->where('mc_ordenes_compras.estado', 1);
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
            $this->db->group_end();

        }
        $this->db->order_by('mc_ordenes_compras.id', 'desc');

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getOrdenesAnuladas()
    {
        return $objOrdenes = McOrdenesCompras::whereIn("estado", [2, 5])->get();
    }

    public function getOrdenesAprobacion()
    {
        return $objOrdenes = McOrdenesCompras::where("estado", '3')->get();
    }

    public function aprobarOrden($id)
    {
        
        $objOrden = $this->getOrden($id);
        
        if(!$objOrden){
            return false;
        }

        if (!$this->aprobarArticulosOrden($id)) {
            return false;
        }

        $objOrden->estado = "1";
        if (!$objOrden->update()) {
            return false;
        }
        return true;

    }

    public function rechazarOrden($id)
    {
        $objOrden = $this->getOrden($id);
        $objOrden->estado = "4";
        if (!$objOrden->update()) {
            return false;
        }
        return true;
    }

    public function updComprobante($id, $nombre_img, $extension, $comprobante_id)
    {
        $userdata = $this->session->userdata('user');
        $id_path = $userdata['id'];
        $objOrdenComprobante = McPagosOrdenesComprobantes::find($comprobante_id);

        $objOrdenComprobante->imagen = $nombre_img . ".$extension";
        $objOrdenComprobante->path = "/uploads/comprobantes/$id_path/";
        $objOrdenComprobante->fecha = time();

        if (!$objOrdenComprobante->update()) {
            return false;
        }

        $objOrden = McOrdenesCompras::find($id);
        $objOrden->estado = "3";
        if (!$objOrden->update()) {
            return false;
        }
        $this->setContadorAprobacion();
        return true;
    }

    public function aprobarArticulosOrden($orden_id)
    {
        $objOrden = $this->getOrden($orden_id);
        $status = "Disponible";
        $ciudad = strtolower($objOrden->cliente->ciudad);
        if (strpos($ciudad, 'cali') !== false) {
            $status = "En tus manos";
        }
        foreach ($objOrden->articulos as $row) {
            $row->articulo->estadoArticulo = $status;
            $row->articulo->update();
        }
        return true;
    }

    /**
     * Inicia la transaccion con pasarela de pago cambiando el estado de la orden a 6=>"Por confirmar"
     * 13/10/2020 
     * cdiaz@skylabs.com.co
     */
    public function initPayment($id,$payment_method = "pse")
    {
        $objOrden = McOrdenesCompras::find($id);
        $search = ['$', '.', ' '];
        
        if (!$objOrden) {
            $this->session->set_flashdata('msgError', 'No se pudo encontrar la orden');
            return false;
        }

        if ($objOrden->estado == "1") {
            $this->session->set_flashdata('msgOk', 'El pago ha sido aprobado y se ha enviado la factura correspondiente a su correo electrónico, muchas gracias.');
            return true;
        }
      
        $data = $this->GstEpayco->calcularComision($objOrden);
        $objOrden->estado = "6"; //orden por confirmar
        $objOrden->valor_recargo = str_replace($search, "", $data[$payment_method]['comision_pago']);
        $objOrden->valor = str_replace($search, "", $data[$payment_method]['total']);
        
        if(!$objOrden->update()){
            return false;
        }

        return true;
    }

    public function resetPayment($id)
    {
        $objOrden = McOrdenesCompras::find($id);

        if (!$objOrden) {
            $this->session->set_flashdata('msgError', 'No se pudo encontrar la orden');
            return false;
        }

        if($objOrden->estado !=6){
            $this->session->set_flashdata('msgError', 'No se puede actualizar la orden');
            return false;
        }

        $objOrden->estado = 0;
        $objOrden->valor = $objOrden->valor - $objOrden->valor_recargo;
        $objOrden->valor_recargo = 0;
        
        if(!$objOrden->update()){
            $this->session->set_flashdata('msgError', 'No se pudo actualizar la orden');
            return false;
        }

        return true;
    }

    public function newPago($orden_id)
    {
        return true;
    }

    public function getPagos()
    {
        $userdata = $this->session->userdata('user');
        return $this->McPagosArticulos->where('user_id', $userdata['id'])->get();

    }

    public function validateFirm($firm)
    {
        if ($this->McPagosArticulos->firm != $firm) {
            return false;
        }
        return true;
    }

    public function setContadorAprobacion()
    {
        $this->load->modelORM('McPagosContador');
        $objContador = McPagosContador::find(1);
        $objContador->aprobacion = $objContador->aprobacion + 1;
        $objContador->update();
    }

    public function setContadorPagos()
    {
        $this->load->modelORM('McPagosContador');
        $objContador = McPagosContador::find(1);
        $objContador->pagos = $objContador->pagos + 1;
        $objContador->update();
    }

    public function unsetPagos()
    {
        $this->load->modelORM('McPagosContador');
        $objContador = McPagosContador::find(1);
        $objContador->pagos = 0;
        $objContador->update();
    }

    public function unsetAprobacion()
    {
        $this->load->modelORM('McPagosContador');
        $objContador = McPagosContador::find(1);

        $objContador->aprobacion = $objContador->aprobacion - 1;
        if ($objContador->aprobacion <= 0) {
            $objContador->aprobacion = 0;
        }
        $objContador->update();
    }

    public function getOrdenesActivas($user_id)
    {
        return $objOrdenes = McOrdenesCompras::where('user_id', $user_id)
            ->where('estado', '0')
            ->first();
    }

    public function agregarPaquetes($orden_id, $cliente_id, $flete, $articulos)
    {
        $sum = 0;
        $seguro_adicional = 0;
        $search = ['$', '.', ' '];
        $flete = str_replace($search, "", trim($flete));
        $objOrden = McOrdenesCompras::find($orden_id);
        $seguro = $objOrden->seguro;

        foreach ($articulos as $key => $value) {

            $objArticulo = McArticulos::find($value);
            $sum = $sum + $objArticulo->valor;

            $objArticuloOrden = new McArticulosOrdenes;
            $objArticuloOrden->articulo_id = $value;
            $objArticuloOrden->orden_id = $orden_id;
            $objArticuloOrden->save();

            $objArticulo->valor_seguro = $this->calcularSeguro($objArticulo);
            $seguro_adicional += $objArticulo->valor_seguro;
            $objArticulo->estadoArticulo = "Orden";
            $objArticulo->update();

        }
        $seguro += $seguro_adicional;
        $objOrden->seguro = $seguro;
        $objOrden->valor = ($objOrden->valor - $objOrden->flete_nacional + $sum) + $flete + $seguro_adicional;
        $objOrden->flete_nacional = $flete;
        $objOrden->update();
        return true;
    }

    public function pagoManual($id, $observaciones)
    {
        $objOrden = McOrdenesCompras::find($id);
        $objOrden->estado = "1";
        $objOrden->observaciones = $observaciones;
        $objOrden->update();
        $this->aprobarArticulosOrden($objOrden->id);
    }

    public function newFactura($orden_id)
    {
        $objOrden = $this->getOrden($orden_id);
        $nombre = time();
        $objFactura = new McFacturas;
        $objFactura->nombre = $nombre . ".pdf";
        $objFactura->path = "uploads/facturas/$objOrden->user_id/";
        $objFactura->valor = $objOrden->valor;
        $objFactura->fecha = $nombre;
        $objFactura->orden_id = $objOrden->id;
        $objFactura->save();
        return $objFactura;
    }

    public function eliminar($id, $observaciones)
    {
        $objOrden = $this->getOrden($id);
        if ($objOrden->estado != 0) {
            return false;
        }
        $this->restablecerArticulos($objOrden->id);
        $objOrden->estado = "5";
        $objOrden->observaciones = $observaciones;
        $objOrden->update();
        return true;
    }

    public function restablecerArticulos($id)
    {
        $objOrden = $this->getOrden($id);
        foreach ($objOrden->articulos as $row) {
            $row->articulo->estadoArticulo = "En Cali";
            $row->articulo->update();
            $row->delete();
        }
        return true;
    }

    public function abandonarOrden($id)
    {
        $objOrden = $this->getOrden($id);
        if ($objOrden->estado != 0) {
            return false;
        }
        $this->abandonarArticulos($objOrden->id);
        $objOrden->estado = "2";
        $objOrden->update();
        return true;
    }

    public function abandonarArticulos($id)
    {
        $objOrden = $this->getOrden($id);
        foreach ($objOrden->articulos as $row) {
            $row->articulo->estadoArticulo = "Abandonado";
            $row->articulo->update();
        }
        return true;
    }

    public function aplicarDescuento($descuento, $orden_id)
    {

        $objOrden = $this->getOrden($orden_id);
        $total = $objOrden->valor - $descuento;

        if ($objOrden->valor <= $descuento) {
            $total = "0";
        }
        $objOrden->valor = $total;
        $objOrden->descuento = $descuento;
        $objOrden->update();
    }

    public function createComprobante($user_id, $nombre_img, $extension, $orden_id)
    {
        $id_path = $user_id;
        $objOrdenComprobante = new McPagosOrdenesComprobantes;

        $objOrdenComprobante->imagen = $nombre_img . ".$extension";
        $objOrdenComprobante->path = "/uploads/comprobantes/$id_path/";
        $objOrdenComprobante->fecha = time();
        $objOrdenComprobante->orden_id = $orden_id;
        if (!$objOrdenComprobante->save()) {
            return false;
        }
        return true;
    }

    public function getPagosReport()
    {
        $fecha1 = strtotime(str_replace('/', '-', $this->input->post('fecha1')) . ' 00:00:00');
        $fecha2 = strtotime(str_replace('/', '-', $this->input->post('fecha2')) . ' 23:59:59');

        return McFacturas::whereBetween('fecha', [$fecha1, $fecha2])->get();
    }

    public function delDomicilio($orden_id)
    {
        $objOrden = $this->getOrden($orden_id);
        $objOrden->valor = $objOrden->valor - $objOrden->flete_nacional;
        $objOrden->flete_nacional = 0;
        $objOrden->update();
    }

    public function setDomicilio($orden_id, $domicilio)
    {
        $search = ['$', '.', ' '];
        $domicilio = str_replace($search, "", trim($domicilio));
        $objOrden = $this->getOrden($orden_id);
        $objOrden->valor = $objOrden->valor - $objOrden->flete_nacional;
        $objOrden->valor = $objOrden->valor + $domicilio;
        $objOrden->flete_nacional = $domicilio;
        $objOrden->update();

    }
    /**
     * Metodo que obtiene la trm del dia por el web service de la superfinanciera
     * 2018/08/16
     * cdiaz@skylabs.com.co
     */
    public function getTrm()
    {
        /* $date = date("Y-m-d");
        try {
            $soap = new soapclient("https://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService?WSDL", array(
                'soap_version' => SOAP_1_1,
                'trace' => 1,
                "location" => "http://www.superfinanciera.gov.co/SuperfinancieraWebServiceTRM/TCRMServicesWebService/TCRMServicesWebService",
            ));
            $response = $soap->queryTCRM(array('tcrmQueryAssociatedDate' => $date));
            $response = $response->return;
            if ($response->success) {
                return round($response->value);
            }
        } catch (Exception $e) {
            return false;
        } */
        $trm = "0";
        $homepage = file_get_contents('https://dolar.wilkinsonpc.com.co/divisas/dolar.html');
        $dom = new DOMDocument();
        $dom->loadHTML($homepage);
        $spans = $dom->getElementsByTagName('span');
        
        foreach ($spans as $span) {
            if('numero' === $span->getAttribute('class')){
                $trm = str_replace(',','',$span->nodeValue);
                return round($trm);
            }
        }
        return round($trm);
    }

    /**
     * Metodo que obtiene los articulos del cliente en estado Cali
     * 2018/08/21
     * cdiaz@magical.com.co
     */
    public function getArticulosCali($user_id)
    {
        $objArticulo = McArticulos::where('user_id', $user_id)
            ->where('estadoArticulo', 'En Cali')
            ->get();

        return $objArticulo;
    }

    public function calcularSeguro($objArticulo)
    {
        $objConfig = McConfig::find(1);
        $valor_seguro = 0;
        $valor_paquete_cop = $objArticulo->valor_paquete * $_SESSION['trm']['hoy'];

        if ($objArticulo->valor_paquete >= $objConfig->seguro_max+1) {
            $valor_seguro = ($valor_paquete_cop * $objConfig->seguro_obligatorio)/100;
        }

        if ($objArticulo->valor_paquete >= $objConfig->seguro_min && $objArticulo->valor_paquete <= $objConfig->seguro_max && $objArticulo->seguro == "si") {
            $valor_seguro = ($valor_paquete_cop * $objConfig->seguro_opcional)/100;
        }

        if($objArticulo->cliente->cobrar_seguro==1){
            $valor_seguro = 0;
        }

        return $valor_seguro;
    }

    public function getOrdenesPendientes()
    {
        return $objOrdenes = McOrdenesCompras::where("estado", '0')->get();
    }

    public function delBodegaje($orden_id)
    {
        $objOrden = $this->getOrden($orden_id);

        foreach ($objOrden->bodegajes as $row) {
            $row->delete();
        }

        $objOrden->valor = $objOrden->valor - $objOrden->valor_bodega;
        $objOrden->valor_bodega = 0;
        $objOrden->update();
        return true;
    }

    public function crearAbono($orden_id,$user_id,$nombre_img,$valor){
        $objAbono = new McAbonosOrdenes;
        $objAbono->valor = $valor;
        $objAbono->fecha = time();
        $objAbono->comprobante = "/uploads/comprobantes/$user_id/".$nombre_img;
        $objAbono->orden_id = $orden_id;

        if(!$objAbono->save()){
            return false;
        }
        return true;
    }

    public function setObservaciones($observaciones,$id){
        $objOrden = $this->getOrden($id);
        $objOrden->observaciones = $observaciones;
        $objOrden->update();
    }

    public function setDescuento($descuento, $orden_id){
        $objOrden = $this->getOrden($orden_id);
        
        $total = ($objOrden->valor + $objOrden->descuento)-$descuento;

        if ($objOrden->valor < $descuento) {
            $total = "0";
        }
        $objOrden->valor = $total;
        $objOrden->descuento = $descuento;
        $objOrden->update();
    }

  

}
