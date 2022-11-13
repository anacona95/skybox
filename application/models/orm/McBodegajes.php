<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McBodegajes extends Eloquent
{

    protected $table = 'mc_bodegajes';
    public $timestamps = false;

    public function orden()
    {
        return $this->hasOne('McOrdenesCompras', 'id', 'orden_id');
    }

}
