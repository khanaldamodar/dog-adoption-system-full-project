
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php?err=1');
}
?>