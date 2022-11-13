<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class McPagosOrdenesTc extends Eloquent
{

    protected $table = 'mc_pagos_ordenes_tc';
    public $timestamps = false;
    public $statusLst = ['1' => "Exitosa", '0' => "Pendiente", '-1' => "Rechazada", '2' => "Abortada", '3' => "Reversada"];
    public $key = "ff84937e92df45d5a0a8f1fa7143db6d";
}
