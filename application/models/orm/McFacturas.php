<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McFacturas extends Eloquent
{

    protected $table = 'mc_facturas';
    public $timestamps = false;

    public function orden()
    {
        return $this->hasOne('McOrdenesCompras', 'id', 'orden_id');
    }

}
