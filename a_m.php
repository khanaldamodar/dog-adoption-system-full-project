<?php
// Include database connection
require_once 'connection.php';

// Fetch total number of dogs with status 1 (available)
$sql_dogs = "SELECT COUNT(*) AS total_dogs FROM dogs WHERE status = 1";
$result_dogs = $connection->query($sql_dogs);
$total_dogs = 0;
if ($result_dogs->num_rows > 0) {
    $row_dogs = $result_dogs->fetch_assoc();
    $total_dogs = $row_dogs['total_dogs'];
}

// Fetch total number of adopted dogs (status 0)
$sql_adopted = "SELECT COUNT(*) AS total_adopted FROM dogs WHERE status = 0";
$result_adopted = $connection->query($sql_adopted);
$total_adopted = 0;
if ($result_adopted->num_rows > 0) {
    $row_adopted = $result_adopted->fetch_assoc();
    $total_adopted = $row_adopted['total_adopted'];
}

// Fetch total donations amount
$sql_donations = "SELECT SUM(amount) AS total_donations FROM donations";
$result_donations = $connection->query($sql_donations);
$total_donations = 0;
if ($result_donations->num_rows > 0) {
    $row_donations = $result_donations->fetch_assoc();
    $total_donations = $row_donations['total_donations'];
}

// Fetch total number of users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $connection->query($sql_users);
$total_users = 0;
if ($result_users->num_rows > 0) {
    $row_users = $result_users->fetch_assoc();
    $total_users = $row_users['total_users'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background: #f0f2f5;
        }
        
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: #ecf0f1;
            padding: 25px 0;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 24px;
            letter-spacing: 1px;
            color: #fff;
            text-transform: uppercase;
        }
        
        .sidebar ul {
            list-style-type: none;
        }
        
        .sidebar ul li {
            padding: 15px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar ul li:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        
        .sidebar ul li a i {
            margin-right: 15px;
            font-size: 20px;
            width: 25px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            background-color: #f8f9fa;
        }
        
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            padding: 15px 30px;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .logo img {
            height: 45px;
            transition: transform 0.3s ease;
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }
        
        .logout-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231,76,60,0.3);
        }
        
        .dashboard-content {
            padding: 30px;
        }
        
        .dashboard-content h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: 600;
            text-align: center;
        }
        
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        
        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            border-top: 4px solid #3498db;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        
        .card i {
            font-size: 54px;
            margin-bottom: 15px;
            color: #3498db;
            background: rgba(52,152,219,0.1);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
        }
        
        .card h3 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .card p {
            color: #3498db;
            font-size: 24px;
            font-weight: 600;
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    
    <div class="dashboard-content">
        <h1>Welcome to the Admin Dashboard</h1>
        <div class="card-container">
            <div class="card">
                <i class="fas fa-dog"></i>
                <h3>Total Dogs</h3>
                <p><?php echo $total_dogs; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-home"></i>
                <h3>Adoptions</h3>
                <p><?php echo $total_adopted; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-dollar-sign"></i>
                <h3>Donations</h3>
                <p>Rs. <?php echo $total_donations; ?></p>
            </div>
            <div class="card">
                <i class="fas fa-users"></i>
                <h3>Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
