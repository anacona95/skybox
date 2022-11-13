<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McCliente extends Eloquent {

    protected $table = 'mc_clientes';
    public $timestamps = false;

    public function ordenes()
    {
        return $this->hasMany('McOrdenesCompras', 'user_id', 'id');
    }

    public function validoNuevaOrden(){
        $objOrdenes = McOrdenesCompras::where('user_id',$this->id)->whereIn('estado',[0,3,4])->get();
        
        if(count($objOrdenes)>0){
            return false;
        }
        return true;
    }

    public function cupon_registro()
    {
        return $this->hasOne('McCupones', 'id', 'cupon_id');
    }

   
}
