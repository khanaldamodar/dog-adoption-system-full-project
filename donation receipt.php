<?php

// Database Configuration
$host = 'localhost';
$dbname = 'dog-adoption-project';
$username = 'root';
$password = '';

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_GET['transaction_id'])) {
    $transaction_id = htmlspecialchars($_GET['transaction_id']);

    try {
        // Fetch donation details from the database
        $stmt = $conn->prepare("SELECT * FROM donations WHERE transaction_id = :transaction_id");
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();
        $donation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($donation) {
            $name = $donation['donor_name'];
            $email = $donation['donor_email'];
            $amount = $donation['amount'];
            $date = $donation['donation_date'];
        } else {
            die('Donation not found.');
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die('Invalid request.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h1 {
            color: #4caf50;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .highlight {
            color: #4caf50;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                max-width: 90%;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You, <?php echo $name; ?>! 🐶</h1>
        <p>Your donation of <span class="highlight">$<?php echo number_format($amount, 2); ?></span> has been successfully received.</p>
        <p>A receipt has been sent to: <span class="highlight"><?php echo $email; ?></span></p>
        <p>Transaction ID: <span class="highlight"><?php echo $transaction_id; ?></span></p>
        <p>Date: <span class="highlight"><?php echo $date; ?></span></p>
        <a href="donation.php" class="button">Donate Again</a>
    </div>
</body>
</html>
