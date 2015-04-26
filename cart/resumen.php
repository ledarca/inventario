<?PHP 
session_start(); 
$carrito_mio=$_SESSION['carritovyf'];
$cliente = $_SESSION['cliente'];
require_once('../Connections/cnx.php');
$respuesta = mysql_query("SELECT clientes.idCliente, clientes.nombre FROM clientes WHERE idCliente =  ".$cliente."") or die(mysql_error());
$row_RsCliente = mysql_fetch_assoc($respuesta);
$totalRows_RsCliente = mysql_num_rows($respuesta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style type="text/css">
        input{background: red; font-weight: bold;color: white}
        input:hover{cursor: pointer;}
        input[type=text]{width: 25%; background: white; color: black}
        table, td{border: 1px solid}
        td{width: 150px}
    </style>
    <title>Resumen de Inventario</title>
</head>
<body>
    <h1>Compra del cliente: <span><?php echo $row_RsCliente['nombre']; ?></span></h1>
    <p><a href="#">Atras</a></p>
    <table>
        <tr>
            <td>Referencia</td>
            <td>Producto id</td>
            <td>P. Unit (Bs.)</td>
            <td>Cantidad</td>
            <td>Color</td>
            <td>Peso (Gr.)</td>
            <td>Sub-total</td>      
            <td>Eliminar</td>   
        </tr>
    </table>
    <?php
        if(isset($_SESSION['carritovyf'])){
        $total=0;
        $totalgramos=0;
        $totalcantidad=0;
            for($i=0;$i<=count($carrito_mio)-1;$i++){
            if($carrito_mio[$i]!=NULL){
    ?> 
    <table>
        <tr>
            <td><?php print $carrito_mio[$i]['codigo'];?></td>
            <td><?php print $carrito_mio[$i]['producto'];?></td>
            <td><?php print number_format($carrito_mio[$i]['precio'], 2, ',', '.');?></td>
            <td><?php print $carrito_mio[$i]['cantidad']; ?></td>
            <td><?php print $carrito_mio[$i]['color']; ?></td>
            <td><?php print number_format($carrito_mio[$i]['peso'] * $carrito_mio[$i]['cantidad'], 2, ',', '.'); ?></td>
            <td><?php print number_format($carrito_mio[$i]['precio'] * $carrito_mio[$i]['cantidad'], 2, ',', '.'); ?></td>      
            <td></td>   
        </tr>
    </table>   
    <?php
        $total= $total+($carrito_mio[$i]['precio'] * $carrito_mio[$i]['cantidad']);
        $totalgramos= $totalgramos+(($carrito_mio[$i]['peso'] * $carrito_mio[$i]['cantidad'])/*/1000*/);
        $totalcantidad= $totalcantidad+($carrito_mio[$i]['cantidad']);
        }   }   } ;         
    ?>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Kg: <?php if(isset($_SESSION['carritovyf'])){print number_format($totalgramos, 2, ',', '.');}?></td>
            <td>BsF. <?php if(isset($_SESSION['carritovyf'])){print number_format($total, 2, ',', '.');} ?></td>
            <td></td>           
        </tr>
    </table>   		
    <form method="post" action="guardar.php">
        <input type="submit" value="Enviar Pedido"  tabindex="5"/>
    </form>
</body>
</html>