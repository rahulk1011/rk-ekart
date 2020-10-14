<?php
	session_start();
	
	include_once("configfunction.php");

	$updateuser = new DataBase_Conn();

	if(isset($_POST['update']))
	{
		// Get the userid
		$userid = intval($_GET['id']);
		// Posted Values
		$uname = $_POST["username"];
		$pass = $_POST["password"];
		$email = $_POST["email_id"];
		$address = $_POST["address"];

		$check = getimagesize($_FILES["imageupload"]["tmp_name"]);
	    if($check !== false)
		{
			$image = $_FILES['imageupload']['tmp_name'];
	        $imgContent = addslashes(file_get_contents($image));
	    }

		//Function Calling
		$sql = $updateuser->UpdateUser($userid, $uname, $pass, $email, $address, $imgContent);

		// Mesage after updation
		$alert_msg = "<h3><font color='yellow'>Details Updated Successfully</font></h3>";
		// Code for redirection
		header('Refresh:3; URL=welcome.php');
	}
?>

<!DOCTYPE html>
<html>

<title>User Edit</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

	<h3>Edit User</h3>

	<?php 
		// Get the userid
		$id = intval($_GET['id']);
		$sql =$updateuser->FetchOneRecord($id);

		while($row=mysqli_fetch_array($sql))
		{
	?>

	<form action="" method="post" enctype="multipart/form-data">
		<table align="center" width="60%">
			<tr>
				<td><label>User-ID</label></td>
				<td><b><?php echo $row['user_id']; ?></b></td>
			</tr>
			<tr>
				<td><label>Name</label></td>
				<td><input type="text" name="username" value="<?php echo htmlentities($row['username']); ?>" class="form-control" required></td>
			</tr>
			<tr>
				<td><label>Password</label></td>
				<td><input type="text" name="password" value="<?php echo htmlentities($row['password']); ?>" class="form-control" required></td>
			</tr>
			<tr>
				<td><label>Email-ID</label></td>
				<td><input type="text" name="email_id" value="<?php echo htmlentities($row['email_id']); ?>" class="form-control" required></td>
			</tr>
			<tr>
				<td><label>Address</label></td>
				<td><input type="text" name="address" value="<?php echo htmlentities($row['address']); ?>" class="form-control" required></td>
			</tr>
			<tr>
				<td><label>Profile Image</label></td>
				<td><input type="file" name="imageupload" id="imageupload" class="form-control" required></td>
			</tr>
		</table>
		<p><?php if (!empty($alert_msg)){ echo $alert_msg;	} ?></p>
	<?php 
		}
	?>
	<p>
		<input type="submit" name="update" value="Update" class="button">
		<a href="welcome.php" class="button-anchor">CANCEL</a>
	</p>
	</form>

</body>

</html>