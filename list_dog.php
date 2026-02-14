<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="a_m.css">
    <style>
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
    
</div><br>
        <div class="row">
        <?php require_once 'list_dogs_categories.php';?>
            
        </div>
       
       
        
    </div>

</div>
</body>
</html>



