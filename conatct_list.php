<?php require_once 'check_admin_login.php';?>
<?php 
error_reporting(E_ERROR);
try{
    $connection = mysqli_connect('localhost','root','','dog-adoption-project');
    $sql = "SELECT * FROM contact";
    $res = mysqli_query($connection,$sql);
    $data = [];
    if ($res->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            array_push($data, $r);
        }
    }
}catch(Exception $e){
    die('Connection Error: ' . $e->getMessage());
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Contacts</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
       * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .table-wrapper {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ecf0f1;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .action-buttons a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
            transition: color 0.3s;
        }

        .action-buttons a:hover {
            color: #c0392b;
        }

        .no-contacts {
            text-align: center;
            padding: 30px;
            font-size: 18px;
            color: #7f8c8d;
        }

        .no-contacts i {
            font-size: 40px;
            color: #e74c3c;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center; color: #2c3e50;">List of Contacts</h1>
        <div class="table-wrapper">
            <?php if (count($data) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $key => $record): ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo htmlspecialchars($record['phone']); ?></td>
                                <td><?php echo htmlspecialchars($record['address']); ?></td>
                                <td><?php echo htmlspecialchars($record['subject']); ?></td>
                                <td><?php echo htmlspecialchars($record['message']); ?></td>
                                <td class="action-buttons">
                                    <a href="delete-contact.php?id=<?php echo $record['id']; ?>" onclick="return confirm('Are you sure you want to delete this contact?');">
                                        <i class="fas fa-trash" title="Delete Contact"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-contacts">
                    <i class="fas fa-user-slash"></i>
                    <p>No contacts found in the database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
