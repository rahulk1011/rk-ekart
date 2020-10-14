<!DOCTYPE html>
<html>
<body>

<?php	
	session_start();
	unset($_SESSION["login_user"]);
	unset($_SESSION["user_id"]);
	unset($_SESSION["cart_item"]);
	unset($_SESSION["shopping_cart"]);
	session_destroy();
	header("Location:index.php");
?>

</body>
</html>