

<?php
session_start();
include_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate admin credentials from the database
    $sql = "SELECT * FROM admins WHERE email= ?";
    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        // Error in preparing the query
        die("Error in preparing the SQL query: " . $connection ->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Admin found, verify password
        $row = $result->fetch_assoc();
        $db_pw = $row['password'];
        if($password == $db_pw) {
            // Admin login successful
            $_SESSION["admin_id"] = $email;
            header("Location:dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "Invalid password.";
        }
    } else {
        // Admin not found
        echo "Admin not found.";
    }
}
?>