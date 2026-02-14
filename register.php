<?php
session_start(); // Start session for user authentication

// Database connection
$host = "localhost";
$dbname = "dog-adoption-project";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if session exists and verify user in the database
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // User exists, redirect to dashboard
        header("Location: index.php");
        exit();
    } else {
        // User does not exist, clear the session
        session_unset();
        session_destroy();
    }
}
// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error messages
    $errors = [];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors['password'] = "Passwords do not match.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Profile image upload logic
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to save the image
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        if (getimagesize($_FILES["profile_image"]["tmp_name"])) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = $target_file; // Store the path in the database
            } else {
                $errors['profile_image'] = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errors['profile_image'] = "File is not an image.";
        }
    }

    // If there are errors, display them
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, profile_image) VALUES (:full_name, :email, :password, :profile_image)");
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':profile_image', $profile_image);
            $stmt->execute();

            $_SESSION['register_success'] = "Registration successful! Please log in.";
            header("Location: register.php"); // Redirect to the register page
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry error
                $errors['email'] = "This email is already registered.";
            } else {
                $errors['general'] = "Registration error: " . $e->getMessage();
            }
        }
    }
}

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Initialize login errors
    $login_errors = [];

    try {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];

            header("Location: index.php"); // Redirect to dashboard
            exit();
        } else {
            $login_errors['invalid_credentials'] = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $login_errors['general'] = "Login error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADO~PUPS - Login & Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            align-items: center;
            color: #f39c12;
        }

        .logo .dog-icon {
            font-size: 36px;
            margin-right: 8px;
            color: #f39c12;
        }

        .logo .main-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo .sub-text {
            font-size: 12px;
            margin-left: 5px;
            color:rgb(9, 12, 13);
            font-style: italic;
        }

        .form {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .input-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }

        .input-group i {
            margin-right: 10px;
            color: #333;
        }

        .input-group input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 16px;
        }

        .error-message {
            color: #ff6f61;
            font-size: 12px;
            margin-top: 5px;
        }

        button[type="submit"] {
            background-color: #ff6f61;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #ff4a3d;
        }

        .form-toggle {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .form-toggle button {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 10px 20px;
            color: #333;
            transition: color 0.3s;
        }

        .form-toggle button.active {
            color: #ff6f61;
            border-bottom: 2px solid #ff6f61;
        }

        .hidden {
            display: none;
        }

        .message {
            margin-top: 10px;
            font-size: 14px;
            color: #ff6f61;
        }
    </style>
    <script>
        // Client-side validation for login form
        function validateLoginForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function(errorElement) {
                errorElement.innerHTML = '';
            });

            let email = document.getElementById("login-email").value;
            let password = document.getElementById("login-password").value;

            // Validate email
            if (email === "") {
                document.getElementById("login-email-error").innerHTML = "Email is required.";
                isValid = false;
            }

            // Validate password
            if (password === "") {
                document.getElementById("login-password-error").innerHTML = "Password is required.";
                isValid = false;
            }

            return isValid;
        }

        // Client-side validation for registration form
        function validateRegistrationForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function(errorElement) {
                errorElement.innerHTML = '';
            });

            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;

            // Check if passwords match
            if (password !== confirmPassword) {
                document.getElementById("password-error").innerHTML = "Passwords do not match.";
                isValid = false;
            }

            let fileInput = document.getElementById("profile_image");
            let file = fileInput.files[0];
            if (file) {
                let fileType = file.type.split('/')[0];
                if (fileType !== 'image') {
                    document.getElementById("profile-image-error").innerHTML = "Please upload a valid image file.";
                    isValid = false;
                }
            }

            return isValid; // Return whether the form is valid or not
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <i class="fas fa-dog dog-icon"></i>
            <div>
                <span class="main-text">ADO~PUPS</span>
                <br>
                <span class="sub-text">Adopt Happiness</span>
            </div>
        </div>

        <div class="form-container">
            <?php
            // Display messages
            if (isset($_SESSION['login_error'])) {
                echo "<div class='message'>" . $_SESSION['login_error'] . "</div>";
                unset($_SESSION['login_error']);
            }
            if (isset($_SESSION['register_success'])) {
                echo "<div class='message success'>" . $_SESSION['register_success'] . "</div>";
                unset($_SESSION['register_success']);
            }
            ?>

            <!-- Login Form -->
            <form id="login-form" class="form" method="POST" onsubmit="return validateLoginForm()">
                <h2>Login</h2>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="login-email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                </div>
                <span id="login-email-error" class="error-message">
                    <?php if (isset($login_errors['invalid_credentials'])) echo $login_errors['invalid_credentials']; ?>
                </span>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="login-password" placeholder="Enter your password" required>
                </div>
                <span id="login-password-error" class="error-message">
                    <?php if (isset($login_errors['general'])) echo $login_errors['general']; ?>
                </span>

                <button type="submit" name="login">Login</button>
            </form>

            <!-- Registration Form -->
            <form id="register-form" class="form" method="POST" enctype="multipart/form-data" onsubmit="return validateRegistrationForm()">
                <h2>Register</h2>

                <!-- Display errors -->
                <?php
                if (!empty($errors)) {
                    echo "<div class='message'>";
                    foreach ($errors as $error) {
                        echo "<p>$error</p>";
                    }
                    echo "</div>";
                }
                ?>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="full_name" placeholder="Full Name" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <span id="password-error" class="error-message"></span>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                </div>
                <span id="confirm-password-error" class="error-message"></span>

                <div class="input-group">
                    <input type="file" name="profile_image" id="profile_image">
                </div>
                <span id="profile-image-error" class="error-message"></span>

                <button type="submit" name="register">Register</button>
            </form>

            <div class="form-toggle">
                <button onclick="toggleLogin()">Login</button>
                <button onclick="toggleRegister()">Register</button>
            </div>
        </div>
    </div>

    <script>
        function toggleLogin() {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
        }

        function toggleRegister() {
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('login-form').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleLogin(); // Default to login form
        });
    </script>
</body>
</html>
