<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it hasn't been started already
}

$host = "localhost";
$dbname = "dog-adoption-project";
$username = "root";
$password = "";

try {
    // Database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize variables
    $user = null;
    $notif_count = 0;
    $adoption_requests = [];

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Fetch user details
        $stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Sanitize user data
            $full_name = htmlspecialchars($user['full_name']);
            $email = htmlspecialchars($user['email']);
            $phone = htmlspecialchars($user['phone']);
            $profile_image = !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'user.png';

            // Handle profile updates
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
                $new_name = $_POST['name'];
                $new_email = $_POST['email'];
                $new_phone = $_POST['phone'];

                // Handle profile picture upload
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/'; // Directory to store uploaded images
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
                    }

                    $file_name = basename($_FILES['profile_pic']['name']);
                    $file_path = $upload_dir . $file_name;

                    // Move the uploaded file to the upload directory
                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $file_path)) {
                        $profile_image = $file_path; // Update the profile image path
                    } else {
                        die("Failed to upload profile picture.");
                    }
                }

                // Update user details in the database
                $update_stmt = $conn->prepare("
                    UPDATE users 
                    SET full_name = :name, email = :email, phone = :phone, profile_image = :profile_image 
                    WHERE id = :user_id
                ");
                $update_stmt->bindParam(':name', $new_name);
                $update_stmt->bindParam(':email', $new_email);
                $update_stmt->bindParam(':phone', $new_phone);
                $update_stmt->bindParam(':profile_image', $profile_image);
                $update_stmt->bindParam(':user_id', $user_id);
                $update_stmt->execute();

                // Refresh the page to reflect changes
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            // Fetch unread notifications count for the logged-in user
            $notif_count_query = "SELECT COUNT(*) AS count FROM notifications WHERE status = 'unread' AND user_id = :user_id";
            $stmt = $conn->prepare($notif_count_query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $notif_count_result = $stmt->fetch(PDO::FETCH_ASSOC);
            $notif_count = $notif_count_result['count'] ?? 0;

            // Fetch user's adoption requests with dog details
            $stmt = $conn->prepare("
                SELECT 
                    a.id AS adoption_id, 
                    a.status, 
                    a.message, 
                    d.name AS dog_name, 
                    d.image AS dog_image 
                FROM adoption a
                JOIN dogs d ON a.dog_id = d.id
                WHERE a.user_id = :user_id
                ORDER BY a.id DESC
            ");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $adoption_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="homepage.css">
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f6fa;
        }

        /* Search Bar */
.search-bar {
    display: flex;
    align-items: center;
    background: #ecf0f1;
    padding: 6px 10px;
    border-radius: 8px;
    margin-left: auto; /* Pushes it to the rightmost available space */
    margin-right: 80px; /* Creates space between search bar and profile */
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    position: relative; /* Set position to relative to position suggestions correctly */
}

.search-bar input {
    border: none;
    outline: none;
    background: none;
    font-size: 14px;
    margin-left: 8px;
    color: #34495e;
}

.search-bar input::placeholder {
    color: #7f8c8d;
}

.search-bar i {
    color: #34495e;
}

/* Hover Effect for Search Bar */
.search-bar:hover {
    background:rgb(239, 242, 242);
}

/* Suggestions box styles */
#suggestions {
    position: absolute; /* Position it absolutely */
    top: 100%; /* Position below the search bar */
    left: 0; /* Align to the left of the search bar */
    width: auto; /* Change width to auto to fit content */
    max-width: 250px; /* Set a maximum width to limit the size */
    background-color: #fff;
    border-radius: 0 0 10px 10px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    max-height: 150px; /* Maximum height */
    overflow-y: auto; /* Scroll if needed */
    z-index: 10; /* Layering */
    display: none; /* Hidden by default */
    padding: 5px 0; /* Padding for the suggestions box */
}

/* Individual suggestion styles */
#suggestions div {
    padding: 10px 15px; /* Padding for suggestions */
    font-size: 1rem; /* Font size */
    color: #333; /* Text color */
    cursor: pointer; /* Pointer cursor */
    transition: color 0.3s ease, text-decoration 0.3s ease; /* Smooth transition */
    font-family: 'Poppins', sans-serif; /* Font family */
}

/* Hover effect for suggestions */
#suggestions div:hover {
    color: #E85C0D; /* Change text color to orange on hover */
    text-decoration: underline; /* Underline on hover */
}

/* Optional - suggestion box with rounded corners */
#suggestions div {
    border-bottom: 1px solid #ddd; /* Light border for separation */
}

/* Hide last border */
#suggestions div:last-child {
    border-bottom: none; /* Remove border for the last suggestion */
}
header nav ul li a {
    position: relative; /* Ensure the <a> element is positioned relative for its children */
}

header nav ul li a .notif-count {
    color: white; /* White text for contrast */
    font-weight: bold; /* Bold text for visibility */
    position: absolute; /* Position the count absolutely within the <a> element */
    display: flex; /* Flexbox for centering text */
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
    width: auto; /* Allow the width to adjust based on content */
    height: 18px; /* Height for a balanced oval shape */
    padding: 0 8px; /* Horizontal padding */
    text-align: center; /* Center the text */
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.3); /* Shadow for depth */
    background: red; /* Red background for visibility */
    font-size: 12px; /* Font size for notification count */
    z-index: 2; /* Ensure it stays on top */
    border-radius: 12px; /* Oval shape */
    top: -3px; /* Adjust vertical gap */
    right: -3px; /* Adjust horizontal gap */
    transition: transform 0.2s ease-in-out; /* Hover animation */
}

header nav ul li a:hover .notif-count {
    transform: scale(1.15); /* Slight scaling effect on hover */
}
.user-profile {
    position: relative;
    display: inline-block;
}

.user-profile img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 50px;
    background-color: #fff;
    min-width: 300px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1000;
    border-radius: 8px;
    overflow: hidden;
}

.dropdown-content .dashboard {
    background: #fff;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
}

.dropdown-content .sidebar {
    background: #6200ea;
    color: #fff;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.dropdown-content .sidebar img {
    border-radius: 50%;
    width: 100px;
    height: 100px;
    margin-bottom: 15px;
}

.dropdown-content .sidebar h2 {
    font-size: 20px;
    margin-bottom: 10px;
}

.dropdown-content .button-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.dropdown-content .button-group button {
    background: #fff;
    color: #6200ea;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s;
    display: flex;
    align-items: center;
}

.dropdown-content .button-group button i {
    margin-right: 8px;
}

.dropdown-content .button-group button:hover {
    background: #f3e5f5;
}

.dropdown-content main {
    flex: 1;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.dropdown-content .profile-section, .dropdown-content .adoption-status {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.dropdown-content .profile-section h2, .dropdown-content .adoption-status h2 {
    font-size: 20px;
    margin-bottom: 15px;
    color: #6200ea;
}

.dropdown-content form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

.dropdown-content form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: border 0.3s;
}

.dropdown-content form input:focus {
    border-color: #6200ea;
    outline: none;
}

.dropdown-content form button {
    background: #6200ea;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
}

.dropdown-content form button i {
    margin-right: 8px;
}

.dropdown-content form button:hover {
    background: #3700b3;
}

.dropdown-content .request-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dropdown-content .request-info {
    display: flex;
    align-items: center;
}

.dropdown-content .request-info img {
    border-radius: 50%;
    width: 60px;
    height: 60px;
    margin-right: 15px;
}

.dropdown-content .request-info h3 {
    font-size: 18px;
    color: #333;
}

.dropdown-content .status {
    font-weight: 600;
    display: flex;
    align-items: center;
}

.dropdown-content .status.pending {
    color: #ff9800;
}

.dropdown-content .status.approved {
    color: #4caf50;
}
.dropdown-content .status.rejected {
    color:rgb(242, 19, 19);
}

.dropdown-content .status i {
    margin-right: 8px;
}

.dropdown-content .view-details {
    background: #6200ea;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
}

.dropdown-content .view-details i {
    margin-right: 8px;
}

.dropdown-content .view-details:hover {
    background: #3700b3;
}

@media (max-width: 768px) {
    nav ul {
        display: none;
        flex-direction: column;
        background-color: #34495e;
        position: absolute;
        top: 70px;
        right: 15px;
        width: 200px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    nav ul.active {
        display: flex;
    }

    .menu-toggle {
        display: block;
    }
}

header nav ul li a {
position: relative; /* Position the badge relative to the bell icon */
}
 /* Add this CSS for the login/register button */
 .auth-buttons {
            display: flex;
            gap: 10px;
            margin-left: auto;
            margin-right: 20px;
        }

        .auth-buttons a {
            background-color:rgb(238, 114, 19);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .auth-buttons a:hover {
            background-color:rgb(247, 179, 89);
        }

        .auth-buttons a i {
            font-size: 16px;
        }


    </style>
</head>
<body>
<header>
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
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="Animals.php"><i class="fas fa-paw"></i> Animals</a></li>
            <li><a href="donation.php"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="notification.php">
                        <i class="fas fa-bell"></i> 
                        <?php if ($notif_count > 0): ?>
                            <span class="notif-count"><?= $notif_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="search-bar">
        <form action="search_results.php" method="GET">
            <input type="text" name="search_breed" id="searchInput" placeholder="Search pets by breed..." onkeyup="getSuggestions()">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <ul id="suggestions" style="display: none;"></ul>
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-profile">
            <img src="<?php echo $profile_image; ?>" alt="User Profile" onclick="toggleDropdown()">
            <div class="dropdown-content">
                <div class="dashboard">
                    <aside class="sidebar">
                        <img src="<?php echo $profile_image; ?>" alt="User Profile">
                        <h2><?php echo $full_name; ?></h2>
                        <div class="button-group">
                            <button class="edit-profile" onclick="toggleEditProfile()">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </button>
                            <button class="logout" onclick="window.location.href='loggout.php'">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </aside>
                    <main>
                        <section class="profile-section" id="editProfileSection" style="display: none;">
                            <h2>Edit Profile</h2>
                            <form id="profileForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" value="<?php echo $full_name; ?>" required>
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                                <label for="phone">Phone:</label>
                                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                                <label for="profile_pic">Profile Picture:</label>
                                <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                                <button type="submit" name="update_profile">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </form>
                        </section>
                        <section class="adoption-status">
                            <h2>Adoption Requests</h2>
                            <?php if (count($adoption_requests) > 0): ?>
                                <?php foreach ($adoption_requests as $request): ?>
                                    <div class="request-card">
                                        <div class="request-info">
                                            <img src="image/<?php echo htmlspecialchars($request['dog_image']); ?>" alt="Dog Image" class="dog-image">
                                            <div>
                                                <h3><?php echo htmlspecialchars($request['dog_name']); ?></h3>
                                                <p class="status <?php echo strtolower($request['status']); ?>">
                                                    <i class="fas fa-<?php echo ($request['status'] === 'Approved') ? 'check-circle' : 'clock'; ?>"></i>
                                                    <?php echo htmlspecialchars($request['status']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No adoption requests found.</p>
                            <?php endif; ?>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="auth-buttons">
            <a href="register.php"><i class="fas fa-user-plus"></i> Register/Login</a>
        </div>
    <?php endif; ?>
</header>
<script>
    function toggleDropdown() {
        const dropdown = document.querySelector('.dropdown-content');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    function toggleEditProfile() {
        const editProfileSection = document.getElementById('editProfileSection');
        editProfileSection.style.display = editProfileSection.style.display === 'none' ? 'block' : 'none';
    }

    function getSuggestions() {
        const searchQuery = document.getElementById('searchInput').value;

        if (searchQuery.length === 0) {
            document.getElementById('suggestions').style.display = 'none';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "search_suggestions.php?query=" + searchQuery, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const suggestions = JSON.parse(xhr.responseText);
                showSuggestions(suggestions);
            }
        };
        xhr.send();
    }

    function showSuggestions(suggestions) {
        const suggestionsBox = document.getElementById('suggestions');
        suggestionsBox.innerHTML = '';

        if (suggestions.length > 0) {
            suggestionsBox.style.display = 'block';
            suggestions.forEach(function (suggestion) {
                const div = document.createElement('div');
                div.textContent = suggestion.breed;
                div.onclick = function () {
                    window.location.href = 'dogs_by_breed.php?breed=' + suggestion.breed;
                };
                suggestionsBox.appendChild(div);
            });
        } else {
            suggestionsBox.style.display = 'none';
        }
    }
</script>
</body>
</html>