<?php
// Include database connection and session start
require_once 'connection.php';
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $dog_id = $_GET['id'];

    // Query to fetch details of the selected dog
    $sql = "SELECT * FROM dogs WHERE id = ? AND status = 1";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $dog_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the dog exists
    if ($result->num_rows > 0) {
        $dog = $result->fetch_assoc();
    } else {
        echo "<p>Sorry, no details found for this dog.</p>";
        exit;
    }
} else {
    echo "<p>No dog selected.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meet <?php echo htmlspecialchars($dog['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .dog-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-bottom: 3px solid #4CAF50;
        }

        .card-content {
            padding: 30px;
        }

        .dog-breed {
            font-size: 2.2rem;
            margin: 10px 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .dog-details {
            font-size: 1.1rem;
            margin: 15px 0;
            color: #34495e;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .dog-details i {
            color: #4CAF50;
            margin-right: 8px;
        }

        .dog-description {
            font-size: 1rem;
            line-height: 1.6;
            margin: 20px 0;
            color: #555;
            padding: 0 20px;
        }

        .adopt-button {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .adopt-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(69, 160, 73, 0.3);
        }

        .login-button {
            background: linear-gradient(135deg, #E85C0D 0%, #d04f0c 100%);
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(232, 92, 13, 0.3);
        }

        .badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if (isset($dog)): ?>
            <span class="badge">Available for Adoption</span>
            <img src="image/<?php echo htmlspecialchars($dog['image']); ?>" alt="<?php echo htmlspecialchars($dog['name']); ?>" class="dog-image">
            <div class="card-content">
                <h2 class="dog-breed"><?php echo htmlspecialchars($dog['name']); ?></h2>
                
                <div class="details-grid">
                    <p class="dog-details">
                        <i class="fas fa-palette"></i>
                        <?php echo htmlspecialchars($dog['color']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-ruler"></i>
                        <?php echo htmlspecialchars($dog['size']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-venus-mars"></i>
                        <?php echo htmlspecialchars($dog['gender']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-paw"></i>
                        <?php echo htmlspecialchars($dog['breed']); ?>
                    </p>
                </div>

                <p class="dog-description">
                    <?php echo htmlspecialchars($dog['message']); ?>
                </p>

                <?php if ($is_logged_in): ?>
                    <a href="application.php?id=<?php echo htmlspecialchars($dog_id); ?>" class="adopt-button">
                        <i class="fas fa-heart"></i>
                        Adopt Me!
                    </a>
                <?php else: ?>
                    <a href="register.php" class="login-button">
                        <i class="fas fa-sign-in-alt"></i>
                        Login to Adopt
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Sorry, no details available for this dog.</p>
        <?php endif; ?>
    </div>
</body>
</html>
