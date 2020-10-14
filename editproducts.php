<!DOCTYPE html>
<html>

<title>Product Edit</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	
	<style>
		.box {
			background-color: gainsboro;
			width: 80%;
			padding: 15px;
			margin-left: 8%;
		}
		p {
			text-align: center;
		}
	</style>
</head>

<body>
<h3>Edit Product Info</h3>

<div class="box">

<form action="" method="post" enctype="multipart/form-data">

<table align="center">
<?php
include ("config.php");

$product_id = intval($_GET['product_id']);

$img_sql = mysqli_query($conn, "SELECT product_image, folder_id FROM tbl_product_master WHERE product_id = '$product_id'");
while($i_row = mysqli_fetch_array($img_sql))
{
?>
<tr>
	<th>Current Image</th>
	<td>
		<?php
			$c_image = $i_row["product_image"]; echo "<img height='40px' width='40px' src='".$c_image."' />";
			$folder_id = $i_row["folder_id"];
		?>
		&nbsp;&nbsp;&nbsp;
		<input type="file" name="product_image" id="product_image">
	</td>
</tr>
<?php
}

$pro_sql = mysqli_query($conn, "SELECT pLang.product_id, pLang.id, pLang.product_name, pLang.product_details, pLang.product_add_info, lang.lang_code, lang.lang_id
	FROM tbl_product_language pLang
	INNER JOIN tbl_language lang ON (lang.lang_id = pLang.language_id)
	WHERE (pLang.product_id = '$product_id')");

while($p_row=mysqli_fetch_array($pro_sql))
{
?>
<tr>
	<th width="200px">Product Name in <?php echo $p_row["lang_code"]; ?></th>
	<td width="750px"><input type="text" name="product_name[]" multiple value="<?php echo htmlentities($p_row['product_name']); ?>" class="form-control" required></td>
</tr>
<tr>
	<th>Product Details in <?php echo $p_row["lang_code"]; ?></th>
	<td><input type="text" name="product_details[]" multiple value="<?php echo htmlentities($p_row['product_details']); ?>" class="form-control" required></td>
</tr>
<tr>
	<th>Additional Info in <?php echo $p_row["lang_code"]; ?></th>
	<td><input type="text" name="product_add_info[]" multiple value="<?php echo htmlentities($p_row['product_add_info']); ?>" class="form-control" required></td>
</tr>
<?php
}

$val_sql = mysqli_query($conn, "SELECT pCurr.currency_value, curr.currency_code, curr.currency_id FROM tbl_product_currency pCurr
	INNER JOIN tbl_currency curr ON (curr.currency_id = pCurr.currency_id)
	WHERE (pCurr.product_id = '$product_id')");

while($v_row = mysqli_fetch_array($val_sql))
{
?>
<tr>
	<th>Price in <?php echo $v_row["currency_code"]; ?></th>
	<td><input type="text" name="currency_value[]" multiple value="<?php echo htmlentities($v_row['currency_value']); ?>" class="form-control" required></td>
</tr>
<?php
}
?>
<tr>
	<td colspan="2"><input type="submit" name="update" value="UPDATE" class="button"></td>
</tr>
</table>

</form>

<?php
if(isset($_POST['update']))
{
	$i_flag = 0;
	$p_flag = 0;
	$c_flag = 0;

	$product_id = intval($_GET['product_id']);
	date_default_timezone_set("Asia/Calcutta");
    $edit_date = date('Y-m-d H:i:s');

    // Product Image & Update Edit Section....
    $product_image = $_FILES['product_image']['tmp_name'];
    if ($product_image == "")
	{
		$i_updaterecord = mysqli_query($conn, "UPDATE tbl_product_master SET product_image = '$c_image' WHERE product_id ='$product_id'");
	}
	else
	{
		$target_file = "products/".$folder_id."/". basename($_FILES["product_image"]["name"]);
		if(move_uploaded_file($product_image, $target_file))
		{
			$i_updaterecord = mysqli_query($conn,"UPDATE tbl_product_master SET product_image='$target_file' WHERE product_id='$product_id'");
		}
	}
	if ($i_updaterecord)
	{
		$p_update = mysqli_query($conn, "UPDATE tbl_product_master SET edit_date = '$edit_date' WHERE product_id='$product_id'");
		echo "<h3><font color='green'>Image Update Successful..</font></h3>";
	}

	// Product Language Edit & Update Section....
	$p_count = mysqli_query($conn, "SELECT COUNT(product_id) FROM tbl_product_language WHERE product_id = '".$product_id."'");
	$p_res = mysqli_fetch_array($p_count);
	$pCount = $p_res[0];

	$p_request = mysqli_query($conn, "SELECT * FROM tbl_product_language WHERE product_id = '".$product_id."'");
	$p_row = mysqli_fetch_array($p_request);
	$l_id = intval($p_row["language_id"]);

	for($i = 0; $i < $pCount; $i++ )
	{
		$product_name = $_POST["product_name"][$i];
		$product_details = $_POST['product_details'][$i];
		$product_add_info = $_POST['product_add_info'][$i];

		$p_updaterecord = mysqli_query($conn, "UPDATE tbl_product_language SET product_name='$product_name', product_details='$product_details', product_add_info='$product_add_info' WHERE product_id='$product_id' AND language_id='$l_id'");
		$l_id++;
	}
	if ($p_updaterecord)
	{
		$p_update = mysqli_query($conn, "UPDATE tbl_product_master SET edit_date = '$edit_date' WHERE product_id='$product_id'");
		echo "<h3><font color='green'>Details Update Successful..</font></h3>";
	}
	else
	{
		echo "<h3><font color='red'>Details Update Failed..</font></h3>";
	}

	// Product Value Edit & Update Section....
	$c_count = mysqli_query($conn, "SELECT COUNT(product_id) FROM tbl_product_currency WHERE product_id = '".$product_id."'");
	$c_res = mysqli_fetch_array($c_count);
	$cCount = $c_res[0];

	$c_request = mysqli_query($conn, "SELECT * FROM tbl_product_currency WHERE product_id = '".$product_id."'");
	$c_row = mysqli_fetch_array($c_request);
	$c_id = intval($c_row["currency_id"]);

	for($j = 0; $j < $cCount; $j++ )
	{
		$currency_value = $_POST["currency_value"][$j];

		$c_updaterecord = mysqli_query($conn, "UPDATE tbl_product_currency SET currency_value='$currency_value' WHERE product_id='$product_id' AND currency_id='$c_id'");
		$c_id++;
	}
	if ($c_updaterecord)
	{
		$p_update = mysqli_query($conn, "UPDATE tbl_product_master SET edit_date = '$edit_date' WHERE product_id='$product_id'");
		echo "<h3><font color='green'>Price Update Successful..</font></h3>";
	}
	else
	{
		echo "<h3><font color='red'>Price Update Failed..</font></h3>";
	}
}
?>
<p><a href="productlist.php">
    <button style="border-radius: 5px; background-color: black; height: 35px; width: 130px; cursor: pointer;">
        <font color="gainsboro" face="Abel">GO BACK</font>
    </button>
</a></p>

</div>

</body>

</html>