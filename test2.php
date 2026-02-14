<?php
session_start();

$host = "localhost";
$dbname = "dog-adoption-project";
$username = "root";
$password = "";

try {
    // Database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

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

} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage()));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADO-PUPS</title>
    <script src="homepage.js"></script>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your CSS styles here */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f6fa;
        }

        header {
            background: linear-gradient(90deg, #2c3e50, #34495e);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            color: #f39c12;
        }

        .logo .dog-icon {
            font-size: 36px;
            margin-right: 8px;
        }

        .logo .main-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo .sub-text {
            font-size: 12px;
            margin-left: 5px;
            color: #ecf0f1;
            font-style: italic;
        }

        .menu-toggle {
            display: none;
            cursor: pointer;
            color: #ecf0f1;
            font-size: 24px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 18px;
        }

        nav ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        nav ul li a i {
            margin-right: 6px;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #f39c12;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #ecf0f1;
            padding: 6px 10px;
            border-radius: 8px;
            margin-left: 15px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
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

        .search-bar:hover {
            background: #d5dbdb;
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
                <li><a href="advance.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="About-us.php"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="Animals.php"><i class="fas fa-paw"></i> Animals</a></li>
                <li><a href="donation"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                <li><a href="notification.php"><i class="fas fa-bell"></i>Notification</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search pets by breed...">
        </div>
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
                                        <button class="view-details">
                                            <i class="fas fa-eye"></i> View Details
                                        </button>
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
    </script>
</body>
</html>
<div class="slider_area">
        <div class="single_slider slider_bg_1 d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="slider_text">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="dog_thumb d-none d-lg-block">
                <img src="dog.png" alt="dog">
            </div>
        </div>
    </div>
    <!-- slider_area_end -->
    <div class="section-one">
        <h1>MEET OUR ADOPTABLE PETS</h1>
        <div class="description">
           <p>If you are looking for a new family member—dog, or puppy—you’ve come to the right place. We have a 
            good<br> selection of sizes, breed mixes, and ages among our homeless pets who are waiting patiently for you to adopt 
            and bring<br> them home.</p>
        </div>
            <div class="images">
                <div class="image-item">
                <img src="dog1.jpg" alt="image 1"></div>
                <div class="image-item">
                <img src="dog4.jpg" alt="image2"></div>
                <div class="image-item">
                <img src="dog5.jpg" alt="image3"></div>
                <div class="image-item">
                <img src="dog3.jpg" alt="image4"></div>
                <div class="image-item">
                    <img src="dog2.jpg" alt="image5">
                </div>
            </div>
            <button class="button"><a href="Animals.php">Meet All Of Our Rescuses</a></button>
    </div>
<div class="footer">
    <div class="column-one">
        <img src="DogDash.png" alt="logo"><p>Dog Adoption System</p><br>
        <p>
            Dog Adoption System focuses on<br> saving at-risk dogs in pound<br> facilities. We save homeless dogss, <br>give them medical care and a <br>safe temporary home, 
            and<br> provide responsible adoption<br> services to those seeking dogs.
    <p>&copy; 2024 Dog Adoption System. All Rights Reserved.</p>
            
        </p>
    </div>
    <div class="column-two">
      <h2>Featured Pets</h2> 
        <div class="card">
            <div class="image">
             <a href="Animals.php">  <img src="dog4.jpg" alt="one"></a> 
            </div>
           <div class="description">
           cherry<br>
            Golden Retriver<br>
            Adult Feamle/
            Medium
           </div>
        </div>
        <div class="card">
            <div class="image">
              <a href="Animals.php"><img src="dog6.jpg" alt="one"></a>  
            </div>
            <div class="description">
                coco<br>
               labrador<br>
                Baby Feamle/
                Medium
            </div>
        </div>
        <div class="card">
            <div class="image">
               <a href="Animals.php"><img src="dog3.jpg" alt="one"></a> 
            </div>
            <div class="description">
                Reo<br>
                Terrier<br>
                Adult Male/
                Medium
            </div>
        </div>
    </div>
    <div class="column-three">
        <h2>Contact</h2>
        <div class="contact-info">
        <p>Dog Adoption System<br>Kuleshwor,ktm</p>
        <p>Monady-Friday:12:00 pm to 6:00 pm<br>Sunday:11:00 am to 4:00 pm<br>Saturday:Closed</p>
        <p>269-492-1010<br>info@Dogadoptionsystem.com</p>
        <ul type="none">
            <a href="https://www.facebook.com/"><li><i class="fa-brands fa-facebook"></i></li></a>
           <a href="https://www.instagram.com/"> <li><i class="fa-brands fa-instagram"></i></li></a>
           <a href="https://twitter.com/"><li><i class="fa-brands fa-twitter"></i></li></a> 
           <a href="https://www.youtube.com/"> <li><i class="fa-brands fa-youtube"></i></li></a>
        </ul>
    </div>
    </div>
    </div><div class="right" >
    <p text-align="centre">&copy; 2024 Dog Adoption System. All Rights Reserved.</p></div>
            
    
</body>
</html>


.main-page {
            /* width: 100%; */
            /* Set the width of the div */
            height: 100%;
            /* Set the height of the div */
            background-image: url('d1.jpg');
            /* Path to the image */
            background-size: cover;
            /* Ensures the image covers the entire div */
            background-position: center;
            /* Centers the image inside the div */
        }.slider_area {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .slider_bg_1 {
            background-image: url(banner.png);
            background-size: cover;
            background-position: center;
            height: 80%;
            display: flex;
            align-items: center;
        }

        .dog_thumb {
    position: relative;
    top: 80px;
    margin-left: -30px; /* Further shift the dog image to the left */
}
.dog_thumb img {
    max-width: 50%; /* Reduced from 100% to 80% to make the image smaller */
    height: 10%; /* Reduced height for smaller image */
    transform: translateX(350px); /* Keeps the image position the same */
    margin-left:500px; /* Keeps the left margin */
}
.main-page .main p{
    position: absolute;
    top: 50%;
    left: 10%;
    font-size:30px ;
    width: 120px;
    animation: typing 15s steps(80,end), blink .5s step-end infinite alternate;
    animation-fill-mode: backwards;
    white-space: nowrap;
    overflow: hidden;
    color: #f9ca24;
  
  }
  
  @keyframes typing {
  from {
    width:0%
  }
  to{
      width:100%;
  }
  }
    
  @keyframes blink {
  50% {
    border-color: transparent
  }
  }

.section-one {
  background-image: url("../images/paws.jpg");
  padding-top:30px ;
  padding-bottom: 30px;

}
.section-one h1{
  text-align: center;
  color: #C21717;
  font-size: 20px;
 
}

.section-one .description p{
  margin-top: 30px;
  text-align: center;
}

.section-one .images {
  display: flex;
  margin: 0px 0px 0px 0px 0px ;
  
}
.section-one .images{
  display: flex;
  justify-content:center;
  align-items: center;
}
.section-one .images .image-item img{
width: 95%;
height: 120px;
margin-top: 40px;
border-radius: 20px;

}

.section-one .button{
  height: 40px;
  width: 250px;
  border-radius: 15px;
  background-color:  #C21717;
  margin-left: 700px;
  margin-top: 40px;
  border: none;
}
.section-one .button a{
  text-decoration: none;
  color:#000;
}
.section-one .button:hover{
  background-color:#E85C0D;
  transition: 0.5s ease-in-out;
}


.footer{
  background-color:black;
  display: flex;
  justify-content:space-evenly;
  padding-top: 40px;
}

.footer .column-one img{
  height: 80px;
  /* padding-top: 30px; */
  
}

.footer .column-one p{
   color: white; 
 
}
.footer .column-two{
  /* display: flex; */

}
.footer .column-two h2{
  color: #C21717;
  font-size: 20px;
  width: 46%;
  border-bottom: 1px solid #D9D9D9;
  margin-bottom: 20px;
  padding-bottom: 5px;
}

.footer .column-two .card{
  display: flex;
  background-color: #D9D9D9;
  padding: 5px;
  margin-bottom: 20px;
}
.footer .column-two .card .image img{
  height: 60px;
  width: 80px;
  border-radius: 5px;
}
.footer .column-two .description{
  color: #000;
  padding-left: 20px;
}
.footer .column-three h2{
  color: #C21717;
  font-size: 20px;
  width: 25%;
  border-bottom: 1px solid #D9D9D9;
  margin-bottom: 20px;
  padding-bottom: 5px;
  
 

}
.footer .column-three p{
  color: black;
}

.footer .column-three .contact-info {
  background-color:#D9D9D9;
  margin-bottom: 30px;

}

.footer .column-three .contact-info p{
  width: 300px;
  text-align: left;
  padding-top: 35px;
  padding-bottom: 0px;
  padding-left: 10px;
}
.footer .column-three .contact-info ul li {
  display: inline-block;

}
.footer .column-three .contact-info ul li i{
 font-size: 40px;
 margin-left: 20px;
 margin-top: 25px;
 padding-bottom: 10px;
}
.footer .column-three .contact-info ul li i:hover,.footer .column-three .contact-info ul li i:active{
  font-size: 50px;

}

.right{
  text-align: center;
  text-decoration: none;
}