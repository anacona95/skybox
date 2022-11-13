<?php

class GstBodegajes extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        $this->load->modelORM('McBodegajes');
        $this->load->modelORM('McLogBodegajes');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->modelORM('McArticulos');

    }

    public function setBodejage($id)
    {
        $trm = $_SESSION['trm']['hoy'];
        $objOrden = McOrdenesCompras::find($id);
        $objOrden->valor_bodega += $trm;
        $objOrden->valor += $trm;
        $objOrden->update();

        $objBodegaje = new McBodegajes;
        $objBodegaje->valor = $trm;
        $objBodegaje->fecha = time();
        $objBodegaje->orden_id = $objOrden->id;
        $objBodegaje->save();

    }

    public function setLog()
    {
        $objLogBodegajes = new McLogBodegajes;
        $objLogBodegajes->fecha = time();
        $objLogBodegajes->save();
    }

    public function getLog()
    {
        return $objLogBodegajes = McLogBodegajes::orderBy('id', 'desc')->first();
    }

    public function getPrealertas()
    {
        return $objArticulos = McArticulos::where('estadoArticulo','Prealertado')->get();
    }

    public function delArticulo($articulo_id)
    {
        $objArticulo = McArticulos::find($articulo_id);

        if(!$objArticulo->delete()){
            return false;
        }

        return true;
    }
}
