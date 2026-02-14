<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dog-adoption-project";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the breed from the query parameter
$breed = isset($_GET['breed']) ? $_GET['breed'] : '';

// Prevent SQL injection by escaping the breed parameter
$breed = $conn->real_escape_string($breed);

// Query to get dogs of the selected breed
$sql = "SELECT * FROM dogs WHERE breed = '$breed' AND status = 1";  // Assuming status 1 means available dogs
$result = $conn->query($sql);

// HTML and CSS structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Dogs for Adoption</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .page-title {
            text-align: center;
            margin: 40px 0;
            font-size: 2.5rem;
            color: #2C3E50;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .pet-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .pet-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
        }

        .pet-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 18px 30px rgba(0, 0, 0, 0.2);
            filter: brightness(1.05);
        }

        .pet-image {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 50%;
            margin: 20px auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .pet-card:hover .pet-image {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .pet-info {
            padding: 20px;
            text-align: center;
            flex: 1;
        }

        .pet-name {
            font-size: 1.8rem;
            color: #E85C0D;
            margin-bottom: 10px;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        .pet-description {
            font-size: 1rem;
            color: #555;
            margin: 15px 0;
            line-height: 1.5;
            height: 75px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline {
            background: transparent;
            color: #E85C0D;
            border: 2px solid #E85C0D;
        }

        .btn-outline:hover {
            background: #E85C0D;
            color: white;
            transform: scale(1.05);
        }

        .btn-fill {
            background: #E85C0D;
            color: white;
        }

        .btn-fill:hover {
            background: #d04f0c;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(232, 92, 13, 0.3);
        }
    </style>
</head>
<body>

<?php include"Navbar.php"?>

<h1 class="page-title"><?php echo "<h1 class='page-title'>Dogs available for adoption - Breed: " . htmlspecialchars($breed) . "</h1>"?>;
</h1>

<section class="pet-cards">
    <?php

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <div class="pet-card">
                <img src="image/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="pet-image">
                <div class="pet-info">
                    <h2 class="pet-name"><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p class="pet-description"><?php echo htmlspecialchars($row['message']); ?></p>
                    <div class="button-wrapper">
                        <a href="details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline">DETAILS</a>
                        <a href="application.php?id=<?php echo $row['id']; ?>" class="btn btn-fill">ADOPT ME</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-pets-message'>No dogs found for this breed.</div>";
    }

   
    ?>
</section>

</body>
</html>
