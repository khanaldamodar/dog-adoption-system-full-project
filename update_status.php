<?php
// Database connection
$connection = mysqli_connect('localhost', 'root', '', 'dog-adoption-project');

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $adoption_id = intval($_GET['id']);
    $new_status = $_GET['status'];

    $query = "UPDATE adoption SET status = '$new_status' WHERE id = $adoption_id";
    $pet_status_query = "";

    // Update pet availability based on status
    if ($new_status === 'Accepted') {
        $pet_status_query = "UPDATE dogs SET status = 0 WHERE id = (SELECT dog_id FROM adoption WHERE id = $adoption_id)";
    } else {
        $pet_status_query = "UPDATE dogs SET status = 1 WHERE id = (SELECT dog_id FROM adoption WHERE id = $adoption_id)";
    }

    if (mysqli_query($connection, $query) && mysqli_query($connection, $pet_status_query)) {
        header('Location: adoption.php');
        exit;
    } else {
        echo "Error updating status: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request.";
}
?>
