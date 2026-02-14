

<?php require_once 'check_admin_login.php';?>
<?php
 if (is_numeric($_GET['id'])){
     $id = $_GET['id'];

 } else {
    header('location:adoption_list.php?msg=1');
 }
$id = $_GET['id'];
        require_once 'connection.php';
        //query to select data from table
        $sql = "SELECT * from information where id=$id";
       //execute query
        $result = $connection->query($sql);

        //check number of rows fetch by query
        if ($result-> num_rows == 1) {
            $row = $result->fetch_assoc();

        } else {
            $row = [];
        }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dogs Adoption list</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<!-- <?php require_once 'admin_menu.php';?> -->
<div class="wrapper">
<h2>Dogs Adoption list</h2>
    <div>
        <?php if (!empty($row)) {?>
            <table class="view_table">
            <tr> 
                    <td> ID</td>
                    <td><?php echo $row['id'] ?></td>
                </tr>
                <tr> 
                    <td> fname</td>
                    <td><?php echo $row['fname'] ?></td>
                </tr>
                <tr> 
                    <td>lname </td>
                    <td><?php echo $row['lname'] ?></td>
                </tr> 
                <tr> 
                    <td> email</td>
                    <td><?php echo $row['email'] ?></td>
                </tr> 
                <tr> 
                    <td> phone</td>
                    <td><?php echo $row['phone'] ?></td>
                </tr> 
                <tr> 
                    <td> address</td>
                    <td><?php echo $row['address'] ?></td>
                </tr> 
            </table>

        <?php } else { ?>
            <div class="no_record"> Invalid category information </div>
        <?php } ?>
    </div>
</body>
</html>