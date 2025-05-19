<?php
    error_reporting(0);
    $id=$_GET['id'];
    require_once 'connection.php';
    $sql="DELETE FROM parklocation where LocationID='$id'";
    $result =$connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("Deleted Successfully");
            window.location.href="admin_listlocations.php";
        </script>';
    }else{
        echo '<script>
            alert("Unable to Delete");
            window.location.href="admin_listlocations.php";
        </script>';
    }
?>