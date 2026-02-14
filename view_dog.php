<?php
// Include database connection and session start
require_once 'connection.php';
session_start();

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $dog_id = $_GET['id'];

    // Query to fetch details of the selected dog
    $sql = "SELECT * FROM dogs WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $dog_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the dog exists
    if ($result->num_rows > 0) {
        $dog = $result->fetch_assoc();
    } else {
        header("Location: dog_list.php"); // Redirect to dog listing page if not found
        exit;
    }
} else {
    header("Location: dog_list.php");
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
            margin: 10px 0;
            color: #34495e;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
        }

        .dog-details i {
            color: #4CAF50;
        }

        .dog-description {
            font-size: 1rem;
            line-height: 1.6;
            margin: 20px 0;
            color: #555;
            padding: 0 20px;
        }

        .badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 0.9rem;
            font-weight: 500;
            color: white;
        }

        .badge.available {
            background: #4CAF50;
        }

        .badge.adopted {
            background: #E85C0D;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin: 20px 0;
            padding-left: 30px;
            text-align: left;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 1rem;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if (isset($dog)): ?>
            <span class="badge <?php echo ($dog['status'] == 1) ? 'available' : 'adopted'; ?>">
                <?php echo ($dog['status'] == 1) ? 'Available for Adoption' : 'Already Adopted'; ?>
            </span>
            <img src="image/<?php echo htmlspecialchars($dog['image']); ?>" alt="<?php echo htmlspecialchars($dog['name']); ?>" class="dog-image">
            <div class="card-content">
                <h2 class="dog-breed"><?php echo htmlspecialchars($dog['name']); ?></h2>
                
                <div class="details-grid">
                    <p class="dog-details">
                        <i class="fas fa-paw"></i> <strong>Breed:</strong> <?php echo htmlspecialchars($dog['breed']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-venus-mars"></i> <strong>Gender:</strong> <?php echo htmlspecialchars($dog['gender']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-palette"></i> <strong>Color:</strong> <?php echo htmlspecialchars($dog['color']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-ruler"></i> <strong>Size:</strong> <?php echo htmlspecialchars($dog['size']); ?>
                    </p>
                    <p class="dog-details">
                        <i class="fas fa-birthday-cake"></i> <strong>Age:</strong> <?php echo htmlspecialchars($dog['age']); ?> years
                    </p>
                   
                </div>

                <p class="dog-description">
                    <i class="fas fa-comment"></i> <strong>Description:</strong> <?php echo htmlspecialchars($dog['message']); ?>
                </p>

                <a href="list_dog.php" class="back-link">← Back to Dog Listings</a>
            </div>
        <?php else: ?>
            <p>Sorry, no details available for this dog.</p>
        <?php endif; ?>
    </div>
</body>
</html>
