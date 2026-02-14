<header class="header">
    <div class="logo">
        <i class="fas fa-dog dog-icon"></i>
        <div>
            <span class="main-text">ADO~PUPS</span>
            <br>
            <span class="sub-text">Adopt Happiness</span>
        </div>
    </div>
    <div class="menu-toggle"><i class="fas fa-bars"></i></div>
    <nav>
        <ul>
            <li><a href="homepage.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="About-us.php"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="donation.php"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
        </ul>
    </nav>
    <?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the breed from the query parameter
$breed = isset($_GET['breed']) ? $_GET['breed'] : '';

// Prevent SQL injection by escaping the breed parameter
$breed = $conn->real_escape_string($breed);

// Query to get dogs of the selected breed
$sql = "SELECT * FROM dogs WHERE breed = '$breed' AND status = 1";  // Assuming status 1 means available dogs
$result = $conn->query($sql);

// Display breed name
echo "<h1>Dogs available for adoption - Breed: " . htmlspecialchars($breed) . "</h1>";

if ($result->num_rows > 0) {
    // Output the list of dogs
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>Name:</strong> " . htmlspecialchars($row['name']) . "<br>";
        echo "<strong>Age:</strong> " . $row['age'] . " years<br>";
        echo "<strong>Color:</strong> " . htmlspecialchars($row['color']) . "<br>";
        echo "<strong>Size:</strong> " . htmlspecialchars($row['size']) . "<br>";
        echo "<strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "<br>";
        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Dog Image' width='100' height='100'><br>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "No dogs found for this breed.";
}

// Close connection
$conn->close();
?>

</header>
