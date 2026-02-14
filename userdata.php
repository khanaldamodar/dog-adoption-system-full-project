<?php
require_once 'check_admin_login.php'; 

// Database connection
$host = "localhost";
$dbname = "dog-adoption-project";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user data
$query = "SELECT id, full_name, email, profile_image FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="a_m.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fa;
            color: #2c3e50;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .h1list {
            font-size: 2.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #7f8c8d;
        }

        .table-container {
            overflow-x: auto;
        }

        .tablestyle {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .tablestyle th {
            background: #3498db;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
            text-transform: uppercase;
        }

        .tablestyle td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 0.9rem;
            text-align: center;
        }

        .tablestyle tr:hover {
            background-color: #f8f9fa;
        }

        .status {
            font-weight: 500;
        }

        .status.active {
            color: #2e7d32;
            background: #e8f5e9;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .action-buttons i {
            font-size: 18px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .delete-btn i {
            color: #e74c3c;
        }

        .delete-btn i:hover {
            color: #c0392b;
        }

        table td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .no-users {
            text-align: center;
            padding: 30px;
            font-size: 18px;
            color: #555;
        }

        .no-users i {
            font-size: 40px;
            color: #ff6f61;
            margin-bottom: 10px;
        }
        .logo {
    display: flex;
    align-items: center;
    color: #f39c12;
}

.logo .dog-icon {
    font-size: 36px;
    margin-right: 8px;
}

.logo .main-text {
    font-size: 24px;
    font-weight: bold;
    letter-spacing: 1px;
}

.logo .sub-text {
    font-size: 12px;
    margin-left: 5px;
    color:rgb(40, 43, 43);
    font-style: italic;
}
    </style>
</head>
<body>
<div class="sidebar">
    <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Admin Dashboard</a></h2>
    <ul>
        <li><a href="add_dogs.php"><i class="fas fa-plus"></i> Add Dogs</a></li>
        <li><a href="list_dog.php"><i class="fas fa-list"></i> List Dogs</a></li>
        <li><a href="donate.php"><i class="fas fa-donate"></i> Donations</a></li>
        <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="adoption.php"><i class="fas fa-home"></i> Adoption</a></li>
        <li><a href="userdata.php"><i class="fas fa-user-plus"></i> Users</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="top-nav">
        <div class="logo">
            <i class="fas fa-dog dog-icon"></i>
            <div>
                <span class="main-text">ADO~PUPS</span>
                <br>
                <span class="sub-text">Adopt Happiness</span>
            </div>
        </div>
         
        <button class="logout-btn" onclick="window.location.href='logout.php';">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    
</div>

<div class="container">
    <div class="header">
        <h1 class="h1list">User Management</h1>
        <p class="subtitle">Manage registered users</p>
    </div>

    <div class="table-container">
        <?php if (count($users) > 0): ?>
            <table class="tablestyle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $user): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td>
                                <img src="<?php echo $user['profile_image'] ? $user['profile_image'] : 'default-profile.jpg'; ?>" alt="Profile Image">
                            </td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="status active">Active</span>
                            </td>
                            <td class="action-buttons">
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="delete-btn"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    <i class="fas fa-trash" title="Delete User"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-users">
                <i class="fas fa-user-slash"></i>
                <p>No users found in the database.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
