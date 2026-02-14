<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dog-adoption-project";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the query parameter (search term)
$searchTerm = $_GET['query'];

// Prevent SQL injection by escaping the search term
$searchTerm = $conn->real_escape_string($searchTerm);

// Query to search the dogs table by breed
$sql = "SELECT breed FROM dogs WHERE breed LIKE '%$searchTerm%' GROUP BY breed LIMIT 5";  // Limit 5 results for suggestions
$result = $conn->query($sql);

// Array to store suggestions
$suggestions = [];

if ($result->num_rows > 0) {
    // Fetch results and store them in the suggestions array
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'breed' => $row['breed']
        ];
    }
}

// Return suggestions as JSON
echo json_encode($suggestions);

// Close connection
$conn->close();
?>
