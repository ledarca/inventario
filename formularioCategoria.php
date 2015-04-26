<?php 
require_once('Connections/cnx.php');
if(isset($_POST['submit']) and !empty($_POST['name']))
{
	$name = $_POST['name'];
	if (isset($name)) 
	{
		if (mysql_select_db($database_cnx,$cnx))
			{
				$consulta="insert into productocategoria(nombreCategoria) values ('$name')";
			}if (mysql_query($consulta,$cnx))
			{
				//echo "Su Producto se ha guardado con exito en la base de datos";
				echo"<script type='text/javascript'>alert('Tu orden ha sido recibida y guardada.');	window.location='index.php?';</script>";
			}mysql_error($cnx);
	}
}else {echo "introduce la categoria";}
?>
<!DOCTYPE html>
<html> 
<head> 
<meta charset="utf-8" />
<title>Agrefgar categoria</title>
</head> 
<body> 
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	 
	<input type="text" name="name" value=""><br>
	 
	<input type="submit" name="submit" value="enviar"><br>
	 
	</form> 
</body> 
</html> 