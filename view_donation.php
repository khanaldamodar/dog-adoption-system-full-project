

<?php require_once 'check_admin_login.php';?>
<?php
 if (is_numeric($_GET['p_id'])){
     $p_id = $_GET['p_id'];

 } else {
    header('location:donation_list.php?msg=1');
 }
$p_id = $_GET['p_id'];
        require_once 'connection.php';
        //query to select data from table
        $sql = "SELECT * from donation where p_id=$p_id";
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
        <title>Dogs donation list</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="wrapper">
<h2>Dogs donation list</h2>
    <div>
        <?php if (!empty($row)) {?>
            <table class="view_table">
            <tr> 
                    <td> ID</td>
                    <td><?php echo $row['p_id'] ?></td>
                </tr>
                <tr> 
                    <td> firstname</td>
                    <td><?php echo $row['firstname'] ?></td>
                </tr>
                <tr> 
                    <td>lastname </td>
                    <td><?php echo $row['lastname'] ?></td>
                </tr> 
                <tr> 
                    <td> email</td>
                    <td><?php echo $row['email'] ?></td>
                </tr> 
                <tr> 
                    <td> number</td>
                    <td><?php echo $row['number'] ?></td>
                </tr> 
                <tr> 
                    <td> address</td>
                    <td><?php echo $row['address'] ?></td>
                </tr> 
                <tr> 
                    <td> image</td>
                    <td><?php echo $row['image'] ?></td>
                </tr> 
                <th>description</th>
                <td><?php echo $row['description'] ?></td>
            </tr>
            </tr> 
                <th>donation_type</th>
                <td><?php echo $row['donation_type'] ?></td>
            </tr>
          

            </table>

        <?php } else { ?>
            <div class="no_record"> Invalid category information </div>
        <?php } ?>
    </div>
</body>
</html>