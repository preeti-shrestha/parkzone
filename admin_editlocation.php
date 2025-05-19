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
    $id=$_GET['id'];
    require_once 'connection.php';
    $sql="SELECT ElectionName,ElectionDate,ElectionType from election where ElectionID='$id' ";
    $result =$connection->query($sql);
    $data=[];
    if($result-> num_rows==1){
        $row=$result->fetch_assoc();
        // print_r($row);
    }else{
        $row=[];
    }
    if(isset($_POST['update'])){
        $err=[];
        if(isset($_POST['election_name']) && !empty($_POST['election_name']) && trim($_POST['election_name']) ){
            $election_name=$_POST['election_name'];
            if(!preg_match('/^[A-Za-z\s]+$/',$election_name)){
                $err['election_name'] =  'Enter valid election name';
            }
        }else{
            $err['election_name']='Please enter election name';
        }
        if(isset($_POST['election_date']) && !empty($_POST['election_date']) && trim($_POST['election_date']) ){
            $today=time();
            $today_format=date('Y-m-d',$today);
            $election_date=$_POST['election_date'];
            if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$election_date)){
                if($election_date<=$today_format){
                    $err['election_date']='Enter valid date';
                }
            }else{
                $err['election_date']='Enter valid date';
            }
        }else{
            $err['election_date']='Please enter election date';
        }
        $index_value=0;
        if(isset($_POST['election_type']) && !empty($_POST['election_type'])){
            $election_type=$_POST['election_type'];
        }else{
            $err['election_type']='Please select election type';
        }

        if(count($err)==0){
            $sqlupdate="UPDATE election SET ElectionName='$election_name', ElectionDate='$election_date', ElectionType='$election_type' where ElectionID='$id' ";
            $connection->query($sqlupdate);
            // print_r($connection);
            if($connection->affected_rows==1){
                echo '<script>
                    alert("Record Updated");
                    window.location.href="admin_listelection.php";
                </script>';
            }else{
                echo '<script>
                    alert("Unable to Update");
                    window.location.href="admin_listelection.php";
                </script>';
            }
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
        .main form{
            position: relative;
            padding: 20px;
            width: 750px;
            margin: auto;
        }
        form fieldset{
            border: 1px solid #332c58;
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
        }
        .label-input input[type=date]{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
        }
        .label-input select{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
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
                        <a href="#">
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
            <a href="admin_listlocations.php"><span>Back to List</span></a>
        </div>
        <!-- <form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $id ?>" method="post">
            <fieldset>
                <legend>Edit Location</legend>
                <?php if(isset($error)) {?>
                    <p class="err_msg"><?php echo $error ?></p>
                <?php }?>
                <?php if(isset($success)) {?>
                    <p class="success_msg"><?php echo $success ?></p>
                <?php }?>
                <div class="label-input">
                    <label for="election_name">Election Name</label>
                    <input type="text" name="election_name" id="election_name" value="<?php echo isset($row['ElectionName'])?$row['ElectionName']:'' ?>">
                    <?php if(isset($err['election_name'])) { ?>
                        <span class="err_message">
                            <?php echo $err['election_name'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="label-input">
                    <label for="election_date">Election Date</label>
                    <input type="text" name="election_date" id="election_date" placeholder="YYYY-MM-DD" value="<?php echo isset($row['ElectionDate'])?$row['ElectionDate']:'' ?>">
                    <?php if(isset($err['election_date'])) { ?>
                        <span class="err_message">
                            <?php echo $err['election_date'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="label-input">
                    <label for="election_type">Election Type</label>
                    <select name="election_type" id="election_type" value="<?php echo isset($election_type)?$election_type:'' ?>">
                        <option value="0" <?php echo ($row['ElectionType']== '0')?'selected':'' ?> >Select one</option>
                        <option value="National" <?php echo ($row['ElectionType']== 'National')?'selected':'' ?> >National</option>
                        <option value="Regional" <?php echo ($row['ElectionType']== 'Regional')?'selected':'regional' ?> >Regional</option>
                        <option value="Local" <?php echo ($row['ElectionType']== 'Local')?'selected':'' ?> >Local</option>
                    </select>  
                    <?php if(isset($err['election_type'])) { ?>
                        <span class="err_message">
                            <?php echo $err['election_type'] ?>
                        </span>
                    <?php } ?>   
                </div>
                <div class="btn">
                    <button type="submit" name="update" value="update" class="btnSave">Update</button>
                    <button type="clear" name="btnClear" class="btnClear">Clear</button>
                </div>
            </fieldset>
        </form> -->
    </div>
</body>
</html>