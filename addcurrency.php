<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<!DOCTYPE html>
<html>

<title>Add Currency</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<script src="js/jquery-3.4.1.js"></script>

	<style type="text/css">
		.container .box { 
            width: 100%;
            display:table; 
        }
        .container .box .box-row { 
            display:table-row;
            padding: 10px;
        }
        .container .box .box-cell { 
            display:table-cell; 
            width: auto; 
            padding: 10px; 
        }
        .container .box .box-cell .user-left {
            align-content: justify;
            width: 30%;
        }
        .container .box .box-cell .user-right {
            align-content: justify;
            width: 70%;
        }
        .box {
        	color: lavender;
        }
    </style>
</head>

<body>
	
<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:25px;">

<h2>Add Currency</h2>
<hr/>

<div class="container">
	<div class="box">
		<div class="box-row">			
			<div class="box-cell user-left" style="padding: 15px; background-color: gainsboro; border: 2px solid navy; border-radius: 5px;">
				<form action="" method="post">
				<div class="box">
					<label>Currency Name</label><br>
					<input type="text" name="currency_code" class="form-control" required>
				</div>
				<br>
				<div class="box">
					<label>Currency Symbol</label><br>
					<input type="text" name="currency_symbol" class="form-control" required>
				</div>
				<br>
				<div class="box">
					<input type="submit" name="submit" value="Save" class="button">
				</div>
				</form>

				<?php
				include("config.php");

				if(isset($_POST["submit"]))
				{
					$currency_code = $_POST["currency_code"];
					$currency_symbol = $_POST["currency_symbol"];

					$query = "INSERT INTO tbl_currency (currency_code, currency_symbol) VALUES ('".$currency_code."', '".$currency_symbol."')";
					$result = mysqli_query($conn, $query);
					if($result)
					{
						// Get last Currency_ID
						$curr_id = mysqli_insert_id($conn);

						// Print Success Message
						echo "<h3><font color='navy'>Save Successful..</font></h3>";

						// Insert rows
						$test = mysqli_query($conn, "SELECT product_id FROM tbl_product_master");
		                while ($row_test = mysqli_fetch_assoc($test))
		                {
		                	$test_query = mysqli_query($conn, "INSERT INTO tbl_product_currency (product_id, currency_id) VALUES ('".$row_test["product_id"]."', '".$curr_id."')");
		                }

						// Redirect to previous page
						header('Refresh:3; URL=addcurrency.php');
					}
					else
					{
						echo "<h3><font color='red'>Save Failed..</font></h3>";
						header('Refresh:3; URL=addcurrency.php');
					}
				}
				?>
			</div>

			<div class="box-cell user-right" style="padding: 15px;">
				<table width="90%" cellpadding="1" cellspacing="0" align="center">
					<tr>
						<th>Serial</th>
						<th>Code</th>
						<th>Symbol</th>
					</tr>

					<?php

					include('config.php');

					$resultset = mysqli_query($conn, "SELECT * FROM tbl_currency");

					if (mysqli_num_rows($resultset) > 0) 
					{
						$serial = 1;	
					    while($row = mysqli_fetch_assoc($resultset))
					    {
			    	?>

			    	<tr>
			    		<td><?php echo $serial; ?></td>
			    		<td><?php echo $row["currency_code"]; ?></td>
			    		<td><?php echo $row["currency_symbol"]; ?></td>
			    	</tr>

			    	<?php
			    		$serial++;
		    			}
		    		}
		    		else
		    		{
	    			?>
	    			<tr><td colspan="3"><b>- NO RECORDS FOUND -</b></td></tr>
	    			<?php
		    		}
		    		?>
				</table>
			</div>
		</div>
	</div>
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