<?php
    session_start();
    session_destroy();
    setcookie('admin_id',false,time()-2*24*60*60);
    setcookie('admin_username',false,time()-2*24*60*60);
    setcookie('admin_role',false,time()-2*24*60*60);
    header('location:index.php');
?>