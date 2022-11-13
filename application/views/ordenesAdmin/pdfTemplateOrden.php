<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>
<style>
    body {
        font-family: Helvetica;
        font-size: 10px;
        padding-right: 11%;
    }

    table.mytable {
        border-collapse: separate;
        border-spacing: 0;
    }

    .mytable td {
        border: 1px solid;
        border-color: #bbb;
        
    }

    .first-transparent tr:first-child td:nth-child(1) {
        border: 1px transparent;
    }

    .bg-primary {
        background: #484B41;
        color: white;
    }

    table tr th,
    table tr td {
        border-right: 1px solid #bbb;
        border-bottom: 1px solid #bbb;
        padding: 5px;
    }
    table tr th:first-child, table tr th:last-child{
    border-top:solid 1px      #bbb;}
    table tr th:first-child,
    table tr td:first-child {
        border-left: 1px solid #bbb;
        
    }
    table tr th:first-child,
    table tr td:first-child {
        border-left: 1px solid #bbb;
    }
    table tr th {
        background: #eee;
        text-align: left;
    }
    
    /* top-left border-radius */
    table tr:first-child th:first-child,
    table tr:first-child td:first-child {
        border-top-left-radius: 6px;
    }
    
    /* top-right border-radius */
    table tr:first-child th:last-child,
    table tr:first-child td:last-child {
        border-top-right-radius: 6px;
    }
    
    /* bottom-left border-radius */
    table tr:last-child td:first-child {
        border-bottom-left-radius: 6px;
    }
    
    /* bottom-right border-radius */
    table tr:last-child td:last-child {
        border-bottom-right-radius: 6px;
    }
         
</style>

<body>
    <table class="mytable first-transparent" style="width: 100%;">
        <tr>
            <td style="width: 50px;">
                <img style="width: 50px;" src="<?= base_url(); ?>/public/images/logo.png">
            </td>
            <td style="width: 359px;" align="center">
                <b>PRUEBA DE ENTREGA</b>
            </td>
            <td style="width: 219px;">
                <b>N° de orden
                    <?php echo $objOrden->factura?>
                </b>
            </td>
        </tr>
    </table>
    <br>
    <table class="mytable" style="width: 100%;">
        <tr>
            <td style="width: 157px;">
                <b>Cliente:</b>
            </td>
            <td style="width: 300px;">
                <?php echo $objOrden->cliente->primer_nombre. " ". $objOrden->cliente->apellidos?>
            </td>
            <td style="width: 219px;">
                <b>Fecha:
                    <?php echo date('d/m/Y')?>
                </b>
            </td>
        </tr>
        <tr>
            <td style="width: 157px;">
                <b>Direccion:</b>
            </td>
            <td style="width: 300px;">
                <?php echo $objOrden->cliente->direccion?>
            </td>
            <td>
                <b>Tel:
                    <?php echo $objOrden->cliente->telefono?>
                </b>
            </td>
        </tr>
    </table>
    <br>
    <table class="mytable" style="width: 100%;">
        <tr bgcolor="#bbb">
            <td style="width: 40px;" align="center">
                <b>PESO</b>
            </td>
            <td style="width: 306px;" align="center">
                <b>TRACKING</b>
            </td>
            <td style="width: 250px;" align="center">
                <b>DESCRIPCIÓN</b>
            </td>
            <td style="width: 70px;" align="center">
                <b>VALOR</b>
            </td>
        </tr>
        <?php foreach($objOrden->articulos as $row):?>
        <tr>
            <td align="center">
                <?php echo $row->articulo->peso?> lb
            </td>
            <td align="center">
                <?php echo $row->articulo->id_track?>
            </td>
            <td align="center">
                <?php echo $row->articulo->nombre?>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($row->articulo->valor,0,'','.')?>
            </td>
        </tr>
        <?php endforeach;?>
        <?php if($objOrden->seguro>0):?>
        <tr>
            <td colspan="3">
                <b>SEGURO</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->seguro,0,'','.')?>
            </td>
        </tr>
        <?php endif;?>
        <?php if($objOrden->valor_bodega>0):?>
        <tr>
            <td colspan="3">
                <b>BODEGAJE</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->valor_bodega,0,'','.')?>
            </td>
        </tr>
        <?php endif;?>
        <?php if($objOrden->impuestos>0):?>
        <tr>
            <td colspan="3">
                <b>IMPUESTOS</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->impuestos,0,'','.')?>
            </td>
        </tr>
        <?php endif;?>
        <tr>
            <td colspan="3">
                <b>DOMICILIO</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->flete_nacional,0,'','.')?>
            </td>
        </tr>
        <?php if($objOrden->descuento>0):?>
        <tr>
            <td colspan="3">
                <b>DESCUENTO</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->descuento,0,'','.')?>
            </td>
        </tr>
        <?php endif;?>
        <tr>
            <td colspan="3">
                <b>VALOR TOTAL</b>
            </td>
            <td align="center">
                <?php echo "$ ".number_format($objOrden->valor,0,'','.')?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <b>LIBRAS</b>
            </td>
            <td align="center">
                <?php echo $objOrden->getLibras()." lb"?>
            </td>
        </tr>
    </table>
    <p>
        <b>RECIBÍ CONFORME Y A SATISFACCIÓN</b>
    </p>
</body>

</html>