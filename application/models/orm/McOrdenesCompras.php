<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McOrdenesCompras extends Eloquent
{

    protected $table = 'mc_ordenes_compras';
    public $timestamps = false;
    public $estados = ['0' => "Pendiente de pago", '1' => "Pagada", '2' => "Abandonada", '3' => "En aprobación", '4' => "Rechazada", "5" => "Anulada", "6" => "Por confirmar"];
    public $tipos = ['0' => 'Con comprobante', '1' => 'Pago en línea'];

    public function articulos()
    {
        return $this->hasMany('McArticulosOrdenes', 'orden_id', 'id');
    }

    public function cliente()
    {
        return $this->hasOne('McCliente', 'id', 'user_id');
    }

    public function comprobantes()
    {
        return $this->hasMany('McPagosOrdenesComprobantes', 'orden_id', 'id');
    }
    public function pagosTc()
    {
        return $this->hasMany('McPagosOrdenesTc', 'orden_id', 'id');
    }

    public function getFactura()
    {
        return McFacturas::where('orden_id', $this->id)
            ->first();
    }
    

    public function getLibras()
    {
        $libras = 0;
        foreach ($this->articulos as $articulo) {
            if (!$articulo->articulo->peso) {
                continue;
            }
            $libras += (int) $articulo->articulo->peso;
        }
        return $libras;
    }
    public function getFletes()
    {
        $valor = 0;
        foreach ($this->articulos as $articulo) {
            if (!$articulo->articulo->valor) {
                continue;
            }
            $valor += $articulo->articulo->valor;
        }
        return $valor;
    }
    public function getFletesCupon()
    {
        $ObjConfig = McConfig::find(1);
        $valor = 0;
        foreach ($this->articulos as $articulo) {
            if (!$articulo->articulo->valor) {
                continue;
            }

            if(!$articulo->articulo->tarifa){
                continue;
            }

            if($articulo->articulo->tarifa >= $ObjConfig->tarifa && $articulo->articulo->tarifa < $ObjConfig->tarifa_manejo){
                $valor += $articulo->articulo->valor;
            }
            
        }
        return $valor;
    }

    public function bodegajes()
    {
        return $this->hasMany('McBodegajes', 'orden_id', 'id');
    }

    public function abonos()
    {
        return $this->hasMany('McAbonosOrdenes', 'orden_id', 'id');
    }

    public function totalAbonos()
    {
        return McAbonosOrdenes::where('orden_id', $this->id)->sum('valor');
    }

}
