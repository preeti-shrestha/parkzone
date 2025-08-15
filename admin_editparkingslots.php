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

    $sql="SELECT LocationID,TwoWheelSlot,FourWheelSlot from parklocation where AdminID='$admin_id' ";
    $result =$connection->query($sql);
    $data=[];
    if($result-> num_rows==1){
        $row=$result->fetch_assoc();
    }else{
        $row=[];
    }
    $twowheels=$row['TwoWheelSlot'];
    $fourwheels=$row['FourWheelSlot'];
    $location_id=$row['LocationID'];

    if(isset($_POST['btnSave'])){
        $err=[];
        if(isset($_POST['twowheelslot']) && !empty($_POST['twowheelslot']) && trim($_POST['twowheelslot']) ){
            $twowheelslot=$_POST['twowheelslot'];
            if(!preg_match('/^[0-9]*$/',$twowheelslot)){
                $err['twowheelslot'] =  'Enter valid number of slots';
            }
        }else{
            $err['twowheelslot']='Please enter number of slots';
        }
        if(isset($_POST['fourwheelslot']) && !empty($_POST['fourwheelslot']) && trim($_POST['fourwheelslot']) ){
            $fourwheelslot=$_POST['fourwheelslot'];
            if(!preg_match('/^[0-9]*$/',$fourwheelslot)){
                $err['fourwheelslot'] =  'Enter valid number of slots';
            }
        }else{
            $err['fourwheelslot']='Please enter number of slots';
        }
        $totaltwowheelslot=(int)$twowheels+(int)$twowheelslot;
        $totalfourwheelslot=(int)$fourwheels+(int)$fourwheelslot;
        if(count($err)==0){
            require_once 'connection.php';
            $sqlupdate="UPDATE parklocation SET TwoWheelSlot=$totaltwowheelslot, FourWheelSlot=$totalfourwheelslot where AdminID='$admin_id' ";
            $connection->query($sqlupdate);
            if($connection->affected_rows==1){
                $success='Slots added successfully';
            }else{
                $error='Slot addition failed';
            }
            if($success){
                for($i=1;$i<=$twowheelslot;$i++){
                    require_once 'connection.php';
                    $sql_addnewtwowheelslot="INSERT INTO slot(SlotType,SlotStatus,LocationID)
                        values('twowheeler','empty','$location_id')
                    ";
                    $connection->query($sql_addnewtwowheelslot);
                }
                for($i=1;$i<=$fourwheelslot;$i++){
                    require_once 'connection.php';
                    $sql_addnewfourwheelslot="INSERT INTO slot(SlotType,SlotStatus,LocationID)
                        values('fourwheeler','empty','$location_id')";
                    $connection->query($sql_addnewfourwheelslot);
                }
                echo '<script>
                    alert("New Parking Slots Added");
                    window.location.href="admin_listparkingslots.php";
                    </script>';
            }
        }
    }
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
        .main form{
            position: relative;
            padding: 20px;
            width: auto;
            margin: auto;
            color:#171a3a;
        }
        form fieldset{
            background-color: #f2fcff;
            border-radius:10px;
            box-shadow: 1px 1px 1px 1px #8a98b3;
            border:0.5px solid #8a98b3;
        }
        fieldset legend{
            border:0.5px solid #8a98b3;
            margin-left:5px;
            padding:5px;
            border-radius:10px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 0.5px 1px #8a98b3 inset;
        }
        form .label-input{
            padding: 10px;
            font-size: 14px;
            height: 50px;
        }
        .label-input label{
            display: inline-block;
            width: 150px;
        }
        .label-input input[type=text]{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .label-input input[type=fourwheelslot]{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .error_message_box{
            color:red;
            font-size:12px;
            text-align:right;
        }
        .btn{
            text-align: center;
            padding: 10px;
        }
        .btnSave{
            background-color: green;
            padding: 10px;
            border-radius: 10px;
            color: #f2fcff;
        }
        .btnClear{
            background-color: red;
            padding: 10px;
            border-radius: 10px;
            color: #f2fcff;
        }
        .btnGo{
            background-color: #22356a;
            padding: 10px;
            border-radius: 10px;
            color: #f2fcff;
        }
        .err_msg{
            background-color: red;
            color: #f2fcff;
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
                <?php }else{?>
                    <li class="active">
                        <a href="admin_listparkingslots.php">
                            <i class="fa-solid fa-square-parking"></i>
                            <span>Parking Slots </span>
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
            <a href="admin_listparkingslots.php"><span>Slots List</span></a>
            <a href="admin_editparkingslots.php"><span class="active">Update Slots</span></a>
        </div>
        <div style="padding:auto; margin:20px auto;">
            <h4>Current No. of Two Wheeler Slots : <?php echo $twowheels ?></h4>
            <h4>Current No. of Four Wheeler Slots : <?php echo $fourwheels ?></h4>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Add More Slots</legend>
                <?php if(isset($error)) {?>
                    <p class="err_msg"><?php echo $error ?></p>
                <?php }?>
                <?php if(isset($success)) {?>
                    <p class="success_msg"><?php echo $success ?></p>
                <?php }?>
                <div class="label-input">
                    <label for="twowheelslot">Add new Two Wheel Slots</label>
                    <input type="text" name="twowheelslot" id="twowheelslot" value="<?php echo isset($twowheelslot)?$twowheelslot:'' ?>">
                    <div class="error_message_box">
                        <?php if(isset($err['twowheelslot'])) { ?>
                            <span class="err_message">
                                <?php echo $err['twowheelslot'] ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <div class="label-input">
                    <label for="fourwheelslot">Add new Four Wheel Slots</label>
                    <input type="text" name="fourwheelslot" id="fourwheelslot" value="<?php echo isset($fourwheelslot)?$fourwheelslot:'' ?>">
                    <div class="error_message_box">
                        <?php if(isset($err['fourwheelslot'])) { ?>
                            <span class="err_message">
                                <?php echo $err['fourwheelslot'] ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <div class="btn">
                    <button type="submit" name="btnSave" class="btnSave">Confirm</button>
                    <button type="clear" name="btnClear" class="btnClear">Clear</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>