

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
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f5f6fa;
        }

        
        
        .main-page {
            margin-top: 110px;
            min-height: 400px;
            background: url('hero-bg.jpg') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 60px 20px;
        }

        .section-one {
            padding: 80px 0;
            background-color: white;
        }

        .section-one h1 {
            text-align: center;
            color: #C21717;
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        .description {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            line-height: 1.8;
            color: #555;
        }

        .images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .image-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .image-item img:hover {
            transform: scale(1.05);
        }

        .button {
    display: block;
    width: 250px;
    margin: 40px auto;
    padding: 15px 30px;
    background-color: #C21717;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.button::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(135deg, #E85C0D, #C21717);
    border-radius: 25px;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.button:hover::before {
    opacity: 1;
}

.button a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    position: relative;
    z-index: 1;
}

        .footer {
            background-color: #1a1a1a;
            color: white;
            padding: 60px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .footer h2 {
            color: #E85C0D;
            font-size: 1.5em;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .contact-info {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            color: white;
            font-size: 24px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #E85C0D;
        }

        .right {
            text-align: center;
            padding: 20px;
            background-color: #111;
            color: #777;
        }

        @media (max-width: 768px) {
            .links {
                display: none;
            }

            .navbar {
                justify-content: center;
            }

            .footer {
                grid-template-columns: 1fr;
            }
        }

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
    </style>
</head>
<body>
<div class="header">
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
                 <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                <li><a href="About-us.php"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="Animals.php"><i class="fas fa-paw"></i> Animals</a></li>
                <li><a href="donation.php"><i class="fas fa-hand-holding-heart"></i> Donate</a></li>
                <li><a href="register.php"><i class="fa fa-user-plus" aria-hidden="true"></i>Register</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search pets by breed...">
        </div>
    </header>
    </div>
    <!-- slider_area_start -->
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
    <p>&copy; 2024 Dog Adoption System. All Rights Reserved.</p>
            
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
    <p text-align="centre">&copy; 2024 Dog Adoption System. All Rights Reserved.</p></div>
            
    
</body>
</html>
