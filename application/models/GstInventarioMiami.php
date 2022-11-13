<?php

/**
 * Clase gestion para ingreso de paquetes
 */
class GstInventarioMiami extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('GstIngresoPaquetes');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McCliente');

    }

    public function getArticulos()
    {
        return McArticulos::where('estadoArticulo', 'En miami')->get();
    }

    public function getArticle($tracking)
    {

        if ($tracking == "" || $tracking == null) {
            return $response = ["response" => false];
        }
        $track = strtoupper(substr($tracking, '-10'));

        $response = [];
        $objArticulo = McArticulos::where('id_track', 'LIKE', "%" . $track)
            ->where('estadoArticulo', 'Prealertado')
            ->first();

        if (!$objArticulo) {
            return $response = ["response" => false];
        }
        return $response = [
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
        ];
    }

    public function ingresarPaquete($id, $peso, $flete, $user_id)
    {
        $objArticulo = McArticulos::find($id);
        $objArticulo->peso = $peso;
        $objArticulo->valor = $flete;
        $objArticulo->estadoArticulo = "En miami";
        $objArticulo->user_id = $user_id;
        $objArticulo->update();
        $this->session->set_flashdata("msgOk", "El paquete ha sido ingresado a la bodega con éxito.");
    }

    public function crearPaquete()
    {
        $this->load->modelORM('McConfig');
        $objConfig = McConfig::find(1);
        $search = ['$', '.', ' '];
        $flete = str_replace($search, "", trim($flete));
        $objArticulo = new McArticulos;
        $objArticulo->nombre = utf8_encode($this->input->post("n_nombre"));
        $objArticulo->id_track = strtoupper($this->input->post("n_tracking"));
        $objArticulo->user_id = $objConfig->default_client;
        $objArticulo->peso = $this->input->post("n_peso");
        $objArticulo->valor = str_replace($search, "", trim($this->input->post("n_flete")));
        $objArticulo->tipo = "envio";
        $objArticulo->fecha = date('Y-m-d');
        $objArticulo->fecha_entrega = date('Y-m-d', strtotime('+ 1 day'));
        $objArticulo->fecha_reporte = date("Y-m-d");
        $objArticulo->descripcion = "Artículo no prealertado";
        $objArticulo->seguro = "no";
        $objArticulo->valor_paquete = "0";
        $objArticulo->puntos = "0";
        $objArticulo->estadoArticulo = "En miami";
        $objArticulo->save();

    }
}
