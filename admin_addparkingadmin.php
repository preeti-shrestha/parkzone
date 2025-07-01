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
    if(isset($_POST['btnSave'])){
        $err=[];
        if(isset($_POST['username']) && !empty($_POST['username']) && trim($_POST['username']) ){
            $username=$_POST['username'];
            if(preg_match('/^[A-Za-z]+[0-9]*$/',$username)){
                if(strlen($username)<5){
                    $err['username'] =  'At least 5 characters';
                }
            }else{
                $err['username'] =  'Enter valid username';
            }
        }else{
            $err['username']='Please choose username';
        }
        if(isset($_POST['password']) && !empty($_POST['password']) && trim($_POST['password']) ){
            $password=$_POST['password'];
            if(strlen($password)<5){
                $err['password'] =  'At least 5 characters';
            }
            $encrypted_password=md5($password);
        }else{
            $err['password']='Please choose password';
        }
        if(count($err)==0){
            require_once 'connection.php';
            $sql="insert into admin(Username,Password,AdminRole)
                values('$username','$encrypted_password','parkingadmin')
            ";
            $connection->query($sql);
            if($connection->affected_rows==1 && $connection->insert_id>0){
                $success='Parking admin created successfully';
            }else{
                $error='Parking admin creation failed';
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
        .label-input input[type=password]{
            padding: 5px;
            border-radius: 5px;
            width: 300px;
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
            <a href="#"><span class="active">Add Admin</span></a>
            <a href="admin_addlocation.php"><span>Add Location</span></a>
            <a href="admin_maplocation.php"><span>Map</span></a>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Create Parking Admin</legend>
                <?php if(isset($error)) {?>
                    <p class="err_msg"><?php echo $error ?></p>
                <?php }?>
                <?php if(isset($success)) {?>
                    <p class="success_msg"><?php echo $success ?></p>
                <?php }?>
                <div class="label-input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo isset($username)?$username:'' ?>">
                    <span id="check_username"></span>
                    <?php if(isset($err['username'])) { ?>
                        <span class="err_message">
                            <?php echo $err['username'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="label-input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" value="<?php echo isset($password)?$password:'' ?>">
                    <?php if(isset($err['password'])) { ?>
                        <span class="err_message">
                            <?php echo $err['password'] ?>
                        </span>
                    <?php } ?>
                </div>
                <div class="btn">
                    <button type="submit" name="btnSave" class="btnSave">Confirm</button>
                    <button type="clear" name="btnClear" class="btnClear">Clear</button>
                </div>
                <?php if($success){?>
                    <div class="btn">
                        <a href="admin_addlocation.php">
                            <button type="button" class="btnGo">Add Location</button>
                        </a>
                    </div>
                <?php }?>
            </fieldset>
        </form>
    </div>
    <!-- JAVASCRIPT -->
    <script type="text/javascript" src="file/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#username').keyup(function(){
                var check_username=$(this).val();
                $.ajax({
                    url:'admin_checkusername.php',
                    data:{'user_name':check_username},
                    dataType:'text',
                    method:'post',
                    success:function(resp){
                        $('#check_username').html(resp);
                        if(resp=='Username available'){
                            $('#check_username').css({color:'green'})
                        }else{
                            $('#check_username').css({color:'red'})
                        }
                    }
                });
            });                 
        });
    </script>
</body>
</html>