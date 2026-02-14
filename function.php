<?php 
function getNameByAdminId($admin_id){
require_once 'connection.php';
//query to select data from table
$sql= "SELECT name from admins where id=$admin_id";
//execute query
$result = $connection->query($sql);
//assign blank array to store data from result
$row = $result->fetch_assoc();
return $row['name'];

}
?>