<?php

class GstPagos extends CI_Model
{
    /**
     * Constructor de la clase de gestion de pagos
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McPagosOrdenesTc');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->modelORM('McArticulos');
        $this->load->model('GstOrdenes');
        $this->load->model('ConfigEmail');
    }

    /**
     * Retorna el listado de articulos para pagar
     */
    public function getArticulos()
    {
        $user_data = $this->session->userdata('user');
        return $this->McArticulos->where('estadoArticulo', 'En tus manos')
            ->where('user_id', $user_data['id'])
            ->get();

    }
    public function getArticulo($id)
    {
        return $this->McArticulos->find($id);
    }

    public function newPago($valor, $id, $status)
    {
        $this->load->library('pdfgenerator');
        $objOrden = McOrdenesCompras::find($id);

        if (!$objOrden) {
            return false;
        }
        $recargo = $objOrden->valor * 0.042;
        $objPagosOrdenesTc = new McPagosOrdenesTc;
        $objPagosOrdenesTc->fecha = time();
        $objPagosOrdenesTc->estado = $status;
        $objPagosOrdenesTc->valor = $valor;
        $objPagosOrdenesTc->ip = $_SERVER['REMOTE_ADDR'];
        $objPagosOrdenesTc->orden_id = $objOrden->id;
        $objPagosOrdenesTc->save();

        if ($status != "1") {
            return false;
        }
        //actualiza el estado de la orden cuando se aprueba el pago
        $objOrden->estado = "1"; //orden pagada
        $objOrden->valor_recargo = $recargo;
        $objOrden->update();

        $this->GstOrdenes->aprobarArticulosOrden($objOrden->id);
        $this->GstOrdenes->setContadorPagos();

        $data["libras"] = 0;
        $data['objFactura'] = $this->GstOrdenes->newFactura($id);
        $id_path = $data['objFactura']->orden->user_id;
        foreach ($data['objFactura']->orden->articulos as $row) {
            $data["libras"] = $data["libras"] + $row->articulo->peso;
        }
        $data['count'] = 0;
        $html = $this->load->view('ordenes/pdfTemplateFactura', $data, true);

        $pdf_content = $this->pdfgenerator->generate($html, null, false);

        if (!file_exists("./uploads/facturas/$id_path/")) {
            mkdir("./uploads/facturas/$id_path/", 0755, true);
        }

        $nombre = $data['objFactura']->nombre;
        file_put_contents("./uploads/facturas/$id_path/$nombre", $pdf_content);

        $msg = $this->load->view('ordenes/emailPagos', $data, true);

        $this->ConfigEmail->to($data['objFactura']->orden->cliente->email);
        $this->ConfigEmail->subject('PAGO RECIBIDO');
        $this->ConfigEmail->message($msg);
        //$this->ConfigEmail->from("compras@micasillero.co", 'Micasillero');
        $this->ConfigEmail->attach("./" . $data['objFactura']->path . $data['objFactura']->nombre);
        $this->ConfigEmail->send();

        return true;
    }

    public function getPagos()
    {
        $userdata = $this->session->userdata('user');
        return $this->McPagosArticulos->where('user_id', $userdata['id'])->get();

    }

    /**
     * Metodo que actualiza la trm en el sistema
     * 2018/10/03
     * @author Cristhian Diaz <cdiaz@magical.com.co>
     * @param [string] $trm
     * @return boolval
     */
    public function updTrm($trm)
    {
        $this->load->modelORM('McConfig');
        $objMcConfig = McConfig::find(1);
        $objMcConfig->trm = $trm;
        if (!$objMcConfig->update()) {
            return false;
        }
        return true;
    }

}
