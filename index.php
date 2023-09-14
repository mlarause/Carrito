<?php
session_start();

require_once("clase.php");

$usar_db = new DBControl();

if(!empty($_GET["accion"])) 
{
switch($_GET["accion"]) 
{
	case "agregar":
		if(!empty($_POST["txtcantidad"])) 
		{
			$codproducto = $usar_db->larauseQuery("SELECT * FROM productos WHERE cod='" . $_GET["cod"] . "'");
			$items_array = array($codproducto[0]["cod"]=>array(
			'larause_nom'		=>$codproducto[0]["nom"], 
			'larause_cod'		=>$codproducto[0]["cod"], 
			'txtcantidad'	=>$_POST["txtcantidad"], 
			'larause_pre'		=>$codproducto[0]["pre"], 
			'larause_img'		=>$codproducto[0]["img"]
			));
			
			if(!empty($_SESSION["items_carrito"])) 
			{
				if(in_array($codproducto[0]["cod"],
				array_keys($_SESSION["items_carrito"]))) 
				{
					foreach($_SESSION["items_carrito"] as $i => $j) 
					{
							if($codproducto[0]["cod"] == $i) 
							{
								if(empty($_SESSION["items_carrito"][$i]["txtcantidad"])) 
								{
									$_SESSION["items_carrito"][$i]["txtcantidad"] = 0;
								}
								$_SESSION["items_carrito"][$i]["txtcantidad"] += $_POST["txtcantidad"];
							}
					}
				} else 
				{
					$_SESSION["items_carrito"] = array_merge($_SESSION["items_carrito"],$items_array);
				}
			} 
			else 
			{
				$_SESSION["items_carrito"] = $items_array;
			}
		}
	break;
	case "eliminar":
		if(!empty($_SESSION["items_carrito"])) 
		{
			foreach($_SESSION["items_carrito"] as $i => $j) 
			{
				if($_GET["eliminarcode"] == $i)
				{
					unset($_SESSION["items_carrito"][$i]);	
				}			
				if(empty($_SESSION["items_carrito"]))
				{
					unset($_SESSION["items_carrito"]);
				}
			}
		}
	break;
	case "vacio":
		unset($_SESSION["items_carrito"]);
	break;	
	case "pagar":
	echo "<script> alert('Gracias por su compra - larause');window.location= 'index.php' </script>";
		unset($_SESSION["items_carrito"]);
	
	break;	
}
}
?>
<html>
<meta charset="UTF-8">
<head>
<title>Larause</title>
<link href="style.css" rel="stylesheet" />
</head>
<body>
<div align="center"><h1>Larause Carrito de compras</h1></div>
<div>
<div><h2>Lista de productos a comprar.</h2></div>


<?php
if(isset($_SESSION["items_carrito"]))
{
    $totcantidad = 0;
    $totprecio = 0;
?>	

<table>
<tr>
<th style="width:30%">Descripción</th>
<th style="width:10%">Código</th>
<th style="width:10%">Cantidad</th>
<th style="width:10%">Precio x unidad</th>
<th style="width:10%">Precio</th>
<th style="width:10%"><a href="index.php?accion=vacio">Limpiar</a></th>
</tr>	
<?php		
    foreach ($_SESSION["items_carrito"] as $item){
        $item_price = $item["txtcantidad"]*$item["larause_pre"];
		?>
				<tr>
				<td><img src="<?php echo $item["larause_img"]; ?>" class="imagen_peque" /><?php echo $item["larause_nom"]; ?></td>
				<td><?php echo $item["larause_cod"]; ?></td>
				<td><?php echo $item["txtcantidad"]; ?></td>
				<td><?php echo "$ ".$item["larause_pre"]; ?></td>
				<td><?php echo "$ ". number_format($item_price,2); ?></td>
				<td><a href="index.php?accion=eliminar&eliminarcode=<?php echo $item["larause_cod"]; ?>">Eliminar</a></td>
				</tr>
				<?php
				$totcantidad += $item["txtcantidad"];
				$totprecio += ($item["larause_pre"]*$item["txtcantidad"]);
		}
		?>

<tr style="background-color:red">
<td colspan="2"><b>Total de productos:</b></td>
<td><b><?php echo $totcantidad; ?></b></td>
<td colspan="2"><strong><?php echo "$ ".number_format($totprecio, 2); ?></strong></td>
<td><a href="index.php?accion=pagar">Pagar</a></td>
</tr>

</table>		
  <?php
} else {
?>
<div align="center"><h3>¡El carrito esta vacío!</h3></div>

<?php 
}
?>
</div>

<div>
<div><h2>Productosok</h2></div>
<div class="contenedor_general">
	<?php
	/*Larause*/
	$productos_array = $usar_db->larausequery("SELECT * FROM productos ORDER BY id ASC");
	if (!empty($productos_array)) 
	{ 
		foreach($productos_array as $i=>$k)
		{
	?>
		<div class="contenedor_productos">
			<form method="POST" action="index.php?accion=agregar&cod=
			<?php echo $productos_array[$i]["cod"]; ?>">
			<div><img src="<?php echo $productos_array[$i]["img"]; ?>"></div>
			<div>
			<div style="padding-top:20px;font-size:18px;"><?php echo $productos_array[$i]["nom"]; ?></div>
			<div style="padding-top:10px;font-size:20px;"><?php echo "$".$productos_array[$i]["pre"]; ?></div>
			<div><input type="text" name="txtcantidad" value="1" size="2" /><input type="submit" value="Agregar" />
			</div>
			</div>
			</form>
		</div>
	<?php
		}
	}
	?>
</div>
</body>
</html>