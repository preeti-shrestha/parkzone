<?php
    $location_name=$_POST['location_name'];
    $location_id=$_POST['location_id'];
    $connection=new mysqli('localhost','root','','parkzone');
    $sql="SELECT * from parklocation where LocationName='$location_name' and LocationID!='$location_id'";
    $result=$connection->query($sql);
    if($result->num_rows==1){
        echo 'Location already exists';
    }else{
        echo 'Location available';
    }
?>