<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<!DOCTYPE html>
<html>

<title>Add Language</title>

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

<h2>Add Languages</h2>
<hr/>

<div class="container">
	<div class="box">
		<div class="box-row">			
			<div class="box-cell user-left" style="padding: 15px; background-color: gainsboro; border: 2px solid navy; border-radius: 5px;">
				<form action="" method="post">
				<div class="box">
					<label>Language Name</label><br>
					<input type="text" name="lang_name" class="form-control" required>
				</div>
				<br>
				<div class="box">
					<label>Language Code</label><br>
					<input type="text" name="lang_code" class="form-control" required>
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
					$lang_name = $_POST["lang_name"];
					$lang_code = $_POST["lang_code"];

					$query = "INSERT INTO tbl_language (lang_code, lang_name) VALUES ('".$lang_code."', '".$lang_name."')";
					$result = mysqli_query($conn, $query);
					if($result)
					{
						// Get last Language_ID
						$lang_id = mysqli_insert_id($conn);

						// Print Success Message
						echo "<h3><font color='navy'>Save Successful..</font></h3>";
						
						// Insert language rows corresponding to Product_ID
						$test = mysqli_query($conn, "SELECT product_id FROM tbl_product_master");
		                while ($row_test = mysqli_fetch_assoc($test))
		                {
		                	$test_query = mysqli_query($conn, "INSERT INTO tbl_product_language (product_id, language_id) VALUES ('".$row_test["product_id"]."', '".$lang_id."')");
		                }
		                // Redirect to previous page
		                header('Refresh:3; URL=addlanguage.php');               
					}
					else
					{
						echo "<h3><font color='red'>Save Failed..</font></h3>";
						header('Refresh:3; URL=addlanguage.php');
					}
				}
				?>
			</div>

			<div class="box-cell user-right" style="padding: 15px;">
				<table width="90%" cellpadding="1" cellspacing="0" align="center">
					<tr>
						<th>Serial</th>
						<th>Language</th>
						<th>Code</th>
					</tr>

					<?php

					include('config.php');

					$resultset = mysqli_query($conn, "SELECT * FROM tbl_language");

					if (mysqli_num_rows($resultset) > 0) 
					{
						$serial = 1;	
					    while($row = mysqli_fetch_assoc($resultset))
					    {
			    	?>

			    	<tr>
			    		<td><?php echo $serial; ?></td>
			    		<td><?php echo $row["lang_name"]; ?></td>
			    		<td><?php echo $row["lang_code"]; ?></td>
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