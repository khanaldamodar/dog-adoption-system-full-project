<?php require_once 'check_admin_login.php';?>
<?php 
require_once 'connection.php';

// Query to select data from table
$sql = "SELECT * FROM dogs";
$result = $connection->query($sql);

$availableDogs = [];
$adoptedDogs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 1) {
            $availableDogs[] = $row;  // Available for Adoption
        } else {
            $adoptedDogs[] = $row;  // Adopted Dogs
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Adoption - Available & Adopted Dogs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f9;
            padding: 20px;
        }

        .wrapper {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        .page-title {
            font-size: 26px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 20px;
            padding-bottom: 10px;
            font-size: 20px;
            border-bottom: 2px solid #ccc;
            text-transform: uppercase;
        }

        .list_table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .list_table th, .list_table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .list_table th {
            background: #2c3e50;
            color: #fff;
        }

        .list_table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .list_table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .action_column {
            text-align: center;
        }

        .action_column a {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            margin: 0 3px;
            font-size: 14px;
            color: white;
        }

        .edit { background-color: #1976D2; }
        .edit:hover { background-color: #125899; }
        
        .delete {
            background-color: #e74c3c;
        }

        .delete:hover {
            background-color: #c0392b;
        }

        .view {
            background-color: #27ae60;
        }

        .view:hover {
            background-color: #219150;
        }

        .dog-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .no_record {
            text-align: center;
            font-style: italic;
            color: #666;
            padding: 20px;
            font-size: 16px;
        }

        .action_column a {
            padding: 6px 10px;
            display: inline-block;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1 class="page-title">Dog Management System</h1>
        
        <!-- Available Dogs Table -->
        <h2 style="color: #27ae60;">Available for Adoption</h2>
        <table class="list_table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Size</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($availableDogs) > 0) { 
                    foreach ($availableDogs as $key => $dog) { ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><img class="dog-image" src="image/<?php echo htmlspecialchars($dog['image']); ?>" alt="Dog Image"></td>
                            <td><?php echo htmlspecialchars($dog['name']); ?></td>
                            <td><?php echo htmlspecialchars($dog['breed']); ?></td>
                            <td><?php echo $dog['age']; ?></td>
                            <td><?php echo $dog['size']; ?></td>
                            <td class="action_column">
                                <a href="view_dog.php?id=<?php echo $dog['id']; ?>" class="view" title="View"><i class="fas fa-eye"></i></a>
                                <a href="edit_dogs_categories.php?id=<?php echo $dog['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="delete_dogs_categories.php?id=<?php echo $dog['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?')" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } 
                } else { ?>
                    <tr><td colspan="7" class="no_record">No dogs available for adoption</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Adopted Dogs Table -->
        <h2 style="color: #c0392b;">Adopted Dogs</h2>
        <table class="list_table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Size</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($adoptedDogs) > 0) { 
                    foreach ($adoptedDogs as $key => $dog) { ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><img class="dog-image" src="image/<?php echo htmlspecialchars($dog['image']); ?>" alt="Dog Image"></td>
                            <td><?php echo htmlspecialchars($dog['name']); ?></td>
                            <td><?php echo htmlspecialchars($dog['breed']); ?></td>
                            <td><?php echo $dog['age']; ?></td>
                            <td><?php echo $dog['size']; ?></td>
                            <td class="action_column">
                                <a href="view_dog.php?id=<?php echo $dog['id']; ?>" class="view" title="View"><i class="fas fa-eye"></i></a>
                                <a href="edit_dogs_categories.php?id=<?php echo $dog['id']; ?>" class="edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="delete_dogs_categories.php?id=<?php echo $dog['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?')" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } 
                } else { ?>
                    <tr><td colspan="7" class="no_record">No adopted dogs found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
