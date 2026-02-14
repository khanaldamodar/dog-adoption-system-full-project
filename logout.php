<?php
session_start();
session_destroy();
setcookie('admin_id',false,time()-7*24*60*60);
setcookie('admin_email',false,time()-7*24*60*60);
setcookie('admin_name',false,time()-7*24*60*60);
header('location:admin_login.php');
?>