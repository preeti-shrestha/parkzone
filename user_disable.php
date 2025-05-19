<?php
    error_reporting(0);
    $connection = new mysqli('localhost','root','','parkzone');
    if($connection->connect_errno!=0){
        die('Database Connection Error'.$connection->connect_error);
    }
    $user_id=$_GET['id'];
    $sql="update user
        set Status='inactive'
        where UserID='$user_id'
    ";
    $connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("User disabled");
            window.location.href="admin_listusers.php";
        </script>';
    }else{
        echo '<script>
            alert("User could not be disabled");
            window.location.href="admin_listusers.php";
        </script>';
    }
?>