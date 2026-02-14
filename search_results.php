<?php
session_start();
// Include database connection
require_once 'connection.php';

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Get the search breed from the query string
$search_breed = isset($_GET['search_breed']) ? $_GET['search_breed'] : '';

// Initialize result variable
$result = null;

// Query to fetch active dogs by breed
if (!empty($search_breed)) {
    $sql = "SELECT * FROM dogs 
            WHERE status = 1 
            AND breed LIKE ? 
            AND id NOT IN (SELECT id FROM adoption WHERE status = 'Accepted')";
    
    // Prepare and bind parameters to avoid SQL injection
    $stmt = $connection->prepare($sql);
    $search_breed_param = '%' . $search_breed . '%';
    $stmt->bind_param("s", $search_breed_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch all active dogs that are not yet adopted
    $sql = "SELECT * FROM dogs 
            WHERE status = 1 
            AND id NOT IN (SELECT id FROM adoption WHERE status = 'Accepted')";
    $result = $connection->query($sql);
}

if (!$result) {
    die("Query failed: " . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dogs Available for Adoption</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Your existing styles go here, just make sure to include them for consistency. */
    </style>
</head>
<body>
<header class="header">
    <div class="logo">
        <i class="fas fa-dog dog-icon"></i>
        <div>
            <span class="main-text">ADO~PUPS</span>
            <br>
            <span class="sub-text">Adopt Happiness</span>
        </div>
    </div>
    <div class="menu-toggle"><i class="fas fa-bars"></i></div>
    <nav>
        <ul>
            <li><a href="homepage.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="About-us.php"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="donation.php"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        </ul>
    </nav>
</header>

<h1 class="page-title">Available Dogs of Breed: <?php echo htmlspecialchars($search_breed); ?></h1>

<section class="pet-cards">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="pet-card">
                <img src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="pet-image">
                <div class="pet-info">
                    <h2 class="pet-name"><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p class="pet-description"><?php echo htmlspecialchars($row['message']); ?></p>
                    <div class="button-wrapper">
                        <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline">DETAILS</a>
                        <?php if ($is_logged_in) { ?>
                            <a href="application.php?id=<?php echo $row['id']; ?>" class="btn btn-fill">ADOPT ME</a>
                        <?php } else { ?>
                            <a href="register.php" class="btn btn-fill">LOGIN TO ADOPT</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-pets-message'>No dogs found for this breed or currently available for adoption. Please check back later!</div>";
    }
    ?>
</section>

</body>
</html>
