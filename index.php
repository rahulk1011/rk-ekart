<?php
	// Configuration file....
	include("config.php");
	session_start();
?>

<!DOCTYPE html>
<html>

<title>Login</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style-login.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
</head>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Fetch Values
	$uname = $_POST["username"];
	$pass = $_POST["password"];

	//$_SESSION['login_user'] = "$uname";
	
	$result = mysqli_query($conn, "SELECT * FROM tbl_userdetail WHERE username = '$uname' AND password = '$pass'");

	$row = mysqli_fetch_array($result);

	if($row["username"] == $uname && $row["password"] == $pass)
	{
		$prev_login = "SELECT last_login FROM tbl_userdetail WHERE username = '$uname'";
		mysqli_query($conn, $prev_login);

		$_SESSION["login_user"] = $uname;
		$_SESSION["user_id"] = $row["user_id"];

        /* cheecks already last login details are added or not */

		$count_sql = "SELECT lastlogin FROM tbl_userlogin_history WHERE user_id='".$row["user_id"]."' ORDER BY id DESC LIMIT 0,1 ";
		$details_records_query = mysqli_query($conn, $count_sql);
		$login_details_history_arr = mysqli_fetch_array($details_records_query);

		if(empty($login_details_history_arr))
		{
			/* if empty last login is the current login time for first time log in user */
			$last_login_time = time();
		}
		else
		{
			$last_login_time = $login_details_history_arr['lastlogin'];
		}


		$logintime = "UPDATE tbl_userdetail SET last_login = '".$last_login_time."' WHERE username = '$uname'";
		
		
		mysqli_query($conn, $logintime);

		/* data inserts into login history */
		$login_history_insert_sql = "INSERT INTO tbl_userlogin_history SET user_id='".$row["user_id"]."'";
		mysqli_query($conn, $login_history_insert_sql);

		header("location: welcome.php");
	}
	else
	{
		$alert_msg = "<h3><font color='red'>Invalid Username / Password..</font></h3>";
		header('Refresh:2; URL=index.php');
	}
}

mysqli_close($conn);

?>

<body>

<div class="container-login100">
	<div class="wrap-login100">
	<form class="login100-form" action="" method="post">
		<h1>LOGIN PAGE</h1>
		<br>
		<div>
			<img src="css/images/logo.jpg" class="login100-form-logo">
		</div>
		<br>
		<div>
			<input type="text" name="username" required="true" class="form-control" placeholder="Username">
		</div>
		<br>
		<div>
			<input type="password" name="password" required="true" class="form-control" placeholder="Password">
		</div>
		<br>
		<div>
			<?php if (!empty($alert_msg)){
				echo $alert_msg;
			} ?>
		</div>
		<br>
		<div>
			<p><input type="submit" name="login" value="LOGIN" class="button"></p>
		</div>
	</form>
	<br>

	<div>
	<a href="register.php">
        <button style="border-radius: 5px; background-color: gainsboro; height: 30px; width: 180px; cursor: pointer;">
            <font color="black" face="Abel"><b>NEW USER: REGISTER</b></font>
        </button>
    </a>
    <a href="userlist.php">
        <button style="border-radius: 5px; background-color: gainsboro; height: 30px; width: 150px; cursor: pointer;">
            <font color="black" face="Abel"><b>SHOW ALL USERS</b></font>
        </button>
    </a>
	</div>
	</div>
</div>

</body>

</html>