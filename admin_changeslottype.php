<?php
    $connection = new mysqli('localhost','root','','parkzone');
    if($connection->connect_errno!=0){
        die('Database Connection Error'.$connection->connect_error);
    }
    $id=$_GET['id'];
    $locid=$_GET['locid'];
    $sql_slottype="SELECT SlotType from slot where SlotID='$id' and LocationID='$locid' ";
    $slottype =$connection->query($sql_slottype);
    $data_slottype=[];
    $data_slottype=$slottype->fetch_assoc();
    $slot_type=$data_slottype['slottype'];
    if($slot_type=='twowheeler'){
        $sql="update slot
            set SlotType='fourwheeler'
            where SlotID='$id' and LocationID='$locid'
        ";
    }else{
        $sql="update slot
            set SlotType='twowheeler'
            where SlotID='$id'and LocationID='$locid'
        ";
    }
    $connection->query($sql);
    $data=[];
    if($connection-> affected_rows==1){
        echo '<script>
            alert("Slot Type Changed");
            window.location.href="admin_listparkingslots.php";
        </script>';
    }else{
        echo '<script>
            alert("Slot type could not be changed");
            window.location.href="admin_listparkingslots.php";
        </script>';
    }
?>