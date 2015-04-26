<?php require_once('Connections/cnx.php');
$respuesta = mysql_query("SELECT productocategoria.nombreCategoria, productocategoria.idCategoria
            FROM productocategoria ORDER BY productocategoria.nombreCategoria ASC") or die(mysql_error());
$row_RsProducto = mysql_fetch_assoc($respuesta);
$totalRows_RsProducto = mysql_num_rows($respuesta);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FORMULARIO DE RECARGA</title>
<script src="js/ajax.js"></script>
</head>

<body>
<a href="index.php">ATRAS</a>
<hr>
<div onLoad="limpiar()">
  <form action="formularioRecargaProcesar.php" name="form" method="POST">
  	<fieldset>
    	<label style="color:black; font-size: xx-large">Categoria</label><br>
     	<select name="nombre" size="1" onChange="from(document.form.nombre.value,'categoria','catalogoAddRecarga.php')"> 
      	<?php do { ?>
     		<option value="<?php echo $row_RsProducto['idCategoria']?>"><?php echo $row_RsProducto['nombreCategoria']?></option>
      	<?php } while ($row_RsProducto = mysql_fetch_assoc($respuesta));
    		$rows = mysql_num_rows($respuesta);
    		if($rows > 0) {
    		mysql_data_seek($respuesta, 0);
    		$row_RsProducto = mysql_fetch_assoc($respuesta);
    		}
    		?>
    	</select>  <br>
      <label style="color:black; font-size: xx-large">Producto</label>
        <div id="categoria">
          <select name="categoria">
          <option value="0">Seleccione Categor&iacute;a</option>
          </select>
        </div><hr>
      <label for="cantidad">Cantidad:</label>        
      <input name="cantidad" type="text">
      <input type="submit" value="Insertar registro">
    </fieldset>
  </form>
</div>
</body>
</html>
