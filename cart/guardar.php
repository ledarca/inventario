 <?php 
session_start();
$carrito_mio=$_SESSION['carritovyf'];
$cliente = $_SESSION['cliente'];
require_once('../Connections/cnx.php');
///////////////////////////////////////////////////////////////////////////////
$numeroventa=0;
		$re=mysql_query("select * from productovendido order by numeroventa DESC limit 1") or die(mysql_error());	
		while (	$f=mysql_fetch_array($re)) {
					$numeroventa=$f['numeroventa'];	
		}
		if($numeroventa==0){
			$numeroventa=1;
		}else{
			$numeroventa=$numeroventa+1;
		}
	for($i=0;$i<=count($carrito_mio)-1;$i++)
	{
		$cod=$carrito_mio[$i]['producto']; 	//codigo del proucto idProducto
		$cant=$carrito_mio[$i]['cantidad'];
		$fec= date("Y-m-d\TH:i:sP");			//fecha
		$qty=$carrito_mio[$i]['cantidad'];		//cantidad que resta para actualizar la primera tabla
		$cod2=$carrito_mio[$i]['producto'];
			
		//GUARDAR EN BASE DE DATOS PRODUCTOS
		if (mysql_select_db($database_cnx,$cnx))
		{
			$consulta="insert into productoVendido 	(idProducto, cantidad, idCliente, fecha, numeroventa) values ('$cod', '$cant', '$cliente',' $fec', ".$numeroventa.")"; 

			//codigo que permite modificar el stock
		    $sql_qty = "UPDATE producto SET cantidad = cantidad - '$qty' WHERE idProducto = '$cod2' ";
		    mysql_query($sql_qty) or die(mysql_error(). " Query: " . $sql_qty);     
		    //GUARDAR EN BASE DE DATOS PRODUCTOS
			if (!mysql_query($consulta,$cnx)) 
			echo "<p style='color:red'>error al guardar en db";			
		}
	}	
	//destruyo la sesion carrito
	//unset($_SESSION['carritovyf']);
	//DESTRUYO TODAS LAS SECIONES 
	session_destroy();
	//DESTRUYO TODAS LAS SESIONES REDIRECCIONO 
	echo"<script type='text/javascript'>alert('Tu orden ha sido recibida y guardada.');	window.location='../index.php?';</script>"; 
?>
