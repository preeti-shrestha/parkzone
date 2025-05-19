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
    /*
    require_once 'connection.php';
    $sql="SELECT VoterID,FirstName,MiddleName,LastName,DateOfBirth,Gender,Address,Email from voter where VoterStatus='active'";
    $result =$connection->query($sql);
    
    $data=[];
    if($result-> num_rows>0){
        while($row=$result->fetch_assoc()){
            array_push($data,$row);
        }
    }
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
        .main .mini-nav a{
            color:#22356a;
            text-decoration: none;
        }
        .main .mini-nav span{
            padding: 10px;
            border-radius: 10px;
            background-color:#f2fcff;
            box-shadow:0.5px 0.5px 0.5px 0.5px  #22356a inset;
        }
        .main .mini-nav span:hover,.main .mini-nav .active{
            text-decoration: none;
            padding: 10px;
            border-radius: 10px;
            color:#f2fcff;
            background-color:#22356a;
            box-shadow:1px 1px 1px 1px  #22356a ;
        }
        .location-list{
            padding: 20px;
        }
        .location-list table{
            width: 1000px;
        }
        .location-list table,tr,th,td{
            border-collapse: collapse;
            border: 1px solid #22356a;
            padding: 5px;
            font-size: 14px;
        }
        .location-list thead{
            background-color: #22356a;
            color: #f2fcff;
        }
        .location-list tbody{
            background-color:#f2fcff;
            color: #22243e;
        }
        .action{
            text-align: center;
        }
        .action .view{
            text-decoration:none;
            color: #e2e5ff;
            background-color: #22356a;
            padding: 4px;
            border-radius: 5px;
        }
        .action .edit{
            text-decoration:none;
            color: #e2e5ff;
            background-color: #346791;
            padding: 4px;
            border-radius: 5px;
        }
        .action .delete{
            text-decoration:none;
            color: #e2e5ff;
            background-color: #6a4822;
            padding: 4px;
            border-radius: 5px;
        }
        .err_msg{
            background-color: red;
            color: #e2e5ff;
            width: 500px;
            margin: auto;
            text-align: center;
            padding: 10px;
        }
        .success_msg{
            background-color: green;
            color: #e2e5ff;
            width: 500px;
            margin: auto;
            text-align: center;
            padding: 10px;
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
                <li>
                    <a href="dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <?php if($roleadmin=='headadmin'){?>
                    <li class="active">
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
                <?php }else{?> 
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
                <?php }?>                      
                <li class="logout">
                    <a href="admin_logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span> Logout </span>
                    </a>
                </li>
        </div>
    </div>
    <div class="main">
        <div class="mini-nav">
            <a href="admin_listlocations.php"><span>List</span></a>
            <a href="admin_addparkingadmin.php"><span>Add Admin</span></a>
            <a href="admin_addlocation.php"><span class="active">Add Location</span></a>
            <a href="admin_maplocation.php"><span class="active">Map</span></a>
        </div>
    </div>
</body>
</html>