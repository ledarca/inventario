<?php require_once('Connections/cnx.php'); 
$cantidadRecarga	= 	$_POST['cantidad'];
$id 				= 	$_POST['categoria']; 
$fechasubida		=	date("Y-m-d");

$respuesta = mysql_query("SELECT producto.idProducto, producto.cantidad 
					FROM producto WHERE producto.idProducto ="" ORDER BY producto.nombreProducto ASC") or die(mysql_error());
$row_RsRecarga = mysql_fetch_assoc($respuesta);
$totalRows_RsRecarga = mysql_num_rows($respuesta);

if ($cantidadRecarga != 0) 
{
	$cantidadModificar	= 	$cantidadRecarga+$row_RsRecarga['cantidad'];
	if (mysql_select_db($database_cnx,$cnx))
	{
		$consulta="
			insert into productorecarga (
				idproducto,				
				cantidad,
				fecha)
			values (
				'$id',				
				'$cantidadRecarga',
				'$fechasubida')";
		$consulta2 = "
			update producto set cantidad = '$cantidadModificar'
			where idProducto = '$id';				 
			";
		if (mysql_query($consulta,$cnx) and mysql_query($consulta2,$cnx))
		{
			//echo "Su Producto se ha guardado con exito en la base de datos";
			echo"<script type='text/javascript'>alert('Tu orden ha sido recibida y guardada.');	window.location='index.php?';</script>";
		}mysql_error($cnx);
	}		
}else {echo "Tienes que introducir la cantidad";}
mysql_free_result($respuesta);
?>
