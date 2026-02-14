<?php require_once 'check_admin_login.php'; ?>

<?php
// Database connection
$connection = mysqli_connect('localhost', 'root', '', 'dog-adoption-project');

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Fetch pending adoption requests
$sql_pending = "SELECT d.name AS dog_name, d.breed AS dog_breed, a.name AS adopter_name, a.email, a.number,a.address, a.message, a.status, a.id AS adoption_id
                FROM adoption a
                JOIN dogs d ON a.dog_id = d.id
                WHERE a.status = 'Pending'";
$result_pending = mysqli_query($connection, $sql_pending);
$pending_data = mysqli_fetch_all($result_pending, MYSQLI_ASSOC);

// Fetch processed adoption requests
$sql_processed = "SELECT d.name AS dog_name, d.breed AS dog_breed, a.name AS adopter_name, a.status, a.id AS adoption_id
                  FROM adoption a
                  JOIN dogs d ON a.dog_id = d.id
                  WHERE a.status IN ('Accepted', 'Rejected')";
$result_processed = mysqli_query($connection, $sql_processed);
$processed_data = mysqli_fetch_all($result_processed, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Management</title>
    
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
            line-height: 1.6;
        }

        .list {
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
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
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
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tablestyle td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 0.9rem;
        }

        .tablestyle tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge.accepted {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-badge.rejected {
            background: #ffebee;
            color: #c62828;
        }

        .status-badge.pending {
            background: #fff3e0;
            color: #ef6c00;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .accept-btn, .reject-btn, .reverse-btn, .delete-btn {
            height: 30px;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .accept-btn {
            background-color: #2ecc71;
        }

        .reject-btn {
            background-color: #e74c3c;
        }

        .reverse-btn {
            background-color: #3498db; 
        }

        .delete-btn {
            background-color: #e67e22; 
        }

        .accept-btn:hover {
            background-color: #27ae60;
        }

        .reject-btn:hover {
            background-color: #c0392b;
        }

        .reverse-btn:hover {
            background-color: #2980b9; 
        }

        .delete-btn:hover {
            background-color: #d35400; 
        }

        button a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .no-data, .error {
            text-align: center;
            padding: 2rem;
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .list {
                margin: 1rem;
                padding: 1rem;
            }

            .h1list {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="list">
        <div class="header">
            <h1 class="h1list">Adoption Applications</h1>
            <p class="subtitle">Manage adoption requests</p>
        </div>

        <!-- Pending Adoption Requests -->
        <div class="table-container">
            <h2>Pending Applications</h2>
            <table class="tablestyle">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Dog Name</th>
                        <th>Dog Breed</th>
                        <th>Adopter Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_data as $key => $record) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo htmlspecialchars($record['dog_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['dog_breed']); ?></td>
                            <td><?php echo htmlspecialchars($record['adopter_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['email']); ?></td>
                            <td><?php echo htmlspecialchars($record['number']); ?></td>
                            <td><?php echo htmlspecialchars($record['address']); ?></td>
                            <td><?php echo htmlspecialchars($record['message']); ?></td>
                            <td>
                                <span class="status-badge pending">Pending</span>
                            </td>
                            <td class="action-buttons">
                                <button class="accept-btn">
                                    <a href="update_status.php?id=<?php echo $record['adoption_id']; ?>&status=Accepted"
                                       onclick="return confirm('Mark as Accepted?')">Accept</a>
                                </button>
                                <button class="reject-btn">
                                    <a href="update_status.php?id=<?php echo $record['adoption_id']; ?>&status=Rejected"
                                       onclick="return confirm('Mark as Rejected?')">Reject</a>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Processed Adoption Requests -->
        <div class="table-container">
            <h2>Processed Applications</h2>
            <table class="tablestyle">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Dog Name</th>
                        <th>Dog Breed</th>
                        <th>Adopter Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($processed_data as $key => $record) { ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo htmlspecialchars($record['dog_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['dog_breed']); ?></td>
                            <td><?php echo htmlspecialchars($record['adopter_name']); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($record['status']); ?>">
                                    <?php echo htmlspecialchars($record['status']); ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <button class="reverse-btn">
                                    <a href="update_status.php?id=<?php echo $record['adoption_id']; ?>&status=Pending"
                                       onclick="return confirm('Reverse to Pending?')">Reverse</a>
                                </button>
                                <button class="delete-btn">
                                    <a href="delete_adoption.php?id=<?php echo $record['adoption_id']; ?>"
                                       onclick="return confirm('Delete this adoption?')">Delete</a>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
