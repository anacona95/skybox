<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McArticulos extends Eloquent {

    protected $table = 'mc_articulos';
    public $timestamps = false;
    protected $primaryKey = 'articulo_id';
    
    public function cliente(){
        return $this->hasOne('McCliente','id','user_id');
    }
    
   
}
