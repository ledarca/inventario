<?php require_once('Connections/cnx.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_cnx, $cnx);
$query_Rsprimer = "SELECT productoCategoria.nombreCategoria, producto.nombreProducto, producto.unidad, producto.cantidad, producto.idCategoria, producto.idProducto
FROM producto INNER JOIN productoCategoria ON productoCategoria.idCategoria = producto.idCategoria
ORDER BY
producto.nombreProducto ASC";
$Rsprimer = mysql_query($query_Rsprimer, $cnx) or die(mysql_error());
$row_Rsprimer = mysql_fetch_assoc($Rsprimer);
$totalRows_Rsprimer = mysql_num_rows($Rsprimer);

mysql_select_db($database_cnx, $cnx);
$query_RsRecargas = "SELECT idRecarga, idProducto, cantidad, fecha FROM productoRecarga ORDER BY idRecarga DESC limit 0,10";
$RsRecargas = mysql_query($query_RsRecargas, $cnx) or die(mysql_error());
$row_RsRecargas = mysql_fetch_assoc($RsRecargas);
$totalRows_RsRecargas = mysql_num_rows($RsRecargas);

mysql_select_db($database_cnx, $cnx);
$query_RsVendido = "
SELECT
productoCategoria.nombreCategoria,
producto.nombreProducto,
productoVendido.cantidad,
productoVendido.fecha,
producto.idProducto,
producto.unidad
FROM
producto
INNER JOIN productoCategoria ON productoCategoria.idCategoria = producto.idCategoria
INNER JOIN productoVendido ON producto.idProducto = productoVendido.idProducto
";
$RsVendido = mysql_query($query_RsVendido, $cnx) or die(mysql_error());
$row_RsVendido = mysql_fetch_assoc($RsVendido);
$totalRows_RsVendido = mysql_num_rows($RsVendido);

$maxRows_RsVendidoTotal = 10;
$pageNum_RsVendidoTotal = 0;
if (isset($_GET['pageNum_RsVendidoTotal'])) {
  $pageNum_RsVendidoTotal = $_GET['pageNum_RsVendidoTotal'];
}
$startRow_RsVendidoTotal = $pageNum_RsVendidoTotal * $maxRows_RsVendidoTotal;

mysql_select_db($database_cnx, $cnx);
$query_RsVendidoTotal = "SELECT * FROM productoVendido ORDER BY idVendido DESC";
$query_limit_RsVendidoTotal = sprintf("%s LIMIT %d, %d", $query_RsVendidoTotal, $startRow_RsVendidoTotal, $maxRows_RsVendidoTotal);
$RsVendidoTotal = mysql_query($query_limit_RsVendidoTotal, $cnx) or die(mysql_error());
$row_RsVendidoTotal = mysql_fetch_assoc($RsVendidoTotal);

if (isset($_GET['totalRows_RsVendidoTotal'])) {
  $totalRows_RsVendidoTotal = $_GET['totalRows_RsVendidoTotal'];
} else {
  $all_RsVendidoTotal = mysql_query($query_RsVendidoTotal);
  $totalRows_RsVendidoTotal = mysql_num_rows($all_RsVendidoTotal);
}
$totalPages_RsVendidoTotal = ceil($totalRows_RsVendidoTotal/$maxRows_RsVendidoTotal)-1;

 // consulta donde aparece el total de cada categoria.
$respuesta = mysql_query("SELECT Count(producto.cantidad) AS Total, productocategoria.nombreCategoria
              FROM producto
              INNER JOIN productocategoria ON productocategoria.idCategoria = producto.idCategoria
              GROUP BY producto.idCategoria ORDER BY Total ASC") or die(mysql_error());

$respuesta2 =mysql_query(" SELECT Sum(producto.cantidad) AS Total, productocategoria.nombreCategoria
              FROM producto
              INNER JOIN productocategoria ON productocategoria.idCategoria = producto.idCategoria
              GROUP BY producto.idCategoria, productocategoria.nombreCategoria ORDER BY Total ASC") or die(mysql_error());  

$respuesta3 = mysql_query("SELECT Sum(productovendido.cantidad) AS total, producto.nombreProducto
              FROM productovendido
              INNER JOIN producto ON producto.idProducto = productovendido.idProducto
              GROUP BY productovendido.idProducto ORDER BY total DESC") or die(mysql_error());
?>


<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Sistema de inventario</title>
<meta charset="UTF-8">
    <style type="text/css">
        a{font-size: 1em; margin: 0;}
        article{width: 49%; border:1px dotted #0F0; padding:1px}
		    body{background: #999}
        form{width: 400px; margin: auto;}
        section{background: #666; color: white;}
        header{border: 5px solid violet}
        h1{font-size: 1em; margin: 0;}
        h2 {color:#fff; border-left: 1px solid red; font-size: 1em}
        h4{border: 1px solid red; width: 100%}
        ul{border: 1px solid; padding: 0px; }
        label{width: 90%;display: inline-block;}
        li{display: inline-block; width: 150px;}
		#der{width: 49%; border:1px dotted red; padding:5px}
		article, #der {	display: inline-block; vertical-align: top;}

    .box { display: inline-block; width: 30%; margin: 1em; border: 2px solid #99ffaa }
    .box2{ display: inline; width: 50%;  border: 1px solid #99ffaa; float: right;}

      
    </style>
</head>

<body>
<a href="formularioRecarga.php">Entrada</a> || <a href="formlarioVenta.php">Salida</a>|| <a href="formularioCategoria.php">+ Categoria</a>
<hr>
<header>
  <div class="box">
  <table> 
    <TR><TD>MODELOS</TD><td>Cantidad de modelos</td></TR>
    <?php while ($f=mysql_fetch_array($respuesta)){ ?>
    <tr><td><span><?php echo $f['nombreCategoria']; echo"--";?></span></td><td><?php echo $f['Total'];?></td></tr>
    <?php }   ?>  
  </table>
  </div>
  <div class="box">
<table >
  <tr><td>Articulos mas vendidos</td><td>Cantidad</td></tr>
  <?php while ($f=mysql_fetch_array($respuesta3)) { ?>
  <tr><td><?php echo $f['nombreProducto'];?></td><td><?php echo $f['total']; echo"--";?></td></tr>
  <?php } ?>
</table>
  </div>
  <div class="box">
  <table>
    <TR><TD>MODELOS</TD><td>Total stock</td></TR>
    <?php while ($f2=mysql_fetch_array($respuesta2)) {    ?>
    <tr><td><span><?php echo $f2['nombreCategoria']; echo"--";?></span></td><td><span><?php echo $f2['Total']; ?></span></td></tr>
    <?php   } ?>
  </table>  
  </div>
   
</header>
<article>
 <h1> Entrada</h1>
  <table width="90%" border="1">
  <tr>
  	<td width="152">id</td>
    <td width="158">id producto</td>
    <td width="142">cantidad</td>
    <td width="249">fecha</td>
  </tr><?php do { ?>
    <tr>
      <td><?php echo $row_RsRecargas['idRecarga']; ?></td>
      <td><?php echo $row_RsRecargas['idProducto']; ?></td>
      <td><?php echo $row_RsRecargas['cantidad']; ?></td>
      <td><?php echo $row_RsRecargas['fecha']; ?></td>
    </tr><?php } while ($row_RsRecargas = mysql_fetch_assoc($RsRecargas)); ?>
  </table>
   
<hr><h1>Salida diaria</h1>  
<table width="90%" border="1">
  <tr>
  	<td width="152">id</td>
    <td width="158">id producto</td>
    <td width="142">cantidad</td>
    <td width="249">fecha</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_RsVendidoTotal['idVendido']; ?></td>
    <td><?php echo $row_RsVendidoTotal['idProducto']; ?></td>
    <td><?php echo $row_RsVendidoTotal['cantidad']; ?></td>
    <td><?php echo $row_RsVendidoTotal['fecha']; ?></td>
  </tr>
  <?php } while ($row_RsVendidoTotal = mysql_fetch_assoc($RsVendidoTotal)); ?>
 </table>
</article> 

<div id="der"> 
<h1>Total en stock</h1>

  <table width="90%" height="27" border="1">
    <tr>
  	<td width="134">Categoria</td>
    <td width="247">Nombreproducto</td>
     <td width="247">unidad</td>
    <td width="313">cantidad</td>
  	</tr><?php do { ?>
    <tr>
      <td width="134"><?php echo $row_Rsprimer['nombreCategoria']; ?></td>
      <td width="247"><?php echo $row_Rsprimer['nombreProducto']; ?></td>
       <td width="247"><?php echo $row_Rsprimer['unidad']; ?></td>
      <td width="313"><?php echo number_format($row_Rsprimer['cantidad'],0, '.', ''); ?></td>
      </tr>  <?php } while ($row_Rsprimer = mysql_fetch_assoc($Rsprimer)); ?>
    </table>

  
<hr><h1>Salida</h1>   

  <table width="90%" height="27" border="1">
     <tr>
  	<td width="134">Categoria</td>
    <td width="210">Nombreproducto</td>
    <td width="57">Unidad</td>
    <td width="71">Cantidad</td>
  	</tr>
    <tr> <?php do { ?>
      <td width="57"><?php echo $row_RsVendido['nombreCategoria']; ?></td>
      <td width="71"><?php echo $row_RsVendido['nombreProducto']; ?></td>
      <td width="62"><?php echo $row_RsVendido['unidad']; ?></td>
      <td width="50"><?php echo $row_RsVendido['cantidad']; ?></td>
    </tr>  <?php } while ($row_RsVendido = mysql_fetch_assoc($RsVendido)); ?>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($Rsprimer);

mysql_free_result($RsRecargas);

mysql_free_result($RsVendido);

mysql_free_result($RsVendidoTotal);
?>
