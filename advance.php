

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADO-PUPS</title>
    <script src="homepage.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your CSS styles here */
       




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
    </style>
</head>
<body>
   <?php include "Navbar.php" ?>
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

    
</body>
</html>