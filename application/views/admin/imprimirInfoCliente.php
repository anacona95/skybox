<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>FOXCARGA</title>
    <link href="<?php echo base_url(); ?>public/images/ico.png" rel="icon">


    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet">



    <!-- Data tables-->
    <link href="<?php echo base_url(); ?>public/datatables-net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>public/datatables-net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Dosis|Ubuntu:400,700i" rel="stylesheet">

</head>

<body>
    <div class="form-group">
        <div class="form-group">
            <p>
                <FONT size="5" FACE="helvetica">
                    <b>Para:
                        <?php echo $per->primer_nombre ?>&nbsp;
                        <?php echo $per->segundo_nombre ?>&nbsp;
                        <?php echo $per->apellidos ?>
                    </b>
                </font>
                <br>
                <FONT size="5" FACE="helvetica">
                    CC:
                    <?php echo $per->num_documento ?>
                </font>
                <br>
                <FONT size="5" FACE="helvetica">
                Dir:
                    <?php echo $per->direccion ?>
                </font>
                <br>
                <FONT size="5" FACE="helvetica">Tel:
                    <?php echo $per->telefono ?>
                </font>
                <br>
                <FONT size="5" FACE="helvetica">
                        <?php echo $per->ciudad ?>
                </font>
                <br>
        </div>
        <div class="form-group">
            <h1></h1>
        </div>
        <div class="form-group">
            <p>
                <FONT size="5" FACE="helvetica">
                    <b>De: FOXCARGA.COM</b>
                    <br>
                    NIT: 1.144.175.542-5
                </font>
                <br>
                <FONT size="5" FACE="helvetica">Dir: Calle 25 Norte # 3AN-15, Barrio San Vicente</font>
                <br>
                <FONT size="5" FACE="helvetica">Tel: (+57) 317 601 3404</font>
                <br>
                <FONT size="5" FACE="helvetica">Cali, Valle</font>
                <br>
                <FONT size="5" FACE="helvetica">foxcarga.com</font>
                <br>
        </div>
    </div>
</body>

</html>