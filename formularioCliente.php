<?php 
require_once('Connections/cnx.php');
if(isset($_POST['submit']) and !empty($_POST['nombre']))
{
	$nombre 	= $_POST['nombre'];
	$apellido 	= $_POST['apellido'];
	$email 		= $_POST['email'];
	$ci 		= $_POST['ci'];
	$telefono 	= $_POST['telefono'];
	$estado 	= $_POST['estado'];
	$municipio 	= $_POST['municipio'];
	$direccion 	= $_POST['direccion'];
	$fecha		= date("Y-m-d");
//	if (isset($nombre)) 
//	{
		if (mysql_select_db($database_cnx,$cnx))
			{
				$consulta="insert into clientes(nombre, apellido, email, ci, telefono, estado, municipio, direccion, fecha) 
										values ('$nombre', '$apellido', '$email', '$ci', '$telefono', '	estado', 'municipio','direccion', 'fecha')";
			}if (mysql_query($consulta,$cnx))
			{
				//echo "Su Producto se ha guardado con exito en la base de datos";
				echo"<script type='text/javascript'>alert('Tu orden ha sido recibida y guardada.');	window.location='formlarioVenta.php?';</script>";
			}mysql_error($cnx);
//	}
}  ?>
<!DOCTYPE html>
<html> 
<head> 
<meta charset="utf-8" />
<title>Agrefgar categoria</title>
</head> 
<body> 
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	 
	<label>Nombres</label>
	<input type="text" name="nombre" value=""><br>

	<label>Apellidos</label>
	<input type="text" name="apellido" value=""><br>
	 
	<label>Correo Electronico</label>
	<input type="text" name="email" value=""><br>

	<label>Cedula</label>
	<input type="text" name="ci" value=""><br>

	<label>Telefono</label>
	<input type="text" name="telefono" value=""><br>

	<label>Estado</label>
	<input type="text" name="estado" value=""><br>	

	<label>Municipio</label>
	<input type="text" name="municipio" value=""><br>

	<label>Direccion</label>
	<input type="text" name="direccion" value=""><br>

	<input type="submit" name="submit" value="enviar"><br>
	 
	</form> 
</body> 
</html> 
