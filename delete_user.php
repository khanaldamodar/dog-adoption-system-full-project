<?php


if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Database connection
    $host = "localhost";
    $dbname = "dog-adoption-project";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        // Set session message and redirect back to the user table page
        $_SESSION['message'] = "User deleted successfully!";
        header("Location: userdata.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
        header("Location: userdata.php");
        exit();
    }
} else {
    // If no ID is provided, redirect to the dashboard
    header("Location: userdata.php");
    exit();
}
