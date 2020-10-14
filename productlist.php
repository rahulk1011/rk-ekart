<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<?php
include ("config.php");

$status = "";

$lang = "1";
if(isset($_GET['lang']))
{
	$lang = $_GET['lang'];
}

//Deletion
if(isset($_GET['delete']))
{
    // Geeting deletion row id
    $product_id = $_GET['delete'];
    
    $img_delete = mysqli_query($conn, "DELETE FROM tbl_product_master WHERE product_id='$product_id'");
    $lang_delete = mysqli_query($conn, "DELETE FROM tbl_product_language WHERE product_id='$product_id'");
    $cur_delete = mysqli_query($conn, "DELETE FROM tbl_product_currency WHERE product_id='$product_id'");

    if($img_delete)
    {
        $status = "<font color='yellow'>Product Deleted Successfully..!!</font>";
        header('Refresh:3; URL=productlist.php');
    }
}
?>

<!DOCTYPE html>
<html>

<title>Product List</title>

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
            padding:10px; 
        }
        .container .box .box-cell .user-left {
            align-content: justify;
            width: 40%;
        }
        .container .box .box-cell .user-right {
            align-content: justify;
            width: 60%;
        }
        .box {
        	color: red;
        }
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
    </style>
</head>

<body>
	
<?php
include ("menu.php");
?>

<div style="margin-left:12%; padding:15px;">
<h2>Products Details</h2>
<div class="table" style="color: white;">
	<?php
	$resultset_l = mysqli_query($conn, "SELECT * FROM tbl_language");
	?>
	<div class="table-cell" style="text-align: right; padding: 5px; text-decoration: none;">
		<b>Language : </b>
		<?php
	    while($row_l = mysqli_fetch_assoc($resultset_l))
	    {
	    	echo "<a style='color: yellow;' href='productlist.php?lang=".$row_l["lang_id"]."'>".$row_l["lang_name"]."</a> | ";
	    }
		?>
	</div>
</div>
<hr/>

<h3><?php echo $status; ?></h3>

<div class="container">
	<div class="box">
		<div class="box-row">
			<table align="center" width="95%">
				<tr>
					<th>Product ID</th>
					<th>Product Image</th>
					<th>Product Name</th>
					<th>Product Details</th>
					<th>Product Status</th>
					<th>Add Date</th>
					<th>Edit Date</th>
					<th>Action</th>
				</tr>
				<?php
				include("config.php");

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

				$query = "SELECT * FROM tbl_product_master pMaster 
				INNER JOIN tbl_product_language pLang ON (pLang.product_id = pMaster.product_id)
				WHERE pLang.language_id = '$lang' ORDER BY pMaster.product_id ASC LIMIT $start_from, $limit";
				$sql = mysqli_query($conn, $query);
				if(mysqli_num_rows($sql) > 0)
				{
				    while($row=mysqli_fetch_array($sql))
				    {
				?>
				<tr>
					<td><?php echo $row["product_id"]; ?></td>
					<td><?php echo "<img class='zoom' border='1' height='30px' width='30px' src='".$row["product_image"]."' />"; ?></td>
					<td><?php echo $row["product_name"]; ?></td>
					<td><?php echo $row["product_details"]; ?></td>
					<td><?php if($row["product_status"] == 1) {echo "Active";} else {echo "Inactive";} ?></td>
					<td><?php echo date('d.m.Y, H:i:s', strtotime($row["add_date"])); ?></td>
					<td><?php echo date('d.m.Y, H:i:s', strtotime($row["edit_date"])); ?></td>
					<td>
						<a href="editproducts.php?product_id=<?php echo htmlentities($row['product_id']);?>"><button style="cursor: pointer;"><img src="css/icon/edit.png" height="15px" width="10px"></button></a>
						<a href="productlist.php?delete=<?php echo htmlentities($row['product_id']);?>"><button style="cursor: pointer;" onClick="return confirm('Do you want to delete??');"><img src="css/icon/delete.png" height="15px" width="10px"></button></a>
					</td>
				</tr>
				<?php
					}
				}
				else
				{
				?>
				<tr>
					<td colspan="8"><b>-- NO RECORDS FOUND --</b></td>
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