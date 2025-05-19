<?php
    session_start();
    if(!isset($_SESSION['admin_id'])){
        header('location:index.php?err=1');
    }
?>
<?php
    error_reporting(0);
    $admin_id=$_SESSION['admin_id'];
    $admin_role=$_SESSION['admin_role'];
    require_once 'connection.php';
    $sql_adminrole="SELECT AdminRole from admin where AdminID='$admin_id' ";
    $adminrole =$connection->query($sql_adminrole);
    $dataadmin=[];
    $dataadmin=$adminrole->fetch_assoc();
    $roleadmin=$dataadmin['AdminRole'];
    $sql1="SELECT COUNT(DISTINCT UserID) as users from user where Status='active'";
    $result1 =$connection->query($sql1);
    $row1=[];
    if($result1->num_rows==0){
        $users= 'NULL';
    }else{
        $row1=$result1->fetch_assoc();
        $users= $row1['users'];
    }
    $sql2="SELECT COUNT(DISTINCT LocationID) as locations from parklocation";
    $result2 =$connection->query($sql2);
    $row2=[];
    if($result2->num_rows==0){
        $locations= 'NULL';
    }else{
        $row2=$result2->fetch_assoc();
        $locations= $row2['locations'];
    }
    $sql3="SELECT SUM(TwoWheelSlot)+SUM(FourWheelSlot) as totalslot from parklocation";
    $result3 =$connection->query($sql3);
    $row3=[];
    if($result3->num_rows==0){
        $totalslot= 'NULL';
    }else{
        $row3=$result3->fetch_assoc();
        $totalslot= $row3['totalslot'];
    }
    $sql4="SELECT COUNT(DISTINCT SlotID) as occupiedslot from slot where SlotStatus='occupied'";
    $result4 =$connection->query($sql4);
    $row4=[];
    if($result4->num_rows==0){
        $occupiedslot= 'NULL';
    }else{
        $row4=$result4->fetch_assoc();
        $occupiedslot= $row4['occupiedslot'];
    }
    $sql5="SELECT TwoWheelSlot from parklocation where AdminID='$admin_id'";
    $result5 =$connection->query($sql5);
    $row5=[];
    if($result5->num_rows==0){
        $twowheelslot= 'NULL';
    }else{
        $row5=$result5->fetch_assoc();
        $twowheelslot= $row5['TwoWheelSlot'];
    }
    $sql6="SELECT FourWheelSlot from parklocation where AdminID='$admin_id'";
    $result6 =$connection->query($sql6);
    $row6=[];
    if($result6->num_rows==0){
        $fourwheelslot= 'NULL';
    }else{
        $row6=$result6->fetch_assoc();
        $fourwheelslot= $row6['FourWheelSlot'];
    }
    $sql7="SELECT Count(slot.SlotID) as totalslots from slot inner join parklocation where parklocation.AdminID='$admin_id' and slot.SlotType='twowheeler' AND slot.SlotStatus='occupied' ";
    $result7 =$connection->query($sql7);
    $row7=[];
    if($result7->num_rows==0){
        $bookedtwowheelslot= 'NULL';
    }else{
        $row7=$result7->fetch_assoc();
        $bookedtwowheelslot= $row7['totalslots'];
    }
    $sql8="SELECT Count(slot.SlotID) as totalslots from slot inner join parklocation where parklocation.AdminID='$admin_id' and slot.SlotType='fourwheeler' AND slot.SlotStatus='occupied' ";
    $result8 =$connection->query($sql8);
    $row8=[];
    if($result8->num_rows==0){
        $bookedfourwheelslot= 'NULL';
    }else{
        $row8=$result8->fetch_assoc();
        $bookedfourwheelslot= $row8['totalslots'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php require_once 'link.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *{
            margin: 0;
            padding: 0;
            border:none;
            outline: none;
            box-sizing: border-box;
        }
        body{
            background-color: #d4e9ff;
            font-family:"Quicksand",sans-serif;
        }
        .header{          
            background-color:#f2fcff;
            height: 12vh;
            width: 100%;
        }
        .logo{
            display: flex;
            padding-left: 35px;
        }
        .logo i {
           font-size: 30px;
           padding:  25px 5px ;   
        }
        .profile{
            padding-left: 1050px;
        }
        .sidebar{
            position:sticky ;
            width: 250px;
            height: 88vh;
            color:#cbeeff;
            overflow:hidden;
            padding-left: 30px;
            transition: all 0.5s linear;
            background:#22356a;            
        }
        .menu{
            height: 88%;
            position: relative;
            list-style: none;
            padding: 0;
        }
        .menu li{
            padding: 1rem;
            margin: 8px 5px;
            border-radius: 8px;
            transition: all 0.5s ease-in-out;
        }
        .menu li:hover,.menu .active{
            background:#7B8FB5;
            color:#171a3a;
        }
        .menu a{
            color:#cbeeff;
            font-size: 18px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap:1.5rem;
        }
        .menu a span{
            overflow:hidden;
        }
        .menu a i{
            font-size: 1.2rem;
        }
        .main{
            position: absolute;
            top:100px;
            left: 300px;
            color:#22356a;
        }
        .counts{
            padding: 35px 10px;
            display: grid;
            grid-template-columns: repeat(2, 400px);
            grid-gap:60px;
        }
        .counts .count{
            padding: 20px;
            border-radius: 10px;
        }
        .count h1,h3{
            text-align: center;
        }
        .count h1{
            font-size: 60px;
        }
        .count a,p{
            text-align: right;
            font-size: 12px;
        }
    </style>

</head>
<body>
    <div class="header">
        <div class="logo"> 
            <i class='bx bx-slider'></i>
            <img src="images/fulllogo.png" alt="logo" height="75px"></a>
        </div>
    </div>
    
    <div class="sidebar">
            <div class="menu">
                <li class="active">
                    <a href="#">
                        <i class='bx bxs-dashboard'></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <?php if($roleadmin=='headadmin'){?>
                    <li>
                        <a href="admin_listlocations.php">
                            <i class="fa-solid fa-location-dot"></i>
                            <span> Locations </span>
                        </a>
                    </li>
                    <li>
                        <a href="admin_listusers.php">
                            <i class="fa-solid fa-user"></i>
                            <span> Users </span>
                        </a>
                    </li>
                <?php }else{ ?>
                    <li>
                        <a href="admin_listparkingslots.php">
                            <i class="fa-solid fa-square-parking"></i>
                            <span> Parking Slots </span>
                        </a>
                    </li>   
                    <li>
                        <a href="admin_listbooking.php">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span> Booking </span>
                        </a>
                    </li>   
                <?php } ?>   
                <li class="logout">
                    <a href="admin_logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span> Logout </span>
                    </a>
                </li>
        </div>
    </div>
    <div class="main">
        <h1>Dashboard</h1>
        <?php if($roleadmin=='headadmin'){?>
            <div class="counts">
                <div class="count" style="background-color:#79CDFC">
                    <h3> No of Users</h3>
                    <h1><?php echo $users ?></h1>
                    <a href="admin_listusers.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
                <div class="count" style="background-color:#8EF592;" >
                    <h3> No of Parking Locations</h3>
                    <h1><?php echo $locations ?></h1>
                    <a href="admin_listlocations.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
                <div class="count" style="background-color:#E8BF8F;">
                    <h3> No of Parking Slots</h3>
                    <h1><?php echo $totalslot ?></h1>
                </div>
                <div class="count" style="background-color:#F37C7C;" >
                    <h3>No. of Occupied Slots</h3>
                    <h1><?php echo $occupiedslot ?></h1>
                </div>
            </div>
        <?php }else{ ?>
            <div class="counts">
                <div class="count" style="background-color:#79CDFC">
                    <h3>Total Two Wheel Slots</h3>
                    <h1><?php echo $twowheelslot ?></h1>
                    <a href="admin_listparkingslots.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
                <div class="count" style="background-color:#8EF592;" >
                    <h3> No of Four Wheel Slots</h3>
                    <h1><?php echo $fourwheelslot ?></h1>
                    <a href="admin_listparkingslots.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
                <div class="count" style="background-color:#E8BF8F;">
                    <h3>Occupied Two Wheel Slots</h3>
                    <h1><?php echo $bookedtwowheelslot ?></h1>
                    <a href="admin_listbooking.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
                <div class="count" style="background-color:#F37C7C;" >
                    <h3>Occupied Two Wheel Slots</h3>
                    <h1><?php echo $bookedfourwheelslot ?></h1>
                    <a href="admin_listbooking.php"><p>More info <i class='bx bx-right-arrow-circle'></i></p></a>
                </div>
            </div>
        <?php }?>
    </div>
    

</body>
</html>