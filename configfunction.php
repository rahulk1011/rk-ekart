<?php

define('servername','localhost');
define('username','root');
define('password' ,'');
define('dbname', 'rahulkart');

class DataBase_Conn
{
	function __construct()
	{
		$conn = mysqli_connect(servername,username,password,dbname);
		$this->db_conn = $conn;

		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}
	
	public function RegisterUser($uname, $pass, $email, $address, $imgContent)
	{
		// Checking whether a user of same name exists or not
		
		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$rnd_str = substr(str_shuffle($str_result), 0, 5);

		date_default_timezone_set("Asia/Calcutta");
  		$user_id = strval($rnd_str.date('dm-His', time()));

    	$check = mysqli_query($this->db_conn, "SELECT * FROM tbl_userdetail WHERE username='$uname'");
	    $checkrows=mysqli_num_rows($check);

	    if($checkrows>0)
	    {
			header('Refresh:0; URL=register.php');
	    }
	    else
	    {
			//Insert values from the form

			$ret = mysqli_query($this->db_conn, "INSERT IGNORE INTO tbl_userdetail (user_id, username, password, email_id, address, image_upload) VALUES ('$user_id', '$uname', '$pass', '$email', '$address', '$imgContent')");
			return $ret;
	    }
	}

	public function FetchUserList()
	{
		$sql = mysqli_query($this->db_conn, "SELECT * FROM tbl_userdetail");
		return $sql;
	}

	public function FetchOneRecord($id)
	{
		$oneresult = mysqli_query($this->db_conn,"SELECT * FROM tbl_userdetail WHERE id = $id");
		return $oneresult;
	}

	public function UpdateUser($userid, $uname, $pass, $email, $address, $imgContent)
	{
		$updaterecord = mysqli_query($this->db_conn,"UPDATE tbl_userdetail SET username='$uname', password='$pass', email_id='$email', address='$address', image_upload='$imgContent' WHERE id='$userid' ");
		return $updaterecord;
	}

	public function DeleteUser($rowid)
	{
		$deleterecord = mysqli_query($this->db_conn, "DELETE FROM tbl_userdetail WHERE user_id = '$rowid'");
		return $deleterecord;
	}

	public function SaveCategory($catparent, $catname)
	{
		if ($catparent == "")
		{
			// Checking of Dulicate Parent Category...

			$check = mysqli_query($this->db_conn,"SELECT * FROM tbl_category WHERE category_parent='$catname'");
	    	$checkrows=mysqli_num_rows($check);

	    	if($checkrows>0)
		    {
				echo '<script language="javascript">alert("Parent Category Already Exists..!!")</script>';
				header('Refresh:3; URL=category.php');
		    }
		    else
		    {
				//Insert values from the Form
				
				$sql = mysqli_query($this->db_conn, "INSERT IGNORE INTO tbl_category (category_parent, category_name) VALUES ('$catname', '$catname')");
				return $sql;
		    }
		}
		else
		{
			// Checking of Dulicate Child Category...

			$check = mysqli_query($this->db_conn, "SELECT * FROM tbl_category WHERE category_name='$catname'");
	    	$checkrows=mysqli_num_rows($check);

	    	if($checkrows>0)
		    {
				echo '<script language="javascript">alert("Sub-Category Already Exists..!!")</script>';
				header('Refresh:3; URL=category.php');
		    }
		    else
		    {
				//Insert values from the Form
				$sql = mysqli_query($this->db_conn, "INSERT IGNORE INTO tbl_category (category_parent, category_name) VALUES ('$catparent', '$catname')");
				return $sql;
		    }
		}
	}

	public function FetchCategoryList()
	{
		$sql = mysqli_query($this->db_conn, "SELECT * FROM tbl_category");
		return $sql;
	}

	public function DeleteCategory($rowid)
	{
		$deleterecord = mysqli_query($this->db_conn, "DELETE FROM tbl_category WHERE category_name='$rowid'");
		return $deleterecord;
	}

	public function SaveProduct($productid, $productcategory, $productdetails, $productprice, $imgContent)
	{
        // Checking whether a product of same name exists or not

	    $check = mysqli_query($this->db_conn, "SELECT * FROM tbl_product WHERE product_id='$productid'");
	    $checkrows=mysqli_num_rows($check);

	    if($checkrows>0)
	    {
			echo '<script language="javascript">alert("Product Entry Failed. Product-ID Already Exists..!!")</script>';
			header('Refresh:0; URL=product.php');
	    }
	    else
	    {
			//Insert values from the form
			
			$ret = mysqli_query($this->db_conn, "INSERT IGNORE INTO tbl_product (product_id, product_category, product_details, product_price, image_upload) VALUES ('$productid', '$productcategory', '$productdetails', '$productprice', '$imgContent')");
			return $ret;
	    }
	}

	public function FetchProductList()
	{
		$sql = mysqli_query($this->db_conn, "SELECT * FROM tbl_product");
		return $sql;
	}

	public function DeleteProduct($rowid)
	{
		$deleterecord = mysqli_query($this->db_conn, "DELETE FROM tbl_product WHERE product_id='$rowid'");
		return $deleterecord;
	}

	public function FetchProducts($query) 
	{
		$result = mysqli_query($this->db_conn, $query);
		while($row=mysqli_fetch_assoc($result))
		{
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}

	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
}

?>