<?php require_once 'check_admin_login.php';?>
<?php

if (is_numeric($_GET['p_id'])){
    $p_id = $_GET['p_id'];
}
else{
    header('location:donation_list.php?msg=1');
}

require_once "connection.php";
//query to select data from table
$sql= "DELETE from donation where p_id=$p_id";
//execute query
$result = $connection->query($sql);
//assign blank array to store data from result
//check number of rows fetch by query
if($connection->affected_rows == 1){
    header('location:donation_list.php?msg=2');
}
else{
    header('location:donation_list.php?msg=3');
}
?> 
