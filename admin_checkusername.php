<?php
    $user_name=$_POST['user_name'];
    $connection=new mysqli('localhost','root','','parkzone');
    $sql="SELECT * from admin where Username='$user_name' and AdminRole='parkingadmin' ";
    $result=$connection->query($sql);
    if($result->num_rows==1){
        echo 'Username already exists';
    }else{
        echo 'Username available';
    }
?>