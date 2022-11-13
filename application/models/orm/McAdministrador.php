<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McAdministrador extends Eloquent
{

    protected $table = 'mc_user_admin';
    public $timestamps = false;
    public $roles = ['1' => 'Administrador', '2' => 'Ejecutivo Cali', '3' => 'Inventario Miami'];

}
