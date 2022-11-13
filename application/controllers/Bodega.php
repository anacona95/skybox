<?php

class Bodega extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        $this->load->GstClass('GstOrdenes');
        $this->load->GstClass('GstBodegajes');
        $this->load->GstClass('ConfigEmail');
        $this->load->GstClass('GstApi');

    }

    public function index()
    {
        $fecha_actual = time();
        $objOrdenes = $this->GstOrdenes->getOrdenesPendientes();
        $objLogBodegaje = $this->GstBodegajes->getLog();

        if ($objLogBodegaje) {

            $fecha_log = date('d/m/Y', $objLogBodegaje->fecha);
            $fecha_actual_log = date('d/m/Y', $fecha_actual);

            if ($fecha_log === $fecha_actual_log) {
                exit;
            }
        }

        foreach ($objOrdenes as $objOrden) {
            $fecha_inicio = $objOrden->fecha;
            $segundos = $fecha_inicio - $fecha_actual;
            $dias = $segundos / (60 * 60 * 24);
            $dias = abs($dias);
            $dias = floor($dias);
            //valida si han transcurrido 16 dias para adicionar el valor de la trm
            if ($dias >= 16) {
                $this->GstBodegajes->setBodejage($objOrden->id);
            }
        }
        $this->GstBodegajes->setLog();
        exit;
    }

    public function limpiarPrealertas()
    {
        $fecha_actual = time();
        $objArticulos = $this->GstBodegajes->getPrealertas();
        
        foreach ($objArticulos as $objArticulo) {
            $fecha_inicio = $objArticulo->fecha_registro;
            $segundos = $fecha_inicio - $fecha_actual;
            $dias = $segundos / (60 * 60 * 24);
            $dias = abs($dias);
            $dias = floor($dias);
            //valida si han transcurrido 120 dias elimina el articulo
            if ($dias >= 120) {
                $this->GstBodegajes->delArticulo($objArticulo->articulo_id);
            }
        }

        exit;
    }

    public function alertarOrdenes()
    {
        $fecha_actual = time();
        $objOrdenes = $this->GstOrdenes->getOrdenesPendientes();

        foreach ($objOrdenes as $objOrden) {
            $fecha_inicio = $objOrden->fecha;
            $segundos = $fecha_inicio - $fecha_actual;
            $dias = $segundos / (60 * 60 * 24);
            $dias = abs($dias);
            $dias = floor($dias);
            $data['objOrden'] = $objOrden;
            
            //valida si han transcurrido 60 dias y abandona la orden
            if ($dias >= 60) {
                //si la orden tiene abonos no debe abandonarla
                if($objOrden->totalAbonos() > 0){
                    continue;
                }

                $res = $this->GstOrdenes->abandonarOrden($objOrden->id);

                if(!$res){
                    continue;
                }

                $msg = $this->load->view('ordenesAdmin/emailAbandonoAutomatico', $data, true);

                $this->ConfigEmail->to($data['objOrden']->cliente->email);
                $this->ConfigEmail->subject('Tu orden ha sido abandonada.');
                $this->ConfigEmail->message($msg);
                $this->ConfigEmail->send();
                $this->GstApi->sendMessageApp("Tu orden #".$data['objOrden']->factura.' ha sido abandonada.',$data['objOrden']->cliente->device);
                continue;
            }
            //valida si han transcurrido 45 dias notificacion en los proximos 15 dias abandona orden
            if ($dias === 45) {
                $msg = $this->load->view('ordenesAdmin/emailNotificacionAbandono3', $data, true);

                $this->ConfigEmail->to($data['objOrden']->cliente->email);
                $this->ConfigEmail->subject('Notificación de abandono de orden.');
                $this->ConfigEmail->message($msg);
                $this->ConfigEmail->send();
                $this->GstApi->sendMessageApp("Tu orden #".$data['objOrden']->factura.' está a punto de vencer.',$data['objOrden']->cliente->device);
                continue;
            }
            //valida si han transcurrido 30 dias notificacion en los proximos 30 dias abandona orden
            if ($dias === 30) {
                $msg = $this->load->view('ordenesAdmin/emailNotificacionAbandono2', $data, true);

                $this->ConfigEmail->to($data['objOrden']->cliente->email);
                $this->ConfigEmail->subject('Tus paquetes están disponibles hace 30 días en Cali.');
                $this->ConfigEmail->message($msg);
                $this->ConfigEmail->send();
                $this->GstApi->sendMessageApp("Tus paquetes están disponibles hace 30 días en Cali.",$data['objOrden']->cliente->device);
                continue;
            }
             //valida si han transcurrido 15 dias notificacion 1
            if ($dias === 15) {
                $msg = $this->load->view('ordenesAdmin/emailNotificacionAbandono1', $data, true);

                $this->ConfigEmail->to($data['objOrden']->cliente->email);
                $this->ConfigEmail->subject('Tus paquetes te echan de menos.');
                $this->ConfigEmail->message($msg);
                $this->ConfigEmail->send();

                $this->GstApi->sendMessageApp("Tus paquetes te echan de menos, realiza el pago de tu orden",$data['objOrden']->cliente->device);
                continue;
                
            }
        }
        exit;
    }
}
