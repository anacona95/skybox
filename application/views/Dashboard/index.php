<div class="x_panel">
    <div class="row equal">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Libras</h2></div>
                <div class="panel-body">
                    <canvas id="myChart" width="800" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Indicadores</h2></div>
                <div class="panel-body">
                <div class="col-md-12">
                        <span class="clientes"><i class="fa fa-users fa-lg" aria-hidden="true"></i> Clientes: <?php echo $clientes?> | Hoy: <?php echo $clientes_hoy?> </span>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <span class="cartera"><i class="fa fa-money fa-lg" aria-hidden="true"></i> Cartera: $ <?php echo number_format($cartera,0,'','.')?></span>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <span class="ordenes"><i class="fa fa-file-text-o fa-lg" aria-hidden="true"></i> Ordenes activas: <?php echo $ordenes_activas?></span>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <span class="paquetes"><i class="fa fa-cubes fa-lg"></i> Paquetes en proceso: <?php echo $paquetes_process?></span>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row equal">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Ventas</h2></div>
                <div class="panel-body">
                    <canvas id="myChart3" width="705" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Paquetes en proceso</h2></div>
                <div class="panel-body">
                    <canvas id="myChart4" width="555" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row equal">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Consolidado de ventas</h2></div>
                <div class="panel-body">
                    <canvas id="myChart2" width="800" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        var ctx = document.getElementById("myChart").getContext("2d");
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                datasets: [
                    {
                        label: "Libras vendidas",
                        backgroundColor: "#f94144",
                        borderColor: "#f94144",
                        data: [
                            <?php foreach ($data as $key => $mes) {
                                    if(date("n") > $key){
                                        echo $mes['datas']['libras'] . ",";
                                    }else{
                                        echo null.",";
                                    }
                                }?>

                        ],
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: "Libras ingresadas",
                        backgroundColor: "#282A8F",
                        borderColor: "#282A8F",
                        data: [
                            <?php foreach ($data as $key => $mes){
                                    if(date("n") > $key){
                                        echo $mes['datas']['ingreso_libras'] . ",";
                                    }else{
                                        echo null.",";
                                    } 
                                }
                            ?>

                        ],
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: "Libras pagadas",
                        backgroundColor: "#2b9348",
                        borderColor: "#2b9348",
                        data: [
                            <?php foreach ($data as $key => $mes) {
                                     if(date("n") > $key){
                                        echo $mes['datas']['libras_pagadas'] . ",";
                                    }else{
                                        echo null.",";
                                    }
                                }?>

                        ],
                        fill: false,
                        lineTension: 0
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Libras por mes'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel+" Lb";
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                return value + " Libras";
                            }
                        }
                    }]
                }
            }

        });
    </script>
    <script>
        var ctx = document.getElementById("myChart4").getContext("2d");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                        data: [
                            <?php foreach ($pie as $dato) {
                                    echo $dato . ",";
                                }?>
                        ],
                        backgroundColor: [
                                "#282A8F",
                                "#53BA1D",
                                "#1E87C7",
                                "#E4001C",
            ],
                }],
                labels: [
                        'Prealertados',
                        'Recibido y viajando',
                        'En Cali',
                        'En Orden'
                    ]
            }
        });
    </script>
    <script>
        var ctx = document.getElementById("myChart2").getContext("2d");
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                datasets: [
                    {
                        label: "Envíos nacionales",
                        backgroundColor: "#AB3F2E",
                        data: [
                            <?php foreach ($data as $mes) {
                                    echo $mes['datas']['nacional'] . ",";
                                }?>
                        ],
                    },
                    {
                        label: "Domicilios",
                        backgroundColor: "#6D6D70",
                        data: [
                            <?php foreach ($data as $mes) {
                                    echo $mes['datas']['domicilios'] . ",";
                                }?>
                        ],
                    },
                    {
                        label: "Seguros",
                        backgroundColor: "#355C4B",
                        data: [
                            <?php foreach ($data as $mes) {
                                    echo $mes['datas']['seguros'] . ",";
                                }?>
                        ],
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Consolidado de ventas adicionales'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " COP";
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                if (parseInt(value) >= 1000) {
                                    return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " COP";
                                } else {
                                    return '$' + value  + " COP";
                                }
                            }
                        }
                    }]
                }
            }


        });

    </script>
    <script>
        var ctx = document.getElementById("myChart3").getContext("2d");
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                datasets: [
                    {
                        label: "Ventas",
                        backgroundColor: "#282A8F",
                        borderColor: "#282A8F",
                        data: [
                            <?php foreach ($data as $key => $mes) {
                                     if(date("n") > $key){
                                        echo $mes['datas']['ventas'] . ",";
                                    }else{
                                        echo null.",";
                                    }
                                }?>

                        ],
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: "Costos",
                        backgroundColor: "#f94144",
                        borderColor: "#f94144",
                        data: [
                            <?php foreach ($data as $key => $mes) {
                                     if(date("n") > $key){
                                        echo $mes['datas']['costos'] . ",";
                                    }else{
                                        echo null.",";
                                    }
                                }?>

                        ],
                        fill: false,
                        lineTension: 0
                    },
                    {
                        label: "Utilidad",
                        backgroundColor: "#2b9348",
                        borderColor: "#2b9348",
                        data: [
                            <?php foreach ($data as $key => $mes) {
                                     if(date("n") > $key){
                                        echo ($mes['datas']['ventas']- $mes['datas']['costos']) . ",";
                                    }else{
                                        echo null.",";
                                    }
                                }?>

                        ],
                        fill: false,
                        lineTension: 0
                    }
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Ventas por mes'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " COP";
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                if (parseInt(value) >= 1000) {
                                    return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " COP";
                                } else {
                                    return '$' + value + " COP";
                                }
                            }
                        }
                    }]
                }
            }

        });
    </script>
</div>