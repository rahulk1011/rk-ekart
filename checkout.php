<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<!DOCTYPE html>
<html>

<title>Checkout</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	
	<script src="js/jquery-3.4.1.js"></script>
</head>

<body>

<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:1px 16px;">

	<?php

		include_once("config.php");

		$uname = $_SESSION["login_user"];

		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$rnd_str = substr(str_shuffle($str_result), 0, 5);

		date_default_timezone_set("Asia/Calcutta");
  		$order_id = strval("OD-".$rnd_str."-".date('dmy-His', time()));

		$sql_userinfo = mysqli_query($conn, "SELECT user_id, username, email_id, address FROM tbl_userdetail WHERE username = '$uname'");

		$row_userinfo = mysqli_fetch_array($sql_userinfo);

		if(mysqli_num_rows($sql_userinfo) > 0)
		{
			$user_id = $row_userinfo["user_id"];
			$user_username = $row_userinfo["username"];
			$user_email = $row_userinfo["email_id"];
			$user_address = $row_userinfo["address"];
		}

		$save_userinfo = mysqli_query($conn, "INSERT INTO tbl_order_userdetail (u_order_id, user_id, username, address, email_id) VALUES ('$order_id', '$user_id', '$user_username', '$user_address', '$user_email')");

		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$amount = $_SESSION["total_price"];
			$product_currency = $_SESSION["currency_symbol"];
			$timestamp = date("Y-m-d H:i:s");

			$save_order = mysqli_query($conn, "INSERT INTO tbl_order_master (o_order_id, user_id, order_date, order_currency, order_amount) VALUES ('$order_id', '$user_id', '$timestamp', '$product_currency', '$amount')");

			foreach ($_SESSION["shopping_cart"] as $_SESSION["products"])
			{
				$product_id = $_SESSION["products"]["product_id"];
				$product_name = $_SESSION["products"]["product_name"];
				$product_details = $_SESSION["products"]["product_details"];
				$quantity = $_SESSION["products"]["quantity"];
				$product_price = $_SESSION["products"]["currency_value"];

				$sql_productinfo = mysqli_query($conn, "INSERT INTO tbl_order_productdetail (p_order_id, product_id, product_name, product_detail, quantity, price) VALUES ('$order_id', '$product_id', '$product_name', '$product_details', '$quantity', '$product_price')");
			}
			if ($sql_productinfo)
			{
				echo "<p><h2>CHECKOUT SUCCESSFUL..!!</h2></p>";
				echo "<p><h3>YOUR ORDER-ID IS: ".$order_id."</h3></p>";
				echo "<p><h3>Your Total Bill Amount: ".$product_currency." ".$amount."</h3></p>";
			}
			else
			{
				echo "<p><h2>SOMETHING WENT WRONG..!!</h2></p>";
			}
			unset($_SESSION["shopping_cart"]);
		}
	?>
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