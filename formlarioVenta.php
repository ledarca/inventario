<?php require_once('Connections/cnx.php'); 
mysql_select_db($database_cnx, $cnx);
$query_RsProducto = "SELECT productoCategoria.nombreCategoria, producto.idProducto, producto.nombreProducto, producto.unidad, producto.cantidad, producto.idCategoria 
          FROM producto 
          INNER JOIN productoCategoria ON producto.idCategoria = productoCategoria.idCategoria ORDER BY producto.nombreProducto ASC ";
$RsProducto = mysql_query($query_RsProducto, $cnx) or die(mysql_error());
$row_RsProducto = mysql_fetch_assoc($RsProducto);
$totalRows_RsProducto = mysql_num_rows($RsProducto);

$respuesta=mysql_query("SELECT clientes.idCliente, clientes.nombre, clientes.ci
          FROM clientes") or die(mysql_error());
$row_Respuesta = mysql_fetch_assoc($respuesta);
$totalRows_RsProducto = mysql_num_rows($respuesta);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
    <style type="text/css">
        a{font-size: 1em; margin: 0;}
        body{background: #999}
        form{width: 400px; margin: auto;}
        section{background: #666; color: white;}
        h1{font-size: 1em; margin: 0;}
        h2 {color:#fff; border-left: 1px solid red; font-size: 1em}
        ul{border: 1px solid; padding: 0px; }
        label{width: 90%;display: inline-block;}
        li{display: inline-block; width: 150px;}
    </style>
<title>Ventas</title>
</head>

<body>
  <form action="cart/index.php" method="post" target="new">
    <fieldset>
    	<legend>Formulario</legend>

      <label>Nombre de Cliente</label>
      <select name="cliente" size="1"> 
        <?php do { ?>
        <option value="<?php echo $row_Respuesta['idCliente']?>"><?php echo $row_Respuesta['nombre']?></option>
        <?php } while ($row_Respuesta = mysql_fetch_assoc($respuesta));
              $rows = mysql_num_rows($respuesta);
                if($rows > 0) {
                  mysql_data_seek($respuesta, 0);
                $$row_Respuesta = mysql_fetch_assoc($respuesta);
                }
        ?>
      </select><a href="formularioCliente.php"><span>+</span></a><br><hr>
    
      <label>Producto</label>
  	 <select name="producto" size="1"> 
  	  <?php do { ?>
  	  <option value="<?php echo $row_RsProducto['idProducto']?>"><?php echo $row_RsProducto['nombreProducto']?></option>
  	  <?php } while ($row_RsProducto = mysql_fetch_assoc($RsProducto));
    				$rows = mysql_num_rows($RsProducto);
    					if($rows > 0) {
        				mysql_data_seek($RsProducto, 0);
  	  				$row_RsProducto = mysql_fetch_assoc($RsProducto);
    					}
  		?>
  	</select>  <br><hr>
     
      <label>Cantidad</label>
      <input name="cantidad" value=""/><br><hr>      
      <label>Precio</label>   <input name="precio"    value="1"               /><hr>
      <label>peso</label>     <input name="peso"      value=""               /><hr>
      <label>codigo</label>   <input name="codigo"    value="LaReferencia"    /><hr>
      <!--      <label>nombre</label>   <input name="nombre"    value="leonard"         /><hr>  --> 
      <label>color</label>    <input name="color"     value="blanco"          /><hr>

      
      <input name="carrito" type="submit" />

    </fieldset>
  </form>

</body>
</html>