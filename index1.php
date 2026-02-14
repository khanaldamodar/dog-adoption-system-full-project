<!-- <?php
// Establish a database connection
require "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Using a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM user_details WHERE username = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            header("Location: register.php");
            exit();
        } else {
            echo "<p style='color:red;'>Login failed. Invalid credentials.</p>";
        }
    } else {
        echo "<p style='color:red;'>Login failed. Invalid credentials.</p>";
    }
    $stmt->close();
}
$conn->close();
?> -->
