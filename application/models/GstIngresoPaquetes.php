<?php

/**
 * Clase gestion para ingreso de paquetes
 */
class GstIngresoPaquetes extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GstIngresoPaquetes');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McCliente');

    }

    public function getArticle($tracking)
    {

        if ($tracking == "" || $tracking == null) {
            return $response = ["response" => false];
        }
        $track = strtoupper(substr($tracking, '-10'));
        $tarifas = $this->getTarifas();
        $response = [];
        $objArticulo = McArticulos::where('id_track', 'LIKE', "%" . $track)
            ->where('estadoArticulo', 'Recibido y viajando')
            ->first();

        if (!$objArticulo) {
            return $response = ["response" => false];
        }
        $response = [
            "response" => true,
            "articulo_id" => $objArticulo->articulo_id,
            "nombre" => $objArticulo->nombre,
            "peso" => $objArticulo->peso,
            "id_track" => $objArticulo->id_track,
            "fecha" => $objArticulo->fecha,
            "fecha_entrega" => $objArticulo->fecha_entrega,
            "descripcion" => $objArticulo->descripcion,
            "seguro" => $objArticulo->seguro,
            "valor" => $objArticulo->valor,
            "valor_paquete" => $objArticulo->valor_paquete,
            "cliente" => $objArticulo->cliente->primer_nombre . " " .
            $objArticulo->cliente->segundo_nombre . " " .
            $objArticulo->cliente->apellidos,
            "pais" => $objArticulo->cliente->pais,
            "ciudad" => $objArticulo->cliente->ciudad,
            "direccion" => $objArticulo->cliente->direccion,
            "user_id" => $objArticulo->user_id,
            "tecnologia" => $objArticulo->tecnologia,
            "valor_tarifa" => $tarifas["tarifa"],
            "valor_tarifa_comercial" =>  $tarifas["tarifa_manejo"],
        ];
        
        if($objArticulo->cliente->tarifa){
            $response['valor_tarifa'] = $objArticulo->cliente->tarifa;
        }

        if($objArticulo->cliente->tarifa_comercial){
            $response['valor_tarifa_comercial'] = $objArticulo->cliente->tarifa_comercial;
        }

        return $response;
    }

    public function ingresarPaquete($id, $peso, $user_id, $tecnologia, $tarifa_especial, $cantidad)
    {
        $search = ['$', ' '];
        $tarifa_especial = str_replace($search, "", $tarifa_especial);
        $tarifas = $this->getTarifas();
        $valor_tarifa = $tarifa_especial * $_SESSION['trm']['hoy'];

        $objArticulo = McArticulos::find($id);
        $objArticulo->peso = $peso;
        $objArticulo->valor = $valor_tarifa * $peso;
        $objArticulo->estadoArticulo = "En Cali";
        $objArticulo->user_id = $user_id;

        if ($objArticulo->valor_paquete >= 500) {
            $objArticulo->seguro = "si";
        }
        if ($tarifa_especial > $tarifas['tarifa_minima']) {
            $objArticulo->valor = round($tarifa_especial * $_SESSION['trm']['hoy']) * $peso;
        }
        if ($tecnologia == 1) {//tarifa para celulares
            $objArticulo->valor = ($tarifas['tarifa_4'] * $_SESSION['trm']['hoy']) * $cantidad;
        }
        if ($tecnologia == 2) {//tarifa para laptops y tablets
            $objArticulo->valor = ($tarifas['tarifa_5'] * $_SESSION['trm']['hoy']) * $cantidad;
        }

        $objArticulo->trm=$_SESSION['trm']['hoy'];
        $objArticulo->tarifa= $tarifa_especial;
        $objArticulo->fecha_punteo= time();


        $objArticulo->update();
        $this->session->set_flashdata("msgOk", "El paquete ha sido ingresado a la bodega con éxito.");
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
            'tarifa_minima' => $objConfig->tarifa_minima,
            'tarifa_manejo' => $objConfig->tarifa_manejo,
        ];
        return $tarifas;
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
}
