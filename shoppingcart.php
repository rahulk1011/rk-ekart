<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<?php

$status="";

if(!empty($_GET["action"]))
{
	switch($_GET["action"])
	{
		case "remove":
		if(!empty($_SESSION["shopping_cart"]))
		{
			foreach($_SESSION["shopping_cart"] as $key => $value)
			{
				if($value["product_id"] == $_GET["product_id"])
				{
					unset($_SESSION["shopping_cart"][$key]);
				}
				if(empty($_SESSION["shopping_cart"]))
				{
					unset($_SESSION["shopping_cart"]);
				}
			}
		}
		break;
		case "empty":
			unset($_SESSION["shopping_cart"]);
		break;	
	}
}

?>

<!DOCTYPE html>
<html>

<title>Shopping Cart</title>
<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<style>
		.button-cart {
			/*background-color: darkgoldenrod;*/
			background: linear-gradient(to bottom right, #33ccff 0%, #ffffff 100%);
			color: black;
			font-size: 15px;
			font-weight: bold;
			padding: 5px 10px;
			border: 4px outset black;
			border-radius: 2px;
			cursor: pointer;
			/*font-family: "corbel";*/
		}
	</style>
</head>

<body>
	
<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:2px 15px;">
<h2>Shopping Cart</h2>
<hr/>   

<h3><?php echo "<h3><font color='yellow'>".$status."</font></h3>"; ?></h3>

<div class="cart">
<?php
if(isset($_SESSION["shopping_cart"]))
{
    $total_price = 0;
?>

<form method="post" action="checkout.php">
<table align="center" width="90%">
	<tr>
		<th>Product Image</th>
		<th>Product Name</th>
		<th>Product Details</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Total Price</th>
		<th>Action</th>
	</tr>	
	<?php		
	foreach ($_SESSION["shopping_cart"] as $product)
	{
	?>
	<tr>
		<?php $_SESSION["product"]["product_id"] = $product["product_id"]; ?>
		<td><img class="zoom" src='<?php echo $product["product_image"]; ?>' width="40" height="40" border="1"/></td>
		<td>
			<?php echo $product["product_name"];
			$_SESSION["product"]["product_name"] = $product["product_name"]; ?>
		</td>
		<td>
			<?php echo $product["product_details"];
			$_SESSION["product"]["product_details"] = $product["product_details"]; ?>
		</td>
		<td>
			<?php echo $product["quantity"];
			$_SESSION["product"]["quantity"] = $product["quantity"]; ?>
		</td>
		<td>
			<?php echo $product["currency_symbol"]." ".$product["currency_value"];
			$_SESSION["product"]["currency_value"] = $product["currency_value"]; ?>
		</td>
		<td>
			<?php echo $product["currency_symbol"]." ".$product["product_price"]; 
			$_SESSION["product"]["product_price"] = $product["product_price"];?>
		</td>
		<td><a href="shoppingcart.php?action=remove&product_id=<?php echo $product["product_id"]; ?>"><img src="css/icon/delete.png" height="20px" width="20px"></a></td>
	</tr>
	<?php
		$total_price += ($product["currency_value"]*$product["quantity"]);
		$_SESSION["total_price"] = $total_price;
		$_SESSION["currency_symbol"] = $product["currency_symbol"];
	}
	?>
	<tr>
		<th colspan="5" align="right"><strong>TOTAL:</strong></th>
		<th><?php echo $product["currency_symbol"]." ".$total_price; ?></th>
		<th><a href="shoppingcart.php?action=empty" class="button-cart" id="btnEmpty">EMPTY CART</a></th>
	</tr>
	<tr>
		<th colspan="8"><input type="submit" name="checkout" value="CHECKOUT" class="button-cart"></th>
	</tr>
</table>
</form>
<?php
}
else
{
	echo "<h3><font color='yellow'>Your Cart Is Empty..!!</font></h3>";
}
?>
</div>

</div>

<?php
	}
	else
	{
    	header("Location:index.php");
	}	
?>
</body>
</html>