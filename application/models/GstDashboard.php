<?php
class GstDashboard extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->modelORM('McCliente');
        $this->load->modelORM('McArticulos');
        $this->load->modelORM('McArticulosOrdenes');
        $this->load->modelORM('McOrdenesCompras');
        $this->load->modelORM('McFacturas');
        $this->load->modelORM('McCostos');
        $this->load->modelORM('DB');

    }

    public function isLeapYear($year)
    {
        return ((($year % 4 == 0) && ($year % 100)) || $year % 400 == 0) ? true : false;
    }

    public function getDataGraph($anno = null)
    {
        if($anno == null){
            $anno = date('Y');
        }

        $data_mes = [
            '0' => [
                'inicio' => strtotime($anno . '-01-01 00:00:00'),
                'fin' => strtotime($anno . '-01-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '1' => [
                'inicio' => strtotime($anno . '-02-01 00:00:00'),
                'fin' => strtotime($anno . '-02-28 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '2' => [
                'inicio' => strtotime($anno . '-03-01 00:00:00'),
                'fin' => strtotime($anno . '-03-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '3' => [
                'inicio' => strtotime($anno . '-04-01 00:00:00'),
                'fin' => strtotime($anno . '-04-30 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '4' => [
                'inicio' => strtotime($anno . '-05-01 00:00:00'),
                'fin' => strtotime($anno . '-05-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '5' => [
                'inicio' => strtotime($anno . '-06-01 00:00:00'),
                'fin' => strtotime($anno . '-06-30 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '6' => [
                'inicio' => strtotime($anno . '-07-01 00:00:00'),
                'fin' => strtotime($anno . '-07-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '7' => [
                'inicio' => strtotime($anno . '-08-01 00:00:00'),
                'fin' => strtotime($anno . '-08-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '8' => [
                'inicio' => strtotime($anno . '-09-01 00:00:00'),
                'fin' => strtotime($anno . '-09-30 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '9' => [
                'inicio' => strtotime($anno . '-10-01 00:00:00'),
                'fin' => strtotime($anno . '-10-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '10' => [
                'inicio' => strtotime($anno . '-11-01 00:00:00'),
                'fin' => strtotime($anno . '-11-30 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
            '11' => [
                'inicio' => strtotime($anno . '-12-01 00:00:00'),
                'fin' => strtotime($anno . '-12-31 23:59:59'),
                'datas' => [
                    "libras" => 0,
                    "fletes" => 0,
                    "domicilios" => 0,
                    "nacional" => 0,
                    "seguros" => 0,
                    "ingreso_libras" => 0,
                    "ventas" => 0,
                    "libras_pagadas" => 0,
                    "costos" => 0
                ],
            ],
        ];

        if ($this->isLeapYear($anno)) {
            $rangos_mes['febrero']['fin'] = strtotime($anno . '-02-29 23:59:59');
        }

        foreach ($data_mes as $key => $mes) {
            $data_mes[$key]['datas'] = $this->getDatasFacturas(
                    $data_mes[$key]['inicio'],
                    $data_mes[$key]['fin']
                );
                
            $data_mes[$key]['datas']["ingreso_libras"] = $this->getIngresoLibras(
                $data_mes[$key]['inicio'],
                $data_mes[$key]['fin']
            );

            $data_mes[$key]['datas']["libras_pagadas"] = $this->getLibrasPagadas(
                $data_mes[$key]['inicio'],
                $data_mes[$key]['fin']
            );

            $data_mes[$key]['datas']["costos"] = $this->getCostos(
                $data_mes[$key]['inicio'],
                $data_mes[$key]['fin']
            );
        }
        return $data_mes;
    }

    public function getFacturas($fecha_inicio, $fecha_fin)
    {
        return McFacturas::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->get();
    }

    public function getDatasFacturas($fecha_inicio, $fecha_fin)
    {

        $seguros_fletes_libras = DB::select('call seguros_fletes_libras(?,?)',[$fecha_inicio,$fecha_fin]);
        $ventas = DB::select('call ventas_mes(?,?)',[$fecha_inicio,$fecha_fin]);
        $domicilios = DB::select('call domicilios_nacionales(?,?,?)',[$fecha_inicio,$fecha_fin,0]);
        $nacionales = DB::select('call domicilios_nacionales(?,?,?)',[$fecha_inicio,$fecha_fin,1]);
        
        if($seguros_fletes_libras[0]->libras == NULL){
            $seguros_fletes_libras[0]->libras = 0;
        }

        if($seguros_fletes_libras[0]->fletes == NULL){
            $seguros_fletes_libras[0]->fletes = 0;
        }

        if($domicilios[0]->domicilio == NULL){
            $domicilios[0]->domicilio = 0;
        }
        
        if($nacionales[0]->domicilio == NULL){
            $nacionales[0]->domicilio = 0;
        }

        if($seguros_fletes_libras[0]->seguros == NULL){
            $seguros_fletes_libras[0]->seguros = 0;
        }

        if($ventas[0]->ventas == NULL){
            $ventas[0]->ventas = 0;
        }

        $datas = [
            "libras" => $seguros_fletes_libras[0]->libras,
            "fletes" => $seguros_fletes_libras[0]->fletes,
            "domicilios" => $domicilios[0]->domicilio,
            "nacional" => $nacionales[0]->domicilio,
            "seguros" => $seguros_fletes_libras[0]->seguros,
            "ventas" => $ventas[0]->ventas
        ];
        
        return $datas;
    }

    public function getIngresoLibras($fecha_inicio, $fecha_fin)
    {
        return  McArticulos::whereBetween('fecha_punteo', [$fecha_inicio, $fecha_fin])->sum('peso');
    }

    public function getPaquetesProcess()
    {
        
        $objPrealertados = $this->McArticulos->where('estadoArticulo', 'Prealertado')->get();
        $objMiami = $this->McArticulos->where('estadoArticulo', 'Recibido y viajando')->get();
        $objCali = $this->McArticulos->where('estadoArticulo', 'En Cali')->get();
        $objOrden = $this->McArticulos->where('estadoArticulo', 'Orden')->get();
        
        return [
            "prealertados" => count($objPrealertados),
            "miami" => count($objMiami),
            "cali" => count($objCali),
            "orden" => count($objOrden),
        ];
    }

    public function getCountClientes()
    {
        $clientes = $this->McCliente->all();
        return count($clientes);
    }

    public function getCartera()
    {
        return $this->McOrdenesCompras->where('estado',"0")->sum('valor');
    }

    public function getOrdenesActivas()
    {
        return count($this->McOrdenesCompras->where('estado',"0")->get());
    }

    public function getabonado()
    {
        $abonado = DB::select('call get_abonado');
        return $abonado[0]->abonado;
    }

    public function getLibrasPagadas($fecha_inicio,$fecha_fin)
    {
        return  McCostos::where("tipo","0")
        ->whereBetween('fecha_pago', [$fecha_inicio, $fecha_fin])
        ->sum('libras');
        
    }

    public function getCostos($fecha_inicio,$fecha_fin)
    {
        return  McCostos::whereBetween('fecha_pago', [$fecha_inicio, $fecha_fin])->sum('valor');
        
    }


    public function getCountClientesToday()
    {
        $clientes = McCliente::where("fecha_creacion",date("Y-m-d"))->get();
        return count($clientes);
    }



}
