<?php 	
session_start();
require_once('../Connections/cnx.php');
//var_dump($_SESSION); //funcion que me muestra el puntero de matriz cuando genera el error Undefined offset:1 y el numero significa la posicion de la matriz donde esta el error

$_SESSION['cliente'] = $_POST['cliente'];
$cliente = $_SESSION['cliente'];

if(isset($_SESSION['carritovyf'])||isset($_POST['producto']))
{
	if(isset($_SESSION['carritovyf']))
	{
		$carrito_mio =	$_SESSION['carritovyf']; 		// 	VARIABLE DE SESION CARRITO_MIO
		if(isset($_POST['producto']))
		{
			$cantidad 	=	$_POST['cantidad'];
			$precio 	= 	$_POST['precio'];
			$nombre 	= 	$_POST['producto'];
			$peso 		= 	$_POST['peso'];
			$codigo 	= 	$_POST['codigo'];
			$color 		= 	$_POST['color'];
			$donde=-1; 									//	CODIGO DE CONTROL										
			for($i=0; $i<=count($carrito_mio)-1;$i++)
			{ 		
				if ($nombre==$carrito_mio[$i]['producto'])
				{
					$donde=$i; 
				} 
			}
			if ($donde !=-1)							// CONTROLADOR DE ELEMENTOS: PARA QUE NO SE DUPLIQUEN EN LA MATRIZ
			{
				$cuanto=$carrito_mio[$donde]['cantidad']+ $cantidad;
				$carrito_mio[$donde]=array("producto"=>$nombre, "precio"=>$precio, "cantidad"=>$cuanto, "peso"=>$peso, "color"=>$color, "codigo"=>$codigo);
			}else 										// MATRIZ ASOCIATIVA
			{											
				$carrito_mio[]=array("producto"=>$nombre,"precio"=>$precio, "cantidad"=>$cantidad, "peso"=>$peso, "color"=>$color, "codigo"=>$codigo);
			} 		
		}		
	}
	else
	{
		$nombre=$_POST['producto'];
		$precio=$_POST['precio'];
		$cantidad=$_POST['cantidad'];
		$peso=$_POST['peso'];
		$codigo=$_POST['codigo'];
		$color= $_POST['color'];
		$carrito_mio[]=array("producto"=>$nombre,"precio"=>$precio,"cantidad"=> $cantidad, "peso"=>$peso, "color"=>$color, "codigo"=>$codigo);}
	if(isset ($_POST['cantidad2']))
	{ 													// permite cambiar los datos de cantidad manualmente actualizar
		$id=$_POST['id'];
		$cuantos=$_POST['cantidad2'];
		if($cuantos<1)
		{
			$carrito_mio[$id]=NULL;	
		}else
		{
			$carrito_mio[$id]['cantidad']=$cuantos;
		}
	}
}
// permite borrar  papelera******************************************************************************************
if(isset($_POST['id2']))
{
	$id=$_POST['id2'];
	$carrito_mio[$id]=NULL;
}
$_SESSION['carritovyf']= $carrito_mio;

$respuesta = mysql_query("SELECT clientes.idCliente, clientes.nombre FROM clientes WHERE idCliente =  ".$cliente."") or die(mysql_error());
$row_RsCliente = mysql_fetch_assoc($respuesta);
$totalRows_RsCliente = mysql_num_rows($respuesta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <title>Sistema de inventario venyor</title>
    <style type="text/css">
    	input{background: red; font-weight: bold;color: white}
    	input:hover{cursor: pointer;}
		input[type=text]{width: 25%; background: white; color: black}
		table, td{border: 1px solid}
		td{width: 150px}
    </style>
</head>

<body>
	<h1>Compra del cliente: <span><?php echo $row_RsCliente['nombre']; ?></span></h1>

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
			<td>
				<form id="form1" name="form1" method="post" action="">
					<label>
				   		<input name="id" type="hidden" id="id" value="<?php $can = print $i;?>" />
				   		<input name="cantidad2" type="text" id="cantidad2" value="<?php print $carrito_mio[$i]['cantidad']; ?>" size="4" maxlength="4" />
				   		<input name="actualizar" type="submit" class="actualizar" value="Actualizar"  />
				   	</label>
				</form>				
			</td>
			<td><?php print $carrito_mio[$i]['color']; ?></td>
			<td><?php print number_format($carrito_mio[$i]['peso'] * $carrito_mio[$i]['cantidad'], 2, ',', '.'); ?></td>
			<td><?php print number_format($carrito_mio[$i]['precio'] * $carrito_mio[$i]['cantidad'], 2, ',', '.'); ?></td>		
			<td>
				<form id="form2" name="form2" method="post" action="">
					<label>
						<input name="id2" type="hidden" id="id2" value="<?php print $i;?>" />
						<input name="eliminar" type="submit" class="actualizar" value="Eliminar"  />
	      			</label>
	    		</form>
			</td>	
		</tr>
	</table>   
    <?php
	    $total= $total+($carrito_mio[$i]['precio'] * $carrito_mio[$i]['cantidad']);
		$totalgramos= $totalgramos+(($carrito_mio[$i]['peso'] * $carrito_mio[$i]['cantidad'])/1000);
		$totalcantidad= $totalcantidad+($carrito_mio[$i]['cantidad']);
		}	}  	} ;		    
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
	<!--CONTROLADOR DE COMPRAS PARA DESBLOQUER EL BOTON **********-->
	<form name="form4" method="post" action="resumen.php"/>
		<input type="submit" value="Enviar Pedido"/>
	</form>

</body>
</html>