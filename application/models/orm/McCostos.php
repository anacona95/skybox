<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McCostos extends Eloquent {

    protected $table = 'mc_costos';
    public $timestamps = false;
    
    public $tipos = ['0' => 'Flete', '1' => 'Otro','2'=>'Domicilio', '3'=>'Envío nacional'];
    public $estados = ['0' => 'Pagada', '1' => 'Pendiente de pago'];

    


   
}
