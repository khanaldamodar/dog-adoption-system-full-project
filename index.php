<?php
session_start();  // Start the session at the very beginning


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADO~PUPS - Dog Adoption System</title> <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>

    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
}

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color:rgb(85, 127, 169);
        }


    
/* Hero Section */
.hero {
    background: url('https://images.unsplash.com/photo-1587300003388-59208cc962cb') center/cover no-repeat;
    color: white;
    text-align: left;
    padding: 8rem 2rem;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    min-height: 9vh;
    position: relative;
}

.hero-content {
    max-width: 800px;
    margin: 0;
    padding-left: 2rem;
    position: relative;
    top: -2.5rem; /* Move content upwards */
}

/* Stylish Headline */
.hero h1 {
    font-size: 3rem;
    font-family: 'Playfair Display', serif;
    margin-bottom: 0.5rem;
    margin-top: -1.5rem;
    background: linear-gradient(90deg,rgb(247, 135, 22),rgb(251, 123, 11));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent; /* Apply gradient to text */
    text-shadow: 3px 3px 6px rgba(251, 190, 8, 0.93);
    animation: fadeIn 2s ease-in-out, glow 3s infinite alternate; /* Glow effect */
}

/* Glow Animation for Headline */
@keyframes glow {
    0% {
        text-shadow: 3px 3px 6px rgba(231, 112, 21, 0.94);
    }
    100% {
        text-shadow: 3px 3px 10px rgba(236, 157, 20, 0.7);
    }
}

/* Paragraph Style */
.hero p {
    font-size: 1.3rem;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 4rem; /* Space before button */
    color: rgba(255, 255, 255, 0.9);
    animation: fadeIn 3s ease-in-out;
}

/* Call-to-Action Button */
.cta-button {
    display: inline-block;
    padding: 1.2rem 3rem;
    background: linear-gradient(45deg, #f39c12, #e67e22);
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2rem;
    border-radius: 30px;
    transition: all 0.3s ease;
    animation: bounce 2s infinite;
    margin-top: 2.5rem; /* Space below the text */
    margin-left: 2rem; /* Move button to the right */
    box-shadow: 0 4px 10px rgba(243, 156, 18, 0.4);
}

.cta-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.5);
}

/* Keyframes for Button Bounce */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

        /* Adoption Process */
        .adoption-process {
            background: #ecf0f1;
            padding: 3rem 2rem;
            text-align: center;
        }

        .process-steps {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
        }

        .step {
            flex: 1 1 200px;
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .step:hover {
            transform: translateY(-5px);
        }

        .step i {
            color: #f39c12;
            margin-bottom: 1rem;
        }

        .step h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        /* Pets Section */
        .pets-grid {
            padding: 3rem 2rem;
            background: #f9f9f9;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .pet-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .pet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .pet-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .pet-info {
            padding: 1.5rem;
            text-align: center;
        }

        .pet-name {
            font-size: 1.5rem;
            color: #34495e;
            margin-bottom: 0.5rem;
        }

        .pet-breed {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }

        .pet-age {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .pet-card .cta-button {
            margin-top: 1rem;
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            transition: background 0.3s ease;
        }

        .pet-card .cta-button:hover {
            background: #e67e22;
        }
/* Donation Section */
.donation-section {
    background: #ecf0f1;
    color: #333;
    padding: 3rem 2rem;
    text-align: center;
}

.donation-section h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: rgb(85, 127, 169);
}

.donation-options {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 2rem;
    margin-top: 2rem;
}

.donation-card {
    flex: 1 1 250px;
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, background 0.3s ease;
    text-align: center;
}

.donation-card:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.8);
}

.donation-card i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #f39c12;
}

.donation-card h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.donation-card p {
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* Button */
.donation-section .cta-button {
    display: inline-block;
    padding: 1.2rem 3rem;
    background: linear-gradient(45deg, #f39c12, #e67e22);
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2rem;
    border-radius: 30px;
    transition: all 0.3s ease;
    margin-top: 2.5rem;
    box-shadow: 0 4px 10px rgba(243, 156, 18, 0.4);
}

.donation-section .cta-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.5);
}

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        footer p {
            margin: 0.5rem 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .grid-container {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .donation-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
 <?php include "Navbar.php"?>

    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Forever Friend</h1>
            <p>Open your heart and home to a rescued companion</p>
            <div style="margin: 2rem 0;">
                <a href="Animals.php" class="cta-button">Start Adoption Journey</a>
            </div>
        </div>
    </section>

    <section id="adoption-process" class="adoption-process">
        <h2>Adoption Process</h2>
        <div class="process-steps">
            <div class="step">
                <i class="fas fa-search fa-3x"></i>
                <h3>Step 1: Explore</h3>
                <p>Discover our available pets and choose the perfect companion.</p>
            </div>
            <div class="step">
                <i class="fas fa-file-alt fa-3x"></i>
                <h3>Step 2: Apply</h3>
                <p>Complete the adoption application to get started.</p>
            </div>
            <div class="step">
                <i class="fas fa-user-check fa-3x"></i>
                <h3>Step 3: Screening</h3>
                <p>Our team reviews your application and contacts you for further steps.</p>
            </div>
            <div class="step">
                <i class="fas fa-handshake fa-3x"></i>
                <h3>Step 4: Finalize</h3>
                <p>Meet your chosen pet and complete the adoption process.</p>
            </div>
        </div>
    </section>

    
    <section id="pets" class="pets-grid">
        <h2>Available Pets</h2>
        <div class="grid-container">
            <div class="pet-card">
                <img src="OIP (1).jpg" alt="" class="pet-image">
                <div class="pet-info">
                    <h3 class="pet-name">Rocky</h3>
                    <p class="pet-breed">Golden Retriever Mix</p>
                    <p class="pet-age">9months</p>
                    <a href="Animals.php" class="cta-button">Adopt Now</a>
                </div>
            </div>
            <div class="pet-card">
                <img src="dog5.jpg" alt="Luna" class="pet-image">
                <div class="pet-info">
                    <h3 class="pet-name">Buddy</h3>
                    <p class="pet-breed">Golden Retriever</p>
                    <p class="pet-age">6 months</p>
                    <a href="Animals.php" class="cta-button">Adopt Now</a>
                </div>
            </div>
            <div class="pet-card">
                <img src="Poodle1.jpg" alt="Charlie" class="pet-image">
                <div class="pet-info">
                    <h3 class="pet-name">Charlie</h3>
                    <p class="pet-breed">Poodle</p>
                    <p class="pet-age">6 months</p>
                    <a href="Animals.php" class="cta-button">Adopt Now</a>
                </div>
            </div>
        </div>
    </section>

    <section id="donate" class="donation-section">
        <h2>Support Us</h2>
        <p>Your contributions make a difference in rescuing and rehoming dogs.</p>
        <div class="donation-options">
            <div class="donation-card">
                <i class="fas fa-dog fa-3x"></i>
                <h3>Food & Supplies</h3>
                <p>Help us provide essential items for our furry friends.</p>
            </div>
            <div class="donation-card">
                <i class="fas fa-clinic-medical fa-3x"></i>
                <h3>Medical Care</h3>
                <p>Support our efforts to provide medical care for rescued dogs.</p>
            </div>
            <div class="donation-card">
                <i class="fas fa-home fa-3x"></i>
                <h3>Shelter Support</h3>
                <p>Contribute to maintaining a safe haven for our pets.</p>
            </div>
        </div>
        <a href="donation.php" class="cta-button">Donate Now</a>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Rescue Road, Petsville</p>
                <p><i class="fas fa-phone"></i> (555) 123-4567</p>
                <p><i class="fas fa-envelope"></i> adopt@adopups.com</p>
            </div>
        </div>
    </footer>
</body>
</html>