<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McCupones extends Eloquent
{

    protected $table = 'mc_cupones';
    public $timestamps = false;
    public $estados = ["1"=>"Activo","2"=>"Inactivo"];

}
