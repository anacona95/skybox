<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McAbonosOrdenes extends Eloquent
{

    protected $table = 'mc_abonos_ordenes';
    public $timestamps = false;

    public function orden()
    {
        return $this->hasOne('McOrdenesCompras', 'id', 'orden_id');
    }



}
