<html>
<style>
    body {
        font-family: Helvetica;
        font-size: 12px;
    }

    #background {
        position: absolute;
        z-index: 0;
        top: 280px;
        left: 120px;
        background: white;
        display: block;
        min-height: 50%;
        min-width: 50%;
        color: yellow;
    }

    #content {
        position: absolute;
        z-index: 1;
    }

    #bg-text {
        color: lightgrey;
        font-size: 70px;
        transform: rotate(320deg);
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 50px;
        color: gray;
        text-align: center;
        line-height: 35px;
    }
</style>

<body>
    <div id="background">
        <p id="bg-text">
            <!-- <b>PAGADA</b>-->
        </p>
    </div>
    <div id="content">
        <table style="width: 100%;">
            <tr>
                <td valign="top">
                    <img style="width: 180px;" src="<?= base_url(); ?>/public/images/logo2.png">
                </td>
                <td align="right">
                    <b>Factura de venta #:</b>
                    <?php echo $objFactura->id ?>
                    <br>
                    <b>Orden de compra:</b>
                    <?php echo $objFactura->orden->factura ?>
                    <br>
                    <b>Fecha:</b>
                    <?php echo date('d/m/Y', $objFactura->fecha) ?>
                    <br>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table style="width: 100%;">
            <tr>
                <td style="width: 380px;">
                    <b>FOXCARGA.COM</b>
                    <br> NIT: 1144175542-5
                    <br> Régimen simplificado
                    <br> Calle 25 # 3AN-15
                    <br>
                    <br> hola@foxcarga.com
                    <br> Teléfono Cali: (+57) 317 601 3404
                    <br>
                    <br> Banco: Bancolombia S.A.
                    <br> N de cuenta: 735-558642-45 Ahorros
                </td>
                <td valign="top">
                    <b>CLIENTE</b>
                    <br>
                    <br>
                    <?php echo ucwords(strtolower($objFactura->orden->cliente->primer_nombre . " " . $objFactura->orden->cliente->apellidos)) ?>
                    <br>
                    <br>
                    <?php echo $objFactura->orden->cliente->direccion ?>
                    <br>
                    <br>
                    <?php echo $objFactura->orden->cliente->ciudad . " - " . $objFactura->orden->cliente->pais ?>
                    <br>
                    <br> CC:
                    <?php echo $objFactura->orden->cliente->num_documento ?>
                </td>
            </tr>
        </table>
        <h2>Detalle de la factura</h2>
        <table style="width: 100%;">
            <thead>
                <tr style="background: #337ab7; color: white;">
                    <th align="center" width="250">Concepto</th>
                    <th align="center">Cantidad</th>
                    <th align="center">Precio Unitario</th>
                    <th align="center">IVA</th>
                    <th align="center">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FLETES</td>
                    <td align="center">
                        <?php echo $libras . " lb" ?>
                    </td>
                    <td align="center">
                        <?php echo "$ " . number_format(($objFactura->valor - $objFactura->orden->impuestos - $objFactura->orden->flete_nacional - $objFactura->orden->seguro + $objFactura->orden->descuento) / $libras, 2, ',', '.') ?>
                    </td>
                    <td align="center">0%</td>
                    <td align="center">
                        <?php echo "$ " . number_format($objFactura->valor - $objFactura->orden->impuestos - $objFactura->orden->flete_nacional - $objFactura->orden->seguro + $objFactura->orden->descuento, 2, ',', '.') ?>
                    </td>
                </tr>
                <tr>
                    <?php foreach($objFactura->orden->articulos as $row){
                        if($row->articulo->seguro == "si"){
                            ++$count;
                        }
                    }
                    ?>

                    <td>SEGUROS</td>
                    <td align="center">
                        <?php echo $count;?>
                    </td>
                    <td align="center">
                        ----
                    </td>
                    <td align="center">0%</td>
                    <td align="center">
                        <?php echo "$ " . number_format($objFactura->orden->seguro, 2, ',', '.') ?>
                    </td>
                </tr>
                <tr>
                    <td>INGRESOS PARA TERCEROS: IMPUESTOS</td>
                    <td align="center">
                        ----
                    </td>
                    <td align="center">
                        ----
                    </td>
                    <td align="center">0%</td>
                    <td align="center">
                        <?php echo "$ " . number_format($objFactura->orden->impuestos, 2, ',', '.') ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%; font-size: 16px;">
            <tr>
                <td align="right" style="width: 75%;">
                    <br>
                    <b>Subtotal:</b>
                    <br>
                    <b>Descuento:</b>
                    <br>
                    <b>Total:</b>
                    <br>
                </td>
                <td align="right">
                    <br>
                    <b>
                        <?php echo "$ " . number_format($objFactura->valor + $objFactura->orden->descuento, 2, ',', '.') ?>
                    </b>
                    <br>
                    <b>
                        <?php echo "$ " . number_format($objFactura->orden->descuento, 2, ',', '.') ?>
                    </b>
                    <br>
                    <b>
                        <?php echo "$ " . number_format($objFactura->valor, 2, ',', '.') ?>
                    </b>
                    <br>
                </td>
            </tr>
        </table>
    </div>
    <footer></footer>

</body>

</html>