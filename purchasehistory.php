<?php
	include('config.php');
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<!DOCTYPE html>
<html>

<title>Purchase History</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<script src="js/jquery-3.4.1.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/timeout.js"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			$('#fromdate').datepicker({
	            format: 'dd/mm/yyyy',
	            autoclose: true
	    	});
	    	$('#todate').datepicker({
	            format: 'dd/mm/yyyy',
	            autoclose: true
	    	});
	    	$('#searchdate').datepicker({
	            format: 'dd/mm/yyyy',
	            autoclose: true
	    	});
		});
	</script>

	<style type="text/css">
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
		.container .box { 
            width: 100%;
            display:table; 
        }
        .container .box .box-row { 
            display:table-row;
            padding: 2px;
        }
        .container .box .box-cell { 
            display: table-cell;
            padding: 2px;
            align-content: justify;
        }
	</style>
</head>

<body>

<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:1px 16px;">

<h2>Purchase History of <?php echo $_SESSION["login_user"]; ?>..</h2>
<hr/>

<form action="" method="post">
	<table align="center" padding-left="20px" cellspacing="0" width="60%">
		<tr>
			<td>From Date: <input type="text" id="fromdate" name="fromdate"></td>
			<td>To Date: <input type="text" id="todate" name="todate"></td>
			<td><input type="submit" name="submit" value="SEARCH"></td>
		</tr>
	</table>
</form>
<br>

<div>
<table align="center" width="100%" cellspacing="0">
	<tr>
		<th width="30px">Serial</th>
		<th width="190px">Order ID</th>
		<th>Order Details (Product Name, Details, Quantity, Unit Price, Amount)</th>
		<th width="100px">Total Amount</th>
		<th width="210px">Order Date</th>
	</tr>

	<?php

	include("config.php");

	$user_id = $_SESSION["user_id"];

	$pager_links = "";
	$limit = 5;  // Number of entries to show in a page...
	if (isset($_GET["page"]))
	{
		$page  = $_GET["page"];
	}
	else
	{
		$page=1;
	};
	$start_from = ($page-1) * $limit;

	if(@$_REQUEST["fromdate"])
	{
		// Fetch Values
		$fdate = $_REQUEST["fromdate"];
		$tdate = $_REQUEST["todate"];

		$pager_links .= '&fromdate='.$fdate;
		$pager_links .= '&todate='.$tdate;

	    // Convert Textbox datepicker date string into Database date format.....

		$arrfdate = explode("/", $fdate);
		$sfdate = $arrfdate[2]."-".$arrfdate[0]."-".$arrfdate[1];

		$arrtdate = explode("/", $tdate);
		$stdate = $arrtdate[2]."-".$arrtdate[0]."-".($arrtdate[1]+1);

		if ($fdate > $tdate)
		{
			echo "<script>alert('Enter Valid Dates..')</script>";
		}

		echo "<p><b>Purchase History Between:</b> ".$arrfdate[1]."/".$arrfdate[0]."/".$arrfdate[2]." & ".$arrtdate[1]."/".$arrtdate[0]."/".$arrtdate[2]."</p>";

	    $purchasehistory = mysqli_query($conn, "SELECT * FROM tbl_order_master JOIN tbl_order_productdetail ON tbl_order_master.o_order_id = tbl_order_productdetail.p_order_id WHERE tbl_order_master.user_id = '".$user_id."' AND tbl_order_master.order_date >= '$sfdate' AND tbl_order_master.order_date <= '$stdate' GROUP BY tbl_order_master.o_order_id ORDER BY tbl_order_productdetail.id DESC LIMIT $start_from, $limit");
	}
	else
	{
		$purchasehistory = mysqli_query($conn, "SELECT * FROM tbl_order_master JOIN tbl_order_productdetail ON tbl_order_master.o_order_id = tbl_order_productdetail.p_order_id WHERE tbl_order_master.user_id = '".$user_id."' GROUP BY tbl_order_master.o_order_id ORDER BY tbl_order_productdetail.id DESC LIMIT $start_from, $limit");
	}

	if (mysqli_num_rows($purchasehistory) > 0) 
	{
		$serial = 1;
		$total_quantity = 0;
		$total_amount = 0;

	    while ($rows = mysqli_fetch_array($purchasehistory, MYSQLI_ASSOC))
	    {
	?>
	<tr>
		<td><?php echo $serial; ?></td>
		<td><?php $order_id = $rows["o_order_id"]; echo $rows["o_order_id"]; ?></td>
		<td>
		<?php
		$productlist = mysqli_query($conn, "SELECT * FROM tbl_order_productdetail WHERE p_order_id='".$order_id."'");
		if (mysqli_num_rows($productlist) > 0)
		{
			while ($row_product = mysqli_fetch_array($productlist, MYSQLI_ASSOC))
			{
		?>
		<div class="container">
			<div class="box">
				<div class="box-row">
					<div class="box-cell" style="width: 20%;"><?php echo "<b>".$row_product["product_name"]."</b>"; ?></div>
					<div class="box-cell" style="width: 35%;"><?php echo $row_product["product_detail"]; ?></div>
					<div class="box-cell" style="width: 10%;"><?php echo $row_product["quantity"]; ?></div>
					<div class="box-cell" style="width: 15%;"><?php echo $rows["order_currency"]." ".$row_product["price"]; ?></div>
					<div class="box-cell" style="width: 20%;">
						<?php echo "<b>".$rows["order_currency"]." ".$amount = ($row_product["quantity"] * $row_product["price"])."</b>"; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
			}
		}
		?>
		</td>
		<td><b><?php echo $rows["order_currency"]." ".$rows["order_amount"]; ?></b></td>
		<td><?php echo date('d-M-Y, h:i:s A', strtotime($rows["order_date"])); ?></td>
	</tr>
	<?php
		$total_amount += $rows["order_amount"];
		$serial++;
		}
	}
	else
	{
	?>
	<tr>
		<td colspan="5"><b>-- NO RECORDS FOUND --</b></td>
	</tr>
	<?php
	}
	?>
	<tfoot>
		<tr>
			<th colspan="3">
				<?php
					$totamount = mysqli_query($conn, "SELECT SUM(order_amount) AS amount_sum FROM tbl_order_master WHERE user_id = '".$user_id."' "); 
					$rowval = mysqli_fetch_assoc($totamount); 
					echo "TOTAL PURCHASE AMOUNT : ".$sum_amt = $rowval['amount_sum'];
				?>
			</th>
			<th><?php echo "<b>".@$total_amount."</b>"; ?></th>
			<th></th>
		</tr>
	</tfoot>
</table>
<p class="pagination">
	<?php

	if(@$_REQUEST["fromdate"])
	{
		$sql = mysqli_query($conn, "SELECT COUNT(DISTINCT o_order_id) FROM tbl_order_master JOIN tbl_order_productdetail ON tbl_order_master.o_order_id = tbl_order_productdetail.p_order_id WHERE tbl_order_master.user_id = '".$user_id."' AND tbl_order_master.order_date >= '$sfdate' AND tbl_order_master.order_date <= '$stdate' ORDER BY tbl_order_productdetail.id DESC");
	}
	else
	{
		$sql = mysqli_query($conn, "SELECT COUNT(DISTINCT o_order_id) FROM tbl_order_master JOIN tbl_order_productdetail ON tbl_order_master.o_order_id = tbl_order_productdetail.p_order_id WHERE tbl_order_master.user_id = '".$user_id."' ORDER BY tbl_order_productdetail.id DESC");
	}

	$row = mysqli_fetch_row($sql);
	$total_records = $row[0];

	// Number of pages required..
	$total_pages = ceil($total_records / $limit);   
	$pageLink = "";                         
	for ($i=1; $i<=$total_pages; $i++)
	{ 
		if ($i==$page)
		{ 
			$pageLink .= "<a class='active' href='purchasehistory.php?page=".$i.$pager_links."'>".$i."</a>"; 
		}
		else
		{
			$pageLink .= "<a href='purchasehistory.php?page=".$i.$pager_links."'>".$i."</a>";
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
		//echo '<script language="javascript">alert("Please Log In First..!!")</script>';
    	header("Location:index.php");
	}	
?>

</body>
</html>