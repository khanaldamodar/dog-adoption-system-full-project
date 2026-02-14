<?php
session_start();  // Start the session at the very beginning

if (isset($_POST['btnlogin'])) {
    $err = [];
    $successMessage = ""; // Initialize success message variable

    //check name
    if (isset($_POST['name']) && !empty($_POST['name']) && trim($_POST['name'])) {
        $name = $_POST['name'];
        if (!preg_match('/^[A-Za-z\s]+$/', $_POST['name'])) {
            $err['nname'] = "**Please enter a valid Name";
        }
    } else {
        $err['name'] = "**Please enter the name";
    }

    //check email
    if (isset($_POST['email']) && !empty($_POST['email']) && trim($_POST['email'])) {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err['email'] = "**Please enter a valid email";
        }
    } else {
        $err['email'] = "**Please enter the email field";
    }

    //check phone number
    if (isset($_POST['phone']) && !empty($_POST['phone']) && trim($_POST['phone'])) {
        $phone = $_POST['phone'];
        if (!preg_match('/^[9]{1}[0-9]{9}$/',$_POST['phone'] )) {
            $err['phone'] = "**Please enter a valid phone";
        }
    } else {
        $err['phone'] = "**Please enter the phone";
    }

    //check address
    if (isset($_POST['address']) && !empty($_POST['address']) && trim($_POST['address'])) {
        $address = $_POST['address'];
        if (!preg_match('/^[A-Za-z\s]+$/', $_POST['address'])) {
            $err['address'] = "**Please enter a valid  Name";
        }
    } else {
        $err['address'] = "**Please enter the address";
    }

    //check subject
    if (isset($_POST['subject']) && !empty($_POST['subject']) && trim($_POST['subject'])) {
        $subject = $_POST['subject'];
    } else {
        $err['subject'] = "**Please enter the subject";
    }

    //check message
    if (isset($_POST['message']) && !empty($_POST['message']) && trim($_POST['message'])) {
        $message = $_POST['message'];
    } else {
        $err['message'] = "**Please enter the message";
    }

    //connection with database and insert data into database
    if (count($err) == 0) {
        require_once 'connection.php';
        $query = "INSERT INTO contact (name, email, address, phone, subject, message) 
                  VALUES ('$name', '$email', '$address', '$phone', '$subject', '$message')";
        if (mysqli_query($connection, $query)) {
            $successMessage = "Form successfully submitted!"; // Success message
        } else {
            echo "Error inserting data: " . mysqli_error($connection);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Dog Adoption System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .row {
            background-image: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url("../images/paws.jpg");
            background-size: cover;
            padding: 50px 0;
        }
        
        .navbar #Contact {
            background-color: #E85C0D;
            border-radius: 25px;
            color: white;
            transition: all 0.3s ease;
        }

        .navbar #Contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(232, 92, 13, 0.3);
        }

        .location iframe {
            width: 100%;
            height: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .row .contact {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .contact-info, .contact-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .contact-info {
            flex: 1;
            min-width: 300px;
        }

        .contact-form {
            flex: 2;
            min-width: 400px;
        }

        h3, h4 {
            color: #E85C0D;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #E85C0D;
            border-radius: 2px;
        }

        .sub-info ul li, .shelter ul li {
            margin: 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icons ul {
            display: flex;
            gap: 15px;
        }

        .icons ul li {
            width: 40px;
            height: 40px;
            background: #E85C0D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .icons ul li:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(232, 92, 13, 0.3);
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .contact-form input:focus, .contact-form textarea:focus {
            border-color: #E85C0D;
            outline: none;
            box-shadow: 0 0 5px rgba(232, 92, 13, 0.3);
        }

        .contact-form .btn input {
            background: #E85C0D;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .contact-form .btn input:hover {
            background: #d04f0c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(232, 92, 13, 0.3);
        }

        .error {
            color: #ff4444;
            font-size: 12px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .row .contact {
                flex-direction: column;
            }
            
            .contact-form, .contact-info {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="homepage.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<?php include "Navbar.php"?>

    <div class="location">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3538.8412285947353!2d85.29014711189995!3d27.688048482707622!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb18b383eb68af%3A0x3a1535306dbf76a4!2sKuleshwor%20Awas!5e0!3m2!1sen!2snp!4v1694249194374!5m2!1sen!2snp" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="row">
        <div class="container">
            <div class="contact">
                <div class="contact-info">
                    <h3>How to Contact Us</h3>
                    <div class="sub-info">
                        <h4>Contact Info</h4>
                        <ul type="none">
                            <li><i class="fa-solid fa-location-pin"></i>Dog Adoption System<br>Kuleshwor, Ktm</li>
                            <li><i class="fa-solid fa-phone"></i>Phone: 00876543</li>
                            <li><i class="fa-regular fa-envelope"></i>Email: info@Dogadoptionsystem.com</li>
                        </ul>
                    </div>
                    <div class="shelter">
                        <h4>Regular Shelter Hours</h4>
                        <ul type="none">
                            <li>Monday-Friday: 12:00 pm to 6:00 pm</li>
                            <li>Sunday: 11:00 am to 4:00 pm</li>
                            <li>Saturday: Closed</li>
                        </ul>
                    </div>
                    <div class="icons">
                        <h3>Follow Us</h3>
                        <ul type="none">
                            <li><i class="fa-brands fa-facebook"></i></li>
                            <li><i class="fa-brands fa-instagram"></i></li>
                            <li><i class="fa-brands fa-twitter"></i></li>
                            <li><i class="fa-brands fa-youtube"></i></li>
                        </ul>
                    </div>
                </div>

                <div class="contact-form">
                    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                        <h3>Contact Form</h3>
                        <div class="box-one">
                            <div class="full-name">
                                <label>FULL NAME</label>
                                <input type="text" name="name" placeholder="Enter Full Name" id="name" value="<?php echo isset($name) ? $name : '' ?>">
                                <?php if (isset($err['name'])) { ?>
                                    <span class="error"><?php echo $err['name'] ?></span>
                                <?php } ?>
                            </div>
                            <div class="email">
                                <label>EMAIL</label>
                                <input type="email" name="email" placeholder="Enter Email" id="email" value="<?php echo isset($email) ? $email : '' ?>">
                                <?php if (isset($err['email'])) { ?>
                                    <span class="error"><?php echo $err['email'] ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="box-two">
                            <div class="address">
                                <label>ADDRESS</label>
                                <input type="text" name="address" placeholder="Enter address" id="address" value="<?php echo isset($address) ? $address : '' ?>">
                                <?php if (isset($err['address'])) { ?>
                                    <span class="error"><?php echo $err['address'] ?></span>
                                <?php } ?>
                            </div>
                            <div class="Mobile-Number">
                                <label>MOBILE NUMBER</label>
                                <input type="phone" name="phone" placeholder="Enter Mobile Number" id="phone" value="<?php echo isset($phone) ? $phone : '' ?>">
                                <?php if (isset($err['phone'])) { ?>
                                    <span class="error"><?php echo $err['phone'] ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="box-three">
                            <div class="subject">
                                <label>SUBJECT</label>
                                <input type="text" name="subject" placeholder="Enter The Subject Of Your Message" id="subject" value="<?php echo isset($subject) ? $subject : '' ?>">
                                <?php if (isset($err['subject'])) { ?>
                                    <span class="error"><?php echo $err['subject'] ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="box-four">
                            <label>MESSAGE</label>
                            <textarea name="message" rows="6" placeholder="Enter Your Message" id="message"><?php echo isset($message) ? $message : '' ?></textarea>
                            <?php if (isset($err['message'])) { ?>
                                <span class="error"><?php echo $err['message'] ?></span>
                            <?php } ?>
                        </div>

                        <div class="btn">
                            <input type="submit" name="btnlogin" id="btnlogin" value="SUBMIT">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>