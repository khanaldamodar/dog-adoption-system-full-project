<?php
require_once 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<div class='error-message'>Error: User not logged in.</div>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the user exists
$check_user = $connection->prepare("SELECT id FROM users WHERE id = ?");
$check_user->bind_param("i", $user_id);
$check_user->execute();
$user_result = $check_user->get_result();
$check_user->close();

if ($user_result->num_rows === 0) {
    echo "<div class='error-message'>Error: The user does not exist.</div>";
    exit();
}

// Get Dog Details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dog_id = intval($_GET['id']);
    
    $stmt = $connection->prepare("SELECT * FROM dogs WHERE id = ?");
    $stmt->bind_param("i", $dog_id);
    $stmt->execute();
    $dog_result = $stmt->get_result();
    
    if ($dog_result->num_rows > 0) {
        $dog = $dog_result->fetch_assoc();
    } else {
        echo "<div class='error-message'>Error: Dog not found!</div>";
        exit();
    }
    $stmt->close();
} else {
    echo "<div class='error-message'>Error: Invalid dog ID.</div>";
    exit();
}

// Check if the user has already applied for this dog
$check_adoption = $connection->prepare("SELECT status FROM adoption WHERE user_id = ? AND dog_id = ? ORDER BY id DESC LIMIT 1");
$check_adoption->bind_param("ii", $user_id, $dog_id);
$check_adoption->execute();
$adoption_result = $check_adoption->get_result();
$check_adoption->close();

$already_applied = false;
if ($adoption_result->num_rows > 0) {
    $adoption = $adoption_result->fetch_assoc();
    if ($adoption['status'] !== 'Rejected') {
        $already_applied = true;
    }
}

// Handle Form Submission
if (isset($_POST['btnSubmit']) && !$already_applied) {
    $err = [];

    // Name Validation
    if (!empty($_POST['name']) && preg_match('/^[A-Za-z\s]+$/', $_POST['name'])) {
        $name = trim($_POST['name']);
    } else {
        $err['name'] = "Please enter a valid name.";
    }

    // Email Validation
    if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = trim($_POST['email']);
    } else {
        $err['email'] = "Please enter a valid email.";
    }

    // Phone Number Validation
    if (!empty($_POST['number']) && preg_match('/^[9]{1}[0-9]{9}$/', $_POST['number'])) {
        $number = trim($_POST['number']);
    } else {
        $err['number'] = "Please enter a valid phone number.";
    }

    // Address Validation (Optional)
    $address = !empty($_POST['address']) ? trim($_POST['address']) : '';

    // Other Fields
    $breed = !empty($_POST['breed']) ? trim($_POST['breed']) : '';
    $adoption_date = $_POST['adoption_date'] ?? date('Y-m-d');
    $message = !empty($_POST['message']) ? trim($_POST['message']) : '';

    // If No Errors, Process the Adoption Request
    if (count($err) === 0) {
        $stmt = $connection->prepare("INSERT INTO adoption (user_id, name, email, number, address, breed, adoption_date, message, dog_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("isssssssi", $user_id, $name, $email, $number, $address, $breed, $adoption_date, $message, $dog_id);

        if ($stmt->execute()) {
            $adoption_id = $stmt->insert_id; // Get last inserted adoption ID

            // Insert Notification
            $notification_msg = "New adoption request from $name for " . htmlspecialchars($dog['name']) . ".";
            $stmt2 = $connection->prepare("INSERT INTO notifications (adoption_id, user_id, message, status) VALUES (?, ?, ?, 'Unread')");
            $stmt2->bind_param("iis", $adoption_id, $user_id, $notification_msg);

            if ($stmt2->execute()) {
                echo "<div class='success-message'>Your adoption form has been submitted successfully!</div>";
                // Redirect after success
                header("Location: animals.php");
                exit();
            } else {
                echo "<div class='error-message'>Error inserting notification: " . $stmt2->error . "</div>";
            }

            $stmt2->close();
        } else {
            echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        // Display Validation Errors
        foreach ($err as $error) {
            echo "<div class='error-message'>$error</div>";
        }
    }
} elseif ($already_applied) {
    echo "<div class='error-message'>You have already applied for this dog.</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adoption Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .container {
            width: 80%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .form-header h2 {
            font-size: 2rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .form-header img {
            width: 180px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-weight: 500;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="date"], 
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input:focus, 
        textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
            outline: none;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            font-size: 1.2rem;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(69, 160, 73, 0.3);
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .error:before {
            content: "⚠";
            margin-right: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h2>Adopt <?php echo htmlspecialchars($dog['name']); ?>!</h2>
            <img src="image/<?php echo htmlspecialchars($dog['image']); ?>" alt="Dog Image">
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="dog_name"><i class="fas fa-paw"></i> Dog's Name: </label>
                <input type="text" name="dog_name" value="<?php echo htmlspecialchars($dog['name']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="breed"><i class="fas fa-dog"></i> Breed: </label>
                <input type="text" name="breed" value="<?php echo isset($dog['breed']) ? htmlspecialchars($dog['breed']) : ''; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Your Name: </label>
                <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Your Email: </label>
                <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="number"><i class="fas fa-phone"></i> Your Phone Number: </label>
                <input type="text" name="number" value="<?php echo isset($_POST['number']) ? htmlspecialchars($_POST['number']) : ''; ?>" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label for="address"><i class="fas fa-map-marker-alt"></i> Your Address: </label>
                <input type="text" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" placeholder="Enter your address">
            </div>

            <div class="form-group">
                <label for="message"><i class="fas fa-comment"></i> Message: </label>
                <textarea name="message" placeholder="Tell us why you'd like to adopt this dog"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>

            <button type="submit" name="btnSubmit"><i class="fas fa-heart"></i> Submit Adoption Form</button>
        </form>
    </div>
</body>
</html>
