<?php
require_once 'check_admin_login.php'; // Ensures user is logged in as an admin

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id']; // Get the dog ID from URL
} else {
    // If no valid ID is provided, redirect with an error message
    header('Location: list_dog.php?msg=1');
    exit;
}

require_once "connection.php"; // Include the database connection file

// Prepare the DELETE query using the ID to remove the dog entry
$stmt = $connection->prepare("DELETE FROM dogs WHERE id = ?");
$stmt->bind_param('i', $id); // Bind the ID parameter (integer)

if ($stmt->execute()) {
    // If the record was successfully deleted, redirect with success message
    header('Location: list_dog.php?msg=2');
} else {
    // If the deletion failed, redirect with failure message
    header('Location: list_dog.php?msg=3');
}

$stmt->close(); // Close the prepared statement
$connection->close(); // Close the database connection
?>
