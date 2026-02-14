<?php
require_once 'check_admin_login.php'; // Ensure you have a login check
require_once 'connection.php'; // Make sure your DB connection is correct

// Ensure the ID is numeric
if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header('Location: list_dogs_categories.php?msg=1');
    exit;
}

$photo = '';
$err = [];

if (isset($_POST['btnUpdate'])) {
    // Validate form data
    if (empty($_POST['name'])) {
        $err['name'] = 'Please enter name';
    } else {
        $name = $_POST['name'];
    }

    if (empty($_POST['age'])) {
        $err['age'] = 'Please enter age';
    } else {
        $age = $_POST['age'];
    }

    if (empty($_POST['color'])) {
        $err['color'] = 'Please enter color';
    } else {
        $color = $_POST['color'];
    }

    if (empty($_POST['breed'])) {
        $err['breed'] = 'Please enter breed';
    } else {
        $breed = $_POST['breed'];
    }

    if (empty($_POST['size'])) {
        $err['size'] = 'Please enter size';
    } else {
        $size = $_POST['size'];
    }

    if (empty($_POST['message'])) {
        $err['message'] = 'Please enter the message';
    } else {
        $message = $_POST['message'];
    }

    // Check photo upload
    if ($_FILES['photo']['error'] == 0) {
        if ($_FILES['photo']['size'] <= 1000000) {
            $allowedFormats = ['image/png', 'image/jpeg'];
            if (in_array($_FILES['photo']['type'], $allowedFormats)) {
                $photoName = uniqid() . '_' . $_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], 'image/' . $photoName);
                $photo = $photoName;
            } else {
                $err['photo'] = 'Select a valid image format (png, jpeg)';
            }
        } else {
            $err['photo'] = 'Select a valid image size (less than 1MB)';
        }
    }

    $gender = $_POST['gender'];
    $status = $_POST['status'];

    // If no errors, update the database
    if (count($err) == 0) {
        // Prepare the update query
        $stmt = $connection->prepare("UPDATE dogs SET name = ?, age = ?, color = ?, breed = ?, size = ?, gender = ?, status = ?, message = ?, image = ? WHERE id = ?");
        $stmt->bind_param('sssssssssi', $name, $age, $color, $breed, $size, $gender, $status, $message, $photo, $id);

        if ($stmt->execute()) {
            $success = 'Category updated successfully!';
        } else {
            $error = 'Category update failed';
        }
    }
}

// Retrieve current dog data based on the correct ID
$stmt = $connection->prepare("SELECT * FROM dogs WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dog Category Edit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f6f9;
            min-height: 100vh;
            padding: 2rem;
        }

        .wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        fieldset {
            border: none;
            padding: 0;
        }

        legend {
            font-size: 1.5rem;
            color: #3498db;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 500;
        }

        input[type="text"], select, textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        input[type="radio"] {
            margin-right: 0.5rem;
        }

        input[type="file"] {
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 8px;
            width: 100%;
        }

        input[type="submit"], input[type="reset"] {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"] {
            background: #3498db;
            color: white;
            margin-right: 1rem;
        }

        input[type="reset"] {
            background: #e74c3c;
            color: white;
        }

        input[type="submit"]:hover {
            background: #2980b9;
        }

        input[type="reset"]:hover {
            background: #c0392b;
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .error_message {
            background: #ffebee;
            color: #c62828;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .success_message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2><i class="fas fa-paw"></i> Edit Dog Category</h2>

        <form action="" method="post" id="category_form" enctype="multipart/form-data">
            <fieldset>
                <?php if (isset($error)) { ?>
                    <p class="error_message"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                <?php } ?>
                <?php if (isset($success)) { ?>
                    <p class="success_message"><i class="fas fa-check-circle"></i> <?php echo $success; ?></p>
                <?php } ?>

                <legend><i class="fas fa-edit"></i> Edit Dog Information</legend>

                <div class="form-group">
                    <label for="name"><i class="fas fa-dog"></i> Dog Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter name" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">
                    <?php if (isset($err['name'])) { ?>
                        <span class="error"><?php echo $err['name']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="age"><i class="fas fa-birthday-cake"></i> Age</label>
                    <input type="text" name="age" id="age" placeholder="Enter age" value="<?php echo isset($row['age']) ? $row['age'] : ''; ?>">
                    <?php if (isset($err['age'])) { ?>
                        <span class="error"><?php echo $err['age']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="breed"><i class="fas fa-paw"></i> Breed</label>
                    <input type="text" name="breed" id="breed" placeholder="Enter breed" value="<?php echo isset($row['breed']) ? $row['breed'] : ''; ?>">
                    <?php if (isset($err['breed'])) { ?>
                        <span class="error"><?php echo $err['breed']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="color"><i class="fas fa-palette"></i> Color</label>
                    <input type="text" name="color" id="color" placeholder="Enter color" value="<?php echo isset($row['color']) ? $row['color'] : ''; ?>">
                    <?php if (isset($err['color'])) { ?>
                        <span class="error"><?php echo $err['color']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label for="size"><i class="fas fa-ruler"></i> Size</label>
                    <select name="size" id="size">
                        <option value="large" <?php echo isset($row['size']) && $row['size'] == 'large' ? 'selected' : ''; ?>>Large</option>
                        <option value="medium" <?php echo isset($row['size']) && $row['size'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="small" <?php echo isset($row['size']) && $row['size'] == 'small' ? 'selected' : ''; ?>>Small</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="male" <?php echo isset($row['gender']) && $row['gender'] == 'male' ? 'checked' : ''; ?>> Male</label>
                        <label><input type="radio" name="gender" value="female" <?php echo isset($row['gender']) && $row['gender'] == 'female' ? 'checked' : ''; ?>> Female</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message"><i class="fas fa-comment"></i> Dog's Message</label>
                    <textarea name="message" placeholder="Type Message"><?php echo isset($row['message']) ? $row['message'] : ''; ?></textarea>
                    <?php if (isset($err['message'])) { ?>
                        <span class="error"><?php echo $err['message']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Status</label>
                    <div class="radio-group">
                        <label><input type="radio" name="status" value="1" <?php echo isset($row['status']) && $row['status'] == 1 ? 'checked' : ''; ?>> Active</label>
                        <label><input type="radio" name="status" value="0" <?php echo isset($row['status']) && $row['status'] == 0 ? 'checked' : ''; ?>> Deactive</label>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-image"></i> Dog's Image</label>
                    <input type="file" name="photo">
                    <?php if (isset($err['photo'])) { ?>
                        <span class="error"><?php echo $err['photo']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group" style="text-align: center;">
                    <input type="submit" name="btnUpdate" value="Update">
                    <input type="reset" name="btnReset" value="Clear">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
