<?php
// Database Configuration
$host = 'localhost';
$dbname = 'dog-adoption-project';
$username = 'root';
$password = '';

// Connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all donations
$stmt = $conn->prepare("SELECT * FROM donations");
$stmt->execute();
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Donations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .dashboard-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background:rgb(130, 208, 244);
            color: #fff;
            font-weight: bold;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .icon {
            margin-right: 10px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background: #ff6f61;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .action-buttons button:hover {
            background: #ff4a3d;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1><i class="fas fa-tachometer-alt icon"></i> Admin Dashboard - Donations</h1>

        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-id-badge icon"></i> ID</th>
                    <th><i class="fas fa-user icon"></i> Donor Name</th>
                    <th><i class="fas fa-envelope icon"></i> Donor Email</th>
                    <th><i class="fas fa-dollar-sign icon"></i> Amount</th>
                    <th><i class="fas fa-credit-card icon"></i> Card Number</th>
                    <th><i class="fas fa-receipt icon"></i> Transaction ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['id']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                        <td><?php echo htmlspecialchars($donation['donor_email']); ?></td>
                        <td>$<?php echo htmlspecialchars($donation['amount']); ?></td>
                        <td><?php echo htmlspecialchars($donation['card_number']); ?></td>
                        <td><?php echo htmlspecialchars($donation['transaction_id']); ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function viewDetails(id) {
            alert("View details for donation ID: " + id);
            // You can redirect to a detailed view page or show a modal
        }

        function deleteDonation(id) {
            if (confirm("Are you sure you want to delete this donation?")) {
                window.location.href = "delete_donation.php?id=" + id;
            }
        }
    </script>
</body>
</html>