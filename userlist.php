<?php
    include_once("configfunction.php");
    $userlist = new DataBase_Conn();

    //Deletion
    if(isset($_GET['delete']))
    {
        // Geeting deletion row id
        $rowid=$_GET['delete'];
        $sql=$userlist->DeleteUser($rowid);

        if($sql)
        {
            echo '<script language="javascript">alert("Record Deleted Successfully..!!")</script>';;
            echo "<script>window.location.href='userlist.php'</script>"; 
        }
    }
?>

<!DOCTYPE html>
<html>

<title>User List</title>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Abel' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/font-abel.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style type="text/css">
        .button-foot {
            padding-left: 35px;
        }

        div.table-scroll {
            height: 500px;
            overflow: auto;
        }
    </style>
</head>

<body>

<h2>List of All Users..</h2>

<hr/>
<form action="" method="post">
    <table align="center" padding-left="20px" cellspacing="0" width="40%">
        <tr>
            <td>Search: <input type="text" name="search" class="form-control"></td>
            <td><input type="submit" name="submit" value="FIND" class="button-anchor"></td>
        </tr>
    </table>
</form>
<br>

<table align="center" cellspacing="0" cellpadding="1" width="95%" class="table-scroll">
    <tr>
        <th width="100px">User-ID</th>
        <th width="130px">Name</th>
        <th width="100px">Email-ID</th>
        <th width="180px">Address</th>
        <th width="100px">Last Login</th>
        <th width="30px">Delete</th>
    </tr>
    <?php
    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        @$search_val = $_POST["search"];
        $sql = mysqli_query($conn, "SELECT * FROM tbl_userdetail WHERE username LIKE '%$search_val%'");

        while($row=mysqli_fetch_assoc($sql))
        {
        ?>
        <tr>
            <td><?php echo $row["user_id"]; ?></td>
            <td><?php echo $row["username"]; ?></td>
            <td><?php echo $row["email_id"]; ?></td>
            <td><?php echo $row["address"]; ?></td>
            <td><?php echo date('d/m/Y - H:i:s', strtotime($row["last_login"])); ?></td>
            <td><a href="userlist.php?delete=<?php echo htmlentities($row['user_id']);?>"><button style="border-radius: 5px;" onClick="return confirm('Do you want to delete??');"><img src="css/icon/delete.png" height="10px" width="10px"></button></a></td>
        </tr>
        <?php
        }  
    }
    else
    {
        $sql = $userlist->FetchUserList();
    }
    
    if(mysqli_num_rows($sql) > 0)
    {
        while($row=mysqli_fetch_array($sql))
        {
        ?>
        <tr>
            <td><?php echo $row["user_id"]; ?></td>
            <td><?php echo $row["username"]; ?></td>
            <td><?php echo $row["email_id"]; ?></td>
            <td><?php echo $row["address"]; ?></td>
            <td><?php echo date('d/m/Y - H:i:s', strtotime($row["last_login"])); ?></td>
            <td><a href="userlist.php?delete=<?php echo htmlentities($row['user_id']);?>"><button style="border-radius: 5px;" onClick="return confirm('Do you want to delete??');"><img src="css/icon/delete.png" height="10px" width="10px"></button></a></td>
        </tr>
        <?php
        }
    }
    else
    {
    ?>
        <tr>
            <td colspan="6"><strong>-- NO USERS FOUND --</strong></td>
        </tr>
    <?php
    }
    ?>
</table>

<br>

<div class="button-foot">
    <a href="index.php">
        <button style="border-radius: 5px; background-color: black; height: 35px; width: 130px; cursor: pointer;">
            <font color="gainsboro" face="Abel"><b>GO BACK</b></font>
        </button>
    </a>
</div>

</body>

</html>