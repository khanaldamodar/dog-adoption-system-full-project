<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Dog Adoption System</title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
        }

       
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, rgba(232,92,13,0.9), rgba(194,23,23,0.9)), 
                        url('https://images.unsplash.com/photo-1587300003388-59208cc962cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 80px;
        }

        .hero-content {
            max-width: 800px;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 3.5em;
            margin-bottom: 1.5rem;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.5em;
            margin-bottom: 2rem;
            animation: fadeInUp 1s 0.3s ease both;
        }

        .cta-button {
            display: inline-block;
            padding: 1rem 2rem;
            background: #FF6B35;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            animation: fadeInUp 1s 0.6s ease both;
        }

        .cta-button:hover {
            background: #E85C0D;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            background: #fff;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-number {
            font-size: 3em;
            color: #E85C0D;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        /* Mission Section */
        .mission {
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #fff);
            text-align: center;
        }

        .mission h2 {
            font-size: 2.5em;
            color: #C21717;
            margin-bottom: 2rem;
        }

        .mission-content {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .mission-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .mission-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .mission-text {
            text-align: left;
            font-size: 1.1em;
            line-height: 1.8;
        }

        /* Commitments Section */
        .commitments {
            padding: 6rem 2rem;
            background: #fff;
        }

        .commitment-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 3rem auto;
        }

        .commitment-card {
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .commitment-card:hover {
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }

        .commitment-icon {
            font-size: 2.5em;
            color: #E85C0D;
            margin-bottom: 1rem;
        }

        /* Footer Styles */
        .footer {
            background: #1a1a1a;
            color: white;
            padding: 4rem 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            color: #E85C0D;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #E85C0D;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-links a {
            color: white;
            font-size: 1.5em;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #E85C0D;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.5em;
            }

            .mission-content {
                grid-template-columns: 1fr;
            }

            .mission-text {
                text-align: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
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
                <li><a href="Animals.php"><i class="fas fa-paw"></i> Animals</a></li>
                <li><a href="donation.php"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search pets by breed...">
        </div>
    </header>
    <section class="hero">
        <div class="hero-content">
            <h1>Changing Lives, One Paw at a Time</h1>
            <p>Join us in our mission to provide loving homes for every dog in need</p>
            <a href="Animals.php" class="cta-button">Meet Our Dogs</a>
        </div>
    </section>

    <section class="stats">
        <div class="stat-card">
            <div class="stat-number">200+</div>
            <p>Dogs Rescued</p>
        </div>
        <div class="stat-card">
            <div class="stat-number">9</div>
            <p>Countries Reached</p>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <p>No-Kill Commitment</p>
        </div>
    </section>

    <section class="mission">
        <h2>Our Promise to Every Dog</h2>
        <div class="mission-content">
            <div class="mission-image">
                <img src="https://images.unsplash.com/photo-1554696009-9b9ca7c8c0e8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Rescued dogs">
            </div>
            <div class="mission-text">
                <p>Since 2014, Dog Adoption System has been at the forefront of canine rescue and rehabilitation. Our comprehensive approach includes:</p>
                <ul style="margin: 1.5rem 0; padding-left: 1.5rem;">
                    <li>Emergency medical care for injured dogs</li>
                    <li>Behavioral rehabilitation programs</li>
                    <li>Comprehensive adoption screening</li>
                    <li>Lifetime post-adoption support</li>
                </ul>
                <p>We believe every dog deserves a second chance, and we work tirelessly to make that happen through community education and partnership programs.</p>
            </div>
        </div>
    </section>

    <section class="commitments">
        <div class="commitment-cards">
            <div class="commitment-card">
                <i class="fas fa-heart commitment-icon"></i>
                <h3>No-Kill Philosophy</h3>
                <p>We never euthanize for space reasons and maintain a 100% placement rate for healthy animals.</p>
            </div>
            <div class="commitment-card">
                <i class="fas fa-shield-alt commitment-icon"></i>
                <h3>Lifetime Protection</h3>
                <p>Every adopted dog has a permanent safety net with our open-return policy.</p>
            </div>
            <div class="commitment-card">
                <i class="fas fa-stethoscope commitment-icon"></i>
                <h3>Full Medical Care</h3>
                <p>Comprehensive veterinary care including spay/neuter, vaccinations, and rehabilitation.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Rescue Road, Kuleshwor</p>
                <p><i class="fas fa-phone"></i> (977) 123-4567</p>
                <p><i class="fas fa-envelope"></i> rescue@dogadoptionsystem.com</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="Animals.php">Available Dogs</a></li>
                    <li><a href="Donate.php">Support Us</a></li>
                    <li><a href="Volunteer.php">Volunteer</a></li>
                    <li><a href="Contact.php">Schedule Visit</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Our Promise</h3>
                <p>We commit to transparency in all our operations. 100% of donations go directly to animal care.</p>
                <img src="DogDash.png" alt="Logo" style="width: 100px; margin-top: 1rem;">
            </div>
        </div>
    </footer>

    <script>
        // Add scroll animation functionality
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        document.querySelectorAll('.stat-card, .commitment-card, .mission-content').forEach((el) => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>