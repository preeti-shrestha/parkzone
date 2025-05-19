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
    $sql_parkadminid="SELECT admin.AdminID, admin.Username FROM admin
                        WHERE admin.AdminRole = 'parkingadmin'
                        AND admin.AdminID NOT IN (
                            SELECT DISTINCT parklocation.AdminID FROM parklocation
                        );";
    $parkadmin_id=$connection->query($sql_parkadminid);
    $data1=[];
    if($parkadmin_id-> num_rows>0){
        while($row1=$parkadmin_id->fetch_assoc()){
            $username=$row1['Username'];
            array_push($data1,$row1);
        }
    }
    if(isset($_POST['btnSave'])){
        $err=[];
        if(isset($_POST['location_name']) && !empty($_POST['location_name']) && trim($_POST['location_name']) ){
            $location_name=$_POST['location_name'];
            if(!preg_match('/^[A-Za-z\s]+$/',$location_name)){
                $err['location_name'] =  'Enter valid location name';
            }
        }else{
            $err['location_name']='Please enter location name';
        }
        if(isset($_POST['parking_admin_id']) && !empty($_POST['parking_admin_id']) && $_POST['parking_admin_id']!=0){
            $parking_admin_id=$_POST['parking_admin_id'];
        }else{
            $err['parking_admin_id']='Please select parking admin';
        }
        if(count($err)==0){
            require_once 'connection.php';
            $sql="insert into parklocation(LocationName,LocationLat,LocationLong,AdminID,TwoWheelSlot,FourWheelSlot)
                values('$location_name','$location_lat','$location_long','$parking_admin_id','0','0')
            ";
            $connection->query($sql);
            if($connection->affected_rows==1 && $connection->insert_id>0){
                $success='New location added successfully';
            }else{
                $error='Location addition failed';
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
            width: 750px;
            margin: auto;
            height:auto;
        }
        form fieldset{
            height:auto;
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
            height: auto;
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
        .label-input select{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .label-input iframe{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
            height:300px;
            background-color: #d4e9ff;
            box-shadow: 1px 1px 1px 1px #8a98b3 inset;
        }
        .btn{
            text-align: center;
            padding: 10px;
        }
        .btnSave{
            background-color: green;
            padding: 10px;
            border-radius: 10px;
            color: #e2e5ff;
        }
        .btnClear{
            background-color: red;
            padding: 10px;
            border-radius: 10px;
            color: #e2e5ff;
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
            <a href="#"><span class="active">Add Location</span></a>
            <a href="admin_maplocation.php"><span>Map</span></a>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Add New Election</legend>
                <?php if(isset($error)) {?>
                    <p class="err_msg"><?php echo $error ?></p>
                <?php }?>
                <?php if(isset($success)) {?>
                    <p class="success_msg"><?php echo $success ?></p>
                <?php }?>
                <div class="label-input">
                    <label for="location_name">Location Name</label>
                    <input type="text" name="location_name" id="location_name" value="<?php echo isset($location_name)?$location_name:'' ?>">
                    <span id="check_location"></span>
                    <?php if(isset($err['location_name'])) { ?>
                        <span class="err_message">
                            <?php echo $err['location_name'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="label-input">
                    <label for="parking_admin_id">Assign Parking Admin</label>
                    <select name="parking_admin_id" id="parking_admin_id" value="<?php echo isset($parking_admin_id)?$parking_admin_id:'' ?>">
                        <option value="0">Select one</option>
                        <?php foreach($data1 as $key=>$value){?>
                            <option value="<?php echo $value['AdminID'] ?>"><?php echo $value['Username'] ?></option>
                        <?php }?>
                    </select>   
                    <?php if(isset($err['parking_admin_id'])) { ?>
                        <span class="err_message">
                            <?php echo $err['parking_admin_id'] ?>
                        </span>
                    <?php } ?> 
                </div>
                <div class="label-input label-map">
                    <label for="location_name">Location in Map</label>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d53719.93686121613!2d85.28493302080203!3d27.708954252207754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb198a307baabf%3A0xb5137c1bf18db1ea!2sKathmandu%2044600!5e1!3m2!1sen!2snp!4v1747483222076!5m2!1sen!2snp" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <span id="check_location"></span>
                    <?php if(isset($err['location_name'])) { ?>
                        <span class="err_message">
                            <?php echo $err['location_name'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="btn">
                    <button type="submit" name="btnSave" class="btnSave">Confirm</button>
                    <button type="clear" name="btnClear" class="btnClear">Clear</button>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- JAVASCRIPT -->
    <script type="text/javascript" src="file/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#location_name').keyup(function(){
                var check_location=$(this).val();
                $.ajax({
                    url:'election_check.php',
                    data:{'location_name':check_location},
                    dataType:'text',
                    method:'post',
                    success:function(resp){
                        $('#check_location').html(resp);
                        if(resp=='Location available'){
                            $('#check_location').css({color:'green'})
                        }else{
                            $('#check_location').css({color:'red'})
                        }
                    }
                });
            });                 
        });
    </script>
</body>
</html>