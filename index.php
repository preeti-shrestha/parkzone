<?php 
    error_reporting(0);
    if(isset($_COOKIE['admin_id'])){
        session_start();
        $_SESSION['admin_id']=$_COOKIE['admin_id'];
        $_SESSION['admin_username']=$_COOKIE['admin_username'];
        $_SESSION['admin_role']=$_COOKIE['admin_role'];
        header('location:dashboard.php');
    }

    if(isset($_POST['btnLogin'])){
        $err=[];
        if(isset($_POST['admin_username']) && !empty($_POST['admin_username']) && trim($_POST['admin_username']) ){
            $admin_username=$_POST['admin_username'];
        }else{
            $err['admin_username']='*Please enter username';
        }
        if(isset($_POST['admin_password']) && !empty($_POST['admin_password']) && trim($_POST['admin_password']) ){
            $admin_password=$_POST['admin_password'];
            $encrypted_password=md5($admin_password);
        }else{
            $err['admin_password']='*Please enter password';
        }

        if(count($err)==0){
            require_once 'connection.php';
            $sql="SELECT AdminID,Username,AdminRole FROM admin where Username='$admin_username' and Password='$encrypted_password' ";
            $result = $connection->query($sql);
        }

        if($result->num_rows==1){
            $row = $result->fetch_assoc();
            session_start();
            $_SESSION['admin_id']=$row['AdminID'];
            $_SESSION['admin_username']=$row['Username'];
            $_SESSION['admin_role']=$row['AdminRole'];

            if(isset($_POST['rem_me'])){
                setcookie('admin_id',$row['AdminID'],time()+2*24*60*60);
                setcookie('admin_username',$row['Username'],time()+2*24*60*60);
                setcookie('admin_role',$row['AdminRole'],time()+2*24*60*60);
            }

            header('location:dashboard.php');
        }else{
            $err_msg="Credentials do not match";
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require_once 'link.php'; ?>

    <style>
        body{
            background-color:  #f2fcff;
        }
        form{
            font-family: "Quicksand",sans-serif;
            display: grid;
            grid-template-columns: 1fr;
            place-items: center;
            height: 100vh;
        }
        fieldset{
            width: fit-content;
            background-color: #cbeeff;
            box-shadow: 1px 1px 1px 1px #8a98b3;
            border-radius: 20px;
            color: #22356a;
            padding: 20px;
            border: none;
        }
        h3{
            text-align: center;
        }
        .label-input{
            padding: 10px;
        }
        .label-input label{
            display: inline-block;
            width: 100px;
            padding: 5px;
        }
        .label-input input{
            height: 20px;
            width: 150px;
            border: none;
            border-radius:5px;
            padding: 5px;
            box-shadow:1px 1px 1px 1px #8a98b3 inset;
            font-family: "Quicksand", sans-serif;   
        }
        .checkbox{
            font-size: 12px;
            padding: 10px;
        }
        .button{
            text-align: center;
            padding: 10px;
        }
        .button button{
            padding: 5px;
            width: 50px;
            border-radius: 5px;
            background-color: #22356a;
            color: #cbeeff;
            border: none;
            box-shadow: 1px 1px 0 0 #c5cbff;
        }
        .button button:hover{
            background-color:#98deff;
            color: #22356a;
            box-shadow: 1px 1px 0 0 #332c58 ;
        }
        .err_message{
            color: red;
            font-size: 10px;
            padding-left:115px;
        }
        .err_msg{
            color: red;
            font-weight:bold;
            width: 80%;
            margin: auto;
            text-align: center;
            padding: 5px;
        }
    </style>
</head>
<body>
    <form action="" method="post" >
        <fieldset>
            <h3>Admin Login</h3>
            <?php if(isset($err_msg)) { ?>
                <p class="err_msg"><?php echo $err_msg ?></p>
            <?php } ?>
            <?php if(isset($_GET['err']) && $_GET['err']==1) { ?>
                <p class="err_msg">Please login to continue</p>
            <?php } ?>
            <div class="label-input">
                <label for="admin_username">Username</label>
                <input type="text" name="admin_username" id="admin_username" placeholder="Enter Username" value="<?php echo isset($admin_username)?$admin_username:'' ?>">
                <br>
                <?php if(isset($err['admin_username'])) { ?>
                    <span class="err_message">
                        <?php echo $err['admin_username'] ?>
                    </span>
                <?php } ?>
            </div>
            <div class="label-input">
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" placeholder="Enter Password" value="<?php echo isset($admin_password)?$admin_password:'' ?>">
                <br>
                <?php if(isset($err['admin_password'])) { ?>
                    <span class="err_message">
                        <?php echo $err['admin_password'] ?>
                    </span>
                <?php } ?>
            </div>
            <div class="checkbox">
                <input type="checkbox" name="rem_me" id="rem_me">
                <label for="rem_me">Remember Me</label>
            </div>
            <div class="button">
                <button name="btnLogin">Login</button>
            </div>
        </fieldset>
    </form>
</body>
</html>