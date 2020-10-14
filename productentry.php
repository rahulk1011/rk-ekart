<?php
	session_start();

	if ($_SESSION["login_user"]) {
	// Checking if the user has logged in or not...
    include("config.php");
?>

<!DOCTYPE html>
<html>

<title>Product Entry</title>

<head>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-nav.css">
    
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/font-abel.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">

	<script src="js/jquery-3.4.1.js"></script>
    <style>
        .container .box { 
            width: 100%;
            display:table; 
        }
        .container .box .box-row { 
            display: table-row;
            padding: 10px;
        }
        .container .box .box-cell { 
            display: table-cell;
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
            color: navy;
        }
        .form-control {
            width: 50%;
            padding: 5px;
            margin: 2px;
            box-sizing: border-box;
            border: 1px solid black;
            border-radius: 4px;
        }
    </style>
    <script>
    function onlyNos(e, t)
    {
        try 
        {
            if (window.event)
            {   var charCode = window.event.keyCode;    }
            else if (e)
            {   var charCode = e.which; }
            else {  return true;    }
            if (charCode > 31 && (charCode < 46 || charCode > 57)) 
            {   return false;   }
            return true;
        }
        catch (err) 
        {   alert(err.Description); }
    }
</script>
</head>

<body>
	
<?php
include ("menu.php");
?>

<div style="margin-left:12%;padding:2px 15px;">
<h2>Product Entry</h2>
<hr/>

<div class="container" style="padding: 10px 25px; background-color: gainsboro;">
    <form action="" method="post" enctype="multipart/form-data">
    <div class="box">
        <div class="box-row">
            <div class="box-cell user-left">
                <div class="box">
                    <label>Product Image</label>
                    <input type="file" name="productimage" id="productimage" required />
                </div>
                <br>
                <div class="box">
                    <label>Product Status</label>
                    <select name="productstatus" style="width: 150px;" required>
                        <option value=""> SELECT STATUS </option>
                        <option value="1"> Active </option>
                        <option value="0"> Inactive </option>
                    </select>
                </div>                
                <br>
                <?php

                $query = mysqli_query($conn, "SELECT * FROM tbl_currency");
                $currency_list = array();

                while($row = mysqli_fetch_assoc($query))
                {
                    $currency_list[] = $row["currency_id"];
                }
                foreach($currency_list as $key => $val)
                {
                    $cur_query = mysqli_query($conn, "SELECT currency_code, currency_symbol FROM tbl_currency WHERE currency_id = '$val'");
                    $cur_code = mysqli_fetch_row($cur_query);
                ?>
                <div class="box">
                    <label>Product Value in <?php echo $cur_code[1] ;?>:</label>
                    <input type="text" name="p_value_<?php echo $val;?>" class="form-control" onkeypress="return onlyNos(event, this);" required>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="box-cell user-right">
                <?php
                $p_id = mysqli_query($conn, "SELECT product_id FROM tbl_product_master ORDER BY product_id DESC LIMIT 1");

                $query = mysqli_query($conn, "SELECT * FROM tbl_language");
                $language_list = array();
                $arr = array();

                while($row = mysqli_fetch_assoc($query))
                {
                    $language_list[] = $row["lang_id"];
                }

                foreach($language_list as $key => $val)
                {
                    $lang_query = mysqli_query($conn, "SELECT lang_code, lang_name FROM tbl_language WHERE lang_id = '$val'");
                    $lang_code = mysqli_fetch_row($lang_query);
                ?>
                    <label>Product Name (<?php echo $lang_code[0]; ?>) :</label>
                    <input type="text" name="p_name_<?php echo $val;?>" class="form-control" required>
                    <br>
                    <label>Product Details (<?php echo $lang_code[0] ;?>):</label>
                    <textarea name="p_detail_<?php echo $val;?>" class="form-control" required rows="3"></textarea>
                    <br>
                    <label>Additional Info (<?php echo $lang_code[0]; ?>) :</label>
                    <input type="text" name="p_info_<?php echo $val;?>" class="form-control" required>
                    <br><br>
                <?php
                }
                ?>
            </div>
        </div>
        <input type="submit" name="submit" value="Save Record" class="button">
    </div>
    </form>
    <?php

    if(isset($_POST["submit"]))
    {
        $p_flag = 0;
        $c_flag = 0;

        $str_result = '0123456789';
        $folderid = "P_".substr(str_shuffle($str_result), 0, 7);
        $productstatus = $_POST["productstatus"];

        $target_dir = mkdir("products/".$folderid."/", 0777);
        $tmpFilePath = $_FILES['productimage']['tmp_name'];

        date_default_timezone_set("Asia/Calcutta");
        $add_date = date('Y-m-d H:i:s');

        if ($tmpFilePath != "")
        {
            $target_file = "products/".$folderid."/". basename($_FILES["productimage"]["name"]);
        }
        if (move_uploaded_file($tmpFilePath, $target_file))
        {
            $query = "INSERT INTO tbl_product_master (product_status, product_image, add_date, edit_date, folder_id) VALUES ('".$productstatus."', '".$target_file."', '".$add_date."', '".$add_date."', '".$folderid."')";

            $result = mysqli_query($conn, $query);
            if($result)
            {
                $product_id = mysqli_insert_id($conn);
                echo "<h3><font color='green'>Product Save Successful..</font></h3>";
            }
            else
            {
                echo "<h3><font color='red'>Image Upload Failed..</font></h3>";
            }
        }

        foreach($language_list as $key => $val)
        {
            $language_id = $val;
            $product_name = $_POST["p_name_".$val];
            $product_details = $_POST["p_detail_".$val];
            $product_add_info = $_POST["p_info_".$val];
            
            $product_query = "INSERT INTO tbl_product_language (product_id, language_id, product_name, product_details, product_add_info) VALUES ('".$product_id."', '".$language_id."', '".$product_name."', '".$product_details."', '".$product_add_info."')";
            $product_result = mysqli_query($conn, $product_query);
        }

        foreach($currency_list as $key => $val)
        {
            $currency_id = $val;
            $currency_value = $_POST["p_value_".$val];
            
            $currency_query = "INSERT INTO tbl_product_currency (product_id, currency_id, currency_value) VALUES ('".$product_id."', '".$currency_id."', '".$currency_value."')";
            $currency_result = mysqli_query($conn, $currency_query);
        }

        if (@$product_result)
        {
            $p_flag = 1;
        }
        if (@$currency_result)
        {
            $c_flag = 1;
        }
        
        $flag = $p_flag + $c_flag;
        if($flag == 2)
        {
            echo "<h3><font color='green'>Details Save Successful..</font></h3>";
            header('Refresh:3; URL=productlist.php');
        }
        else
        {
            echo "<h3><font color='red'>Details Save Failed..</font></h3>";
        }
    }
    ?>
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