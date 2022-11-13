<?php
/**
 * Clase encargada de procesar confirmacion de pagosa
 * 2018/10/03
 * @author Cristhian Diaz <cdiaz@magical.com.co>
 */
class Payment extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->load->model('GstOrdenes');
        $this->load->model('GstPagos');
    }

    /**
     * Metodo que actualiza la trm en el sistema
     * 2018/10/03
     * @author Cristhian Diaz <cdiaz@magical.com.co>
     * @return void
     */
    public function updTrm()
    {
        $this->GstPagos->updTrm($this->GstOrdenes->getTrm());
        exit;
    }
}
