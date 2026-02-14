<?php require_once 'check_admin_login.php'; ?>
<?php
if (isset($_GET['dog_id']) && is_numeric($_GET['dog_id'])) {
    $dog_id = (int)$_GET['dog_id'];
} else {
    header('Location: list_dogs_categories.php?msg=1');
    exit;
}

require_once 'connection.php';

// Query to select data from the dogs table
$sql = "SELECT * FROM dogs WHERE dog_id = $dog_id";
$result = $connection->query($sql);

// Debugging output
if (!$result) {
    die("Query failed: " . $connection->error); // Show SQL errors
}

// Check number of rows fetched by the query
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    $row = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Category Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="wrapper">
    <h2>Dog Details</h2>
    <div>
        <?php if (!empty($row)) { ?>
            <table class="view_table">
                <tr>
                    <td>Id</td>
                    <td><?php echo $row['dog_id']; ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?php echo $row['name']; ?></td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td><?php echo $row['age']; ?></td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td><?php echo $row['color']; ?></td>
                </tr>
                <tr>
                    <td>Breed</td>
                    <td><?php echo $row['breed']; ?></td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td><?php echo $row['size']; ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo $row['gender']; ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                </tr>
                <tr>
                    <td>Message</td>
                    <td><?php echo $row['message']; ?></td>
                </tr>
                <tr>
                    <td>Image</td>
                    <td>
                        <?php
                        $imagePath = 'image/' . $row['image'];
                        if (file_exists($imagePath)) {
                            echo "<img src='$imagePath' height='100px' />";
                        } else {
                            echo "Image not found";
                        }
                        ?>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <div class="no_record">Invalid category information</div>
        <?php } ?>
    </div>
</div>

</body>
</html>
