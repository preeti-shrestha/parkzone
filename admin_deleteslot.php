<?php
    error_reporting(0);
    $id=$_GET['id'];
    $locid=$_GET['locid'];
    $connection = new mysqli('localhost','root','','parkzone');
    if($connection->connect_errno!=0){
        die('Database Connection Error'.$connection->connect_error);
    }
    $sql="DELETE FROM slot where SlotID='$id' and LocationID='$locid'";
    $result =$connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("Deleted Successfully");
            window.location.href="admin_listparkingslots.php";
        </script>';
    }else{
        echo '<script>
            alert("Unable to Delete");
            window.location.href="admin_listparkingslots.php";
        </script>';
    }
?>