<?php
    $connection = new mysqli('localhost','root','','parkzone');
    if($connection->connect_errno!=0){
        die('Database Connection Error'.$connection->connect_error);
    }
    $id=$_GET['id'];
    $sql="update book
        set ApprovalStatus='approved'
        where BookID='$id'
    ";
    $connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("Booking Approved");
            window.location.href="admin_approvebooking.php";
        </script>';
    }else{
        echo '<script>
            alert("Booking could not be approved");
            window.location.href="admin_approvebooking.php";
        </script>';
    }
?>