<?php require_once('Connections/cnx.php');
$respuesta = mysql_query("SELECT producto.idProducto, producto.nombreProducto, producto.idCategoria
				FROM producto
				WHERE producto.idCategoria =".$_GET["id"]." ORDER BY producto.nombreProducto ASC") or die(mysql_error());
$row_RsProducto = mysql_fetch_assoc($respuesta);
$totalRows_RsProducto = mysql_num_rows($respuesta);
?>
<!doctype html>
<head>
<meta charset="utf-8">
</head>

<select name="categoria">

<?php do {?>
<option value="<?php echo $row_RsProducto['idProducto']?>"><?php  echo $row_RsProducto['nombreProducto']?></option>
            <?php } while ($row_RsProducto = mysql_fetch_assoc($respuesta));
              $rows = mysql_num_rows($respuesta);
                if($rows > 0) {
                  mysql_data_seek($respuesta, 0);
                $row_RsProducto = mysql_fetch_assoc($respuesta);
                }
          ?>
</select>
<?php
mysql_free_result($respuesta);
?>

</html>