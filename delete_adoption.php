<?php
// Database connection
$connection = mysqli_connect('localhost', 'root', '', 'dog-adoption-project');

if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $adoption_id = intval($_GET['id']);

    // Fetch the dog_id associated with this adoption request
    $query = "SELECT dog_id FROM adoption WHERE id = $adoption_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $dog_id = $row['dog_id'];

        // Update the dog's status to 1 (available)
        $update_query = "UPDATE dogs SET status = 1 WHERE id = $dog_id";
        if (mysqli_query($connection, $update_query)) {
            // Now delete the adoption record
            $delete_query = "DELETE FROM adoption WHERE id = $adoption_id";

            if (mysqli_query($connection, $delete_query)) {
                header('Location: adoption.php');
                exit;
            } else {
                echo "Error deleting adoption record: " . mysqli_error($connection);
            }
        } else {
            echo "Error updating dog status: " . mysqli_error($connection);
        }
    } else {
        echo "Adoption record not found.";
    }
} else {
    echo "Invalid request.";
}
?>
