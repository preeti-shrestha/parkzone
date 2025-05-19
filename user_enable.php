<?php
    $connection = new mysqli('localhost','root','','parkzone');
    if($connection->connect_errno!=0){
        die('Database Connection Error'.$connection->connect_error);
    }
    $user_id=$_GET['id'];
    $sql="update user
        set Status='active'
        where UserID='$user_id'
    ";
    $connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("User enabled");
            window.location.href="admin_listusers.php";
        </script>';
    }else{
        echo '<script>
            alert("User could not be enabled");
            window.location.href="admin_listusers.php";
        </script>';
    }
?>