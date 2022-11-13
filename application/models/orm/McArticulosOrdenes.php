<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McArticulosOrdenes extends Eloquent
{

    protected $table = 'mc_articulos_ordenes';
    public $timestamps = false;

    public function articulo()
    {
        return $this->hasOne('McArticulos', 'articulo_id', 'articulo_id');
    }

}
