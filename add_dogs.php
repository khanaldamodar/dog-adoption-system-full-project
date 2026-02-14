<?php
session_start();
require_once 'connection.php';  // Ensure this file contains the correct database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $err = [];

    // Validations
    if (empty($_POST['name'])) $err['name'] = "Name is required.";
    if (empty($_POST['age']) || !is_numeric($_POST['age']) || $_POST['age'] <= 0) $err['age'] = "Valid age in months is required.";
    if (empty($_POST['breed'])) $err['breed'] = "Breed is required.";
    if (empty($_POST['color'])) $err['color'] = "Color is required.";
    if (empty($_POST['size'])) $err['size'] = "Size is required.";
    if (empty($_POST['gender'])) $err['gender'] = "Gender is required.";
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
        $err['photo'] = "Valid photo is required.";
    } else {
        $allowedFormats = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['photo']['type'], $allowedFormats)) {
            $err['photo'] = "Invalid image format (jpeg, png, jpg only).";
        }
    }

    // Check if there are no errors
    if (count($err) == 0) {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $breed = $_POST['breed'];
        $color = $_POST['color'];
        $size = $_POST['size'];
        $gender = $_POST['gender'];
        $status = $_POST['status'];
        $message = $_POST['message'];
        $created_at = date('Y-m-d H:i:s');
        $created_by = $_SESSION['admin_id'];  // Admin ID from session

        // Handle file upload
        if ($_FILES['photo']['error'] == 0) {
            $fname = uniqid() . '_' . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], 'image/' . $fname);
        }

        // SQL query to insert dog
        $sql = "INSERT INTO dogs (name, age, breed, color, size, gender, status, message, created_at, created_by, image)
                VALUES ('$name', '$age', '$breed', '$color', '$size', '$gender', '$status', '$message', '$created_at', '$created_by', '$fname')";

        // Execute query
        if ($connection->query($sql) === TRUE) {
            $success = 'Dog added successfully!';
        } else {
            $error = 'Error: ' . $connection->error;  // Display SQL error if query fails
        }
    } else {
        $error = 'There are validation errors. Please check your inputs.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Dog</title>
    <link rel="stylesheet" href="a_m.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            height: 100vh;
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .wrapper {
            height: 100vh;
            padding: 2rem;
        }

        .main-content {
            height: 100vh;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        form fieldset {
            border: none;
        }

        form legend {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: #2c3e50;
            position: relative;
            padding-bottom: 10px;
        }

        form legend:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #3498db;
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
            font-size: 15px;
        }

        .form-group input:not([type="radio"]),
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            font-size: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            box-sizing: border-box;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            background: #fff;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        input[type="radio"] {
            margin-right: 8px;
            cursor: pointer;
        }

        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .success_message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error_message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        input[type="submit"],
        input[type="reset"] {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"] {
            background: #3498db;
            color: #ffffff;
            border: none;
        }

        input[type="reset"] {
            background: #e74c3c;
            color: #ffffff;
            border: none;
        }

        input[type="submit"]:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        input[type="reset"]:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .file-input {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        input[type="file"] {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #cbd5e0;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="file"]:hover {
            border-color: #3498db;
        }

        .sidebar {
            position: fixed;
            height: 100vh;
            background: linear-gradient(45deg, #2c3e50, #3498db);
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .top-nav {
            background: linear-gradient(45deg, #3498db, #2980b9);
            padding: 15px 30px;
        }

        .logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logout-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
<div class="sidebar">
    <h2><a href="dashboard.php" style="text-decoration: none; color: inherit;">Admin Dashboard</a></h2>
    <ul>
        <li><a href="add_dogs.php"><i class="fas fa-plus"></i> Add Dogs</a></li>
        <li><a href="list_dog.php"><i class="fas fa-list"></i> List Dogs</a></li>
        <li><a href="donate.php"><i class="fas fa-donate"></i> Donations</a></li>
        <li><a href="contacts.php"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="adoption.php"><i class="fas fa-home"></i> Adoption</a></li>
        <li><a href="userdata.php"><i class="fas fa-user-plus"></i> Users</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>
<div class="main-content">
    <div class="top-nav">
        <div class="logo">
            <i class="fas fa-dog dog-icon"></i>
            <div>
                <span class="main-text">ADO~PUPS</span>
                <br>
                <span class="sub-text">Adopt Happiness</span>
            </div>
        </div>
         
        <button class="logout-btn" onclick="window.location.href='logout.php';">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    
</div>

        <div class="wrapper">
            <form action="" method="post" id="category_form" enctype="multipart/form-data">
                <fieldset>
                    <?php if (isset($error)) { ?>
                        <p class="error_message"><?php echo $error; ?></p>
                    <?php } ?>
                    <?php if (isset($success)) { ?>
                        <p class="success_message"><?php echo $success; ?></p>
                    <?php } ?>
                    <legend>Add New Dog</legend>

                    <div class="form-group">
    <label for="breed">Breed</label>
    <select name="breed" id="breed">
        
        <option value="Street Dog" <?php echo (isset($breed) && $breed == 'Street Dog') ? 'selected' : ''; ?>>Street Dog</option>
        <option value="Labrador Retriever" <?php echo (isset($breed) && $breed == 'Labrador Retriever') ? 'selected' : ''; ?>>Labrador Retriever</option>
        <option value="German Shepherd" <?php echo (isset($breed) && $breed == 'German Shepherd') ? 'selected' : ''; ?>>German Shepherd</option>
        <option value="Golden Retriever" <?php echo (isset($breed) && $breed == 'Golden Retriever') ? 'selected' : ''; ?>>Golden Retriever</option>
        <option value="Bulldog" <?php echo (isset($breed) && $breed == 'Bulldog') ? 'selected' : ''; ?>>Bulldog</option>
        <option value="Beagle" <?php echo (isset($breed) && $breed == 'Beagle') ? 'selected' : ''; ?>>Beagle</option>
        <option value="Poodle" <?php echo (isset($breed) && $breed == 'Poodle') ? 'selected' : ''; ?>>Poodle</option>
        <option value="Rottweiler" <?php echo (isset($breed) && $breed == 'Rottweiler') ? 'selected' : ''; ?>>Rottweiler</option>
        <option value="Dachshund" <?php echo (isset($breed) && $breed == 'Dachshund') ? 'selected' : ''; ?>>Dachshund</option>
        <option value="Other" <?php echo (isset($breed) && $breed == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>
    <?php if (isset($err['breed'])) { ?>
        <span class="error"><?php echo $err['breed']; ?></span>
    <?php } ?>
</div>


                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter dog's name"
                            value="<?php echo isset($name) ? $name : '' ?>">
                        <?php if (isset($err['name'])) { ?>
                            <span class="error"><?php echo $err['name']; ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group">
    <label for="age">Age (in months)</label>
    <input type="number" name="age" id="age" placeholder="Enter dog's age in months" 
        value="<?php echo isset($age) ? $age : '' ?>" min="1">
    <?php if (isset($err['age'])) { ?>
        <span class="error"><?php echo $err['age']; ?></span>
    <?php } ?>
</div>


                    <div class="form-group">
    <label for="breed">Breed</label>
    <select name="breed" id="breed">
        
        <option value="Street Dog" <?php echo (isset($breed) && $breed == 'Street Dog') ? 'selected' : ''; ?>>Street Dog</option>
        <option value="Labrador Retriever" <?php echo (isset($breed) && $breed == 'Labrador Retriever') ? 'selected' : ''; ?>>Labrador Retriever</option>
        <option value="German Shepherd" <?php echo (isset($breed) && $breed == 'German Shepherd') ? 'selected' : ''; ?>>German Shepherd</option>
        <option value="Golden Retriever" <?php echo (isset($breed) && $breed == 'Golden Retriever') ? 'selected' : ''; ?>>Golden Retriever</option>
        <option value="Bulldog" <?php echo (isset($breed) && $breed == 'Bulldog') ? 'selected' : ''; ?>>Bulldog</option>
        <option value="Beagle" <?php echo (isset($breed) && $breed == 'Beagle') ? 'selected' : ''; ?>>Beagle</option>
        <option value="Poodle" <?php echo (isset($breed) && $breed == 'Poodle') ? 'selected' : ''; ?>>Poodle</option>
        <option value="Rottweiler" <?php echo (isset($breed) && $breed == 'Rottweiler') ? 'selected' : ''; ?>>Rottweiler</option>
        <option value="Dachshund" <?php echo (isset($breed) && $breed == 'Dachshund') ? 'selected' : ''; ?>>Dachshund</option>
        <option value="Other" <?php echo (isset($breed) && $breed == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>
    <?php if (isset($err['breed'])) { ?>
        <span class="error"><?php echo $err['breed']; ?></span>
    <?php } ?>
</div>

                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" name="color" id="color" placeholder="Enter dog's color"
                            value="<?php echo isset($color) ? $color : '' ?>">
                        <?php if (isset($err['color'])) { ?>
                            <span class="error"><?php echo $err['color']; ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label for="size">Size</label>
                        <select name="size" id="size">
                            <option value="">Select size</option>
                            <option value="large">Large</option>
                            <option value="medium">Medium</option>
                            <option value="small">Small</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="male"> Male</label>
                            <label><input type="radio" name="gender" value="female"> Female</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Dog's Image</label>
                        <div class="file-input">
                            <input type="file" name="photo" accept="image/*">
                        </div>
                        <?php if (isset($err['photo'])) { ?>
                            <span class="error"><?php echo $err['photo']; ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="message" placeholder="Enter description about the dog"><?php echo isset($message) ? $message : '' ?></textarea>
                        <?php if (isset($err['message'])) { ?>
                            <span class="error"><?php echo $err['message']; ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <div class="radio-group">
                            <label><input type="radio" name="status" value="1"> Active</label>
                            <label><input type="radio" name="status" value="0" checked> Inactive</label>
                        </div>
                    </div>

                    <div class="btn-group">
                        <input type="submit" name="btnAdd" value="Add Dog">
                        <input type="reset" name="btnReset" value="Clear Form">
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</body>
</html>