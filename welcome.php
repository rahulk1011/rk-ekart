<?php
	include('config.php');
	session_start();

	// validation
	$user_check = $_SESSION['login_user'];
	$ses_sql = mysqli_query($conn,"SELECT * FROM tbl_userdetail WHERE username = '$user_check' ");
	$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
	$login_session = $row['username'];

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
?>

<!DOCTYPE html>
<html>

<title>Welcome</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<script src="js/timeout.js"></script>

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
            width:50%; 
            padding:10px; 
        }
        .container .box .box-cell .user-left {
            align-content: justify;
        }
        .container .box .box-cell .user-right {
            align-content: justify;
        }
        .box {
        	color: lavender;
        }
        b {
        	color: lavender;
        	font-size: larger;
        	text-transform: uppercase;
        }

		.circle {
			border: 4px outset darkgoldenrod;
			height: 120px;
			width: 120px;
			border-radius: 50%;
		}
		img {
			border-radius: 50%;
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
	</style>
</head>

<body>

<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:1px 16px;">

	<h2>Welcome <?php echo $row["username"]; ?>..</h2>
	<hr/>
	<br>
	<div class="container">
		<div class="box">
			<div class="box-row">
				<div class="box-cell user-left" style="padding: 5px 50px">
					<div class="box">
						<div><b>Profile Picture</b></div>
						<br>
						<div class="circle">
							<?php echo "<img height='120px' width='120px' src='data:image/jpg;base64,".base64_encode($row["image_upload"])."'/>"; ?>
						</div>
					</div>
					<br>
					<div class="box"><b>Unique-ID :</b> <?php echo $row["user_id"]; ?></div>
					<br>
					<div class="box"><b>Address :</b> <?php echo $row["address"]; ?></div>
					<br>
					<div class="box"><b>Email-ID :</b> <?php echo $row["email_id"]; ?></div>
					<br>
					<div class="box"><b>Last Login :</b> <?php $timestamp = strtotime($row["last_login"]); echo date('d-M-Y, h:i:s A', $timestamp); ?></div>
					<br>
					<div class="box"><b>Edit Details : </b><a href="edituser.php?id=<?php echo htmlentities($row['id']);?>"><button style="border-radius: 3px; cursor: pointer;"><img src="css/icon/edit.png" height="25px" width="20px"></button></a></div>
				</div>

				<div class="box-cell user-right" style="padding: 5px 50px">
					<table width="80%" cellpadding="1" cellspacing="0" align="center">
						<tr>
							<th>Serial</th>
							<th>Login History</th>
						</tr>

						<?php

						include('config.php');

						$limit = 10;  // Number of entries to show in a page...
						if (isset($_GET["page"]))
						{
							$page = $_GET["page"];
						}
						else
						{
							$page = 1;
						};
						$start_from = ($page - 1) * $limit;

						$loginrecord = "SELECT lastlogin FROM tbl_userlogin_history RIGHT JOIN tbl_userdetail ON tbl_userlogin_history.user_id = tbl_userdetail.user_id WHERE tbl_userdetail.username = '".$_SESSION['login_user']."' ORDER BY tbl_userlogin_history.id DESC LIMIT $start_from, $limit";

						$resultset = mysqli_query($conn, $loginrecord);

						if (mysqli_num_rows($resultset) > 0) 
						{
						    // output data of each row
							$serial = 1;
							
						    while($row = mysqli_fetch_assoc($resultset))
						    {
				    	?>

				    	<tr>
				    		<td><?php echo $serial; ?></td>
				    		<td><?php $timestamp = strtotime($row["lastlogin"]); echo date('d-M-Y, h:i:s A', $timestamp); ?></td>
				    	</tr>

				    	<?php
				    		$serial++;
			    			}
			    		}
			    		?>
					</table>
					<p class="pagination">
				    	<?php
			    		$sql = mysqli_query($conn, "SELECT COUNT(*) FROM tbl_userlogin_history RIGHT JOIN tbl_userdetail ON tbl_userlogin_history.user_id = tbl_userdetail.user_id WHERE tbl_userdetail.username = '".$_SESSION['login_user']."' ORDER BY tbl_userlogin_history.id DESC");

						$row = mysqli_fetch_row($sql);
						$total_records = $row[0];

						// Number of pages required..
						$total_pages = ceil($total_records / $limit);   
						$pageLink = "";                         
						for ($i=1; $i<=$total_pages; $i++)
						{ 
							if ($i==$page)
							{ 
								$pageLink .= "<a class='active' href='welcome.php?page=".$i."'>".$i."</a>"; 
							}
							else
							{
				 				$pageLink .= "<a href='welcome.php?page=".$i."'>".$i."</a>";
							}
						}   
						echo $pageLink;
				    	?>
				    </p>
				</div>
			</div>
		</div>
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