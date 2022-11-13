<html>

<head>

</head>
<style>
    table,
    td,
    th {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<body>
    <table>
        <tr style="background: #3774e5; color: white;">
            <td>Suite</td>
            <td>Cliente</td>
            <td>Dirección</td>
            <td>Telefono</td>
            <td>Ciudad</td>
            <td>ID</td>
            <td>Fecha</td>
            <td>IVA 0%</td>
            <td>Valor</td>
        </tr>
        <?php foreach($ordenes as $row): $total = $total + $row->valor;?>
        <tr>
            <td>
                <?php if(!isset($row->id)){
                    echo "SIN FACTURA";
                }else{
                    echo $row->id;
                }?>
            </td>
            <td>
                <?php echo $row->orden->cliente->primer_nombre." ".$row->orden->cliente->apellidos?>
            </td>
<td>
	                <?php echo $row->orden->cliente->direccion ?>
	            </td>
	            <td>
	                <?php echo $row->orden->cliente->telefono ?>
	            </td>
	            <td>
	                <?php echo $row->orden->cliente->ciudad ?>
	            </td>
            <td>
                <?php echo $row->orden->cliente->num_documento?>
            </td>
            <td>
                <?php echo date('d/m/Y',$row->fecha)?>
            </td>
            <td>$ 0,00</td>
            <td>
                <?php echo "$ ".number_format($row->valor,2,',','.')?>
            </td>
        </tr>
        <?php endforeach;?>
        <tr style="background: #3774e5; color: white;">
            <td colspan="5">Total</td>
            <td>
                <?php echo "$ " . number_format($total, 2, ',', '.') ?>
            </td>
        </tr>
    </table>

</body>

</html>
