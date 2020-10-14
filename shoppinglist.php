<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<?php
include ("config.php");

$lang = "1";
$cur = "1";
if(isset($_GET['lang']))
{
	$lang = $_GET['lang'];
}
if(isset($_GET["cur"]))
{
	$cur = $_GET["cur"];
}
?>

<!DOCTYPE html>
<html>

<title>Shopping List</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<script src="js/jquery-3.4.1.js"></script>
	<script>
		function onlyNos(e, t)
		{
			try 
			{
				if (window.event)
				{	var charCode = window.event.keyCode;	}
				else if (e)
				{	var charCode = e.which;	}
				else {	return true;	}
				if (charCode > 31 && (charCode < 48 || charCode > 57)) 
				{	return false;	}
				return true;
			}
			catch (err) 
			{	alert(err.Description);	}
		}
	</script>
    <style>
    	.pagination a {
			background-color: midnightblue;
			color: white;
			padding: 5px 10px;
			border-radius: 50%;
		}

		.pagination a.active {
			background-color: gainsboro;
			color: midnightblue;
			padding: 5px 10px;
			border-radius: 50%;
		}
		p {
			color: white;
		}
        .box {
            color: black;
            padding: 2px;
        }
        .table {
        	display: table;
        	width: 100%;
        	color: white;
        }
        .table .table-row { 
            display:table-row;
        }
        .table .table-cell { 
            display:table-cell; 
            width:50%;
        }
        .form-control {
            width: 50%;
            padding: 5px;
            margin: 2px;
            box-sizing: border-box;
            border: 1px solid black;
            border-radius: 4px;
        }
        .product-menu {
        	background-color: lavender;
        	margin: 5px;
        	padding: 10px;
        	width: 240px;
        	height: 220px;
        	float: left;
        	border: 2px solid navy;
        	border-radius: 5px;
        }
        .cart_div {
			float:right;
			font-weight:bold;
			position:relative;
		}
		.cart_div a {
			color:#000;
		}	
		.cart_div span {
			font-size: 12px;
		    line-height: 14px;
		    background: #F68B1E;
		    padding: 2px;
		    border: 2px solid #fff;
		    border-radius: 50%;
		    position: absolute;
		    top: -1px;
		    left: 13px;
		    color: #fff;
		    width: 14px;
		    height: 13px;
		    text-align: center;
		}
		.button-cart {
			background: linear-gradient(to right, #ff9933 0%, #ffffff 100%);
			color: black;
			font-size: 15px;
			font-weight: bold;
			padding: 5px 10px;
			border: 1px solid black;
			cursor: pointer;
			border-radius: 5px;
			font-family: "corbel";
		}
		.quantity {
			width: 50px;
			padding: 5px;
			box-sizing: border-box;
			border: 1px solid black;
			border-radius: 4px;
		}
    </style>
</head>

<body>
	
<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:2px 15px;">

<div class="table">
	<div class="table-row">
		<div class="table-cell" style="text-align: left; padding: 10px 0px 0px 15px; text-decoration: none;">
			<h3 style="text-align: left;">Shopping Menu</h3>
		</div>
		<div class="table-cell" style="text-align: right; padding: 10px 15px 0px 0px; text-decoration: none;">
		<?php

		$status="";
		if (isset($_POST["product_id"]))
		{
			$product_id = $_POST['product_id'];

			$s_query = "SELECT pMaster.product_id, pMaster.product_image, pLang.product_name, pLang.product_details, curr.currency_symbol, pCurr.currency_value
				FROM tbl_product_master pMaster
				INNER JOIN tbl_product_language pLang ON (pLang.product_id = pMaster.product_id)
				INNER JOIN tbl_product_currency pCurr ON (pCurr.product_id = pMaster.product_id)
				INNER JOIN tbl_language lang ON (lang.lang_id = pLang.language_id)
				INNER JOIN tbl_currency curr ON (curr.currency_id = pCurr.currency_id)
				WHERE (pMaster.product_id = '$product_id' AND plang.language_id = '$lang' AND pCurr.currency_id = '$cur')";

			$s_result = mysqli_query($conn, $s_query);
			$s_row = mysqli_fetch_assoc($s_result);

			$product_id = $s_row["product_id"];
			$product_name = $s_row["product_name"];				
			$product_details = $s_row["product_details"];
			$product_image = $s_row["product_image"];
			$currency_symbol = $s_row["currency_symbol"];
			$currency_value = $s_row["currency_value"];
			$quantity = $_POST["quantity"];
			$product_price = ($currency_value * $quantity);

			$cartArray = array($product_id=>array('product_id'=>$product_id, 'product_name'=>$product_name, 'product_details'=>$product_details, 'product_image'=>$product_image, 'currency_symbol'=>$currency_symbol, 'currency_value'=>$currency_value, 'quantity'=>$quantity, 'product_price'=>$product_price));

			if(empty($_SESSION["shopping_cart"]))
			{
				$_SESSION["shopping_cart"] = $cartArray;
				$status = "<font color='white'>Product Added To Cart..!!</font>";
			}
			else
			{
				$array_keys = array_keys($_SESSION["shopping_cart"]);
				if(in_array($product_id, $array_keys))
				{
					$status = "<font color='yellow'>Product Already In Cart..!!</font>";
				}
				else
				{
					$_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"], $cartArray);
					$status = "<font color='white'>Product Added To Cart..!!</font>";
				}
			}
		}

		if(!empty($_SESSION["shopping_cart"]))
		{
			$cart_count = count(array_keys($_SESSION["shopping_cart"]));
		?>
			<div class="cart_div">
			<a style="color: yellow;" href="shoppingcart.php">
				<img src="css/icon/cart.png" height="30px" width="30px"/> YOUR CART<span><?php echo $cart_count; ?></span>
			</a>
			</div>
		<?php
		}
		?>
		</div>
	</div>
</div>

<div class="table">
	<?php
	$resultset_c = mysqli_query($conn, "SELECT * FROM tbl_currency");
	$resultset_l = mysqli_query($conn, "SELECT * FROM tbl_language");
	?>
	<div class="table-row">
		<div class="table-cell" style="text-align: left; padding: 5px; text-decoration: none;">
			<b>Currency :</b>
			<?php
			while($row_c = mysqli_fetch_assoc($resultset_c))
		    {
		    	echo "<a style='color: yellow;' href='shoppinglist.php?cur=".$row_c["currency_id"]."&lang=".$lang."'>".$row_c["currency_code"]."</a> | ";
		    }
			?>
		</div>
		<div class="table-cell" style="text-align: right; padding: 5px; text-decoration: none;">
			<b>Language : </b>
			<?php
		    while($row_l = mysqli_fetch_assoc($resultset_l))
		    {
		    	echo "<a style='color: yellow;' href='shoppinglist.php?cur=".$cur."&lang=".$row_l["lang_id"]."'>".$row_l["lang_name"]."</a> | ";
		    }
			?>
		</div>
	</div>
</div>

<hr/>

<div class="container" style="padding: 2px 15px;">
	<h3><?php echo $status; ?></h3>
	<table width="100%">
		<tr>
			<th width="60px">Product ID</th>
			<th width="50px">Image</th>
			<th width="200px">Name</th>
			<th width="300px">Details</th>
			<th width="200px">Additional Info</th>
			<th width="100px">Price</th>
			<th width="100px">Quantity</th>
			<th width="100px">Action</th>
		</tr>
		<?php

		$limit = 10;  // Number of entries to show in a page...
		if (isset($_GET["page"]))
		{
			$page  = $_GET["page"];
		}
		else
		{
			$page=1;
		};
		$start_from = ($page-1) * $limit;

		$productquery = "SELECT pMaster.product_id, pLang.product_name, pLang.product_details, pLang.product_add_info, curr.currency_symbol, pCurr.currency_value, pMaster.product_image FROM tbl_product_master pMaster
			INNER JOIN tbl_product_language pLang ON (pLang.product_id = pMaster.product_id)
			INNER JOIN tbl_product_currency pCurr ON (pCurr.product_id = pMaster.product_id)
			INNER JOIN tbl_language lang ON (lang.lang_id = pLang.language_id)
			INNER JOIN tbl_currency curr ON (curr.currency_id = pCurr.currency_id)
			WHERE (plang.language_id = '$lang' AND pCurr.currency_id = '$cur')
			ORDER BY pMaster.product_id ASC LIMIT $start_from, $limit";
		$productarray = mysqli_query($conn, $productquery);
		
		if(mysqli_num_rows($productarray) > 0)
		{
			while($row = mysqli_fetch_assoc($productarray))
			{
				echo "<form action='' method='post'>
					<tr>
					<td>".$row["product_id"]."<input type='hidden' name='product_id' value=".$row['product_id']."/></td>
					<td><img class='zoom' border='1' height='30px' width='30px' src='".$row["product_image"]."'/></td>
					<td>".$row["product_name"]."</td>
					<td>".$row["product_details"]."</td>
					<td>".$row["product_add_info"]."</td>
					<td><b>".$row["currency_symbol"]." ".$row["currency_value"]."</b></td>
					<td><input type='text' name='quantity' class='quantity' maxlength='2' onkeypress='return onlyNos(event, this);' required></td>
					<td><input type='submit' class='button-cart' value='Add To Cart'></td>
					</tr>
				</form>";
			}
		}
		else
		{
		?>
		<tr>
			<td colspan="8"><strong>-- NO PRODUCTS AVAILABLE --</strong></td>
		</tr>
		<?php
		}
		?>
	</table>
	<p class="pagination">
    	<?php
		$sql = mysqli_query($conn, "SELECT COUNT(*) FROM tbl_product_master");

		$row = mysqli_fetch_row($sql);
		$total_records = $row[0];

		// Number of pages required..
		$total_pages = ceil($total_records / $limit);   
		$pageLink = "";
		for ($i=1; $i<=$total_pages; $i++)
		{ 
			if ($i==$page)
			{ 
				$pageLink .= "<a class='active' href='shoppinglist.php?page=".$i."'>".$i."</a>"; 
			}
			else
			{
 				$pageLink .= "<a href='shoppinglist.php?page=".$i."'>".$i."</a>";
			}
		}   
		echo $pageLink;
    	?>
    </p>
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