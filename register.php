<html>

<title>Registration</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style-login.css">

	<link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/font-abel.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
</head>

<?php

// Configuration file....
include_once("configfunction.php");

$registeruser = new DataBase_Conn();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Fetch Values
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

    $sql = $registeruser->RegisterUser($uname, $pass, $email, $address, $imgContent);
  
	if($sql)
	{
		$alert_msg = "<h3><font color='green'>Registration Successful..</font></h3><br>";
		header('Refresh:3; URL=index.php');
	}
	else
	{
		$alert_msg = "<h3><font color='red'>Registration Failed. User Already Exists..</font></h3><br>";
		header('Refresh:3; URL=register.php');
	}
}

?>

<body>

<div class="container-login100">
	<div class="wrap-login100">

	<form class="login100-form" action="" method="post" enctype="multipart/form-data">
		<h1>REGISTRATION PAGE</h1>
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
			<input type="email" name="email_id" required="true" class="form-control" placeholder="Email-ID">
		</div>
		<br>
		<div>
			<input type="text" name="address" required="true" class="form-control" placeholder="Address">
		</div>
		<br>
		<div>
			<input type="file" name="imageupload" id="imageupload" class="form-control" placeholder="Upload Image" required="true">
		</div>
		<br>
		<div>
			<p><input type="submit" name="register" value="REGISTER" class="button"></p>
		</div>
		<br>
		<div>
			<?php if (!empty($alert_msg)){
				echo $alert_msg;
			} ?>
		</div>
	</form>

    <p><a href="index.php">
        <button style="border-radius: 5px; background-color: gainsboro; height: 35px; width: 130px; cursor: pointer; padding-left: 5px;">
            <font color="black" face="Abel"><b>GO BACK</b></font>
        </button>
    </a></p>
	
	</div>
</div>

</body>

</html>