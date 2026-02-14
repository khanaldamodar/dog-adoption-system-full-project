<?php
session_start();
require_once 'connection.php'; // Database connection

// Fetch logged-in user ID (assuming it's stored in the session)
$user_id = $_SESSION['user_id'] ?? null;

// Redirect to login if user is not logged in
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Fetch unread notifications count for the logged-in user
$notif_count_query = "SELECT COUNT(*) AS count FROM notifications WHERE status = 'unread' AND user_id = ?";
$stmt = $connection->prepare($notif_count_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_count_result = $stmt->get_result();
$notif_count = $notif_count_result->fetch_assoc()['count'];

// Mark all notifications as read when clicked
if (isset($_POST['mark_as_read'])) {
    $update_query = "UPDATE notifications SET status = 'read' WHERE status = 'unread' AND user_id = ?";
    $stmt = $connection->prepare($update_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location: notification.php"); // Refresh page
    exit();
}

// Delete a specific notification
if (isset($_POST['delete_notification'])) {
    $notif_id = $_POST['notification_id'];
    $delete_query = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
    $stmt = $connection->prepare($delete_query);
    $stmt->bind_param("ii", $notif_id, $user_id);
    $stmt->execute();
    header("Location: notification.php"); // Refresh page
    exit();
}

// Fetch all notifications for the logged-in user
$notif_query = "SELECT n.*, a.name AS adopter_name, d.name AS dog_name 
                FROM notifications n
                LEFT JOIN adoption a ON n.adoption_id = a.id
                LEFT JOIN dogs d ON a.dog_id = d.id
                WHERE n.user_id = ?
                ORDER BY n.created_at DESC";

$stmt = $connection->prepare($notif_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --unread-indicator: #f59e0b;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: #1e293b;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .badge {
            background: var(--unread-indicator);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        }

        .notification-list {
            display: grid;
            gap: 1rem;
        }

        .notification-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            position: relative;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            animation: fadeIn 0.5s ease;
        }

        .notification-card.unread {
            border-left-color: var(--unread-indicator);
            background: linear-gradient(90deg, #fffbeb 0%, #ffffff 100%);
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .notification-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            background: #e0e7ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .notification-content h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .notification-meta {
            display: flex;
            gap: 1rem;
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .notification-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .delete-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #e63946;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            background: #d62839;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(230, 57, 70, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 640px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .notification-card {
                padding: 1rem;
            }

            .notification-meta {
                flex-direction : column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Notifications</h1>
                <div class="badge"><?= $notif_count ?> unread</div>
            </div>
            <form method="POST">
                <button type="submit" name="mark_as_read" class="btn">
                    <i class="fas fa-check-double"></i>
                    Mark All as Read
                </button>
            </form>
        </div>

        <?php if ($notif_result->num_rows > 0): ?>
            <div class="notification-list">
                <?php while ($notif = $notif_result->fetch_assoc()): ?>
                    <div class="notification-card <?= $notif['status'] == 'unread' ? 'unread' : '' ?>">
                        <div class="notification-header">
                            <div class="notification-icon">
                                <i class="fas fa-paw"></i>
                            </div>
                            <div class="notification-content">
                                <h3><?= htmlspecialchars($notif['message']) ?></h3>
                                <p class="text-sm">Adoption update</p>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <div class="notification-meta-item">
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($notif['adopter_name'] ?? 'Unknown') ?>
                            </div>
                            <div class="notification-meta-item">
                                <i class="fas fa-dog"></i>
                                <?= htmlspecialchars($notif['dog_name'] ?? 'Unknown') ?>
                            </div>
                            <div class="notification-meta-item">
                                <i class="fas fa-clock"></i>
                                <?= date("M j, Y g:i A", strtotime($notif['created_at'])) ?>
                            </div>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                            <button type="submit" name="delete_notification" class="delete-btn">
                                <i class="fas fa-trash"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <h3>No notifications found</h3>
                <p class="text-muted">You're all caught up!</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        $(document).ready(function() {
            $('.notification-card.unread').click(function() {
                $(this).removeClass('unread');
                // Here you could add AJAX to mark individual notifications as read
            });
        });
    </script>
</body>
</html>
