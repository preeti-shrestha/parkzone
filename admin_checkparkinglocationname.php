<?php
    $location_name=$_POST['location_name'];
    $connection=new mysqli('localhost','root','','parkzone');
    $sql="SELECT * from parklocation where LocationName='$location_name'";
    $result=$connection->query($sql);
    if($result->num_rows==1){
        echo 'Location already exists';
    }else{
        echo 'Location available';
    }
?>